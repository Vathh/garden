<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'Garden Manager')</title>
    <link rel="stylesheet" href="/css/style.css">
    <link rel="icon" href="./favicon.ico" type="image/x-icon">
    <script src="/js/inactiveLogout.js"></script>
</head>
<body>
<main>
    @include('partials.nav')
    @yield('content')

    @yield('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</main>
</body>
</html>
