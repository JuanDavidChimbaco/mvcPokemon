<?php

namespace Core;

/**
 * Base Controller - Funcionalidades comunes
 */
abstract class Controller
{
    protected Request $request;
    protected Response $response;
    protected Session $session;
    
    public function __construct()
    {
        $this->request = app('request');
        $this->response = app('response');
        $this->session = app('session');
    }
    
    // ===== Helpers de respuesta =====
    
    protected function view(string $view, array $data = [], int $status = 200): Response
    {
        return Response::view($view, $data, $status);
    }
    
    protected function json(mixed $data, int $status = 200): Response
    {
        return Response::json($data, $status);
    }
    
    protected function redirect(string $url, int $status = 302): Response
    {
        return Response::redirect($url, $status);
    }
    
    protected function back(int $status = 302): Response
    {
        return Response::back($status);
    }
    
    protected function abort(int $status, string $message = ''): never
    {
        throw new \RuntimeException($message ?: 'Error', $status);
    }
    
    // ===== Validación =====
    
    protected function validate(array $rules): array
    {
        return $this->request->validate($rules);
    }
    
    // ===== Flash / Old =====
    
    protected function flash(string $key, mixed $value): void
    {
        $this->session->flash($key, $value);
    }
    
    protected function flashInput(array $input = null): void
    {
        $this->session->flashInput($input);
    }
    
    protected function old(string $key, mixed $default = ''): mixed
    {
        return $this->session->old($key, $default);
    }
    
    // ===== Auth helpers =====
    
    protected function auth(): ?\Shared\Models\Usuario
    {
        return $this->session->user();
    }
    
    protected function check(): bool
    {
        return $this->session->check();
    }
    
    protected function guest(): bool
    {
        return $this->session->guest();
    }
    
    protected function login(\Shared\Models\Usuario $user, array $data = []): void
    {
        $this->session->login($user, $data);
    }
    
    protected function logout(): void
    {
        $this->session->logout();
    }
    
    // ===== CSRF =====
    
    protected function csrfToken(): string
    {
        return $this->session->token();
    }
    
    protected function verifyCsrf(string $token): bool
    {
        return $this->session->verifyToken($token);
    }
}