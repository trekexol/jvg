<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NominaConceptTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('nomina_concepts')->insert([
            
            'abbreviation' => 'VAC',
            'order' => '1',
            'description' => 'Vacaciones',
            'type' => 'Especial',
            'sign' => 'A',
            'calculate' => 'S',
            'minimum' => 0,
            'maximum' => 999999999999999999.99,
           
            'status' => '1',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);
        DB::table('nomina_concepts')->insert([
            
            'abbreviation' => 'SUES',
            'order' => '2',
            'description' => 'Sueldo Semanal',
            'type' => 'Especial',
            'sign' => 'A',
            'calculate' => 'S',
            'minimum' => 0,
            'maximum' => 999999999999999999.99,
            'id_formula_q' => 3,
            'id_formula_m' => 3,
            'id_formula_s' => 3,
            'status' => '1',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);
        DB::table('nomina_concepts')->insert([
            
            'abbreviation' => 'SUEQ',
            'order' => '3',
            'description' => 'Sueldo Quincenal',
            'type' => 'Especial',
            'sign' => 'A',
            'calculate' => 'S',
            'minimum' => 0,
            'maximum' => 999999999999999999.99,
            'id_formula_m' => 6,
            'status' => '1',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);
        DB::table('nomina_concepts')->insert([
            
            'abbreviation' => 'SUEM',
            'order' => '4',
            'description' => 'Sueldo Mensual',
            'type' => 'Segunda Quincena',
            'sign' => 'A',
            'calculate' => 'S',
            'minimum' => 0,
            'maximum' => 999999999999999999.99,
            'id_formula_q' => 7,
            'id_formula_m' => 7,
            'id_formula_s' => 7,
            'status' => '1',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);
        DB::table('nomina_concepts')->insert([
            
            'abbreviation' => 'RSAL',
            'order' => '5',
            'description' => 'Retroactivo de Salario',
            'type' => 'Especial',
            'sign' => 'A',
            'calculate' => 'S',
            'minimum' => 0,
            'maximum' => 999999999999999999.99,
            'status' => '1',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);
        DB::table('nomina_concepts')->insert([
            
            'abbreviation' => 'RCT',
            'order' => '6',
            'description' => 'Retroactivo de Cestatiket',
            'type' => 'Especial',
            'sign' => 'A',
            'calculate' => 'S',
            'minimum' => 0,
            'maximum' => 999999999999999999.99,
            'status' => '1',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);
        DB::table('nomina_concepts')->insert([
            
            'abbreviation' => 'RAYU',
            'order' => '7',
            'description' => 'Retroactivo Ayuda Economica',
            'type' => 'Especial',
            'sign' => 'A',
            'calculate' => 'S',
            'minimum' => 0,
            'maximum' => 999999999999999999.99,
            'status' => '1',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);
        DB::table('nomina_concepts')->insert([
            
            'abbreviation' => 'LIQU',
            'order' => '8',
            'description' => 'Liquidacion',
            'type' => 'Especial',
            'sign' => 'A',
            'calculate' => 'S',
            'minimum' => 0,
            'maximum' => 999999999999999999.99,
            'status' => '1',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);
        DB::table('nomina_concepts')->insert([
            
            'abbreviation' => 'HEXT',
            'order' => '9',
            'description' => 'Horas Extras Diurna',
            'type' => 'Especial',
            'sign' => 'A',
            'calculate' => 'N',
            'minimum' => 0,
            'maximum' => 999999999999999999.99,
            'id_formula_q' => 8,
            'id_formula_m' => 8,
            'id_formula_s' => 8,
            'status' => '1',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);
        DB::table('nomina_concepts')->insert([
            
            'abbreviation' => 'HENO',
            'order' => '10',
            'description' => 'Horas Extras Nocturnas',
            'type' => 'Especial',
            'sign' => 'A',
            'calculate' => 'N',
            'minimum' => 0,
            'maximum' => 999999999999999999.99,
            'id_formula_q' => 9,
            'id_formula_m' => 9,
            'id_formula_s' => 9,
            'status' => '1',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);
        DB::table('nomina_concepts')->insert([
            
            'abbreviation' => 'DTRA',
            'order' => '11',
            'description' => 'Dias Trabajados',
            'type' => 'Especial',
            'sign' => 'A',
            'calculate' => 'S',
            'minimum' => 0,
            'maximum' => 999999999999999999.99,
            'id_formula_m' => 10,
            'status' => '1',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);
        DB::table('nomina_concepts')->insert([
            
            'abbreviation' => 'DIF',
            'order' => '12',
            'description' => 'Diferencial',
            'type' => 'Especial',
            'sign' => 'A',
            'calculate' => 'S',
            'minimum' => 0,
            'maximum' => 999999999999999999.99,
            'status' => '1',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);
        DB::table('nomina_concepts')->insert([
            
            'abbreviation' => 'DFTR',
            'order' => '13',
            'description' => 'Dias Feriados',
            'type' => 'Especial',
            'sign' => 'A',
            'calculate' => 'N',
            'minimum' => 0,
            'maximum' => 999999999999999999.99,
            'id_formula_q' => 11,
            'id_formula_m' => 11,
            'id_formula_s' => 11,
            'status' => '1',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);
        DB::table('nomina_concepts')->insert([
            
            'abbreviation' => 'CTS',
            'order' => '14',
            'description' => 'Cestaticket Semanal',
            'type' => 'Especial',
            'sign' => 'A',
            'calculate' => 'S',
            'minimum' => 0,
            'maximum' => 999999999999999999.99,
            'status' => '1',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);
        DB::table('nomina_concepts')->insert([
            
            'abbreviation' => 'CT',
            'order' => '15',
            'description' => 'Cestaticket Quincenal',
            'type' => 'Especial',
            'sign' => 'A',
            'calculate' => 'N',
            'minimum' => 0,
            'maximum' => 999999999999999999.99,
            'id_formula_m' => 12,
           
            'id_formula_q' => 12,
            'status' => '1',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);
        DB::table('nomina_concepts')->insert([
            
            'abbreviation' => 'COMP',
            'order' => '16',
            'description' => 'Complemento',
            'type' => 'Especial',
            'sign' => 'A',
            'calculate' => 'S',
            'minimum' => 0,
            'maximum' => 999999999999999999.99,
            'status' => '1',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);
        DB::table('nomina_concepts')->insert([
            
            'abbreviation' => 'BVAC',
            'order' => '16',
            'description' => 'Bono Vacacional',
            'type' => 'Especial',
            'sign' => 'A',
            'calculate' => 'S',
            'minimum' => 0,
            'maximum' => 999999999999999999.99,
            'status' => '1',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);
        DB::table('nomina_concepts')->insert([
            
            'abbreviation' => 'ANTC',
            'order' => '17',
            'description' => 'Anticipo Primera Quincena',
            'type' => 'Primera Quincena',
            'sign' => 'A',
            'calculate' => 'S',
            'minimum' => 0,
            'maximum' => 999999999999999999.99,
            'id_formula_m' => 6,
            'id_formula_s' => 6,
            'id_formula_q' => 6,
            'status' => '1',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);
        DB::table('nomina_concepts')->insert([
            
            'abbreviation' => 'SSO',
            'order' => '17',
            'description' => 'Seguro Social Obligatorio',
            'type' => 'Quincenal',
            'sign' => 'D',
            'calculate' => 'S',
            'minimum' => 0,
            'maximum' => 999999999999999999.99,
            'id_formula_m' => 1,
            'id_formula_s' => 2,
            'id_formula_q' => 1,
            'status' => '1',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);
        DB::table('nomina_concepts')->insert([
            
            'abbreviation' => 'RIMP',
            'order' => '18',
            'description' => 'Retencion Impuesto sobre la Renta',
            'type' => 'Especial',
            'sign' => 'D',
            'calculate' => 'S',
            'minimum' => 0,
            'maximum' => 999999999999999999.99,
            'id_formula_m' => 13,
            'status' => '1',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);
        DB::table('nomina_concepts')->insert([
            
            'abbreviation' => 'PIE',
            'order' => '19',
            'description' => 'Ley de Regimen Prestacional de empleo',
            'type' => 'Especial',
            'sign' => 'D',
            'calculate' => 'S',
            'minimum' => 0,
            'maximum' => 999999999999999999.99,
            'id_formula_m' => 14,
            'id_formula_s' => 15,
            'id_formula_q' => 14,
            'status' => '1',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);
        DB::table('nomina_concepts')->insert([
            
            'abbreviation' => 'FINJ',
            'order' => '20',
            'description' => 'Faltas Injustificadas',
            'type' => 'Especial',
            'sign' => 'D',
            'calculate' => 'S',
            'minimum' => 0,
            'maximum' => 999999999999999999.99,
            'id_formula_m' => 16,
            'status' => '1',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);
        DB::table('nomina_concepts')->insert([
            
            'abbreviation' => 'FAOV',
            'order' => '21',
            'description' => 'Fondo de Ahorro para la Vivienda',
            'type' => 'Quincenal',
            'sign' => 'D',
            'calculate' => 'S',
            'minimum' => 0,
            'maximum' => 999999999999999999.99,
            'id_formula_m' => 4,
            'id_formula_s' => 5,
            'id_formula_q' => 4,
            'status' => '1',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);
        DB::table('nomina_concepts')->insert([
            
            'abbreviation' => 'DAPQ',
            'order' => '22',
            'description' => 'Fondo de Ahorro para la Vivienda',
            'type' => 'Segunda Quincena',
            'sign' => 'D',
            'calculate' => 'S',
            'minimum' => 0,
            'maximum' => 999999999999999999.99,
            'id_formula_m' => 6,
            'status' => '1',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);
    }
}
