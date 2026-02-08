# Update all view files with correct price logic

Write-Host "Updating view files with correct price logic..."

# 1. Update product detail page (show.blade.php)
$showFile = 'resources\views\frontend\products\show.blade.php'
$content = Get-Content $showFile -Raw
$content = $content -replace '@if\(\$product->variants->count\(\) > 0\)', '@if($product->variants->count() > 0 && $product->variants->min(''price'') > 0)'
$content | Set-Content $showFile -NoNewline
Write-Host "✅ Updated $showFile"

# 2. Update products index page (index.blade.php) - has 2 occurrences
$indexFile = 'resources\views\frontend\products\index.blade.php'
$content = Get-Content $indexFile -Raw
$content = $content -replace '@if\(\$product->variants->count\(\) > 0\)', '@if($product->variants->count() > 0 && $product->variants->min(''price'') > 0)'
$content | Set-Content $indexFile -NoNewline
Write-Host "✅ Updated $indexFile"

Write-Host "`n✨ All view files updated successfully!"
Write-Host "Run 'php artisan view:clear' to clear cache"
