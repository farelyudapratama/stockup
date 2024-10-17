<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @vite('resources/css/app.css')
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <title>StockUp</title>
</head>

<body class="bg-gray-100" x-data="{ isOpen: false, isAsideOpen: false }">
    <x-navbar></x-navbar>
    <div class="flex h-screen">
        <x-sidebar class="flex-1"></x-sidebar>
        <div class="flex-1 flex  justify-center">{{ $slot }}</div>
    </div>

    <script>
        const folderTitles = document.querySelectorAll('.folder-title');

        folderTitles.forEach(title => {
            title.addEventListener('click', () => {
                const content = title.nextElementSibling;
                const toggleIcon = title.querySelector('.folder-toggle');

                content.classList.toggle('hidden');
                toggleIcon.classList.toggle('rotate-90');
            });
        });
    </script>
</body>

</html>
