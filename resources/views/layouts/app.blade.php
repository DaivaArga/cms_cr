<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>INVENTORY360 - Admin Dashboard</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/lucide@latest"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts@latest/dist/apexcharts.min.js"></script>
    <script>
        // Initialize Lucide icons on page load
        document.addEventListener('DOMContentLoaded', function () {
            lucide.createIcons();
        });
    </script>
</head>

<body class="bg-[#1a1a1a]">
    @yield('content')

    <script>
        // Initialize icons on dynamic content
        document.addEventListener('DOMContentLoaded', function () {
            lucide.createIcons();
        });
    </script>
</body>

</html>