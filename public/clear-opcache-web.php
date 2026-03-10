<?php

/**
 * Web-based Opcache Clear Script
 * Visit this file in browser to clear opcache
 */
if (function_exists('opcache_reset')) {
    $result = opcache_reset();
    if ($result) {
        echo "<h1 style='color: green;'>✅ Opcache Cleared Successfully!</h1>";
        echo '<p>Now try editing your product again.</p>';
    } else {
        echo "<h1 style='color: red;'>❌ Failed to clear opcache</h1>";
    }
} else {
    echo "<h1 style='color: orange;'>⚠️ Opcache is not enabled</h1>";
    echo '<p>This means opcache is already disabled or not installed.</p>';
}

echo '<hr>';
echo '<h2>Opcache Status:</h2>';
if (function_exists('opcache_get_status')) {
    $status = opcache_get_status();
    echo '<pre>';
    print_r($status);
    echo '</pre>';
}

echo '<hr>';
echo "<p><a href='/admin/products'>← Back to Products</a></p>";
