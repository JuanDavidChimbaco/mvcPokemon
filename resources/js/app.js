// =============================================================================
// POKÉTIENDA - Shop Frontend JavaScript
// =============================================================================

import '../scss/app.scss';
import { loadMissingProductArtwork } from './product-artwork.js';

// Bootstrap components are auto-imported via laravel-vite-plugin
import * as bootstrap from 'bootstrap';

// =============================================================================
// GLOBAL UTILITIES
// =============================================================================

/**
 * Show toast notification
 */
export function showToast(message, type = 'info') {
    const toastEl = document.createElement('div');
    toastEl.className = `toast align-items-center text-white bg-${type} border-0`;
    toastEl.setAttribute('role', 'alert');
    toastEl.setAttribute('aria-live', 'assertive');
    toastEl.setAttribute('aria-atomic', 'true');
    toastEl.innerHTML = `
        <div class="d-flex">
            <div class="toast-body">${message}</div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Cerrar"></button>
        </div>
    `;

    let container = document.querySelector('.toast-container');
    if (!container) {
        container = document.createElement('div');
        container.className = 'toast-container position-fixed bottom-0 end-0 p-3';
        document.body.appendChild(container);
    }

    container.appendChild(toastEl);
    const toast = new bootstrap.Toast(toastEl, { delay: 3000 });
    toast.show();

    toastEl.addEventListener('hidden.bs.toast', () => toastEl.remove());
}

/**
 * Update cart count badge
 */
export function updateCartCount(count) {
    const badge = document.getElementById('cart-count') || document.getElementById('carrito-numero');
    if (badge) {
        badge.textContent = count;
        badge.style.display = count > 0 ? 'flex' : 'none';
    }
}

/**
 * Format price
 */
export function formatPrice(price) {
    return new Intl.NumberFormat('es-MX', {
        style: 'currency',
        currency: 'MXN',
        minimumFractionDigits: 2
    }).format(price);
}

/**
 * Debounce function
 */
export function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

/**
 * Fetch with CSRF token
 */
export async function fetchWithCsrf(url, options = {}) {
    const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    
    const defaultOptions = {
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': token || '',
            ...options.headers
        },
        credentials: 'same-origin'
    };

    return fetch(url, { ...defaultOptions, ...options });
}

function appRoute(name) {
    return document.body.dataset[name];
}

async function readJsonResponse(response) {
    const payload = await response.json();
    if (!response.ok && payload.success !== false) {
        throw new Error(`La solicitud falló (${response.status}).`);
    }
    return payload;
}

// =============================================================================
// CART FUNCTIONALITY
// =============================================================================

export class Cart {
    static async add(productId, quantity = 1) {
        try {
            const response = await fetchWithCsrf(appRoute('cartAddUrl'), {
                method: 'POST',
                body: JSON.stringify({ producto_id: productId, cantidad: quantity })
            });
            return await readJsonResponse(response);
        } catch (error) {
            console.error('No se pudo agregar el producto al carrito:', error);
            return { success: false, message: 'Error de conexión' };
        }
    }

    static async update(productId, quantity) {
        try {
            const response = await fetchWithCsrf(appRoute('cartUpdateUrl'), {
                method: 'POST',
                body: JSON.stringify({ producto_id: productId, cantidad: quantity })
            });
            return await readJsonResponse(response);
        } catch (error) {
            console.error('No se pudo actualizar el carrito:', error);
            return { success: false, message: 'Error de conexión' };
        }
    }

    static async remove(productId) {
        try {
            const response = await fetchWithCsrf(appRoute('cartUpdateUrl'), {
                method: 'POST',
                body: JSON.stringify({ producto_id: productId, cantidad: 0 })
            });
            return await readJsonResponse(response);
        } catch (error) {
            console.error('No se pudo eliminar el producto del carrito:', error);
            return { success: false, message: 'Error de conexión' };
        }
    }

    static async clear() {
        try {
            const response = await fetchWithCsrf(appRoute('cartClearUrl'), {
                method: 'POST'
            });
            return await readJsonResponse(response);
        } catch (error) {
            console.error('No se pudo vaciar el carrito:', error);
            return { success: false, message: 'Error de conexión' };
        }
    }
}

