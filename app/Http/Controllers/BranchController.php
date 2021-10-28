<?php

namespace App\Http\Controllers;

use App\Branch;
use App\Company;
use App\Estado;
use App\Municipio;
use App\Parroquia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BranchController extends Controller
{
 
    public function __construct(){

       $this->middleware('auth');
   }

   public function index()
   {
       $user= auth()->user();

       $branches = Branch::on(Auth::user()->database_name)->orderBy('id' ,'DESC')->get();
       //dd($user->estado_id);

       //$rol = $user->roles();
       //dd($rol);
       //$persons = Person::on(Auth::user()->database_name)->where('estado_id', $user->estado_id)
                           //->orderBy('id', 'DESC')
                           //->get();
       // dd($persons);
       return view('admin.branches.index',compact('branches'));
   }

   /**
    * Show the form for creating a new resource.
    *
    * @return \Illuminate\Http\Response
    */
   public function create()
   {
        $estados            = Estado::on(Auth::user()->database_name)->orderBY('descripcion','asc')->pluck('descripcion','id')->toArray();
        $municipios         = Municipio::on(Auth::user()->database_name)->get();
        $companies     = Company::on(Auth::user()->database_name)->orderBy('razon_social', 'DESC')->get();
        $parroquias         = Parroquia::on(Auth::user()->database_name)->orderBy('descripcion', 'ASC')->get();

       return view('admin.branches.create',compact('companies','parroquias','estados','municipios'));
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
      
        'description'         =>'required|max:80',
        'direction'         =>'required|max:100',

        'phone'         =>'required|max:20',
        'phone2'         =>'required|max:20',

        
        'person_contact'         =>'required|max:160',
        'phone_contact'    =>'required|max:30',
        'observation'    =>'required|max:150',
       
    ]);

    $users = new Branch();
    $users->setConnection(Auth::user()->database_name);

    $users->company_id = request('company_id');
    $users->parroquia_id = request('Parroquia');
    $users->description = request('description');
    $users->direction = request('direction');

    $users->phone = request('phone');
    $users->phone2 = request('phone2');

  
    $users->person_contact = request('person_contact');
    $users->phone_contact = request('phone_contact');
    $users->observation = request('observation');
  
    $users->status =  request('status');
   
    $users->save();

    return redirect('/branches')->withSuccess('Registro Exitoso!');
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
        $var = Branch::on(Auth::user()->database_name)->find($id);
      
        //SIRVE PARA OBTENER EL ID DE SU ESTADO Y DE SU MUNICIPIO, YA QUE NO ESTA GUARDADO EN LA TABLA BRANCH
        $parroquia_guia = Parroquia::on(Auth::user()->database_name)->find($var->parroquia_id);

        $parroquias = Parroquia::on(Auth::user()->database_name)->get();
        $estados = Estado::on(Auth::user()->database_name)->get();
        $municipios = Municipio::on(Auth::user()->database_name)->get();
        $companies = Company::on(Auth::user()->database_name)->get();
      

        return view('admin.branches.edit',compact('var','parroquia_guia','parroquias',
                                                    'estados','municipios','companies'));
  
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

   
    $data = request()->validate([
        
        'description'         =>'required|max:80',
        'direction'         =>'required|max:100',

        'phone'         =>'required|max:20',
        'phone2'         =>'required|max:20',

        
        'person_contact'         =>'required|max:160',
        'phone_contact'    =>'required|max:30',
        'observation'    =>'required|max:150',
       
    ]);



    $users = Branch::on(Auth::user()->database_name)->findOrFail($id);

    
    
    $users->company_id = request('company_id');
    $users->parroquia_id = request('Parroquia');
    $users->description = request('description');
    $users->direction = request('direction');

    $users->phone = request('phone');
    $users->phone2 = request('phone2');

  
    $users->person_contact = request('person_contact');
    $users->phone_contact = request('phone_contact');
    $users->observation = request('observation');
  
    $users->status =  request('status');
  
      

    $users->save();

    return redirect('/branches')->withSuccess('Actualizacion Exitosa!');
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

