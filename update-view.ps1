$content = Get-Content 'resources\views\frontend\home.blade.php' -Raw

# Replace first occurrence (featured products - line 194)
$content = $content -replace '(@if\(\$product->variants->count\(\) > 0\))(\r?\n\s+@if\(\$product->variants->min)', '@if($product->variants->count() > 0 && $product->variants->min(''price'') > 0)$2'

# Replace second occurrence (new products - line 284)  
$pattern = '(@if\(\$product->variants->count\(\) > 0\))(?!.*@if\(\$product->variants->count\(\) > 0\))'
$replacement = '@if($product->variants->count() > 0 && $product->variants->min(''price'') > 0)'
$content = $content -replace $pattern, $replacement

$content | Set-Content 'resources\views\frontend\home.blade.php' -NoNewline

Write-Host "Updated home.blade.php successfully"
