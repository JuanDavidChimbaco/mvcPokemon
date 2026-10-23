<?php

namespace Core;

/**
 * Middleware base
 */
abstract class Middleware
{
    abstract public function handle(): mixed;
}

/**
 * Middleware de autenticación
 */
class AuthMiddleware extends Middleware
{
    public function handle(): mixed
    {
        $session = app('session');
        
        if (!$session->check()) {
            $request = app('request');
            
            if ($request->isJson() || $request->isAjax()) {
                return app('response')->json(['error' => 'No autenticado'], 401);
            }
            
            $session->flash('error', 'Debes iniciar sesión');
            return app('response')->redirect(route('auth.login'));
        }
        
        return null; // Continuar
    }
}

/**
 * Middleware de rol admin
 */
class AdminMiddleware extends Middleware
{
    public function handle(): mixed
    {
        $session = app('session');
        
        if (!$session->check()) {
            return app('response')->redirect(route('auth.login'));
        }
        
        $user = $session->user();
        
        if (!$user || !$user->isAdmin()) {
            if (app('request')->isJson()) {
                return app('response')->json(['error' => 'Acceso denegado'], 403);
            }
            return app('response')->redirect(route('home'));
        }
        
        return null;
    }
}

/**
 * Middleware de sesión (para API)
 */
class SessionMiddleware extends Middleware
{
    public function handle(): mixed
    {
        app('session')->start();
        return null;
    }
}

/**
 * Middleware CSRF
 */
class CsrfMiddleware extends Middleware
{
    public function handle(): mixed
    {
        $request = app('request');
        $session = app('session');
        
        if (in_array($request->method(), ['POST', 'PUT', 'PATCH', 'DELETE'])) {
            $token = $request->header('x-csrf-token') 
                ?? $request->input('_token')
                ?? $request->input('csrf_token');
            
            if (!$session->verifyToken($token ?? '')) {
                if ($request->isJson() || $request->isAjax()) {
                    return app('response')->json(['error' => 'Token CSRF inválido'], 419);
                }
                return app('response')->make('Token CSRF inválido', 419);
            }
        }
        
        return null;
    }
}

/**
 * Middleware de rate limiting simple
 */
class RateLimitMiddleware extends Middleware
{
    private int $maxRequests;
    private int $windowSeconds;
    
    public function __construct(int $maxRequests = 60, int $windowSeconds = 60)
    {
        $this->maxRequests = $maxRequests;
        $this->windowSeconds = $windowSeconds;
    }
    
    public function handle(): mixed
    {
        $request = app('request');
        $ip = $request->ip();
        $key = "ratelimit:{$ip}";
        
        $cache = app('cache') ?? [];
        
        // Implementación simple en memoria (para producción usar Redis)
        if (!isset($cache[$key])) {
            $cache[$key] = ['count' => 1, 'expires' => time() + $this->windowSeconds];
        } elseif (time() > $cache[$key]['expires']) {
            $cache[$key] = ['count' => 1, 'expires' => time() + $this->windowSeconds];
        } else {
            $cache[$key]['count']++;
        }
        
        if ($cache[$key]['count'] > $this->maxRequests) {
            $retryAfter = $cache[$key]['expires'] - time();
            return app('response')->json([
                'error' => 'Demasiadas solicitudes',
                'retry_after' => $retryAfter,
            ], 429);
        }
        
        return null;
    }
}