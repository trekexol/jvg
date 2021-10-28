<?php

namespace App\Imports;

use App\ExpensesDetail;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ExpensesImport implements ToModel,WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $user       =   auth()->user();
        $date = Carbon::now();

        return new ExpensesDetail([
            
            'id_expense'        => $row['id_compra'],
            'id_inventory'      => $row['id_inventario'], 
            'id_account'        => $row['id_cuenta'], 
            'id_branch'         => $row['id_sucursal'], 
            'description'       => $row['descripcion'], 
            'exento'            => $row['exento'], 
            'islr'              => $row['islr'], 
            'amount'            => $row['cantidad'], 
            'price'             => $row['precio'], 
            'rate'              => $row['tasa'], 
            'id_user'           => $user->id,
            'status'            => 1,
            'created_at'        => $date,
            'updated_at'        => $date,
        ]);
    }
}
