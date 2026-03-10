<!DOCTYPE html>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Realtime Test</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 flex items-center justify-center p-10 h-screen">
    <div class="bg-white p-6 rounded shadow-md max-w-sm text-center">
        <h1 class="text-xl font-bold mb-4">Laravel Reverb Test</h1>
        <p class="mb-4">Mở trang này ra. <br>Sau đó mở 1 tab terminal khác chạy `php artisan tinker`, gõ lệnh: <br><code class="bg-gray-200 px-2 py-1 rounded">event(new \App\Events\TestEvent('Hello'));</code></p>
        <p class="text-green-600 font-semibold italic">Đang chờ sự kiện...</p>
    </div>
</body>
</html>
