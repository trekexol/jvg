<?php

namespace App\Http\Controllers;



use Carbon\Carbon;
use App;
use App\DetailVoucher;
use App\ExpensePayment;
use App\ExpensesAndPurchase;
use App\MultipaymentExpense;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class PaymentExpenseController extends Controller
{
    public function index()
    {
        
        $user       =   auth()->user();
        $users_role =   $user->role_id;

        $payment_expenses = null;
        
        $date = Carbon::now();
        $datenow = $date->format('Y-m-d');    
        $datebeginyear = $date->firstOfYear()->format('Y-m-d');
        
        $payment_expenses = ExpensePayment::on(Auth::user()->database_name)
                                ->whereRaw(
                                    "(DATE_FORMAT(created_at, '%Y-%m-%d') >= ? AND DATE_FORMAT(created_at, '%Y-%m-%d') <= ?)", 
                                    [$datebeginyear, $datenow])
                                ->where('status',1)->orderBy('created_at','desc')->get();

        foreach($payment_expenses as $payment_expense){

            $type = $this->asignar_payment_type($payment_expense->payment_type);

            $payment_expense->type = $type;
        }
            
        
        return view('admin.payment_expenses.index',compact('datenow','payment_expenses'));
      
    }


    public function movements($id_expense)
    {
        

        $user       =   auth()->user();
        $users_role =   $user->role_id;
        
            $expense = ExpensesAndPurchase::on(Auth::user()->database_name)->find($id_expense);
            $detailvouchers = DetailVoucher::on(Auth::user()->database_name)
                                            ->join('header_vouchers','header_vouchers.id','detail_vouchers.id_header_voucher')
                                            ->where('id_expense',$id_expense)
                                            ->where('header_vouchers.description','LIKE','Pago%')
                                            ->where('detail_vouchers.status','C')
                                            ->get();

            $multipayments_detail = null;
            $expenses = null;
            $coin = $expense->coin;
            $return = "payments";

            //Buscamos a la factura para luego buscar atraves del header a la otras facturas
            $multipayment = MultipaymentExpense::on(Auth::user()->database_name)->where('id_expense',$id_expense)->first();
            if(isset($multipayment)){
            $expenses = MultipaymentExpense::on(Auth::user()->database_name)->where('id_header',$multipayment->id_header)->get();
            $multipayments_detail = DetailVoucher::on(Auth::user()->database_name)->where('id_header_voucher',$multipayment->id_header)->get();
            }

            if(!isset($coin)){
                $coin = 'bolivares';
            }
         
        
        return view('admin.expensesandpurchases.index_movement',compact('return','detailvouchers','expense','coin','expenses','multipayments_detail'));
    }


    public function pdf($id_payment,$coin)
    {
        
        $pdf = App::make('dompdf.wrapper');

        $date = Carbon::now();
        $datenow = $date->format('Y-m-d');    

        $payment = ExpensePayment::on(Auth::user()->database_name)->find($id_payment);
       
        $movements = ExpensesAndPurchase::on(Auth::user()->database_name)
            ->join('detail_vouchers', 'detail_vouchers.id_expense', '=', 'expenses_and_purchases.id')
            ->join('header_vouchers','header_vouchers.id','detail_vouchers.id_header_voucher')
            ->join('accounts','accounts.id','detail_vouchers.id_account')
            ->join('providers','providers.id','expenses_and_purchases.id_provider')
            ->where('expenses_and_purchases.id',$payment->id_expense)
            ->where('header_vouchers.description','LIKE','Pago%')
            ->where('detail_vouchers.status','C')
            ->select('header_vouchers.description', 'header_vouchers.id as header_id',
            'detail_vouchers.debe', 'detail_vouchers.haber', 'detail_vouchers.haber', 'detail_vouchers.tasa',
            'accounts.code_one','accounts.code_two','accounts.code_three','accounts.code_four','accounts.code_five','accounts.description as account_description',
            'providers.razon_social as provider_name','providers.code_provider as provider_code_provider',
            'expenses_and_purchases.id as expense_id','expenses_and_purchases.serie as expense_serie')
            ->get();

        

        
        $type = $this->asignar_payment_type($payment->payment_type);

        $payment->type = $type;
            
        $pdf = $pdf->loadView('admin.payment_expenses.pdf',compact('payment','coin','movements','datenow'));
        return $pdf->stream();
                 
    }


    public function deleteAllPayments(Request $request){

        
        $id_expense = request('id_expense_modal');
        
        $expense = ExpensesAndPurchase::on(Auth::user()->database_name)->findOrFail($id_expense);
        
        if($expense->status != 'X'){
            
            DetailVoucher::on(Auth::user()->database_name)
                    ->join('header_vouchers','header_vouchers.id','detail_vouchers.id_header_voucher')
                    ->where('detail_vouchers.id_expense',$id_expense)
                    ->where('header_vouchers.description','LIKE','Cobro%')
                    ->update(['detail_vouchers.status' => 'X','header_vouchers.status' => 'X']);

                    
            ExpensePayment::on(Auth::user()->database_name)
                            ->where('id_expense',$expense->id)
                            ->update(['status' => 'X']);

            $expense->status = 'P';
            $expense->save();
        }
        
        
        return redirect('payment_expenses/index')->withSuccess('Reverso de Pago Exitoso!');
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


}
