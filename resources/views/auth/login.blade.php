@extends('layouts.auth')


@section('content')
<main class="relative">
    <div class="fixed top-0 left-0 w-full h-full bg-black opacity-75 z-0"></div>
    <div class="sm:container sm:mx-auto sm:max-w-lg sm:mt-10 relative z-10">
        <div class="flex">
            <div class="w-full">
                <section class="flex flex-col break-words">
                    <header class="py-2 px-6 sm:py-2 sm:px-8 sm:rounded-t-md relative z-10">
                        <img class="m-auto object-center h-24" src="{{asset('img/logo_2.png')}}" alt="SIG">
                    </header>
                
                <p class="font-bold text-3xl text-center py-1 text-dark-100">Bienvenido al módulo de supervisores</p>
                <form class="w-full px-6 space-y-6 sm:px-10 sm:space-y-8" method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="flex flex-wrap">
                        <input placeholder="Usuario" type="text" name="email" class="text-xl block px-3 py-2 form-input @error('email') border-red-500 @enderror rounded-lg w-full 
                bg-white placeholder-gray-400 shadow-md focus:placeholder-gray-500 focus:bg-white"
                            value="{{ old('email') }}" required autocomplete="email" autofocus>
                        @error('email')
                        <p class="text-red-500 text-xs italic mt-4">
                            {{ $message }}
                        </p>
                        @enderror
                        
                    @if ($message = Session::get('error'))
                    <p class="text-red-500 text-xs italic mt-4">
                        {{ $message }}
                    </p>
                    @endif
                    </div>

                    <div class="py-1" x-data="{ show: true }">
                        <div class="relative">
                            <input placeholder="Contraseña" :type="show ? 'password' : 'text'" class="text-xl block px-3 py-2  form-input rounded-lg w-full 
                        bg-white   focus:placeholder-gray-500 shadow-md" name="password">
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center text-sm leading-5">
                                <svg class="h-6 text-gray-400" fill="none" @click="show = !show"
                                    :class="{'hidden': !show, 'block':show }" xmlns="http://www.w3.org/2000/svg"
                                    viewbox="0 0 576 512">
                                    <path fill="currentColor"
                                        d="M572.52 241.4C518.29 135.59 410.93 64 288 64S57.68 135.64 3.48 241.41a32.35 32.35 0 0 0 0 29.19C57.71 376.41 165.07 448 288 448s230.32-71.64 284.52-177.41a32.35 32.35 0 0 0 0-29.19zM288 400a144 144 0 1 1 144-144 143.93 143.93 0 0 1-144 144zm0-240a95.31 95.31 0 0 0-25.31 3.79 47.85 47.85 0 0 1-66.9 66.9A95.78 95.78 0 1 0 288 160z">
                                    </path>
                                </svg>

                                <svg class="h-6 text-gray-700" fill="none" @click="show = !show"
                                    :class="{'block': !show, 'hidden':show }" xmlns="http://www.w3.org/2000/svg"
                                    viewbox="0 0 640 512">
                                    <path fill="currentColor"
                                        d="M320 400c-75.85 0-137.25-58.71-142.9-133.11L72.2 185.82c-13.79 17.3-26.48 35.59-36.72 55.59a32.35 32.35 0 0 0 0 29.19C89.71 376.41 197.07 448 320 448c26.91 0 52.87-4 77.89-10.46L346 397.39a144.13 144.13 0 0 1-26 2.61zm313.82 58.1l-110.55-85.44a331.25 331.25 0 0 0 81.25-102.07 32.35 32.35 0 0 0 0-29.19C550.29 135.59 442.93 64 320 64a308.15 308.15 0 0 0-147.32 37.7L45.46 3.37A16 16 0 0 0 23 6.18L3.37 31.45A16 16 0 0 0 6.18 53.9l588.36 454.73a16 16 0 0 0 22.46-2.81l19.64-25.27a16 16 0 0 0-2.82-22.45zm-183.72-142l-39.3-30.38A94.75 94.75 0 0 0 416 256a94.76 94.76 0 0 0-121.31-92.21A47.65 47.65 0 0 1 304 192a46.64 46.64 0 0 1-1.54 10l-73.61-56.89A142.31 142.31 0 0 1 320 112a143.92 143.92 0 0 1 144 144c0 21.63-5.29 41.79-13.9 60.11z">
                                    </path>
                                </svg>

                            </div>
                        </div>
                        @error('password')
                        <p class="text-red-500 text-xs italic mt-4">
                            {{ $message }}
                        </p>
                        @enderror
                    </div>
                    <div  class="flex flex-wrap">
                        <select class="text-xl block px-3 py-2 form-input @error('email') border-red-500 @enderror rounded-lg w-full 
                        bg-white placeholder-gray-400 shadow-md focus:placeholder-gray-500 focus:bg-white" widget="tcombo" placeholder="Ver como guardia" name="guardia" id="tcombo_1042015602">
                            <option value="A">Guardia A</option>
                            <option value="B">Guardia B</option>
                            </select>
                    </div>

                    <div class="flex flex-wrap">
                        <button type="submit"
                            class="w-full select-none font-bold whitespace-no-wrap p-2 h-14 rounded-lg text-lg leading-normal no-underline text-gray-100 bg-gradient-to-r from-bgprimary-100 to-bgsecondary-100 hover:from-bgprimary-200 hover:to-bgsecondary-200 sm:py-2">
                            {{ __('INGRESAR') }}
                        </button>
                        @if (Route::has('register'))
                        <p class="w-full text-xs text-center text-gray-700 my-6 sm:text-sm sm:my-8">
                            {{ __("Don't have an account?") }}
                            <a class="text-blue-500 hover:text-blue-700 no-underline hover:underline"
                                href="{{ route('register') }}">
                                {{ __('Register') }}
                            </a>
                        </p>
                        @endif
                    </div>
                </form>
              
            </section>
        </div>
    </div>
</main>
@endsection