// =============================================================================
// WISHLIST FUNCTIONALITY
// =============================================================================

export class Wishlist {
    static async toggle(productId) {
        try {
            const response = await fetchWithCsrf(appRoute('wishlistToggleUrl'), {
                method: 'POST',
                body: JSON.stringify({ producto_id: productId })
            });
            return await readJsonResponse(response);
        } catch (error) {
            console.error('No se pudo actualizar favoritos:', error);
            return { success: false, message: 'No se pudieron actualizar tus favoritos.' };
        }
    }
}

// =============================================================================
// QUANTITY CONTROLS
// =============================================================================

export function initQuantityControls() {
    document.querySelectorAll('.product-quantity-control .qty-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const container = this.closest('.product-quantity-control');
            const input = container.querySelector('.qty-input');
            const action = this.dataset.action;
            const current = parseInt(input.value) || 1;
            let newVal = current;

            if (action === 'increase') {
                newVal = Math.min(current + 1, parseInt(input.max, 10) || current);
            } else if (action === 'decrease') {
                newVal = Math.max(current - 1, 1);
            }

            if (newVal !== current) {
                input.value = newVal;
                updateProductQuantityButtons(container, newVal, parseInt(input.max, 10) || newVal);
            }
        });
    });
    document.querySelectorAll('.product-quantity-control').forEach(container => {
        const input = container.querySelector('.qty-input');
        updateProductQuantityButtons(container, parseInt(input.value, 10) || 1, parseInt(input.max, 10) || 0);
    });
}

function updateProductQuantityButtons(container, quantity, stock) {
    const decrease = container.querySelector('[data-action="decrease"]');
    const increase = container.querySelector('[data-action="increase"]');
    if (decrease) decrease.disabled = quantity <= 1;
    if (increase) increase.disabled = quantity >= stock;
}

export function initCartControls() {
    document.querySelectorAll('.cart-row').forEach(row => {
        row.querySelectorAll('.qty-btn').forEach(button => {
            button.addEventListener('click', async () => {
                if (button.disabled) return;
                const input = row.querySelector('.qty-input');
                const current = parseInt(input.value, 10) || 1;
                const stock = parseInt(row.dataset.stock, 10) || 0;
                const quantity = button.dataset.action === 'increase'
                    ? Math.min(current + 1, stock)
                    : Math.max(current - 1, 1);
                if (quantity === current) return;

                await updateCartRow(row, quantity);
            });
        });

        row.querySelector('.btn-remove')?.addEventListener('click', async () => {
            if (!confirm('¿Eliminar este producto del carrito?')) return;
            await updateCartRow(row, 0);
        });
    });

    document.getElementById('clearCart')?.addEventListener('click', async () => {
        if (!confirm('¿Vaciar todo el carrito?')) return;
        const result = await Cart.clear();
        if (!result.success) {
            showToast(result.message || 'No se pudo vaciar el carrito.', 'danger');
            return;
        }
        window.location.reload();
    });
}

