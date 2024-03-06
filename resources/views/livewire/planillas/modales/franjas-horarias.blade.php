<!-- Modal -->
<div wire:ignore.self id="modalFranjas" class="fixed hidden z-50 inset-0 bg-gray-600 bg-opacity-60 overflow-y-auto h-full w-full px-4">
    <div class="relative top-40 mx-auto shadow-lg rounded-md bg-white max-w-md">
        <!-- Modal header -->
        <div class="flex justify-between items-center bg-secondary-100 text-white-100 text-xl rounded-t-md px-4 py-2">
            <h3>FRANJAS HORARIAS</h3>
            <button onclick="closeModalFranjas()">x</button>
        </div>

        <!-- Modal body -->
        <div class="p-4 bg-white-100">

            <div wire.ignore class="flex justify-center">
                <div class="mb-3 w-full flex flex-wrap">
                    @foreach($franjas as $key => $value)
                    <div class="p-1" style="width: 33.3333%;">
                        <div wire:click.prevent="nuevaFranja({{$key}})" onclick="closeModalFranjas()"
                            class="w-full px-3 py-4 mb-2
                            text-center font-normal text-xl
                            border border-solid border-gray-300 rounded
                            cursor-pointer
                            bg-clip-padding bg-no-repeat
                            @if ($franjaInicial == $value)
                                {{"bg-secondary-100 text-white-100"}}
                            @else
                                {{"bg-white hover:bg-secondary-100 text-gray-700 hover:text-white-100"}}
                            @endif
                            "
                        >{{$value}}</div>
                    </div>
                    @endforeach

                </div>
              </div>
        </div>
    </div>
</div>

<script type="text/javascript">
function openModalFranjas(modalId) {
    modal = document.getElementById(modalId)
    modal.classList.remove('hidden')
}

function closeModalFranjas() {
    modal = document.getElementById('modalFranjas')
    modal.classList.add('hidden')
}
</script>
