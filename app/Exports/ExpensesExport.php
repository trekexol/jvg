<?php

namespace App\Exports;


use Maatwebsite\Excel\Concerns\FromCollection;

use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Facades\Excel;

use Maatwebsite\Excel\Concerns\FromArray;

class ExpensesExport implements FromArray
{
    /**
    * @return \Illuminate\Support\Collection
    */
    protected $expenses;

    public function __construct(array $expenses)
    {
        $this->expenses = $expenses;
    }

    public function array(): array
    {
        return $this->expenses;
    }


    
}
