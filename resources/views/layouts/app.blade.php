<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @wireUiScripts
        <!-- Styles -->
        @livewireStyles
        {{-- <link rel="stylesheet" href="build/assets/app-CjG8IKAm.css">
        <script src="build/assets/app-DgUFBvlu.js"></script> --}}
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />
        <script src="https://cdn.amcharts.com/lib/5/index.js"></script>
        <script src="https://cdn.amcharts.com/lib/5/percent.js"></script>
        <script src="https://cdn.amcharts.com/lib/5/xy.js"></script>
        <script src="https://cdn.amcharts.com/lib/5/themes/Animated.js"></script>
        {{-- <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.1/dist/cdn.min.js"></script> --}}
    </head>
    <body class="font-sans antialiased">
    <!-- https://tailwindcomponents.com/component/dashboard-template/landing -->
        <div>
            <x-dialog/>
            <x-notifications />
            <div x-data="{ sidebarOpen: false }" class="flex h-screen">
                <div :class="sidebarOpen ? 'block' : 'hidden'" @click="sidebarOpen = false" class="fixed inset-0 z-20 transition-opacity bg-black opacity-50 lg:hidden"></div>
                @livewire('dashboard.sidebar')
                <div class="flex flex-col flex-1 overflow-hidden">
                    @livewire('dashboard.header')
                    {{-- prueba laravel reverb --}}
                    {{-- <livewire:delivery-history /> --}}
                    <main class="flex-1 overflow-x-hidden overflow-y-auto bg-indigo-100">
                        <div class="py-8 mx-auto">
                            {{ $slot }}
                        </div>
                    </main>
                </div>
            </div>
        </div>
        @stack('modals')
        @livewireScripts
        @stack('js')
        {{-- @livewireScriptConfig --}}
    </body>
</html>
