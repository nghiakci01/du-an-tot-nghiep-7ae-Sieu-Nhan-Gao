/**
 * Global UX Fixes
 * Prevents unintended keyboard shortcuts and improves general interactions
 */

(function($) {
    'use strict';

    $(document).ready(function() {
        // Prevent "/" key from focusing search if not intended or if it causes "No matching results"
        // Most templates use "/" to focus search. If it's causing issues, we handle it here.
        $(document).on('keydown', function(e) {
            // Forward slash is keyCode 191
            if (e.keyCode === 191) {
                // If not already in an input/textarea
                if (!$(e.target).is('input, textarea, [contenteditable="true"]')) {
                    // We can either prevent it:
                    // e.preventDefault();
                    
                    // Or we can focus the desktop search properly and CLEAR it to avoid "No matching results"
                    const $searchDesktop = $('#search-input-desktop');
                    if ($searchDesktop.length) {
                        e.preventDefault();
                        $searchDesktop.focus().val('');
                        // Trigger input event to clear suggestions
                        $searchDesktop.trigger('input');
                    }
                }
            }
        });

        console.log('Elite UX Fixes initialized.');
    });

})(jQuery);