async function updateCartRow(row, quantity) {
    const input = row.querySelector('.qty-input');
    const current = parseInt(input.value, 10) || 1;
    const buttons = [...row.querySelectorAll('button')];
    buttons.forEach(button => button.disabled = true);
    const result = await Cart.update(row.dataset.id, quantity);

    if (!result.success) {
        input.value = current;
        buttons.forEach(button => button.disabled = false);
        updateProductQuantityButtons(row.querySelector('.quantity-control'), current, parseInt(row.dataset.stock, 10));
        showToast(result.message || 'No se pudo actualizar la cantidad.', 'danger');
        return;
    }

    if (quantity === 0) {
        row.remove();
        if (!document.querySelector('.cart-row')) {
            window.location.reload();
            return;
        }
    } else {
        input.value = quantity;
        const item = result.item;
        if (item) {
            row.dataset.price = item.precio;
            row.dataset.stock = item.stock;
            row.querySelector('[data-cart-item-price]').textContent = formatPrice(item.precio);
            row.querySelector('[data-cart-line-subtotal]').textContent = formatPrice(item.subtotal);
        }
        buttons.forEach(button => button.disabled = false);
        updateProductQuantityButtons(row.querySelector('.quantity-control'), quantity, parseInt(row.dataset.stock, 10));
    }

    updateCartCount(result.quantity_count);
    const countLabel = document.querySelector('[data-cart-quantity-count]');
    if (countLabel) countLabel.textContent = `${result.quantity_count} unidad${result.quantity_count === 1 ? '' : 'es'}`;
    const subtotalLabel = document.querySelector('[data-cart-subtotal-label]');
    if (subtotalLabel) subtotalLabel.textContent = `Subtotal (${result.quantity_count} unidad${result.quantity_count === 1 ? '' : 'es'})`;
    const subtotal = document.querySelector('[data-cart-subtotal]');
    const tax = document.querySelector('[data-cart-tax]');
    const total = document.querySelector('[data-cart-total]');
    if (subtotal) subtotal.textContent = formatPrice(result.subtotal);
    if (tax) tax.textContent = formatPrice(result.tax);
    if (total) total.textContent = formatPrice(result.total);
    document.querySelectorAll('[data-cart-checkout]').forEach(checkoutLink => {
        checkoutLink.classList.toggle('disabled', !result.can_checkout);
        checkoutLink.setAttribute('aria-disabled', result.can_checkout ? 'false' : 'true');
        if (result.can_checkout) checkoutLink.removeAttribute('tabindex');
        else checkoutLink.setAttribute('tabindex', '-1');
    });
}
// =============================================================================
// PRODUCT GALLERY
// =============================================================================

export function initProductGallery() {
    const mainImage = document.getElementById('mainImage');
    const thumbnails = document.querySelectorAll('.thumb');

    thumbnails.forEach(thumb => {
        thumb.addEventListener('click', function() {
            thumbnails.forEach(t => t.classList.remove('active'));
            this.classList.add('active');
            if (mainImage) {
                mainImage.src = this.dataset.img;
            }
        });
    });
}

// =============================================================================
// ADD TO CART BUTTONS
// =============================================================================

export function initAddToCartButtons() {
    document.addEventListener('click', async function(e) {
        const btn = e.target.closest('.btn-add-cart, .btn-add-to-cart');
        if (!btn) return;

        e.preventDefault();
        
        if (btn.disabled) return;
        
        const productId = btn.dataset.id;
        const quantityInput = document.getElementById('quantity');
        const quantity = quantityInput ? parseInt(quantityInput.value) || 1 : 1;
        const originalText = btn.innerHTML;

        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Agregando...';

        const result = await Cart.add(productId, quantity);

        if (result.success) {
            btn.innerHTML = '<i class="fas fa-check me-2"></i>¡Agregado!';
            btn.classList.remove('btn-outline-poke', 'btn-poke-primary');
            btn.classList.add('btn-success');
            updateCartCount(result.quantity_count);
            showToast('Producto agregado al carrito', 'success');

            setTimeout(() => {
                btn.innerHTML = originalText;
                btn.classList.remove('btn-success');
                btn.classList.add(btn.classList.contains('btn-add-cart') ? 'btn-outline-poke' : 'btn-poke-primary');
                btn.disabled = false;
            }, 2000);
        } else {
            btn.innerHTML = originalText;
            btn.disabled = false;
            showToast(result.message || 'Error al agregar', 'danger');
        }
    });
}

// =============================================================================
// WISHLIST BUTTONS
// =============================================================================

