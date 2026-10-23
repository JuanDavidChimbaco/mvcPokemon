@if(session()->hasFlash('success') || session()->hasFlash('error') || session()->hasFlash('warning') || session()->hasFlash('info'))
<div class="flash-messages-container" id="flashMessages">
    @if(session()->hasFlash('success'))
    <div class="alert alert-success alert-dismissible fade show flash-message" role="alert">
        <i class="fas fa-check-circle me-2"></i>
        {{ session()->getFlash('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
    </div>
    @endif
    
    @if(session()->hasFlash('error'))
    <div class="alert alert-danger alert-dismissible fade show flash-message" role="alert">
        <i class="fas fa-exclamation-circle me-2"></i>
        {{ session()->getFlash('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
    </div>
    @endif
    
    @if(session()->hasFlash('warning'))
    <div class="alert alert-warning alert-dismissible fade show flash-message" role="alert">
        <i class="fas fa-exclamation-triangle me-2"></i>
        {{ session()->getFlash('warning') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
    </div>
    @endif
    
    @if(session()->hasFlash('info'))
    <div class="alert alert-info alert-dismissible fade show flash-message" role="alert">
        <i class="fas fa-info-circle me-2"></i>
        {{ session()->getFlash('info') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
    </div>
    @endif
</div>
@endif

<script>
// Auto-dismiss flash messages after 5 seconds
document.addEventListener('DOMContentLoaded', function() {
    const messages = document.querySelectorAll('.flash-message');
    messages.forEach(function(message) {
        setTimeout(function() {
            const bsAlert = new bootstrap.Alert(message);
            bsAlert.close();
        }, 5000);
    });
});
</script>