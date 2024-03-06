<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    {{-- <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge"> --}}
    {{-- <title>Document</title> --}}

    <style>
    

        /* DOMPDF */
        @page {
            margin: 40px 20px;
            /* font-family: ui-sans-serif, system-ui; */
        }

        .pagenum:before {
            content: counter(page);
        }

        /* CSS */
        body{
            /* background-color: red; */
        }

        h1{
            text-align: center;
            font-size: 25px;
            margin: 0 0 20px 0;
        }

        h2{
            text-align: center;
            font-size: 18px;
            margin: 10px 0 20px 0;
        }

        h3{
            text-align: center;
        }

        table{
            width: 100%;
            font-size: 10px;
            border-collapse: collapse;
        }

        th{
            font-size: 10px;
            font-weight: 700;
        }
        th,td{
            text-align: center;
            height: 30px;
            border-bottom: 1px solid rgb(190, 190, 190);
        }


        /* filas impares grises */
        tr:nth-child(odd){
            background-color: rgb(244, 244, 244);
        }

        .w10{
            width: 10%;
        }

        .w5{
            width: 5%;
        }

        .w15{
            width: 15%;
        }

        .w20{
            width: 18%;
        }
        
         .w25{
            width: 20%;
        }

        .text-sm{
            font-size: 0.7rem;
        }

        .time-row{
            text-align: left;
            background-color: rgb(42, 160, 229);
            color: #ffffff;
            font-size: 15px;
            padding-left: 20px;
            height: 20px;
        }

        .box{
            padding: 4px 6px;
            background-color: rgb(136, 136, 136);
            color: #ffffff;
            font-weight: 700;
        }


        .text-primary{
            color: rgb(41, 161, 229);
        }

        .text-white{
            color: #ffffff;
        }

        .bg-primary-100{
            background-color: rgb(30, 86, 154)!important;
        }
        
        .bg-primary-200{
            background-color: rgb(154, 30, 30)!important;
        }

        .bg-secondary-100{
            background-color: rgb(41, 161, 229)!important;
        }

        .bg-green-400{
            background-color: rgb(74, 222, 128)!important;
        }
        
        .bg-orange{
            background-color: rgb(255, 153, 0)!important;
        }

        footer {
            position: fixed;
            bottom: -60px;
            left: 0px;
            right: 0px;
            height: 60px;
            text-align: center;
            font-size: 0.7rem;
            color: #000000;
        }


    </style>

</head>
<meta charset="UTF-8">
<body>
    
    {{-- Footer - necesita ir primero --}}
    <footer>
        {{$supervisor->name}} - {{now()->format('d/m/Y H:i')}}
    </footer>
    
    <h1 style="color: black; font-size: 1.1rem;">
                             Parte Diario de Asistencia del {{$fechaStr}}
    </h1>
    <h2 style="color: black; font-size: 0.9rem;">
        Jefe de turno: {{$jefeDeTurno->nombre_agente ?? 'Sin Jefe de Turno'}}
    </h2>
    <h3 style="color: black; font-size: 0.7rem;">Total Agentes: {{$agentesTotal}}</h2>


    <table>
        <thead>
            <tr class="bg-primary-100 text-white">
                <th class="w5">Horario</th>
                <th class="w5">Sector</th>
                <th class="w25">Agente</th>
                <th class="w15">Tipo Agente</th>
                <th class="w5">Guardia</th>
                <th class="w5">Día</th>
                <th class="w20">Reemplaza a:</th>
                <th class="w15">Detalle</th>
                <th class="w5">Presente</th>
                <th class="w5"></th>

                {{-- Horario Sector Agente Puesto Guardia Día Reemplaza a: Detalle Presente --}}
            </tr>
        </thead>

        <tbody>

            @foreach ($data as $ingreso => $items)

                    <tr>
                        <td class="time-row" colspan="10" style="text-align: center;">
                            Horario: {{$ingreso}} &nbsp; &nbsp;  >>>> &nbsp; &nbsp;    {{count($items)}} Agentes
                        </td>
                    </tr>


                @foreach ($items as $item)

                    <tr>
                        <td>{{$ingreso}}</td>
                        <td>{{$item->etiqueta}}</td>
                        <td>{{$item->nombre_agente}}</td>
                        <td>{{$item->nombre_puesto_migratorio}}</td>
                        <td>{{$item->guardia}}</td>
                        <td>{{$item->dia}}</td>
                        <td>{{$item->agente_cobertura}}</td>
                        <td>{{$item->nota}}</td>
                        <td>
                            @if ($item->presente == 'SI')
                                <span class="box bg-primary-100">SI</span>
                            @else
                                <span class="box bg-primary-200">{{$item->presente}}</span>
                            @endif
                        </td>

                        <td>
                            
                        </td>
                    </tr>

                @endforeach
            @endforeach
        </tbody>


    </table>

    <div>
<center><img src="img/footer.png" alt="" width="760" height="33"></center><br>
</div>

@if (count($agentesConLicencia) > 0)


    <div style="margin: 0px 60px;">

        <h2 style="color: black; font-size: 0.9rem;">
            Agentes con licencias en el día
        </h2>

        <table>

            <thead>
                <tr class="bg-orange text-white">
                    <th class="w5">Agente</th>
                    <th class="w5">Tipo de licencia</th>
                    <th class="w5">Desde</th>
                    <th class="w5">Hasta</th>

                    {{-- Horario Sector Agente Puesto Guardia Día Reemplaza a: Detalle Presente --}}
                </tr>
                @foreach ($agentesConLicencia as $agente)

                    <tr>
                        <td>{{$agente->nombre_agente}}</td>
                        <td>{{$agente->tipolicencia__nombre}}</td>
                        <td>{{$agente->licencia__fecha_desde}}</td>
                        <td>{{$agente->licencia__fecha_hasta}}</td>
                    </tr>

                @endforeach

            </thead>

        </table>
    </div>

@else

    <h2 style="color: rgb(0, 131, 17); font-size: 0.9rem;">
        No hay agentes con licencias
    </h2>

@endif

<!-- Nuevo bloque para "Coberturas y Devoluciones" -->
@if (isset($coberturasDevoluciones) && (is_array($coberturasDevoluciones) || $coberturasDevoluciones instanceof Countable) && count($coberturasDevoluciones) > 0)
    <div style="margin: 0px 60px;">
        <h2 style="color: black; font-size: 0.9rem;">
            Coberturas y Devoluciones
        </h2>
        <table>
            <thead>
                <tr class="bg-orange text-white">
                    <th class="w25">Solicitado</th>
                    <th class="w25">Solicitante</th>
                    <th class="w25">Tipo</th>
                    <th class="w25">Fecha</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($coberturasDevoluciones as $cobertura)
                    <tr>
                        <td>{{ $cobertura->solicitado }}</td>
                        <td>{{ $cobertura->solicitante }}</td>
                        <td>{{ $cobertura->tipo }}</td>
                        <td>{{ $cobertura->fecha }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@else
    <h2 style="color: rgb(0, 131, 17); font-size: 0.9rem;">
        No hay coberturas ni devoluciones
    </h2>
@endif







</body>
</html>
