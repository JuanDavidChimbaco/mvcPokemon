// =============================================================================
// POKÉTIENDA - Admin Panel JavaScript
// =============================================================================

import '../scss/admin.scss';
import { initializeProductImageForm } from './product-artwork.js';
import * as bootstrap from 'bootstrap';

// =============================================================================
// SIDEBAR MANAGEMENT
// =============================================================================

export function initSidebar() {
    const sidebar = document.getElementById('adminSidebar');
    const sidebarToggle = document.querySelector('.sidebar-toggle');
    const sidebarOverlay = document.getElementById('sidebarOverlay');
    const main = document.getElementById('adminMain');

    if (!sidebar || !sidebarToggle) return;

    // Toggle sidebar
    sidebarToggle.addEventListener('click', () => {
        sidebar.classList.toggle('show');
        sidebarOverlay?.classList.toggle('show');
    });

    // Close on overlay click
    sidebarOverlay?.addEventListener('click', () => {
        sidebar.classList.remove('show');
        sidebarOverlay.classList.remove('show');
    });

    // Close on nav link click (mobile)
    sidebar.querySelectorAll('.nav-link').forEach(link => {
        link.addEventListener('click', () => {
            if (window.innerWidth < 992) {
                sidebar.classList.remove('show');
                sidebarOverlay?.classList.remove('show');
            }
        });
    });

    // Handle resize
    window.addEventListener('resize', () => {
        if (window.innerWidth >= 992) {
            sidebar.classList.remove('show');
            sidebarOverlay?.classList.remove('show');
        }
    });
}

// =============================================================================
// DATA TABLES INITIALIZATION
// =============================================================================

export function initDataTables() {
    if (typeof $ === 'undefined' || !$.fn.DataTable) {
        console.warn('jQuery or DataTables not loaded');
        return;
    }

    // Common DataTables configuration
    const commonConfig = {
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.8/i18n/es-ES.json'
        },
        pageLength: 10,
        lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
        responsive: true,
        dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>rtip',
        drawCallback: function() {
            // Reinitialize tooltips after draw
            const tooltips = [].slice.call(this.api().table().container().querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltips.forEach(el => new bootstrap.Tooltip(el));
        }
    };

    // Initialize all tables with .datatable class
    document.querySelectorAll('table.datatable').forEach(table => {
        if (!$.fn.dataTable.isDataTable(table)) {
            new $.fn.dataTable(table, commonConfig);
        }
    });
}

// =============================================================================
// FORMS VALIDATION
// =============================================================================

export function initForms() {
    const forms = document.querySelectorAll('.needs-validation');
    
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            if (!this.checkValidity()) {
                e.preventDefault();
                e.stopPropagation();
            }
            this.classList.add('was-validated');
        });

        // Clear validation on input
        form.querySelectorAll('.form-control, .form-select').forEach(input => {
            input.addEventListener('input', () => {
                input.classList.remove('is-invalid');
            });
        });
    });
}

// =============================================================================
// DELETE CONFIRMATION
// =============================================================================

export function initDeleteConfirmations() {
    document.addEventListener('click', function(e) {
        const btn = e.target.closest('[data-delete]');
        if (!btn) return;

        const id = btn.dataset.delete;
        const name = btn.dataset.name || 'este elemento';
        
        if (confirm(`¿Eliminar "${name}"? Esta acción no se puede deshacer.`)) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = btn.dataset.url || window.location.href;
            form.innerHTML = `
                <input type="hidden" name="_method" value="DELETE">
                <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').getAttribute('content')}">
            `;
            document.body.appendChild(form);
            form.submit();
        }
    });
}

// =============================================================================
// IMAGE PREVIEW
// =============================================================================

export function initImagePreviews() {
    document.querySelectorAll('[data-preview]').forEach(input => {
        const targetId = input.dataset.preview;
        const preview = document.getElementById(targetId);
        const previewImg = preview?.querySelector('img');

        if (!preview || !previewImg) return;

        input.addEventListener('input', function() {
            const url = this.value.trim();
            if (url) {
                previewImg.src = url;
                previewImg.onload = () => preview.style.display = 'block';
                previewImg.onerror = () => preview.style.display = 'none';
            } else {
                preview.style.display = 'none';
            }
        });
    });
}

