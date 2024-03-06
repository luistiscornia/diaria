<!-- Modal -->
<div wire:ignore.self id="modalDetalle" class="fixed hidden z-50 inset-0 bg-gray-600 bg-opacity-60  h-full w-full px-4">
  <div class="relative top-40 mx-auto shadow-lg rounded-md bg-white  p-4 w-full max-w-2xl h-full md:h-auto">
    <!-- Modal header -->
    <div class="flex justify-between items-center bg-secondary-100 text-white-100 text-xl rounded-t-md px-4 py-2">
      <h3>DETALLE</h3>
      <button onclick="closeModalDetalle()">x</button>
    </div>

    <!-- Modal body -->
    <div class="max-h-48 overflow-y-scroll p-1 bg-white-100">
      <div class="flex justify-center">
        <div class="mb-3 w-full">
          <table class="min-w-full table-auto ">
            <thead class="border-b bg-white">
              <tr>
                <th class="text-sm font-medium text-dark-100 p-1">TIPO</th>
                <th class="text-sm font-medium text-dark-100 p-1">SOLICITADO</th>
                <th class="text-sm font-medium text-dark-100 p-1">SOLICITANTE</th>
                <th class="text-sm font-medium text-dark-100 p-1">FECHA</th>
              </tr>
            </thead>
            <tbody>
              @if(count($detalleAgente))
              @foreach ($detalleAgente as $item)
              <tr>
                <td class="text-center border-b  py-0 text-sm font-normal text-secondary-100">
                  {{$item->tipo}}
                </td>
                <td class="text-center border-b  py-0 text-sm font-normal text-secondary-100">
                  {{$item->nombre_agente_solicitado}}
                </td>
                <td class="text-center border-b  py-0 text-sm font-normal text-secondary-100">
                  {{$item->nombre_agente_solicitante}}
                </td>
                <td class="text-center border-b py-0 text-sm font-normal text-secondary-100">
    {{ Carbon\Carbon::parse($item->fecha)->format('d/m/Y') }}
</td>
              </tr>
              @endforeach
              @else
              <tr>
                <td colspan="{{ count($headers) }}">
                  <p class="text-sm text-center">No hay registros</p>
                </td>
              </tr>
              @endif
            </tbody>
          </table>
        </div>
      </div>
    </div>
    <!-- Modal footer -->
    <div class="px-4 py-2 border-t border-t-gray-500 flex justify-end items-center space-x-4 bg-white-100">
      <button class="bg-red-600 text-white-100 px-4 py-2 rounded-md hover:bg-red-700 transition"
        onclick="closeModalDetalle()">Cerrar</button>
    </div>
  </div>
</div>

<script type="text/javascript">
  document.addEventListener("DOMContentLoaded", function() {
 console.log('ready')
});
  function openModalDetalle(modalId) {
    modal = document.getElementById(modalId)
    modal.classList.remove('hidden')
}

function closeModalDetalle() {
    modal = document.getElementById('modalDetalle')
    modal.classList.add('hidden')
    eventos();
}
function eventos(){
  Livewire.emit('limpiarDetalle'); 
}
</script>