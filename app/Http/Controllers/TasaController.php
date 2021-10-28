<?php

namespace App\Http\Controllers;

use App\Tasa;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TasaController extends Controller
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
           $tasas      =   Tasa::on(Auth::user()->database_name)->orderBy('id', 'desc')->get();
        }elseif($users_role == '2'){
            return view('admin.index');
        }

    
        return view('admin.tasas.index',compact('tasas'));
      
    }

    public function create()
    {
        $date = Carbon::now();
        $datenow = $date->format('Y-m-d');    
        return view('admin.tasas.create',compact('datenow'));
    }

    public function store(Request $request)
    {
        
        $data = request()->validate([
           
            'date_begin'         =>'required',
            'amount'         =>'required',
            
           
        ]);

        $tasa_old = Tasa::on(Auth::user()->database_name)->where('date_end','=',null)->first();

        $date = Carbon::now();
        $datenow = $date->format('Y-m-d');  

        $tasa_old->date_end = $datenow;

        $tasa_old->save();



        $users = new Tasa();
        $users->setConnection(Auth::user()->database_name);

        $users->id_user = request('id_user');

        $users->date_begin = request('date_begin');

        $valor_sin_formato_amount = str_replace(',', '.', str_replace('.', '', request('amount')));

        $users->amount = $valor_sin_formato_amount;
        

        $users->save();

        return redirect('/tasas')->withSuccess('Registro Exitoso!');
    }



    public function edit($id)
    {

        $user    = Tasa::on(Auth::user()->database_name)->find($id);
        
        return view('admin.tasas.edit',compact('user'));
    }

   


    public function update(Request $request,$id)
    {
       
        $request->validate([
          
            'description'      =>'required|string|max:255',
            
        ]);

        

        $user          = Tasa::on(Auth::user()->database_name)->findOrFail($id);
        $user->description        = request('description');
       
     

        $user->save();


        return redirect('/tasas')->withSuccess('Registro Guardado Exitoso!');

    }


}

