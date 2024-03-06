@extends('layouts.planilla')


@section('header')

<div class="antialiased sans-serif bg-gray-200">

</div>

@endsection

@section('content')


<main class="sm:container sm:mx-auto sm:max-w-lg sm:mt-10">
    <div class="flex">
        <div class="w-full">
            <section class="flex flex-col break-words">
                <header class="py-2 px-6 sm:py-2 sm:px-8 sm:rounded-t-md">
                    <img class="m-auto object-center h-24" src="{{asset('img/logo_2.png')}} " alt="Diario SGI">
                </header>
                <h1 class="font-bold text-5xl text-center py-1 text-dark-100">Hola {{auth()->user()->name}}!</h1>
                <p class="text-center text-xl text-dark-100 py-7">Seleccioná tu sector</p>
                <form class="w-full px-6 space-y-6 sm:px-10 sm:space-y-8" method="POST" action="{{ route('home') }}">
                    @csrf

                        <div class="w-full py-2">
                            <button type="submit" name="sector" value="S"
                                class="w-full select-none font-bold whitespace-no-wrap p-2 rounded-lg text-2xl leading-normal no-underline text-gray-100 bg-gradient-to-r from-bgprimary-100 to-bgsecondary-100 hover:from-bgprimary-200 hover:to-bgsecondary-200 sm:py-4">
                                 <i class="fa-solid fa-down-left-and-up-right-to-center fa-beat fa-xl" style="color: #ffffff; margin-right: 10px; animation-duration: 4s;"></i>
                                {{ __('ENTRADA') }}
                            </button>
                        </div>

                        <div class="w-full py-2">
                            <button type="submit" name="sector" value="E"
                                class="w-full select-none font-bold whitespace-no-wrap p-2 rounded-lg text-2xl leading-normal no-underline text-gray-100 bg-gradient-to-r from-bgprimary-100 to-bgsecondary-100 hover:from-bgprimary-200 hover:to-bgsecondary-200 sm:py-4">
                                <i class="fa-solid fa-up-right-and-down-left-from-center fa-beat fa-xl" style="color: #ffffff; margin-right: 10px; animation-duration: 3.5s;"></i>
                                {{ __('SALIDA') }}
                            </button>
                        </div>
                        <div class="flex flex-wrap space-y-1">
                        <div class="w-full py-2">
                            <button type="submit" name="sector" value="T"
                                class="w-full select-none font-bold whitespace-no-wrap p-2 rounded-lg text-2xl leading-normal no-underline text-gray-100 bg-gradient-to-r from-bgprimary-100 to-bgsecondary-100 hover:from-bgprimary-200 hover:to-bgsecondary-200 sm:py-4">
                                <i class="fa-solid fa-list-ol fa-xl" style="color: #ffffff; margin-right: 10px;"></i>
                                {{ __('TODOS') }}
                            </button>
                        </div>
                    </div>

                </form>

            </section>
        </div>
    </div>
</main>


@endsection
