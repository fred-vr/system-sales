<?php

namespace App\Http\Controllers;

use App\Log;
use Illuminate\Http\Request;
use App\Cliente;

class ClienteController extends Controller
{
    //

    public function index(Request $request)
    {
        //
        if(!$request->ajax()) return redirect('/');

        $buscar= $request->buscar;
        $criterio= $request->criterio;

        if($buscar==''){

            $clientes= Cliente::orderBy('id','desc')->paginate(3);

        } else{

            $clientes= Cliente::where($criterio,'like','%'.$buscar.'%')->orderBy('id','desc')->paginate(3);
        }


        return[

            'pagination' => [
            'total'            => $clientes->total(),
            'current_page'     => $clientes->currentPage(),
            'per_page'         => $clientes->perPage(),
            'last_page'        => $clientes->lastPage(),
            'from'             => $clientes->firstItem(),
            'to'               => $clientes->lastItem(),

            ],

            'clientes' =>$clientes

        ];

    }

    public function selectCliente(Request $request){
        if (!$request->ajax()) return redirect('/');

        $filtro = $request->filtro;
        $clientes = Cliente::where('nombre', 'like', '%'. $filtro . '%')
        ->orWhere('num_documento', 'like', '%'. $filtro . '%')
        ->select('id','nombre','num_documento')
        ->orderBy('nombre', 'asc')->get();

        return ['clientes' => $clientes];
    }

    public function store(Request $request)
    {
        //
        if(!$request->ajax()) return redirect('/');
        $cliente= new Cliente();
        $cliente->nombre = $request->nombre;
        $cliente->tipo_documento = $request->tipo_documento;
        $cliente->num_documento = $request->num_documento;
        $cliente->telefono = $request->telefono;
        $cliente->email = $request->email;
        $cliente->direccion = $request->direccion;
        $cliente->save();

        $array= array($request->nombre, '-', $request->tipo_documento, '-', $request->num_documento,'-', $request->telefono,'-', $request->email,'-', $request->direccion, '-', date('Y-m-d H:i:s'));

        $log = new Log();
        $log->ip = $request->server->get('REMOTE_ADDR');
        $log->entrada = json_encode($array);
        $log->salida = 'NO APLICA';
        $log->tabla = 'Cliente';
        $log->proceso = 'Insertar';
        $log->usuario = auth()->user()->nombre;
        $log->save();
    }

    public function update(Request $request)
    {
        //
        if(!$request->ajax()) return redirect('/');
        $cliente= Cliente::findOrFail($request->id);
            $data_entrate = array(
                'nombre' =>$cliente->nombre,
                '-'=>"",
                't_documento' =>$cliente->num_documento,
                '-'=>"",
                'email' =>$cliente->email,
                '-'=>"",
                'telefono' =>$cliente->telefono,
                '-'=>"",
                'fecha' => date('Y-m-d H:i:s')

            );

        $cliente->nombre = $request->nombre;
        $cliente->tipo_documento = $request->tipo_documento;
        $cliente->num_documento = $request->num_documento;
        $cliente->telefono = $request->telefono;
        $cliente->email = $request->email;
        $cliente->direccion = $request->direccion;
        $cliente->save();

        $array= array($request->nombre,'-', $request->tipo_documento,'-', $request->num_documento,'-', $request->telefono,'-', $request->email,'-', $request->direccion, '-', date('Y-m-d H:i:s'));



        $log = new Log();
        $log->ip = $request->server->get('REMOTE_ADDR');
        $log->entrada = json_encode($data_entrate);
        $log->salida = json_encode($array);
        $log->tabla = 'Cliente';
        $log->proceso = 'Actualizar';
        $log->usuario = auth()->user()->nombre;
        $log->save();


    }

}
