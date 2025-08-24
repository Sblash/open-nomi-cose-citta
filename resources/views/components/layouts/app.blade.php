<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Nomi Cose Città</title>
    <livewire:styles />
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>
    <header class="bg-blue-600 text-white py-4">
        <div class="container mx-auto px-4">
            <h1 class="text-4xl font-bold">Nomi Cose Città</h1>
        </div>
    </header>
    <div class="container mx-auto px-4 py-8 sm:px-6 lg:px-8">
        {{ $slot }}
    </div>
    <livewire:scripts />
</body>
</html>
