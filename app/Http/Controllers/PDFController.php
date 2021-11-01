<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App;
use App\Account;
use App\AccountHistorial;
use App\Client;
use App\Company;
use App\ExpensePayment;
use App\ExpensesAndPurchase;
use App\ExpensesDetail;
use App\Inventory;
use App\Quotation;
use App\QuotationPayment;
use App\QuotationProduct;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PDFController extends Controller
{

    function imprimirFactura($id_quotation,$coin = null)
    {


        $pdf = App::make('dompdf.wrapper');


             $quotation = null;

             if(isset($id_quotation)){
                 $quotation = Quotation::on(Auth::user()->database_name)->where('date_billing', '<>', null)->find($id_quotation);


             }else{
                return redirect('/invoices')->withDanger('No llega el numero de la factura');
                }

             if(isset($quotation)){

                $payment_quotations = QuotationPayment::on(Auth::user()->database_name)
                                            ->where('id_quotation',$quotation->id)
                                            ->where('status',1)
                                            ->get();

                foreach($payment_quotations as $var){
                    $var->payment_type = $this->asignar_payment_type($var->payment_type);
                    if($coin == 'dolares'){
                        $var->amount = $var->amount / $var->rate;
                    }
                }


                 $inventories_quotations = DB::connection(Auth::user()->database_name)->table('products')->join('inventories', 'products.id', '=', 'inventories.product_id')
                                                                ->join('quotation_products', 'inventories.id', '=', 'quotation_products.id_inventory')
                                                                ->where('quotation_products.id_quotation',$quotation->id)
                                                                ->where('quotation_products.status','C')
                                                                ->select('products.*','quotation_products.price as price','quotation_products.rate as rate','quotation_products.discount as discount',
                                                                'quotation_products.amount as amount_quotation','quotation_products.retiene_iva as retiene_iva_quotation'
                                                                ,'quotation_products.retiene_islr as retiene_islr_quotation')
                                                                ->get();
                                                                    $inventories_quotationss = DB::table('products')->join('inventories', 'products.id', '=', 'inventories.product_id')
                ->join('quotation_products', 'inventories.id', '=', 'quotation_products.id_inventory')
                ->where('quotation_products.id_quotation',$quotation->id)
                ->select('products.*','quotation_products.price as price','quotation_products.rate as rate','quotation_products.discount as discount',
                    'quotation_products.amount as amount_quotation','quotation_products.retiene_iva as retiene_iva_quotation'
                    ,'quotation_products.retiene_islr as retiene_islr_quotation','quotation_products.retiene_iva as retiene_iva_quotation')
                ->get();

            $date = Carbon::now();
            $datenow = $date->format('Y-m-d');

            $company = Company::on(Auth::user()->database_name)->find(1);
            $tax_1   = $company->tax_1;

            //Si la taza es automatica
            if($company->tiporate_id == 1){
                //esto es para que siempre se pueda guardar la tasa en la base de datos
                $bcv_quotation_product = $this->search_bcv();
                $bcv = $this->search_bcv();
            }else{
                //si la tasa es fija
                $bcv_quotation_product = $company->rate;
                $bcv = $company->rate;

            }

            if(($coin == 'bolivares') ){

                $coin = 'bolivares';
            }else{
                //$bcv = null;

                $coin = 'dolares';
            }

            $total= 0;
            $base_imponible= 0;
            $price_cost_total= 0;

            //este es el total que se usa para guardar el monto de todos los productos que estan exentos de iva, osea retienen iva
            $total_retiene_iva     = 0;
            $retiene_iva           = 0;
            $total_retiene_islr    = 0;
            $total_retiene         = 0;
            $total_iva             = 0;
            $total_base_impo_pcb   = 0;
            $total_iva_pcb         = 0;
            $total_venta           = 0;
            $retiene_islr          = 0;
            $variable_total        = 0;
            $base_imponible_pcb    = 15;
            $iva                   = 16;
            $rate                  = $quotation->bcv;


            foreach($inventories_quotationss as $vars){

                //Se calcula restandole el porcentaje de descuento (discount)
                $percentage = (($vars->price * $vars->amount_quotation) * $vars->discount)/100;
                $total += ($vars->price * $vars->amount_quotation) - $percentage;


                if( $vars->retiene_iva_quotation == 1 ){
                    $total_retiene         = 0;
                    $total_base_impo_pcb   = 0;
                    $total_iva_pcb         = 0;
                    $total_iva             = 0;
                    $total_venta        += $vars->price * $vars->amount_quotation ;

                }else{

                    $total_retiene         +=  ($vars->price * $vars->amount_quotation) - $percentage;
                    $base_imponible        += $total_retiene;
                    $total_iva             =  $total_retiene * ($iva / 100) ;
                    $total_base_impo_pcb   =  $total_retiene *($base_imponible_pcb /100) ;
                    $total_iva_pcb         =  $total_base_impo_pcb * ($iva /100);
                    $total_venta           =    $total_retiene + $total_iva + $total_iva_pcb;
                }

            }


            $newVenc = date("d/m/Y", strtotime($quotation->date_expiration));

            if(empty($newVenc)){
                $newVenc ="";
            }



                if($coin == 'bolivares'){
                    $bcv = null;

                }else{
                    $bcv = $quotation->bcv;
                }

                $company = Company::on(Auth::user()->database_name)->find(1);

                 $pdf = $pdf->loadView('pdf.factura',compact('quotation','inventories_quotations','datenow','bcv','coin','bcv_quotation_product','total','rate','total_retiene','total_iva','total_base_impo_pcb','total_iva_pcb','total_venta','iva','newVenc','base_imponible'))->setPaper('a4', 'landscape');
                 return $pdf->stream();

                }else{
                 return redirect('/invoices')->withDanger('La factura no existe');
             }

    }


    function previewfactura($id_quotation,$coin = null){

        $quotation = null;

        if(isset($id_quotation)){
            $quotation = Quotation::on(Auth::user()->database_name)->find($id_quotation);
        }

        if(isset($quotation)){
            //$inventories_quotations = QuotationProduct::where('id_quotation',$quotation->id)->get();

            $inventories_quotations = DB::table('products')->join('inventories', 'products.id', '=', 'inventories.product_id')
                ->join('quotation_products', 'inventories.id', '=', 'quotation_products.id_inventory')
                ->where('quotation_products.id_quotation',$quotation->id)
                ->select('products.*','quotation_products.price as price','quotation_products.rate as rate','quotation_products.discount as discount',
                    'quotation_products.amount as amount_quotation','quotation_products.retiene_iva as retiene_iva_quotation'
                    ,'quotation_products.retiene_islr as retiene_islr_quotation','quotation_products.retiene_iva as retiene_iva_quotation')
                ->get();

            $inventories_quotationss = DB::table('products')->join('inventories', 'products.id', '=', 'inventories.product_id')
                ->join('quotation_products', 'inventories.id', '=', 'quotation_products.id_inventory')
                ->where('quotation_products.id_quotation',$quotation->id)
                ->select('products.*','quotation_products.price as price','quotation_products.rate as rate','quotation_products.discount as discount',
                    'quotation_products.amount as amount_quotation','quotation_products.retiene_iva as retiene_iva_quotation'
                    ,'quotation_products.retiene_islr as retiene_islr_quotation','quotation_products.retiene_iva as retiene_iva_quotation')
                ->get();

            $date = Carbon::now();
            $datenow = $date->format('Y-m-d');

            $company = Company::on(Auth::user()->database_name)->find(1);
            $tax_1   = $company->tax_1;

            //Si la taza es automatica
            if($company->tiporate_id == 1){
                //esto es para que siempre se pueda guardar la tasa en la base de datos
                $bcv_quotation_product = $this->search_bcv();
                $bcv = $this->search_bcv();
            }else{
                //si la tasa es fija
                $bcv_quotation_product = $company->rate;
                $bcv = $company->rate;

            }

            if(($coin == 'bolivares') ){

                $coin = 'bolivares';
            }else{
                //$bcv = null;

                $coin = 'dolares';
            }

            $total= 0;
            $base_imponible= 0;
            $price_cost_total= 0;

            //este es el total que se usa para guardar el monto de todos los productos que estan exentos de iva, osea retienen iva
            $total_retiene_iva     = 0;
            $retiene_iva           = 0;
            $total_retiene_islr    = 0;
            $total_retiene         = 0;
            $total_iva             = 0;
            $total_base_impo_pcb   = 0;
            $total_iva_pcb         = 0;
            $total_venta           = 0;
            $retiene_islr          = 0;
            $variable_total        = 0;
            $base_imponible_pcb    = 15;
            $iva                   = 16;
            $rate                  = $quotation->bcv;


            foreach($inventories_quotationss as $vars){

                //Se calcula restandole el porcentaje de descuento (discount)
                $percentage = (($vars->price * $vars->amount_quotation) * $vars->discount)/100;
                $total += ($vars->price * $vars->amount_quotation) - $percentage;


                if( $vars->retiene_iva_quotation == 1 ){
                    $total_retiene         = 0;
                    $total_base_impo_pcb   = 0;
                    $total_iva_pcb         = 0;
                    $total_iva             = 0;
                    $total_venta        += $vars->price * $vars->amount_quotation ;

                }else{

                    $total_retiene         +=  ($vars->price * $vars->amount_quotation) - $percentage;
                    $base_imponible        += $total_retiene;
                    $total_iva             =  $total_retiene * ($iva / 100) ;
                    $total_base_impo_pcb   =  $total_retiene *($base_imponible_pcb /100) ;
                    $total_iva_pcb         =  $total_base_impo_pcb * ($iva /100);
                    $total_venta           =    $total_retiene + $total_iva + $total_iva_pcb;
                }

            }


            $originalDate = $quotation->date_billing;
            $newDate = date("d/m/Y", strtotime($originalDate));

            $newVenc = date("d/m/Y", strtotime($quotation->date_expiration));

            if(empty($newVenc)){
                $newVenc ="";
            }

            $pdf = App::make('dompdf.wrapper');
            $pdf = $pdf->loadView('pdf.previewfactura',compact('quotation','inventories_quotations','datenow','bcv','coin','bcv_quotation_product','total','rate','total_retiene','total_iva','total_base_impo_pcb','total_iva_pcb','total_venta','iva','newDate','base_imponible'))->setPaper('a4', 'landscape');
            return $pdf->stream();

        }else{
            return redirect('/invoices')->withDanger('La factura no existe');
        }
    }




    function previewnote($id_quotation,$coin = null){

        $quotation = null;

        if(isset($id_quotation)){
            $quotation = Quotation::on(Auth::user()->database_name)->find($id_quotation);
        }

        if(isset($quotation)){
            //$inventories_quotations = QuotationProduct::where('id_quotation',$quotation->id)->get();

            $inventories_quotations = DB::table('products')->join('inventories', 'products.id', '=', 'inventories.product_id')
                ->join('quotation_products', 'inventories.id', '=', 'quotation_products.id_inventory')
                ->where('quotation_products.id_quotation',$quotation->id)
                ->select('products.*','quotation_products.price as price','quotation_products.rate as rate','quotation_products.discount as discount',
                    'quotation_products.amount as amount_quotation','quotation_products.retiene_iva as retiene_iva_quotation'
                    ,'quotation_products.retiene_islr as retiene_islr_quotation','quotation_products.retiene_iva as retiene_iva_quotation')
                ->get();

            $inventories_quotationss = DB::table('products')->join('inventories', 'products.id', '=', 'inventories.product_id')
                ->join('quotation_products', 'inventories.id', '=', 'quotation_products.id_inventory')
                ->where('quotation_products.id_quotation',$quotation->id)
                ->select('products.*','quotation_products.price as price','quotation_products.rate as rate','quotation_products.discount as discount',
                    'quotation_products.amount as amount_quotation','quotation_products.retiene_iva as retiene_iva_quotation'
                    ,'quotation_products.retiene_islr as retiene_islr_quotation','quotation_products.retiene_iva as retiene_iva_quotation')
                ->get();

            $date = Carbon::now();
            $datenow = $date->format('Y-m-d');

            $company = Company::on(Auth::user()->database_name)->find(1);
            $tax_1   = $company->tax_1;

            //Si la taza es automatica
            if($company->tiporate_id == 1){
                //esto es para que siempre se pueda guardar la tasa en la base de datos
                $bcv_quotation_product = $this->search_bcv();
                $bcv = $this->search_bcv();
            }else{
                //si la tasa es fija
                $bcv_quotation_product = $company->rate;
                $bcv = $company->rate;

            }

            if(($coin == 'bolivares') ){

                $coin = 'bolivares';
            }else{
                //$bcv = null;

                $coin = 'dolares';
            }

            $total= 0;
            $base_imponible= 0;
            $price_cost_total= 0;

            //este es el total que se usa para guardar el monto de todos los productos que estan exentos de iva, osea retienen iva
            $total_retiene_iva     = 0;
            $retiene_iva           = 0;
            $total_retiene_islr    = 0;
            $total_retiene         = 0;
            $total_iva             = 0;
            $total_base_impo_pcb   = 0;
            $total_iva_pcb         = 0;
            $total_venta           = 0;
            $retiene_islr          = 0;
            $variable_total        = 0;
            $base_imponible_pcb    = 15;
            $iva                   = 12;
            $rate                  = $quotation->bcv;


            foreach($inventories_quotationss as $vars){

                //Se calcula restandole el porcentaje de descuento (discount)
                $percentage = (($vars->price * $vars->amount_quotation) * $vars->discount)/100;
                $total += ($vars->price * $vars->amount_quotation) - $percentage;


                if( $vars->retiene_iva_quotation == 1 ){
                    $total_retiene         = 0;
                    $total_base_impo_pcb   = 0;
                    $total_iva_pcb         = 0;
                    $total_iva             = 0;
                    $total_venta        += $vars->price * $vars->amount_quotation ;

                }else{

                    $total_retiene         +=  ($vars->price * $vars->amount_quotation) - $percentage;
                    $base_imponible        += $total_retiene;
                    $total_iva             =  $total_retiene * ($iva / 100) ;
                    $total_base_impo_pcb   =  $total_retiene *($base_imponible_pcb /100) ;
                    $total_iva_pcb         =  $total_base_impo_pcb * ($iva /100);
                    $total_venta           =    $total_retiene + $total_iva + $total_iva_pcb;
                }

            }

            $originalDate = $quotation->date_billing;
            $newDate = date("d/m/Y", strtotime($originalDate));

            $newVenc = date("d/m/Y", strtotime($quotation->date_expiration));

            if(empty($newVenc)){
                $newVenc ="";
            }

            $pdf = App::make('dompdf.wrapper');
            $pdf = $pdf->loadView('pdf.previewnote',compact('quotation','inventories_quotations','datenow','bcv','coin','bcv_quotation_product','total','rate','total_retiene','total_iva','total_base_impo_pcb','total_iva_pcb','total_venta','iva','newDate','base_imponible'))->setPaper('a4', 'landscape');
            return $pdf->stream();

        }else{
            return redirect('/invoices')->withDanger('La factura no existe');
        }
    }


    function imprimirFactura_media($id_quotation,$coin = null)
    {


        $pdf = App::make('dompdf.wrapper');


             $quotation = null;

             if(isset($id_quotation)){
                 $quotation = Quotation::on(Auth::user()->database_name)->where('date_billing', '<>', null)->find($id_quotation);


             }else{
                return redirect('/invoices')->withDanger('No llega el numero de la factura');
                }

             if(isset($quotation)){

                 $payment_quotations = QuotationPayment::on(Auth::user()->database_name)
                                        ->where('id_quotation',$quotation->id)
                                        ->where('status',1)
                                        ->get();

                 foreach($payment_quotations as $var){
                    $var->payment_type = $this->asignar_payment_type($var->payment_type);
                    if($coin == 'dolares'){
                        $var->amount = $var->amount / $var->rate;
                    }
                 }

                 $inventories_quotations = DB::connection(Auth::user()->database_name)->table('products')->join('inventories', 'products.id', '=', 'inventories.product_id')
                                                                ->join('quotation_products', 'inventories.id', '=', 'quotation_products.id_inventory')
                                                                ->where('quotation_products.id_quotation',$quotation->id)
                                                                ->where('quotation_products.status','C')
                                                                ->select('products.*','quotation_products.price as price','quotation_products.rate as rate','quotation_products.discount as discount',
                                                                'quotation_products.amount as amount_quotation','quotation_products.retiene_iva as retiene_iva_quotation'
                                                                ,'quotation_products.retiene_islr as retiene_islr_quotation')
                                                                ->get();

                 if($coin == 'bolivares'){
                    $bcv = null;

                }else{
                    $bcv = $quotation->bcv;
                }

                $company = Company::on(Auth::user()->database_name)->find(1);

                 $pdf = $pdf->loadView('pdf.factura_media',compact('quotation','inventories_quotations','payment_quotations','bcv','company'));
                 return $pdf->stream();

                }else{
                 return redirect('/invoices')->withDanger('La factura no existe');
             }




    }

    function deliverynote($id_quotation,$coin,$iva,$date)
    {


        $pdf = App::make('dompdf.wrapper');

             $quotation = null;

            if(isset($id_quotation)){
                 $quotation = Quotation::on(Auth::user()->database_name)->findOrFail($id_quotation);



                 if(!(isset($quotation->date_delivery_note))){
                    if(empty($quotation->number_delivery_note)){
                        //Me busco el ultimo numero en notas de entrega
                        $last_number = Quotation::on(Auth::user()->database_name)->where('number_delivery_note','<>',NULL)->orderBy('number_delivery_note','desc')->first();


                        //Asigno un numero incrementando en 1
                        if(isset($last_number)){
                            $quotation->number_delivery_note = $last_number->number_delivery_note + 1;
                        }else{
                            $quotation->number_delivery_note = 1;
                        }
                    }

                    $global = new GlobalController();
                    $retorno = $global->discount_inventory($id_quotation);

                    if($retorno != 'exito'){
                        return redirect('quotations/register/'.$id_quotation.'/'.$coin.'')->withDanger($retorno);
                    }


                 }else{
                    if(isset($quotation->bcv)){
                        $bcv = $quotation->bcv;
                     }
                 }


            }else{
                return redirect('/quotations')->withDanger('No llega el numero de la cotizacion');
            }

             if(isset($quotation)){

                $inventories_quotations = DB::connection(Auth::user()->database_name)->table('products')->join('inventories', 'products.id', '=', 'inventories.product_id')
                                                                ->join('quotation_products', 'inventories.id', '=', 'quotation_products.id_inventory')
                                                                ->where('quotation_products.id_quotation',$quotation->id)
                                                                ->select('products.*','quotation_products.price as price','quotation_products.rate as rate','quotation_products.discount as discount',
                                                                'quotation_products.amount as amount_quotation','quotation_products.retiene_iva as retiene_iva_quotation'
                                                                ,'quotation_products.retiene_islr as retiene_islr_quotation')
                                                                ->get();

                $total= 0;
                $base_imponible= 0;
                $price_cost_total= 0;

                //este es el total que se usa para guardar el monto de todos los productos que estan exentos de iva, osea retienen iva
                $total_retiene_iva = 0;
                $retiene_iva = 0;

                $total_retiene_islr = 0;
                $retiene_islr = 0;

                foreach($inventories_quotations as $var){
                    //Se calcula restandole el porcentaje de descuento (discount)
                    $percentage = (($var->price * $var->amount_quotation) * $var->discount)/100;

                    $total += ($var->price * $var->amount_quotation) - $percentage;
                    //-----------------------------

                    if($var->retiene_iva_quotation == 0){

                        $base_imponible += ($var->price * $var->amount_quotation) - $percentage;

                    }

                    if($var->retiene_islr_quotation == 1){

                        $retiene_islr += ($var->price * $var->amount_quotation) - $percentage;

                    }


                }

                $quotation->amount = $total;
                $quotation->base_imponible = $base_imponible;
                $quotation->amount_iva = $base_imponible * $quotation->iva_percentage / 100;
                $quotation->amount_with_iva = $quotation->amount + $quotation->amount_iva;
                $quotation->iva_percentage = $iva;
                $quotation->date_delivery_note = $date;
                $quotation->save();


                $quotation->total_factura = $total;
                //$quotation->base_imponible = $base_imponible;


                $date = Carbon::now();
                $datenow = $date->format('Y-m-d');
                $anticipos_sum = 0;
                if(isset($coin)){
                    if($coin == 'bolivares'){
                        $bcv = null;
                    }else{
                        $bcv = $quotation->bcv;
                    }
                }else{
                    $bcv = null;
                }


                /*Aqui revisamos el porcentaje de retencion de iva que tiene el cliente, para aplicarlo a productos que retengan iva */
                $client = Client::on(Auth::user()->database_name)->find($quotation->id_client);


                $company = Company::on(Auth::user()->database_name)->find(1);

                $pdf = $pdf->loadView('pdf.deliverynote',compact('quotation','inventories_quotations','bcv','company'
                                                                ,'total_retiene_iva','total_retiene_islr'));
                return $pdf->stream();

            }else{
                return redirect('/invoices')->withDanger('La nota de entrega no existe');
            }




    }

    function order($id_quotation,$coin,$iva,$date)
    {


        $pdf = App::make('dompdf.wrapper');

             $quotation = null;

            if(isset($id_quotation)){
                 $quotation = Quotation::on(Auth::user()->database_name)->findOrFail($id_quotation);

                 if(!(isset($quotation->date_order))){
                    if(empty($quotation->number_order)){
                        //Me busco el ultimo numero en notas de entrega
                        $last_number = Quotation::on(Auth::user()->database_name)->where('number_order','<>',NULL)->orderBy('number_order','desc')->first();

                        //Asigno un numero incrementando en 1
                        if(isset($last_number)){
                            $quotation->number_order = $last_number->number_order + 1;
                        }else{
                            $quotation->number_order = 1;
                        }
                    }
                    //if(!(isset($quotation->date_delivery_note)) && !(isset($quotation->date_order))){
                    $global = new GlobalController();
                    $retorno = $global->discount_inventory($id_quotation);

                    if($retorno != 'exito'){
                        return redirect('quotations/register/'.$id_quotation.'/'.$coin.'')->withDanger($retorno);
                    }
                    //}


                 }else{
                    if(isset($quotation->bcv)){
                        $bcv = $quotation->bcv;
                     }
                 }


            }else{
                return redirect('/quotations')->withDanger('No llega el numero de la cotizacion');
            }

             if(isset($quotation)){

                $inventories_quotations = DB::connection(Auth::user()->database_name)->table('products')->join('inventories', 'products.id', '=', 'inventories.product_id')
                                                                ->join('quotation_products', 'inventories.id', '=', 'quotation_products.id_inventory')
                                                                ->where('quotation_products.id_quotation',$quotation->id)
                                                                ->select('products.*','quotation_products.price as price','quotation_products.rate as rate','quotation_products.discount as discount',
                                                                'quotation_products.amount as amount_quotation','quotation_products.retiene_iva as retiene_iva_quotation'
                                                                ,'quotation_products.retiene_islr as retiene_islr_quotation')
                                                                ->get();

                $total= 0;
                $base_imponible= 0;
                $price_cost_total= 0;

                //este es el total que se usa para guardar el monto de todos los productos que estan exentos de iva, osea retienen iva
                $total_retiene_iva = 0;
                $retiene_iva = 0;

                $total_retiene_islr = 0;
                $retiene_islr = 0;

                foreach($inventories_quotations as $var){
                    //Se calcula restandole el porcentaje de descuento (discount)
                    $percentage = (($var->price * $var->amount_quotation) * $var->discount)/100;

                    $total += ($var->price * $var->amount_quotation) - $percentage;
                    //-----------------------------

                    if($var->retiene_iva_quotation == 0){

                        $base_imponible += ($var->price * $var->amount_quotation) - $percentage;

                    }

                    if($var->retiene_islr_quotation == 1){

                        $retiene_islr += ($var->price * $var->amount_quotation) - $percentage;

                    }


                }

                $quotation->amount = $total;
                $quotation->base_imponible = $base_imponible;
                $quotation->amount_iva = $base_imponible * $quotation->iva_percentage / 100;
                $quotation->amount_with_iva = $quotation->amount + $quotation->amount_iva;
                $quotation->iva_percentage = $iva;
                $quotation->date_order = $date;
                $quotation->save();


                $quotation->total_factura = $total;
                //$quotation->base_imponible = $base_imponible;


                $date = Carbon::now();
                $datenow = $date->format('Y-m-d');
                $anticipos_sum = 0;
                if(isset($coin)){
                    if($coin == 'bolivares'){
                        $bcv = null;
                    }else{
                        $bcv = $quotation->bcv;
                    }
                }else{
                    $bcv = null;
                }


                /*Aqui revisamos el porcentaje de retencion de iva que tiene el cliente, para aplicarlo a productos que retengan iva */
                $client = Client::on(Auth::user()->database_name)->find($quotation->id_client);


                $company = Company::on(Auth::user()->database_name)->find(1);

                $pdf = $pdf->loadView('pdf.order',compact('quotation','inventories_quotations','bcv','company'
                                                                ,'total_retiene_iva','total_retiene_islr'));
                return $pdf->stream();

            }else{
                return redirect('/invoices')->withDanger('La nota de entrega no existe');
            }




    }

    function deliverynotemediacarta($id_quotation,$coin,$iva,$date)
    {


        $pdf = App::make('dompdf.wrapper');

             $quotation = null;

            if(isset($id_quotation)){
                 $quotation = Quotation::on(Auth::user()->database_name)->findOrFail($id_quotation);



                 if(!(isset($quotation->date_delivery_note))){
                    if(empty($quotation->number_delivery_note)){
                        //Me busco el ultimo numero en notas de entrega
                        $last_number = Quotation::on(Auth::user()->database_name)->where('number_delivery_note','<>',NULL)->orderBy('number_delivery_note','desc')->first();
                        //Asigno un numero incrementando en 1
                        if(isset($last_number)){
                            $quotation->number_delivery_note = $last_number->number_delivery_note + 1;
                        }else{
                            $quotation->number_delivery_note = 1;
                        }
                    }

                    $global = new GlobalController();
                    $retorno = $global->discount_inventory($id_quotation);

                    if($retorno != 'exito'){
                        return redirect('quotations/register/'.$id_quotation.'/'.$coin.'')->withDanger($retorno);
                    }


                 }else{
                    if(isset($quotation->bcv)){
                        $bcv = $quotation->bcv;
                     }
                 }


            }else{
                return redirect('/quotations')->withDanger('No llega el numero de la cotizacion');
            }

             if(isset($quotation)){

                $inventories_quotations = DB::connection(Auth::user()->database_name)->table('products')->join('inventories', 'products.id', '=', 'inventories.product_id')
                                                                ->join('quotation_products', 'inventories.id', '=', 'quotation_products.id_inventory')
                                                                ->where('quotation_products.id_quotation',$quotation->id)
                                                                ->select('products.*','quotation_products.price as price','quotation_products.rate as rate','quotation_products.discount as discount',
                                                                'quotation_products.amount as amount_quotation','quotation_products.retiene_iva as retiene_iva_quotation'
                                                                ,'quotation_products.retiene_islr as retiene_islr_quotation')
                                                                ->get();

                $total= 0;
                $base_imponible= 0;
                $price_cost_total= 0;

                //este es el total que se usa para guardar el monto de todos los productos que estan exentos de iva, osea retienen iva
                $total_retiene_iva = 0;
                $retiene_iva = 0;

                $total_retiene_islr = 0;
                $retiene_islr = 0;

                foreach($inventories_quotations as $var){
                    //Se calcula restandole el porcentaje de descuento (discount)
                    $percentage = (($var->price * $var->amount_quotation) * $var->discount)/100;

                    $total += ($var->price * $var->amount_quotation) - $percentage;
                    //-----------------------------

                    if($var->retiene_iva_quotation == 0){

                        $base_imponible += ($var->price * $var->amount_quotation) - $percentage;

                    }

                    if($var->retiene_islr_quotation == 1){

                        $retiene_islr += ($var->price * $var->amount_quotation) - $percentage;

                    }


                }

                $quotation->amount = $total;
                $quotation->base_imponible = $base_imponible;
                $quotation->amount_iva = $base_imponible * $quotation->iva_percentage / 100;
                $quotation->amount_with_iva = $quotation->amount + $quotation->amount_iva;
                $quotation->iva_percentage = $iva;
                 $quotation->date_delivery_note = $date;
                $quotation->save();


                $quotation->total_factura = $total;
                //$quotation->base_imponible = $base_imponible;


                $date = Carbon::now();
                $datenow = $date->format('Y-m-d');
                $anticipos_sum = 0;
                if(isset($coin)){
                    if($coin == 'bolivares'){
                        $bcv = null;
                    }else{
                        $bcv = $quotation->bcv;
                    }
                }else{
                    $bcv = null;
                }


                /*Aqui revisamos el porcentaje de retencion de iva que tiene el cliente, para aplicarlo a productos que retengan iva */
                $client = Client::on(Auth::user()->database_name)->find($quotation->id_client);


                $company = Company::on(Auth::user()->database_name)->find(1);

                $pdf = $pdf->loadView('pdf.deliverynotemediacarta',compact('quotation','inventories_quotations','bcv','company'
                                                                ,'total_retiene_iva','total_retiene_islr'));
                return $pdf->stream();

            }else{
                return redirect('/invoices')->withDanger('La nota de entrega no existe');
            }




    }

    function deliverynote_expense($id_expense,$coin,$iva,$date)
    {


        $pdf = App::make('dompdf.wrapper');

             $expense = null;

             if(isset($id_expense)){
                $expense = ExpensesAndPurchase::on(Auth::user()->database_name)->findOrFail($id_expense);


                $date = Carbon::now();
                $datenow = $date->format('Y-m-d');

                $expense->iva_percentage = $iva;
                $expense->date_delivery_note = $date;


                if(isset($expense->bcv)){
                    $bcv = $expense->bcv;
                    }



             }else{
                return redirect('/expensesandpurchases')->withDanger('No llega el numero de la cotizacion');
                }

             if(isset($expense)){

                $inventories_expenses = DB::connection(Auth::user()->database_name)->table('products')->join('inventories', 'products.id', '=', 'inventories.product_id')
                                                           ->join('expenses_details', 'inventories.id', '=', 'expenses_details.id_inventory')
                                                           ->where('expenses_details.id_expense',$expense->id)
                                                           ->select('products.*','expenses_details.price as price','expenses_details.rate as rate',
                                                           'expenses_details.amount as amount_expense','expenses_details.exento as retiene_iva_expense'
                                                           ,'expenses_details.islr as retiene_islr_expense')
                                                           ->get();


                $total= 0;
                $base_imponible= 0;
                $price_cost_total= 0;

                //este es el total que se usa para guardar el monto de todos los productos que estan exentos de iva, osea retienen iva
                $total_retiene_iva = 0;
                $retiene_iva = 0;

                $total_retiene_islr = 0;
                $retiene_islr = 0;

                foreach($inventories_expenses as $var){
                    //Se calcula restandole el porcentaje de descuento (discount)

                        $total += ($var->price * $var->amount_expense);
                    //-----------------------------

                    if($var->retiene_iva_expense == 0){

                        $base_imponible += ($var->price * $var->amount_expense);

                    }else{
                        $retiene_iva += ($var->price * $var->amount_expense);
                    }

                    if($var->retiene_islr_expense == 1){

                        $retiene_islr += ($var->price * $var->amount_expense);

                    }

                }


                $expense->amount = $total;
                $expense->base_imponible = $base_imponible;
                $expense->amount_iva = $base_imponible * $expense->iva_percentage / 100;
                $expense->amount_with_iva = $expense->amount + $expense->amount_iva;
                $expense->save();


                $expense->total_factura = $total;
                //$expense->base_imponible = $base_imponible;


                $date = Carbon::now();
                $datenow = $date->format('Y-m-d');
                $anticipos_sum = 0;
                if(isset($coin)){
                    if($coin == 'bolivares'){
                        $bcv = null;
                    }else{
                        $bcv = $expense->rate;
                    }
                }else{
                    $bcv = null;
                }


                $company = Company::on(Auth::user()->database_name)->find(1);

                $pdf = $pdf->loadView('pdf.deliverynote_expense',compact('expense','inventories_expenses','bcv','company'
                                                                ,'total_retiene_iva','total_retiene_islr'));
                return $pdf->stream();

            }else{
                return redirect('/expensesandpurchases')->withDanger('La nota de entrega no existe');
            }




    }




    function asignar_payment_type($type){

        if($type == 1){
            return "Cheque";
        }
        if($type == 2){
            return "Contado";
        }
        if($type == 3){
            return "Contra Anticipo";
        }
        if($type == 4){
            return "Crédito";
        }
        if($type == 5){
            return "Depósito Bancario";
        }
        if($type == 6){
            return "Efectivo";
        }
        if($type == 7){
            return "Indeterminado";
        }
        if($type == 8){
            return "Tarjeta Coorporativa";
        }
        if($type == 9){
            return "Tarjeta de Crédito";
        }
        if($type == 10){
            return "Tarjeta de Débito";
        }
        if($type == 11){
            return "Transferencia";
        }
    }




    function imprimirinventory(){



        $pdf_inventory = App::make('dompdf.wrapper');

        $inventories = Inventory::on(Auth::user()->database_name)->orderBy('id','desc')->get();
        $date = Carbon::now();
        $datenow = $date->format('Y-m-d');

        $company = Company::on(Auth::user()->database_name)->find(1);

        $pdf_inventory = $pdf_inventory->loadView('pdf.inventory',compact('inventories','datenow','company'));
        return $pdf_inventory->stream();

    }






    function imprimirExpense($id_expense,$coin){


        $pdf = App::make('dompdf.wrapper');


             $expense = null;

             if(isset($id_expense)){
                 $expense = ExpensesAndPurchase::on(Auth::user()->database_name)->find($id_expense);


             }else{
                return redirect('/expensesandpurchases')->withDanger('No llega el numero del Gasto o Compra');
                }

             if(isset($expense)){

                 $payment_expenses = ExpensePayment::on(Auth::user()->database_name)->where('id_expense',$expense->id)->get();


                 if(!$payment_expenses->isEmpty()){
                    foreach($payment_expenses as $var){
                        $var->payment_type = $this->asignar_payment_type($var->payment_type);
                     }
                 }



                $inventories_expenses = ExpensesDetail::on(Auth::user()->database_name)->where('id_expense',$expense->id)->get();


                 if($coin == 'bolivares'){
                    $bcv = null;

                }else{
                    $bcv = $expense->rate;
                }

                 $company = Company::on(Auth::user()->database_name)->find(1);

                 $pdf = $pdf->loadView('pdf.expense',compact('bcv','coin','expense','inventories_expenses','payment_expenses','company'));
                 return $pdf->stream();

                }else{
                 return redirect('/expensesandpurchases')->withDanger('La Compra no existe');
             }




    }

    function imprimirExpenseMedia($id_expense,$coin){


        $pdf = App::make('dompdf.wrapper');


             $expense = null;

             if(isset($id_expense)){
                 $expense = ExpensesAndPurchase::on(Auth::user()->database_name)->find($id_expense);


             }else{
                return redirect('/expensesandpurchases')->withDanger('No llega el numero del Gasto o Compra');
                }

             if(isset($expense)){

                 $payment_expenses = ExpensePayment::on(Auth::user()->database_name)->where('id_expense',$expense->id)->get();

                 if(!$payment_expenses->isEmpty()){
                    foreach($payment_expenses as $var){
                        $var->payment_type = $this->asignar_payment_type($var->payment_type);
                     }
                 }



                $inventories_expenses = ExpensesDetail::on(Auth::user()->database_name)->where('id_expense',$expense->id)->get();

                /*$total= 0;
                $base_imponible= 0;
                $ventas_exentas= 0;
                foreach($inventories_expenses as $var){

                    $total += ($var->price * $var->amount);
                    //-----------------------------

                    if($var->exento == 0){

                        $base_imponible += ($var->price * $var->amount);

                    }
                    if($var->exento == 1){

                        $ventas_exentas += ($var->price * $var->amount);

                    }
                }

                if($coin != 'bolivares'){
                    $total = $total / $expense->rate;
                    $base_imponible = $base_imponible / $expense->rate;
                    $ventas_exentas = $ventas_exentas / $expense->rate;
                }

                 $expense->sub_total = $total;
                 $expense->base_imponible = $base_imponible;
                 $expense->ventas_exentas = $ventas_exentas;
                    */

                if($coin == 'bolivares'){
                    $bcv = null;

                }else{
                    $bcv = $expense->rate;
                }
                 $company = Company::on(Auth::user()->database_name)->find(1);

                 $pdf = $pdf->loadView('pdf.expense_media',compact('bcv','coin','expense','inventories_expenses','payment_expenses','company'));
                 return $pdf->stream();

                }else{
                 return redirect('/expensesandpurchases')->withDanger('La Compra no existe');
             }

    }


    function print_previousexercise($date_begin,$date_end){

        $pdf = App::make('dompdf.wrapper');

        $account_historial = AccountHistorial::on(Auth::user()->database_name)->where('date_begin',$date_begin)->where('date_end',$date_end)->orderBy('id','asc')->get();

        $date = Carbon::now();
        $datenow = $date->format('Y-m-d');

        $company = Company::on(Auth::user()->database_name)->find(1);

        $pdf = $pdf->loadView('pdf.previousexercise',compact('account_historial','datenow','company'));
        return $pdf->stream();

    }



    public function search_bcv()
    {
        /*Buscar el indice bcv*/
        $urlToGet ='http://www.bcv.org.ve/tasas-informativas-sistema-bancario';
        $pageDocument = @file_get_contents($urlToGet);
        preg_match_all('|<div class="col-sm-6 col-xs-6 centrado"><strong> (.*?) </strong> </div>|s', $pageDocument, $cap);

        if ($cap[0] == array()){ // VALIDAR Concidencia
            $titulo = '0,00';
        } else {
            $titulo = $cap[1][4];
        }

        $bcv_con_formato = $titulo;
        $bcv = str_replace(',', '.', str_replace('.', '',$bcv_con_formato));


        /*-------------------------- */
        return $bcv;

    }






    public function calculation($coin)
    {

        $accounts = Account::on(Auth::user()->database_name)->orderBy('code_one', 'asc')
                         ->orderBy('code_two', 'asc')
                         ->orderBy('code_three', 'asc')
                         ->orderBy('code_four', 'asc')
                         ->orderBy('code_five', 'asc')
                         ->get();


        if(isset($accounts)) {

            foreach ($accounts as $var)
            {
                if($var->code_one != 0)
                {
                    if($var->code_two != 0)
                    {
                        if($var->code_three != 0)
                        {
                            if($var->code_four != 0)
                            {
                                if($var->code_five != 0)
                                {
                                     /*CALCULA LOS SALDOS DESDE DETALLE COMPROBANTE */

                                     if($coin == 'bolivares'){
                                        $total_debe =   DB::connection(Auth::user()->database_name)->select('SELECT SUM(d.debe) AS debe
                                                        FROM accounts a
                                                        INNER JOIN detail_vouchers d
                                                            ON d.id_account = a.id
                                                        WHERE a.code_one = ? AND
                                                        a.code_two = ? AND
                                                        a.code_three = ? AND
                                                        a.code_four = ? AND
                                                        a.code_five = ? AND
                                                        d.status = ?
                                                        '
                                                        , [$var->code_one,$var->code_two,$var->code_three,$var->code_four,$var->code_five,'C']);
                                        $total_haber =   DB::connection(Auth::user()->database_name)->select('SELECT SUM(d.haber) AS haber
                                                        FROM accounts a
                                                        INNER JOIN detail_vouchers d
                                                            ON d.id_account = a.id
                                                        WHERE a.code_one = ? AND
                                                        a.code_two = ? AND
                                                        a.code_three = ? AND
                                                        a.code_four = ? AND
                                                        a.code_five = ? AND
                                                        d.status = ?
                                                        '
                                                        , [$var->code_one,$var->code_two,$var->code_three,$var->code_four,$var->code_five,'C']);

                                        $total_dolar_debe =   DB::connection(Auth::user()->database_name)->select('SELECT SUM(d.debe/d.tasa) AS dolar
                                                        FROM accounts a
                                                        INNER JOIN detail_vouchers d
                                                            ON d.id_account = a.id
                                                        WHERE a.code_one = ? AND
                                                        a.code_two = ? AND
                                                        a.code_three = ? AND
                                                        a.code_four = ? AND
                                                        a.code_five = ? AND
                                                        d.status = ?
                                                        '
                                                        , [$var->code_one,$var->code_two,$var->code_three,$var->code_four,$var->code_five,'C']);

                                        $total_dolar_haber =   DB::connection(Auth::user()->database_name)->select('SELECT SUM(d.haber/d.tasa) AS dolar
                                                        FROM accounts a
                                                        INNER JOIN detail_vouchers d
                                                            ON d.id_account = a.id
                                                        WHERE a.code_one = ? AND
                                                        a.code_two = ? AND
                                                        a.code_three = ? AND
                                                        a.code_four = ? AND
                                                        a.code_five = ? AND
                                                        d.status = ?
                                                        '
                                                        , [$var->code_one,$var->code_two,$var->code_three,$var->code_four,$var->code_five,'C']);

                                                        $var->balance =  $var->balance_previus;


                                        }else{
                                            $total_debe =   DB::connection(Auth::user()->database_name)->select('SELECT SUM(d.debe/d.tasa) AS debe
                                            FROM accounts a
                                            INNER JOIN detail_vouchers d
                                                ON d.id_account = a.id
                                            WHERE a.code_one = ? AND
                                            a.code_two = ? AND
                                            a.code_three = ? AND
                                            a.code_four = ? AND
                                            a.code_five = ? AND
                                            d.status = ?
                                            '
                                            , [$var->code_one,$var->code_two,$var->code_three,$var->code_four,$var->code_five,'C']);

                                            $total_haber =   DB::connection(Auth::user()->database_name)->select('SELECT SUM(d.haber/d.tasa) AS haber
                                            FROM accounts a
                                            INNER JOIN detail_vouchers d
                                                ON d.id_account = a.id
                                            WHERE a.code_one = ? AND
                                            a.code_two = ? AND
                                            a.code_three = ? AND
                                            a.code_four = ? AND
                                            a.code_five = ? AND
                                            d.status = ?
                                            '
                                            , [$var->code_one,$var->code_two,$var->code_three,$var->code_four,$var->code_five,'C']);




                                        }
                                        $total_debe = $total_debe[0]->debe;
                                        $total_haber = $total_haber[0]->haber;
                                        if(isset($total_dolar_debe[0]->dolar)){
                                            $total_dolar_debe = $total_dolar_debe[0]->dolar;
                                            $var->dolar_debe = $total_dolar_debe;
                                        }
                                        if(isset($total_dolar_haber[0]->dolar)){
                                            $total_dolar_haber = $total_dolar_haber[0]->dolar;
                                            $var->dolar_haber = $total_dolar_haber;
                                        }

                                        $var->debe = $total_debe;
                                        $var->haber = $total_haber;

                                        if(($var->balance_previus != 0) && ($var->rate !=0)){
                                            $var->balance =  $var->balance_previus;
                                        }

                                }else{

                                    /*CALCULA LOS SALDOS DESDE DETALLE COMPROBANTE */

                                    if($coin == 'bolivares'){
                                    $total_debe =   DB::connection(Auth::user()->database_name)->select('SELECT SUM(d.debe) AS debe
                                                    FROM accounts a
                                                    INNER JOIN detail_vouchers d
                                                        ON d.id_account = a.id
                                                    WHERE a.code_one = ? AND
                                                    a.code_two = ? AND
                                                    a.code_three = ? AND
                                                    a.code_four = ? AND
                                                    d.status = ?
                                                    '
                                                    , [$var->code_one,$var->code_two,$var->code_three,$var->code_four,'C']);
                                    $total_haber =   DB::connection(Auth::user()->database_name)->select('SELECT SUM(d.haber) AS haber
                                                    FROM accounts a
                                                    INNER JOIN detail_vouchers d
                                                        ON d.id_account = a.id
                                                    WHERE a.code_one = ? AND
                                                    a.code_two = ? AND
                                                    a.code_three = ? AND
                                                    a.code_four = ? AND
                                                    d.status = ?
                                                    '
                                                    , [$var->code_one,$var->code_two,$var->code_three,$var->code_four,'C']);

                                    $total_dolar_debe =   DB::connection(Auth::user()->database_name)->select('SELECT SUM(d.debe/d.tasa) AS dolar
                                                    FROM accounts a
                                                    INNER JOIN detail_vouchers d
                                                        ON d.id_account = a.id
                                                    WHERE a.code_one = ? AND
                                                    a.code_two = ? AND
                                                    a.code_three = ? AND
                                                    a.code_four = ? AND
                                                    d.status = ?
                                                    '
                                                    , [$var->code_one,$var->code_two,$var->code_three,$var->code_four,'C']);

                                    $total_dolar_haber =   DB::connection(Auth::user()->database_name)->select('SELECT SUM(d.haber/d.tasa) AS dolar
                                                    FROM accounts a
                                                    INNER JOIN detail_vouchers d
                                                        ON d.id_account = a.id
                                                    WHERE a.code_one = ? AND
                                                    a.code_two = ? AND
                                                    a.code_three = ? AND
                                                    a.code_four = ? AND
                                                    d.status = ?
                                                    '
                                                    , [$var->code_one,$var->code_two,$var->code_three,$var->code_four,'C']);

                                                    $var->balance =  $var->balance_previus;

                                    $total_balance =   DB::connection(Auth::user()->database_name)->select('SELECT SUM(a.balance_previus) AS balance
                                                    FROM accounts a
                                                    WHERE a.code_one = ? AND
                                                    a.code_two = ?  AND
                                                    a.code_three = ? AND
                                                    a.code_four = ?
                                                    '
                                                    , [$var->code_one,$var->code_two,$var->code_three,$var->code_four]);

                                    }else{
                                        $total_debe =   DB::connection(Auth::user()->database_name)->select('SELECT SUM(d.debe/d.tasa) AS debe
                                        FROM accounts a
                                        INNER JOIN detail_vouchers d
                                            ON d.id_account = a.id
                                        WHERE a.code_one = ? AND
                                        a.code_two = ? AND
                                        a.code_three = ? AND
                                        a.code_four = ? AND
                                        d.status = ?
                                        '
                                        , [$var->code_one,$var->code_two,$var->code_three,$var->code_four,'C']);

                                        $total_haber =   DB::connection(Auth::user()->database_name)->select('SELECT SUM(d.haber/d.tasa) AS haber
                                        FROM accounts a
                                        INNER JOIN detail_vouchers d
                                            ON d.id_account = a.id
                                        WHERE a.code_one = ? AND
                                        a.code_two = ? AND
                                        a.code_three = ? AND
                                        a.code_four = ? AND
                                        d.status = ?
                                        '
                                        , [$var->code_one,$var->code_two,$var->code_three,$var->code_four,'C']);

                                        $total_balance =   DB::connection(Auth::user()->database_name)->select('SELECT SUM(a.balance_previus/a.rate) AS balance
                                                    FROM accounts a
                                                    WHERE a.code_one = ? AND
                                                    a.code_two = ?  AND
                                                    a.code_three = ? AND
                                                    a.code_four = ?
                                                    '
                                                    , [$var->code_one,$var->code_two,$var->code_three,$var->code_four]);

                                        /*if(($var->balance_previus != 0) && ($var->rate !=0))
                                        $var->balance =  $var->balance_previus / $var->rate;*/
                                    }
                                    $total_debe = $total_debe[0]->debe;
                                    $total_haber = $total_haber[0]->haber;
                                    if(isset($total_dolar_debe[0]->dolar)){
                                        $total_dolar_debe = $total_dolar_debe[0]->dolar;
                                        $var->dolar_debe = $total_dolar_debe;
                                    }
                                    if(isset($total_dolar_haber[0]->dolar)){
                                        $total_dolar_haber = $total_dolar_haber[0]->dolar;
                                        $var->dolar_haber = $total_dolar_haber;
                                    }

                                    $var->debe = $total_debe;
                                    $var->haber = $total_haber;

                                    $total_balance = $total_balance[0]->balance;
                                    $var->balance = $total_balance;
                                }
                            }else{

                                if($coin == 'bolivares'){
                                $total_debe =   DB::connection(Auth::user()->database_name)->select('SELECT SUM(d.debe) AS debe
                                                FROM accounts a
                                                INNER JOIN detail_vouchers d
                                                    ON d.id_account = a.id
                                                WHERE a.code_one = ? AND
                                                a.code_two = ? AND
                                                a.code_three = ? AND

                                                d.status = ?
                                                '
                                                , [$var->code_one,$var->code_two,$var->code_three,'C']);
                                $total_haber =   DB::connection(Auth::user()->database_name)->select('SELECT SUM(d.haber) AS haber
                                                FROM accounts a
                                                INNER JOIN detail_vouchers d
                                                    ON d.id_account = a.id
                                                WHERE a.code_one = ? AND
                                                a.code_two = ? AND
                                                a.code_three = ? AND

                                                d.status = ?
                                                '
                                                , [$var->code_one,$var->code_two,$var->code_three,'C']);

                                $total_balance =   DB::connection(Auth::user()->database_name)->select('SELECT SUM(a.balance_previus) AS balance
                                            FROM accounts a
                                            WHERE a.code_one = ? AND
                                            a.code_two = ?  AND
                                            a.code_three = ?
                                            '
                                            , [$var->code_one,$var->code_two,$var->code_three]);

                                }else{
                                        $total_debe =   DB::connection(Auth::user()->database_name)->select('SELECT SUM(d.debe/d.tasa) AS debe
                                        FROM accounts a
                                        INNER JOIN detail_vouchers d
                                            ON d.id_account = a.id
                                        WHERE a.code_one = ? AND
                                        a.code_two = ? AND
                                        a.code_three = ? AND

                                        d.status = ?
                                        '
                                        , [$var->code_one,$var->code_two,$var->code_three,'C']);

                                        $total_haber =   DB::connection(Auth::user()->database_name)->select('SELECT SUM(d.haber/d.tasa) AS haber
                                        FROM accounts a
                                        INNER JOIN detail_vouchers d
                                            ON d.id_account = a.id
                                        WHERE a.code_one = ? AND
                                        a.code_two = ? AND
                                        a.code_three = ? AND

                                        d.status = ?
                                        '
                                        , [$var->code_one,$var->code_two,$var->code_three,'C']);

                                        $total_balance =   DB::connection(Auth::user()->database_name)->select('SELECT SUM(a.balance_previus/a.rate) AS balance
                                            FROM accounts a
                                            WHERE a.code_one = ? AND
                                            a.code_two = ? AND
                                            a.code_three = ?
                                            '
                                            , [$var->code_one,$var->code_two,$var->code_three]);

                                    }
                                    $total_debe = $total_debe[0]->debe;
                                    $total_haber = $total_haber[0]->haber;

                                    $var->debe = $total_debe;
                                    $var->haber = $total_haber;



                                    $total_balance = $total_balance[0]->balance;
                                    $var->balance = $total_balance;


                            }
                        }else{

                            if($coin == 'bolivares'){
                                $total_debe =   DB::connection(Auth::user()->database_name)->select('SELECT SUM(d.debe) AS debe
                                                FROM accounts a
                                                INNER JOIN detail_vouchers d
                                                    ON d.id_account = a.id
                                                WHERE a.code_one = ? AND
                                                a.code_two = ? AND
                                                d.status = ?
                                                '
                                                , [$var->code_one,$var->code_two,'C']);
                                $total_haber =   DB::connection(Auth::user()->database_name)->select('SELECT SUM(d.haber) AS haber
                                                FROM accounts a
                                                INNER JOIN detail_vouchers d
                                                    ON d.id_account = a.id
                                                WHERE a.code_one = ? AND
                                                a.code_two = ? AND
                                                d.status = ?
                                                '
                                                , [$var->code_one,$var->code_two,'C']);

                                $total_balance =   DB::connection(Auth::user()->database_name)->select('SELECT SUM(a.balance_previus) AS balance
                                            FROM accounts a
                                            WHERE a.code_one = ? AND
                                            a.code_two = ?
                                            '
                                            , [$var->code_one,$var->code_two]);

                                }else{
                                    $total_debe =   DB::connection(Auth::user()->database_name)->select('SELECT SUM(d.debe/d.tasa) AS debe
                                    FROM accounts a
                                    INNER JOIN detail_vouchers d
                                        ON d.id_account = a.id
                                    WHERE a.code_one = ? AND
                                    a.code_two = ? AND
                                    d.status = ?
                                    '
                                    , [$var->code_one,$var->code_two,'C']);

                                    $total_haber =   DB::connection(Auth::user()->database_name)->select('SELECT SUM(d.haber/d.tasa) AS haber
                                    FROM accounts a
                                    INNER JOIN detail_vouchers d
                                        ON d.id_account = a.id
                                    WHERE a.code_one = ? AND
                                    a.code_two = ? AND
                                    d.status = ?
                                    '
                                    , [$var->code_one,$var->code_two,'C']);

                                    $total_balance =   DB::connection(Auth::user()->database_name)->select('SELECT SUM(a.balance_previus/a.rate) AS balance
                                            FROM accounts a
                                            WHERE a.code_one = ? AND
                                            a.code_two = ?
                                            '
                                            , [$var->code_one,$var->code_two]);

                                }

                                $total_debe = $total_debe[0]->debe;
                                $total_haber = $total_haber[0]->haber;
                                $var->debe = $total_debe;
                                $var->haber = $total_haber;



                                $total_balance = $total_balance[0]->balance;
                                $var->balance = $total_balance;
                        }
                    }else{
                        if($coin == 'bolivares'){
                            $total_debe =   DB::connection(Auth::user()->database_name)->select('SELECT SUM(d.debe) AS debe
                                            FROM accounts a
                                            INNER JOIN detail_vouchers d
                                                ON d.id_account = a.id
                                            WHERE a.code_one = ? AND
                                            d.status = ?
                                            '
                                            , [$var->code_one,'C']);
                            $total_haber =   DB::connection(Auth::user()->database_name)->select('SELECT SUM(d.haber) AS haber
                                            FROM accounts a
                                            INNER JOIN detail_vouchers d
                                                ON d.id_account = a.id
                                            WHERE a.code_one = ? AND
                                            d.status = ?
                                            '
                                            , [$var->code_one,'C']);

                            $total_balance =   DB::connection(Auth::user()->database_name)->select('SELECT SUM(a.balance_previus) AS balance
                                            FROM accounts a
                                            WHERE a.code_one = ?
                                            '
                                            , [$var->code_one]);

                            }else{
                                $total_debe =   DB::connection(Auth::user()->database_name)->select('SELECT SUM(d.debe/d.tasa) AS debe
                                FROM accounts a
                                INNER JOIN detail_vouchers d
                                    ON d.id_account = a.id
                                WHERE a.code_one = ? AND
                                d.status = ?
                                '
                                , [$var->code_one,'C']);

                                $total_haber =   DB::connection(Auth::user()->database_name)->select('SELECT SUM(d.haber/d.tasa) AS haber
                                FROM accounts a
                                INNER JOIN detail_vouchers d
                                    ON d.id_account = a.id
                                WHERE a.code_one = ? AND
                                d.status = ?
                                '
                                , [$var->code_one,'C']);

                                $total_balance =   DB::connection(Auth::user()->database_name)->select('SELECT SUM(a.balance_previus/a.rate) AS balance
                                            FROM accounts a
                                            WHERE a.code_one = ?
                                            '
                                            , [$var->code_one]);

                            }
                            $total_debe = $total_debe[0]->debe;
                            $total_haber = $total_haber[0]->haber;
                            $var->debe = $total_debe;
                            $var->haber = $total_haber;

                            $total_balance = $total_balance[0]->balance;

                            $var->balance = $total_balance;

                    }
                }else{
                    return redirect('/accounts/menu')->withDanger('El codigo uno es igual a cero!');
                }
            }

        }else{
            return redirect('/accounts/menu')->withDanger('No hay Cuentas');
        }



         return $accounts;
    }
}
