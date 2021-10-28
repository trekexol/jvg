
  
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="{{asset('vendor/sb-admin/css/sb-admin-2.min.css')}}" rel="stylesheet">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
<title>Prestaciones</title>
<style>
  body{
    background: white;
  }
  table, td, th {
    border: 1px solid black;
    background: white;
  }
  
  table {
    border-collapse: collapse;
    width: 50%;
  }
  
  th {
    
    text-align: left;
  }
  </style>
</head>

<body>


  <br><br><br><br><br><br><br><br><br>
  <div class="text-center h6">RECIBO DE PRESTACIONES ACUMULADAS</div>

 <div class="small">
   
    
    <table style="width: 25%;">
      <tr>
        <th >Fecha: {{ $datenow }}</th>
    </table>

    <table style="width: 100%;">
      <tr>
        <th style="width: 72%; border-right: none;">Nombre de la Empresa: {{ $company->razon_social ?? ''}}</th>
        <th style="width: 28%;" class="font-weight-normal">Rif: {{ $company->code_rif ?? ''}}</th>
      </tr>
    </table>



    <table style="width: 100%;">
      <tr>
        <th style="width: 25%; ">Domicilio Fiscal:</th>
        <th style="width: 75%;" class="font-weight-normal">{{ $company->address ?? ''}}</th>
      </tr>
    </table>

    <table style="width: 100%;">
      <tr>
        <th  class="text-center" style="border-bottom-color: white; width: 25%;">Empleado</th>
        <th  class="text-center">Nombre del Trabajador:</th>
        <th  class="text-center">Cargo</th>
        <th  class="text-center">Cédula</th>
      </tr>
      <tr>
        <td class="text-center font-weight-normal"></td>
        <td class="text-center font-weight-normal">{{ $employee->nombres }} {{ $employee->apellidos}}</td>
        <td class="text-center font-weight-normal">{{ $employee->professions['description'] }}</td>
        <td class="text-center font-weight-normal">{{ $employee->id_empleado }}</td>
      </tr>  
    </table>

    <?php 

    use Carbon\Carbon;

      $datework = Carbon::createFromDate($employee->fecha_ingreso);
      $now = Carbon::now();

      $days = $datework->diffInDays($now);
      $months = $datework->diffInMonths($now);
      $years = $datework->diffInYears($now);

      $humans = $datework->diffForHumans();

      $years_while = $years;

      while($years_while != 0){
        $months -= 12;
        $years_while -= 1;
      }
    
    ?>



    <table style="width: 100%;">
      <tr>
        <th  class="text-center" style="border-bottom-color: #ffffff; width: 25%;">Tiempo de Servicio</th>
        <th  class="text-center">Fecha de Ingreso</th>
        <th  class="text-center">Fecha de Egreso</th>
        <th  class="text-center">Años</th>
        <th  class="text-center">Meses</th>
        <th  class="text-center">Motivo</th>
      </tr>
      <tr>
        <td class="text-center font-weight-normal"></td>
        <td class="text-center font-weight-normal">{{ $employee->fecha_ingreso }}</td>
        <td class="text-center font-weight-normal">{{ $employee->fecha_egreso ?? '' }}</td>
        <td class="text-center font-weight-normal">{{ $years }}</td>
        <td class="text-center font-weight-normal">{{ $months }}</td>
        <td class="text-center font-weight-normal">Prestaciones Sociales</td>
      </tr>  
    </table>


    <?php 



    ?>


    <table style="width: 100%;">
      <tr>
        <th  class="text-center" style="border-bottom-color: #ffffff; width: 25%;">Periodo Actual</th>
        <th  class="text-center">Fecha de último Pago</th>
        <th  class="text-center">Periodo</th>
        <th  class="text-center">Mes</th>
      </tr>
      <tr>
        <td class="text-center font-weight-normal"></td>
        <td class="text-center font-weight-normal">{{ $ultima_nomina->date_begin ?? '' }}</td>
        <td class="text-center font-weight-normal">{{ \Carbon\Carbon::parse($ultima_nomina->date_begin)->format('Y') }}</td>
        <td class="text-center font-weight-normal">{{ \Carbon\Carbon::parse($ultima_nomina->date_begin)->format('M') }}</td>
      </tr>  
    </table>

      <table style="width: 100%;">
        <tr>
          <th  class="text-center" style="border-bottom-color: #ffffff; width: 25%;">Último Salario</th>
          <th  class="text-center">Último Sueldo</th>
          <th  class="text-center">Sueldo Diario</th>
          <th  class="text-center">Alic. Utilidades</th>
          <th  class="text-center">Alic. Vacaciones</th>
          <th  class="text-center">Salario Integral</th>
        </tr>
        <tr>
          <td class="text-center font-weight-normal"></td>
          <td class="text-center font-weight-normal">{{ number_format($employee->monto_pago, 2, ',', '.') }}</td>
          <td class="text-center font-weight-normal">{{ number_format($employee->monto_pago / 30, 2, ',', '.') }}</td>
          <td class="text-center font-weight-normal"></td>
          <td class="text-center font-weight-normal"></td>
          <td class="text-center font-weight-normal"></td>
        </tr>  
      </table>


      <table style="width: 100%;">
        <tr>
          <th  class="text-center" style="border-bottom-color: #ffffff; width: 25%;">Periodo Actual</th>
          <th  class="text-center">Fecha de último Pago</th>
          <th  class="text-center">Periodo</th>
          <th  class="text-center">Mes</th>
        </tr>
        <tr>
          <td class="text-center font-weight-normal"></td>
          <td class="text-center font-weight-normal">{{ $ultima_nomina->date_begin ?? '' }}</td>
          <td class="text-center font-weight-normal">{{ \Carbon\Carbon::parse($ultima_nomina->date_begin)->format('Y') }}</td>
          <td class="text-center font-weight-normal">{{ \Carbon\Carbon::parse($ultima_nomina->date_begin)->format('M') }}</td>
        </tr>  
      </table>
  
        <table style="width: 100%;">
          <tr>
            <th  class="text-center" style="width: 68%;">Descripción</th>
            <th  class="text-center" style="width: 16%;">Monto</th>
            <th  class="text-center" style="width: 16%;">Total</th>
          </tr>
        </table>

        <table style="width: 100%;">
          <tr>
            <th  class="text-left" >1 - PRESTACIONES SOCIALES</th>
          </tr>
        </table>

        <table style="width: 100%;">
          <tr>
            <th  class="text-left font-weight-normal" style="width: 68%;">A - Garantia de Prestaciones Acumuladas</th>
            <th  class="text-center" style="width: 16%;"></th>
            <th  class="text-center" style="width: 16%;"></th>
          </tr>
          <tr>
            <td  class="text-left" style="width: 68%;">B - Prestaciones Sociales LOTTT Art. 142 Literal "C"</td>
            <td  class="text-center" style="width: 16%;"></td>
            <td  class="text-center" style="width: 16%;"></td>
          </tr>
          <tr>
            <td  class="text-left" style="width: 68%;">Total Prestaciones Sociales LOTTT Art. 142 Literal "D". Monto mayor entre A y B</td>
            <td  class="text-center" style="width: 16%;"></td>
            <td  class="text-center" style="width: 16%;"></td>
          </tr>
        </table>

        <table style="width: 100%;">
          <tr>
            <th  class="text-left" >2 - INTERESES SOBRE PRESTACIONES SOCIALES</th>
          </tr>
        </table>

        <table style="width: 100%;">
          <tr>
            <th  class="text-left font-weight-normal" style="width: 68%;">Intereses Garantia Prestaciones LOTTT 2014. Art. 143</th>
            <th  class="text-center" style="width: 16%;"></th>
            <th  class="text-center" style="width: 16%;"></th>
          </tr>
          <tr>
            <td  class="text-left" style="width: 68%;">Total Intereses Garantia Prestaciones LOTTT 2014. Art. 143</td>
            <td  class="text-center" style="width: 16%;"></td>
            <td  class="text-center" style="width: 16%;"></td>
          </tr>
        </table>

        <table style="width: 100%;">
          <tr>
            <th  class="text-left" >3 - TOTAL DE PRESTACIÓNES</th>
          </tr>
        </table>

        <table style="width: 100%;">
          <tr>
            <th  class="text-left font-weight-normal" style="width: 68%;">Total a pagar....</th>
            <th  class="text-center" style="width: 16%;"></th>
            <th  class="text-center" style="width: 16%;"></th>
          </tr>
        </table>

        <div class="small">El suscrito trabajador declara haber recibido de la empresa EMPRESA DEMO C.A. la cantidad de Bolivares  a su entera satisfacción por concepto de pago completo e indemnizaciones, hasta la fecha de la presente liquidación, no teniendo nada
         que reclamar en relación a salarios e indemnizaciones causadas por el contrato de trabajo que hoy queda terminado.
        </div>
      
      <br>
   
     
      <table style="width: 100%;">
        <tr>
          <th  class="text-left font-weight-normal" style="border-color: #ffffff; width: 50%;">__________________________________</th>
          <th  class="text-left" style="border-color: #ffffff; width: 50%;">__________________________________</th>
        </tr>
        <tr>
          <td  class="text-left font-weight-normal" style="border-color: #ffffff; width: 50%;">Empleado: {{ $employee->nombres }} {{ $employee->apellidos }}</td>
          <td  class="text-left" style="border-color: #ffffff; width: 50%;">Testigo</td>
        </tr>
        <tr>
          <td  class="text-left font-weight-normal" style="border-color: #ffffff; width: 50%;"> C.I : {{ $employee->id_empleado }} </td>
          <td  class="text-left" style="border-color: #ffffff; width: 50%;">C.I :</td>
        </tr>
      </table>
      
</div>
 
</body>
</html>
