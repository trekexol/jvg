<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\User;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Hash;



use App\Permission\Models\Role;

class UserController extends Controller
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
           $users      =   User::on(Auth::user()->database_name)->orderBy('id', 'asc')->get();
        }elseif($users_role == '2'){
            return view('admin.index');
        }

    
        return view('admin.users.index',compact('users'));
      
    }

    public function create()
    {

       /* $personregister         = Person::on(Auth::user()->database_name)->find($id);
        $estados                = Estado::on(Auth::user()->database_name)->orderBY('descripcion','asc')->pluck('descripcion','id')->toArray();
        $municipios             = Municipio::on(Auth::user()->database_name)->orderBY('descripcion','asc')->pluck('descripcion','id')->toArray();
       */ $roles                  = Role::on(Auth::user()->database_name)->orderBY('descripcion','asc')->pluck('descripcion','id')->toArray();

        return view('admin.users.create',compact('roles'));
    }

    public function store(Request $request)
    {
        //
        $data = request()->validate([
            'email'         =>'required|max:255|unique:users,email',
            'name'         =>'required|max:160',
            'password'         =>'required|max:255|confirmed|min:6',
            
           
        ]);

        $users = new User();
        $users->setConnection(Auth::user()->database_name);

        $users->name = request('name');
        $users->email = request('email');
        $users->password = Hash::make(request('password'));
        $users->role_id = request('roles_id');
        $users->status =  request('status');

        $users->save();
        return redirect('/users')->withSuccess('Registro Exitoso!');
    }



    public function edit($id)
    {

        $user   = User::on(Auth::user()->database_name)->find($id);
        $roles  = Role::on(Auth::user()->database_name)->get();

        return view('admin.users.edit',compact('user','roles'));
    }

   


    public function update(Request $request,$id)
    {
       
        $users =  User::on(Auth::user()->database_name)->find($id);
        $user_rol = $users->role_id;
        $user_status = $users->status;
      

        $request->validate([
            'name'      =>'required|string|max:255',
            'email'     =>'required|max:120|unique:users,email,'.$users->id,
            'Roles'     =>'max:2',
            'password'  =>'max:255|confirmed',
            'status'     =>'max:2',
        ]);//verifica que el usuario existe

        

        if(isset($password)){
            $password = Hash::make(request('password'));
        }else{
            $password = $users->password;
        }
        $user          = User::on(Auth::user()->database_name)->findOrFail($id);
        $user->name         = request('name');
        $user->email        = request('email');
        $user->password     = $password;

        if(request('Roles') == null){
            $user->role_id = $user_rol;
        }else{
            $user->role_id = request('Roles');
        }

        if(request('status') == null){
            $user->status = $user_status;
        }else{
            $user->status = request('status');
        }
       

        $user->save();


        return redirect('/users')->withSuccess('Registro Guardado Exitoso!');

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
