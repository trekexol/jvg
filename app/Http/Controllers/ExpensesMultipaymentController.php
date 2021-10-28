<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App;
use App\Account;
use App\Branch;
use App\Client;
use App\DetailVoucher;
use App\ExpensePayment;
use App\ExpensesAndPurchase;
use App\ExpensesDetail;
use App\HeaderVoucher;
use App\Inventory;
use App\Provider;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Anticipo;
use App\Company;
use App\IslrConcept;
use App\MultipaymentExpense;
use Illuminate\Support\Facades\Auth;


class ExpensesMultipaymentController extends Controller
{

    public function __construct(){

        $this->middleware('auth');
    }

    
    public function multipayment(Request $request)
    {
        $expense = null;

        //Recorre el request y almacena los valores despues del segundo valor que le llegue, asi guarda los id de las facturas a procesar
        $array = $request->all();
        $count = 0;
        $facturas_a_procesar = [];

        $total_facturas = new ExpensesAndPurchase;
        $total_facturas->setConnection(Auth::user()->database_name);
        
        foreach ($array as $key => $id_expense) {

            if($count >= 2){
                array_push($facturas_a_procesar, $id_expense);
                //$expense = $this->calcularfactura($id_expense);
                $expense = ExpensesAndPurchase::on(Auth::user()->database_name)->findOrFail($id_expense);
                $expense = $this->calculate($expense);

                $total_facturas->anticipo += $expense->anticipo;
                $total_facturas->retencion_iva += $expense->retencion_iva;
                $total_facturas->retencion_islr += $expense->retencion_islr;
                $total_facturas->base_imponible += $expense->base_imponible;
                $total_facturas->amount += $expense->amount;
                $total_facturas->amount_iva += $expense->amount_iva;
                $total_facturas->amount_with_iva += $expense->amount + $expense->amount_iva - $expense->anticipo - $expense->retencion_iva - $expense->retencion_islr;
               
            }
            
            $count ++;
        }
         
        if(empty($facturas_a_procesar)){
            return redirect('expensesandpurchases/indexhistorial')->withDanger('Debe seleccionar facturar para Pagar!');
       }
        $date = Carbon::now();
        $datenow = $date->format('Y-m-d');    

        $accounts_bank = DB::connection(Auth::user()->database_name)->table('accounts')->where('code_one', 1)
                    ->where('code_two', 1)
                    ->where('code_three', 1)
                    ->where('code_four', 2)
                    ->where('code_five', '<>',0)
                    ->where('description','not like', 'Punto de Venta%')
                    ->get();
        $accounts_efectivo = DB::connection(Auth::user()->database_name)->table('accounts')->where('code_one', 1)
                    ->where('code_two', 1)
                    ->where('code_three', 1)
                    ->where('code_four', 1)
                    ->where('code_five', '<>',0)
                    ->get();
        $accounts_punto_de_venta = DB::connection(Auth::user()->database_name)->table('accounts')->where('description','LIKE', 'Punto de Venta%')
                    ->get();

        
        
        return view('admin.expensesandpurchases.create_multipayment',compact('total_facturas',
                 'accounts_bank', 'accounts_efectivo', 'accounts_punto_de_venta'
                ,'datenow','facturas_a_procesar'));
         
         
    }

