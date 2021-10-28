<?php

namespace App\Http\Controllers;

use App\Profession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfessionsController extends Controller
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
           $professions      =   Profession::on(Auth::user()->database_name)->orderBy('id', 'asc')->get();
        }elseif($users_role == '2'){
            return view('admin.index');
        }

    
        return view('admin.professions.index',compact('professions'));
      
    }

    public function create()
    {

        

        return view('admin.professions.create');
    }

    public function store(Request $request)
    {
        
        $data = request()->validate([
           
            'name'         =>'required|max:160',
            'description'         =>'required|max:255',
            'status'         =>'required|max:2',
            
           
        ]);

        $users = new Profession();
        $users->setConnection(Auth::user()->database_name);

        $users->name = request('name');
        $users->description = request('description');
        $users->status =  request('status');
       

        $users->save();

        return redirect('/professions')->withSuccess('Registro Exitoso!');
    }



    public function edit($id)
    {

        $user  = Profession::on(Auth::user()->database_name)->find($id);
        
        return view('admin.professions.edit',compact('user'));
    }

   


    public function update(Request $request,$id)
    {
       
        $users =  Profession::on(Auth::user()->database_name)->find($id);
        $user_rol = $users->role_id;
        $user_status = $users->status;
      

        $request->validate([
            'name'      =>'required|string|max:255',
            'description'      =>'required|string|max:255',
            'status'     =>'max:2',
        ]);

        

        $user          = Profession::on(Auth::user()->database_name)->findOrFail($id);
        $user->name         = request('name');
        $user->description        = request('description');
       
        if(request('status') == null){
            $user->status = $user_status;
        }else{
            $user->status = request('status');
        }
       

        $user->save();


        return redirect('/professions')->withSuccess('Registro Guardado Exitoso!');

    }


}