export function initWishlistButtons() {
    document.addEventListener('click', async function(e) {
        const btn = e.target.closest('.btn-wishlist');
        if (!btn) return;

        if (btn.disabled) return;
        btn.disabled = true;
        const result = await Wishlist.toggle(btn.dataset.id);
        btn.disabled = false;
        if (!result.success) {
            showToast(result.message || 'No se pudieron actualizar tus favoritos.', 'danger');
            return;
        }

        btn.dataset.favorited = result.is_favorite ? 'true' : 'false';
        btn.setAttribute('aria-pressed', result.is_favorite ? 'true' : 'false');
        btn.classList.toggle('active', result.is_favorite);
        btn.classList.toggle('btn-danger', result.is_favorite);
        btn.classList.toggle('btn-outline-secondary', !result.is_favorite);
        if (btn.hasAttribute('aria-label')) {
            btn.setAttribute(
                'aria-label',
                `${result.is_favorite ? 'Quitar de' : 'Agregar a'} favoritos: ${btn.dataset.productName}`
            );
            btn.innerHTML = '<i class="fas fa-heart" aria-hidden="true"></i>';
        } else {
            btn.innerHTML = `<i class="fas fa-heart me-2"></i>${result.is_favorite ? 'En Favoritos' : 'Añadir a Favoritos'}`;
        }
        showToast(result.message, result.is_favorite ? 'success' : 'info');
    });
}

export function initPaymentMethods() {
    const methods = document.querySelectorAll('.payment-method');
    methods.forEach(method => {
        const input = method.querySelector('input[name="payment_method"]');
        if (!input) return;
        method.classList.toggle('active', input.checked);
        input.addEventListener('change', () => {
            methods.forEach(item => item.classList.toggle(
                'active',
                item.querySelector('input[name="payment_method"]')?.checked === true
            ));
        });
    });
}

// =============================================================================
// SCROLL TO TOP
// =============================================================================

export function initScrollToTop() {
    const scrollTopBtn = document.getElementById('scrollTopBtn') || 
                         document.querySelector('.scroll-top');

    if (!scrollTopBtn) return;

    window.addEventListener('scroll', () => {
        if (window.scrollY > 300) {
            scrollTopBtn.classList.add('show');
        } else {
            scrollTopBtn.classList.remove('show');
        }
    });

    scrollTopBtn.addEventListener('click', (e) => {
        e.preventDefault();
        window.scrollTo({ top: 0, behavior: 'smooth' });
    });
}

// =============================================================================
// SEARCH AUTOCOMPLETE
// =============================================================================

export function initSearchAutocomplete() {
    const searchInput = document.getElementById('txtBuscar');
    const autocompleteContainer = document.getElementById('listaPokemon');
    const searchForm = searchInput?.closest('.store-search');

    if (!searchInput || !autocompleteContainer || !searchForm) return;

    let activeRequest;
    let requestId = 0;

    const showMessage = (message) => {
        autocompleteContainer.replaceChildren();
        const item = document.createElement('div');
        item.className = 'search-suggestion-message';
        item.textContent = message;
        autocompleteContainer.appendChild(item);
        autocompleteContainer.classList.add('show');
    };

    const renderProducts = (products) => {
        autocompleteContainer.replaceChildren();
        if (products.length === 0) {
            showMessage('No encontramos Pokémon con ese nombre.');
            return;
        }

        products.forEach((product) => {
            const link = document.createElement('a');
            link.className = 'search-suggestion';
            link.href = searchForm.dataset.productUrl.replace('PRODUCT_ID', encodeURIComponent(product.id));
            link.setAttribute('role', 'option');

            const image = document.createElement('img');
            image.src = product.image_url || `${searchForm.dataset.imagePrefix}placeholder.svg`;
            image.alt = '';
            image.loading = 'lazy';
            image.dataset.pokemonName = product.nombrePro;

            const details = document.createElement('span');
            details.className = 'search-suggestion-details';
            const name = document.createElement('strong');
            name.textContent = product.nombrePro;
            const meta = document.createElement('small');
            const price = Number(product.precioPro);
            meta.textContent = [
                product.categoria_nombre,
                Number.isFinite(price) ? formatPrice(price) : ''
            ].filter(Boolean).join(' · ');

            details.append(name, meta);
            link.append(image, details);
            autocompleteContainer.appendChild(link);
        });
        void loadMissingProductArtwork(autocompleteContainer);
        autocompleteContainer.classList.add('show');
    };

    const debouncedSearch = debounce(async (query) => {
        if (query.length < 2) {
            activeRequest?.abort();
            autocompleteContainer.replaceChildren();
            autocompleteContainer.classList.remove('show');
            return;
        }

        activeRequest?.abort();
        activeRequest = new AbortController();
        const currentRequestId = ++requestId;
        showMessage('Buscando Pokémon…');

        try {
            const searchUrl = searchForm.dataset.searchUrl.replace('SEARCH_TERM', encodeURIComponent(query));
            const response = await fetch(searchUrl, {
                headers: { Accept: 'application/json' },
                signal: activeRequest.signal
            });
            if (!response.ok) {
                throw new Error(`Search request failed with status ${response.status}`);
            }

            const payload = await response.json();
            if (!payload.success || !Array.isArray(payload.data)) {
                throw new Error('Search response did not contain product results');
            }
            if (currentRequestId === requestId) renderProducts(payload.data);
        } catch (error) {
            if (error.name !== 'AbortError') {
                console.error('Search error:', error);
                if (currentRequestId === requestId) showMessage('No se pudo completar la búsqueda. Intenta de nuevo.');
            }
        }
    }, 300);

    searchInput.addEventListener('input', function() {
        debouncedSearch(this.value.trim());
    });

    // Close on click outside
    document.addEventListener('click', (e) => {
        if (!searchInput.contains(e.target) && !autocompleteContainer.contains(e.target)) {
            autocompleteContainer.classList.remove('show');
        }
    });

    // Focus handling
    searchInput.addEventListener('focus', () => {
        if (searchInput.value.length >= 2) {
            debouncedSearch(searchInput.value.trim());
        }
    });
}

