<?php

namespace App\Http\Controllers;

use App\Provider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProviderController extends Controller
{

    public function __construct(){

       $this->middleware('auth');
   }

   public function index()
   {
       $user= auth()->user();
       $providers = Provider::on(Auth::user()->database_name)->orderBy('id' ,'DESC')->get();
       return view('admin.providers.index',compact('providers'));
   }

   /**
    * Show the form for creating a new resource.
    *
    * @return \Illuminate\Http\Response
    */
   public function create()
   {
       return view('admin.providers.create');
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
        'code_provider'         =>'max:20',
        'razon_social'         =>'required|max:80',
        'direction'         =>'max:100',
        'city'         =>'required|max:20',
        'country'         =>'required|max:20',
        'phone1'         =>'max:20',
        'phone2'                =>'max:20',
        'porc_retencion_iva'    =>  'min:0|max:100',
        'porc_retencion_islr'    => 'min:0|max:100',
    ]);



    $razon_social = request('razon_social');
    $city = request('city');
    $country = request('country');

    $code_provider = request('code_provider');
    if($code_provider == null){
        $code_provider = "SIN CODIGO";
    }else{
        $code_provider = request('code_provider');
    }

    $direction = request('direction');
    if($direction == null){
        $direction = "SIN DIRECCION";
    }else{
        $direction = request('direction');
    }

    $phone1 = request('phone1');
    if($phone1 == null){
        $phone1 = 0000000;
    }else{
        $phone1 = request('phone1');
    }

    $phone2 = request('phone2');
    if($phone2 == null){
        $phone2 = 00000000;
    }else{
        $phone2 = request('phone2');
    }

    $days_credit = request('days_credit');
    if($days_credit == null){
        $days_credit = 0;
    }else{
        $days_credit = request('days_credit');
    }

    $sin_formato_amount_max_credit = str_replace(',', '.', str_replace('.', '', request('amount_max_credit')));
    if($sin_formato_amount_max_credit == null){
        $amount_max_credit             = 0;
    }else{
        $sin_formato_amount_max_credit = str_replace(',', '.', str_replace('.', '', request('amount_max_credit')));
        $amount_max_credit = $sin_formato_amount_max_credit;
    }

    $sin_formato_balance = str_replace(',', '.', str_replace('.', '', request('balance')));
    if($sin_formato_balance == null){
        $balance = 0;
    }else{
        $sin_formato_balance = str_replace(',', '.', str_replace('.', '', request('balance')));
        $balance = $sin_formato_balance;
    }

    $porc_retencion_iva = str_replace(',', '.', str_replace('.', '', request('porc_retencion_iva')));
    if($porc_retencion_iva == null){
        $porc_retencion_iva = 0;
    }else{
        $porc_retencion_iva = str_replace(',', '.', str_replace('.', '', request('porc_retencion_iva')));
    }

    $porc_retencion_islr = str_replace(',', '.', str_replace('.', '', request('porc_retencion_islr')));
    if($porc_retencion_islr == null){
        $porc_retencion_islr = 0;
    }else{
        $porc_retencion_islr = str_replace(',', '.', str_replace('.', '', request('porc_retencion_islr')));
    }

    $has_credit = request('has_credit');
    if($has_credit == null){
        $has_credit = false;
    }else{
        $has_credit = true;
    }



    $users = new Provider();
    $users->setConnection(Auth::user()->database_name);

    $users->code_provider       =   $code_provider;
    $users->razon_social        =   request('razon_social');
    $users->direction           =   $direction;
    $users->city                =   request('city');
    $users->country             =   request('country');
    $users->phone1              =   $phone1;
    $users->phone2              =   $phone2;
    $users->has_credit          =   $has_credit;
    $users->days_credit         =   $days_credit;
    $users->amount_max_credit   =   $amount_max_credit;
    $users->porc_retencion_iva  =   $porc_retencion_iva;
    $users->porc_retencion_islr =   $porc_retencion_islr;
    $users->balance             =   $balance;
    $users->status              =   1;
    $users->save();

    return redirect('/providers')->withSuccess('Registro Exitoso!');
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
        $var = Provider::on(Auth::user()->database_name)->find($id);
        return view('admin.providers.edit',compact('var'));
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
        'code_provider'         =>'max:20',
        'razon_social'          =>'required|max:80',
        'direction'             =>'max:100',
        'city'                  =>'required|max:20',
        'country'               =>'required|max:20',
        'phone1'                =>'max:20',
        'phone2'                =>'max:20',
        'porc_retencion_iva'    =>  'min:0|max:100',
        'porc_retencion_islr'   => 'min:0|max:100',
    ]);




       $razon_social = request('razon_social');
       $city = request('city');
       $country = request('country');

       $code_provider = request('code_provider');
       if($code_provider == null){
           $code_provider = "SIN CODIGO";
       }else{
           $code_provider = request('code_provider');
       }

       $direction = request('direction');
       if($direction == null){
           $direction = "SIN DIRECCION";
       }else{
           $direction = request('direction');
       }

       $phone1 = request('phone1');
       if($phone1 == null){
           $phone1 = 0000000;
       }else{
           $phone1 = request('phone1');
       }

       $phone2 = request('phone2');
       if($phone2 == null){
           $phone2 = 00000000;
       }else{
           $phone2 = request('phone2');
       }

       $days_credit = request('days_credit');
       if($days_credit == null){
           $days_credit = 0;
       }else{
           $days_credit = request('days_credit');
       }

       $sin_formato_amount_max_credit = str_replace(',', '.', str_replace('.', '', request('amount_max_credit')));
       if($sin_formato_amount_max_credit == null){
           $amount_max_credit             = 0;
       }else{
           $sin_formato_amount_max_credit = str_replace(',', '.', str_replace('.', '', request('amount_max_credit')));
           $amount_max_credit = $sin_formato_amount_max_credit;
       }

       $sin_formato_balance = str_replace(',', '.', str_replace('.', '', request('balance')));
       if($sin_formato_balance == null){
           $balance = 0;
       }else{
           $sin_formato_balance = str_replace(',', '.', str_replace('.', '', request('balance')));
           $balance = $sin_formato_balance;
       }

       $porc_retencion_iva = str_replace(',', '.', str_replace('.', '', request('porc_retencion_iva')));
       if($porc_retencion_iva == null){
           $porc_retencion_iva = 0;
       }else{
           $porc_retencion_iva = str_replace(',', '.', str_replace('.', '', request('porc_retencion_iva')));
       }

       $porc_retencion_islr = str_replace(',', '.', str_replace('.', '', request('porc_retencion_islr')));
       if($porc_retencion_islr == null){
           $porc_retencion_islr = 0;
       }else{
           $porc_retencion_islr = str_replace(',', '.', str_replace('.', '', request('porc_retencion_islr')));
       }

       $has_credit = request('has_credit');
       if($has_credit == null){
           $has_credit = false;
       }else{
           $has_credit = true;
       }

    $users = Provider::on(Auth::user()->database_name)->findOrFail($id);
    $users->code_provider       =   $code_provider;
    $users->razon_social        =   request('razon_social');
    $users->direction           =   $direction;
    $users->city                =   request('city');
    $users->country             =   request('country');
    $users->phone1              =   $phone1;
    $users->phone2              =   $phone2;
    $users->has_credit          =   $has_credit;
    $users->days_credit         =   $days_credit;
    $users->amount_max_credit   =   $amount_max_credit;
    $users->porc_retencion_iva  =   $porc_retencion_iva;
    $users->porc_retencion_islr =   $porc_retencion_islr;
    $users->balance             =   $balance;
    $users->status =  request('status');
    $users->save();

    return redirect('/providers')->withSuccess('Actualizacion Exitosa!');
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
