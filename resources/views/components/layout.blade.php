<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @vite('resources/css/app.css')
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="/sweetalert2@11.js"></script>
    <title>StockUp</title>
</head>

<body class="bg-gray-100" x-data="{ isOpen: false, isAsideOpen: false }">
    <x-navbar></x-navbar>
    <div class="flex h-screen">
        <x-sidebar class="flex-1"></x-sidebar>
        <div class="flex-1 flex min-h-screen bg-gray-100 justify-center z-10">
            {{ $slot }}</div>
    </div>

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

        // // sweet alert
        // // revisi bang tambahin ditiap blade yang pake sweet alert
        // document.addEventListener('DOMContentLoaded', function() {
        //     @if (session('success'))
        //         Swal.fire({
        //             icon: 'success',
        //             title: 'Sukses',
        //             text: '{{ session('success') }}',
        //             confirmButtonText: 'OK'
        //         });
        //     @endif

        //     @if (session('error'))
        //         Swal.fire({
        //             icon: 'error',
        //             title: 'Terjadi Kesalahan',
        //             text: '{{ session('error') }}',
        //             confirmButtonText: 'OK'
        //         });
        //     @endif
        // });
    </script>
</body>

</html>
