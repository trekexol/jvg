
  
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="{{asset('vendor/sb-admin/css/sb-admin-2.min.css')}}" rel="stylesheet">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
<title>Bono Vacaciones</title>
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
  <div class="text-center h4">Recibo de Utilidades</div>

 
   
 
<table style="width: 25%;">
  <tr>
    <th >Fecha: {{ $datenow }}</th>
</table>

<table style="width: 100%;">
  <tr>
    <th style="width: 28%; border-right: none;">Nombre de la Empresa: </th>
    <th style="width: 72%;" class="font-weight-normal">{{ $company->razon_social ?? '' }}</th>
  </tr>
</table>



<table style="width: 100%;">
  <tr>
    <th style="width: 28%; ">Domicilio Fiscal:</th>
    <th style="width: 72%;" class="font-weight-normal">{{ $company->address ?? '' }}</th>
  </tr>
</table>

<table style="width: 100%;">
  <tr>
    <th style="width: 70%; " class="font-weight-normal">Nombre del Trabajador: {{ $employee->nombres }} {{ $employee->apellidos}}</th>
    <th style="width: 30%; " class="text-center">Último Sueldo</th>
  </tr>
  <tr>
    <td class="font-weight-normal">Cargo desempeñado: {{ $employee->professions['description'] }}</td>
    <td class="text-center">{{ number_format($employee->monto_pago, 2, ',', '.')}}</td>
  </tr>  
</table>

<table style="width: 100%;">
  <tr>
    <th class="text-center" style="width: 20%;">Cédula</th>
    <th class="text-center" style="width: 20%;">Fecha de Ingreso</th>
    <th class="text-center" style="width: 20%;">Motivo del Recibo</th>
    <th class="text-center" style="width: 20%;">Fecha de Utilidades</th>
    <th class="text-center" style="width: 20%;">Dias a Pagar</th>
  </tr>
  <tr>
    <td class="text-center font-weight-normal">{{ $employee->id_empleado }}</td>
    <td class="text-center font-weight-normal">{{ $employee->fecha_ingreso }}</td>
    <td class="text-center font-weight-normal">Utilidades</td>
    <td class="text-center font-weight-normal">{{ $employee->date_end }}</td>
    <td class="text-center font-weight-normal">{{ $employee->days }}</td>
  </tr>  
</table>

<table style="width: 100%;">
  <tr>
    <th class="text-center" style="width: 20%;">Tipo</th>
    <th class="text-center" style="width: 60%;">Descripción</th>
    <th class="text-center" style="width: 20%;">Total</th>
  </tr>
 
</table>


</div>

</body>
</html>
