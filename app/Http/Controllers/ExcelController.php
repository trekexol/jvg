<?php

namespace App\Http\Controllers;

use App\Account;
use App\Exports\ExpensesExport;
use App\Imports\ExpensesImport;
use App\Inventory;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Auth;

class ExcelController extends Controller
{
    public function export($id) 
   {
       
       $export = new ExpensesExport([
            ['id_compra', 'id_inventario', 'id_cuenta','id_sucursal','descripcion','exento','islr','cantidad','precio','tasa'],
            [$id]
       ]);
       
       return Excel::download($export, 'plantilla_compras.xlsx');
   }

   public function export_guide_account() 
   {
        $account_inventory = Account::on(Auth::user()->database_name)->select('id','description')
                                ->where('code_one',1)
                                ->where('code_two', 1)
                                ->where('code_three', 3)
                                ->where('code_four',1)
                                ->where('code_five', '<>',0)
                                ->get();
        $account_costo = Account::on(Auth::user()->database_name)->select('id','description')->where('code_one',5)
                                ->where('code_two', '<>',0)
                                ->where('code_three', '<>',0)
                                ->where('code_four', '<>',0)
                                ->where('code_five', '<>',0)->get();
       
        $export = new ExpensesExport([
            ['id_cuenta','Cuenta'],
            $account_inventory,
            $account_costo
       ]);
       
       return Excel::download($export, 'guia_cuentas.xlsx');
   }

   public function export_guide_inventory() 
   {
        $account_inventory = Inventory::on(Auth::user()->database_name)
                                ->join('products','products.id','inventories.product_id')
                                ->select('inventories.id','products.description')
                                ->orderBy('products.description','asc')
                                ->get();
        
       
        $export = new ExpensesExport([
            ['id_inventario','Nombre'],
            $account_inventory
       ]);
       
       return Excel::download($export, 'guia_inventario.xlsx');
   }

   public function import(Request $request) 
   {
       $file = $request->file('file');
       $id_expense = request('id_expense');
       $coin = request('coin_hidde');
       
       Excel::import(new ExpensesImport, $file);
       
       return redirect('expensesandpurchases/register/'.$id_expense.'/'.$coin.'')->with('success', 'Archivo importado con Exito!');
   }
}
