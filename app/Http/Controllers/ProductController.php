<?php

namespace App\Http\Controllers;

use App\Inventory;
use App\Product;
use App\ProductPrice;
use App\Segment;
use App\Subsegment;
use App\ThreeSubsegment;
use App\TwoSubsegment;
use App\UnitOfMeasure;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{

    public function __construct(){

       $this->middleware('auth');

   }

    public function listprice(Request $request, $code_id = null){

        //validar si la peticion es asincrona
        if($request->ajax()){
            try{
                $productprice = ProductPrice::select('id','price')->where('id_product',$code_id)->orderBy('price','asc')->get();
                return response()->json($productprice,200);
            }catch(Throwable $th){
                return response()->json(false,500);
            }
        }

    }

   public function index()
   {
       $user           = auth()->user();
       $users_role =   $user->role_id;

        $products = Product::on(Auth::user()->database_name)->orderBy('id' ,'DESC')->where('status',1)->get();


       return view('admin.products.index',compact('products'));
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

        return view('admin.products.create',compact('segments','subsegments','unitofmeasures'));
   }

   /**
    * Store a newly created resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
   public function store(Request $request)
    {

        $user           = auth()->user();
        $segmento       = request('Segmento');
        $sub_segmento   = request('Sub_Segmento');
        $codigo         = $segmento.$sub_segmento;

        $users_id       = $user->id;
        $exento         = request('exento');
        $contador = Product::on(Auth::user()->database_name)->where('sku', 'LIKE', "%$codigo%")->get()->last();
        if($contador == null){
            $sku = '1';
        }else{
            $sku_subr       = substr($contador->sku,2);
            $sku            = $sku_subr+"1";
        }
        $serial         = $codigo.str_pad($sku, 3, "0", STR_PAD_LEFT);

        $code = request('Codigo_Comercial');
        if($code == null){
            $code = $serial;
        }
        $valor_sin_formato_price            =   trim(str_replace(',', '.', str_replace('.', '',request('Precio')))) ;
        $valor_sin_formato_price_buy        =   trim(str_replace(',', '.', str_replace('.', '',request('Precio_Compra'))));
        $valor_sin_formato_cost_average     =   trim(str_replace(',', '.', str_replace('.', '',request('Costo_Promedio'))));
        $valor_sin_formato_special_impuesto =   trim(str_replace(',', '.', str_replace('.', '',request('Impuesto_Especial'))));
        $valor_sin_formato_degree           = trim(str_replace(',', '.', str_replace('.', '',request('Grado'))));
        $valor_sin_formato_liter            = trim(str_replace(',', '.', str_replace('.', '',request('Litros'))));
        $valor_sin_formato_capacity         = trim(str_replace(',', '.', str_replace('.', '',request('Capacidad'))));

        $var = new Product();
        $var->setConnection(Auth::user()->database_name);
        $var->id_user           = $users_id;
        $var->segment_id        = request('Segmento');
        $var->subsegment_id     = request('Sub_Segmento');
        $var->unit_of_measure_id = request('Unidad_Medida');
        $var->code_comercial    = $code;
        $var->type              = request('Tipo');
        $var->description       = request('Descripcion');
        $var->twosubsegment_id  = request('twoSubsegment');
        $var->threesubsegment_id= request('threeSubsegment');
        $var->price             = $valor_sin_formato_price;
        $var->price_buy         = $valor_sin_formato_price_buy;
        $var->cost_average      = $valor_sin_formato_cost_average;
        $var->money             = request('Moneda');
        $var->photo_product     = request('photo_product');
        $var->sku               = $serial;

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

        $var->box               = request('Cajas');
        $var->degree            = $valor_sin_formato_degree;
        $var->bottle            = request('Botellas');
        $var->liter             = $valor_sin_formato_liter;
        $var->capacity          = $valor_sin_formato_capacity;
        $var->special_impuesto  = $valor_sin_formato_special_impuesto;
        $var->status            =  1;
        $var->save();

        $inventory              = new Inventory();
        $inventory->setConnection(Auth::user()->database_name);
        $inventory->product_id  = $var->id;
        $inventory->code     = $code;
        $inventory->id_user     = $var->id_user;
        $inventory->amount      = 0;
        $inventory->status      = 1;
        $inventory->save();

        return redirect('/products')->withSuccess('Registro Exitoso!');
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
        $product = Product::on(Auth::user()->database_name)->find($id);
        $segments     = Segment::on(Auth::user()->database_name)->orderBY('description','asc')->get();
        $subsegments  = Subsegment::on(Auth::user()->database_name)->orderBY('description','asc')->get();
        $twosubsegments  = TwoSubsegment::on(Auth::user()->database_name)->where('subsegment_id',$product->subsegment_id)->orderBY('description','asc')->get();
        $threesubsegments  = ThreeSubsegment::on(Auth::user()->database_name)->where('twosubsegment_id',$product->twosubsegment_id)->orderBY('description','asc')->get();
        $unitofmeasures   = UnitOfMeasure::on(Auth::user()->database_name)->orderBY('description','asc')->get();
        //dd($product->subsegment_id);

        return view('admin.products.edit',compact('threesubsegments','twosubsegments','product','segments','subsegments','unitofmeasures'));

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
       $vars_status    = $vars->status;
       $vars_exento    = $vars->exento;
       $vars_islr      = $vars->islr;
       $segmento       = request('Segmento');
       $sub_segmento   = request('Sub_Segmento');
       $codigo         = $segmento.$sub_segmento;
       $sku_value      = substr($vars->sku,0,2);
       $contador        = Product::where('sku', 'LIKE', "%$codigo%")->get()->last();

       if($contador == null){
           $sku = '1';
       }elseif($sku_value == $codigo ){
           $sku = substr($vars->sku,2,3);
       }else{
           $sku_subr       = substr($contador->sku,2);
           $sku            = $sku_subr+"1";
       }

       $serial = $codigo.str_pad($sku, 3, "0", STR_PAD_LEFT);
       if($serial <> $vars->sku){
           $serial = $codigo.str_pad($sku, 3, "0", STR_PAD_LEFT);
       }else{
           $serial = $vars->sku;
       }

       $code = request('Codigo_Comercial');
       if($code == null){
           $code = $serial;
       }elseif($code == $vars->sku ){
           $code = $serial;
       }else{
           $code = request('Codigo_Comercial');
       }
       $valor_sin_formato_price            =   trim(str_replace(',', '.', str_replace('.', '',request('Precio')))) ;
       $valor_sin_formato_price_buy        =   trim(str_replace(',', '.', str_replace('.', '',request('Precio_Compra'))));
       $valor_sin_formato_cost_average     =   trim(str_replace(',', '.', str_replace('.', '',request('Costo_Promedio'))));
       $valor_sin_formato_special_impuesto =   trim(str_replace(',', '.', str_replace('.', '',request('Impuesto_Especial'))));
       $valor_sin_formato_degree           = trim(str_replace(',', '.', str_replace('.', '',request('Grado'))));
       $valor_sin_formato_liter            = trim(str_replace(',', '.', str_replace('.', '',request('Litros'))));
       $valor_sin_formato_capacity         = trim(str_replace(',', '.',request('Capacidad')));

       $var = Product::on(Auth::user()->database_name)->findOrFail($id);
       $var->segment_id = request('Segmento');
       $var->subsegment_id= request('Sub_Segmento');
       $var->unit_of_measure_id = request('Unidad_Medida');
       $var->code_comercial = $code;
       $var->sku = $serial;
       $var->type = request('Tipo');
       $var->description = request('Descripcion');
       $var->box               = request('Cajas');
       $var->degree            = $valor_sin_formato_degree;
       $var->bottle            = request('Botellas');
       $var->liter             = $valor_sin_formato_liter;
       $var->capacity          = $valor_sin_formato_capacity;
       $var->price             = $valor_sin_formato_price;
       $var->price_buy         = $valor_sin_formato_price_buy;
       $var->cost_average      = $valor_sin_formato_cost_average;
       $var->photo_product = request('photo_product');
       $var->money = request('Moneda');
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
     * View the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function productprice($id)
    {

        $user       =   auth()->user();
        $users_role =   $user->role_id;
        if($users_role == '1'){
            $product_detail        =   Product::on(Auth::user()->database_name)->find($id);

            $products = ProductPrice::orderBy('id' ,'DESC')->where("id_product",$product_detail->id)->get();
        }elseif($users_role == '2'){
            return view('admin.index');
        }
        return view('admin.products.indexproduct',compact('products','product_detail'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createproduct($id)
    {
        $user       =   auth()->user();
        $users_role =   $user->role_id;
        if($users_role == '1'){
            $product_detail        =   Product::on(Auth::user()->database_name)->find($id);
        }elseif($users_role == '2'){
            return view('admin.index');
        }
        return view('admin.products.createproduct',compact('product_detail'));
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function storeproduct(Request $request)
    {

        $id     = request('id_product');
        $consul     = ProductPrice::on(Auth::user()->database_name)->where('id_product',$id)->get()->count();
        if($consul == 6){
            return \redirect()->route('products.productprice',$id)->withDelete('Ya el producto tiene los 6 precios.!');;
        }else{
            $valor_sin_formato_precio=   trim(str_replace(',', '.', str_replace('.', '',request('Precio'))));

            $var = new ProductPrice();
            $var->id_product        = $id;
            $var->price             = $valor_sin_formato_precio;
            $var->status            =  1;
            $var->save();

            return \redirect()->route('products.productprice',$id);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function editproduct($id)
    {
        $product            = ProductPrice::on(Auth::user()->database_name)->find($id);
        $product_detail     = Product::where('id',$product->id_product)->get()->first();
        return view('admin.products.editproduct',compact('product','product_detail'));

    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateproduct(Request $request, $id)
    {
        $valor_sin_formato_price            =   trim(str_replace(',', '.', str_replace('.', '',request('Precio')))) ;
        $var = ProductPrice::on(Auth::user()->database_name)->findOrFail($id);
        $var->price = $valor_sin_formato_price;

        $var->save();

        return \redirect()->route('products')->withSuccess('Actualizacion Exitosa!');
    }

   /**
    * Remove the specified resource from storage.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
   public function destroy()
   {
        $product = Product::on(Auth::user()->database_name)->find(request('id_product_modal'));

        if(isset($product)){

            Inventory::on(Auth::user()->database_name)
                            ->where('product_id',$product->id)
                            ->update(['status' => 'X']);

            $product->status = 'X';

            $product->save();

            return redirect('/products')->withSuccess('Se ha Deshabilitado el Producto Correctamente!!');
        }
   }


}
