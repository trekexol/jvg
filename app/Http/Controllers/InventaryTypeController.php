<?php

namespace App\Http\Controllers;

use App\Company;
use Illuminate\Http\Request;
use App\InventaryType;
use App\User;
use Illuminate\Support\Facades\Auth;

class InventaryTypeController extends Controller
{
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
        $user       =   auth()->user();
        $users_role =   $user->role_id;

        try{
            if($users_role == '1' || $users_role == '2' || $users_role == '3'  ){
                $inventarytypes      =   InventaryType::on(Auth::user()->database_name)->orderBy('id', 'asc')->get();
                return view('admin.inventarytypes.index',compact('inventarytypes'));
            }else{
                dd('No tiene acceso');
                return view('admin.index');
            }
        }catch(\Illuminate\Database\QueryException $qry_ex){//capturar excepciones de consultas en BD
            return view('admin.index');
        }catch(Throwable $th){//Capturar errores en General.
            return view('admin.index');
        }
    }

    public function create()
    {
        return view('admin.inventarytypes.create');
    }

    public function store(Request $request)
    {

        $data = request()->validate([
            'Descripcion'    =>'required|max:255',
        ]);

        $descripcion     = strtoupper(trim(request('Descripcion')));
        $inventaryTypes  = new InventaryType();
        $inventaryTypes->setConnection(Auth::user()->database_name);

        $inventaryTypes->description      = $descripcion;
        $inventaryTypes->status          = '1';

        $inventaryTypes->save();
        return redirect('/inventarytypes')->withSuccess('Registro Exitoso!');
    }

    public function edit($id)
    {
        $company            = Company::on(Auth::user()->database_name)->find($id);
        $codigo             = substr($company->razon_social,0,2);
        $razon_social       = substr($company->razon_social,2);

        return view('admin.companies.edit',compact('company','codigo','razon_social'));
    }

    public function update(Request $request,$id)
    {
        $validar              =  Company::on(Auth::user()->database_name)->find($id);

        $request->validate([
            'Nombre'         =>'required|max:191,'.$validar->id,
            'Email'          =>'required|max:255,'.$validar->id,
            'Codigo'         =>'required|max:4',
            'Razon_Social'   =>'required|max:160,'.$validar->id,
            'Descripcion'    =>'required|max:255',
            'Estado'         =>'required|max:2',
        ]);

        $nombre              = strtoupper(request('Nombre'));
        $email               = strtoupper(request('Email'));
        $descripcion         = strtoupper(request('Descripcion'));
        $codigo              = strtoupper(request('Codigo'));
        $razon_social        = strtoupper(request('Razon_Social'));
        $resul_social        = $codigo.$razon_social;

        $companies          = Company::on(Auth::user()->database_name)->findOrFail($id);
        $companies->name                = $nombre;
        $companies->email               = $email;
        $companies->description         = $descripcion;
        $companies->razon_social        = $resul_social;
        $companies->status              = request('Estado');

        $companies->save();
        return redirect('/companies')->withSuccess('Registro Guardado Exitoso!');

    }

    public function destroy(Request $request)
    {
        //find the Division
        $user = User::on(Auth::user()->database_name)->find($request->user_id);

        //Elimina el Division
        $user->delete();
        return redirect('users')->withDelete('Registro Eliminado Exitoso!');
    }
}
