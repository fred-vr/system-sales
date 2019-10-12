<?php

namespace App\Http\Controllers;

use App\Log;
use Illuminate\Http\Request;
use App\Proveedor;

class ProveedorController extends Controller
{
    //
    public function index(Request $request)
    {
        //
        if(!$request->ajax()) return redirect('/');

        $buscar= $request->buscar;
        $criterio= $request->criterio;

        if($buscar==''){

            $proveedores= Proveedor::orderBy('id','desc')->paginate(3);

        } else{

            $proveedores= Proveedor::where($criterio,'like','%'.$buscar.'%')->orderBy('id','desc')->paginate(3);
        }


        return[

            'pagination' => [
            'total'            => $proveedores->total(),
            'current_page'     => $proveedores->currentPage(),
            'per_page'         => $proveedores->perPage(),
            'last_page'        => $proveedores->lastPage(),
            'from'             => $proveedores->firstItem(),
            'to'               => $proveedores->lastItem(),

            ],

            'proveedores' =>$proveedores

        ];

    }

    public function selectProveedor(Request $request){
        //if (!$request->ajax()) return redirect('/');

        $filtro = $request->filtro;
        $proveedores = Proveedor::orderBy('id', 'asc')->get();

        return ['proveedores' => $proveedores];
    }

    public function store(Request $request)
    {
        //
        if(!$request->ajax()) return redirect('/');
        $proveedor= new Proveedor();
        $proveedor->nombre = $request->nombre;
        $proveedor->tipo_documento = $request->tipo_documento;
        $proveedor->num_documento = $request->num_documento;
        $proveedor->telefono = $request->telefono;
        $proveedor->email = $request->email;
        $proveedor->direccion = $request->direccion;
        $proveedor->save();

        $array= array($request->nombre,'-', $request->tipo_documento,'-', $request->num_documento,'-', $request->telefono,'-', $request->email,'-', $request->direccion);

        $log = new Log();
        $log->ip = $request->server->get('REMOTE_ADDR');
        $log->entrada = json_encode($array);
        $log->salida = 'NO APLICA';
        $log->tabla = 'Proveedor';
        $log->proceso = 'Insertar';
        $log->usuario = auth()->user()->nombre;
        $log->save();


    }

    public function update(Request $request)
    {
        //
        if(!$request->ajax()) return redirect('/');
        $proveedor= Proveedor::findOrFail($request->id);

        $data_entrate = array(
            'nombre' => $proveedor->codigo,
            '-'=>"",
            'tipo_documento' => $proveedor->nombre,
            '-'=>"",
            'num_documento' => $proveedor->precio_venta,
            '-'=>"",
            'telefono' => $proveedor->telefono,
            '-'=>"",
            'email' => $proveedor->email,
        );


        $proveedor->nombre = $request->nombre;
        $proveedor->tipo_documento = $request->tipo_documento;
        $proveedor->num_documento = $request->num_documento;
        $proveedor->telefono = $request->telefono;
        $proveedor->email = $request->email;
        $proveedor->direccion = $request->direccion;
        $proveedor->save();


        $array= array($request->nombre, $request->tipo_documento,'-', $request->num_documento,'-', $request->telefono,'-', $request->email,'-', $request->direccion, '-', date('Y-m-d H:i:s'));

        $log = new Log();
        $log->ip = $request->server->get('REMOTE_ADDR');
        $log->entrada = json_encode($data_entrate);
        $log->salida = json_encode($array);
        $log->tabla = 'Proveedor';
        $log->proceso = 'Actualiuzar';
        $log->usuario = auth()->user()->nombre;
        $log->save();

    }

}
