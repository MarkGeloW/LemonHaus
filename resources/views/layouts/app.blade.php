<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<style>
.sidebar-link{
    display:flex;
    align-items:center;
    padding:16px 20px;
    border-radius:18px;
    font-size:18px;
    font-weight:500;
    color:#334155;
}

.sidebar-link:hover{
    background:#f8fafc;
}

.active-sidebar{
    border:1px solid #f1cd47;
    background:#fffceb;
    color:#c97a00;
}
</style>
<body class="font-sans antialiased bg-[#f6f7fb] text-slate-900">

    <div class="min-h-screen">
        @yield('content')
    </div>

</body>
</html>