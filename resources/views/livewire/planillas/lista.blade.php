<div class="w-full sm:px-6">
  <div wire:loading.delay wire:target="sort,actualizarNota,findPlanillaNota,
  setPlanillaID,actualizarPresente,fechaActual,fechaSeleccionada,fecha_actual,setDetalleId,
  actualizarSector,setAusentePorAsignar,actualizarAusente,render">
    <x-loading />
  </div>
  <section class="flex flex-col break-words  sm:border-1 sm:rounded-md sm:shadow-sm lg:shadow-lg">
    <header class="flex flex-row font-bold mx-auto text-secondary-100 py-5 px-6 sm:py-2 sm:px-8 sm:rounded-t-md">
      <span class="font-bold text-2xl my-auto">
        PLANILLA DIARIA
      </span>
      <input wire:model="fechaInput" type="hidden" readonly>
      @include('planillas.input-date')

      <a 
        class="no-underline hover:underline mr-5 mt-3 cursor-pointer" 
        wire:click="setEsPlanillaYaExportada(false)" 
        onclick="event.preventDefault();openExportarPlanilla();">
        <i class="fa-solid fa-download fa-lg_2 text-gray-400" title="Descargar Planilla"></i>
      </a>


    </header>
    <div class="w-full p-2  bg-white-100">
      <div class="flex flex-col">
        <div class="overflow-x-auto sm:-mx-6 lg:-mx-8">
          <div class="py-1 pb-10 inline-block min-w-full sm:px-6 lg:px-8">
              <table class="md:table-fixed min-w-full table-auto">
                <thead class="border-b bg-white">
                  <tr>
                    @foreach($headers as $key => $value)
                    @switch($value)
                    @case('ID INTERNO')
                    <th style="cursor: pointer;width:100px" class="text-sm font-medium text-dark-100 px-4 py-2"
                      wire:click="sort('{{ $key }}')">
                      @break
                      @case('DETALLE')
                    <th style="cursor: pointer;width:100px" class="text-sm font-medium text-dark-100 px-4 py-2"
                      wire:click="sort('{{ $key }}')">
                      @break
                      @default
                    <th style="cursor: pointer" class="text-sm font-medium text-dark-100 px- py-2"
                      wire:click="sort('{{ $key }}')">
                      @endswitch

                      @if($sortColumn == $key)
                      <span>{!! $sortDirection == 'asc' ? '&#8593;':' &#8595;' !!}</span>
                      @endif
                      {{$value}}
                    </th>
                    @endforeach
                    <th class="text-sm font-medium text-dark-100 px-2 py-2" style="width:60px">PRESENTE</th>
                    <th class="text-sm font-medium text-dark-100 px-2 py-2" style="width:60px">PUESTO</th>
                    <th class="text-sm font-medium text-dark-100 px-2 py-2" style="width:90px">SECTOR</th>
                    <th class="text-sm font-medium text-dark-100 px-2 py-2" style="width:90px">RT</th>
                  </tr>
                </thead>

                <tbody>
                  @if(count($data))
                  @foreach ($data as $item)
                  <tr class="{!! $item->presente=='SI' ? 'bg-green-400':( $item->presente=='NO' ? '' : 'bg-red-100')!!}">
                    @foreach ($headers as $key => $value)
                    <td class="text-center border-b  py-1 text-lg font-bold {!! $item->presente=='SI' ? 'text-white-100':'text-secondary-100'!!} px-2">

                      @switch($key)
                      @case('nombre_agente')
                      <div class="cursor-pointer hover:bg-secondary-100 max-w-xl py-1 px-10  mx-auto hover:text-white-100" title="Cargar Ausente" onclick="openModalAusentes('modal-ausentes')" wire:click="setPlanillaNotaYAgente({{$item->id_planilla_horarios_det}},'{{$item->nombre_agente}}')">
                        {{ $item->$key}}
                      </div>
                        @break
                        @case('nota')
                        <div class="cursor-pointer hover:bg-secondary-100 text-base min-w-fit mx-auto py-1 hover:text-white-100" onclick="openModalDetalle('modalDetalle')"
                        wire:click="setDetalleId({{$item->id_planilla_horarios_det}})">

                          @if($item->nota)
                           {{substr($item->nota, 0, 3)}}
                            @else
                               &nbsp;
                          @endif
                      </div>

                        @break
                        @default
                        {{ $item->$key}}
                        @endswitch

                    </td>
                    @endforeach
                    {{-- <td class="text-center p-2 text-sm font-semibold  border-b"
                    wire:click="setPlanillaNotaYAgenteYRedireccionarAAsignar({{$item->id_planilla_horarios_det}},'{{$item->nombre_agente}}')">
                        <div class="bg-primary-100 p-2 cursor-pointer text-white-100 {!! $item->presente=='SI' ? 'bg-primary-100':( $item->presente=='NO' ? 'bg-primary-100' : 'bg-red-600')!!}">

                            @if ($item->presente == 'NO')
                                --
                            @else
                                {{$item->presente}}
                            @endif

                        </div>
                    </td> --}}

                    @if ($item->presente == 'NO')

                        <td class="text-center p-2 text-sm font-semibold border-b"
                            onclick="document.getElementById('seleccion-puesto-form_{{$item->id_agente}}').submit();"
                        >
                            <div class="bg-primary-100 p-2 cursor-pointer text-white-100 bg-primary-100 hover:bg-secondary-300">
                                --
                            </div>
                            <form id="seleccion-puesto-form_{{$item->id_agente}}" action="{{ route('seleccion-puesto') }}" method="POST" class="hidden">
                                {{ csrf_field() }}
                                <input type="hidden" name="planilla" value="{{$item->id_planilla_horarios_det}}">
                                <input type="hidden" name="agente" value="{{$item->nombre_agente}}">
                            </form>
                        </td>

                    @else
                        <td class="text-center p-2 text-sm font-semibold  border-b"
                            wire:click="actualizarPresenteNo({{$item->id_planilla_horarios_det}})">
                            <div class="bg-primary-100 p-2 cursor-pointer text-white-100 bg-primary-100 hover:bg-secondary-100">
                                {{$item->presente}}
                            </div>
                        </td>
                    @endif


