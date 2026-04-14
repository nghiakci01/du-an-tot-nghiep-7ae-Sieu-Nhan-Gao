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

        console.log('Elite UX Fixes initialized.');
    });

})(jQuery);