// =============================================================================
// TOOLTIPS & POPOVERS
// =============================================================================

export function initTooltipsPopovers() {
    // Tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.forEach(el => new bootstrap.Tooltip(el, { boundary: 'window' }));

    // Popovers
    const popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
    popoverTriggerList.forEach(el => new bootstrap.Popover(el, { boundary: 'window' }));
}

// =============================================================================
// PAYMENT METHOD TOGGLE
// =============================================================================

export function initPaymentMethodToggle() {
    document.querySelectorAll('input[name="payment_method"]').forEach(radio => {
        radio.addEventListener('change', function() {
            document.querySelectorAll('.payment-method').forEach(m => m.classList.remove('active'));
            this.closest('.payment-method').classList.add('active');

            const cardForm = document.getElementById('cardForm');
            if (cardForm) {
                if (this.value === 'tarjeta') {
                    cardForm.style.display = 'block';
                    cardForm.querySelectorAll('input').forEach(input => input.required = true);
                } else {
                    cardForm.style.display = 'none';
                    cardForm.querySelectorAll('input').forEach(input => input.required = false);
                }
            }
        });
    });
}

// =============================================================================
// CARD NUMBER FORMATTING
// =============================================================================

export function initCardFormatting() {
    // Card number
    const cardNumber = document.getElementById('cardNumber');
    if (cardNumber) {
        cardNumber.addEventListener('input', function() {
            let value = this.value.replace(/\s/g, '').replace(/\D/g, '');
            value = value.replace(/(.{4})/g, '$1 ').trim();
            this.value = value.substring(0, 19);
        });
    }

    // Expiry
    const cardExpiry = document.getElementById('cardExpiry');
    if (cardExpiry) {
        cardExpiry.addEventListener('input', function() {
            let value = this.value.replace(/\D/g, '');
            if (value.length >= 2) {
                value = value.substring(0, 2) + '/' + value.substring(2, 4);
            }
            this.value = value.substring(0, 5);
        });
    }
}

// =============================================================================
// QUICK ACTION CARDS HOVER
// =============================================================================

export function initQuickActionCards() {
    document.querySelectorAll('.quick-action-card').forEach(card => {
        card.addEventListener('mouseenter', () => {
            card.style.transform = 'translateY(-4px)';
            card.style.boxShadow = '0 10px 25px rgba(0,0,0,0.15)';
        });

        card.addEventListener('mouseleave', () => {
            card.style.transform = '';
            card.style.boxShadow = '';
        });
    });
}

// =============================================================================
// FILTER FORMS AUTO-SUBMIT
// =============================================================================

export function initFilterForms() {
    document.querySelectorAll('.filter-form select, .filter-form input[type="checkbox"]').forEach(el => {
        el.addEventListener('change', () => {
            el.closest('form').submit();
        });
    });

    // Price inputs - submit on Enter
    document.querySelectorAll('.filter-form input[type="number"]').forEach(el => {
        el.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') {
                e.preventDefault();
                el.closest('form').submit();
            }
        });
    });
}

// =============================================================================
// QUANTITY CONTROLS (ADMIN)
// =============================================================================

export function initAdminQuantityControls() {
    document.querySelectorAll('.qty-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const row = this.closest('tr');
            const input = row?.querySelector('.qty-input');
            const id = row?.dataset.id;
            const action = this.dataset.action;
            const current = parseInt(input?.value) || 1;
            let newVal = current;

            if (action === 'increase') newVal = Math.min(current + 1, 99);
            else if (action === 'decrease') newVal = Math.max(current - 1, 1);

            if (newVal !== current && input) {
                updateAdminQuantity(id, newVal, input, row);
            }
        });
    });

    // Remove buttons
    document.querySelectorAll('.btn-remove').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.dataset.id;
            const row = this.closest('tr');
            
            if (confirm('¿Eliminar este producto del carrito?')) {
                updateAdminQuantity(id, 0, null, row);
            }
        });
    });
}

