<?php

namespace App\Http\Controllers;

use App\Account;
use App\BankMovement;
use App\BankVoucher;
use App\Branch;
use App\ChargeOrder;
use App\Client;
use App\Company;
use App\DetailVoucher;
use App\HeaderVoucher;
use App\Provider;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DirectChargeOrderController extends Controller
{
    public function create()
   {
        $accounts = DB::connection(Auth::user()->database_name)->table('accounts')->where('code_one', 1)
                                        ->where('code_two', 1)
                                        ->where('code_three', 1)
                                        ->whereIn('code_four', [1,2])
                                        ->where('code_five','<>',0)
                                        ->orderBY('description','asc')->pluck('description','id')->toArray();


        if(isset($accounts)){   

            $contrapartidas     = Account::on(Auth::user()->database_name)->where('code_one', '<>',0)
                                            ->where('code_two', '<>',0)
                                            ->where('code_three', '<>',0)
                                            ->where('code_four', '<>',0)
                                            ->where('code_five', '=',0)
                                        ->orderBY('description','asc')->pluck('description','id')->toArray();
            $date = Carbon::now();
            $datenow = $date->format('Y-m-d');  

            $branches = Branch::on(Auth::user()->database_name)->orderBY('description','asc')->get();

            $coin = 'bolivares';

            /*Revisa si la tasa de la empresa es automatica o fija*/
            $company = Company::on(Auth::user()->database_name)->find(1);
            //Si la taza es automatica
            if($company->tiporate_id == 1){
                $bcv = $this->search_bcv();
            }else{
                //si la tasa es fija
                $bcv = $company->rate;
            }

           return view('admin.directchargeorder.create',compact('accounts','datenow','contrapartidas','branches','bcv','coin'));

        }else{
            return redirect('/directchargeorders')->withDanger('No hay Cuentas!');
       }
   }


    public function store(Request $request)
    {
        
        $data = request()->validate([
            
        
            'account'        =>'required',
            'Subcontrapartida'  =>'required',

            'beneficiario'      =>'required',
            'Subbeneficiario'      =>'required',

            'user_id'           =>'required',
            'amount'            =>'required',
            
            'date'              =>'required',
        
        
        ]);
        
        $account = request('account');
        $contrapartida = request('Subcontrapartida');
        $coin = request('coin');

        if($account != $contrapartida){

            $amount = str_replace(',', '.', str_replace('.', '', request('amount')));
            $rate = str_replace(',', '.', str_replace('.', '', request('rate')));

            if($coin != 'bolivares'){
                $amount = $amount * $rate;
            }

            if($rate == 0){
                return redirect('/directchargeorders')->withDanger('La tasa no puede ser cero!');
            }

            /*$check_amount = $this->check_amount($account);

            se desabilita esta validacion por motivos que el senor nestor queria ingresar datos y que queden en negativo
            if($check_amount->saldo_actual >= $amount){*/

                $charge_order = new ChargeOrder();
                $charge_order->setConnection(Auth::user()->database_name);

                if(request('beneficiario') == 1){
                    $charge_order->id_client = request('Subbeneficiario');
                    
                }else{
                    $charge_order->id_provider = request('Subbeneficiario');
                }
                $charge_order->id_user = request('user_id');

                if(request('branch') != 'ninguno'){
                    $charge_order->id_branch = request('branch');
                }

                $charge_order->date = request('date');
                $charge_order->reference = request('reference');
                $charge_order->description = request('description');
                $charge_order->amount = $amount;
                $charge_order->rate = $rate;
                $charge_order->coin = $coin;

                $charge_order->status = 1;

                $charge_order->save();

                $header = new HeaderVoucher();
                $header->setConnection(Auth::user()->database_name);

                $header->description = "Orden de Cobro ". request('description');
                $header->date = request('date');
                $header->reference = request('reference');
                $header->status =  1;
            
                $header->save();


                $movement = new DetailVoucher();
                $movement->setConnection(Auth::user()->database_name);

                $movement->id_header_voucher = $header->id;
                $movement->id_account = $account;
                $movement->user_id = request('user_id');
                $movement->debe = $amount;
                $movement->haber = 0;
                $movement->tasa = $rate;
                $movement->status = "C";
            
                $movement->save();
                
                $account = Account::on(Auth::user()->database_name)->findOrFail($account);

                if($account->status != "M"){
                    $account->status = "M";
                    $account->save();
                }

                $movement_counterpart = new DetailVoucher();
                $movement_counterpart->setConnection(Auth::user()->database_name);

                $movement_counterpart->id_header_voucher = $header->id;
                $movement_counterpart->id_account = $contrapartida;
                $movement_counterpart->user_id = request('user_id');
                $movement_counterpart->debe = 0;
                $movement_counterpart->haber = $amount;
                $movement_counterpart->tasa = $rate;
                $movement_counterpart->status = "C";

                $movement_counterpart->save();

                $account = Account::on(Auth::user()->database_name)->findOrFail($contrapartida);

                if($account->status != "M"){
                    $account->status = "M";
                    $account->save();
                }

                $account = Account::on(Auth::user()->database_name)->findOrFail($movement->id_account);

                if($account->status != "M"){
                    $account->status = "M";
                    $account->save();
                }

                return redirect('/directchargeorders')->withSuccess('Registro Exitoso!');

           /* }else{
                return redirect('/directchargeorders'.request('id_account').'')->withDanger('El saldo de la Cuenta '.$check_amount->description.' es menor al monto del retiro!');
            }*/

        }else{
            return redirect('/directchargeorders')->withDanger('No se puede hacer un movimiento a la misma cuenta!');
        }
    }

    public function check_amount($id_account)
    {       
        
        $var = Account::on(Auth::user()->database_name)->find($id_account);

                      
       if(isset($var)) {
               
               if($var->code_one != 0){
                   
                   if($var->code_two != 0){
   
   
                       if($var->code_three != 0){
   
   
                           if($var->code_four != 0){
                             
                            /*CALCULA LOS SALDOS DESDE DETALLE COMPROBANTE */                                                   
                            $total_debe = DB::connection(Auth::user()->database_name)->table('accounts')
                                                       ->join('detail_vouchers', 'detail_vouchers.id_account', '=', 'accounts.id')
                                                       ->where('accounts.code_one', $var->code_one)
                                                       ->where('accounts.code_two', $var->code_two)
                                                       ->where('accounts.code_three', $var->code_three)
                                                       ->where('accounts.code_four', $var->code_four)
                                                       ->where('accounts.code_five', $var->code_five)
                                                       ->where('detail_vouchers.status', 'C')
                                                       ->sum('debe');
   
                            $total_haber = DB::connection(Auth::user()->database_name)->table('accounts')
                                                       ->join('detail_vouchers', 'detail_vouchers.id_account', '=', 'accounts.id')
                                                       ->where('accounts.code_one', $var->code_one)
                                                       ->where('accounts.code_two', $var->code_two)
                                                       ->where('accounts.code_three', $var->code_three)
                                                       ->where('accounts.code_four', $var->code_four)
                                                       ->where('accounts.code_five', $var->code_five)
                                                       ->where('detail_vouchers.status', 'C')
                                                       ->sum('haber');   
                            /*---------------------------------------------------*/

                          
                                $var->debe = $total_debe;
                                $var->haber = $total_haber;
  
                                $var->saldo_actual = ($var->balance_previus + $var->debe) - $var->haber;
                                                          
   
                           }else{
                              
                             
                         
                        /*CALCULA LOS SALDOS DESDE DETALLE COMPROBANTE */ 
                           $total_debe = DB::connection(Auth::user()->database_name)->table('accounts')
                                                       ->join('detail_vouchers', 'detail_vouchers.id_account', '=', 'accounts.id')
                                                       ->where('accounts.code_one', $var->code_one)
                                                       ->where('accounts.code_two', $var->code_two)
                                                       ->where('accounts.code_three', $var->code_three)
                                                       ->where('detail_vouchers.status', 'C')
                                                       ->sum('debe');
   
                           $total_haber =  DB::connection(Auth::user()->database_name)->table('accounts')
                                                       ->join('detail_vouchers', 'detail_vouchers.id_account', '=', 'accounts.id')
                                                       ->where('accounts.code_one', $var->code_one)
                                                       ->where('accounts.code_two', $var->code_two)
                                                       ->where('accounts.code_three', $var->code_three)
                                                       ->where('detail_vouchers.status', 'C')
                                                       ->sum('haber');      
                        /*---------------------------------------------------*/                               
  
                        

                        
                            $var->debe = $total_debe;
                        
                            $var->haber = $total_haber;       
                                          
                            $var->saldo_actual = ($var->balance_previus + $var->debe) - $var->haber;
                           
                   }
                       }else{
                           
                      
                        /*CALCULA LOS SALDOS DESDE DETALLE COMPROBANTE */                                   
                           $total_debe = DB::connection(Auth::user()->database_name)->table('accounts')
                                                           ->join('detail_vouchers', 'detail_vouchers.id_account', '=', 'accounts.id')
                                                           ->where('accounts.code_one', $var->code_one)
                                                           ->where('accounts.code_two', $var->code_two)
                                                           ->where('detail_vouchers.status', 'C')
                                                           ->sum('debe');
   
                         
                           $total_haber = DB::connection(Auth::user()->database_name)->table('accounts')
                                                           ->join('detail_vouchers', 'detail_vouchers.id_account', '=', 'accounts.id')
                                                           ->where('accounts.code_one', $var->code_one)
                                                           ->where('accounts.code_two', $var->code_two)
                                                           ->where('detail_vouchers.status', 'C')
                                                           ->sum('haber');
                        /*---------------------------------------------------*/
                                 
                       


                      
                            $var->debe = $total_debe;
                       
                            $var->haber = $total_haber;
                    
                            $var->saldo_actual = ($var->balance_previus + $var->debe) - $var->haber;
                       }
                   }else{
                       //Cuentas NIVEL 2 EJEMPLO 1.0.0.0
                     /*CALCULA LOS SALDOS DESDE DETALLE COMPROBANTE */
                            $total_debe = DB::connection(Auth::user()->database_name)->table('accounts')
                                                       ->join('detail_vouchers', 'detail_vouchers.id_account', '=', 'accounts.id')
                                                       ->where('accounts.code_one', $var->code_one)
                                                       ->where('detail_vouchers.status', 'C')
                                                       ->sum('debe');
   
                        
                          
                           $total_haber = DB::connection(Auth::user()->database_name)->table('accounts')
                                                       ->join('detail_vouchers', 'detail_vouchers.id_account', '=', 'accounts.id')
                                                       ->where('accounts.code_one', $var->code_one)
                                                       ->where('detail_vouchers.status', 'C')
                                                       ->sum('haber');
                    /*---------------------------------------------------*/

                        $var->debe = $total_debe;
                      
                        $var->haber = $total_haber;           
                      
                        $var->saldo_actual = ($var->balance_previus + $var->debe) - $var->haber;
   
                   }
               }else{
                   return redirect('/accounts')->withDanger('El codigo uno es igual a cero!');
               }
           } 
       
      
       
        return $var;
    }

    public function listbeneficiary(Request $request, $id_var = null){
        //validar si la peticion es asincrona
        if($request->ajax()){
            try{
                
                if($id_var == 1){
                    $clients = Client::on(Auth::user()->database_name)->orderBy('name','asc')->get();
                    return response()->json($clients,200);
                }else{
                    $providers = Provider::on(Auth::user()->database_name)->orderBy('razon_social','asc')->get();
                    return response()->json($providers,200);
                }
               
                
                
            }catch(Throwable $th){
                return response()->json(false,500);
            }
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
        }else {
            $titulo = $cap[1][4];
        }

        $bcv_con_formato = $titulo;
        $bcv = str_replace(',', '.', str_replace('.', '',$bcv_con_formato));


        /*-------------------------- */
        return $bcv;

    }


    public function listcontrapartida(Request $request, $id_var = null){
        //validar si la peticion es asincrona
        if($request->ajax()){
            try{

                $account = Account::on(Auth::user()->database_name)->find($id_var);
                $subcontrapartidas = Account::on(Auth::user()->database_name)->select('id','description')->where('code_one',$account->code_one)
                                                                    ->where('code_two',$account->code_two)
                                                                    ->where('code_three',$account->code_three)
                                                                    ->where('code_four',$account->code_four)
                                                                    ->where('code_five','<>',0)
                                                                    ->orderBy('description','asc')->get();
                    
                return response()->json($subcontrapartidas,200);
               
                
            }catch(Throwable $th){
                return response()->json(false,500);
            }
        }
        
    }

}
