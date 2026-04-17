/**
 * Global UX Fixes
 * Prevents unintended keyboard shortcuts and improves general interactions
 */

(function($) {
    'use strict';

    /**
     * Patch EventTarget.prototype.addEventListener to handle passive events
     * from third-party plugins (Slick, Owl Carousel, etc.) that might call preventDefault()
     */
    (function() {
        const originalAddEventListener = EventTarget.prototype.addEventListener;
        EventTarget.prototype.addEventListener = function(type, listener, options) {
            let addOptions = options;
            if (['touchstart', 'touchmove', 'wheel', 'mousewheel'].includes(type)) {
                if (typeof options === 'object') {
                    if (options.passive === undefined) {
                        addOptions = Object.assign({}, options, { passive: false });
                    }
                } else if (typeof options === 'boolean') {
                    addOptions = { capture: options, passive: false };
                } else {
                    addOptions = { passive: false };
                }
            }
            originalAddEventListener.call(this, type, listener, addOptions);
        };
    })();

    $(document).ready(function() {
        // Prevent "/" key from focusing search if not intended or if it causes "No matching results"
        // Most templates use "/" to focus search. If it's causing issues, we handle it here.
        $(document).on('keydown', function(e) {
            // Forward slash is keyCode 191
            if (e.keyCode === 191 && !$(e.target).is('input, textarea, [contenteditable="true"]')) {
                // Focus the desktop search properly and CLEAR it to avoid "No matching results"
                const $searchDesktop = $('#search-input-desktop');
                if ($searchDesktop.length) {
                    e.preventDefault();
                    $searchDesktop.focus().val('');
                    // Trigger input event to clear suggestions
                    $searchDesktop.trigger('input');
                }
            }
        });

        /**
         * ⚛️ AGGRESSIVE ACCESSIBILITY GUARD (Double-Bootstrap Conflict Fix)
         * Modern browsers (Chrome 124+) block 'aria-hidden="true"' on elements containing focus.
         * This observer ensures that any visible modal NEVER has aria-hidden="true" or "inert",
         * even if conflicting legacy scripts attempt to set them.
         */
        const enforceModalA11y = (modal) => {
            const isVisible = modal.classList.contains('show') || (window.getComputedStyle(modal).display !== 'none');
            if (isVisible) {
                if (modal.getAttribute('aria-hidden') === 'true') {
                    modal.removeAttribute('aria-hidden');
                }
                if (modal.hasAttribute('inert')) {
                    modal.removeAttribute('inert');
                }
            } else {
                // Only set hidden state if no child has focus (standard safety)
                if (!modal.contains(document.activeElement)) {
                    modal.setAttribute('aria-hidden', 'true');
                    modal.setAttribute('inert', '');
                }
            }
        };

        const modalObserver = new MutationObserver((mutations) => {
            mutations.forEach((mutation) => {
                if (mutation.type === 'attributes') {
                    enforceModalA11y(mutation.target);
                }
            });
        });

        // Apply to all existing and future modals
        const setupModals = () => {
            document.querySelectorAll('.modal').forEach(modal => {
                enforceModalA11y(modal);
                modalObserver.observe(modal, { 
                    attributes: true, 
                    attributeFilter: ['class', 'style', 'aria-hidden', 'inert'] 
                });
            });
        };

        setupModals();

        // Watch for dynamically added modals
        new MutationObserver((mutations) => {
            mutations.forEach(m => m.addedNodes.forEach(node => {
                if (node.nodeType === 1) {
                    if (node.classList.contains('modal')) setupModals();
                    else if (node.querySelectorAll('.modal').length) setupModals();
                }
            }));
        }).observe(document.body, { childList: true, subtree: true });

        console.log('Elite UX Fixes initialized.');
    });

})(jQuery);