async function updateAdminQuantity(id, quantity, input, row) {
    try {
        const response = await fetch('/api/carrito/actualizar', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
            },
            body: JSON.stringify({ producto_id: id, cantidad: quantity })
        });
        
        const data = await response.json();
        
        if (data.success) {
            if (quantity === 0) {
                row?.remove();
                updateAdminSummary(data.total);
            } else if (input) {
                input.value = quantity;
                const price = parseFloat(row.querySelector('td:nth-child(3)').textContent.replace('$', ''));
                row.querySelector('.subtotal-cell').textContent = '$' + (price * quantity).toFixed(2);
                updateAdminSummary(data.total);
            }
        } else {
            alert(data.message || 'Error al actualizar');
        }
    } catch (error) {
        alert('Error de conexión');
    }
}

function updateAdminSummary(total) {
    const subtotal = total;
    const tax = subtotal * 0.19;
    const grandTotal = subtotal + tax;
    
    const rows = document.querySelectorAll('.summary-row');
    if (rows.length >= 4) {
        rows[0].querySelector('span:last-child').textContent = '$' + subtotal.toFixed(2);
        rows[2].querySelector('span:last-child').textContent = '$' + tax.toFixed(2);
        rows[3].querySelector('span:last-child').textContent = '$' + grandTotal.toFixed(2);
    }
}

// =============================================================================
// CATEGORY COLOR PREVIEW
// =============================================================================

export function initCategoryColorPreview() {
    const select = document.getElementById('categoria');
    if (!select) return;

    const updateColor = () => {
        const option = select.options[select.selectedIndex];
        if (option.style.background) {
            select.style.background = option.style.background;
            select.style.color = option.style.color;
        } else {
            select.style.background = '';
            select.style.color = '';
        }
    };

    select.addEventListener('change', updateColor);
    updateColor(); // Initial
}

// =============================================================================
// TABLE ROW CLICK
// =============================================================================

export function initTableRowClick() {
    document.querySelectorAll('table tbody tr[data-href]').forEach(row => {
        row.style.cursor = 'pointer';
        row.addEventListener('click', (e) => {
            if (e.target.tagName !== 'A' && e.target.tagName !== 'BUTTON' && !e.target.closest('a, button')) {
                window.location.href = row.dataset.href;
            }
        });
    });
}

// =============================================================================
// SEARCH INPUT DEBOUNCE
// =============================================================================

export function initSearchDebounce() {
    const searchInput = document.querySelector('input[name="search"]');
    const form = searchInput?.closest('form');
    
    if (!searchInput || !form) return;

    let timeout;
    searchInput.addEventListener('input', () => {
        clearTimeout(timeout);
        timeout = setTimeout(() => form.submit(), 500);
    });

    searchInput.addEventListener('keypress', (e) => {
        if (e.key === 'Enter') {
            e.preventDefault();
            clearTimeout(timeout);
            form.submit();
        }
    });
}

// =============================================================================
// CSRF TOKEN META
// =============================================================================

export function ensureCsrfMeta() {
    if (!document.querySelector('meta[name="csrf-token"]')) {
        const meta = document.createElement('meta');
        meta.name = 'csrf-token';
        meta.content = window.CSRF_TOKEN || '';
        document.head.appendChild(meta);
    }
}

// =============================================================================
// INITIALIZATION
// =============================================================================

document.addEventListener('DOMContentLoaded', () => {
    ensureCsrfMeta();
    initSidebar();
    initDataTables();
    initForms();
    initializeProductImageForm();
    initDeleteConfirmations();
    initImagePreviews();
    initTooltipsPopovers();
    initPaymentMethodToggle();
    initCardFormatting();
    initQuickActionCards();
    initFilterForms();
    initAdminQuantityControls();
    initCategoryColorPreview();
    initTableRowClick();
    initSearchDebounce();

    // Initialize Bootstrap components
    const dropdowns = [].slice.call(document.querySelectorAll('[data-bs-toggle="dropdown"]'));
    dropdowns.forEach(el => new bootstrap.Dropdown(el));

    console.log('⚙️ PokéTienda Admin initialized!');
});

// =============================================================================
// EXPORTS
// =============================================================================

export default {
    initSidebar,
    initDataTables,
    initForms,
    initDeleteConfirmations,
    initImagePreviews,
    initTooltipsPopovers,
    initPaymentMethodToggle,
    initCardFormatting,
    initQuickActionCards,
    initFilterForms,
    initAdminQuantityControls,
    initCategoryColorPreview,
    initTableRowClick,
    initSearchDebounce,
    ensureCsrfMeta
};