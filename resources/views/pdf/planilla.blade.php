<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        /* DOMPDF */
        @page {
            margin: 40px 20px;
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
            line-height: 0.5; /* Nuevo estilo para el interlineado */
        }
        
        th, td {
            text-align: center;
            height: 30px;
            border-bottom: 1px solid rgb(190, 190, 190);
            line-height: 0.5; /* Nuevo estilo para el interlineado */
        }

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

        .container {
            width: 760px; /* Ancho total de la página */
            margin: 0 auto; /* Centrar en la página */
        }

        .table-container table {
            width: 100%;
        }
    </style>
    <center><img src="img/logo2_dnm.png" alt="encabezado" width="650" height="43"></center><br>
</head>
<body>
    <footer>
        Reporte generado por {{$supervisor->name}} el {{now()->format('d/m/Y')}} a las {{now()->format('H:i')}}hs
</footer>
    </footer>
    
    <div class="container">
        <h1 style="color: black; font-size: 1.1rem;">
                             Paso Internacional Posadas/Encarnación Pry-026<br>
                             Parte Diario de Asistencia del {{$fechaStr}}
    </h1>
        <h2 style="color: black; font-size: 0.9rem;">Jefe de turno: {{$jefeDeTurno->nombre_agente ?? 'Sin Jefe de Turno'}}</h2>
        <h3 style="color: black; font-size: 0.7rem;">Total Agentes: {{$agentesTotal}}</h3>

        <!-- Tabla principal -->
        <div class="table-container">
            <table>
                <thead>
            <tr style="line-height: 0.5;" class="bg-primary-100 text-white">
                <th style="line-height: 0.5 !important; width: 10%;">Horario</th>
                <th style="line-height: 0.5 !important; width: 10%;">Sector</th>
                <th style="line-height: 0.5 !important; width: 30%;">Agente</th>
                <th style="line-height: 0.5 !important; width: 20%;">Tipo Agente</th>
                <th style="line-height: 0.5 !important; width: 10%;">Guardia</th>
                <th style="line-height: 0.5 !important; width: 10%;">Día</th>
                {{--<th style="width: 10%;">Reemplaza a:</th>--}}
                {{--<th style="width: 10%;">Detalle</th>--}}
                <th style="line-height: 0.5 !important; width: 5%;">Presente</th>
                <th style="line-height: 0.5 !important; width: 5%;"></th>
                {{-- Horario Sector Agente Puesto Guardia Día Reemplaza a: Detalle Presente --}}
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $ingreso => $items)
                <tr>
                    <td class="time-row" colspan="10">
                        Horario: {{$ingreso}} &nbsp; &nbsp;  >>>> &nbsp; &nbsp; {{count($items)}} Agentes
                    </td>
                </tr>
                @foreach ($items as $item)
                    <tr>
                        <td style="line-height: 0.5 !important;">{{$ingreso}}</td>
                        <td style="line-height: 0.5 !important;">{{$item->etiqueta}}</td>
                        <td style="line-height: 0.5 !important;">{{$item->nombre_agente}}</td>
                        <td style="line-height: 0.5 !important;">{{$item->nombre_puesto_migratorio}}</td>
                        <td style="line-height: 0.5 !important;">{{$item->guardia}}</td>
                        <td style="line-height: 0.5 !important;">{{$item->dia}}</td>
                        {{--<td>{{$item->agente_cobertura}}</td>--}}
                        {{--<td>{{$item->nota}}</td>--}}
                        <td style="line-height: 0.5 !important;">
                            @if ($item->presente == 'SI')
                                <span class="box bg-primary-100">SI</span>
                            @else
                                <span class="box bg-primary-200">{{$item->presente}}</span>
                            @endif
                        </td>
                        <td></td>
                    </tr>
                @endforeach
            @endforeach
        </tbody>
    </table>
</div>


    <div>
<center><img src="img/footer.png" alt="" width="760" height="33"></center><br>
</div>

<!-- Salto de línea -->
<div style="margin-bottom: 40px;"></div>
        

        <!-- Tabla de coberturas/devoluciones -->
        <div style="margin: 0px 60px;">
            <h2 style="color: black; font-size: 0.9rem;">Coberturas y devoluciones del día</h2>
            <table class="min-w-full table-auto ">
                <thead class="border-b bg-white">
                    <tr class="bg-primary-100 text-white">
                        <th class="text-sm font-medium text-dark-100 p-1">Agente de guardia</th>
                        <th class="text-sm font-medium text-dark-100 p-1">Reemplaza a</th>
                        <th class="text-sm font-medium text-dark-100 p-1">Concepto</th>
                    </tr>
                </thead>
                <tbody>
                    @if(count($historial))
                        @foreach ($historial as $item)
                            <tr>
                                <td class="text-center border-b  py-0 text-sm font-normal text-secondary-100">{{$item->nombre_agente_solicitado}}</td>
                                <td class="text-center border-b  py-0 text-sm font-normal text-secondary-100">{{$item->nombre_agente_solicitante}}</td>
                                <td class="text-center border-b  py-0 text-sm font-normal text-secondary-100">{{$item->tipo}}</td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="{{ count($headers) }}">
                                <p class="text-sm text-center">No coberturas ni devoluciones en la fecha</p>
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
    
    <!-- Tabla de Licencias -->
        @if (count($agentesConLicencia) > 0)
            <div style="margin: 0px 60px;">
                <h2 style="color: black; font-size: 0.9rem;">Agentes con licencias en el día</h2>
                <table>
                    <thead>
                        <tr class="bg-primary-100 text-white">
                            <th class="w10">Agente</th>
                            <th class="w5">Tipo de licencia</th>
                            <th class="w5">Desde</th>
                            <th class="w5">Hasta</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($agentesConLicencia as $agente)
                            <tr>
                                <td>{{$agente->nombre_agente}}</td>
                                <td>{{$agente->tipolicencia__nombre}}</td>
                                <td>{{$agente->licencia__fecha_desde}}</td>
                                <td>{{$agente->licencia__fecha_hasta}}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <h2 style="color: rgb(0, 131, 17); font-size: 0.9rem;">No hay agentes con licencias</h2>
        @endif

</body>
</html>
