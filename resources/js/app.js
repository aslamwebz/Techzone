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
    if (!price) return '₦0.00';
    return '₦' + parseFloat(price).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
};

// Initialize AOS (Animate On Scroll)
document.addEventListener('DOMContentLoaded', function() {
    AOS.init({
        duration: 800,
        easing: 'ease-in-out',
        once: true,
        offset: 100,
        delay: 100
    });
    
    // Initialize cart functionality
    initializeCart();
    
    // Initialize tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
    
    // Add smooth scrolling for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            
            const targetId = this.getAttribute('href');
            if (targetId === '#') return;
            
            const targetElement = document.querySelector(targetId);
            if (targetElement) {
                targetElement.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
    
    // Initialize mobile menu
    document.addEventListener('alpine:init', () => {
        Alpine.data('mobileMenu', () => ({
            open: false,
            searchOpen: false,
            toggle() {
                this.open = !this.open;
            },
            close() {
                this.open = false;
            },
            toggleSearch() {
                this.searchOpen = !this.searchOpen;
            }
        }));
        
        // Sticky header
        Alpine.data('stickyHeader', () => ({
            init() {
                let lastScroll = 0;
                const header = document.getElementById('main-header');
                
                window.addEventListener('scroll', () => {
                    const currentScroll = window.pageYOffset;
                    
                    if (currentScroll <= 0) {
                        header.classList.remove('shadow-md');
                        header.classList.add('shadow-sm');
                        return;
                    }
                    
                    if (currentScroll > lastScroll && currentScroll > 100) {
                        // Scrolling down
                        header.classList.add('-translate-y-full');
                        header.classList.remove('shadow-md');
                    } else {
                        // Scrolling up
                        header.classList.remove('-translate-y-full');
                        header.classList.add('shadow-md');
                    }
                    
                    lastScroll = currentScroll;
                });
            }
        }));
    });
});

// Cart functionality
function initializeCart() {
    // Cart count update
    function updateCartCount() {
        fetch('/cart/count')
            .then(response => response.json())
            .then(data => {
                const cartCounts = document.querySelectorAll('.cart-count');
                cartCounts.forEach(countEl => {
                    if (data.count > 0) {
                        countEl.textContent = data.count;
                        countEl.classList.remove('hidden');
                    } else {
                        countEl.classList.add('hidden');
                    }
                });
            });
    }

    // Add to cart with animation
    window.addToCart = function(productId, event) {
        const button = event.target.closest('.add-to-cart');
        const productCard = button.closest('.product-card');
        const productImage = productCard.querySelector('img');
        const productName = productCard.querySelector('.product-name').textContent;
        const productPrice = productCard.querySelector('.product-price').textContent;
        
        // Disable button and show loading state
        button.disabled = true;
        button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Adding...';
        
        // Get the position of the cart icon
        const cartIcon = document.querySelector('[aria-label="Cart"]');
        const cartIconRect = cartIcon.getBoundingClientRect();
        
        // Create flying image
        const flyingImage = document.createElement('img');
        flyingImage.src = productImage.src;
        flyingImage.alt = productName;
        flyingImage.className = 'w-12 h-12 rounded-full object-cover border-2 border-white shadow-lg absolute z-50 pointer-events-none';
        
        // Position the flying image at the product
        const productRect = productImage.getBoundingClientRect();
        flyingImage.style.top = `${productRect.top + window.scrollY}px`;
        flyingImage.style.left = `${productRect.left + window.scrollX}px`;
        document.body.appendChild(flyingImage);
        
        // Animate to cart
        flyingImage.style.transition = 'all 0.8s cubic-bezier(0.68, -0.55, 0.265, 1.55)';
        flyingImage.style.opacity = '0.8';
        flyingImage.style.transform = `translate(${cartIconRect.left - productRect.left}px, ${cartIconRect.top - productRect.top}px) scale(0.2)`;
        
        // Send AJAX request
        fetch(`/cart/add/${productId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                _token: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                quantity: 1
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update cart count
                updateCartCount();
                
                // Show success message
                showNotification('Added to cart!', 'success');
                
                // Remove flying image after animation
                setTimeout(() => {
                    flyingImage.remove();
                }, 800);
            } else {
                showNotification(data.message || 'Failed to add to cart', 'error');
                flyingImage.remove();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('An error occurred', 'error');
            flyingImage.remove();
        })
        .finally(() => {
            // Reset button state after a short delay
            setTimeout(() => {
                button.disabled = false;
                button.innerHTML = '<i class="fas fa-shopping-cart mr-2"></i> Add to Cart';
            }, 1000);
        });
    };
    
    // Remove item from cart
    window.removeFromCart = function(itemId) {
        if (!confirm('Are you sure you want to remove this item?')) return;
        
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/cart/remove/${itemId}`;
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        const method = document.createElement('input');
        method.type = 'hidden';
        method.name = '_method';
        method.value = 'DELETE';
        
        form.appendChild(csrfToken);
        form.appendChild(method);
        document.body.appendChild(form);
        form.submit();
    };
    
    // Update cart quantity
    window.updateCartQuantity = function(input) {
        const form = input.closest('form');
        const quantity = parseInt(input.value);
        
        if (quantity < 1 || isNaN(quantity)) {
            input.value = 1;
            return;
    clone.style.transform = 'scale(1)';
    document.body.appendChild(clone);

    setTimeout(() => {
        clone.style.left = `${cartRect.left}px`;
        clone.style.top = `${cartRect.top}px`;
        clone.style.transform = 'scale(0.5)';
        clone.style.opacity = '0.8';
    }, 10);

    setTimeout(() => {
        document.body.removeChild(clone);
        // Trigger cart count animation
        const cartCount = document.querySelector('.cart-count');
        if (cartCount) {
            cartCount.classList.add('animate-ping');
            setTimeout(() => cartCount.classList.remove('animate-ping'), 500);
        }
    }, 800);
};
