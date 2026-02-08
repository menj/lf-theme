/**
 * Main JavaScript for Langgam Fikir Theme
 */

(function() {
    'use strict';

    /**
     * Mobile Menu Toggle
     */
    function initMobileMenu() {
        const menuToggle = document.querySelector('.menu-toggle');
        const navigation = document.querySelector('.main-navigation');

        if (!menuToggle || !navigation) return;

        menuToggle.addEventListener('click', function() {
            const isExpanded = this.getAttribute('aria-expanded') === 'true';
            
            this.setAttribute('aria-expanded', !isExpanded);
            navigation.classList.toggle('active');
            
            // Toggle animation for hamburger icon
            const spans = this.querySelectorAll('span');
            if (spans.length === 3) {
                spans[0].style.transform = !isExpanded ? 'rotate(45deg) translate(5px, 5px)' : '';
                spans[1].style.opacity = !isExpanded ? '0' : '1';
                spans[2].style.transform = !isExpanded ? 'rotate(-45deg) translate(7px, -6px)' : '';
            }
        });

        // Close menu when clicking outside
        document.addEventListener('click', function(event) {
            if (!menuToggle.contains(event.target) && !navigation.contains(event.target)) {
                menuToggle.setAttribute('aria-expanded', 'false');
                navigation.classList.remove('active');
                
                const spans = menuToggle.querySelectorAll('span');
                if (spans.length === 3) {
                    spans[0].style.transform = '';
                    spans[1].style.opacity = '1';
                    spans[2].style.transform = '';
                }
            }
        });

        // Close menu on escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape' && navigation.classList.contains('active')) {
                menuToggle.setAttribute('aria-expanded', 'false');
                navigation.classList.remove('active');
                
                const spans = menuToggle.querySelectorAll('span');
                if (spans.length === 3) {
                    spans[0].style.transform = '';
                    spans[1].style.opacity = '1';
                    spans[2].style.transform = '';
                }
            }
        });
    }

    /**
     * Quantity Selector
     */
    function initQuantitySelector() {
        const quantityInputs = document.querySelectorAll('.quantity-input');
        
        quantityInputs.forEach(function(input) {
            const decreaseBtn = input.previousElementSibling;
            const increaseBtn = input.nextElementSibling;
            
            if (decreaseBtn && decreaseBtn.classList.contains('quantity-decrease')) {
                decreaseBtn.addEventListener('click', function() {
                    const currentValue = parseInt(input.value) || 1;
                    const minValue = parseInt(input.getAttribute('min')) || 1;
                    
                    if (currentValue > minValue) {
                        input.value = currentValue - 1;
                        input.dispatchEvent(new Event('change'));
                    }
                });
            }
            
            if (increaseBtn && increaseBtn.classList.contains('quantity-increase')) {
                increaseBtn.addEventListener('click', function() {
                    const currentValue = parseInt(input.value) || 1;
                    const maxValue = parseInt(input.getAttribute('max')) || 99;
                    
                    if (currentValue < maxValue) {
                        input.value = currentValue + 1;
                        input.dispatchEvent(new Event('change'));
                    }
                });
            }
            
            // Validate input on change
            input.addEventListener('change', function() {
                const value = parseInt(this.value);
                const minValue = parseInt(this.getAttribute('min')) || 1;
                const maxValue = parseInt(this.getAttribute('max')) || 99;
                
                if (isNaN(value) || value < minValue) {
                    this.value = minValue;
                } else if (value > maxValue) {
                    this.value = maxValue;
                }
            });
        });
    }

    /**
     * Smooth Scroll for Anchor Links
     */
    function initSmoothScroll() {
        document.querySelectorAll('a[href^="#"]').forEach(function(anchor) {
            anchor.addEventListener('click', function(e) {
                const href = this.getAttribute('href');
                
                if (href === '#' || href === '#0') return;
                
                const target = document.querySelector(href);
                
                if (target) {
                    e.preventDefault();
                    
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    }

    /**
     * Add to Cart (if WooCommerce is not active)
     */
    function initAddToCart() {
        const addToCartButtons = document.querySelectorAll('.add-to-cart-btn');
        
        addToCartButtons.forEach(function(button) {
            if (!button.hasAttribute('data-product-id')) return;
            
            button.addEventListener('click', function(e) {
                e.preventDefault();
                
                const productId = this.getAttribute('data-product-id');
                const quantityInput = document.querySelector('.quantity-input');
                const quantity = quantityInput ? parseInt(quantityInput.value) : 1;
                
                // This is a placeholder for custom add-to-cart functionality
                // If WooCommerce is active, it handles this automatically
                
                console.log('Adding to cart:', {
                    productId: productId,
                    quantity: quantity
                });
                
                // You can implement custom cart functionality here
                // or redirect to a contact page
            });
        });
    }

    /**
     * Lazy Load Images (simple implementation)
     */
    function initLazyLoad() {
        if ('IntersectionObserver' in window) {
            const images = document.querySelectorAll('img[loading="lazy"]');
            
            const imageObserver = new IntersectionObserver(function(entries, observer) {
                entries.forEach(function(entry) {
                    if (entry.isIntersecting) {
                        const img = entry.target;
                        
                        if (img.dataset.src) {
                            img.src = img.dataset.src;
                            img.removeAttribute('data-src');
                        }
                        
                        observer.unobserve(img);
                    }
                });
            });
            
            images.forEach(function(img) {
                imageObserver.observe(img);
            });
        }
    }

    /**
     * Initialize all functions when DOM is ready
     */
    function init() {
        initMobileMenu();
        initQuantitySelector();
        initSmoothScroll();
        initAddToCart();
        initLazyLoad();
    }

    // Wait for DOM to be ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

})();
