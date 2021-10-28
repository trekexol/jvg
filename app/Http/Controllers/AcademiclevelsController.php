<?php

namespace App\Http\Controllers;

use App\Academiclevel;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AcademiclevelsController extends Controller
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
           $academiclevels      =   Academiclevel::on(Auth::user()->database_name)->orderBy('id', 'asc')->get();
        }elseif($users_role == '2'){
            return view('admin.index');
        }

    
        return view('admin.academiclevels.index',compact('academiclevels'));
      
    }

    public function create()
    {

        

        return view('admin.academiclevels.create');
    }

    public function store(Request $request)
    {
        
        $data = request()->validate([
           
            'name'         =>'required|max:160',
            'description'         =>'required|max:255',
            'status'         =>'required|max:2',
            
           
        ]);

        $users = new Academiclevel();
        $users->setConnection(Auth::user()->database_name);
        $users->name = request('name');
        $users->description = request('description');
        $users->status =  request('status');
       

        $users->save();

        return redirect('/academiclevels')->withSuccess('Registro Exitoso!');
    }



    public function edit($id)
    {

        $user = Academiclevel::on(Auth::user()->database_name)->find($id);
        
        return view('admin.academiclevels.edit',compact('user'));
    }

   


    public function update(Request $request,$id)
    {
       
        $users =  Academiclevel::on(Auth::user()->database_name)->find($id);
        $user_rol = $users->role_id;
        $user_status = $users->status;
      

        $request->validate([
            'name'      =>'required|string|max:255',
            'description'      =>'required|string|max:255',
            'status'     =>'max:2',
        ]);

        

        $user          = Academiclevel::on(Auth::user()->database_name)->findOrFail($id);
        $user->name         = request('name');
        $user->description        = request('description');
       
        if(request('status') == null){
            $user->status = $user_status;
        }else{
            $user->status = request('status');
        }
       

        $user->save();


        return redirect('/academiclevels')->withSuccess('Registro Guardado Exitoso!');

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
