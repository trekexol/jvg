<?php

namespace App\Http\Controllers;

use App\UnitOfMeasure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UnitOfMeasureController extends Controller
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
           $unitofmeasures      =   UnitOfMeasure::on(Auth::user()->database_name)->orderBy('id', 'asc')->get();
        }elseif($users_role == '2'){
            return view('admin.index');
        }

    
        return view('admin.unitofmeasures.index',compact('unitofmeasures'));
      
    }

    public function create()
    {

        

        return view('admin.unitofmeasures.create');
    }

    public function store(Request $request)
    {
        
        $data = request()->validate([
           
            'code'         =>'required|max:5',
            'description'         =>'required|max:100',
            'status'         =>'required|max:1',
            
           
        ]);

        $users = new UnitOfMeasure();
        $users->setConnection(Auth::user()->database_name);
        
        $users->code = request('code');
        $users->description = request('description');
        $users->status =  request('status');
       

        $users->save();

        return redirect('/unitofmeasures')->withSuccess('Registro Exitoso!');
    }



    public function edit($id)
    {

        $var = UnitOfMeasure::on(Auth::user()->database_name)->find($id);
        
        return view('admin.unitofmeasures.edit',compact('var'));
    }

   


    public function update(Request $request,$id)
    {
       
        $users =  UnitOfMeasure::on(Auth::user()->database_name)->find($id);
       
        $user_status = $users->status;
      

        $request->validate([
            'code'         =>'required|max:5',
            'description'         =>'required|max:100',
            'status'         =>'required|max:1',
        ]);

        

        $user          = UnitOfMeasure::on(Auth::user()->database_name)->findOrFail($id);
        $user->code = request('code');
        $user->description = request('description');
       
       
        if(request('status') == null){
            $user->status = $user_status;
        }else{
            $user->status = request('status');
        }
       

        $user->save();


        return redirect('/unitofmeasures')->withSuccess('Registro Guardado Exitoso!');

    }


}
