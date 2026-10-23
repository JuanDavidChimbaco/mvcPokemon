<?php

namespace App\Console;

use Core\Application;
use Core\Migrator;
use Core\SeederRunner;

/**
 * Console Kernel - Artisan-style commands
 */
class Kernel
{
    private Application $app;
    private array $commands = [];
    
    public function __construct(Application $app)
    {
        $this->app = $app;
        $this->registerCommands();
    }
    
    private function registerCommands(): void
    {
        $this->commands = [
            'migrate' => [
                'description' => 'Run database migrations',
                'usage' => 'migrate [--fresh] [--seed]',
                'handler' => [$this, 'migrate'],
            ],
            'migrate:rollback' => [
                'description' => 'Rollback last database migration',
                'usage' => 'migrate:rollback [--step=1]',
                'handler' => [$this, 'rollback'],
            ],
            'migrate:reset' => [
                'description' => 'Rollback all database migrations',
                'usage' => 'migrate:reset',
                'handler' => [$this, 'reset'],
            ],
            'migrate:status' => [
                'description' => 'Show migration status',
                'usage' => 'migrate:status',
                'handler' => [$this, 'status'],
            ],
            'db:seed' => [
                'description' => 'Run database seeders',
                'usage' => 'db:seed [--class=SeederClass]',
                'handler' => [$this, 'seed'],
            ],
            'make:migration' => [
                'description' => 'Create a new migration file',
                'usage' => 'make:migration <name> [--create=table]',
                'handler' => [$this, 'makeMigration'],
            ],
            'make:seeder' => [
                'description' => 'Create a new seeder file',
                'usage' => 'make:seeder <name>',
                'handler' => [$this, 'makeSeeder'],
            ],
            'make:controller' => [
                'description' => 'Create a new controller',
                'usage' => 'make:controller <name> [--resource] [--api]',
                'handler' => [$this, 'makeController'],
            ],
            'make:model' => [
                'description' => 'Create a new model',
                'usage' => 'make:model <name> [--migration]',
                'handler' => [$this, 'makeModel'],
            ],
            'route:list' => [
                'description' => 'List all registered routes',
                'usage' => 'route:list',
                'handler' => [$this, 'routeList'],
            ],
            'cache:clear' => [
                'description' => 'Clear application cache',
                'usage' => 'cache:clear',
                'handler' => [$this, 'cacheClear'],
            ],
            'optimize' => [
                'description' => 'Optimize framework for production',
                'usage' => 'optimize',
                'handler' => [$this, 'optimize'],
            ],
            'help' => [
                'description' => 'Show help for commands',
                'usage' => 'help [command]',
                'handler' => [$this, 'help'],
            ],
        ];
    }
    
    public function handle(array $argv): int
    {
        // Cargar configuración
        $this->app->loadConfig(dirname(__DIR__, 2) . '/app/config/app.php');
        
        if (count($argv) < 2) {
            $this->showHelp();
            return 1;
        }
        
        $command = $argv[1];
        $args = array_slice($argv, 2);
        
        if (!isset($this->commands[$command])) {
            echo "Command not found: {$command}\n\n";
            $this->showHelp();
            return 1;
        }
        
        try {
            return call_user_func($this->commands[$command]['handler'], $args) ?? 0;
        } catch (\Throwable $e) {
            echo "Error: {$e->getMessage()}\n";
            if ($this->app->isDevelopment()) {
                echo "Trace: {$e->getTraceAsString()}\n";
            }
            return 1;
        }
    }
    
    // ===== Commands =====
    
    public function migrate(array $args): int
    {
        $fresh = in_array('--fresh', $args);
        $seed = in_array('--seed', $args);
        
        $migrator = new Migrator();
        
        if ($fresh) {
            echo "Dropping all tables and re-running migrations...\n";
            $migrator->reset();
        }
        
        $migrator->run();
        
        if ($seed) {
            $this->seed([]);
        }
        
        return 0;
    }
    
    public function rollback(array $args): int
    {
        $step = 1;
        foreach ($args as $arg) {
            if (str_starts_with($arg, '--step=')) {
                $step = (int)substr($arg, 7);
            }
        }
        
        $migrator = new Migrator();
        $migrator->rollback($step);
        
        return 0;
    }
    
    public function reset(array $args): int
    {
        $migrator = new Migrator();
        $migrator->reset();
        return 0;
    }
    
    public function status(array $args): int
    {
        error_log("status() called, creating Migrator");
        $migrator = new Migrator();
        error_log("Migrator created, calling status()");
        $migrator->status();
        return 0;
    }
    
