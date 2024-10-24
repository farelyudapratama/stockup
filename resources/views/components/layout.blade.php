<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @vite('resources/css/app.css')
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

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

    <script>
        document.getElementById('products-container').addEventListener('input', function() {
            const productItems = document.querySelectorAll('.product-item');
            let total = 0;

            productItems.forEach(item => {
                const quantity = item.querySelector('input[name*="[quantity]"]').value || 0;
                const unit_price = item.querySelector('input[name*="[unit_price]"]').value || 0;
                const subtotal = quantity * unit_price;

                total += subtotal;
            });

            document.getElementById('total_amount').value = total.toFixed(2);
        });
    </script>
</body>

</html>
