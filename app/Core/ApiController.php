<?php

namespace Core;

/**
 * Base API Controller - Respuestas JSON consistentes
 */
abstract class ApiController extends Controller
{
    protected function success(mixed $data = null, string $message = '', int $status = 200): Response
    {
        return $this->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
        ], $status);
    }
    
    protected function error(string $message = 'Error', int $status = 400, mixed $errors = null): Response
    {
        return $this->json([
            'success' => false,
            'message' => $message,
            'errors' => $errors,
        ], $status);
    }
    
    protected function paginated(array $data, int $page, int $perPage, int $total): Response
    {
        return $this->success($data, '', 200)->setHeader('X-Pagination', json_encode([
            'current_page' => $page,
            'per_page' => $perPage,
            'total' => $total,
            'last_page' => (int)ceil($total / $perPage),
        ]));
    }
    
    protected function created(mixed $data = null, string $message = 'Creado exitosamente'): Response
    {
        return $this->success($data, $message, 201);
    }
    
    protected function updated(mixed $data = null, string $message = 'Actualizado exitosamente'): Response
    {
        return $this->success($data, $message, 200);
    }
    
    protected function deleted(string $message = 'Eliminado exitosamente'): Response
    {
        return $this->success(null, $message, 200);
    }
    
    protected function notFound(string $message = 'Recurso no encontrado'): Response
    {
        return $this->error($message, 404);
    }
    
    protected function unauthorized(string $message = 'No autorizado'): Response
    {
        return $this->error($message, 401);
    }
    
    protected function forbidden(string $message = 'Acceso denegado'): Response
    {
        return $this->error($message, 403);
    }
    
    protected function validationError(array $errors): Response
    {
        return $this->error('Error de validación', 422, $errors);
    }
}