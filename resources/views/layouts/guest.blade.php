<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Pemberitahuan Dosen') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@200..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="flex min-h-full items-center justify-center bg-gradient-to-br from-primary-700 via-primary-600 to-primary-800 px-4 py-12 sm:px-6 lg:px-8">
    <div class="w-full max-w-md">
        <div class="rounded-xl bg-white shadow-2xl">
            <div class="px-8 py-10">
                <div class="mb-6 text-center">
                    <h1 class="text-2xl font-bold text-gray-900">{{ config('app.name', 'Pemberitahuan Dosen') }}</h1>
                    @if(isset($title))
                        <p class="mt-2 text-sm text-gray-500">{{ $title }}</p>
                    @endif
                </div>
                {{ $slot }}
            </div>
        </div>
    </div>
</body>
</html>
