<!-- Modal -->
<div wire:ignore.self id="modalNota" class="fixed hidden z-50 inset-0 bg-gray-600 bg-opacity-60 overflow-y-auto h-full w-full px-4">
    <div class="relative top-40 mx-auto shadow-lg rounded-md bg-white max-w-md">
        <!-- Modal header -->
        <div class="flex justify-between items-center bg-secondary-100 text-white-100 text-xl rounded-t-md px-4 py-2">
            <h3>NOTA</h3>
            <button onclick="closeModalNota()">x</button>
        </div>

        <!-- Modal body -->
        <div class="max-h-48 p-4 bg-white-100">

            <div class="flex justify-center">
                <div class="mb-3 w-full">
                  <input type="text" wire:model="nota" class="
                    w-full
                    px-3
                    py-1.5
                    text-base
                    font-normal
                    text-gray-700
                    border border-solid border-gray-300
                    rounded
                    m-0
                    focus:text-gray-700 focus:bg-white focus:border-blue-600 focus:outline-none" />
                    @error('nota') <span class="text-red-800">{{ $message }}</span>@enderror
                </div>
              </div>
        </div>

        <!-- Modal footer -->
        <div class="px-4 py-2 border-t border-t-gray-500 flex justify-end items-center space-x-4 bg-white-100">
            <button class="bg-red-600 text-white-100 px-4 py-2 rounded-md hover:bg-red-700 transition" onclick="closeModalNota()">Cerrar</button>
            <button class="bg-blue-600 text-white-100 px-4 py-2 rounded-md hover:bg-blue-700 transition" wire:click.prevent="actualizarNota()" onclick="closeModalNota()">Guardar</button>
        </div>
    </div>

</div>
<script type="text/javascript">
function openModalNota(modalId) {
    modal = document.getElementById(modalId)
    modal.classList.remove('hidden')
}

function closeModalNota() {
    modal = document.getElementById('modalNota')
    modal.classList.add('hidden')
}


</script>
