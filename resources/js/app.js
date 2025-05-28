import './bootstrap';
import AOS from 'aos';
import 'aos/dist/aos.css';
import Alpine from 'alpinejs';
import intersect from '@alpinejs/intersect';
import focus from '@alpinejs/focus';

// Initialize Alpine.js with plugins
window.Alpine = Alpine;
Alpine.plugin(intersect);
Alpine.plugin(focus);
Alpine.start();

// Format price with Naira symbol
window.formatPrice = (price) => {
    if (!price && price !== 0) return '₦0.00';
    const amount = parseFloat(price);
    if (isNaN(amount)) return '₦0.00';
    return '₦' + amount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
};

// Initialize AOS (Animate On Scroll)
if (typeof AOS !== 'undefined') {
    AOS.init({
        duration: 600,
        once: true,
        offset: 100
    });
}

// Initialize cart functionality
window.initializeCart = function() {
    console.log('Initializing cart...');
    
    // Update cart count on page load
    updateCartCount();
    
    // Set up event listeners for cart actions
    document.addEventListener('click', function(e) {
        // Handle cart toggles - only prevent default for cart actions
        const cartToggle = e.target.closest('[data-cart-toggle]');
        const addToCartBtn = e.target.closest('.add-to-cart');
        
        if (cartToggle) {
            e.preventDefault();
            e.stopPropagation();
            const dropdown = document.querySelector('.cart-dropdown');
            if (dropdown) {
                dropdown.classList.toggle('hidden');
            }
        } else if (addToCartBtn) {
            // Only prevent default for add to cart buttons
            e.preventDefault();
            e.stopPropagation();
            
            const productId = addToCartBtn.dataset.productId;
            if (productId) {
                addToCart(productId, e);
            }
            return false;
        }
    });
};

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    // Initialize AOS
    AOS.init({
        duration: 800,
        easing: 'ease-in-out',
        once: true,
        offset: 100,
        delay: 100
    });
    
    // Initialize tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
    
    // Initialize cart
    initializeCart();
});

// Initialize Alpine.js components
document.addEventListener('alpine:init', () => {
    // Mobile menu component
    Alpine.data('mobileMenu', () => ({
        open: false,
        toggle() {
            this.open = !this.open;
        },
        close() {
            this.open = false;
        },
        toggleSearch() {
            const searchInput = this.$refs.searchInput;
            if (searchInput) searchInput.focus();
        }
    }));
    
    // Sticky header component
    Alpine.data('stickyHeader', () => ({
        init() {
            const header = this.$el;
            let lastScroll = 0;
            
            window.addEventListener('scroll', () => {
                const currentScroll = window.pageYOffset;
                
                if (currentScroll <= 0) {
                    header.classList.remove('scrolled-down');
                    header.classList.remove('scrolled-up');
                    return;
                }
                
                if (currentScroll > lastScroll && !header.classList.contains('scrolled-down')) {
                    // Scroll down
                    header.classList.remove('scrolled-up');
                    header.classList.add('scrolled-down');
                } else if (currentScroll < lastScroll && header.classList.contains('scrolled-down')) {
                    // Scroll up
                    header.classList.remove('scrolled-down');
                    header.classList.add('scrolled-up');
                }
                
                lastScroll = currentScroll;
            });
        }
    }));
});

// Cart array to store items
let cart = [];

// Update cart count in the UI
function updateCartCount(count = null) {
    if (count === null) {
        count = cart.reduce((total, item) => total + (item.quantity || 0), 0);
    }
    
    const countElements = document.querySelectorAll('.cart-count');
    
    countElements.forEach(el => {
        // Animate the count change
        if (parseInt(el.textContent) !== count) {
            el.classList.add('animate-ping');
            setTimeout(() => el.classList.remove('animate-ping'), 300);
        }
        
        el.textContent = count;
        el.classList.toggle('hidden', count === 0);
        
        // Update aria-label for screen readers
        el.setAttribute('aria-label', `${count} items in cart`);
    });
}

// Update cart totals in the UI
function updateCartTotals() {
    const subtotal = cart.reduce((total, item) => total + (item.price * item.quantity), 0);
    const tax = subtotal * 0.1; // 10% tax
    const total = subtotal + tax;
    
    // Update UI
    const subtotalEl = document.getElementById('cart-subtotal');
    const taxEl = document.getElementById('cart-tax');
    const totalEl = document.getElementById('cart-total');
    
    if (subtotalEl) subtotalEl.textContent = formatPrice(subtotal);
    if (taxEl) taxEl.textContent = formatPrice(tax);
    if (totalEl) totalEl.textContent = formatPrice(total);
}

