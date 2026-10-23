@extends('layouts.admin')

@section('title', 'Usuarios')

@section('content')
<div class="admin-content">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <div>
            <h1 class="h3 mb-1"><i class="fas fa-users me-2 text-warning"></i>Usuarios</h1>
            <p class="text-muted mb-0">Gestiona entrenadores y administradores</p>
        </div>
        <a href="{{ route('admin.users.create') }}" class="btn btn-poke-primary">
            <i class="fas fa-plus me-2"></i>Nuevo Usuario
        </a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-transparent border-0 d-flex justify-content-between align-items-center flex-wrap gap-2">
            <h5 class="mb-0">Listado de Usuarios</h5>
            <div class="d-flex gap-2">
                <button type="button" class="btn btn-outline-secondary btn-sm" data-action="export-csv" title="Exportar CSV"><i class="fas fa-file-csv"></i></button>
                <button type="button" class="btn btn-outline-secondary btn-sm" data-action="export-excel" title="Exportar Excel"><i class="fas fa-file-excel"></i></button>
                <button type="button" class="btn btn-outline-secondary btn-sm" data-action="export-pdf" title="Exportar PDF"><i class="fas fa-file-pdf"></i></button>
            </div>
        </div>
        
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0" id="usersTable">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 60px;">Avatar</th>
                            <th>Nombre</th>
                            <th>Correo</th>
                            <th>Documento</th>
                            <th>Rol</th>
                            <th style="width: 100px;">Estado</th>
                            <th style="width: 150px;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($usuarios as $usuario)
                        <tr>
                            <td>
                                <div class="avatar-sm bg-poke-primary bg-opacity-10 text-poke-red rounded-circle d-flex align-items-center justify-content-center mx-auto">
                                    <i class="fas fa-user"></i>
                                </div>
                            </td>
                            <td>
                                <strong>{{ $usuario->nombre_completo }}</strong>
                                <br><small class="text-muted">ID: {{ $usuario->id }}</small>
                            </td>
                            <td>{{ $usuario->correo }}</td>
                            <td>{{ $usuario->tipoDoc }}: {{ $usuario->identificacion }}</td>
                            <td><span class="badge bg-secondary">{{ $usuario->rol()?->nombreRol ?? 'Sin rol' }}</span></td>
                            <td>
                                <span class="badge {{ $usuario->estado === 'A' ? 'bg-success' : 'bg-secondary' }}">
                                    {{ $usuario->estado === 'A' ? 'Activo' : 'Inactivo' }}
                                </span>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('admin.users.edit', $usuario->id) }}" class="btn btn-outline-primary" title="Editar"><i class="fas fa-edit"></i></a>
                                    <button type="button" class="btn btn-outline-danger" title="Eliminar" data-delete="{{ $usuario->id }}" data-name="{{ $usuario->nombre_completo }}"><i class="fas fa-trash"></i></button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        
        <div class="card-footer bg-transparent border-0">
            <div class="d-flex justify-content-between align-items-center">
                <div id="usersTable_info" class="small text-muted"></div>
                <nav aria-label="Paginación"><ul class="pagination pagination-sm mb-0" id="usersTable_paginate"></ul></nav>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    const table = $('#usersTable').DataTable({
        pageLength: 10,
        lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
        language: { url: '//cdn.datatables.net/plug-ins/1.13.8/i18n/es-ES.json' },
        drawCallback: function() {
            const api = this.api();
            const info = api.page.info();
            const start = info.recordsDisplay === 0 ? 0 : info.start + 1;
            $('#usersTable_info').text(`Mostrando ${start} a ${info.end} de ${info.recordsDisplay} usuarios`);
        }
    });
    
    $('#usersTable').on('click', 'button[data-delete]', function() {
        const id = $(this).data('delete');
        const name = $(this).data('name');
        const row = $(this).closest('tr');
        
        if (confirm(`¿Eliminar al usuario "${name}"?`)) {
            $.ajax({
                url: `{{ route('admin.users.destroy', ':id') }}`.replace(':id', id),
                type: 'DELETE',
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                success: function() { table.row(row).remove().draw(); showToast('Usuario eliminado', 'success'); },
                error: function(xhr) { showToast(xhr.responseJSON?.message || 'Error al eliminar', 'danger'); }
            });
        }
    });
    
    function showToast(message, type = 'info') {
        const toast = $(`<div class="toast align-items-center text-white bg-${type} border-0" role="alert"><div class="d-flex"><div class="toast-body">${message}</div><button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button></div></div>`);
        let container = $('.toast-container');
        if (!container.length) container = $('<div class="toast-container position-fixed bottom-0 end-0 p-3"></div>').appendTo('body');
        container.append(toast);
        new bootstrap.Toast(toast[0], { delay: 3000 }).show();
        toast.on('hidden.bs.toast', () => toast.remove());
    }
});
</script>
@endsection