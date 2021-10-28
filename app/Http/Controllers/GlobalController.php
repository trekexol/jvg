<?php

namespace App\Http\Controllers;

use App\Anticipo;
use App\ComboProduct;
use App\Inventory;
use App\QuotationPayment;
use App\QuotationProduct;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class GlobalController extends Controller
{
    public function procesar_anticipos($quotation,$total_pay)
    {
        
        if($total_pay >= 0){
            $anticipos_old = DB::connection(Auth::user()->database_name)->table('anticipos')
                                ->where('id_client', '=', $quotation->id_client)
                                ->where(function ($query) use ($quotation){
                                    $query->where('id_quotation',null)
                                        ->orWhere('id_quotation',$quotation->id);
                                })
                                ->where('status', '=', '1')->get();

            foreach($anticipos_old as $anticipo){
                DB::connection(Auth::user()->database_name)->table('anticipo_quotations')->insert(['id_quotation' => $quotation->id,'id_anticipo' => $anticipo->id]);
            } 


            /*Verificamos si el cliente tiene anticipos activos */
            DB::connection(Auth::user()->database_name)->table('anticipos')
                    ->where('id_client', '=', $quotation->id_client)
                    ->where(function ($query) use ($quotation){
                        $query->where('id_quotation',null)
                            ->orWhere('id_quotation',$quotation->id);
                    })
                    ->where('status', '=', '1')
                    ->update(['status' => 'C']);

            //los que quedaron en espera, pasan a estar activos
            DB::connection(Auth::user()->database_name)->table('anticipos')->where('id_client', '=', $quotation->id_client)
            ->where(function ($query) use ($quotation){
                $query->where('id_quotation',null)
                    ->orWhere('id_quotation',$quotation->id);
            })
            ->where('status', '=', 'M')
            ->update(['status' => '1']);
        }
    }

    public function check_anticipo($quotation,$total_pay)
    {
        
            $anticipos = DB::connection(Auth::user()->database_name)->table('anticipos')->where('id_client', '=', $quotation->id_client)
                                                                                    ->where(function ($query) use ($quotation){
                                                                                        $query->where('id_quotation',null)
                                                                                            ->orWhere('id_quotation',$quotation->id);
                                                                                    })
                                                                                    ->where('status', '=', '1')->get();

            foreach($anticipos as $anticipo){

                //si el anticipo esta en dolares, multiplico los dolares por la tasa de la cotizacion, para sacar el monto real en bolivares
                if($anticipo->coin != "bolivares"){
                    $anticipo->amount = ($anticipo->amount / $anticipo->rate) * $quotation->bcv;
                }

                if($total_pay >= $anticipo->amount){
                    DB::connection(Auth::user()->database_name)->table('anticipos')
                                                                ->where('id', $anticipo->id)
                                                                ->update(['status' => 'C']);
                   
                    DB::connection(Auth::user()->database_name)->table('anticipo_quotations')->insert(['id_quotation' => $quotation->id,'id_anticipo' => $anticipo->id]);
                                                         
                    $total_pay -= $anticipo->amount;
                }else{

                    DB::connection(Auth::user()->database_name)->table('anticipos')
                                                                ->where('id', $anticipo->id)
                                                                ->update(['status' => 'C']);
                                                    
                    DB::connection(Auth::user()->database_name)->table('anticipo_quotations')->insert(['id_quotation' => $quotation->id,'id_anticipo' => $anticipo->id]);
                      

                    $amount_anticipo_new = $anticipo->amount - $total_pay;

                    $var = new Anticipo();
                    $var->setConnection(Auth::user()->database_name);
                    
                    $var->date = $quotation->date_billing;
                    $var->id_client = $quotation->id_client;
                    $user       =   auth()->user();
                    $var->id_user = $user->id;
                    $var->id_account = $anticipo->id_account;
                    $var->coin = $anticipo->coin;
                    $var->amount = $amount_anticipo_new;
                    $var->rate = $quotation->bcv;
                    $var->reference = $anticipo->reference;
                    $var->status = 1;
                    $var->save();
                    break;
                }
            }

            
    }

    public function check_anticipo_multipayment($quotation,$quotations_id,$total_pay)
    {
        
            $anticipos = DB::connection(Auth::user()->database_name)->table('anticipos')->where('id_client', '=', $quotation->id_client)
                                                                                    ->where(function ($query) use ($quotations_id){
                                                                                        $query->where('id_quotation',null)
                                                                                            ->orWhereIn('id_quotation', $quotations_id);
                                                                                    })
                                                                                    ->where('status', '=', '1')->get();
            

            foreach($anticipos as $anticipo){

                //si el anticipo esta en dolares, multiplico los dolares por la tasa de la cotizacion, para sacar el monto real en bolivares
                if($anticipo->coin != "bolivares"){
                    $anticipo->amount = ($anticipo->amount / $anticipo->rate) * $quotation->bcv;
                }

                if($total_pay >= $anticipo->amount){
                    DB::connection(Auth::user()->database_name)->table('anticipos')
                                                                ->where('id', $anticipo->id)
                                                                ->update(['status' => 'C']);
                   
                    DB::connection(Auth::user()->database_name)->table('anticipo_quotations')->insert(['id_quotation' => $quotation->id,'id_anticipo' => $anticipo->id]);
                                                         
                    $total_pay -= $anticipo->amount;
                }else{

                    DB::connection(Auth::user()->database_name)->table('anticipos')
                                                                ->where('id', $anticipo->id)
                                                                ->update(['status' => 'C']);
                                                    
                    DB::connection(Auth::user()->database_name)->table('anticipo_quotations')->insert(['id_quotation' => $quotation->id,'id_anticipo' => $anticipo->id]);
                      

                    $amount_anticipo_new = $anticipo->amount - $total_pay;

                    $var = new Anticipo();
                    $var->setConnection(Auth::user()->database_name);
                    
                    $var->date = $quotation->date_billing;
                    $var->id_client = $quotation->id_client;
                    $user       =   auth()->user();
                    $var->id_user = $user->id;
                    $var->id_account = $anticipo->id_account;
                    $var->coin = $anticipo->coin;
                    $var->amount = $amount_anticipo_new;
                    $var->rate = $quotation->bcv;
                    $var->reference = $anticipo->reference;
                    $var->status = 1;
                    $var->save();
                    break;
                }
            }

            
    }

    public function discount_inventory($id_quotation)
    {
            /*Primero Revisa que todos los productos tengan inventario suficiente*/
            $no_hay_cantidad_suficiente = DB::connection(Auth::user()->database_name)->table('inventories')
                                    ->join('quotation_products', 'quotation_products.id_inventory','=','inventories.id')
                                    ->join('products', 'products.id','=','inventories.product_id')
                                    ->where('quotation_products.id_quotation','=',$id_quotation)
                                    ->where('quotation_products.amount','<','inventories.amount')
                                    ->where('quotation_products.status','1')
                                    ->where(function ($query){
                                        $query->where('products.type','MERCANCIA')
                                            ->orWhere('products.type','COMBO');
                                    })
                                    ->select('inventories.code as code','quotation_products.id_quotation as id_quotation','quotation_products.discount as discount',
                                    'quotation_products.amount as amount_quotation')
                                    ->first(); 
        
            if(isset($no_hay_cantidad_suficiente)){
                return "no_hay_cantidad_suficiente";
            }

            /*Luego, descuenta del Inventario*/
            $inventories_quotations = DB::connection(Auth::user()->database_name)->table('products')->join('inventories', 'products.id', '=', 'inventories.product_id')
            ->join('quotation_products', 'inventories.id', '=', 'quotation_products.id_inventory')
            ->where('quotation_products.id_quotation',$id_quotation)
            ->where('quotation_products.status','1')
            ->select('products.*','quotation_products.id as id_quotation','quotation_products.discount as discount',
            'quotation_products.amount as amount_quotation')
            ->get(); 

            foreach($inventories_quotations as $inventories_quotation){

                $quotation_product = QuotationProduct::on(Auth::user()->database_name)->findOrFail($inventories_quotation->id_quotation);

                if(isset($quotation_product)){
                    if(($inventories_quotation->type == 'MERCANCIA') || ($inventories_quotation->type == 'COMBO')){
                        $inventory = Inventory::on(Auth::user()->database_name)->findOrFail($quotation_product->id_inventory);

                        if(isset($inventory)){
                            //REVISO QUE SEA MAYOR EL MONTO DEL INVENTARIO Y LUEGO DESCUENTO
                            if($inventory->amount >= $quotation_product->amount){
                                $inventory->amount -= $quotation_product->amount;
                                $inventory->save();
                                if($inventories_quotation->type == 'COMBO'){
                                    $global = new GlobalController;
                                    $global->discountCombo($inventory,$quotation_product->amount);
                                }
                            }else{
                                return 'El Inventario de Codigo: '.$inventory->code.' no tiene Cantidad suficiente!';
                            }
                        }else{
                            return 'El Inventario no existe!';
                        }
                    }
                    //CAMBIAMOS EL ESTADO PARA SABER QUE ESE PRODUCTO YA SE COBRO Y SE RESTO DEL INVENTARIO
                    $quotation_product->status = 'C';  
                    $quotation_product->save();
                }else{
                return 'El Inventario de la cotizacion no existe!';
                }

            }

            return "exito";

    }

    public function add_payment($quotation,$id_account,$payment_type,$amount,$bcv){
        $var = new QuotationPayment();
        $var->setConnection(Auth::user()->database_name);

        $var->id_quotation = $quotation->id;
        $var->id_account = $id_account;
   
        $var->payment_type = $payment_type;
        $var->amount = $amount;
        
        
        $var->rate = $bcv;
        
        $var->status =  1;
        $var->save();
        
        return $var->id;
    }

    public function aumentCombo($inventory,$amount_discount)
    {
        $product = ComboProduct::on(Auth::user()->database_name)
                    ->join('products','products.id','combo_products.id_product')
                    ->join('inventories','inventories.product_id','products.id')
                    ->where('combo_products.id_combo',$inventory->product_id)
                    ->update(['inventories.amount' => DB::raw('inventories.amount - (combo_products.amount_per_product *'.$amount_discount.')')]);


    }

    public function discountCombo($inventory,$amount_discount)
    {
        $product = ComboProduct::on(Auth::user()->database_name)
                    ->join('products','products.id','combo_products.id_product')
                    ->join('inventories','inventories.product_id','products.id')
                    ->where('combo_products.id_combo',$inventory->product_id)
                    ->update(['inventories.amount' => DB::raw('inventories.amount + (combo_products.amount_per_product *'.$amount_discount.')')]);


    }
}
