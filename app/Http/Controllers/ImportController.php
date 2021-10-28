<?php

namespace App\Http\Controllers;

use App\Import;
use App\Product;
use App\Quotation;
use App\ImportDetail;
use App\ExpensesAndPurchase;
use App\ExpensesDetail;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ImportController extends Controller
{

    public function __construct(){

        $this->middleware('auth');
    }

    public function index()
    {

        $user= auth()->user();
        $imports = Import::on(Auth::user()->database_name)->orderBy('id' ,'DESC')->get();
       /* $quotationss = DB::connection(Auth::user()->database_name)
            ->table('expenses_and_purchases')
            ->leftjoin('import_details', 'expenses_and_purchases.id', '=', 'import_details.id_purchases')
            ->select('quotations.*')
            ->whereNull('import_details.id_purchases')
            ->get();*/

        return view('admin.imports.index',compact('imports'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id = null)
    {

        if($id == null){
            $id == null;
        }else{
            $expenses = ExpensesAndPurchase::on(Auth::user()->database_name)->where('id' ,$id)->first();
           $id = $expenses->id;
        }
        return view('admin.imports.create',compact('id'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function cargar()
    {


        $quotationss = DB::connection(Auth::user()->database_name)
            ->table('expenses_and_purchases')
            ->leftjoin('import_details', 'expenses_and_purchases.id', '=', 'import_details.id_purchases')
            ->select('expenses_and_purchases.*')
            ->whereNull('import_details.id_purchases')
            ->get();

        $import_quotationss = DB::connection(Auth::user()->database_name)
            ->table('expenses_and_purchases')
            ->leftjoin('imports', 'expenses_and_purchases.id', '=', 'imports.id_purchases')
            ->select('expenses_and_purchases.*')
            ->whereNull('imports.id_purchases')
            ->get();

        $imports            =   Import::on(Auth::user()->database_name)->get();
        $importDetails      =   ImportDetail::on(Auth::user()->database_name)->get();

        return view('admin.imports.importquotation',compact('quotationss','import_quotationss','importDetails'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function selectquotation($id)
    {
        $quotation    =   ExpensesAndPurchase::on(Auth::user()->database_name)->where('id',$id)->first();

        return redirect()->route('imports.create',$quotation->id);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function selectimport($id)
    {
        $user= auth()->user();
        $import_id          =   Import::on(Auth::user()->database_name)->where('id',$id)->get()->first();
        $imports            =   Import::on(Auth::user()->database_name)->get();
        $importDetails      =   ImportDetail::on(Auth::user()->database_name)->get();


        $quotationss = DB::connection(Auth::user()->database_name)
            ->table('expenses_and_purchases')
            ->leftjoin('import_details', 'expenses_and_purchases.id', '=', 'import_details.id_purchases')
            ->select('expenses_and_purchases.*')
            ->whereNull('import_details.id_purchases')
            ->get();

        return view('admin.imports.importdetails',compact('quotationss','imports','importDetails','import_id'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function cargarDetails($id,$quotation = null)
    {

        if(isset($id)){
            $import     = Import::on(Auth::user()->database_name)->where('id',$id)->get()->first();
            $id_import =  $import->id;
        }

        if(isset($quotation)){
            $quotation     = ExpensesAndPurchase::on(Auth::user()->database_name)->where('id',$quotation)->get()->first();
            $id_quotation =  $quotation->id;
        }

        $ldate = date('Y-m-d');
        $user= auth()->user();
        $imports = new ImportDetail();
        $imports->setConnection(Auth::user()->database_name);

        $imports->id_import       = $id_import;
        $imports->id_purchases    = $id_quotation;
        $imports->fecha           = $ldate;
        $imports->status          = '1';
        $imports->save();




        return redirect('/imports')->withSuccess('Registro de Importacion-Segunradia Exitosa!');


      //  return view('admin.imports.importdetails',compact('quotations','imports','importDetails'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function calcular($id)
    {

        $import             =   Import::on(Auth::user()->database_name)->where('id',$id)->get()->first();

        $import_quotation   =$import->id;
        $import_details     =   ImportDetail::on(Auth::user()->database_name)->where('id_import',$id)->get();

        $import_expense    =   ExpensesAndPurchase::on(Auth::user()->database_name)->where('id', $import->id_purchases)->get()->first();
        $expenses_imports           =   ExpensesDetail::on(Auth::user()->database_name)->where('id_expense', $import_expense->id)->get();
        foreach ($import_details as $import_detail ){

            $expenses           =   ExpensesDetail::on(Auth::user()->database_name)->where('id_expense', $import_detail->id_purchases)->get();

            $inventories_expenses = DB::connection(Auth::user()->database_name)->table('products')->join('inventories', 'products.id', '=', 'inventories.product_id')
                ->join('expenses_details', 'inventories.id', '=', 'expenses_details.id_inventory')
                ->where('expenses_details.id_expense',$import_detail->id_purchases)
                ->select('products.*','expenses_details.price as price','expenses_details.rate as rate',
                    'expenses_details.amount as amount_expense','expenses_details.exento as retiene_iva_expense'
                    ,'expenses_details.islr as retiene_islr_expense')
                ->get();

        }

        $total_import = 0;
        foreach ($expenses_imports as $expenses_import ){
            $total_import += ($expenses_import->price * $expenses_import->amount);

            $inventories_import_expenses = DB::connection(Auth::user()->database_name)->table('products')->join('inventories', 'products.id', '=', 'inventories.product_id')
                ->join('expenses_details', 'inventories.id', '=', 'expenses_details.id_inventory')
                ->where('expenses_details.id_expense',$expenses_import->id)
                ->select('products.*','expenses_details.price as price','expenses_details.rate as rate',
                    'expenses_details.amount as amount_expense','expenses_details.exento as retiene_iva_expense'
                    ,'expenses_details.islr as retiene_islr_expense')
                ->get();
        }



        $total= 0;
        $base_imponible= 0;

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

        $expenses->total_factura = $total;
        $expenses->base_imponible = $base_imponible;


        $resultado = $total + $total_import;

            $date = Carbon::now();
            $datenow = $date->format('Y-m-d');

            return view('admin.imports.importPrincipal',compact('import_quotation','import','import_details','inventories_import_expenses','expenses','expenses_imports','import_expense','resultado','total_import'));

        //  return view('admin.imports.importdetails',compact('quotations','imports','importDetails'));
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
            'Fecha'         =>'required',
        ]);

        $user= auth()->user();
        $imports = new Import();
        $imports->setConnection(Auth::user()->database_name);

        $imports->id_user         = $user->id;
        $imports->id_purchases    = request('Nro_Factura');
        $imports->fecha           = request('Fecha');
        $imports->observaciones   = request('Observaciones');
        $imports->save();

        return redirect('/imports')->withSuccess('Registro de Importacion Exitoso!');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function calcularfiltro(Request $request, $id)
    {

        $imports = Import::on(Auth::user()->database_name)->findOrFail($id);
        $imports->porcentaje_general = request('Precio');
        $imports->save();


        $import             =   Import::on(Auth::user()->database_name)->where('id',$id)->get()->first();

        $import_quotation   =$import->id;
        $import_details     =   ImportDetail::on(Auth::user()->database_name)->where('id_import',$id)->get();

        $import_expense    =   ExpensesAndPurchase::on(Auth::user()->database_name)->where('id', $import->id_purchases)->get()->first();
        $expenses_imports           =   ExpensesDetail::on(Auth::user()->database_name)->where('id_expense', $import_expense->id)->get();

        $expenses_imports2           =   ExpensesDetail::on(Auth::user()->database_name)->where('id_expense', $import_expense->id)->get();

        foreach ($import_details as $import_detail ){

            $expenses           =   ExpensesDetail::on(Auth::user()->database_name)->where('id_expense', $import_detail->id_purchases)->get();

            $inventories_expenses = DB::connection(Auth::user()->database_name)->table('products')->join('inventories', 'products.id', '=', 'inventories.product_id')
                ->join('expenses_details', 'inventories.id', '=', 'expenses_details.id_inventory')
                ->where('expenses_details.id_expense',$import_detail->id_purchases)
                ->select('products.*','expenses_details.price as price','expenses_details.rate as rate',
                    'expenses_details.amount as amount_expense','expenses_details.exento as retiene_iva_expense'
                    ,'expenses_details.islr as retiene_islr_expense')
                ->get();

        }





        $total= 0;
        $base_imponible= 0;

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

        $total_import = 0;
        foreach ($expenses_imports as $expenses_import ){
            $total_import += ($expenses_import->price * $expenses_import->amount);

            $inventories_import_expenses = DB::connection(Auth::user()->database_name)->table('products')->join('inventories', 'products.id', '=', 'inventories.product_id')
                ->join('expenses_details', 'inventories.id', '=', 'expenses_details.id_inventory')
                ->where('expenses_details.id_expense',$expenses_import->id)
                ->select('products.*','expenses_details.price as price','expenses_details.rate as rate',
                    'expenses_details.amount as amount_expense','expenses_details.exento as retiene_iva_expense'
                    ,'expenses_details.islr as retiene_islr_expense')
                ->get();



        }

        $resultado = $total + $total_import;

        foreach ($expenses_imports2 as $expenses_import2 ){

            $inventories_import_expenses2 = DB::connection(Auth::user()->database_name)->table('products')->join('inventories', 'products.id', '=', 'inventories.product_id')
                ->join('expenses_details', 'inventories.id', '=', 'expenses_details.id_inventory')
                ->where('expenses_details.id_expense',$expenses_import2->id)
                ->select('products.*','expenses_details.price as price','expenses_details.rate as rate',
                    'expenses_details.amount as amount_expense','expenses_details.exento as retiene_iva_expense'
                    ,'expenses_details.islr as retiene_islr_expense')
                ->get();


           foreach ($inventories_import_expenses2 as $inventories_import_expenses){
               $venta =  (( $resultado / $total_import )  * $expenses_import2->amount * $expenses_import2->price  / $expenses_import2->amount) +(($resultado / $total_import )  * $expenses_import2->amount * $expenses_import2->price  / $expenses_import2->amount * $import->porcentaje_general   / 100);
               $compra  = ( $resultado / $total_import )  * $expenses_import2->amount * $expenses_import2->price  / $expenses_import2->amount;
               $var = Product::on(Auth::user()->database_name)->findOrFail($inventories_import_expenses->id);
               $var->price =   $venta;
               $var->price_buy = $compra;
               $var->save();
           }
        }


        $expenses->total_factura = $total;
        $expenses->base_imponible = $base_imponible;

        return redirect()->route('imports.calcular',$id);
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
        $var = client::on(Auth::user()->database_name)->find($id);

        $vendors = Vendor::on(Auth::user()->database_name)->orderBy('name','asc')->get();

        return view('admin.clients.edit',compact('var','vendors'));

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

        $vars =  client::on(Auth::user()->database_name)->find($id);
        $vars_status = $vars->status;

        $data = request()->validate([
            'type_code'         =>'required|max:20',

            'razon_social'         =>'required|max:80',
            'cedula_rif'         =>'required|max:20',
            'direction'         =>'required|max:100',

            'city'         =>'required|max:20',
            'country'         =>'required|max:20',
            'phone1'         =>'required|max:20',



            'days_credit'         =>'required|integer',



        ]);


        $users = client::on(Auth::user()->database_name)->findOrFail($id);

        $users->id_vendor = request('id_vendor');

        $users->type_code = request('type_code');

        $users->name = request('razon_social');
        $users->cedula_rif = request('cedula_rif');
        $users->direction = request('direction');
        $users->city = request('city');
        $users->country = request('country');
        $users->phone1 = request('phone1');
        $users->phone2 = request('phone2');

        $users->days_credit = request('days_credit');

        $sin_formato_amount_max_credit = str_replace(',', '.', str_replace('.', '', request('amount_max_credit')));


        $users->amount_max_credit = $sin_formato_amount_max_credit;

        $users->percentage_retencion_iva = request('retencion_iva');
        $users->percentage_retencion_islr = request('retencion_islr');

        if(request('status') == null){
            $users->status = $vars_status;
        }else{
            $users->status = request('status');
        }

        $users->save();

        return redirect('/clients')->withSuccess('Actualizacion Exitosa!');
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
