<?php

// Clear opcache script
if (function_exists('opcache_reset')) {
    opcache_reset();
    echo "OPcache cleared successfully!\n";
} else {
    echo "OPcache is not enabled.\n";
}

// Also clear realpath cache
clearstatcache(true);
echo "Realpath cache cleared!\n";

echo "\nPlease restart your web server (Laragon) now.\n";
