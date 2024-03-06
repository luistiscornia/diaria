<!-- Modal -->
<div wire:ignore.self id="modalPresente" class="fixed hidden z-50 inset-0 bg-gray-600 bg-opacity-60 overflow-y-auto h-full w-full px-4">
    <div class="relative top-40 mx-auto shadow-lg rounded-md bg-white max-w-md">
        <!-- Modal header -->
        <div class="flex justify-between items-center bg-secondary-100 text-white-100 text-xl rounded-t-md px-4 py-2">
            <h3>PRESENTE</h3>
            <button onclick="closeModalPresente()">x</button>
        </div>

        <!-- Modal body -->
        <div class="max-h-48 p-4 bg-white-100">

            <div wire:loading.remove wire:target="setPlanillaNotaYAgente">

                <div class="py-4 text-center">
                    Asignar Presente a: <strong>{{$nombreAgente ?? null}}</strong>
                </div>

                <div class="px-4 py-2 flex justify-center items-center space-x-4 bg-white-100">

                    <button id="modalPresente_button_no" wire:click="actualizarPresenteNo()" onclick="closeModalPresente()" class="bg-red-600 text-white-100 px-6 py-4 rounded-md hover:bg-blue-700 transition">
                        NO
                    </button>
                    <a href="#"
                        class="bg-blue-600 text-white-100 px-6 py-4 rounded-md hover:bg-red-700 transition"
                        onclick="event.preventDefault(); document.getElementById('seleccion-puesto-form').submit();">
                        SI
                    </a>
                    <form id="seleccion-puesto-form" action="{{ route('seleccion-puesto') }}" method="POST" class="hidden">
                        {{ csrf_field() }}
                        <input type="hidden" name="planilla" value="{{$planillaID}}">
                        <input type="hidden" name="agente" value="{{$nombreAgente ?? null}}">
                    </form>

                </div>

            </div>

            <div wire:loading wire:target="setPlanillaNotaYAgente">
                <div class="py-4 text-center">
                    Cargando...
                </div>
            </div>


        </div>

        {{-- <!-- Modal footer -->
        <div class="px-4 py-2 border-t border-t-gray-500 flex justify-end items-center space-x-4 bg-white-100">
            <button class="bg-red-600 text-white-100 px-4 py-2 rounded-md hover:bg-red-700 transition" onclick="closeModalPresente()">Cerrar</button>
            <button class="bg-blue-600 text-white-100 px-4 py-2 rounded-md hover:bg-blue-700 transition"  wire:click.prevent="actualizarPresente()" onclick="closeModalPresente()">Guardar</button>
        </div> --}}
    </div>
</div>

<script type="text/javascript">
function openModalPresente(modalId) {
    // Delay pequeño para que wire pueda ocultar la información
    setTimeout(function() {
        modal = document.getElementById(modalId)
        modal.classList.remove('hidden')
    }, 150)
}

function closeModalPresente() {
    modal = document.getElementById('modalPresente')
    modal.classList.add('hidden')
}
</script>
