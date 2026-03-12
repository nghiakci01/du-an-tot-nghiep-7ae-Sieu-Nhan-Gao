<?php
$path = __DIR__ . '/storage/products/product12.jpg';
if (file_exists($path)) {
    echo "Symlink is valid, file exists! Size: " . filesize($path) . "\n";
} else {
    echo "Symlink is broken or file does not exist!\n";
}

$target = readlink(__DIR__ . '/storage');
echo "Symlink target is: " . ($target !== false ? $target : 'NOT A SYMLINK') . "\n";
