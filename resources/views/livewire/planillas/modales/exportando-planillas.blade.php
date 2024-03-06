<!-- Modal -->
<div wire:ignore.self id="modalExportarPlanilla" class="fixed hidden z-50 inset-0 bg-gray-600 bg-opacity-60 overflow-y-auto h-full w-full px-4">
    <div class="relative top-40 mx-auto shadow-lg rounded-md bg-white max-w-md">
        <!-- Modal header -->
        <div class="flex justify-between items-center bg-secondary-100 text-white-100 text-xl rounded-t-md px-4 py-2">
            <h3>Exportar Planilla</h3>
            <button onclick="closeExportarPlanilla()">x</button>
        </div>

        <!-- Modal body -->
        <div class="max-h-48 p-4 bg-white-100">


            <div wire:loading.remove wire:target="exportarPlanilla,setEsPlanillaYaExportada">

                @if ($esPlanillaYaExportada)

                    <div class="py-4 text-center">
                        Planilla Exportada
                    </div>


                    <div class="px-4 py-2 flex justify-center items-center space-x-4 bg-white-100">
                        <button onclick="closeExportarPlanilla()" class="bg-green-700 text-white-100 px-6 py-4 rounded-md hover:bg-blue-700 transition">
                            ACEPTAR
                        </button>
                    </div>

                @else

                    <div class="py-4 text-center">
                        Elija el formato para exportar la planilla
                    </div>

                    <div class="px-4 py-2 flex justify-center items-center space-x-4 bg-white-100">
                        <button wire:click="exportarPlanilla('excel')" class="bg-green-700 text-white-100 px-6 py-4 rounded-md hover:bg-opacity-75 transition">
                            Excel
                        </button>
                        <button wire:click="exportarPlanilla('pdf')" class="bg-blue-600 text-white-100 px-6 py-4 rounded-md hover:bg-opacity-75 transition">
                            PDF
                        </button>
                    </div>

                @endif


            </div>

            <div wire:loading wire:target="exportarPlanilla">
                <div class="py-4 text-center">
                    Exportando planilla, puede tardar unos segundos...
                </div>
            </div>

            <div wire:loading wire:target="setEsPlanillaYaExportada">
                <div class="py-4 text-center">
                    Aguarde...
                </div>
            </div>

        </div>

    </div>
</div>

<script type="text/javascript">
    function openExportarPlanilla() {
        // Delay pequeño para que wire pueda ocultar la información
        setTimeout(function() {
            modal = document.getElementById("modalExportarPlanilla")
            modal.classList.remove('hidden')
        }, 150)
    }

    function closeExportarPlanilla() {
        modal = document.getElementById('modalExportarPlanilla')
        modal.classList.add('hidden')
        document.querySelectorAll('.hide-on-close').forEach(function(element) {
            element.classList.add('hidden')
        })
    }
</script>
