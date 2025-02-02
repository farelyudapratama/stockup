<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @vite('resources/css/app.css')
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="/sweetalert2@11.js"></script>
    <title>StockUp</title>
</head>

<body class="bg-gray-100" x-data="{ isOpen: false, isAsideOpen: false }">
    <x-navbar></x-navbar>
    <div class="flex h-screen">
        <x-sidebar class="flex-1"></x-sidebar>
        <div class="flex-1 min-h-screen bg-gray-100 justify-center z-10">
            {{ $slot }}</div>
    </div>

    <script>
        function formatRupiah(angka) {
            const cleanNumber = parseFloat(angka.toString().replace(/[^0-9]/g, ''));

            const formatter = new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0
            });

            return formatter.format(cleanNumber || 0);
        }

        function formatInputRupiah(element) {
            let inputVal = element.value.replace(/[^,\d]/g, '');
            if (inputVal) {
                element.value = formatRupiah(inputVal);
            } else {
                element.value = '';
            }
            calculateTotal();
        }

        function parseRupiahToNumber(rupiah) {
            return parseFloat(rupiah.replace(/[Rp,.]/g, '').replace(',', '.')) || 0;
        }

        function calculateTotal() {
            const productItems = document.querySelectorAll('.product-item');
            let total = 0;

            productItems.forEach(item => {
                const quantity = item.querySelector('input[name*="[quantity]"]').value || 0;
                const unitPriceText = item.querySelector('input[name*="[unit_price]"]').value || '0';
                const unitPrice = parseRupiahToNumber(unitPriceText);
                const subtotal = quantity * unitPrice;
                total += subtotal;
            });

            document.getElementById('total_amount').value = formatRupiah(total.toString());
        }

        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('products-container').addEventListener('input', calculateTotal);
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

        let isFormChanged = false;

        const forms = document.querySelectorAll('form:not(#filter)');
        const buttonsaved = document.querySelectorAll('#save-button');

        forms.forEach(form => {
            form.addEventListener('input', function() {
                isFormChanged = true;
            });
        });

        buttonsaved.forEach(button => {
            button.addEventListener('click', function() {
                isFormChanged = false;
            });
        });

        window.addEventListener('beforeunload', function(event) {
            if (isFormChanged) {
                const message = "You have unsaved changes. Are you sure you want to leave?";
                event.returnValue = message;
                return message;
            }
        });
    </script>
</body>

</html>