// Initialize cart when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    // Load cart from localStorage if available
    const savedCart = localStorage.getItem('cart');
    if (savedCart) {
        try {
            cart = JSON.parse(savedCart);
            updateCartCount();
            updateCartTotals();
        } catch (e) {
            console.error('Error loading cart from localStorage:', e);
            localStorage.removeItem('cart');
            cart = [];
        }
    }
    
    // Initialize tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
    
    // Initialize quantity selectors
    document.querySelectorAll('.quantity-selector').forEach(selector => {
        selector.addEventListener('click', function(e) {
            e.preventDefault();
            const input = this.parentElement.querySelector('input');
            const currentVal = parseInt(input.value) || 0;
            
            if (this.classList.contains('decrease') && currentVal > 1) {
                input.value = currentVal - 1;
            } else if (this.classList.contains('increase') && currentVal < 100) {
                input.value = currentVal + 1;
            }
            
            // Trigger change event to update cart if needed
            input.dispatchEvent(new Event('change'));
        });
    });
    
    // Initialize quick view modals
    document.querySelectorAll('.quick-view').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const productId = this.getAttribute('data-product-id');
            // Here you would typically fetch product details via AJAX
            // and populate the quick view modal
            console.log('Quick view for product:', productId);
        });
    });
    
    // Initialize wishlist buttons
    document.querySelectorAll('.wishlist-btn').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const productId = this.getAttribute('data-product-id');
            this.classList.toggle('text-red-500');
            // Here you would typically handle the wishlist functionality
            console.log('Toggled wishlist for product:', productId);
        });
    });
});

// Add to cart with animation and AJAX
async function addToCart(productId, event) {
    event.preventDefault();
    
    // Find the button that was clicked
    const button = event.target.closest('.add-to-cart');
    if (!button) return;
    
    // Check if already processing
    if (button.hasAttribute('data-loading')) return;
    button.setAttribute('data-loading', 'true');
    
    // Get product card and details
    const productCard = button.closest('.product-card');
    const productName = productCard.querySelector('.product-title')?.textContent.trim() || 'Product';
    const productPrice = parseFloat(button.getAttribute('data-price') || 0);
    const productImage = productCard.querySelector('img')?.src || '';
    
    // Create a clone of the product image for animation
    const imgClone = productCard.querySelector('img')?.cloneNode(true);
    if (imgClone) {
        imgClone.style.position = 'fixed';
        imgClone.style.width = '60px';
        imgClone.style.height = '60px';
        imgClone.style.borderRadius = '50%';
        imgClone.style.objectFit = 'cover';
        imgClone.style.zIndex = '9999';
        imgClone.style.pointerEvents = 'none';
        imgClone.style.transition = 'all 0.8s cubic-bezier(0.86, 0, 0.07, 1)';
        imgClone.style.boxShadow = '0 10px 25px rgba(0,0,0,0.2)';
        
        // Get button and cart icon positions
        const buttonRect = button.getBoundingClientRect();
        const cartIcon = document.querySelector('.cart-icon');
        const cartRect = cartIcon ? cartIcon.getBoundingClientRect() : { left: window.innerWidth - 50, top: 20 };
        
        // Position the clone at the button
        imgClone.style.left = `${buttonRect.left + buttonRect.width / 2}px`;
        imgClone.style.top = `${buttonRect.top}px`;
        
        // Add to DOM
        document.body.appendChild(imgClone);
        
        // Animate to cart
        setTimeout(() => {
            imgClone.style.left = `${cartRect.left}px`;
            imgClone.style.top = `${cartRect.top}px`;
            imgClone.style.opacity = '0.5';
            imgClone.style.transform = 'scale(0.5)';
        }, 10);
        
        // Remove after animation
        setTimeout(() => {
            imgClone.remove();
        }, 800);
        
        // Bounce cart icon
        if (cartIcon) {
            cartIcon.classList.add('animate-bounce');
            setTimeout(() => {
                cartIcon.classList.remove('animate-bounce');
            }, 1000);
        }
    }
};

// Remove item from cart
window.removeFromCart = function(itemId) {
    const itemIndex = cart.findIndex(item => item.id === itemId);
    
    if (itemIndex !== -1) {
        cart.splice(itemIndex, 1);
        updateCartCount();
        
        // Update UI
        const cartItem = document.querySelector(`.cart-item[data-id="${itemId}"]`);
        if (cartItem) {
            cartItem.classList.add('opacity-0', 'h-0', 'overflow-hidden', 'transition-all', 'duration-300');
            
            // Remove from DOM after animation
            setTimeout(() => {
                cartItem.remove();
                updateCartTotals();
            }, 300);
        }
    }
};

// Update cart quantity
window.updateCartQuantity = function(input) {
    if (!input) return;
    
    const form = input.closest('form');
    const quantity = parseInt(input.value);
    const itemId = input.dataset?.id;
    
    if (isNaN(quantity) || quantity < 1) {
        input.value = 1;
        return;
    }
    
    // Update cart
    const item = cart.find(item => item.id === itemId);
    if (item) {
        item.quantity = quantity;
        updateCartCount();
        updateCartTotals();
    }
    
    // Submit form if it exists
    if (form) {
        form.submit();
    }
};
