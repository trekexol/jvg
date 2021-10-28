<?php

namespace App\Http\Controllers;

use App\NominaFormula;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NominaFormulaController extends Controller
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
        if($users_role == '1'){
           $nomina_formulas      =   NominaFormula::on(Auth::user()->database_name)->orderBy('id', 'asc')->get();
        }elseif($users_role == '2'){
            return view('admin.index');
        }

    
        return view('admin.nominaformulas.index',compact('nomina_formulas'));
      
    }

    public function create()
    {

        

        return view('admin.nominaformulas.create');
    }

    public function store(Request $request)
    {
        
        
        $data = request()->validate([
           
          
            'description'         =>'required|max:200',
            'type'         =>'required|max:1',
           
        ]);

        $users = new NominaFormula();
        $users->setConnection(Auth::user()->database_name);

        $users->description = request('description');
        $users->type = request('type');
        $users->status = 1;
        

        $users->save();

        return redirect('/nominaformulas')->withSuccess('Registro Exitoso!');
    }



    public function edit($id)
    {

        $var   = NominaFormula::on(Auth::user()->database_name)->find($id);
        
        return view('admin.nominaformulas.edit',compact('var'));
    }

   


    public function update(Request $request,$id)
    {
        $vars =  NominaFormula::on(Auth::user()->database_name)->find($id);
        $var_status = $vars->status;

        $request->validate([
          
            'description'      =>'required|string|max:200',
            'type'    =>'required|max:1',
        ]);

        

        $var          = NominaFormula::on(Auth::user()->database_name)->findOrFail($id);
        $var->description        = request('description');
        $var->type        = request('type');
       
        if(request('status') == null){
            $var->status = $var_status;
        }else{
            $var->status = request('status');
        }
       

        $var->save();


        return redirect('/nominaformulas')->withSuccess('Registro Guardado Exitoso!');

    }


}
