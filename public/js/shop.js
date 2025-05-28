// Handle add to cart functionality
document.addEventListener('DOMContentLoaded', function() {
    // Add to cart button click handler
    document.addEventListener('click', async function(e) {
        const addToCartBtn = e.target.closest('.add-to-cart');
        
        if (addToCartBtn) {
            e.preventDefault();
            const productId = addToCartBtn.dataset.productId;
            
            try {
                const response = await fetch('/cart/add', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        product_id: productId,
                        quantity: 1
                    })
                });
                
                const data = await response.json();
                
                if (response.ok) {
                    // Update cart count
                    await updateCartCount();
                    
                    // Show success message
                    showToast('Product added to cart', 'success');
                } else {
                    throw new Error(data.message || 'Failed to add to cart');
                }
            } catch (error) {
                console.error('Error adding to cart:', error);
                showToast(error.message || 'Failed to add to cart', 'error');
            }
        }
    });
    
    // Update cart count on page load
    updateCartCount();
});

// Function to update cart count
async function updateCartCount() {
    try {
        const response = await fetch('/cart/count');
        if (response.ok) {
            const data = await response.json();
            const countElements = document.querySelectorAll('.cart-count');
            countElements.forEach(el => {
                el.textContent = data.count;
                el.classList.toggle('hidden', data.count === 0);
            });
        }
    } catch (error) {
        console.error('Error updating cart count:', error);
    }
}

// Function to show toast messages
function showToast(message, type = 'info') {
    // Create toast container if it doesn't exist
    let toastContainer = document.getElementById('toast-container');
    if (!toastContainer) {
        toastContainer = document.createElement('div');
        toastContainer.id = 'toast-container';
        toastContainer.className = 'fixed bottom-4 right-4 z-50 space-y-2';
        document.body.appendChild(toastContainer);
    }
    
    // Create toast element
    const toast = document.createElement('div');
    const types = {
        success: 'bg-green-500',
        error: 'bg-red-500',
        info: 'bg-blue-500',
        warning: 'bg-yellow-500'
    };
    
    toast.className = `text-white px-4 py-2 rounded shadow-lg flex items-center justify-between min-w-64 ${types[type] || types.info} animate-fade-in`;
    toast.innerHTML = `
        <span>${message}</span>
        <button class="ml-4 text-white hover:text-gray-200">&times;</button>
    `;
    
    // Add click handler to close button
    const closeBtn = toast.querySelector('button');
    closeBtn.addEventListener('click', () => {
        toast.remove();
    });
    
    // Auto-remove toast after 5 seconds
    setTimeout(() => {
        toast.classList.add('opacity-0', 'transition-opacity', 'duration-300');
        setTimeout(() => toast.remove(), 300);
    }, 5000);
    
    // Add to container
    toastContainer.appendChild(toast);
}

// Add fade-in animation
const style = document.createElement('style');
style.textContent = `
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in {
        animation: fadeIn 0.3s ease-out forwards;
    }
`;
document.head.appendChild(style);
