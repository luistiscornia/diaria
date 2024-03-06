<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {

        // Si existe sector-etiqueta en la sesión, carga la vista home (dash)
        if( session()->exists("sector-etiqueta") ){
            return view('home')
                ->withLivewireComponent('planillas.lista');

        }else{
            return view('seleccion-sector');
        }

    }


    /**
     * Limpia o Almacena sector-etiqueta en la sesión.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeSector(Request $request)
    {
        // Si existe limpiar-sector, remueve el sector-etiqueta de la sesión y
        // vuelve a cargar para mostrar la pantalla de selección de sector.
        if($request->has('limpiar-sector')){
            session()->forget('sector-etiqueta');
            return redirect()->route('home');
        }

        // Valida entonces que exista y el sector sea S, E o T
        $request->validate([
            'sector' => 'required|string|in:S,E,T'
        ]);

        // Almacena el sector-etiqueta en la sesión y redirecciona
        session()->put('sector-etiqueta', $request->sector);

        return redirect()->route('home');
    }




    public function seleccionPuesto(Request $request)
    {
        return view('home')
            ->withLivewireComponent('seleccion-puesto');
    }





}
