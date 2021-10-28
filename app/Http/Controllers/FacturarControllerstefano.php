<?php

namespace App\Http\Controllers;

use App\Account;
use App\Anticipo;
use App\Company;
use App\DetailVoucher;
use App\HeaderVoucher;
use App\Inventory;
use App\Client;
use Illuminate\Http\Request;

use App\Quotation;
use App\QuotationPayment;
use App\QuotationProduct;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class FacturarController extends Controller
{
    public function createfacturar($id_quotation, $coin)
    {

        $quotation = null;

        if (isset($id_quotation)) {
            $quotation = Quotation::on(Auth::user()->database_name)->find($id_quotation);
        }

        if (isset($quotation)) {

            $payment_quotations = QuotationPayment::on(Auth::user()->database_name)->where('id_quotation', $quotation->id)->get();


            $anticipos_sum_bolivares = Anticipo::on(Auth::user()->database_name)->where('status',1)
            ->where('id_client',$quotation->id_client)
            ->where(function ($query) use ($quotation){
                $query->where('id_quotation',null)
                    ->orWhere('id_quotation',$quotation->id);
            })
            ->where('coin','like','bolivares')
            ->sum('amount');


            $total_dolar_anticipo = Anticipo::on(Auth::user()->database_name)->where('status',1)
                                ->where('id_client',$quotation->id_client)
                                ->where(function ($query) use ($quotation){
                                    $query->where('id_quotation',null)
                                        ->orWhere('id_quotation',$quotation->id);
                                })
                                ->where('coin','not like','bolivares')
                                ->select( DB::raw('SUM(anticipos.amount/anticipos.rate) As dolar'))
                                ->get();


            $anticipos_sum_dolares = 0;
            if (isset($total_dolar_anticipo[0]->dolar)) {
                $anticipos_sum_dolares = $total_dolar_anticipo[0]->dolar;
            }


            $accounts_bank = DB::connection(Auth::user()->database_name)->table('accounts')->where('code_one', 1)
                ->where('code_two', 1)
                ->where('code_three', 1)
                ->where('code_four', 2)
                ->where('code_five', '<>', 0)
                ->where('description', 'not like', 'Punto de Venta%')
                ->get();
            $accounts_efectivo = DB::connection(Auth::user()->database_name)->table('accounts')->where('code_one', 1)
                ->where('code_two', 1)
                ->where('code_three', 1)
                ->where('code_four', 1)
                ->where('code_five', '<>', 0)
                ->get();
            $accounts_punto_de_venta = DB::connection(Auth::user()->database_name)->table('accounts')->where('description', 'LIKE', 'Punto de Venta%')
                ->get();

            $inventories_quotations = DB::connection(Auth::user()->database_name)->table('products')->join('inventories', 'products.id', '=', 'inventories.product_id')
                ->join('quotation_products', 'inventories.id', '=', 'quotation_products.id_inventory')
                ->where('quotation_products.id_quotation', $quotation->id)
                ->select('products.*', 'quotation_products.price as price', 'quotation_products.rate as rate', 'quotation_products.discount as discount',
                    'quotation_products.amount as amount_quotation', 'quotation_products.retiene_iva as retiene_iva_quotation'
                    , 'quotation_products.retiene_islr as retiene_islr_quotation')
                ->get();


            $company = Company::find(1);
            $tax_1 = $company->tax_1;

            //Si la taza es automatica
            if ($company->tiporate_id == 1) {
                //esto es para que siempre se pueda guardar la tasa en la base de datos
                $bcv_quotation_product = $this->search_bcv();
                $bcv = $this->search_bcv();
            } else {
                //si la tasa es fija
                $bcv_quotation_product = $company->rate;
                $bcv = $company->rate;

            }
            $total = 0;
            $base_imponible = 0;
            $price_cost_total = 0;

            $retiene_iva = 0;

            $total_retiene_islr = 0;
            $retiene_islr = 0;

            $total_mercancia = 0;
            $total_servicios = 0;

            $total = 0;
            $base_imponible = 0;
            $price_cost_total = 0;

            //este es el total que se usa para guardar el monto de todos los productos que estan exentos de iva, osea retienen iva
            $total_retiene_iva = 0;
            $retiene_iva = 0;
            $total_retiene_islr = 0;
            $total_retiene = 0;
            $total_iva = 0;
            $total_base_impo_pcb = 0;
            $total_iva_pcb = 0;
            $total_venta = 0;

            $base_imponible_pcb = 15;
            $iva = 12;
            $rate = $quotation->bcv;
            foreach ($inventories_quotations as $var) {
                //Se calcula restandole el porcentaje de descuento (discount)
                $percentage = (($var->price * $var->amount_quotation) * $var->discount) / 100;

                $total += ($var->price * $var->amount_quotation) - $percentage;
                //-----------------------------

                if ($var->retiene_iva_quotation == 0) {

                    $base_imponible += ($var->price * $var->amount_quotation) - $percentage;

                    $total_retiene += ($var->price * $var->amount_quotation) - $percentage;
                    $total_iva = $total_retiene * ($iva / 100);
                    $total_base_impo_pcb = $total_retiene * ($base_imponible_pcb / 100);
                    $total_iva_pcb = $total_base_impo_pcb * ($iva / 100);
                    $total_venta = $total_retiene + $total_iva + $total_iva_pcb;


                } else {
                    $retiene_iva += ($var->price * $var->amount_quotation) - $percentage;
                }

                if ($var->retiene_islr_quotation == 1) {

                    $retiene_islr += ($var->price * $var->amount_quotation) - $percentage;

                }

                //me suma todos los precios de costo de los productos
                if (($var->money == 'Bs') && ($var->type == "MERCANCIA")) {
                    $price_cost_total += $var->price_buy * $var->amount_quotation;
                } else if (($var->money != 'Bs') && ($var->type == "MERCANCIA")) {
                    $price_cost_total += $var->price_buy * $var->amount_quotation * $quotation->bcv;
                }

                if ($var->type == "MERCANCIA") {
                    $total_mercancia += ($var->price * $var->amount_quotation) - $percentage;
                } else {
                    $total_servicios += ($var->price * $var->amount_quotation) - $percentage;
                }
            }


            $date = Carbon::now();
            $datenow = $date->format('Y-m-d');
            $anticipos_sum = 0;
            if (isset($coin)) {
                if ($coin == 'bolivares') {
                    $bcv = null;
                    //Si la factura es en BS, y tengo anticipos en dolares, los multiplico los dolares por la tasa a la que estoy facturando
                    $anticipos_sum_dolares = $anticipos_sum_dolares * $quotation->bcv;
                    $anticipos_sum = $anticipos_sum_bolivares + $anticipos_sum_dolares;
                } else {
                    $bcv = $quotation->bcv;
                    //Si la factura es en Dolares, y tengo anticipos en bolivares, divido los bolivares por la tasa a la que estoy facturando
                    $anticipos_sum_bolivares = $anticipos_sum_bolivares / $quotation->bcv;
                    $anticipos_sum = $anticipos_sum_bolivares + $anticipos_sum_dolares;
                }
            } else {
                $bcv = null;
            }


            /*Aqui revisamos el porcentaje de retencion de iva que tiene el cliente, para aplicarlo a productos que retengan iva */
            $client = Client::on(Auth::user()->database_name)->find($quotation->id_client);
            
            if($client->percentage_retencion_islr != 0){
                $total_retiene_islr = ($retiene_islr * $client->percentage_retencion_islr) /100;
            }

            /*-------------- */

            $is_after = false;
            if (empty($quotation->credit_days)) {
                $is_after = true;
            }

            return view('admin.quotations.createfacturar', compact('price_cost_total', 'coin', 'quotation'
                , 'payment_quotations', 'accounts_bank', 'accounts_efectivo', 'accounts_punto_de_venta'
                , 'datenow', 'bcv', 'anticipos_sum', 'total_retiene_islr', 'is_after'
                , 'total_mercancia', 'total_servicios', 'client', 'total', 'total_iva_pcb', 'total_iva', 'total_base_impo_pcb', 'total_venta'));
        } else {
            return redirect('/quotations')->withDanger('La cotizacion no existe');
        }

    }

    public function createfacturar_after($id_quotation, $coin)
    {
        $quotation = null;

        if (isset($id_quotation)) {
            $quotation = Quotation::on(Auth::user()->database_name)->find($id_quotation);
        }

        if (isset($quotation)) {

            $payment_quotations = QuotationPayment::on(Auth::user()->database_name)->where('id_quotation', $quotation->id)->get();

            $anticipos_sum_bolivares = Anticipo::on(Auth::user()->database_name)->where('status', 1)
                ->where('id_client', $quotation->id_client)
                ->where(function ($query) use ($quotation) {
                    $query->where('id_quotation', null)
                        ->orWhere('id_quotation', $quotation->id);
                })
                ->where('coin', 'like', 'bolivares')
                ->sum('amount');

            $total_dolar_anticipo = DB::connection(Auth::user()->database_name)->select('SELECT SUM(amount/rate) AS dolar
                                        FROM anticipos
                                        WHERE id_client = ? AND
                                        coin not like ? AND
                                        status = ?
                                        '
                , [$quotation->id_client, 'bolivares', 1]);

            $anticipos_sum_dolares = 0;
            if (isset($total_dolar_anticipo[0]->dolar)) {
                $anticipos_sum_dolares = $total_dolar_anticipo[0]->dolar;
            }

            $accounts_bank = DB::connection(Auth::user()->database_name)->table('accounts')->where('code_one', 1)
                ->where('code_two', 1)
                ->where('code_three', 1)
                ->where('code_four', 2)
                ->where('code_five', '<>', 0)
                ->where('description', 'not like', 'Punto de Venta%')
                ->get();
            $accounts_efectivo = DB::connection(Auth::user()->database_name)->table('accounts')->where('code_one', 1)
                ->where('code_two', 1)
                ->where('code_three', 1)
                ->where('code_four', 1)
                ->where('code_five', '<>', 0)
                ->get();
            $accounts_punto_de_venta = DB::connection(Auth::user()->database_name)->table('accounts')->where('description', 'LIKE', 'Punto de Venta%')
                ->get();

            $inventories_quotations = DB::connection(Auth::user()->database_name)->table('products')->join('inventories', 'products.id', '=', 'inventories.product_id')
                ->join('quotation_products', 'inventories.id', '=', 'quotation_products.id_inventory')
                ->where('quotation_products.id_quotation', $quotation->id)
                ->select('products.*', 'quotation_products.price as price', 'quotation_products.rate as rate', 'quotation_products.discount as discount',
                    'quotation_products.amount as amount_quotation', 'quotation_products.retiene_iva as retiene_iva_quotation'
                    , 'quotation_products.retiene_islr as retiene_islr_quotation')
                ->get();

            $total = 0;
            $base_imponible = 0;
            $price_cost_total = 0;

            //este es el total que se usa para guardar el monto de todos los productos que estan exentos de iva, osea retienen iva
            $total_retiene_iva = 0;
            $retiene_iva = 0;

            $total_retiene_islr = 0;
            $retiene_islr = 0;

            foreach ($inventories_quotations as $var) {
                //Se calcula restandole el porcentaje de descuento (discount)
                $percentage = (($var->price * $var->amount_quotation) * $var->discount) / 100;

                $total += ($var->price * $var->amount_quotation) - $percentage;
                //-----------------------------

                if ($var->retiene_iva_quotation == 0) {

                    $base_imponible += ($var->price * $var->amount_quotation) - $percentage;

                } else {
                    $retiene_iva += ($var->price * $var->amount_quotation) - $percentage;
                }

                if ($var->retiene_islr_quotation == 1) {

                    $retiene_islr += ($var->price * $var->amount_quotation) - $percentage;

                }

                //me suma todos los precios de costo de los productos
                if ($var->money == 'Bs') {
                    $price_cost_total += $var->price_buy * $var->amount_quotation;
                } else {
                    $price_cost_total += $var->price_buy * $var->amount_quotation * $quotation->bcv;
                }
            }

            $quotation->total_factura = $total;
            $quotation->base_imponible = $base_imponible;

            $date = Carbon::now();
            $datenow = $date->format('Y-m-d');
            $anticipos_sum = 0;
            if (isset($coin)) {
                if ($coin == 'bolivares') {
                    $bcv = null;
                    //Si la factura es en BS, y tengo anticipos en dolares, los multiplico los dolares por la tasa a la que estoy facturando
                    $anticipos_sum_dolares = $anticipos_sum_dolares * $quotation->bcv;
                    $anticipos_sum = $anticipos_sum_bolivares + $anticipos_sum_dolares;
                } else {
                    $bcv = $quotation->bcv;
                    //Si la factura es en Dolares, y tengo anticipos en bolivares, divido los bolivares por la tasa a la que estoy facturando
                    $anticipos_sum_bolivares = $anticipos_sum_bolivares / $quotation->bcv;
                    $anticipos_sum = $anticipos_sum_bolivares + $anticipos_sum_dolares;
                }
            } else {
                $bcv = null;
            }


            /*Aqui revisamos el porcentaje de retencion de iva que tiene el cliente, para aplicarlo a productos que retengan iva */
            $client = Client::on(Auth::user()->database_name)->find($quotation->id_client);

            /*if($client->percentage_retencion_iva != 0){
                $total_retiene_iva = ($retiene_iva * $client->percentage_retencion_iva) /100;
            }*/


            if ($client->percentage_retencion_islr != 0) {
                $total_retiene_islr = ($retiene_islr * $client->percentage_retencion_islr) / 100;
            }

            /*-------------- */

            $is_after = false;

            return view('admin.quotations.createfacturar', compact('price_cost_total', 'coin', 'quotation'
                , 'payment_quotations', 'accounts_bank', 'accounts_efectivo', 'accounts_punto_de_venta'
                , 'datenow', 'bcv', 'anticipos_sum', 'total_retiene_iva', 'total_retiene_islr', 'is_after', 'client'));
        } else {
            return redirect('/quotations')->withDanger('La cotizacion no existe');
        }

    }

    public function storefacturacredit(Request $request)
    {
        //dd($request);
        $id_quotation = request('id_quotation');

        $quotation = Quotation::on(Auth::user()->database_name)->findOrFail($id_quotation);
        $quotation->coin = request('coin');
        $bcv = $quotation->bcv;


        //precio de costo de los productos, vienen en bolivares
        $price_cost_total = request('price_cost_total');// * $bcv;
        $total_venta = request('total_pay');

        $total_retiene_iva = str_replace(',', '.', str_replace('.', '', request('iva_retencion')));
        $total_retiene_islr = str_replace(',', '.', str_replace('.', '', request('islr_retencion')));
        $anticipo = str_replace(',', '.', str_replace('.', '', request('anticipo')));


        $sin_formato_base_imponible = str_replace(',', '.', str_replace('.', '', request('base_imponible')));
        $sin_formato_amount = str_replace(',', '.', str_replace('.', '', request('total_factura')));
        $sin_formato_amount_iva = str_replace(',', '.', str_replace('.', '', request('iva_amount')));
        $sin_formato_amount_with_iva = str_replace(',', '.', str_replace('.', '', request('total_pay')));

        $sin_formato_grand_total = str_replace(',', '.', str_replace('.', '', request('grand_total')));


        $total_mercancia = request('total_mercancia_credit');
        $total_servicios = request('total_servicios_credit');

        if ($quotation->coin != 'bolivares') {

            $sin_formato_amount_iva = $sin_formato_amount_iva * $bcv;
            $sin_formato_amount_with_iva = $sin_formato_amount_with_iva * $bcv;
            $sin_formato_base_imponible = $sin_formato_base_imponible * $bcv;
            $sin_formato_amount = $sin_formato_amount * $bcv;

            $total_retiene_iva = $total_retiene_iva * $bcv;
            $total_retiene_islr = $total_retiene_islr * $bcv;
            $anticipo = $anticipo * $bcv;

            $sin_formato_grand_total = $total_venta * $bcv;
        }


        $date = Carbon::now();
        $datenow = $date->format('Y-m-d');

        $quotation->date_billing = $datenow;

        $quotation->retencion_iva = $total_retiene_iva;
        $quotation->retencion_islr = $total_retiene_islr;
        $quotation->anticipo = $anticipo;

        $quotation->base_imponible = $sin_formato_base_imponible;
        $quotation->amount = $sin_formato_amount;
        $quotation->amount_iva = $sin_formato_amount_iva;
        $quotation->amount_with_iva = $sin_formato_amount_with_iva;

        $credit = request('credit');
        $user_id = request('user_id');

        $date                           = Carbon::now();
        $datenow                        = $date->format('Y-m-d');

        $fecha      = date($datenow);

        $fecha_actual      = date("Y-m-d",strtotime($fecha."+".$credit."days"));
        $dd         = date('l', strtotime($fecha_actual));

        if($dd == "Saturday"){
            $credit_day = $credit + 2;
            $fecha_actual      = date("Y-m-d",strtotime($fecha."+".$credit_day."days"));
        }elseif($dd == "Sunday"){
            $credit_day = $credit + 1;
            $fecha_actual      = date("Y-m-d",strtotime($fecha."+".$credit_day."days"));
        }else{
        }

        $quotation->iva_percentage = request('iva');

        $quotation->credit_days = $credit;

        //P de por pagar
        $quotation->status = 'P';

        $last_number = Quotation::on(Auth::user()->database_name)->where('number_invoice', '<>', NULL)->orderBy('number_invoice', 'desc')->first();

        //Asigno un numero incrementando en 1
        if (isset($last_number)) {
            $quotation->number_invoice = $last_number->number_invoice + 1;
        } else {
            $quotation->number_invoice = 1;
        }

        $quotation->save();


        $header_voucher = new HeaderVoucher();
        $header_voucher->setConnection(Auth::user()->database_name);


        $header_voucher->description = "Ventas de Bienes o servicios.";
        $header_voucher->date = $datenow;


        $header_voucher->status = "1";

        $header_voucher->save();

        DB::connection(Auth::user()->database_name)->table('quotation_products')
            ->where('id_quotation', '=', $quotation->id)
            ->update(['status' => 'C']);


        /*DB::connection(Auth::user()->database_name)->table('anticipos')->where('id_client', '=', $quotation->id_client)
                                                                        ->where('id_quotation',null)
                                                                        ->orWhere('id_quotation',$quotation->id)
                                                                        ->where('status', '=', '1')
                                                                        ->update(['status' => 'C']);


        //los que quedaron en espera, pasan a estar activos
        DB::connection(Auth::user()->database_name)->table('anticipos')->where('id_client', '=', $quotation->id_client)
                                                                        ->where('id_quotation',null)
                                                                        ->orWhere('id_quotation',$quotation->id)
                                                                        ->where('status', '=', 'M')
                                                                        ->update(['status' => '1']);
        */
        /*Busqueda de Cuentas*/

        //Cuentas por Cobrar Clientes

        $account_cuentas_por_cobrar = Account::on(Auth::user()->database_name)->where('description', 'like', 'Cuentas por Cobrar Clientes')->first();

        if (isset($account_cuentas_por_cobrar)) {
            $this->add_movement($bcv, $header_voucher->id, $account_cuentas_por_cobrar->id, $quotation->id, $user_id, $sin_formato_grand_total, 0);
        }

        if ($total_mercancia != 0) {
            $account_subsegmento = Account::on(Auth::user()->database_name)->where('description', 'like', 'Ventas por Bienes')->first();

            if (isset($account_subsegmento)) {
                $this->add_movement($bcv, $header_voucher->id, $account_subsegmento->id, $quotation->id, $user_id, 0, $total_mercancia);
            }
        }

        if ($total_servicios != 0) {
            $account_subsegmento = Account::on(Auth::user()->database_name)->where('description', 'like', 'Ventas por Servicios')->first();

            if (isset($account_subsegmento)) {
                $this->add_movement($bcv, $header_voucher->id, $account_subsegmento->id, $quotation->id, $user_id, 0, $total_servicios);
            }
        }

        //Debito Fiscal IVA por Pagar

        $account_debito_iva_fiscal = Account::on(Auth::user()->database_name)->where('description', 'like', 'Debito Fiscal IVA por Pagar')->first();

        if ($sin_formato_amount_iva != 0) {

            if (isset($account_cuentas_por_cobrar)) {
                $this->add_movement($bcv, $header_voucher->id, $account_debito_iva_fiscal->id, $quotation->id, $user_id, 0, $sin_formato_amount_iva);
            }
        }

        //Mercancia para la Venta

        if ((isset($price_cost_total)) && ($price_cost_total != 0)) {
            $account_mercancia_venta = Account::on(Auth::user()->database_name)->where('description', 'like', 'Mercancia para la Venta')->first();

            if (isset($account_cuentas_por_cobrar)) {
                $this->add_movement($bcv, $header_voucher->id, $account_mercancia_venta->id, $quotation->id, $user_id, 0, $price_cost_total);
            }

            //Costo de Mercancia

            $account_costo_mercancia = Account::on(Auth::user()->database_name)->where('description', 'like', 'Costo de Mercancia')->first();

            if (isset($account_cuentas_por_cobrar)) {
                $this->add_movement($bcv, $header_voucher->id, $account_costo_mercancia->id, $quotation->id, $user_id, $price_cost_total, 0);
            }
        }


        return redirect('quotations/facturado/' . $quotation->id . '/' . $quotation->coin . '')->withSuccess('Factura Guardada con Exito!');
    }


    public function storefactura(Request $request)
    {


        $data = request()->validate([


        ]);


        $quotation = Quotation::on(Auth::user()->database_name)->findOrFail(request('id_quotation'));

        $quotation_status = $quotation->status;

        if ($quotation->status == 'C') {
            return redirect('quotations/facturar/' . $quotation->id . '/' . $quotation->coin . '')->withDanger('Ya esta factura fue procesada!');
        } else {

            //dd($request);
            $date = Carbon::now();
            $datenow = $date->format('Y-m-d');

            $total_pay = 0;

            //Saber cuantos pagos vienen
            $come_pay = request('amount_of_payments');
            $user_id = request('user_id');
            $coin = request('coin');
            $price_cost_total = request('price_cost_total');
            $anticipo = request('anticipo_form');
            $retencion_iva = request('total_retiene_iva');
            $retencion_islr = request('total_retiene_islr');
            $anticipo = request('anticipo_form');

            $sub_total = request('sub_total_form');
            //  $base_imponible = request('base_imponible_form');


            //  $base_imponible = request('base_imponible_form');
            $sin_formato_amount = request('sub_total_form');
            $iva_percentage = request('iva_amount');
            $sin_formato_total_pay = request('total_pay_form');
            $total_venta = request('total_pay_venta');

            $credit = request('credit');
            $iva_percibido = request('iva_retencion');



            /*Validar cuales son los pagos a guardar */
            $validate_boolean1 = false;
            $validate_boolean2 = false;
            $validate_boolean3 = false;
            $validate_boolean4 = false;
            $validate_boolean5 = false;
            $validate_boolean6 = false;
            $validate_boolean7 = false;

            //-----------------------



            $bcv = $quotation->bcv;

            $inventories_quotationss = DB::table('products')->join('inventories', 'products.id', '=', 'inventories.product_id')
                ->join('quotation_products', 'inventories.id', '=', 'quotation_products.id_inventory')
                ->where('quotation_products.id_quotation',$quotation->id)
                ->select('products.*','quotation_products.price as price','quotation_products.rate as rate','quotation_products.discount as discount',
                    'quotation_products.amount as amount_quotation','quotation_products.retiene_iva as retiene_iva_quotation'
                    ,'quotation_products.retiene_islr as retiene_islr_quotation','quotation_products.retiene_iva as retiene_iva_quotation')
                ->get();


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
            $base_imponible_pcb    = 15;
            $iva                   = 12;
            $rate                  = $quotation->bcv;


            foreach($inventories_quotationss as $vars){

                //Se calcula restandole el porcentaje de descuento (discount)
                $percentage = (($vars->price * $vars->amount_quotation) * $vars->discount)/100;
                $total += ($vars->price * $vars->amount_quotation) - $percentage;


                if( $vars->retiene_iva_quotation == 1 ){
                    $base_imponible        = 0;
                    $total_retiene         = 0;
                    $total_base_impo_pcb   = 0;
                    $total_iva_pcb         = 0;
                    $total_iva             = 0;

                }else{
                    $base_imponible         +=  ($vars->price * $vars->amount_quotation) - $percentage;
                    $total_retiene         +=  ($vars->price * $vars->amount_quotation) - $percentage;
                    $total_iva             =  $total_retiene * ($iva / 100) ;
                    $total_base_impo_pcb   =  $total_retiene *($base_imponible_pcb /100) ;
                    $total_iva_pcb         =  $total_base_impo_pcb * ($iva /100);
                }

            }




            $date = Carbon::now();
            $datenow = $date->format('Y-m-d');

            $fecha = date($datenow);

            $fecha_actual = date("Y-m-d", strtotime($fecha . "+" . $credit . "days"));
            $dd = date('l', strtotime($fecha_actual));

            if ($dd == "Saturday") {
                $credit_day = $credit + 2;
                $fecha_actual = date("Y-m-d", strtotime($fecha . "+" . $credit_day . "days"));
            } elseif ($dd == "Sunday") {
                $credit_day = $credit + 1;
                $fecha_actual = date("Y-m-d", strtotime($fecha . "+" . $credit_day . "days"));
            } else {
            }

            $sin_formato_grandtotal = str_replace(',', '.', str_replace('.', '', request('grandtotal_form')));
            $sin_formato_amount_iva = str_replace(',', '.', str_replace('.', '', request('iva_amount_form')));


            $total_mercancia = request('total_mercancia');
            $total_servicios = request('total_servicios');

            $total_iva = 0;

            if ($base_imponible != 0) {
                $total_iva = ($base_imponible * 12) / 100;

            }

            $payment_type = request('payment_type');
            if ($come_pay >= 1) {

                /*-------------PAGO NUMERO 1----------------------*/

                $var = new QuotationPayment();
                $var->setConnection(Auth::user()->database_name);

                $amount_pay = request('amount_pay');

                if (isset($amount_pay)) {

                    $valor_sin_formato_amount_pay = str_replace(',', '.', str_replace('.', '', $amount_pay));
                } else {
                    return redirect('quotations/facturar/' . $quotation->id . '/' . $quotation->coin . '')->withDanger('Debe ingresar un monto de pago 1!');
                }


                $account_bank = request('account_bank');
                $account_efectivo = request('account_efectivo');
                $account_punto_de_venta = request('account_punto_de_venta');

                $credit_days = request('credit_days');

                $reference = request('reference');

                if ($valor_sin_formato_amount_pay != 0) {

                    if ($payment_type != 0) {

                        $var->id_quotation = request('id_quotation');

                        //SELECCIONA LA CUENTA QUE SE REGISTRA EN EL TIPO DE PAGO
                        if ($payment_type == 1 || $payment_type == 11 || $payment_type == 5) {
                            //CUENTAS BANCARIAS
                            if (($account_bank != 0)) {
                                if (isset($reference)) {

                                    $var->id_account = $account_bank;

                                    $var->reference = $reference;

                                } else {
                                    return redirect('quotations/facturar/' . $quotation->id . '/' . $quotation->coin . '')->withDanger('Debe ingresar una Referencia Bancaria!');
                                }
                            } else {
                                return redirect('quotations/facturar/' . $quotation->id . '/' . $quotation->coin . '')->withDanger('Debe seleccionar una Cuenta Bancaria!');
                            }
                        }
                        if ($payment_type == 4) {
                            //DIAS DE CREDITO
                            if (isset($credit_days)) {

                                $var->credit_days = $credit_days;

                            } else {
                                return redirect('quotations/facturar/' . $quotation->id . '/' . $quotation->coin . '')->withDanger('Debe ingresar los Dias de Credito!');
                            }
                        }

                        if ($payment_type == 6) {
                            //DIAS DE CREDITO
                            if (($account_efectivo != 0)) {

                                $var->id_account = $account_efectivo;

                            } else {
                                return redirect('quotations/facturar/' . $quotation->id . '/' . $quotation->coin . '')->withDanger('Debe seleccionar una Cuenta de Efectivo!');
                            }
                        }

                        if ($payment_type == 9 || $payment_type == 10) {
                            //CUENTAS PUNTO DE VENTA
                            if (($account_punto_de_venta != 0)) {
                                $var->id_account = $account_punto_de_venta;
                            } else {
                                return redirect('quotations/facturar/' . $quotation->id . '/' . $quotation->coin . '')->withDanger('Debe seleccionar una Cuenta de Punto de Venta!');
                            }
                        }


                        $var->payment_type = request('payment_type');
                        $var->amount = $valor_sin_formato_amount_pay;


                        if ($coin == 'dolares') {
                            $var->amount = $var->amount * $bcv;
                        }

                        $var->rate = $bcv;

                        $var->status = 1;

                        $total_pay += $valor_sin_formato_amount_pay;

                        $validate_boolean1 = true;


                    } else {
                        return redirect('quotations/facturar/' . $quotation->id . '/' . $quotation->coin . '')->withDanger('Debe seleccionar un Tipo de Pago 1!');
                    }


                } else {
                    return redirect('quotations/facturar/' . $quotation->id . '/' . $quotation->coin . '')->withDanger('El pago debe ser distinto de Cero!');
                }
                /*--------------------------------------------*/
            }
            $payment_type2 = request('payment_type2');
            if ($come_pay >= 2) {

                /*-------------PAGO NUMERO 2----------------------*/

                $var2 = new QuotationPayment();
                $var2->setConnection(Auth::user()->database_name);

                $amount_pay2 = request('amount_pay2');

                if (isset($amount_pay2)) {

                    $valor_sin_formato_amount_pay2 = str_replace(',', '.', str_replace('.', '', $amount_pay2));
                } else {
                    return redirect('quotations/facturar/' . $quotation->id . '/' . $quotation->coin . '')->withDanger('Debe ingresar un monto de pago 2!');
                }


                $account_bank2 = request('account_bank2');
                $account_efectivo2 = request('account_efectivo2');
                $account_punto_de_venta2 = request('account_punto_de_venta2');

                $credit_days2 = request('credit_days2');


                $reference2 = request('reference2');

                if ($valor_sin_formato_amount_pay2 != 0) {

                    if ($payment_type2 != 0) {

                        $var2->id_quotation = request('id_quotation');

                        //SELECCIONA LA CUENTA QUE SE REGISTRA EN EL TIPO DE PAGO
                        if ($payment_type2 == 1 || $payment_type2 == 11 || $payment_type2 == 5) {
                            //CUENTAS BANCARIAS
                            if (($account_bank2 != 0)) {
                                if (isset($reference2)) {

                                    $var2->id_account = $account_bank2;

                                    $var2->reference = $reference2;

                                } else {
                                    return redirect('quotations/facturar/' . $quotation->id . '/' . $quotation->coin . '')->withDanger('Debe ingresar una Referencia Bancaria en pago numero 2!');
                                }
                            } else {
                                return redirect('quotations/facturar/' . $quotation->id . '/' . $quotation->coin . '')->withDanger('Debe seleccionar una Cuenta Bancaria en pago numero 2!');
                            }
                        }
                        if ($payment_type2 == 4) {
                            //DIAS DE CREDITO
                            if (isset($credit_days2)) {

                                $var2->credit_days = $credit_days2;

                            } else {
                                return redirect('quotations/facturar/' . $quotation->id . '/' . $quotation->coin . '')->withDanger('Debe ingresar los Dias de Credito en pago numero 2!');
                            }
                        }

                        if ($payment_type2 == 6) {
                            //DIAS DE CREDITO
                            if (($account_efectivo2 != 0)) {

                                $var2->id_account = $account_efectivo2;

                            } else {
                                return redirect('quotations/facturar/' . $quotation->id . '/' . $quotation->coin . '')->withDanger('Debe seleccionar una Cuenta de Efectivo en pago numero 2!');
                            }
                        }

                        if ($payment_type2 == 9 || $payment_type2 == 10) {
                            //CUENTAS PUNTO DE VENTA
                            if (($account_punto_de_venta2 != 0)) {
                                $var2->id_account = $account_punto_de_venta2;
                            } else {
                                return redirect('quotations/facturar/' . $quotation->id . '/' . $quotation->coin . '')->withDanger('Debe seleccionar una Cuenta de Punto de Venta en pago numero 2!');
                            }
                        }


                        $var2->payment_type = request('payment_type2');
                        $var2->amount = $valor_sin_formato_amount_pay2;

                        if ($coin == 'dolares') {
                            $var2->amount = $var2->amount * $bcv;
                        }
                        $var2->rate = $bcv;

                        $var2->status = 1;

                        $total_pay += $valor_sin_formato_amount_pay2;

                        $validate_boolean2 = true;


                    } else {
                        return redirect('quotations/facturar/' . $quotation->id . '/' . $quotation->coin . '')->withDanger('Debe seleccionar un Tipo de Pago 2!');
                    }


                } else {
                    return redirect('quotations/facturar/' . $quotation->id . '/' . $quotation->coin . '')->withDanger('El pago 2 debe ser distinto de Cero!');
                }
                /*--------------------------------------------*/
            }
            $payment_type3 = request('payment_type3');
            if ($come_pay >= 3) {

                /*-------------PAGO NUMERO 3----------------------*/

                $var3 = new QuotationPayment();
                $var3->setConnection(Auth::user()->database_name);

                $amount_pay3 = request('amount_pay3');

                if (isset($amount_pay3)) {

                    $valor_sin_formato_amount_pay3 = str_replace(',', '.', str_replace('.', '', $amount_pay3));
                } else {
                    return redirect('quotations/facturar/' . $quotation->id . '/' . $quotation->coin . '')->withDanger('Debe ingresar un monto de pago 3!');
                }


                $account_bank3 = request('account_bank3');
                $account_efectivo3 = request('account_efectivo3');
                $account_punto_de_venta3 = request('account_punto_de_venta3');

                $credit_days3 = request('credit_days3');


                $reference3 = request('reference3');

                if ($valor_sin_formato_amount_pay3 != 0) {

                    if ($payment_type3 != 0) {

                        $var3->id_quotation = request('id_quotation');

                        //SELECCIONA LA CUENTA QUE SE REGISTRA EN EL TIPO DE PAGO
                        if ($payment_type3 == 1 || $payment_type3 == 11 || $payment_type3 == 5) {
                            //CUENTAS BANCARIAS
                            if (($account_bank3 != 0)) {
                                if (isset($reference3)) {

                                    $var3->id_account = $account_bank3;

                                    $var3->reference = $reference3;

                                } else {
                                    return redirect('quotations/facturar/' . $quotation->id . '/' . $quotation->coin . '')->withDanger('Debe ingresar una Referencia Bancaria en pago numero 3!');
                                }
                            } else {
                                return redirect('quotations/facturar/' . $quotation->id . '/' . $quotation->coin . '')->withDanger('Debe seleccionar una Cuenta Bancaria en pago numero 3!');
                            }
                        }
                        if ($payment_type3 == 4) {
                            //DIAS DE CREDITO
                            if (isset($credit_days3)) {

                                $var3->credit_days = $credit_days3;

                            } else {
                                return redirect('quotations/facturar/' . $quotation->id . '/' . $quotation->coin . '')->withDanger('Debe ingresar los Dias de Credito en pago numero 3!');
                            }
                        }

                        if ($payment_type3 == 6) {
                            //DIAS DE CREDITO
                            if (($account_efectivo3 != 0)) {

                                $var3->id_account = $account_efectivo3;

                            } else {
                                return redirect('quotations/facturar/' . $quotation->id . '/' . $quotation->coin . '')->withDanger('Debe seleccionar una Cuenta de Efectivo en pago numero 3!');
                            }
                        }

                        if ($payment_type3 == 9 || $payment_type3 == 10) {
                            //CUENTAS PUNTO DE VENTA
                            if (($account_punto_de_venta3 != 0)) {
                                $var3->id_account = $account_punto_de_venta3;
                            } else {
                                return redirect('quotations/facturar/' . $quotation->id . '/' . $quotation->coin . '')->withDanger('Debe seleccionar una Cuenta de Punto de Venta en pago numero 3!');
                            }
                        }


                        $var3->payment_type = request('payment_type3');
                        $var3->amount = $valor_sin_formato_amount_pay3;

                        if ($coin == 'dolares') {
                            $var3->amount = $var3->amount * $bcv;
                        }
                        $var3->rate = $bcv;

                        $var3->status = 1;

                        $total_pay += $valor_sin_formato_amount_pay3;

                        $validate_boolean3 = true;


                    } else {
                        return redirect('quotations/facturar/' . $quotation->id . '/' . $quotation->coin . '')->withDanger('Debe seleccionar un Tipo de Pago 3!');
                    }


                } else {
                    return redirect('quotations/facturar/' . $quotation->id . '/' . $quotation->coin . '')->withDanger('El pago 3 debe ser distinto de Cero!');
                }
                /*--------------------------------------------*/
            }
            $payment_type4 = request('payment_type4');
            if ($come_pay >= 4) {

                /*-------------PAGO NUMERO 4----------------------*/

                $var4 = new QuotationPayment();
                $var4->setConnection(Auth::user()->database_name);

                $amount_pay4 = request('amount_pay4');

                if (isset($amount_pay4)) {

                    $valor_sin_formato_amount_pay4 = str_replace(',', '.', str_replace('.', '', $amount_pay4));
                } else {
                    return redirect('quotations/facturar/' . $quotation->id . '/' . $quotation->coin . '')->withDanger('Debe ingresar un monto de pago 4!');
                }


                $account_bank4 = request('account_bank4');
                $account_efectivo4 = request('account_efectivo4');
                $account_punto_de_venta4 = request('account_punto_de_venta4');

                $credit_days4 = request('credit_days4');


                $reference4 = request('reference4');

                if ($valor_sin_formato_amount_pay4 != 0) {

                    if ($payment_type4 != 0) {

                        $var4->id_quotation = request('id_quotation');

                        //SELECCIONA LA CUENTA QUE SE REGISTRA EN EL TIPO DE PAGO
                        if ($payment_type4 == 1 || $payment_type4 == 11 || $payment_type4 == 5) {
                            //CUENTAS BANCARIAS
                            if (($account_bank4 != 0)) {
                                if (isset($reference4)) {

                                    $var4->id_account = $account_bank4;

                                    $var4->reference = $reference4;

                                } else {
                                    return redirect('quotations/facturar/' . $quotation->id . '/' . $quotation->coin . '')->withDanger('Debe ingresar una Referencia Bancaria en pago numero 4!');
                                }
                            } else {
                                return redirect('quotations/facturar/' . $quotation->id . '/' . $quotation->coin . '')->withDanger('Debe seleccionar una Cuenta Bancaria en pago numero 4!');
                            }
                        }
                        if ($payment_type4 == 4) {
                            //DIAS DE CREDITO
                            if (isset($credit_days4)) {

                                $var4->credit_days = $credit_days4;

                            } else {
                                return redirect('quotations/facturar/' . $quotation->id . '/' . $quotation->coin . '')->withDanger('Debe ingresar los Dias de Credito en pago numero 4!');
                            }
                        }

                        if ($payment_type4 == 6) {
                            //DIAS DE CREDITO
                            if (($account_efectivo4 != 0)) {

                                $var4->id_account = $account_efectivo4;

                            } else {
                                return redirect('quotations/facturar/' . $quotation->id . '/' . $quotation->coin . '')->withDanger('Debe seleccionar una Cuenta de Efectivo en pago numero 4!');
                            }
                        }

                        if ($payment_type4 == 9 || $payment_type4 == 10) {
                            //CUENTAS PUNTO DE VENTA
                            if (($account_punto_de_venta4 != 0)) {
                                $var4->id_account = $account_punto_de_venta4;
                            } else {
                                return redirect('quotations/facturar/' . $quotation->id . '/' . $quotation->coin . '')->withDanger('Debe seleccionar una Cuenta de Punto de Venta en pago numero 4!');
                            }
                        }


                        $var4->payment_type = request('payment_type4');
                        $var4->amount = $valor_sin_formato_amount_pay4;

                        if ($coin == 'dolares') {
                            $var4->amount = $var4->amount * $bcv;
                        }
                        $var4->rate = $bcv;

                        $var4->status = 1;

                        $total_pay += $valor_sin_formato_amount_pay4;

                        $validate_boolean4 = true;


                    } else {
                        return redirect('quotations/facturar/' . $quotation->id . '/' . $quotation->coin . '')->withDanger('Debe seleccionar un Tipo de Pago 4!');
                    }


                } else {
                    return redirect('quotations/facturar/' . $quotation->id . '/' . $quotation->coin . '')->withDanger('El pago 4 debe ser distinto de Cero!');
                }
                /*--------------------------------------------*/
            }
            $payment_type5 = request('payment_type5');
            if ($come_pay >= 5) {

                /*-------------PAGO NUMERO 5----------------------*/

                $var5 = new QuotationPayment();
                $var5->setConnection(Auth::user()->database_name);

                $amount_pay5 = request('amount_pay5');

                if (isset($amount_pay5)) {

                    $valor_sin_formato_amount_pay5 = str_replace(',', '.', str_replace('.', '', $amount_pay5));
                } else {
                    return redirect('quotations/facturar/' . $quotation->id . '/' . $quotation->coin . '')->withDanger('Debe ingresar un monto de pago 5!');
                }


                $account_bank5 = request('account_bank5');
                $account_efectivo5 = request('account_efectivo5');
                $account_punto_de_venta5 = request('account_punto_de_venta5');

                $credit_days5 = request('credit_days5');


                $reference5 = request('reference5');

                if ($valor_sin_formato_amount_pay5 != 0) {

                    if ($payment_type5 != 0) {

                        $var5->id_quotation = request('id_quotation');

                        //SELECCIONA LA CUENTA QUE SE REGISTRA EN EL TIPO DE PAGO
                        if ($payment_type5 == 1 || $payment_type5 == 11 || $payment_type5 == 5) {
                            //CUENTAS BANCARIAS
                            if (($account_bank5 != 0)) {
                                if (isset($reference5)) {

                                    $var5->id_account = $account_bank5;

                                    $var5->reference = $reference5;

                                } else {
                                    return redirect('quotations/facturar/' . $quotation->id . '/' . $quotation->coin . '')->withDanger('Debe ingresar una Referencia Bancaria en pago numero 5!');
                                }
                            } else {
                                return redirect('quotations/facturar/' . $quotation->id . '/' . $quotation->coin . '')->withDanger('Debe seleccionar una Cuenta Bancaria en pago numero 5!');
                            }
                        }
                        if ($payment_type5 == 4) {
                            //DIAS DE CREDITO
                            if (isset($credit_days5)) {

                                $var5->credit_days = $credit_days5;

                            } else {
                                return redirect('quotations/facturar/' . $quotation->id . '/' . $quotation->coin . '')->withDanger('Debe ingresar los Dias de Credito en pago numero 5!');
                            }
                        }

                        if ($payment_type5 == 6) {
                            //DIAS DE CREDITO
                            if (($account_efectivo5 != 0)) {

                                $var5->id_account = $account_efectivo5;

                            } else {
                                return redirect('quotations/facturar/' . $quotation->id . '/' . $quotation->coin . '')->withDanger('Debe seleccionar una Cuenta de Efectivo en pago numero 5!');
                            }
                        }

                        if ($payment_type5 == 9 || $payment_type5 == 10) {
                            //CUENTAS PUNTO DE VENTA
                            if (($account_punto_de_venta5 != 0)) {
                                $var5->id_account = $account_punto_de_venta5;
                            } else {
                                return redirect('quotations/facturar/' . $quotation->id . '/' . $quotation->coin . '')->withDanger('Debe seleccionar una Cuenta de Punto de Venta en pago numero 5!');
                            }
                        }


                        $var5->payment_type = request('payment_type5');
                        $var5->amount = $valor_sin_formato_amount_pay5;

                        if ($coin == 'dolares') {
                            $var5->amount = $var5->amount * $bcv;
                        }

                        $var5->rate = $bcv;

                        $var5->status = 1;

                        $total_pay += $valor_sin_formato_amount_pay5;

                        $validate_boolean5 = true;


                    } else {
                        return redirect('quotations/facturar/' . $quotation->id . '/' . $quotation->coin . '')->withDanger('Debe seleccionar un Tipo de Pago 5!');
                    }


                } else {
                    return redirect('quotations/facturar/' . $quotation->id . '/' . $quotation->coin . '')->withDanger('El pago 5 debe ser distinto de Cero!');
                }
                /*--------------------------------------------*/
            }
            $payment_type6 = request('payment_type6');
            if ($come_pay >= 6) {

                /*-------------PAGO NUMERO 6----------------------*/

                $var6 = new QuotationPayment();
                $var6->setConnection(Auth::user()->database_name);

                $amount_pay6 = request('amount_pay6');

                if (isset($amount_pay6)) {

                    $valor_sin_formato_amount_pay6 = str_replace(',', '.', str_replace('.', '', $amount_pay6));
                } else {
                    return redirect('quotations/facturar/' . $quotation->id . '/' . $quotation->coin . '')->withDanger('Debe ingresar un monto de pago 6!');
                }


                $account_bank6 = request('account_bank6');
                $account_efectivo6 = request('account_efectivo6');
                $account_punto_de_venta6 = request('account_punto_de_venta6');

                $credit_days6 = request('credit_days6');


                $reference6 = request('reference6');

                if ($valor_sin_formato_amount_pay6 != 0) {

                    if ($payment_type6 != 0) {

                        $var6->id_quotation = request('id_quotation');

                        //SELECCIONA LA CUENTA QUE SE REGISTRA EN EL TIPO DE PAGO
                        if ($payment_type6 == 1 || $payment_type6 == 11 || $payment_type6 == 5) {
                            //CUENTAS BANCARIAS
                            if (($account_bank6 != 0)) {
                                if (isset($reference6)) {

                                    $var6->id_account = $account_bank6;

                                    $var6->reference = $reference6;

                                } else {
                                    return redirect('quotations/facturar/' . $quotation->id . '/' . $quotation->coin . '')->withDanger('Debe ingresar una Referencia Bancaria en pago numero 6!');
                                }
                            } else {
                                return redirect('quotations/facturar/' . $quotation->id . '/' . $quotation->coin . '')->withDanger('Debe seleccionar una Cuenta Bancaria en pago numero 6!');
                            }
                        }
                        if ($payment_type6 == 4) {
                            //DIAS DE CREDITO
                            if (isset($credit_days6)) {

                                $var6->credit_days = $credit_days6;

                            } else {
                                return redirect('quotations/facturar/' . $quotation->id . '/' . $quotation->coin . '')->withDanger('Debe ingresar los Dias de Credito en pago numero 6!');
                            }
                        }

                        if ($payment_type6 == 6) {
                            //DIAS DE CREDITO
                            if (($account_efectivo6 != 0)) {

                                $var6->id_account = $account_efectivo6;

                            } else {
                                return redirect('quotations/facturar/' . $quotation->id . '/' . $quotation->coin . '')->withDanger('Debe seleccionar una Cuenta de Efectivo en pago numero 6!');
                            }
                        }

                        if ($payment_type6 == 9 || $payment_type6 == 10) {
                            //CUENTAS PUNTO DE VENTA
                            if (($account_punto_de_venta6 != 0)) {
                                $var6->id_account = $account_punto_de_venta6;
                            } else {
                                return redirect('quotations/facturar/' . $quotation->id . '/' . $quotation->coin . '')->withDanger('Debe seleccionar una Cuenta de Punto de Venta en pago numero 6!');
                            }
                        }


                        $var6->payment_type = request('payment_type6');
                        $var6->amount = $valor_sin_formato_amount_pay6;

                        if ($coin == 'dolares') {
                            $var6->amount = $var6->amount * $bcv;
                        }

                        $var6->rate = $bcv;

                        $var6->status = 1;

                        $total_pay += $valor_sin_formato_amount_pay6;

                        $validate_boolean6 = true;


                    } else {
                        return redirect('quotations/facturar/' . $quotation->id . '/' . $quotation->coin . '')->withDanger('Debe seleccionar un Tipo de Pago 6!');
                    }


                } else {
                    return redirect('quotations/facturar/' . $quotation->id . '/' . $quotation->coin . '')->withDanger('El pago 6 debe ser distinto de Cero!');
                }
                /*--------------------------------------------*/
            }
            $payment_type7 = request('payment_type7');
            if ($come_pay >= 7) {

                /*-------------PAGO NUMERO 7----------------------*/

                $var7 = new QuotationPayment();
                $var7->setConnection(Auth::user()->database_name);

                $amount_pay7 = request('amount_pay7');

                if (isset($amount_pay7)) {

                    $valor_sin_formato_amount_pay7 = str_replace(',', '.', str_replace('.', '', $amount_pay7));
                } else {
                    return redirect('quotations/facturar/' . $quotation->id . '/' . $quotation->coin . '')->withDanger('Debe ingresar un monto de pago 7!');
                }


                $account_bank7 = request('account_bank7');
                $account_efectivo7 = request('account_efectivo7');
                $account_punto_de_venta7 = request('account_punto_de_venta7');

                $credit_days7 = request('credit_days7');


                $reference7 = request('reference7');

                if ($valor_sin_formato_amount_pay7 != 0) {

                    if ($payment_type7 != 0) {

                        $var7->id_quotation = request('id_quotation');

                        //SELECCIONA LA CUENTA QUE SE REGISTRA EN EL TIPO DE PAGO
                        if ($payment_type7 == 1 || $payment_type7 == 11 || $payment_type7 == 5) {
                            //CUENTAS BANCARIAS
                            if (($account_bank7 != 0)) {
                                if (isset($reference7)) {

                                    $var7->id_account = $account_bank7;

                                    $var7->reference = $reference7;

                                } else {
                                    return redirect('quotations/facturar/' . $quotation->id . '/' . $quotation->coin . '')->withDanger('Debe ingresar una Referencia Bancaria en pago numero 7!');
                                }
                            } else {
                                return redirect('quotations/facturar/' . $quotation->id . '/' . $quotation->coin . '')->withDanger('Debe seleccionar una Cuenta Bancaria en pago numero 7!');
                            }
                        }
                        if ($payment_type7 == 4) {
                            //DIAS DE CREDITO
                            if (isset($credit_days7)) {

                                $var7->credit_days = $credit_days7;

                            } else {
                                return redirect('quotations/facturar/' . $quotation->id . '/' . $quotation->coin . '')->withDanger('Debe ingresar los Dias de Credito en pago numero 7!');
                            }
                        }

                        if ($payment_type7 == 6) {
                            //DIAS DE CREDITO
                            if (($account_efectivo7 != 0)) {

                                $var7->id_account = $account_efectivo7;

                            } else {
                                return redirect('quotations/facturar/' . $quotation->id . '/' . $quotation->coin . '')->withDanger('Debe seleccionar una Cuenta de Efectivo en pago numero 7!');
                            }
                        }

                        if ($payment_type7 == 9 || $payment_type7 == 10) {
                            //CUENTAS PUNTO DE VENTA
                            if (($account_punto_de_venta7 != 0)) {
                                $var7->id_account = $account_punto_de_venta7;
                            } else {
                                return redirect('quotations/facturar/' . $quotation->id . '/' . $quotation->coin . '')->withDanger('Debe seleccionar una Cuenta de Punto de Venta en pago numero 7!');
                            }
                        }


                        $var7->payment_type = request('payment_type7');
                        $var7->amount = $valor_sin_formato_amount_pay7;

                        if ($coin == 'dolares') {
                            $var7->amount = $var7->amount * $bcv;
                        }
                        $var7->rate = $bcv;

                        $var7->status = 1;

                        $total_pay += $valor_sin_formato_amount_pay7;

                        $validate_boolean7 = true;


                    } else {
                        return redirect('quotations/facturar/' . $quotation->id . '/' . $quotation->coin . '')->withDanger('Debe seleccionar un Tipo de Pago 7!');
                    }


                } else {
                    return redirect('quotations/facturar/' . $quotation->id . '/' . $quotation->coin . '')->withDanger('El pago 7 debe ser distinto de Cero!');
                }
                /*--------------------------------------------*/
            }

        }


        //VALIDA QUE LA SUMA MONTOS INGRESADOS SEAN IGUALES AL MONTO TOTAL DEL PAGO
        if( $total_venta == $total_pay){


            /*descontamos el inventario, si existe la fecha de nota de entrega, significa que ya hemos descontado del inventario, por ende no descontamos de nuevo*/
            if(!isset($quotation->date_delivery_note)){
                $retorno = $this->discount_inventory($quotation->id);

                if($retorno != "exito"){
                    return redirect('quotations/facturar/'.$quotation->id.'/'.$quotation->coin.'');
                }
            }

            /*---------------- */
                $header_voucher  = new HeaderVoucher();
                $header_voucher->setConnection(Auth::user()->database_name);


                $header_voucher->description = "Cobro de Bienes o servicios.";
                $header_voucher->date = $datenow;


                $header_voucher->status =  "1";

                $header_voucher->save();

            if($validate_boolean1 == true){
                $var->save();

                    $this->add_pay_movement($bcv,$payment_type,$header_voucher->id,$var->id_account,$quotation->id,$user_id,$var->amount,0);


                //LE PONEMOS STATUS C, DE COBRADO
                $quotation->status = "C";
            }

            if($validate_boolean2 == true){
                $var2->save();
                $this->add_pay_movement($bcv,$payment_type2,$header_voucher->id,$var2->id_account,$quotation->id,$user_id,$var2->amount,0);
            }

            if($validate_boolean3 == true){
                $var3->save();
                $this->add_pay_movement($bcv,$payment_type3,$header_voucher->id,$var3->id_account,$quotation->id,$user_id,$var3->amount,0);

            }
            if($validate_boolean4 == true){
                $var4->save();

                $this->add_pay_movement($bcv,$payment_type4,$header_voucher->id,$var4->id_account,$quotation->id,$user_id,$var4->amount,0);

            }
            if($validate_boolean5 == true){
                $var5->save();

                $this->add_pay_movement($bcv,$payment_type5,$header_voucher->id,$var5->id_account,$quotation->id,$user_id,$var5->amount,0);

            }
            if($validate_boolean6 == true){
                $var6->save();

                $this->add_pay_movement($bcv,$payment_type6,$header_voucher->id,$var6->id_account,$quotation->id,$user_id,$var6->amount,0);

            }
            if($validate_boolean7 == true){
                $var7->save();

                $this->add_pay_movement($bcv,$payment_type7,$header_voucher->id,$var7->id_account,$quotation->id,$user_id,$var7->amount,0);

            }


            if($coin != 'bolivares'){
                $anticipo =  $anticipo * $bcv;
                $retencion_iva = $retencion_iva * $bcv;
                $retencion_islr = $retencion_islr * $bcv;

                $sin_formato_amount_iva = $total_iva / $bcv;

                $base_imponible = $base_imponible * $bcv;
                $sin_formato_amount = $sin_formato_amount * $bcv;
                $sin_formato_total_pay = $sin_formato_total_pay * $bcv;

                $sin_formato_grandtotal = $total_venta / $bcv;

                $sub_total = $sub_total * $bcv;

            }

            /*Anticipos*/
            if(isset($anticipo) && ($anticipo != 0)){
                $quotation->anticipo =  $anticipo;

                 $account_anticipo_cliente = Account::on(Auth::user()->database_name)->where('code_one',2)
                                                             ->where('code_two',3)
                                                             ->where('code_three',1)
                                                             ->where('code_four',1)
                                                             ->where('code_five',2)->first();

                 if(isset($account_anticipo_cliente)){
                     $this->add_movement($bcv,$header_voucher->id,$account_anticipo_cliente->id,$quotation->id,$user_id,$quotation->anticipo,0);
                 }
             }else{
                 $quotation->anticipo = 0;
             }
            /*---------- */

            if($retencion_iva !=0){
                $account_iva_retenido = Account::on(Auth::user()->database_name)->where('code_one',1)->where('code_two',1)
                                                        ->where('code_three',4)->where('code_four',1)->where('code_five',2)->first();

                if(isset($account_iva_retenido)){
                    $this->add_movement($bcv,$header_voucher->id,$account_iva_retenido->id,$quotation->id,$user_id,$retencion_iva,0);
                }
            }


            if($retencion_islr !=0){
                $account_islr_pagago = Account::on(Auth::user()->database_name)->where('code_one',1)->where('code_two',1)->where('code_three',4)
                                                ->where('code_four',1)->where('code_five',4)->first();

                if(isset($account_islr_pagago)){
                    $this->add_movement($bcv,$header_voucher->id,$account_islr_pagago->id,$quotation->id,$user_id,$retencion_islr,0);
                }
            }




            //Al final de agregar los movimientos de los pagos, agregamos el monto total de los pagos a cuentas por cobrar clientes
            $account_cuentas_por_cobrar = Account::on(Auth::user()->database_name)->where('description', 'like', 'Cuentas por Cobrar Clientes')->first();

            if(isset($account_cuentas_por_cobrar)){
                $this->add_movement($bcv,$header_voucher->id,$account_cuentas_por_cobrar->id,$quotation->id,$user_id,0,$sin_formato_grandtotal);
            }


            if(($quotation_status != 'C') && ($quotation_status != 'P')){
                //Me busco el ultimo numero en notas de entrega
                $last_number = Quotation::on(Auth::user()->database_name)->where('number_invoice','<>',NULL)->orderBy('number_invoice','desc')->first();


                //Asigno un numero incrementando en 1
                if(isset($last_number)){
                    $quotation->number_invoice = $last_number->number_invoice + 1;
                }else{
                    $quotation->number_invoice = 1;
                }
            }
            $contador           = Quotation::where('serie', '<>', null)->orderBy('serie','desc')->first();
                if($contador <> null){
                    $code = $contador->serie+'1';
                    $serial         = str_pad($code, 8, "0", STR_PAD_LEFT);
                    $serie         = $serial;
                }else{
                    $number = '1';
                    $code = $number+'1';
                    $serial         = str_pad($code, 8, "0", STR_PAD_LEFT);
                    $serie         = $serial;
                }

            /*Modifica la cotizacion */
            $quotation->serie = $serie;
            $quotation->date_billing = $datenow;

            $quotation->base_imponible = $base_imponible;
            $quotation->amount =  $sin_formato_amount;
            $quotation->amount_iva =  $total_iva;
            $quotation->amount_with_iva = $total_venta;
            $quotation->iva_percentage = $iva_percentage;
            $quotation->date_expiration = $fecha_actual;
            $quotation->anticipo =  $anticipo;
            $quotation->retencion_iva = $retencion_iva;
            $quotation->retencion_islr = $retencion_islr;
            $quotation->save();

            /*---------------------- */

            $date = Carbon::now();
            $datenow = $date->format('Y-m-d');

            if(($quotation_status != 'C') && ($quotation_status != 'P')){

                $header_voucher  = new HeaderVoucher();
                $header_voucher->setConnection(Auth::user()->database_name);

                $header_voucher->description = "Ventas de Bienes o servicios.";
                $header_voucher->date = $datenow;


                $header_voucher->status =  "1";

                $header_voucher->save();

                /*Busqueda de Cuentas*/

                //Cuentas por Cobrar Clientes

                $account_cuentas_por_cobrar = Account::on(Auth::user()->database_name)->where('description', 'like', 'Cuentas por Cobrar Clientes')->first();

                if(isset($account_cuentas_por_cobrar)){
                    $this->add_movement($bcv,$header_voucher->id,$account_cuentas_por_cobrar->id,$quotation->id,$user_id,$sin_formato_grandtotal,0);
                }

                //Ingresos por SubSegmento de Bienes

                if($total_mercancia != 0){
                    $account_subsegmento = Account::on(Auth::user()->database_name)->where('description', 'like', 'Ventas por Bienes')->first();

                    if(isset($account_subsegmento)){
                        $this->add_movement($bcv,$header_voucher->id,$account_subsegmento->id,$quotation->id,$user_id,0,$total_mercancia);
                    }
                }

                if($total_servicios != 0){
                    $account_subsegmento = Account::on(Auth::user()->database_name)->where('description', 'like', 'Ventas por Servicios')->first();

                    if(isset($account_subsegmento)){
                        $this->add_movement($bcv,$header_voucher->id,$account_subsegmento->id,$quotation->id,$user_id,0,$total_servicios);
                    }
                }

                //Debito Fiscal IVA por Pagar

                $account_debito_iva_fiscal = Account::on(Auth::user()->database_name)->where('description', 'like', 'Debito Fiscal IVA por Pagar')->first();

                if($base_imponible != 0){
                    $total_iva = ($base_imponible * $iva_percentage)/100;

                    if(isset($account_cuentas_por_cobrar)){
                        $this->add_movement($bcv,$header_voucher->id,$account_debito_iva_fiscal->id,$quotation->id,$user_id,0,$total_iva);
                    }
                }

                //Mercancia para la Venta

                if($price_cost_total != 0){

                    $account_mercancia_venta = Account::on(Auth::user()->database_name)->where('description', 'like', 'Mercancia para la Venta')->first();

                    if(isset($account_cuentas_por_cobrar)){
                        $this->add_movement($bcv,$header_voucher->id,$account_mercancia_venta->id,$quotation->id,$user_id,0,$price_cost_total);
                    }

                    //Costo de Mercancia

                    $account_costo_mercancia = Account::on(Auth::user()->database_name)->where('description', 'like', 'Costo de Mercancía')->first();

                    if(isset($account_cuentas_por_cobrar)){
                        $this->add_movement($bcv,$header_voucher->id,$account_costo_mercancia->id,$quotation->id,$user_id,$price_cost_total,0);
                    }
                }
                /*----------- */
            }
                DB::connection(Auth::user()->database_name)->table('quotation_products')
                                                        ->where('id_quotation', '=', $quotation->id)
                                                        ->update(['status' => 'C']);

            /*Verificamos si el cliente tiene anticipos activos */
                DB::connection(Auth::user()->database_name)->table('anticipos')->where('id_client', '=', $quotation->id_client)
                                                                                ->where(function ($query) use ($quotation){
                                                                                    $query->where('id_quotation',null)
                                                                                        ->orWhere('id_quotation',$quotation->id);
                                                                                })
                                                                                ->where('status', '=', 1)
                                                                                ->update(['status' => 'C']);


                //los que quedaron en espera, pasan a estar activos
                DB::connection(Auth::user()->database_name)->table('anticipos')->where('id_client', '=', $quotation->id_client)
                                                                                ->where(function ($query) use ($quotation){
                                                                                    $query->where('id_quotation',null)
                                                                                        ->orWhere('id_quotation',$quotation->id);
                                                                                })
                                                                                ->where('status', '=', 'M')
                                                                                ->update(['status' => 1]);

            /*------------------------------------------------- */

            return redirect('quotations/facturado/'.$quotation->id.'/'.$coin.'')->withSuccess('Factura Guardada con Exito!');


        }else{
            return redirect('quotations/facturar/'.$quotation->id.'/'.$coin.'')->withDanger('La suma de los pagos es diferente al monto Total a Pagar!');
        }


        }







    public function add_movement($bcv,$id_header,$id_account,$id_invoice,$id_user,$debe,$haber){

        $detail = new DetailVoucher();
        $detail->setConnection(Auth::user()->database_name);


        $detail->id_account = $id_account;
        $detail->id_header_voucher = $id_header;
        $detail->user_id = $id_user;
        $detail->tasa = $bcv;
        $detail->id_invoice = $id_invoice;

      /*  $valor_sin_formato_debe = str_replace(',', '.', str_replace('.', '', $debe));
        $valor_sin_formato_haber = str_replace(',', '.', str_replace('.', '', $haber));*/


        $detail->debe = $debe;
        $detail->haber = $haber;


        $detail->status =  "C";

         /*Le cambiamos el status a la cuenta a M, para saber que tiene Movimientos en detailVoucher */

            $account = Account::on(Auth::user()->database_name)->findOrFail($detail->id_account);

            if($account->status != "M"){
                $account->status = "M";
                $account->save();
            }


        $detail->save();

    }



    public function discount_inventory($id_quotation){
            /*Primero Revisa que todos los productos tengan inventario suficiente*/
            $no_hay_cantidad_suficiente = DB::connection(Auth::user()->database_name)->table('inventories')
                                    ->join('quotation_products', 'quotation_products.id_inventory','=','inventories.id')
                                    ->join('products', 'products.id','=','inventories.product_id')
                                    ->where('quotation_products.id_quotation','=',$id_quotation)
                                    ->where('products.type','LIKE','MERCANCIA')
                                    ->where('quotation_products.amount','<=','inventories.amount')
                                    ->select('inventories.code as code','quotation_products.price as price','quotation_products.rate as rate','quotation_products.id_quotation as id_quotation','quotation_products.discount as discount',
                                    'quotation_products.amount as amount_quotation')
                                    ->first();

            if(isset($no_hay_cantidad_suficiente)){
                return redirect('quotations/facturar/'.$id_quotation.'/bolivares')->withDanger('En el Inventario de Codigo: '.$no_hay_cantidad_suficiente->code.' no hay Cantidad suficiente!');
            }

        /*Luego, descuenta del Inventario*/
            $inventories_quotations = DB::connection(Auth::user()->database_name)->table('products')->join('inventories', 'products.id', '=', 'inventories.product_id')
            ->join('quotation_products', 'inventories.id', '=', 'quotation_products.id_inventory')
            ->where('quotation_products.id_quotation',$id_quotation)
            ->where('products.type','LIKE','MERCANCIA')
            ->select('products.*','quotation_products.price as price','quotation_products.rate as rate','quotation_products.id as id_quotation','quotation_products.discount as discount',
            'quotation_products.amount as amount_quotation')
            ->get();

                foreach($inventories_quotations as $inventories_quotation){

                    $quotation_product = QuotationProduct::on(Auth::user()->database_name)->findOrFail($inventories_quotation->id_quotation);

                    if(isset($quotation_product)){
                    $inventory = Inventory::on(Auth::user()->database_name)->findOrFail($quotation_product->id_inventory);

                        if(isset($inventory)){
                            //REVISO QUE SEA MAYOR EL MONTO DEL INVENTARIO Y LUEGO DESCUENTO
                            if($inventory->amount >= $quotation_product->amount){
                                $inventory->amount -= $quotation_product->amount;
                                $inventory->save();

                                //CAMBIAMOS EL ESTADO PARA SABER QUE ESE PRODUCTO YA SE COBRO Y SE RESTO DEL INVENTARIO
                                $quotation_product->status = 'C';
                                //$quotation_product->price = $inventories_quotation->price;
                                $quotation_product->save();
                            }else{
                                return redirect('quotations/facturar/'.$id_quotation.'/bolivares')->withDanger('El Inventario de Codigo: '.$inventory->code.' no tiene Cantidad suficiente!');
                            }

                        }else{
                            return redirect('quotations/facturar/'.$id_quotation.'/bolivares')->withDanger('El Inventario no existe!');
                        }
                    }else{
                    return redirect('quotations/facturar/'.$id_quotation.'/bolivares')->withDanger('El Inventario de la cotizacion no existe!');
                    }

                }

                return "exito";

    }





    public function add_pay_movement($bcv,$payment_type,$header_voucher,$id_account,$quotation_id,$user_id,$amount_debe,$amount_haber){


            //Cuentas por Cobrar Clientes

                //AGREGA EL MOVIMIENTO DE LA CUENTA CON LA QUE SE HIZO EL PAGO
                if(isset($id_account)){
                    $this->add_movement($bcv,$header_voucher,$id_account,$quotation_id,$user_id,$amount_debe,0);

                }//SIN DETERMINAR
                else if($payment_type == 7){
                            //------------------Sin Determinar
                    $account_sin_determinar = Account::on(Auth::user()->database_name)->where('description', 'like', 'Otros Ingresos No Identificados')->first();

                    if(isset($account_sin_determinar)){
                        $this->add_movement($bcv,$header_voucher,$account_sin_determinar->id,$quotation_id,$user_id,$amount_debe,0);
                    }
                }//PAGO DE CONTADO
                else if($payment_type == 2){

                    $account_contado = Account::on(Auth::user()->database_name)->where('description', 'like', 'Caja Chica')->first();

                    if(isset($account_contado)){
                        $this->add_movement($bcv,$header_voucher,$account_contado->id,$quotation_id,$user_id,$amount_debe,0);
                    }
                }//CONTRA ANTICIPO
                else if($payment_type == 3){
                            //--------------
                    $account_contra_anticipo = Account::on(Auth::user()->database_name)->where('description', 'like', 'Anticipos a Proveedores Nacionales')->first();

                    if(isset($account_contra_anticipo)){
                        $this->add_movement($bcv,$header_voucher,$account_contra_anticipo->id,$quotation_id,$user_id,$amount_debe,0);
                    }
                }
                //Tarjeta Corporativa
               /* else if($payment_type == 8){
                            //---------------
                    $account_contra_anticipo = Account::on(Auth::user()->database_name)->where('description', 'like', 'Tarjeta Corporativa')->first();

                    if(isset($account_contra_anticipo)){
                        $this->add_movement($bcv,$header_voucher,$account_contra_anticipo->id,$quotation_id,$user_id,$amount_debe,0);
                    }
                } */

    }



    public function createfacturado($id_quotation,$coin)
    {
         $quotation = null;

         if(isset($id_quotation)){
             $quotation = Quotation::on(Auth::user()->database_name)->where('date_billing', '<>', null)->find($id_quotation);

         }

         if(isset($quotation)){
                // $product_quotations = QuotationProduct::on(Auth::user()->database_name)->where('id_quotation',$quotation->id)->get();
                $payment_quotations = QuotationPayment::on(Auth::user()->database_name)->where('id_quotation',$quotation->id)->get();




             $date = Carbon::now();
             $datenow = $date->format('Y-m-d');

             if(isset($coin)){
                if($coin == 'bolivares'){
                   $bcv = null;
                }else{
                    $bcv = $quotation->bcv;
                    $quotation->anticipo = $quotation->anticipo;
                }
            }else{
               $bcv = null;
            }







             return view('admin.quotations.createfacturado',compact('quotation','payment_quotations', 'datenow','bcv','coin'));
            }else{
             return redirect('/invoices')->withDanger('La factura no existe');
         }

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
}