    public function storemultipayment(Request $request)
    { 
        $data = request()->validate([
            
        ]);
    
        

        $date = Carbon::now();
        $datenow = $date->format('Y-m-d'); 
        
        $total_payments = 0;
        $coin = 'bolivares';
        $bcv = 0;

        //dd($request);
        //Saber cuantos pagos vienen
        $come_pay = request('amount_of_payments');

        //dd($request);
        $user_id = request('user_id');

        /*Validar cuales son los pagos a guardar */
            $validate_boolean1 = false;
            $validate_boolean2 = false;
            $validate_boolean3 = false;
            $validate_boolean4 = false;
            $validate_boolean5 = false;
            $validate_boolean6 = false;
            $validate_boolean7 = false;

        //-----------------------

        $total_factura = str_replace(',', '.', str_replace('.', '', request('total_factura')));
        $base_imponible = str_replace(',', '.', str_replace('.', '', request('base_imponible')));
        $iva_amount = str_replace(',', '.', str_replace('.', '', request('iva_amount')));
        $iva_retencion = str_replace(',', '.', str_replace('.', '', request('iva_retencion')));
        $grand_total = str_replace(',', '.', str_replace('.', '', request('grand_total')));
        $islr_retencion = str_replace(',', '.', str_replace('.', '', request('islr_retencion')));
        $anticipo = str_replace(',', '.', str_replace('.', '', request('anticipo')));
        $total_pay = str_replace(',', '.', str_replace('.', '', request('total_pay')));
        $amount_pay = str_replace(',', '.', str_replace('.', '', request('amount_pay')));
       
        
            $payment_type = request('payment_type');
            if($come_pay >= 1){

                /*-------------PAGO NUMERO 1----------------------*/

                $var = new ExpensePayment();
                $var->setConnection(Auth::user()->database_name);

                $amount_pay = request('amount_pay');
        
                if(isset($amount_pay)){
                    
                    $valor_sin_formato_amount_pay = str_replace(',', '.', str_replace('.', '', $amount_pay));
                }else{
                    return redirect('expensesandpurchases/indexhistorial')->withDanger('Debe ingresar un monto de pago 1!');
                }
                    
        
                $account_bank = request('account_bank');
                $account_efectivo = request('account_efectivo');
                $account_punto_de_venta = request('account_punto_de_venta');
        
                $credit_days = request('credit_days');
        
                
        
                $reference = request('reference');
        
                if($valor_sin_formato_amount_pay != 0){
        
                    if($payment_type != 0){
        
                        $var->id_expense = request('id_expense');
        
                        //SELECCIONA LA CUENTA QUE SE REGISTRA EN EL TIPO DE PAGO
                        if($payment_type == 1 || $payment_type == 11 || $payment_type == 5 ){
                            //CUENTAS BANCARIAS
                            if(($account_bank != 0)){
                                if(isset($reference)){
        
                                    $var->id_account = $account_bank;
        
                                    $var->reference = $reference;
        
                                }else{
                                    return redirect('expensesandpurchases/indexhistorial')->withDanger('Debe ingresar una Referencia Bancaria!');
                                }
                            }else{
                                return redirect('expensesandpurchases/indexhistorial')->withDanger('Debe seleccionar una Cuenta Bancaria!');
                            }
                        }
                        if($payment_type == 4){
                            //DIAS DE CREDITO
                            if(isset($credit_days)){
        
                                $var->credit_days = $credit_days;
        
                            }else{
                                return redirect('expensesandpurchases/indexhistorial')->withDanger('Debe ingresar los Dias de Credito!');
                            }
                        }
        
                        if($payment_type == 6){
                            //DIAS DE CREDITO
                            if(($account_efectivo != 0)){
        
                                $var->id_account = $account_efectivo;
        
                            }else{
                                return redirect('expensesandpurchases/indexhistorial')->withDanger('Debe seleccionar una Cuenta de Efectivo!');
                            }
                        }
        
                        if($payment_type == 9 || $payment_type == 10){
                            //CUENTAS PUNTO DE VENTA
                            if(($account_punto_de_venta != 0)){
                                $var->id_account = $account_punto_de_venta;
                            }else{
                                return redirect('expensesandpurchases/indexhistorial')->withDanger('Debe seleccionar una Cuenta de Punto de Venta!');
                            }
                        }
        
                            
                    
        
                            $var->payment_type = request('payment_type');
                            $var->amount = $valor_sin_formato_amount_pay;
                            
                            if($coin != 'bolivares'){
                                $var->amount = $var->amount * $bcv;
                            }

                            $var->status =  1;
                        
                            $total_payments += $valor_sin_formato_amount_pay;
        
                            $validate_boolean1 = true;
        
                        
                    }else{
                        return redirect('expensesandpurchases/indexhistorial')->withDanger('Debe seleccionar un Tipo de Pago 1!');
                    }
        
                    
                }else{
                        return redirect('expensesandpurchases/indexhistorial')->withDanger('El pago debe ser distinto de Cero!');
                    }
                /*--------------------------------------------*/
            }   
            $payment_type2 = request('payment_type2');
            if($come_pay >= 2){

                /*-------------PAGO NUMERO 2----------------------*/

                $var2 = new ExpensePayment();
                $var2->setConnection(Auth::user()->database_name);

                $amount_pay2 = request('amount_pay2');

                if(isset($amount_pay2)){
                    
                    $valor_sin_formato_amount_pay2 = str_replace(',', '.', str_replace('.', '', $amount_pay2));
                }else{
                    return redirect('expensesandpurchases/indexhistorial')->withDanger('Debe ingresar un monto de pago 2!');
                }
                    

                $account_bank2 = request('account_bank2');
                $account_efectivo2 = request('account_efectivo2');
                $account_punto_de_venta2 = request('account_punto_de_venta2');

                $credit_days2 = request('credit_days2');

                

                $reference2 = request('reference2');

                if($valor_sin_formato_amount_pay2 != 0){

                if($payment_type2 != 0){

                    $var2->id_expense = request('id_expense');

                    //SELECCIONA LA CUENTA QUE SE REGISTRA EN EL TIPO DE PAGO
                    if($payment_type2 == 1 || $payment_type2 == 11 || $payment_type2 == 5 ){
                        //CUENTAS BANCARIAS
                        if(($account_bank2 != 0)){
                            if(isset($reference2)){

                                $var2->id_account = $account_bank2;

                                $var2->reference = $reference2;

                            }else{
                                return redirect('expensesandpurchases/indexhistorial')->withDanger('Debe ingresar una Referencia Bancaria en pago numero 2!');
                            }
                        }else{
                            return redirect('expensesandpurchases/indexhistorial')->withDanger('Debe seleccionar una Cuenta Bancaria en pago numero 2!');
                        }
                    }
                    if($payment_type2 == 4){
                        //DIAS DE CREDITO
                        if(isset($credit_days2)){

                            $var2->credit_days = $credit_days2;

                        }else{
                            return redirect('expensesandpurchases/indexhistorial')->withDanger('Debe ingresar los Dias de Credito en pago numero 2!');
                        }
                    }

                    if($payment_type2 == 6){
                        //DIAS DE CREDITO
                        if(($account_efectivo2 != 0)){

                            $var2->id_account = $account_efectivo2;

                        }else{
                            return redirect('expensesandpurchases/indexhistorial')->withDanger('Debe seleccionar una Cuenta de Efectivo en pago numero 2!');
                        }
                    }

                    if($payment_type2 == 9 || $payment_type2 == 10){
                            //CUENTAS PUNTO DE VENTA
                        if(($account_punto_de_venta2 != 0)){
                            $var2->id_account = $account_punto_de_venta2;
                        }else{
                            return redirect('expensesandpurchases/indexhistorial')->withDanger('Debe seleccionar una Cuenta de Punto de Venta en pago numero 2!');
                        }
                    }

                        
                

                        $var2->payment_type = request('payment_type2');
                        $var2->amount = $valor_sin_formato_amount_pay2;
                        
                        if($coin != 'bolivares'){
                            $var2->amount = $var2->amount * $bcv;
                        }
                        
                        $var2->status =  1;
                    
                        $total_payments += $valor_sin_formato_amount_pay2;

                        $validate_boolean2 = true;

                    
                }else{
                    return redirect('expensesandpurchases/indexhistorial')->withDanger('Debe seleccionar un Tipo de Pago 2!');
                }

                
                }else{
                    return redirect('expensesandpurchases/indexhistorial')->withDanger('El pago 2 debe ser distinto de Cero!');
                }
                /*--------------------------------------------*/
            } 
            $payment_type3 = request('payment_type3');   
            if($come_pay >= 3){

                    /*-------------PAGO NUMERO 3----------------------*/

                    $var3 = new ExpensePayment();
                    $var3->setConnection(Auth::user()->database_name);

                    $amount_pay3 = request('amount_pay3');

                    if(isset($amount_pay3)){
                        
                        $valor_sin_formato_amount_pay3 = str_replace(',', '.', str_replace('.', '', $amount_pay3));
                    }else{
                        return redirect('expensesandpurchases/indexhistorial')->withDanger('Debe ingresar un monto de pago 3!');
                    }
                        

                    $account_bank3 = request('account_bank3');
                    $account_efectivo3 = request('account_efectivo3');
                    $account_punto_de_venta3 = request('account_punto_de_venta3');

                    $credit_days3 = request('credit_days3');

                

                    $reference3 = request('reference3');

                    if($valor_sin_formato_amount_pay3 != 0){

                        if($payment_type3 != 0){

                            $var3->id_expense = request('id_expense');

                            //SELECCIONA LA CUENTA QUE SE REGISTRA EN EL TIPO DE PAGO
                            if($payment_type3 == 1 || $payment_type3 == 11 || $payment_type3 == 5 ){
                                //CUENTAS BANCARIAS
                                if(($account_bank3 != 0)){
                                    if(isset($reference3)){

                                        $var3->id_account = $account_bank3;

                                        $var3->reference = $reference3;

                                    }else{
                                        return redirect('expensesandpurchases/indexhistorial')->withDanger('Debe ingresar una Referencia Bancaria en pago numero 3!');
                                    }
                                }else{
                                    return redirect('expensesandpurchases/indexhistorial')->withDanger('Debe seleccionar una Cuenta Bancaria en pago numero 3!');
                                }
                            }
                            if($payment_type3 == 4){
                                //DIAS DE CREDITO
                                if(isset($credit_days3)){

                                    $var3->credit_days = $credit_days3;

                                }else{
                                    return redirect('expensesandpurchases/indexhistorial')->withDanger('Debe ingresar los Dias de Credito en pago numero 3!');
                                }
                            }

                            if($payment_type3 == 6){
                                //DIAS DE CREDITO
                                if(($account_efectivo3 != 0)){

                                    $var3->id_account = $account_efectivo3;

                                }else{
                                    return redirect('expensesandpurchases/indexhistorial')->withDanger('Debe seleccionar una Cuenta de Efectivo en pago numero 3!');
                                }
                            }

                            if($payment_type3 == 9 || $payment_type3 == 10){
                                //CUENTAS PUNTO DE VENTA
                                if(($account_punto_de_venta3 != 0)){
                                    $var3->id_account = $account_punto_de_venta3;
                                }else{
                                    return redirect('expensesandpurchases/indexhistorial')->withDanger('Debe seleccionar una Cuenta de Punto de Venta en pago numero 3!');
                                }
                            }

                        
                        

                                $var3->payment_type = request('payment_type3');
                                $var3->amount = $valor_sin_formato_amount_pay3;
                                
                                if($coin != 'bolivares'){
                                    $var3->amount = $var3->amount * $bcv;
                                }
                                
                                $var3->status =  1;
                            
                                $total_payments += $valor_sin_formato_amount_pay3;

                                $validate_boolean3 = true;

                            
                        }else{
                            return redirect('expensesandpurchases/indexhistorial')->withDanger('Debe seleccionar un Tipo de Pago 3!');
                        }

                        
                    }else{
                            return redirect('expensesandpurchases/indexhistorial')->withDanger('El pago 3 debe ser distinto de Cero!');
                        }
                    /*--------------------------------------------*/
            }
            $payment_type4 = request('payment_type4');
            if($come_pay >= 4){

                    /*-------------PAGO NUMERO 4----------------------*/

                    $var4 = new expensePayment();
                    $var4->setConnection(Auth::user()->database_name);

                    $amount_pay4 = request('amount_pay4');

                    if(isset($amount_pay4)){
                        
                        $valor_sin_formato_amount_pay4 = str_replace(',', '.', str_replace('.', '', $amount_pay4));
                    }else{
                        return redirect('expensesandpurchases/indexhistorial')->withDanger('Debe ingresar un monto de pago 4!');
                    }
                        

                    $account_bank4 = request('account_bank4');
                    $account_efectivo4 = request('account_efectivo4');
                    $account_punto_de_venta4 = request('account_punto_de_venta4');

                    $credit_days4 = request('credit_days4');

                

                    $reference4 = request('reference4');

                    if($valor_sin_formato_amount_pay4 != 0){

                        if($payment_type4 != 0){

                            $var4->id_expense = request('id_expense');

                            //SELECCIONA LA CUENTA QUE SE REGISTRA EN EL TIPO DE PAGO
                            if($payment_type4 == 1 || $payment_type4 == 11 || $payment_type4 == 5 ){
                                //CUENTAS BANCARIAS
                                if(($account_bank4 != 0)){
                                    if(isset($reference4)){

                                        $var4->id_account = $account_bank4;

                                        $var4->reference = $reference4;

                                    }else{
                                        return redirect('expensesandpurchases/indexhistorial')->withDanger('Debe ingresar una Referencia Bancaria en pago numero 4!');
                                    }
                                }else{
                                    return redirect('expensesandpurchases/indexhistorial')->withDanger('Debe seleccionar una Cuenta Bancaria en pago numero 4!');
                                }
                            }
                            if($payment_type4 == 4){
                                //DIAS DE CREDITO
                                if(isset($credit_days4)){

                                    $var4->credit_days = $credit_days4;

                                }else{
                                    return redirect('expensesandpurchases/indexhistorial')->withDanger('Debe ingresar los Dias de Credito en pago numero 4!');
                                }
                            }

                            if($payment_type4 == 6){
                                //DIAS DE CREDITO
                                if(($account_efectivo4 != 0)){

                                    $var4->id_account = $account_efectivo4;

                                }else{
                                    return redirect('expensesandpurchases/indexhistorial')->withDanger('Debe seleccionar una Cuenta de Efectivo en pago numero 4!');
                                }
                            }

                            if($payment_type4 == 9 || $payment_type4 == 10){
                                //CUENTAS PUNTO DE VENTA
                                if(($account_punto_de_venta4 != 0)){
                                    $var4->id_account = $account_punto_de_venta4;
                                }else{
                                    return redirect('expensesandpurchases/indexhistorial')->withDanger('Debe seleccionar una Cuenta de Punto de Venta en pago numero 4!');
                                }
                            }

                        
                        

                                $var4->payment_type = request('payment_type4');
                                $var4->amount = $valor_sin_formato_amount_pay4;
                                
                                if($coin != 'bolivares'){
                                    $var4->amount = $var4->amount * $bcv;
                                }
                                
                                $var4->status =  1;
                            
                                $total_payments += $valor_sin_formato_amount_pay4;

                                $validate_boolean4 = true;

                            
                        }else{
                            return redirect('expensesandpurchases/indexhistorial')->withDanger('Debe seleccionar un Tipo de Pago 4!');
                        }

                        
                    }else{
                            return redirect('expensesandpurchases/indexhistorial')->withDanger('El pago 4 debe ser distinto de Cero!');
                        }
                    /*--------------------------------------------*/
            } 
            $payment_type5 = request('payment_type5');
            if($come_pay >= 5){

                /*-------------PAGO NUMERO 5----------------------*/

                $var5 = new expensePayment();
                $var5->setConnection(Auth::user()->database_name);

                $amount_pay5 = request('amount_pay5');

                if(isset($amount_pay5)){
                    
                    $valor_sin_formato_amount_pay5 = str_replace(',', '.', str_replace('.', '', $amount_pay5));
                }else{
                    return redirect('expensesandpurchases/indexhistorial')->withDanger('Debe ingresar un monto de pago 5!');
                }
                    

                $account_bank5 = request('account_bank5');
                $account_efectivo5 = request('account_efectivo5');
                $account_punto_de_venta5 = request('account_punto_de_venta5');

                $credit_days5 = request('credit_days5');

            

                $reference5 = request('reference5');

                if($valor_sin_formato_amount_pay5 != 0){

                    if($payment_type5 != 0){

                        $var5->id_expense = request('id_expense');

                        //SELECCIONA LA CUENTA QUE SE REGISTRA EN EL TIPO DE PAGO
                        if($payment_type5 == 1 || $payment_type5 == 11 || $payment_type5 == 5 ){
                            //CUENTAS BANCARIAS
                            if(($account_bank5 != 0)){
                                if(isset($reference5)){

                                    $var5->id_account = $account_bank5;

                                    $var5->reference = $reference5;

                                }else{
                                    return redirect('expensesandpurchases/indexhistorial')->withDanger('Debe ingresar una Referencia Bancaria en pago numero 5!');
                                }
                            }else{
                                return redirect('expensesandpurchases/indexhistorial')->withDanger('Debe seleccionar una Cuenta Bancaria en pago numero 5!');
                            }
                        }
                        if($payment_type5 == 4){
                            //DIAS DE CREDITO
                            if(isset($credit_days5)){

                                $var5->credit_days = $credit_days5;

                            }else{
                                return redirect('expensesandpurchases/indexhistorial')->withDanger('Debe ingresar los Dias de Credito en pago numero 5!');
                            }
                        }

                        if($payment_type5 == 6){
                            //DIAS DE CREDITO
                            if(($account_efectivo5 != 0)){

                                $var5->id_account = $account_efectivo5;

                            }else{
                                return redirect('expensesandpurchases/indexhistorial')->withDanger('Debe seleccionar una Cuenta de Efectivo en pago numero 5!');
                            }
                        }

                        if($payment_type5 == 9 || $payment_type5 == 10){
                            //CUENTAS PUNTO DE VENTA
                            if(($account_punto_de_venta5 != 0)){
                                $var5->id_account = $account_punto_de_venta5;
                            }else{
                                return redirect('expensesandpurchases/indexhistorial')->withDanger('Debe seleccionar una Cuenta de Punto de Venta en pago numero 5!');
                            }
                        }

                    
                    

                            $var5->payment_type = request('payment_type5');
                            $var5->amount = $valor_sin_formato_amount_pay5;
                            
                            if($coin != 'bolivares'){
                                $var5->amount = $var5->amount * $bcv;
                            }
                            
                            $var5->status =  1;
                        
                            $total_payments += $valor_sin_formato_amount_pay5;

                            $validate_boolean5 = true;

                        
                    }else{
                        return redirect('expensesandpurchases/indexhistorial')->withDanger('Debe seleccionar un Tipo de Pago 5!');
                    }

                    
                }else{
                        return redirect('expensesandpurchases/indexhistorial')->withDanger('El pago 5 debe ser distinto de Cero!');
                    }
                /*--------------------------------------------*/
            } 
            $payment_type6 = request('payment_type6');
            if($come_pay >= 6){

                /*-------------PAGO NUMERO 6----------------------*/

                $var6 = new expensePayment();
                $var6->setConnection(Auth::user()->database_name);

                $amount_pay6 = request('amount_pay6');

                if(isset($amount_pay6)){
                    
                    $valor_sin_formato_amount_pay6 = str_replace(',', '.', str_replace('.', '', $amount_pay6));
                }else{
                    return redirect('expensesandpurchases/indexhistorial')->withDanger('Debe ingresar un monto de pago 6!');
                }
                    

                $account_bank6 = request('account_bank6');
                $account_efectivo6 = request('account_efectivo6');
                $account_punto_de_venta6 = request('account_punto_de_venta6');

                $credit_days6 = request('credit_days6');

                

                $reference6 = request('reference6');

                if($valor_sin_formato_amount_pay6 != 0){

                    if($payment_type6 != 0){

                        $var6->id_expense = request('id_expense');

                        //SELECCIONA LA CUENTA QUE SE REGISTRA EN EL TIPO DE PAGO
                        if($payment_type6 == 1 || $payment_type6 == 11 || $payment_type6 == 5 ){
                            //CUENTAS BANCARIAS
                            if(($account_bank6 != 0)){
                                if(isset($reference6)){

                                    $var6->id_account = $account_bank6;

                                    $var6->reference = $reference6;

                                }else{
                                    return redirect('expensesandpurchases/indexhistorial')->withDanger('Debe ingresar una Referencia Bancaria en pago numero 6!');
                                }
                            }else{
                                return redirect('expensesandpurchases/indexhistorial')->withDanger('Debe seleccionar una Cuenta Bancaria en pago numero 6!');
                            }
                        }
                        if($payment_type6 == 4){
                            //DIAS DE CREDITO
                            if(isset($credit_days6)){

                                $var6->credit_days = $credit_days6;

                            }else{
                                return redirect('expensesandpurchases/indexhistorial')->withDanger('Debe ingresar los Dias de Credito en pago numero 6!');
                            }
                        }

                        if($payment_type6 == 6){
                            //DIAS DE CREDITO
                            if(($account_efectivo6 != 0)){

                                $var6->id_account = $account_efectivo6;

                            }else{
                                return redirect('expensesandpurchases/indexhistorial')->withDanger('Debe seleccionar una Cuenta de Efectivo en pago numero 6!');
                            }
                        }

                        if($payment_type6 == 9 || $payment_type6 == 10){
                            //CUENTAS PUNTO DE VENTA
                            if(($account_punto_de_venta6 != 0)){
                                $var6->id_account = $account_punto_de_venta6;
                            }else{
                                return redirect('expensesandpurchases/indexhistorial')->withDanger('Debe seleccionar una Cuenta de Punto de Venta en pago numero 6!');
                            }
                        }

                    
                    

                            $var6->payment_type = request('payment_type6');
                            $var6->amount = $valor_sin_formato_amount_pay6;

                            if($coin != 'bolivares'){
                                $var6->amount = $var6->amount * $bcv;
                            }
                            
                            $var6->status =  1;
                        
                            $total_payments += $valor_sin_formato_amount_pay6;

                            $validate_boolean6 = true;

                        
                    }else{
                        return redirect('expensesandpurchases/indexhistorial')->withDanger('Debe seleccionar un Tipo de Pago 6!');
                    }

                    
                }else{
                        return redirect('expensesandpurchases/indexhistorial')->withDanger('El pago 6 debe ser distinto de Cero!');
                    }
                /*--------------------------------------------*/
            } 
            $payment_type7 = request('payment_type7');
            if($come_pay >= 7){

                /*-------------PAGO NUMERO 7----------------------*/

                $var7 = new expensePayment();
                $var7->setConnection(Auth::user()->database_name);

                $amount_pay7 = request('amount_pay7');

                if(isset($amount_pay7)){
                    
                    $valor_sin_formato_amount_pay7 = str_replace(',', '.', str_replace('.', '', $amount_pay7));
                }else{
                    return redirect('expensesandpurchases/indexhistorial')->withDanger('Debe ingresar un monto de pago 7!');
                }
                    

                $account_bank7 = request('account_bank7');
                $account_efectivo7 = request('account_efectivo7');
                $account_punto_de_venta7 = request('account_punto_de_venta7');

                $credit_days7 = request('credit_days7');

                

                $reference7 = request('reference7');

                if($valor_sin_formato_amount_pay7 != 0){

                    if($payment_type7 != 0){

                        $var7->id_expense = request('id_expense');

                        //SELECCIONA LA CUENTA QUE SE REGISTRA EN EL TIPO DE PAGO
                        if($payment_type7 == 1 || $payment_type7 == 11 || $payment_type7 == 5 ){
                            //CUENTAS BANCARIAS
                            if(($account_bank7 != 0)){
                                if(isset($reference7)){

                                    $var7->id_account = $account_bank7;

                                    $var7->reference = $reference7;

                                }else{
                                    return redirect('expensesandpurchases/indexhistorial')->withDanger('Debe ingresar una Referencia Bancaria en pago numero 7!');
                                }
                            }else{
                                return redirect('expensesandpurchases/indexhistorial')->withDanger('Debe seleccionar una Cuenta Bancaria en pago numero 7!');
                            }
                        }
                        if($payment_type7 == 4){
                            //DIAS DE CREDITO
                            if(isset($credit_days7)){

                                $var7->credit_days = $credit_days7;

                            }else{
                                return redirect('expensesandpurchases/indexhistorial')->withDanger('Debe ingresar los Dias de Credito en pago numero 7!');
                            }
                        }

                        if($payment_type7 == 6){
                            //DIAS DE CREDITO
                            if(($account_efectivo7 != 0)){

                                $var7->id_account = $account_efectivo7;

                            }else{
                                return redirect('expensesandpurchases/indexhistorial')->withDanger('Debe seleccionar una Cuenta de Efectivo en pago numero 7!');
                            }
                        }

                        if($payment_type7 == 9 || $payment_type7 == 10){
                            //CUENTAS PUNTO DE VENTA
                            if(($account_punto_de_venta7 != 0)){
                                $var7->id_account = $account_punto_de_venta7;
                            }else{
                                return redirect('expensesandpurchases/indexhistorial')->withDanger('Debe seleccionar una Cuenta de Punto de Venta en pago numero 7!');
                            }
                        }

                    
                    

                            $var7->payment_type = request('payment_type7');
                            $var7->amount = $valor_sin_formato_amount_pay7;
                            
                            if($coin != 'bolivares'){
                                $var7->amount = $var7->amount * $bcv;
                            }
                            
                            $var7->status =  1;
                        
                            $total_payments += $valor_sin_formato_amount_pay7;

                            $validate_boolean7 = true;

                        
                    }else{
                        return redirect('expensesandpurchases/indexhistorial')->withDanger('Debe seleccionar un Tipo de Pago 7!');
                    }

                    
                }else{
                        return redirect('expensesandpurchases/indexhistorial')->withDanger('El pago 7 debe ser distinto de Cero!');
                    }
                /*--------------------------------------------*/
            } 
            //dd($total_pay);
            //VALIDA QUE LA SUMA MONTOS INGRESADOS SEAN IGUALES AL MONTO TOTAL DEL PAGO
            if($total_payments == $total_pay)
            {
                //dd($request);
                $array = $request->all();
                $id_expense = 0;
                $facturas_a_procesar = [];

                foreach ($array as $key => $item) {
                    
                    if(substr($key,0, 10) == 'id_expense'){
                        array_push($facturas_a_procesar, $item);
                        $this->procesar_expense($item);
                        $id_expense = $item;
                    }
                    
                }
            
                $expense = ExpensesAndPurchase::on(Auth::user()->database_name)->findOrFail($id_expense);

                $header_voucher  = new HeaderVoucher();
                $header_voucher->setConnection(Auth::user()->database_name);


                $header_voucher->description = "Pago de Bienes o servicios.";
                $header_voucher->date = $datenow;
                
            
                $header_voucher->status =  "1";
            
                $header_voucher->save();

                /* if($coin != 'bolivares'){
                    $total_factura = $total_factura * $bcv;
                    $base_imponible = $base_imponible * $bcv;
                    $iva_amount = $iva_amount * $bcv;
                    $iva_retencion = $iva_retencion * $bcv;
                    $grand_total = $grand_total * $bcv;
                    $islr_retencion = $islr_retencion * $bcv;
                    $anticipo =  $anticipo * $bcv;
                    $total_pay =  $total_pay * $bcv;
                    $amount_pay =  $amount_pay * $bcv;
                    
                }
                */

                if($validate_boolean1 == true){
                    $var->save();

                    foreach($facturas_a_procesar as $key => $id_factura){
                        $this->register_multipayment($id_factura,$header_voucher->id,$var->id,$user_id);
                    }

                    $this->add_pay_movement($bcv,$payment_type,$header_voucher->id,$var->id_account,$user_id,0,$var->amount);
                    
                   
                }
                
                if($validate_boolean2 == true){
                    $var2->save();
                
                    $this->add_pay_movement($bcv,$payment_type2,$header_voucher->id,$var2->id_account,$user_id,0,$var2->amount);
                    
                }
                
                if($validate_boolean3 == true){
                    $var3->save();

                    $this->add_pay_movement($bcv,$payment_type3,$header_voucher->id,$var3->id_account,$user_id,0,$var3->amount);
                
                    
                }
                if($validate_boolean4 == true){
                    $var4->save();

                    $this->add_pay_movement($bcv,$payment_type4,$header_voucher->id,$var4->id_account,$user_id,0,$var4->amount);
                
                }
                if($validate_boolean5 == true){
                    $var5->save();

                    $this->add_pay_movement($bcv,$payment_type5,$header_voucher->id,$var5->id_account,$user_id,0,$var5->amount);
                
                }
                if($validate_boolean6 == true){
                    $var6->save();

                    $this->add_pay_movement($bcv,$payment_type6,$header_voucher->id,$var6->id_account,$user_id,0,$var6->amount);
                
                }
                if($validate_boolean7 == true){
                    $var7->save();

                    $this->add_pay_movement($bcv,$payment_type7,$header_voucher->id,$var7->id_account,$user_id,0,$var7->amount);
                
                }

                /*Anticipos*/
                if(isset($anticipo) && ($anticipo != 0)){
                    $expense->anticipo =  $anticipo;
                    
                    $account_anticipo_cliente = Account::on(Auth::user()->database_name)->where('code_one',1)
                                                                ->where('code_two',1)
                                                                ->where('code_three',4)
                                                                ->where('code_four',2)
                                                                ->where('code_five',1)->first(); 
                    
                    if(isset($account_anticipo_cliente)){
                        $this->add_movement($bcv,$header_voucher->id,$account_anticipo_cliente->id,$user_id,0,$expense->anticipo);
                    }
                   
                }else{
                    $expense->anticipo = 0;
                }
                /*---------- */
                /*Se agregan los movimientos de las retenciones si son diferentes a cero */

                if($iva_retencion !=0){
                    $account_iva_retenido = Account::on(Auth::user()->database_name)->where('code_one',1)->where('code_two',1)
                                                            ->where('code_three',4)->where('code_four',1)->where('code_five',2)->first();  

                    if(isset($account_iva_retenido)){
                        $this->add_movement($bcv,$header_voucher->id,$account_iva_retenido->id,$user_id,0,$iva_retencion);
                    }
                }


                if($islr_retencion !=0){
                    $account_islr_pagago = Account::on(Auth::user()->database_name)->where('code_one',1)->where('code_two',1)->where('code_three',4)
                                                    ->where('code_four',1)->where('code_five',4)->first();  

                    if(isset($account_islr_pagago)){
                        $this->add_movement($bcv,$header_voucher->id,$account_islr_pagago->id,$user_id,0,$islr_retencion);
                    }
                }
                
                /*------------------------------- */

                //Al final de agregar los movimientos de los pagos, agregamos el monto total de los pagos a cuentas por cobrar clientes
                $account_cuentas_por_pagar_proveedores = Account::on(Auth::user()->database_name)->where('description', 'like', 'Cuentas por Pagar Proveedores')->first(); 
                    
                if(isset($account_cuentas_por_pagar_proveedores)){
                    $this->add_movement($bcv,$header_voucher->id,$account_cuentas_por_pagar_proveedores->id,$user_id,$grand_total,0);
                }

                return redirect('expensesandpurchases/indexhistorial')->withSuccess('Gastos o Compras Guardadas con Exito!');

            }else{
                return redirect('expensesandpurchases/indexhistorial')->withDanger('La suma de los pagos es diferente al monto Total a Pagar!');
            }

    }

