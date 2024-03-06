<!-- Modal -->
<div wire:ignore.self id="modalHabilitarPuesto" class="fixed hidden z-50 inset-0 bg-gray-600 bg-opacity-60 overflow-y-auto h-full w-full px-4">
    <div class="relative top-40 mx-auto shadow-lg rounded-md bg-white max-w-md">
        <!-- Modal header -->
        <div class="flex justify-between items-center bg-secondary-100 text-white-100 text-xl rounded-t-md px-4 py-2">
            <h3>HABILITAR PUESTO</h3>
            <button onclick="closeHabilitarPuesto()">x</button>
        </div>

        <!-- Modal body -->
        <div class="max-h-48 p-4 bg-white-100">


            <div wire:loading.remove wire:target="setPuestoYAsignar">

                <div class="py-4 text-center">
                    Habilitar puesto <strong>{{$currentPuesto['nombre'] ?? null}}</strong>
                </div>

                <div class="px-4 py-2 flex justify-center items-center space-x-4 bg-white-100">
                    <button onclick="closeHabilitarPuesto()" class="bg-red-600 text-white-100 px-6 py-4 rounded-md hover:bg-blue-700 transition">
                        NO
                    </button>
                    <button wire:click="habilitarPuesto()" onclick="closeHabilitarPuesto()" class="bg-blue-600 text-white-100 px-6 py-4 rounded-md hover:bg-red-700 transition">
                        SI
                    </button>

                </div>

            </div>

            <div wire:loading wire:target="setPuestoYAsignar">
                <div class="py-4 text-center">
                    Cargando habilitar puesto...
                </div>
            </div>


        </div>

    </div>
</div>

<script type="text/javascript">
function openHabilitarPuesto(modalId) {
    // Delay pequeño para que wire pueda ocultar la información
    setTimeout(function() {
        modal = document.getElementById(modalId)
        modal.classList.remove('hidden')
    }, 150)
}

function closeHabilitarPuesto() {
    modal = document.getElementById('modalHabilitarPuesto')
    modal.classList.add('hidden')
    document.querySelectorAll('.hide-on-close').forEach(function(element) {
        element.classList.add('hidden')
    })
}
</script>
