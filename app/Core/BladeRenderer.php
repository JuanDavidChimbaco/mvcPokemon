<?php

namespace Core;

use RuntimeException;

class BladeRenderer
{
    private const COMPILER_VERSION = '2';

    public function render(string $view, array $data = []): string
    {
        return $this->renderView($view, $data, []);
    }

    private function renderView(string $view, array $data, array $sections): string
    {
        $view = str_replace('.', '/', $view);
        $path = APP_ROOT . '/resources/views/' . $view . '.blade.php';
        if (!is_file($path)) {
            throw new RuntimeException("View not found: {$view}");
        }

        $source = file_get_contents($path);
        if ($source === false) {
            throw new RuntimeException("Unable to read view: {$view}");
        }

        $source = preg_replace_callback(
            '/@section\(\s*([\'"])([^\'"]+)\1\s*\)(.*?)@endsection/s',
            function (array $matches) use ($data, &$sections): string {
                $sections[$matches[2]] = $this->evaluate($matches[3], $data, $sections);
                return '';
            },
            $source
        );

        $source = preg_replace_callback(
            '/@section\(\s*([\'"])([^\'"]+)\1\s*,\s*([^\r\n]*?)\s*\)/',
            static function (array $matches) use ($data, &$sections): string {
                $sections[$matches[2]] = self::evaluateSectionExpression($matches[3], $data);
                return '';
            },
            $source
        );

        if (preg_match('/@extends\(\s*([\'"])([^\'"]+)\1\s*\)/', $source, $matches)) {
            $parent = $matches[2];
            return $this->renderView($parent, $data, $sections);
        }

        return $this->evaluate($source, $data, $sections);
    }

    private static function evaluateSectionExpression(string $expression, array $data): string
    {
        extract($data, EXTR_SKIP);
        $value = eval('return ' . $expression . ';');
        return htmlspecialchars((string)$value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }

    private function evaluate(string $source, array $data, array $sections): string
    {
        $compiled = $this->compile($source);
        $cachePath = APP_ROOT . '/storage/cache/views/' . hash(
            'sha256',
            self::COMPILER_VERSION . $compiled
        ) . '.php';
        $cacheDirectory = dirname($cachePath);

        if (!is_dir($cacheDirectory) && !mkdir($cacheDirectory, 0775, true) && !is_dir($cacheDirectory)) {
            throw new RuntimeException("Unable to create compiled view directory: {$cacheDirectory}");
        }
        if (!is_file($cachePath) && file_put_contents($cachePath, $compiled, LOCK_EX) === false) {
            throw new RuntimeException("Unable to write compiled view: {$cachePath}");
        }

        extract($data, EXTR_SKIP);
        $__sections = $sections;
        ob_start();
        try {
            include $cachePath;
            return ob_get_clean();
        } catch (\Throwable $exception) {
            ob_end_clean();
            throw $exception;
        }
    }

    private function compile(string $source): string
    {
        $source = preg_replace('/@extends\(\s*([\'"])[^\'"]+\1\s*\)/', '', $source);
        $source = preg_replace_callback('/\{\{\s*(.*?)\s*\}\}/s', static function (array $matches): string {
            return '<?= htmlspecialchars((string)(' . $matches[1] . ' ?? \'\'), ENT_QUOTES | ENT_SUBSTITUTE, \'UTF-8\') ?>';
        }, $source);

        $source = $this->replaceDirective($source, 'foreach', static fn(string $expression): string => "<?php foreach ({$expression}): ?>");
        $source = $this->replaceDirective($source, 'for', static fn(string $expression): string => "<?php for ({$expression}): ?>");
        $source = $this->replaceDirective($source, 'if', static fn(string $expression): string => "<?php if ({$expression}): ?>");
        $source = $this->replaceDirective($source, 'include', function (string $expression): string {
            $arguments = $this->splitArguments($expression);
            $view = array_shift($arguments) ?? "''";
            $data = isset($arguments[0])
                ? 'array_merge(get_defined_vars(), ' . $arguments[0] . ')'
                : 'get_defined_vars()';
            return "<?= view({$view}, {$data}) ?>";
        });
        $source = $this->replaceDirective($source, 'yield', static function (string $expression): string {
            $arguments = self::splitArguments($expression);
            $section = $arguments[0] ?? "''";
            $default = $arguments[1] ?? "''";
            return "<?= \$__sections[{$section}] ?? {$default} ?>";
        });
        $source = $this->replaceDirective($source, 'asset', static fn(string $expression): string =>
            "<?= htmlspecialchars(asset({$expression}), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>"
        );
        $source = $this->replaceDirective($source, 'vite', static fn(string $expression): string => "<?= vite({$expression}) ?>");

        $source = str_replace(
            ['@else', '@endif', '@endforeach'],
            ['<?php else: ?>', '<?php endif; ?>', '<?php endforeach; ?>'],
            $source
        );
        return str_replace('@endfor', '<?php endfor; ?>', $source);
    }

    private function replaceDirective(string $source, string $name, callable $replace): string
    {
        $needle = '@' . $name . '(';
        $offset = 0;
        while (($start = strpos($source, $needle, $offset)) !== false) {
            $argumentStart = $start + strlen($needle);
            $depth = 1;
            $quote = null;
            $escaped = false;
            $length = strlen($source);

            for ($index = $argumentStart; $index < $length; $index++) {
                $character = $source[$index];
                if ($quote !== null) {
                    if ($escaped) {
                        $escaped = false;
                    } elseif ($character === '\\') {
                        $escaped = true;
                    } elseif ($character === $quote) {
                        $quote = null;
                    }
                    continue;
                }

                if ($character === "'" || $character === '"') {
                    $quote = $character;
                } elseif ($character === '(') {
                    $depth++;
                } elseif ($character === ')' && --$depth === 0) {
                    $expression = substr($source, $argumentStart, $index - $argumentStart);
                    $replacement = $replace(trim($expression));
                    $source = substr_replace($source, $replacement, $start, $index - $start + 1);
                    $offset = $start + strlen($replacement);
                    continue 2;
                }
            }

            $offset = $argumentStart;
        }

        return $source;
    }

    private static function splitArguments(string $expression): array
    {
        $arguments = [];
        $start = 0;
        $depth = 0;
        $quote = null;
        $escaped = false;

        for ($index = 0, $length = strlen($expression); $index < $length; $index++) {
            $character = $expression[$index];
            if ($quote !== null) {
                if ($escaped) {
                    $escaped = false;
                } elseif ($character === '\\') {
                    $escaped = true;
                } elseif ($character === $quote) {
                    $quote = null;
                }
                continue;
            }

            if ($character === "'" || $character === '"') {
                $quote = $character;
            } elseif ($character === '(' || $character === '[') {
                $depth++;
            } elseif ($character === ')' || $character === ']') {
                $depth--;
            } elseif ($character === ',' && $depth === 0) {
                $arguments[] = trim(substr($expression, $start, $index - $start));
                $start = $index + 1;
            }
        }

        $arguments[] = trim(substr($expression, $start));
        return $arguments;
    }
}