// =============================================================================
// CATEGORY CAROUSEL (for shop page)
// =============================================================================

export function initCategoryCarousel() {
    const carousel = document.getElementById('categoriesCarousel');
    if (!carousel) return;

    // Auto-scroll on hover for desktop
    let scrollInterval;
    
    carousel.addEventListener('mouseenter', () => {
        clearInterval(scrollInterval);
    });

    carousel.addEventListener('mouseleave', () => {
        scrollInterval = setInterval(() => {
            carousel.scrollBy({ left: 200, behavior: 'smooth' });
        }, 3000);
    });

    // Start auto-scroll
    scrollInterval = setInterval(() => {
        if (!carousel.matches(':hover')) {
            carousel.scrollBy({ left: 200, behavior: 'smooth' });
            
            // Loop back to start
            if (carousel.scrollLeft + carousel.clientWidth >= carousel.scrollWidth - 10) {
                carousel.scrollTo({ left: 0, behavior: 'smooth' });
            }
        }
    }, 3000);
}

// =============================================================================
// FLOATING POKEBALL ANIMATIONS
// =============================================================================

export function initFloatingPokeballs() {
    const pokeballs = document.querySelectorAll('.floating-pokeball');
    
    pokeballs.forEach((ball, index) => {
        // Randomize animation slightly
        ball.style.animationDelay = `${-index * 2}s`;
        ball.style.animationDuration = `${8 + index * 0.5}s`;
    });
}

// =============================================================================
// INITIALIZATION
// =============================================================================

document.addEventListener('DOMContentLoaded', () => {
    // Initialize all components
    initScrollToTop();
    initQuantityControls();
    initCartControls();
    initProductGallery();
    initAddToCartButtons();
    initWishlistButtons();
    initPaymentMethods();
    initSearchAutocomplete();
    initCategoryCarousel();
    initFloatingPokeballs();

    // Initialize Bootstrap components
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    const popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
    popoverTriggerList.map(function (popoverTriggerEl) {
        return new bootstrap.Popover(popoverTriggerEl);
    });

    console.log('🎮 PokéTienda Shop initialized!');
});

// =============================================================================
// EXPORTS
// =============================================================================

export default {
    showToast,
    updateCartCount,
    formatPrice,
    debounce,
    fetchWithCsrf,
    Cart,
    Wishlist,
    initQuantityControls,
    initCartControls,
    initProductGallery,
    initAddToCartButtons,
    initWishlistButtons,
    initPaymentMethods,
    initScrollToTop,
    initSearchAutocomplete,
    initCategoryCarousel,
    initFloatingPokeballs
};