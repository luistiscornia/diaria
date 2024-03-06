@extends('layouts.planilla')


@section('header')

<div class="antialiased sans-serif bg-gray-200">

</div>

@endsection
@section('content')
<main class="sm:container sm:mx-auto sm:mt-5">
  @livewire($livewireComponent)
</main>

@endsection
