<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>{{ config('app.name','KuakataStay') }}</title>
<meta name="description" content="Find and book hotels and resorts in Kuakata, Bangladesh.">
<link rel="stylesheet" href="{{ asset('public/css/home.css') }}?v=2">
<link rel="stylesheet" href="{{ asset('public/css/hotel.css') }}?v=2">
<link rel="stylesheet" href="{{ asset('public/css/dashboard.css') }}?v=2">
<link rel="stylesheet" href="{{ asset('public/css/auth.css') }}?v=2">\n<link rel="stylesheet" href="{{ asset('public/css/search-results.css') }}?v=1">
</head>
<body>
@yield('content')
</body>
</html>