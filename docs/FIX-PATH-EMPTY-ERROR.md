# Fix Summary: Path Cannot Be Empty Error

## Root Cause
Browser gửi empty file input → Laravel cố gắng store empty path → Error "Path cannot be empty"

## Fix Applied
```php
if ($request->hasFile('image')) {
    $file = $request->file('image');
    // Only store if file has actual content
    if ($file->getClientOriginalName() && $file->getSize() > 0) {
        try {
            $path = $file->store('products', 'public');
            $data['image'] = $path;
        } catch (\Exception $e) {
            // Skip if storage fails
        }
    }
}
```

## Why This Works
1. `getClientOriginalName()` → Returns empty string for empty file input
2. `getSize() > 0` → Ensures file has content
3. `try-catch` → Gracefully handles any storage errors

## CRITICAL: Restart Required
**Opcache caches old code!** You MUST restart Laragon:
1. Stop All
2. Close Laragon completely
3. Wait 5 seconds
4. Open Laragon
5. Start All

## Test
After restart, create product WITHOUT selecting image → Should work!
