
  
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
 
<title>Calculos del Empleado</title>
<style>
  table, td, th {
    border: 1px solid black;
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
  <h5 style="color: black">{{ $nomina->description }}</h5>
  <h5 style="color: black">fecha desde: {{ \Carbon\Carbon::parse($nomina->date_begin)->format('d-m-Y')}} , Fecha hasta {{ \Carbon\Carbon::parse($nomina->date_end)->format('d-m-Y') }}</h5>
  <h5 style="color: black">ID Empleado: {{ $employee->id_empleado }} , Nombre del Empleado: {{ $employee->nombres }} {{ $employee->apellidos }} , Fecha de Ingreso: {{ \Carbon\Carbon::parse($employee->fecha_ingreso)->format('d-m-Y') }}</h5>
  <h5 style="color: black">Número de Nómina: {{ str_pad($nomina->id, 6, "0", STR_PAD_LEFT) }}</h5>

 
<table style="width: 100%;">
  <tr>
    <th style="text-align: center;">Cod.</th>
    <th style="text-align: center;">Descripción</th>
    <th style="text-align: center;">Variantes</th>
    <th style="text-align: center;">Asignación</th>
    <th style="text-align: center;">Deducción</th>
   
  </tr>
  <?php 
    $total_asignacion = 0;
    $total_deduccion = 0;
  ?>
  @foreach ($nomina_calculation as $var)
  <?php 
    $hours = "";
    if(isset($var->hours)){
      if($var->hours != 0){
        $hours = $var->hours." horas";
      }
    }
    $days = "";
    if(isset($var->days)){
      if($var->days != 0){
        $days = $var->days." dias";
      }
    }
    $cantidad = "";
    if(isset($var->cantidad)){
      if($var->cantidad != 0){
        $cantidad = $var->cantidad." cantidad";
      }
    } 

  ?>
    <tr>
      <td style="text-align: center;">{{ $var->nominaconcepts['abbreviation'] }}</td>
      <td style="text-align: center;">{{ $var->nominaconcepts['description'] }}</td>
      <td style="text-align: center;">{{ $hours }} {{ $days }} {{ $cantidad }}</td>
      @if($var->nominaconcepts['sign'] == 'A')
        <?php 
          $total_asignacion += $var->amount;
        ?>
        <td style="text-align: center;">{{ number_format($var->amount , 2, ',', '.')}}</td>
        <td style="text-align: center;"></td>
      @else
        <?php 
          $total_deduccion += $var->amount;
        ?>
        <td style="text-align: center;"></td>
        <td style="text-align: center;">{{ number_format($var->amount , 2, ',', '.') }}</td>
      @endif
    </tr>
  @endforeach
  
  <tr>
    <td style="text-align: center;"></td>
    <td style="text-align: center;"></td>
    <td style="text-align: center;">Sub Totales</td>
    <td style="text-align: center;">{{ number_format($total_asignacion  , 2, ',', '.')}}</td>
    <td style="text-align: center;">{{ number_format($total_deduccion , 2, ',', '.') }}</td>
  </tr>

  <tr>
    <td style="text-align: center;"></td>
    <td style="text-align: center;"></td>
    <td style="text-align: center;">Total a Pagar</td>
    <td style="text-align: center;">{{ number_format($total_asignacion - $total_deduccion, 2, ',', '.')}}</td>
    <td style="text-align: center;"></td>
  </tr>
  
</table>
<br><br>
<table style="width: 100%;">
  <tr>
    <th  class="text-left font-weight-normal" style="border-color: #ffffff; width: 70%;">Recibe conforme: {{ $employee->nombres }} {{ $employee->apellidos }}</th>
    <th  class="text-left" style="border-color: #ffffff; width: 30%;">Firma: __________________</th>
  </tr>
</table>
<table style="width: 100%;">
  <tr>
    <th  class="text-left font-weight-normal" style="border-color: #ffffff; width: 50%;">C.I: {{ $employee->id_empleado }}</th>
    <th  class="text-left" style="border-color: #ffffff; width: 50%;">Fecha: </th>
    <th  class="text-left" style="border-color: #ffffff; width: 50%;">Hora: </th>
  </tr>
</table>

</body>
</html>