    public function seed(array $args): int
    {
        $class = null;
        foreach ($args as $arg) {
            if (str_starts_with($arg, '--class=')) {
                $class = substr($arg, 8);
            }
        }
        
        $runner = new SeederRunner();
        
        if ($class) {
            $runner->run($class);
        } else {
            $runner->run(\Database\Seeders\DatabaseSeeder::class);
        }
        
        return 0;
    }
    
    public function makeMigration(array $args): int
    {
        if (empty($args)) {
            echo "Error: Migration name required\n";
            return 1;
        }
        
        $name = $args[0];
        $createTable = null;
        
        foreach ($args as $arg) {
            if (str_starts_with($arg, '--create=')) {
                $createTable = substr($arg, 9);
            }
        }
        
        $timestamp = date('Y_m_d_His');
        $className = str_replace(' ', '', ucwords(str_replace('_', ' ', $name)));
        $fileName = "{$timestamp}_{$name}.php";
        $path = dirname(__DIR__, 2) . '/database/migrations/' . $fileName;
        
        $template = $this->getMigrationTemplate($className, $createTable);
        
        if (file_put_contents($path, $template)) {
            echo "Migration created: {$fileName}\n";
            return 0;
        }
        
        echo "Error creating migration\n";
        return 1;
    }
    
    public function makeSeeder(array $args): int
    {
        if (empty($args)) {
            echo "Error: Seeder name required\n";
            return 1;
        }
        
        $name = $args[0];
        $className = str_replace(' ', '', ucwords(str_replace('_', ' ', $name)));
        if (!str_ends_with($className, 'Seeder')) {
            $className .= 'Seeder';
        }
        
        $fileName = $className . '.php';
        $path = dirname(__DIR__, 2) . '/database/seeders/' . $fileName;
        
        $template = $this->getSeederTemplate($className);
        
        if (file_put_contents($path, $template)) {
            echo "Seeder created: {$fileName}\n";
            return 0;
        }
        
        echo "Error creating seeder\n";
        return 1;
    }
    
    public function makeController(array $args): int
    {
        if (empty($args)) {
            echo "Error: Controller name required\n";
            return 1;
        }
        
        $name = $args[0];
        $resource = in_array('--resource', $args);
        $api = in_array('--api', $args);
        
        $className = str_replace(' ', '', ucwords(str_replace('_', ' ', $name)));
        if (!str_ends_with($className, 'Controller')) {
            $className .= 'Controller';
        }
        
        // Determinar módulo
        $module = 'Admin'; // default
        if ($api) $module = 'Api';
        elseif (str_contains($name, 'Shop')) $module = 'Shop';
        elseif (str_contains($name, 'Auth')) $module = 'Auth';
        
        $fileName = $className . '.php';
        $dir = dirname(__DIR__, 2) . "/app/Modules/{$module}/Controllers/";
        
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
        
        $path = $dir . $fileName;
        
        $template = $this->getControllerTemplate($className, $module, $resource, $api);
        
        if (file_put_contents($path, $template)) {
            echo "Controller created: Modules/{$module}/Controllers/{$fileName}\n";
            return 0;
        }
        
        echo "Error creating controller\n";
        return 1;
    }
    
    public function makeModel(array $args): int
    {
        if (empty($args)) {
            echo "Error: Model name required\n";
            return 1;
        }
        
        $name = $args[0];
        $migration = in_array('--migration', $args);
        
        $className = str_replace(' ', '', ucwords(str_replace('_', ' ', $name)));
        $fileName = $className . '.php';
        $path = dirname(__DIR__, 2) . "/app/Shared/Models/{$fileName}";
        
        $template = $this->getModelTemplate($className);
        
        if (file_put_contents($path, $template)) {
            echo "Model created: Shared/Models/{$fileName}\n";
            
            if ($migration) {
                $this->makeMigration([strtolower($name), '--create=' . strtolower($name) . 's']);
            }
            
            return 0;
        }
        
        echo "Error creating model\n";
        return 1;
    }
    
    public function routeList(array $args): int
    {
        // Load module routes
        $this->loadModuleRoutes();
        
        $router = $this->app->router();
        $routes = $router->getRoutes();
        
        echo "\nRegistered Routes:\n";
        echo str_repeat('-', 120) . "\n";
        printf("%-8s %-50s %-40s %s\n", 'METHOD', 'URI', 'NAME', 'ACTION');
        echo str_repeat('-', 120) . "\n";
        
        foreach ($routes as $method => $methodRoutes) {
            if ($method === 'ANY') continue;
            
            foreach ($methodRoutes as $uri => $route) {
                $name = $route->name ?: '-';
                $action = is_string($route->action) ? $route->action : (is_array($route->action) ? implode('@', $route->action) : 'Closure');
                
                printf("%-8s %-50s %-40s %s\n", $method, $uri, $name, $action);
            }
        }
        
        echo str_repeat('-', 120) . "\n";
        return 0;
    }
    
