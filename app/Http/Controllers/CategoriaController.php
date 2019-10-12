<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Categoria;
use App\Log;

class CategoriaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        if(!$request->ajax()) return redirect('/');

        $buscar= $request->buscar;
        $criterio= $request->criterio;

        if($buscar==''){

            $categorias= Categoria::orderBy('id','desc')->paginate(3);

        } else{

            $categorias= Categoria::where($criterio,'like','%'.$buscar.'%')->orderBy('id','desc')->paginate(3);
        }


        return[

            'pagination' => [
            'total'            => $categorias->total(),
            'current_page'     => $categorias->currentPage(),
            'per_page'         => $categorias->perPage(),
            'last_page'        => $categorias->lastPage(),
            'from'             => $categorias->firstItem(),
            'to'               => $categorias->lastItem(),

            ],

            'categorias' =>$categorias

        ];

    }

    public function selectCategoria(Request $request){

        if(!$request->ajax()) return redirect('/');
        $categorias = Categoria::where('condicion','=','1')
        ->select('id','nombre')->orderBy('nombre','asc')->get();

        return ['categorias' => $categorias];
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        if (!$request->ajax()) return redirect('/');

        $categoria = new Categoria();
        $categoria->nombre = $request->nombre;
        $categoria->descripcion = $request->descripcion;
        $categoria->condicion = '1';
        $categoria->save();

        $array= array($request->nombre,'-', $request->descripcion,'-', 1,'-', date('Y-m-d H:i:s') );

        $log = new Log();
        $log->ip = $request->server->get('REMOTE_ADDR');
        $log->entrada = json_encode($array) ;
        $log->salida = 'NO APLICA' ;
        $log->tabla = 'Categoria';
        $log->proceso = 'insertar';
        $log->usuario = auth()->user()->nombre;
        $log->save();
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        //
        if(!$request->ajax()) return redirect('/');
        $categoria= Categoria::findOrFail($request->id);

        $data_entrate = array(
            'nombre' =>$categoria->nombre,
             '-'=>"",
            'descripcion' =>$categoria->descripcion
        );


        $categoria->nombre= $request->nombre;
        $categoria->descripcion= $request->descripcion;
        $categoria->condicion= '1';
        $categoria->save();

        $array= array($request->nombre,'-', $request->descripcion,'-', 1);

        $log = new Log();
        $log->ip = $request->server->get('REMOTE_ADDR');
        $log->entrada = json_encode($data_entrate);
        $log->salida = json_encode($array);
        $log->tabla = 'Categoria';
        $log->proceso = 'Actualizar';
        $log->usuario = auth()->user()->nombre;
        $log->save();


    }

    public function desactivar(Request $request)
    {
        //
        if(!$request->ajax()) return redirect('/');
        $categoria= Categoria::findOrFail($request->id);
        $categoria->condicion= '0';
        $categoria->save();

        $log = new Log();
        $log->ip = $request->server->get('REMOTE_ADDR');
        $log->entrada = 1 ;
        $log->salida = 0 ;
        $log->tabla = 'Categoria';
        $log->proceso = 'Desactivar';
        $log->usuario = auth()->user()->nombre;
        $log->save();
    }

    public function activar(Request $request)
    {
        //
        if(!$request->ajax()) return redirect('/');
        $categoria= Categoria::findOrFail($request->id);
        $categoria->condicion= '1';
        $categoria->save();

        $log = new Log();
        $log->ip = $request->server->get('REMOTE_ADDR');
        $log->entrada = 0 ;
        $log->salida = 1 ;
        $log->tabla = 'Categoria';
        $log->proceso = 'Activar';
        $log->usuario = auth()->user()->nombre;
        $log->save();
    }


}
