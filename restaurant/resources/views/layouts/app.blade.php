<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title> @yield('pageTitle') | Les Super Restos</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link rel="icon" type="image/jpg" href="{{ asset('../images/icons/coffee.svg') }}">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Styles -->
    @livewireStyles
    @stack('css')

</head>

<body class="font-sans antialiased">
    @props([
    'popup' => null,
    'popups' => null,
    'role' => 'guest',
])
    <x-banner />

    <div class="min-h-screen bg-gray-100 w-full">
        @livewire('navbar')

        <!-- Page Heading -->
        @if (isset($header))
            <header class="bg-white shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endif

        {{-- popups --}}
        @if (isset($popup))
            <div id="popups">
                @livewire('notification', ['popup' => $popup])
            </div>
        @endif

        @if (isset($popups))
            <div id="popups" class="fixed top-0 w-full z-50">
                @foreach ($popups as $popup)
                    @livewire('notification', ['popup' => $popup])
                @endforeach
            </div>
        @endif


        <!-- Page Content -->
        <main>
            {{ $slot }}
        </main>
    </div>

    @stack('modals')

    @livewireScripts
</body>

</html>
