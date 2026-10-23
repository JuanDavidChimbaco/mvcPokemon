@extends('layouts.app')

@section('title', 'Finalizar Compra - PokéTienda')

@section('styles')
@endsection

@section('content')
<section class="checkout-section py-5">
    <div class="container">
        @if(empty($items))
        <div class="text-center py-5">
            <i class="fas fa-shopping-bag fa-4x text-muted mb-3"></i>
            <h3>Tu carrito está vacío</h3>
            <a href="{{ route('home') }}" class="btn btn-poke-primary mt-3">Ir a Comprar</a>
        </div>
        @else
        <!-- Progress Steps -->
        <div class="checkout-progress mb-5">
            <div class="row">
                <div class="col-4">
                    <div class="progress-step active">
                        <div class="step-circle">1</div>
                        <div class="step-label">Información</div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="progress-step">
                        <div class="step-circle">2</div>
                        <div class="step-label">Envío</div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="progress-step">
                        <div class="step-circle">3</div>
                        <div class="step-label">Pago</div>
                    </div>
                </div>
            </div>
        </div>
        
        <form id="checkoutForm" class="needs-validation" method="POST" action="{{ route('shop.checkout.process') }}" novalidate>
            <input type="hidden" name="_token" value="{{ session()->token() }}">
            
            <div class="row g-5">
                <!-- Shipping Info -->
                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-transparent border-0">
                            <h5 class="mb-0"><i class="fas fa-user me-2"></i>Información de Contacto</h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="nombre" class="form-label fw-medium">Nombre <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="nombre" name="nombre" value="{{ old('nombre', $user->nombre ?? '') }}" maxlength="50" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="apellido" class="form-label fw-medium">Apellido <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="apellido" name="apellido" value="{{ old('apellido', $user->apellido ?? '') }}" maxlength="50" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="correo" class="form-label fw-medium">Correo Electrónico <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control" id="correo" name="correo" value="{{ old('correo', $user->correo ?? '') }}" maxlength="100" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="telefono" class="form-label fw-medium">Teléfono <span class="text-danger">*</span></label>
                                    <input type="tel" class="form-control" id="telefono" name="telefono" value="{{ old('telefono', $user->telefono ?? '') }}" maxlength="20" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-transparent border-0">
                            <h5 class="mb-0"><i class="fas fa-map-marker-alt me-2"></i>Dirección de Envío</h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-12">
                                    <label for="direccion" class="form-label fw-medium">Dirección <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="direccion" name="direccion" placeholder="Calle, número, apartamento..." value="{{ old('direccion', $user->direccion ?? '') }}" maxlength="255" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="ciudad" class="form-label fw-medium">Ciudad <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="ciudad" name="ciudad" value="{{ old('ciudad') }}" maxlength="100" required>
                                </div>
                                <div class="col-md-3">
                                    <label for="estado" class="form-label fw-medium">Estado/Provincia <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="estado" name="estado" value="{{ old('estado') }}" maxlength="100" required>
                                </div>
                                <div class="col-md-3">
                                    <label for="codigo_postal" class="form-label fw-medium">Código Postal <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="codigo_postal" name="codigo_postal" placeholder="12345" value="{{ old('codigo_postal') }}" maxlength="10" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="pais" class="form-label fw-medium">País</label>
                                    <select class="form-select" id="pais" name="pais">
                                        <option value="CO" {{ old('pais', 'CO') === 'CO' ? 'selected' : '' }}>Colombia</option>
                                        <option value="MX" {{ old('pais') === 'MX' ? 'selected' : '' }}>México</option>
                                        <option value="AR" {{ old('pais') === 'AR' ? 'selected' : '' }}>Argentina</option>
                                        <option value="CL" {{ old('pais') === 'CL' ? 'selected' : '' }}>Chile</option>
                                        <option value="PE" {{ old('pais') === 'PE' ? 'selected' : '' }}>Perú</option>
                                        <option value="OTRO" {{ old('pais') === 'OTRO' ? 'selected' : '' }}>Otro</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="form-check mt-3">
                                <input class="form-check-input" type="checkbox" id="sameBilling" name="same_billing" checked>
                                <label class="form-check-label" for="sameBilling">
                                    La dirección de facturación es la misma que la de envío
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Payment Method -->
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-transparent border-0">
                            <h5 class="mb-0"><i class="fas fa-credit-card me-2"></i>Método de Pago</h5>
                        </div>
                        <div class="card-body">
                            <div class="payment-methods">
                                <label class="payment-method {{ old('payment_method', 'tarjeta') === 'tarjeta' ? 'active' : '' }}">
                                    <input type="radio" name="payment_method" value="tarjeta" {{ old('payment_method', 'tarjeta') === 'tarjeta' ? 'checked' : '' }}>
                                    <div class="payment-method-content">
                                        <i class="fas fa-credit-card fa-2x text-warning mb-2"></i>
                                        <h6>Tarjeta (demostración)</h6>
                                        <small class="text-muted">No se procesan pagos reales</small>
                                    </div>
                                </label>
                                
                                <label class="payment-method {{ old('payment_method') === 'pse' ? 'active' : '' }}">
                                    <input type="radio" name="payment_method" value="pse" {{ old('payment_method') === 'pse' ? 'checked' : '' }}>
                                    <div class="payment-method-content">
                                        <i class="fas fa-university fa-2x text-primary mb-2"></i>
                                        <h6>Transferencia (demostración)</h6>
                                        <small class="text-muted">No se inicia una transferencia real</small>
                                    </div>
                                </label>
                                
                                <label class="payment-method {{ old('payment_method') === 'contraentrega' ? 'active' : '' }}">
                                    <input type="radio" name="payment_method" value="contraentrega" {{ old('payment_method') === 'contraentrega' ? 'checked' : '' }}>
                                    <div class="payment-method-content">
                                        <i class="fas fa-money-bill-wave fa-2x text-success mb-2"></i>
                                        <h6>Contra entrega (demostración)</h6>
                                        <small class="text-muted">Pedido de prueba</small>
                                    </div>
                                </label>
                            </div>
                            <p class="alert alert-info mt-3 mb-0">Esta tienda está en modo demostración. No ingreses ni compartas datos bancarios; no se realiza ningún cobro.</p>
                        </div>
                    </div>
                    
                    <!-- Terms -->
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-body">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="terms" name="terms" value="1" required>
                                <label class="form-check-label" for="terms">
                                    Acepto los <a href="#" class="text-poke-primary">Términos y Condiciones</a> y la <a href="#" class="text-poke-primary">Política de Privacidad</a> <span class="text-danger">*</span>
                                </label>
                                <div class="invalid-feedback">Debes aceptar los términos para continuar</div>
                            </div>
                            
                        </div>
                    </div>
                </div>
                
                <!-- Order Summary -->
                <div class="col-lg-4">
                    <div class="summary-card sticky-top" style="top: 100px;">
                        <div class="card border-0 shadow-sm">
                            <div class="card-header bg-transparent border-0">
                                <h5 class="mb-0"><i class="fas fa-receipt me-2"></i>Resumen del Pedido</h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-sm mb-0">
                                        <tbody>
                                            @foreach($items as $item)
                                            <tr>
                                                <td class="ps-0">
                                                    <div class="d-flex align-items-center">
                                                        <img src="{{ product_image_url($item['imagen'] ?? null) }}" data-pokemon-name="{{ $item['nombre'] }}" alt="{{ $item['nombre'] }}" class="cart-item-image me-2">
                                                        <div>
                                                            <small class="fw-medium">{{ $item['nombre'] }}</small>
                                                            <br><small class="text-muted">{{ $item['cantidad'] }} x ${{ number_format($item['precio'], 2) }}</small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="text-end pe-0">${{ number_format($item['subtotal'], 2) }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                
                                <hr>
                                
                                <div class="summary-row d-flex justify-content-between mb-2">
                                    <span>Subtotal</span>
                                    <span class="fw-medium">${{ number_format($summary['subtotal'], 2) }}</span>
                                </div>
                                <div class="summary-row d-flex justify-content-between mb-2">
                                    <span>Envío</span>
                                    <span class="fw-medium text-success">GRATIS</span>
                                </div>
                                <div class="summary-row d-flex justify-content-between mb-2">
                                    <span>IVA (19%)</span>
                                    <span class="fw-medium">${{ number_format($summary['tax'], 2) }}</span>
                                </div>
                                <hr>
                                <div class="summary-row d-flex justify-content-between fs-5 fw-bold">
                                    <span>Total</span>
                                    <span class="text-poke-primary">${{ number_format($summary['total'], 2) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Submit -->
            <div class="col-12">
                <div class="d-grid">
                    <button type="submit" class="btn btn-poke-primary btn-lg">
                        <i class="fas fa-lock me-2"></i>Realizar Pedido - ${{ number_format($summary['total'], 2) }}
                    </button>
                </div>
                
                <p class="text-center text-muted small mt-3">
                    <i class="fas fa-shield-alt me-1"></i>Compra segura - Tus datos están protegidos con encriptación SSL
                </p>
            </div>
        </div>
        </form>
        @endif
    </div>
</section>
@endsection
