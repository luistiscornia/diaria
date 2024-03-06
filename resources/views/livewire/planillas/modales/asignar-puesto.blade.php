<!-- Modal -->
<div wire:ignore.self id="modalAsignarPuesto" class="fixed hidden z-50 inset-0 bg-gray-600 bg-opacity-60 overflow-y-auto h-full w-full px-4">
    <div class="relative top-40 mx-auto shadow-lg rounded-md bg-white max-w-md">
        <!-- Modal header -->
        <div class="flex justify-between items-center bg-secondary-100 text-white-100 text-xl rounded-t-md px-4 py-2">
            <h3>ASIGNAR PUESTO</h3>
            <button onclick="closeAsignarPuesto()">x</button>
        </div>

        <!-- Modal body -->
        <div class="max-h-48 p-4 bg-white-100">


            <div wire:loading.remove wire:target="setPuestoYAsignar,asignarPuestoConRelevoTempranoComo">

                <div class="py-4 text-center">
                    ¿Desea relevo temprano?
                </div>
                <div class="px-4 py-2 flex justify-center items-center space-x-4 bg-white-100">
                    <div>{{-- b ug de Livewire que no dispara evento sin un elemento previo --}}</div>

                    <button wire:click="asignarPuestoConRelevoTempranoComo(false)" class="bg-red-600 text-white-100 px-6 py-4 rounded-md hover:bg-blue-700 transition">
                        NO
                    </button>

                    <button wire:click="asignarPuestoConRelevoTempranoComo(true)" class="bg-blue-600 text-white-100 px-6 py-4 rounded-md hover:bg-red-700 transition">
                        SI
                    </button>
                </div>


            </div>

            <div wire:loading wire:target="setPuestoYAsignar">
                <div class="py-4 text-center">
                    Cargando asignar puesto...
                </div>
            </div>

            <div wire:loading wire:target="asignarPuestoConRelevoTempranoComo">
                <div class="py-4 text-center">
                    Asignando puesto...
                </div>
            </div>


        </div>

    </div>
</div>

<script type="text/javascript">
function openAsignarPuesto(modalId) {
    // Delay pequeño para que wire pueda ocultar la información
    setTimeout(function() {
        modal = document.getElementById(modalId)
        modal.classList.remove('hidden')
    }, 150)
}
function closeAsignarPuesto() {
    modal = document.getElementById('modalAsignarPuesto')
    modal.classList.add('hidden')
    document.querySelectorAll('.hide-on-close').forEach(function(element) {
        element.classList.add('hidden')
    })
}

function redirectToHome() {
    window.location.href = "{{route('home')}}"
}
</script>
