<?php

namespace App\Http\Controllers;

use App\Color;
use App\Modelo;
use App\Transport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransportController extends Controller
{
 
    public function __construct(){

       $this->middleware('auth');
   }

   public function index()
   {
       $user       =   auth()->user();
       $users_role =   $user->role_id;
       if($users_role == '1'){
        $transports = Transport::on(Auth::user()->database_name)->orderBy('id' ,'DESC')->get();
        }

       return view('admin.transports.index',compact('transports'));
   }

   /**
    * Show the form for creating a new resource.
    *
    * @return \Illuminate\Http\Response
    */
   public function create()
   {


      //  $modelos     = Modelo::on(Auth::user()->database_name)->orderBY('description','asc')->pluck('description','id')->toArray();
      
       // $colors     = Color::on(Auth::user()->database_name)->orderBY('description','asc')->pluck('description','id')->toArray();
       $modelos     = Modelo::on(Auth::user()->database_name)->get();
       $colors      = Color::on(Auth::user()->database_name)->get();

        return view('admin.transports.create',compact('modelos','colors'));
   }

   /**
    * Store a newly created resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
   public function store(Request $request)
    {
   
    $data = request()->validate([
        
       
        'modelo_id'         =>'required',
        'color_id'         =>'required',
        'user_id'         =>'required',

        'type'         =>'required',
        'placa'         =>'required',
        

        'status'         =>'required',
       
    ]);

    $var = new Transport();
    $var->setConnection(Auth::user()->database_name);

    $var->modelo_id = request('modelo_id');
    $var->color_id = request('color_id');
    $var->user_id = request('user_id');
    $var->type = request('type');
   
    $var->placa = request('placa');
    $var->photo_transport = request('photo_transport');

    $var->status =  request('status');
  
    $var->save();

    return redirect('/transports')->withSuccess('Registro Exitoso!');
    }

   /**
    * Display the specified resource.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
   public function show($id)
   {
       //
   }

   /**
    * Show the form for editing the specified resource.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
   public function edit($id)
   {
        $transport = Transport::on(Auth::user()->database_name)->find($id);
       
        $modelos     = Modelo::on(Auth::user()->database_name)->orderBY('description','asc')->pluck('description','id')->toArray();
      
        $colors     = Color::on(Auth::user()->database_name)->orderBY('description','asc')->pluck('description','id')->toArray();
      
        return view('admin.transports.edit',compact('transport','modelos','colors'));
  
   }

   /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
   public function update(Request $request, $id)
   {

    $vars =  Transport::on(Auth::user()->database_name)->find($id);

    $vars_status = $vars->status;
    $vars_exento = $vars->exento;
    $vars_islr = $vars->islr;
  
    $data = request()->validate([
        
       
        'modelo_id'         =>'required',
        'color_id'         =>'required',
        'user_id'         =>'required',

        'type'         =>'required',
        'placa'         =>'required',
        'photo_transport'         =>'required',

        'status'         =>'required',
       
    ]);

    $var = Transport::on(Auth::user()->database_name)->findOrFail($id);

    $var->modelo_id = request('modelo_id');
    $var->color_id = request('color_id');
    $var->user_id = request('user_id');
    $var->type = request('type');
   
    $var->placa = request('placa');
    $var->photo_transport = request('photo_transport');

    if(request('status') == null){
        $var->status = $vars_status;
    }else{
        $var->status = request('status');
    }
   
    $var->save();

    return redirect('/transports')->withSuccess('Actualizacion Exitosa!');
    }


   /**
    * Remove the specified resource from storage.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
   public function destroy($id)
   {
       //
   }
}
