<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>


    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    @livewireStyles()
</head>

<body class="bg-gray-100 h-screen antialiased leading-none font-sans">
    <div id="app">
        <header class="bg-blue-900 z-10 fixed inset-x-0">
            <div class="container mx-auto flex justify-between items-center px-6">
                @yield('header')
            </div>
        </header>

        @yield('content')
    </div>

    <footer class="bg-white-100 text-2xl text-white-100 text-center
             border-t-4 border-bgprimary-100
             fixed
             inset-x-0
             bottom-0
             p-1">


             <div class="grid grid-cols-2 gap-2">
                <div class="flex flex-row items-center">

                    <div class="flex items-start rounded-lg hover:bg-gray-50">
                        <span class="inline-block h-12 w-12 rounded-full overflow-hidden bg-gray-100">
                            <svg class="h-full w-full text-secondary-100" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z">
                                </path>
                            </svg>
                        </span>

                        <div class="ml-2">
                            <p class="text-sm md:text-xl font-semibold  text-secondary-100 text-left">
                                {{ Auth::user()->name }}
                            </p>
                            <p class="mt-0 text-secondary-100 text-base text-left">
                                Supervisor
                            </p>
                        </div>
                    </div>
                </div>

                <div class="flex flex-row items-center justify-end">

                    <div class="mr-5">
                        <i class="fa-solid fa-home fa-lg text-secondary-100"></i>
                    </div>

                    {{-- Cambiar sector --}}
                    <a href="{{ route('home') }}" class="no-underline hover:underline mr-5" onclick="event.preventDefault();
         document.getElementById('limpiar-sector-form').submit();"><i
                            class="fa-solid fa-dice fa-lg text-secondary-100"></i></a>
                    <form id="limpiar-sector-form" action="{{ route('home') }}" method="POST" class="hidden">
                        {{ csrf_field() }}
                        <input type="hidden" name="limpiar-sector" value="1">
                    </form>

                    <a href="{{ route('logout') }}" class="no-underline hover:underline" onclick="event.preventDefault();
         document.getElementById('logout-form').submit();"><i
                            class="fa-solid fa-right-from-bracket fa-lg text-secondary-100"></i></a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                        {{ csrf_field() }}
                    </form>

                </div>
              </div>
    </footer>

    <script src="{{ asset('js/datepicker.js') }}"></script>
    @livewireScripts()
    {{-- <script src="https://sigsistema.ar/diaria/public/vendor/livewire/livewire.js"></script> --}}
    @yield('js')
</body>

</html>
