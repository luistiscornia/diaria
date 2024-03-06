<!-- Modal -->
<div wire:ignore.self id="modal" class="fixed hidden z-50 inset-0 bg-gray-600 bg-opacity-60 overflow-y-auto h-full w-full px-4">
    <div class="relative top-40 mx-auto shadow-lg rounded-md bg-white max-w-md">
        <!-- Modal header -->
        <div class="flex justify-between items-center bg-secondary-100 text-white-100 text-xl rounded-t-md px-4 py-2">
            <h3>CAMBIO DE SECTOR</h3>
            <button onclick="closeModalSectores()">x</button>
        </div>

        <!-- Modal body -->
        <div class="max-h-48 p-4 bg-white-100">

            <div wire:loading.remove wire:target="setSectorFranja,idSector">

                <div  class="flex justify-center">
                    <div class="mb-3 w-full">
                      <select wire:model="idSector" class="form-select
  w-full
  px-3
  py-1.5
  text-base
  font-normal
  text-gray-700
  bg-white bg-clip-padding bg-no-repeat
  border border-solid border-gray-300
  rounded
  transition
  ease-in-out
  m-0
  focus:text-gray-700 focus:bg-white focus:border-blue-600 focus:outline-none" aria-label="Default select example">
    <option value="">Seleccionar un sector</option>
    @foreach($sectores as $key => $value)
        <option value={{$key}}>{{$value}}</option>
    @endforeach

                      </select>
                    </div>
                  </div>

            </div>

            <div wire:loading wire:target="setSectorFranja,idSector">
                <div class="py-4 text-center">
                    Espere un momento...
                </div>
            </div>


        </div>

        <!-- Modal footer -->
        <div wire:loading.remove wire:target="setSectorFranja,idSector"
            class="px-4 py-2 border-t border-t-gray-500 flex justify-end items-center space-x-4 bg-white-100">
            <button class="bg-red-600 text-white-100 px-4 py-2 rounded-md hover:bg-red-700 transition" onclick="closeModalSectores()">Cerrar</button>
            <button
                class="bg-blue-600 text-white-100 px-4 py-2 rounded-md hover:bg-blue-700 transition"
                onclick="closeModalSectores()"
                wire:click.prevent="actualizarSector()"
            >Guardar</button>
        </div>
    </div>
</div>

<script type="text/javascript">
function openModalSectores(modalId) {
    modal = document.getElementById(modalId)
    modal.classList.remove('hidden')
}

function closeModalSectores() {
    modal = document.getElementById('modal')
    modal.classList.add('hidden')
}
</script>