    public function procesar_expense($id_expense)
    {
        $expense = ExpensesAndPurchase::on(Auth::user()->database_name)->findOrFail($id_expense);

        /*Verificamos si el proveedor tiene anticipos activos */
            DB::connection(Auth::user()->database_name)->table('anticipos')
            ->where('id_provider', '=', $expense->id_provider)
            ->where('id_expense',null)
            ->orWhere('id_expense',$expense->id)
            ->where('status', '=', '1')
            ->update(['status' => 'C']);

            //los que quedaron en espera, pasan a estar activos
            DB::connection(Auth::user()->database_name)->table('anticipos')
            ->where('id_provider', '=', $expense->id_provider)
            ->where('id_expense',null)
            ->orWhere('id_expense',$expense->id)
            ->where('status', '=', 'M')
            ->update(['status' => '1']);
        /*------------------------------------------------- */
        
        $expense->status = 'C';
        $expense->save();

        return true;
    }

    public function register_multipayment($id_expense,$id_header,$id_payment,$id_user)
    {
        $multipayment = new MultipaymentExpense();
        $multipayment->setConnection(Auth::user()->database_name);
        $multipayment->id_expense = $id_expense;
        $multipayment->id_header = $id_header;
        $multipayment->id_payment = $id_payment;
        $multipayment->id_user = $id_user;

        $multipayment->save();
    }

