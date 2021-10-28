<?php

namespace App\Http\Controllers;

use App\ComisionType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ComisionTypeController extends Controller
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
           $comisiontypes      =   ComisionType::on(Auth::user()->database_name)->orderBy('id', 'asc')->get();
        }elseif($users_role == '2'){
            return view('admin.index');
        }

    
        return view('admin.comisiontypes.index',compact('comisiontypes'));
      
    }

    public function create()
    {

        

        return view('admin.comisiontypes.create');
    }

    public function store(Request $request)
    {
        
        $data = request()->validate([
           
           
            'description'         =>'required|max:100',
            'status'         =>'required|max:1',
            
           
        ]);

        $users = new Comisiontype();
        $users->setConnection(Auth::user()->database_name);
      
        $users->description = request('description');
        $users->status =  request('status');
       

        $users->save();

        return redirect('/comisiontypes')->withSuccess('Registro Exitoso!');
    }



    public function edit($id)
    {

        $var = Comisiontype::on(Auth::user()->database_name)->find($id);
        
        return view('admin.comisiontypes.edit',compact('var'));
    }

   


    public function update(Request $request,$id)
    {
       
        $users =  Comisiontype::on(Auth::user()->database_name)->find($id);
        
        $user_status = $users->status;
      

        $request->validate([
           
            'description'      =>'required|string|max:100',
            'status'     =>'max:2',
        ]);

        

        $user          = comisiontype::on(Auth::user()->database_name)->findOrFail($id);
       
        $user->description        = request('description');
       
        if(request('status') == null){
            $user->status = $user_status;
        }else{
            $user->status = request('status');
        }
       

        $user->save();


        return redirect('/comisiontypes')->withSuccess('Registro Guardado Exitoso!');

    }


}

