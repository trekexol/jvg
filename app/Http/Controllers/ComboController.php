<?php

namespace App\Http\Controllers;

use App\Combo;
use App\ComboProduct;
use App\Inventory;
use App\Product;

use App\Segment;
use App\Subsegment;
use App\ThreeSubsegment;
use App\TwoSubSegment;
use App\UnitOfMeasure;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PhpParser\Node\Stmt\Foreach_;

class ComboController extends Controller
{
    public function __construct(){

        $this->middleware('auth');
 
    }
 
    public function index()
    {
        $user       =   auth()->user();
        $users_role =   $user->role_id;
    
        $combos = Product::on(Auth::user()->database_name)->orderBy('description' ,'asc')->where('status',1)->where('type',"COMBO")->get();
 
 
        return view('admin.combos.index',compact('combos'));
    }
 
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
         $segments     = Segment::on(Auth::user()->database_name)->orderBY('description','asc')->pluck('description','id')->toArray();
       
         $subsegments  = Subsegment::on(Auth::user()->database_name)->orderBY('description','asc')->get();
      
         $unitofmeasures   = UnitOfMeasure::on(Auth::user()->database_name)->orderBY('description','asc')->get();
 
         return view('admin.combos.create',compact('segments','subsegments','unitofmeasures'));
    }

    public function create_assign($id_combo)
    {
        $combo = Product::on(Auth::user()->database_name)->find($id_combo);

        if(isset($combo) && $combo->type == "COMBO"){

            $products = Product::on(Auth::user()->database_name)->orderBy('description' ,'asc')->where('type','not like','COMBO')->where('type','not like','SERVICIO')->get();

            $combo_products = ComboProduct::on(Auth::user()->database_name)->where('id_combo',$id_combo)->get();
            
            return view('admin.combos.selectproduct',compact('products','id_combo','combo_products'));
        }else{
            return redirect('combos')->withDanger('Debe seleccionar un Combo!');
        }

        
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
             
         
             'segment'         =>'required',
             'unit_of_measure_id'         =>'required',
 
             'description'         =>'required',
         
             'price'         =>'required',
             'price_buy'         =>'required',
             'cost_average'         =>'required',
 
             'money'         =>'required',
         
             'special_impuesto'         =>'required',
             
         
         ]);
 
        
         $var = new Product();
         $var->setConnection(Auth::user()->database_name);
 
         $var->segment_id = request('segment');
         $var->subsegment_id= request('Subsegment');
         $var->unit_of_measure_id = request('unit_of_measure_id');
         $var->code_comercial = request('code_comercial');
         $var->type = "COMBO";
         $var->description = request('description');
 
         $var->twosubsegment_id= request('twoSubsegment');
         $var->threesubsegment_id= request('threeSubsegment');
 
         $var->id_user = request('id_user');
 
         $valor_sin_formato_price = str_replace(',', '.', str_replace('.', '',request('price')));
         $valor_sin_formato_price_buy = str_replace(',', '.', str_replace('.', '',request('price_buy')));
         $valor_sin_formato_cost_average = str_replace(',', '.', str_replace('.', '',request('cost_average')));
         $valor_sin_formato_special_impuesto = str_replace(',', '.', str_replace('.', '',request('special_impuesto')));
         
 
 
         $var->price = $valor_sin_formato_price;
         $var->price_buy = $valor_sin_formato_price_buy;
         $var->cost_average = $valor_sin_formato_cost_average;
         $var->money = request('money');
         $var->photo_product = request('photo_product');
 
         $exento = request('exento');
         if($exento == null){
             $var->exento = false;
         }else{
             $var->exento = true;
         }
         
         $islr = request('islr');
         if($islr == null){
             $var->islr = false;
         }else{
             $var->islr = true;
         }
 
         $var->special_impuesto = $valor_sin_formato_special_impuesto;
         $var->status =  1;
     
         $var->save();
 
         $inventory = new Inventory();
         $inventory->setConnection(Auth::user()->database_name);
 
         $inventory->product_id = $var->id;
         $inventory->id_user = $var->id_user;
         $inventory->code = $var->code_comercial;
         $inventory->amount = 0;
         $inventory->status = 1;
 
         $inventory->save();
 
         return redirect('combos/assign/'.$var->id.'')->withSuccess('Registro del Combo Exitosamente!');
     }

     public function store_assign(Request $request)
     {
        if(isset($request->combo_products) || isset($request->id_products)){
            $array = $request->all();
           
            $amounts = collect();
            
            $count = 0;
            //dd($request);
            foreach ($array as $key => $item) {
                
                if(isset($item)){
                    if(substr($key,0, 6) == 'amount'){
                        $collection = collect();
                        $collection->id = substr($key,6);
                        $collection->amount = str_replace(',', '.', str_replace('.', '', $item));
                        $amounts->push($collection);
                    }
                }
            }
            
            //convierte a array
            $id_products = explode(",", $request->id_products);
            
            if(isset($request->combo_products)){
                
                $id_combos = explode(",", $request->combo_products);

                $diferencias = array_diff($id_products,$id_combos);
                
                if(empty($diferencias) || (isset($diferencias[0]) && $diferencias[0] == "")){
                    $diferencias = array_diff($id_combos,$id_products);
                }
                
                if(count($diferencias) > 0){
                    foreach($diferencias as $diferencia){
                        $combo_exist = ComboProduct::on(Auth::user()->database_name)->where('id_combo',$request->id_combo)->where('id_product',$diferencia)->first();
                        
                        if(isset($combo_exist)){
                            ComboProduct::on(Auth::user()->database_name)->where('id_combo',$request->id_combo)->where('id_product',$diferencia)->delete();
                        }else{
                            $var = new ComboProduct();
                            $var->setConnection(Auth::user()->database_name);
                            $var->id_combo = $request->id_combo;
                            $var->id_product = $diferencia;

                            foreach($amounts as $amount){
                                if($amount->id == $var->id_product){
                                    $var->amount_per_product = $amount->amount;
                                }
                            }

                            $var->save();
                        }
                    }
                }
                //Revisar si todas los montos estan actualizados, sino actualizar
                foreach($amounts as $amount){
                    $combo_actual = ComboProduct::on(Auth::user()->database_name)->where('id_combo',$request->id_combo)->where('id_product',$amount->id)->first();
                    if(isset($combo_actual)){
                        if($combo_actual->amount_per_product != $amount->amount){
                            ComboProduct::on(Auth::user()->database_name)->where('id_combo',$request->id_combo)
                            ->where('id_product',$amount->id)->update(['amount_per_product' => $amount->amount]);
                        }
                    }
                }
            }else{
                
                foreach($id_products as $id_product){
                    $var = new ComboProduct();
                    $var->setConnection(Auth::user()->database_name);
                    $var->id_combo = $request->id_combo;
                    $var->id_product = $id_product;
                    
                    foreach($amounts as $amount){
                        if($amount->id == $var->id_product){
                            $var->amount_per_product = $amount->amount;
                        }
                    }
                    $var->save();
                }
                
            }

        }else{
            return redirect('combos');
        }
        
        
        return redirect('combos')->withSuccess('Registro del Combo Exitosamente!');
    }

     public function edit($id)
     {
          $combo = Product::on(Auth::user()->database_name)->find($id);
          $segments     = Segment::on(Auth::user()->database_name)->orderBY('description','asc')->get();
         
          $subsegments  = Subsegment::on(Auth::user()->database_name)->orderBY('description','asc')->get();
  
          $twosubsegments  = TwoSubsegment::on(Auth::user()->database_name)->where('subsegment_id',$combo->subsegment_id)->orderBY('description','asc')->get();
       
          $threesubsegments  = ThreeSubsegment::on(Auth::user()->database_name)->where('twosubsegment_id',$combo->twosubsegment_id)->orderBY('description','asc')->get();
       
          $unitofmeasures   = UnitOfMeasure::on(Auth::user()->database_name)->orderBY('description','asc')->get();
  
          //dd($product->subsegment_id);
         
          return view('admin.combos.edit',compact('threesubsegments','twosubsegments','combo','segments','subsegments','unitofmeasures'));
    
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
  
      $vars =  Product::on(Auth::user()->database_name)->find($id);
  
      $vars_status = $vars->status;
      $vars_exento = $vars->exento;
      $vars_islr = $vars->islr;
    
      $data = request()->validate([
          
         
          'segment'             =>'required',
          'unit_of_measure_id'  =>'required',
          'description'         =>'required',
          'price'               =>'required',
          'price_buy'           =>'required',
          'cost_average'        =>'required',
          'money'               =>'required',
          'special_impuesto'    =>'required',
          'status'              =>'required',
         
      ]);
  
      $var = Product::on(Auth::user()->database_name)->findOrFail($id);
  
      $var->segment_id = request('segment');
      $var->subsegment_id= request('Subsegment');
      if(request('twoSubsegment') == 'null'){
          $var->twosubsegment_id= null;
      }else{
          $var->twosubsegment_id= request('twoSubsegment');
      }
  
      if(request('threeSubsegment') == 'null'){
          $var->threesubsegment_id= null;
      }else{
          $var->threesubsegment_id= request('threeSubsegment');
      }
      
      
      $var->unit_of_measure_id = request('unit_of_measure_id');
  
      $var->code_comercial = request('code_comercial');
      $var->description = request('description');
  
      $valor_sin_formato_price = str_replace(',', '.', str_replace('.', '',request('price')));
      $valor_sin_formato_price_buy = str_replace(',', '.', str_replace('.', '',request('price_buy')));
      $valor_sin_formato_cost_average = str_replace(',', '.', str_replace('.', '',request('cost_average')));
      $valor_sin_formato_special_impuesto = str_replace(',', '.', str_replace('.', '',request('special_impuesto')));
         
  
  
      $var->price = $valor_sin_formato_price;
      $var->price_buy = $valor_sin_formato_price_buy;
      $var->cost_average = $valor_sin_formato_cost_average;
      
      $var->photo_product = request('photo_product');
  
      $var->money = request('money');
  
  
      $var->special_impuesto = $valor_sin_formato_special_impuesto;
  
      if(request('exento') == null){
          $var->exento = "0";
      }else{
          $var->exento = "1";
      }
      if(request('islr') == null){
          $var->islr = "0";
      }else{
          $var->islr = "1";
      }
     
  
      if(request('status') == null){
          $var->status = $vars_status;
      }else{
          $var->status = request('status');
      }
     
      $var->save();
  
      return redirect('/products')->withSuccess('Actualizacion Exitosa!');
      }
  
  
     /**
      * Remove the specified resource from storage.
      *
      * @param  int  $id
      * @return \Illuminate\Http\Response
      */
     public function destroy(Request $request)
     {
        
          $product = Product::on(Auth::user()->database_name)->find(request('id_combo_modal')); 
  
          if(isset($product)){
              
              Inventory::on(Auth::user()->database_name)
                              ->where('product_id',$product->id)
                              ->update(['status' => 'X']);
  
              $product->status = 'X';
  
              $product->save();
      
              return redirect('combos')->withSuccess('Se ha Deshabilitado el Combo Correctamente!!');
          }
     }
}
