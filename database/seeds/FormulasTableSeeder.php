<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\DB;

class FormulasTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('nomina_formulas')->insert([
            'id' => 1,
            'description' => '{{sueldo}} * 12 / 52 * {{lunes}} * 0.04',
            'type' => 'Q',
            'status' => '1',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);
        DB::table('nomina_formulas')->insert([
            'id' => 2,
            'description' => '{{sueldo}} * 12 / 52 * {{lunes}} * 0.04 * 5 / 5',
            'type' => 'S',
            'status' => '1',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);
        DB::table('nomina_formulas')->insert([
            'id' => 3,
            'description' => '{{sueldo}} / 30 * 7.5',
            'type' => 'Q',
            'status' => '1',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);
        DB::table('nomina_formulas')->insert([
            'id' => 4,
            'description' => '{{sueldo}} * 0.01 / 2',
            'type' => 'Q',
            'status' => '1',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);
        DB::table('nomina_formulas')->insert([
            'id' => 5,
            'description' => '{{sueldo}} * 0.01 / 4',
            'type' => 'Q',
            'status' => '1',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);
        DB::table('nomina_formulas')->insert([
            'id' => 6,
            'description' => '{{sueldo}} / 2',
            'type' => 'Q',
            'status' => '1',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);
        DB::table('nomina_formulas')->insert([
            'id' => 7,
            'description' => '{{sueldo}}',
            'type' => 'T',
            'status' => '1',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);
        DB::table('nomina_formulas')->insert([
            'id' => 8,
            'description' => '{{sueldo}} / 30 / 8 * 1.6 / {{horas}}',
            'type' => 'T',
            'status' => '1',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);
        DB::table('nomina_formulas')->insert([
            'id' => 9,
            'description' => '{{sueldo}} / 30 / 8 * 1.8 / {{horas}}',
            'type' => 'T',
            'status' => '1',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);
        DB::table('nomina_formulas')->insert([
            'id' => 10,
            'description' => '{{sueldo}} / 30*1.5 *{{dias}}',
            'type' => 'M',
            'status' => '1',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);
        DB::table('nomina_formulas')->insert([
            'id' => 11,
            'description' => '{{sueldo}} / 30 * 1.5 * {{diasferiados}}',
            'type' => 'T',
            'status' => '1',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);
        DB::table('nomina_formulas')->insert([
            'id' => 12,
            'description' => '{{cestaticket}} / 2',
            'type' => 'T',
            'status' => '1',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);
        DB::table('nomina_formulas')->insert([
            'id' => 13,
            'description' => '{{sueldo}} * 0.03',
            'type' => 'T',
            'status' => '1',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);
        DB::table('nomina_formulas')->insert([
            'id' => 14,
            'description' => '{{sueldo}} * 12 / 52 * {{lunes}} * 0.005',
            'type' => 'T',
            'status' => '1',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);
        DB::table('nomina_formulas')->insert([
            'id' => 15,
            'description' => '{{sueldo}} * 12 / 52 * {{lunes}} * 0.004',
            'type' => 'T',
            'status' => '1',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);
        DB::table('nomina_formulas')->insert([
            'id' => 16,
            'description' => '{{sueldo}} / 30 * {{dias_faltados}}',
            'type' => 'T',
            'status' => '1',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);
       
    }
}