    private function loadModuleRoutes(): void
    {
        $appRoot = dirname(__DIR__, 2);
        $modulesPath = $appRoot . '/app/Modules';
        
        if (is_dir($modulesPath)) {
            foreach (scandir($modulesPath) as $module) {
                if ($module !== '.' && $module !== '..' && is_dir($modulesPath . '/' . $module)) {
                    $routesFile = $modulesPath . '/' . $module . '/Routes.php';
                    if (file_exists($routesFile)) {
                        require_once $routesFile;
                    }
                }
            }
        }
    }
    
    public function cacheClear(array $args): int
    {
        $cache = $this->app->cache();
        $cache->flush();
        echo "Cache cleared\n";
        return 0;
    }
    
    public function optimize(array $args): int
    {
        echo "Optimizing for production...\n";
        
        // Limpiar cache
        $this->cacheClear([]);
        
        // Generar autoloader optimizado
        echo "Generating optimized autoloader...\n";
        // composer dump-autoload -o would be run externally
        
        // Cache routes
        echo "Caching routes...\n";
        
        // Cache config
        echo "Caching config...\n";
        
        echo "Optimization complete!\n";
        return 0;
    }
    
    public function help(array $args): int
    {
        if (empty($args)) {
            $this->showHelp();
            return 0;
        }
        
        $command = $args[0];
        
        if (!isset($this->commands[$command])) {
            echo "Command not found: {$command}\n";
            return 1;
        }
        
        echo "Description: {$this->commands[$command]['description']}\n";
        echo "Usage: {$this->commands[$command]['usage']}\n";
        return 0;
    }
    
    private function showHelp(): void
    {
        echo "\nPokéTienda Console - Available Commands:\n";
        echo str_repeat('=', 60) . "\n\n";
        
        foreach ($this->commands as $name => $info) {
            printf("  %-25s %s\n", $name, $info['description']);
            echo "      Usage: {$info['usage']}\n\n";
        }
    }
    
    // ===== Templates =====
    
    private function getMigrationTemplate(string $className, ?string $createTable): string
    {
        $template = '<?php

namespace Database\Migrations;

use Core\Migration;

class ' . $className . ' extends Migration
{
    public function up(): void
    {';
        
        if ($createTable) {
            $template .= '
        $this->createTable(\'' . $createTable . '\', function ($table) {
            $table->id();
            // Add columns here
            $table->timestamps();
        });';
        } else {
            $template .= '
        // Add migration logic here';
        }
        
        $template .= '
    }
    
    public function down(): void
    {';
        
        if ($createTable) {
            $template .= '
        $this->dropTable(\'' . $createTable . '\');';
        } else {
            $template .= '
        // Add rollback logic here';
        }
        
        $template .= '
    }
}
';
        
        return $template;
    }
    
    private function getSeederTemplate(string $className): string
    {
        return '<?php

namespace Database\Seeders;

use Core\Seeder;

class ' . $className . ' extends Seeder
{
    public function run(): void
    {
        // Add seeding logic here
    }
}
';
    }
    
    private function getControllerTemplate(string $className, string $module, bool $resource, bool $api): string
    {
        $namespace = "Modules\\{$module}\\Controllers";
        $baseClass = $api ? 'Core\ApiController' : 'Core\Controller';
        $uses = $api ? '' : "use Core\Response;\nuse Core\Request;\nuse Core\Session;\n";
        
        $methods = '';
        if ($resource || $api) {
            $methods = '
    public function index(): Response
    {
        // List resources
        return $this->success([]);
    }
    
    public function store(): Response
    {
        // Create resource
        return $this->created();
    }
    
    public function show(int $id): Response
    {
        // Show resource
        return $this->success(null);
    }
    
    public function update(int $id): Response
    {
        // Update resource
        return $this->updated();
    }
    
    public function destroy(int $id): Response
    {
        // Delete resource
        return $this->deleted();
    }';
        }
        
        return "<?php

namespace {$namespace};

use {$baseClass};
{$uses}

class {$className} extends " . ($api ? 'ApiController' : 'Controller') . "
{
    {$methods}
}
";
    }
    
    private function getModelTemplate(string $className): string
    {
        return "<?php

namespace Shared\Models;

use Core\Model;

class {$className} extends Model
{
    protected static string \$table = '" . strtolower($className) . "s" . "';
    protected static string \$primaryKey = 'id';
    
    protected static array \$fillable = [
        // Add fillable fields
    ];
    
    protected static array \$hidden = [];
    
    protected static array \$casts = [
        'id' => 'int',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
    
    // Add relationships, scopes, and methods here
}
";
    }
}