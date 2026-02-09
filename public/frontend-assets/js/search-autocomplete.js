/**
 * Search Autocomplete Functionality
 * Provides real-time product suggestions as user types
 */

(function($) {
    'use strict';

    // Configuration
    const config = {
        minChars: 2,
        debounceDelay: 300,
        maxResults: 5
    };

    // Debounce function
    function debounce(func, wait) {
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

    // Format price with Vietnamese currency
    function formatPrice(price, salePrice) {
        const formatter = new Intl.NumberFormat('vi-VN');
        
        if (salePrice && salePrice > 0 && salePrice < price) {
            return `<span class="old-price">${formatter.format(price)} đ</span> ${formatter.format(salePrice)} đ`;
        }
        return `${formatter.format(price)} đ`;
    }

    // Create suggestion item HTML
    function createSuggestionItem(product) {
        return `
            <a href="${product.url}" class="search-suggestion-item" data-product-id="${product.id}">
                <img src="${product.image}" alt="${product.name}" class="search-suggestion-image">
                <div class="search-suggestion-info">
                    <div class="search-suggestion-name">${product.name}</div>
                    <div class="search-suggestion-price">${formatPrice(product.price, product.sale_price)}</div>
                </div>
            </a>
        `;
    }

    // Initialize autocomplete for a search input
    function initAutocomplete(inputId, dropdownId) {
        const $input = $(`#${inputId}`);
        const $dropdown = $(`#${dropdownId}`);
        
        if (!$input.length || !$dropdown.length) return;

        let currentIndex = -1;
        let suggestions = [];

        // Fetch suggestions from API
        const fetchSuggestions = debounce(function(query) {
            if (query.length < config.minChars) {
                $dropdown.removeClass('active').empty();
                return;
            }

            // Show loading state
            $dropdown.addClass('active').html('<div class="search-suggestions-loading"><i class="fa fa-spinner fa-spin"></i> Searching...</div>');

            // AJAX request
            $.ajax({
                url: '/search/suggestions',
                method: 'GET',
                data: { q: query },
                success: function(data) {
                    suggestions = data;
                    
                    if (data.length === 0) {
                        $dropdown.html('<div class="search-suggestions-empty">No products found</div>');
                    } else {
                        let html = '';
                        data.forEach(product => {
                            html += createSuggestionItem(product);
                        });
                        $dropdown.html(html);
                    }
                    
                    currentIndex = -1;
                },
                error: function() {
                    $dropdown.html('<div class="search-suggestions-empty">Error loading suggestions</div>');
                }
            });
        }, config.debounceDelay);

        // Input event handler
        $input.on('input', function() {
            const query = $(this).val().trim();
            fetchSuggestions(query);
        });

        // Keyboard navigation
        $input.on('keydown', function(e) {
            const $items = $dropdown.find('.search-suggestion-item');
            
            if (!$items.length) return;

            // Arrow Down
            if (e.keyCode === 40) {
                e.preventDefault();
                currentIndex = (currentIndex + 1) % $items.length;
                updateHighlight($items);
            }
            // Arrow Up
            else if (e.keyCode === 38) {
                e.preventDefault();
                currentIndex = currentIndex <= 0 ? $items.length - 1 : currentIndex - 1;
                updateHighlight($items);
            }
            // Enter
            else if (e.keyCode === 13 && currentIndex >= 0) {
                e.preventDefault();
                $items.eq(currentIndex)[0].click();
            }
            // Escape
            else if (e.keyCode === 27) {
                $dropdown.removeClass('active').empty();
                currentIndex = -1;
            }
        });

        // Update highlighted item
        function updateHighlight($items) {
            $items.removeClass('highlighted');
            if (currentIndex >= 0) {
                $items.eq(currentIndex).addClass('highlighted');
            }
        }

        // Click outside to close
        $(document).on('click', function(e) {
            if (!$(e.target).closest(`#${inputId}, #${dropdownId}`).length) {
                $dropdown.removeClass('active').empty();
                currentIndex = -1;
            }
        });

        // Focus event
        $input.on('focus', function() {
            const query = $(this).val().trim();
            if (query.length >= config.minChars && suggestions.length > 0) {
                $dropdown.addClass('active');
            }
        });
    }

    // Initialize on document ready
    $(document).ready(function() {
        // Desktop search
        initAutocomplete('search-input-desktop', 'search-suggestions-desktop');
        
        // Offcanvas search
        initAutocomplete('search-input-offcanvas', 'search-suggestions-offcanvas');
    });

})(jQuery);
