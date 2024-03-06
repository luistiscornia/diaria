<main class="sm:container sm:mx-auto sm:mt-10" style="max-height: 650px; overflow-y: auto;">

    
    {{-- BLOQUE REDIRECCIONA --}}
    {{-- Crea un "Listener" que espera a que aparezca div[data-redirecting="true"] para redireccionar --}}
    @if ($isRedirecting)
        <div data-redirecting="true">
            <x-loading />
        </div>
    @endif
    <script>
        __redirected_started = false;
        function startCheckRedireccion(){
            console.log("Esperando por redirecting...");
            // cada 1 segundo chequea si existe data-redirecting="true", si existe, redirecciona
            setInterval(function() {
            console.log("Esperando...");
                if (document.querySelector('[data-redirecting="true"]') && !__redirected_started) {
                    __redirected_started = true;
                    console.log("Redireccionando...");
                    window.location.href = "{{route('home')}}";
                }
            }, 1000)
        }
        startCheckRedireccion();
    </script>
    {{-- fin:BLOQUE REDIRECCIONA --}}
    
    <div class="flex flex-col">
        <h1 class="text-center text-2xl font-bold text-black-700">Selección de PUESTO INICIAL para {{$nombreAgente}}</h1>
        <div class="bg-gradient-to-r from-bgprimary-100 to-bgsecondary-100 mx-auto" style="width: 100%; height: 6px;"></div>
    </div>

    <div class="flex mt-4">
        <div class="w-full">
            <section class="flex flex-col break-words">

                <div class="flex flex-wrap w-full px-6 sm:px-10">


                    @isset($error)

                        <div class="bg-red-600 w-full p-6 rounded text-white-100 mt-4">
                            <p class="mb-4">
                                {{$error}}
                            </p>
                            <p>
                                <a href="{{route('home')}}">Volver atrás.</a>
                            </p>
                        </div>

                    @else


                        @foreach ($grupos as $grupo)


                            <div class="flex flex-col w-full mt-4">
                                <h1 class="text-center text-2xl font-bold text-black-700">{{$grupo}}</h1>
                                <div class="bg-gradient-to-r from-bgprimary-100 to-bgsecondary-100 mx-auto" style="width: 40%; height: 6px;"></div>
                            </div>

                            {{-- Only the ones grupo_nombre == $grupo --}}
                            @foreach (collect($lugares)->where('grupo__nombre', $grupo) as $lugar)

                                <div class="w-full p-2" style="width:33.3333%">

                                    @if ($lugar->id == $currentPuestoId || $lugar->vw_horario__puesto != null)

                                    <div
                                        class="w-full select-none font-bold whitespace-no-wrap p-2 rounded-lg text-center leading-normal no-underline text-gray-100 bg-gradient-to-r from-bgred-900 to-bgred-800 sm:py-4">
                                        {{explode(' ', $lugar->nombre)[0]}}
                                    </div>

                                    @elseif($lugar->habilitado == 0)


                                        <button onclick="openHabilitarPuesto('modalHabilitarPuesto');" wire:click="setPuesto({{$lugar->id}},'{{$lugar->nombre}}')"
                                            class="w-full select-none font-bold whitespace-no-wrap p-2 rounded-lg text-base leading-normal no-underline text-gray-100 bg-gradient-to-r from-bggray-400 to-bggray-600 hover:from-bgprimary-200 hover:to-bgsecondary-200 sm:py-4">
                                            {{explode(' ', $lugar->nombre)[0]}}
                                        </button>

                                    @else

                                        @if ($grupo != "Casillas")
                                            <button wire:click="setPuestoYAsignar({{$lugar->id}},'{{$lugar->nombre}}')"
                                                class="w-full select-none font-bold whitespace-no-wrap p-2 rounded-lg text-base leading-normal no-underline text-gray-100 bg-gradient-to-r from-bgprimary-100 to-bgsecondary-100 hover:from-bgprimary-200 hover:to-bgsecondary-200 sm:py-4">
                                                {{explode(' ', $lugar->nombre)[0]}}
                                            </button>
                                        @else

                                            <button onclick="openAsignarPuesto('modalAsignarPuesto')" wire:click="setPuesto({{$lugar->id}},'{{$lugar->nombre}}')"
                                                class="w-full select-none font-bold whitespace-no-wrap p-2 rounded-lg text-base leading-normal no-underline text-gray-100 bg-gradient-to-r from-bgprimary-100 to-bgsecondary-100 hover:from-bgprimary-200 hover:to-bgsecondary-200 sm:py-4">
                                                {{explode(' ', $lugar->nombre)[0]}}
                                            </button>
                                        @endif


                                    @endif
                                </div>

                            @endforeach

                        @endforeach



                    @endisset


                </div>

            </section>
        </div>
    </div>

    @include('livewire.planillas.modales.habilitar-puesto')
    @include('livewire.planillas.modales.asignar-puesto')

</main>

