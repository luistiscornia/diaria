<!-- Modal -->
<div wire:ignore.self id="modal-ausentes" class="fixed hidden z-50 inset-0 bg-gray-600 bg-opacity-60 overflow-y-auto h-full w-full px-4">
    <div class="relative top-40 mx-auto shadow-lg rounded-md bg-white max-w-md">
        <!-- Modal header -->
        <div class="flex justify-between items-center bg-secondary-100 text-white-100 text-xl rounded-t-md px-4 py-2">
            <h3>SELECCIONE EL TIPO DE AUSENTE</h3>
            <button onclick="closeModalAusentes()"  wire:click.prevent="setAusentePorAsignar(null)">x</button>
        </div>

        <!-- Modal body -->
        <div class="max-h-48_2 p-4 bg-white-100">

            <div wire:loading.remove wire:target="setPlanillaNotaYAgente,setAusentePorAsignar">

                @if($ausentePorAsignar == null)

                    <div class="flex justify-center">
                        <div class="mb-3 w-full flex flex-wrap">
                            @foreach($ausentes as $ausente)
                                    <div class="p-1" style="width:33.3333%">
                                            <div class="w-full px-3 py-4 mb-2 text-center font-normal text-xl border border-solid border-gray-300 rounded cursor-pointer bg-clip-padding bg-no-repeat bg-white hover:bg-secondary-100 text-gray-700 hover:text-white-100"
                                            wire:click.prevent="setAusentePorAsignar({{$ausente['id']}})"
                                            >{{$ausente['nombre']}}</div>
                                    </div>
                            @endforeach
                        </div>
                    </div>

                @else
                    <div>

                        <div class="py-4 text-center">
                            Asignar <strong>{{$ausentePorAsignar['nombre']}}</strong> a <strong>{{$nombreAgente ?? null}}</strong>
                        </div>

                        <div class="px-4 py-2 flex justify-center items-center space-x-4 bg-white-100">
                            <button onclick="closeModalAusentes()" wire:click.prevent="actualizarAusente()" class="bg-blue-600 text-white-100 px-6 py-4 rounded-md hover:bg-red-700 transition">
                                CONFIRMAR
                            </button>
                        </div>

                    </div>

                @endif
            </div>

            <div wire:loading wire:target="setPlanillaNotaYAgente,setAusentePorAsignar">
                <div class="py-4 text-center">
                    Cargando...
                </div>
            </div>

        </div>

        <!-- Modal footer -->
        {{-- <div class="px-4 py-2 border-t border-t-gray-500 flex justify-end items-center space-x-4 bg-white-100">
            <button class="bg-red-600 text-white-100 px-4 py-2 rounded-md hover:bg-red-700 transition" onclick="closeModalAusentes()">Cerrar</button>
            <button class="bg-blue-600 text-white-100 px-4 py-2 rounded-md hover:bg-blue-700 transition" onclick="closeModalAusentes()" wire:click.prevent="actualizarSector()">Guardar</button>
        </div> --}}
    </div>
</div>

<script type="text/javascript">
function openModalAusentes(modalId) {
    // Delay pequeño para que wire pueda ocultar la información
    setTimeout(function() {
        modal = document.getElementById(modalId)
        modal.classList.remove('hidden')
    }, 150)
}

function closeModalAusentes() {
    modal = document.getElementById('modal-ausentes')
    modal.classList.add('hidden')
}
</script>
