// =============================================================================
// POKÉTIENDA - Auth Pages JavaScript
// =============================================================================

import '../scss/app.scss';
import * as bootstrap from 'bootstrap';

// =============================================================================
// DEMO ACCOUNT AUTO-FILL
// =============================================================================

export function initDemoAccounts() {
    document.querySelectorAll('.demo-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const emailInput = document.getElementById('correo');
            const passInput = document.getElementById('password') || document.getElementById('contrasena');
            
            if (emailInput) {
                emailInput.value = this.dataset.email;
                emailInput.dispatchEvent(new Event('input', { bubbles: true }));
            }
            
            if (passInput) {
                passInput.value = this.dataset.pass;
                passInput.dispatchEvent(new Event('input', { bubbles: true }));
            }
            
            // Focus password field
            if (passInput) passInput.focus();
        });
    });
}

// =============================================================================
// PASSWORD STRENGTH INDICATOR
// =============================================================================

export function initPasswordStrength() {
    const passwordInput = document.getElementById('password') || document.getElementById('contrasena');
    const confirmInput = document.getElementById('password_confirmation') || document.getElementById('contrasena2');

    if (!passwordInput) return;

    // Create strength indicator
    let indicator = document.querySelector('.password-strength');
    if (!indicator) {
        indicator = document.createElement('div');
        indicator.className = 'password-strength mt-1';
        passwordInput.parentNode.appendChild(indicator);
    }

    passwordInput.addEventListener('input', function() {
        const strength = calculateStrength(this.value);
        updateStrengthIndicator(indicator, strength);
        
        // Validate confirmation match
        if (confirmInput && confirmInput.value) {
            validatePasswordMatch();
        }
    });

    if (confirmInput) {
        confirmInput.addEventListener('input', validatePasswordMatch);
    }

    function validatePasswordMatch() {
        if (passwordInput.value === confirmInput.value) {
            confirmInput.classList.remove('is-invalid');
            confirmInput.classList.add('is-valid');
        } else if (confirmInput.value) {
            confirmInput.classList.remove('is-valid');
            confirmInput.classList.add('is-invalid');
        }
    }
}

function calculateStrength(password) {
    let score = 0;
    if (password.length >= 8) score++;
    if (/[A-Z]/.test(password)) score++;
    if (/[a-z]/.test(password)) score++;
    if (/[0-9]/.test(password)) score++;
    if (/[^A-Za-z0-9]/.test(password)) score++;
    return score;
}

function updateStrengthIndicator(indicator, score) {
    const levels = ['Muy débil', 'Débil', 'Media', 'Fuerte', 'Muy fuerte'];
    const colors = ['danger', 'warning', 'info', 'success', 'success'];
    const safeScore = Math.max(1, Math.min(score, 5));
    
    indicator.innerHTML = `
        <small class="text-${colors[safeScore-1]}">
            <i class="fas fa-shield-alt me-1"></i>
            Fortaleza: ${levels[safeScore-1]}
        </small>
    `;
}

// =============================================================================
// FORM VALIDATION
// =============================================================================

export function initAuthForms() {
    const forms = document.querySelectorAll('.auth-form');
    
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            // Check required fields
            const requiredFields = this.querySelectorAll('[required]');
            let hasErrors = false;
            
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    field.classList.add('is-invalid');
                    hasErrors = true;
                } else {
                    field.classList.remove('is-invalid');
                }
            });

            // Password confirmation check
            const password = this.querySelector('#password, #contrasena');
            const confirm = this.querySelector('#password_confirmation, #contrasena2');
            
            if (password && confirm && password.value !== confirm.value) {
                confirm.classList.add('is-invalid');
                hasErrors = true;
            }

            // Terms checkbox
            const terms = this.querySelector('#terms');
            if (terms && !terms.checked) {
                terms.classList.add('is-invalid');
                hasErrors = true;
            }

            if (hasErrors) {
                e.preventDefault();
                e.stopPropagation();
            }
            
            this.classList.add('was-validated');
        });

        // Real-time validation clearing
        form.querySelectorAll('.form-control, .form-select').forEach(input => {
            input.addEventListener('input', () => {
                input.classList.remove('is-invalid');
            });
        });

        // Terms checkbox
        const terms = form.querySelector('#terms');
        if (terms) {
            terms.addEventListener('change', () => {
                if (terms.checked) terms.classList.remove('is-invalid');
            });
        }
    });
}

// =============================================================================
// PASSWORD TOGGLE VISIBILITY
// =============================================================================

export function initPasswordToggle() {
    document.querySelectorAll('.password-toggle').forEach(toggle => {
        toggle.addEventListener('click', function() {
            const targetId = this.dataset.target;
            const input = document.getElementById(targetId);
            
            if (!input) return;
            
            if (input.type === 'password') {
                input.type = 'text';
                this.innerHTML = '<i class="fas fa-eye-slash"></i>';
            } else {
                input.type = 'password';
                this.innerHTML = '<i class="fas fa-eye"></i>';
            }
        });
    });
}

// =============================================================================
// FLOATING POKEBALL ANIMATIONS
// =============================================================================

export function initFloatingPokeballs() {
    const pokeballs = document.querySelectorAll('.floating-pokeball');
    
    pokeballs.forEach((ball, index) => {
        // Randomize animation slightly
        ball.style.animationDelay = `${-index * 2.5}s`;
        ball.style.animationDuration = `${8 + index * 0.5}s`;
    });
}

// =============================================================================
// FORM ENHANCEMENTS
// =============================================================================

export function initFormEnhancements() {
    // Floating label focus effect
    document.querySelectorAll('.form-floating > .form-control, .form-floating > .form-select').forEach(input => {
        input.addEventListener('focus', () => {
            input.parentElement.classList.add('focused');
        });
        
        input.addEventListener('blur', () => {
            if (!input.value) {
                input.parentElement.classList.remove('focused');
            }
        });
        
        // Check initial state
        if (input.value) {
            input.parentElement.classList.add('focused');
        }
    });

    // Select styling for categoria
    const categoriaSelect = document.getElementById('categoria');
    if (categoriaSelect) {
        const updateSelectColor = () => {
            const option = categoriaSelect.options[categoriaSelect.selectedIndex];
            if (option.style.background) {
                categoriaSelect.style.background = option.style.background;
                categoriaSelect.style.color = option.style.color;
            } else {
                categoriaSelect.style.background = '';
                categoriaSelect.style.color = '';
            }
        };
        
        categoriaSelect.addEventListener('change', updateSelectColor);
        updateSelectColor();
    }
}

// =============================================================================
// INITIALIZATION
// =============================================================================

document.addEventListener('DOMContentLoaded', () => {
    initDemoAccounts();
    initPasswordStrength();
    initAuthForms();
    initPasswordToggle();
    initFloatingPokeballs();
    initFormEnhancements();

    // Initialize Bootstrap components
    const tooltips = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltips.forEach(el => new bootstrap.Tooltip(el));

    console.log('🔐 PokéTienda Auth initialized!');
});

// =============================================================================
// EXPORTS
// =============================================================================

export default {
    initDemoAccounts,
    initPasswordStrength,
    initAuthForms,
    initPasswordToggle,
    initFloatingPokeballs,
    initFormEnhancements
};