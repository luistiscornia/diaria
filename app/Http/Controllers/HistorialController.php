<?php

use App\Historial;
use Illuminate\Http\Request;

class HistorialController extends Controller
{
    public function mostrarHistorial(Request $request)
    {
        // Obtener la fecha del request o una fecha por defecto
        $fechaStr = $request->input('fecha', date('Y-m-d'));

        // Consulta para obtener los datos del historial filtrados por fecha
        $historial = Historial::where('fecha_cobertura', $fechaStr)->get();

        // Retornar la vista con los datos del historial
        return view('historial.index', ['historial' => $historial, 'fechaStr' => $fechaStr]);
    }
}