{{--
                    <a href="#"
                        class="bg-blue-600 text-white-100 px-6 py-4 rounded-md hover:bg-red-700 transition"
                        onclick="event.preventDefault(); document.getElementById('seleccion-puesto-form').submit();">
                        SI
                    </a>
                    <form id="seleccion-puesto-form" action="{{ route('seleccion-puesto') }}" method="POST" class="hidden">
                        {{ csrf_field() }}
                        <input type="hidden" name="planilla" value="{{$planillaID}}">
                        <input type="hidden" name="agente" value="{{$nombreAgente ?? null}}">
                    </form> --}}

                    <td class="text-center border-b  py-1 text-base font-bold {!! $item->presente=='SI' ? 'text-white-100':'text-secondary-100'!!} px-2">
                        @if ($item->puesto == 0)
                            N/A
                        @else
                            {{explode(' ', $item->lugar__nombre)[0]}}
                        @endif

                    </td>
                    <td class="text-center p-2 text-sm font-semibold border-b" onclick="openModalSectores('modal')"
                        wire:click="setSectorFranja({{$item->id_franja_horaria}},{{$item->id_planilla_horarios_det}})">
                        <div class="{!! $item->etiqueta=='S' ? 'bg-secondary-200 ':' bg-secondary-100' !!} p-2 cursor-pointer text-white-100">
                                {{$item->etiqueta}}

                            </div>
                    </td>
                    <td class="text-center border-b  py-1 text-base font-bold text-white-100 px-2">
                        {{$item->rel_temp ?? null}}
                    </td>
                  </tr>
                  @endforeach
                  @else
                  <tr>
                    <td colspan="{{ count($headers) }}"><p class="text-sm text-left">No hay registros</p></td>
                  </tr>
                  @endif
                </tbody>
              </table>


          </div>
        </div>

      </div>

      <div class="fixed w-full z-10 mx-auto bottom-10 left-0">

        <div onclick="openModalFranjas('modalFranjas')"
        class="mx-auto text-white-100 border-white-100  rounded-full cursor-pointer bg-bgprimary-100 h-16 w-16 hover:bg-bgsecondary-200">
        <p class="text-center text-xl font-bold py-4">{{$franjaInicial}} </p>

      </div>
    </div>

    <div class="h-12">
    </div>

</div>

  </section>

  @include('livewire.planillas.modales.sectores')
  @include('livewire.planillas.modales.franjas-horarias')
  {{-- @include('livewire.planillas.modales.notas') --}}
  @include('livewire.planillas.modales.presente')
  @include('livewire.planillas.modales.detalle-agente')
  @include('livewire.planillas.modales.ausentes')
  @include('livewire.planillas.modales.exportando-planillas')

</div>
