@extends('layouts.admin')

@section('title', 'Reportes')

@section('content')
<div class="admin-content">
    <div class="mb-4">
        <h1 class="h3 mb-1"><i class="fas fa-chart-line me-2 text-warning"></i>Reportes</h1>
        <p class="text-muted mb-0">Resumen de actividad y ventas de la tienda</p>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-sm-6 col-xl-3"><div class="card border-0 shadow-sm h-100"><div class="card-body"><div class="text-muted small">Productos</div><div class="h3 mb-0">{{ $resumen['productos'] ?? 0 }}</div></div></div></div>
        <div class="col-sm-6 col-xl-3"><div class="card border-0 shadow-sm h-100"><div class="card-body"><div class="text-muted small">Usuarios</div><div class="h3 mb-0">{{ $resumen['usuarios'] ?? 0 }}</div></div></div></div>
        <div class="col-sm-6 col-xl-3"><div class="card border-0 shadow-sm h-100"><div class="card-body"><div class="text-muted small">Pedidos no cancelados</div><div class="h3 mb-0">{{ $resumen['pedidos'] ?? 0 }}</div></div></div></div>
        <div class="col-sm-6 col-xl-3"><div class="card border-0 shadow-sm h-100"><div class="card-body"><div class="text-muted small">Ventas no canceladas</div><div class="h3 mb-0">${{ number_format($resumen['ventas'] ?? 0, 2) }}</div></div></div></div>
    </div>

    <div class="row g-3">
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-transparent border-0"><h5 class="mb-0">Ventas por mes</h5><small class="text-muted">Últimos seis meses con actividad</small></div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light"><tr><th>Mes</th><th>Pedidos</th><th class="text-end">Ventas</th></tr></thead>
                            <tbody>
                                @foreach($ventasMensuales as $mes)
                                <tr><td>{{ $mes['periodo'] }}</td><td>{{ $mes['pedidos'] }}</td><td class="text-end">${{ number_format($mes['ventas'], 2) }}</td></tr>
                                @endforeach
                                @if(empty($ventasMensuales))
                                <tr><td colspan="3" class="text-center text-muted py-4">No hay ventas en el periodo.</td></tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-transparent border-0"><h5 class="mb-0">Compras de inventario</h5></div>
                <div class="card-body">
                    <div class="text-muted small">Compras registradas</div>
                    <div class="h3 mb-3">{{ $resumen['compras'] ?? 0 }}</div>
                    <div class="text-muted small">Total de compras recibidas</div>
                    <div class="h3 mb-0">${{ number_format($resumen['compras_recibidas'] ?? 0, 2) }}</div>
                    <a href="{{ route('admin.purchases.index') }}" class="btn btn-outline-primary btn-sm mt-4">Ver compras</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
