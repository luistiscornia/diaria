<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historial</title>
</head>
<body>
    <h1>Historial</h1>
    <table>
        <thead>
            <tr>
                <th>Agente Solicitado</th>
                <th>Agente Solicitante</th>
                <th>ID de Planilla</th>
                <th>Tipo</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($historial as $registro)
            <tr>
                <td>{{ $registro->nombre_agente_solicitado }}</td>
                <td>{{ $registro->nombre_agente_solicitante }}</td>
                <td>{{ $registro->id_planilla }}</td>
                <td>{{ $registro->tipo }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