    public function add_movement($bcv,$id_header,$id_account,$id_user,$debe,$haber)
    {

        $detail = new DetailVoucher();
        $detail->setConnection(Auth::user()->database_name);

        $detail->id_account = $id_account;
        $detail->id_header_voucher = $id_header;
        $detail->user_id = $id_user;
        $detail->tasa = $bcv;

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

    public function add_pay_movement($bcv,$payment_type,$header_voucher,$id_account,$user_id,$amount_debe,$amount_haber)
    {


        //Cuentas por Cobrar Clientes

            //AGREGA EL MOVIMIENTO DE LA CUENTA CON LA QUE SE HIZO EL PAGO
            if(isset($id_account)){
                $this->add_movement($bcv,$header_voucher,$id_account,$user_id,$amount_debe,$amount_haber);
            
            }//SIN DETERMINAR
            else if($payment_type == 7){
                
                $account_sin_determinar = Account::on(Auth::user()->database_name)->where('description', 'like', 'Otros Ingresos No Identificados')->first(); 
        
                if(isset($account_sin_determinar)){
                    $this->add_movement($bcv,$header_voucher,$account_sin_determinar->id,$user_id,$amount_debe,$amount_haber);
                }
            }//PAGO DE CONTADO
            else if($payment_type == 2){
                
                $account_contado = Account::on(Auth::user()->database_name)->where('description', 'like', 'Caja Chica')->first(); 
        
                if(isset($account_contado)){
                    $this->add_movement($bcv,$header_voucher,$account_contado->id,$user_id,$amount_debe,$amount_haber);
                }
            }//CONTRA ANTICIPO
            else if($payment_type == 3){
                
                $account_contra_anticipo = Account::on(Auth::user()->database_name)->where('description', 'like', 'Anticipos a Proveedores Nacionales')->first(); 
        
                if(isset($account_contra_anticipo)){
                    $this->add_movement($bcv,$header_voucher,$account_contra_anticipo->id,$user_id,$amount_debe,$amount_haber);
                }
            } 
           

    }

    
    public function calculate($expense)
    {
        
        $anticipos_sum_bolivares = Anticipo::on(Auth::user()->database_name)->where('status',1)
                                                ->where('id_provider',$expense->id_provider)
                                                ->where('id_expense',null)
                                                ->orWhere('id_expense',$expense->id)
                                                ->where('coin','like','bolivares')
                                                ->sum('amount');

        $total_dolar_anticipo =    DB::connection(Auth::user()->database_name)->select('SELECT SUM(amount/rate) AS dolar
                                        FROM anticipos
                                        WHERE id_provider = ? AND
                                        id_expense = null OR
                                        id_expense = ? AND
                                        coin not like ? AND
                                        status = ?
                                        '
                                        , [$expense->id_provider,$expense->id,'bolivares',1]);

        $anticipos_sum_dolares = 0;
        if(isset($total_dolar_anticipo[0]->dolar)){
            $anticipos_sum_dolares = $total_dolar_anticipo[0]->dolar;
            
        }
        

        $anticipos_sum = 0;
            
        //Si la factura es en BS, y tengo anticipos en dolares, los multiplico los dolares por la tasa a la que estoy facturando
        $anticipos_sum_dolares =  $anticipos_sum_dolares * $expense->rate;
        $anticipos_sum = $anticipos_sum_bolivares + $anticipos_sum_dolares; 
    

        $expense->anticipo = $anticipos_sum;
        
        return $expense;
    }
    
}
