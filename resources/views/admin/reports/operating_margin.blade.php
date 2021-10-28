
  
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
 
<title></title>
<style>
  table, td, th {
    border: 1px solid black;
    font-size: x-small;
  }
  
  table {
    border-collapse: collapse;
    width: 100%;
  }
  
  th {
    
    text-align: left;
  }
  </style>
</head>

<body>
  <table>
    <tr>
      <th style="text-align: left; font-weight: normal; width: 10%; border-color: white; font-weight: bold;"> <img src="{{ asset(Auth::user()->company->foto_company ?? 'img/northdelivery.jpg') }}" width="90" height="30" class="d-inline-block align-top" alt="">
      </th>
      <th style="text-align: left; font-weight: normal; width: 90%; border-color: white; font-weight: bold;"><h4>{{Auth::user()->company->code_rif ?? ''}} </h4></th>
    </tr> 
  </table>
  <h4 style="color: black; text-align: center">Margen Operativo</h4>
  <h5 style="color: black; text-align: center">Fecha de Emisión: {{ $datenow ?? '' }} / Fecha desde: {{ $date_begin ?? '' }} Fecha Hasta: {{ $date_end ?? '' }}</h5>
   
  
  <table ALIGN="center" style="width: 80%;">
    <tr>
      <th style="text-align: center; border-color: white; font-weight: bold;background-color: #c9ffc7;">CENTRO DE COSTOS</th>
      <th style="text-align: center; border-color: white; font-weight: bold;background-color: #c9ffc7;">Matriz</th>
      <th style="text-align: center; border-color: white; font-weight: bold;background-color: #c9ffc7;">TOTAL</th>
    </tr> 
    
    <tr>
      <td style="text-align: center; border-color: white; font-weight: bold;background-color: #FFCCFD;">Ingresos</td>
      <td style="text-align: right; border-color: white; font-weight: bold;background-color: #FFCCFD;">{{number_format(($ventas ?? 0), 2, ',', '.') }}</td>
      <td style="text-align: right; border-color: white; font-weight: bold;background-color: #FFCCFD;">{{number_format(($ventas ?? 0) * -1, 2, ',', '.') }}</td>
    </tr> 
    <tr>
      <td style="text-align: center; border-color: white; font-weight: bold;background-color: #FFCCFD;">Costos</td>
      <td style="text-align: right; border-color: white; font-weight: bold;background-color: #FFCCFD;">{{number_format(($costos ?? 0), 2, ',', '.') }}</td>
      <td style="text-align: right; border-color: white; font-weight: bold;background-color: #FFCCFD;">{{number_format(($costos ?? 0), 2, ',', '.') }}</td>
    </tr> 
    <tr>
      <td style="text-align: center; border-color: white; font-weight: bold;background-color: #FFCCFD;">Gastos</td>
      <td style="text-align: right; border-color: white; font-weight: bold;background-color: #FFCCFD;">{{number_format(($gastos ?? 0), 2, ',', '.') }}</td>
      <td style="text-align: right; border-color: white; font-weight: bold;background-color: #FFCCFD;">{{number_format(($gastos ?? 0), 2, ',', '.') }}</td>
    </tr> 
    <tr>
      <td style="text-align: center; border-color: white; font-weight: bold;background-color: #FFCCFD;">UTILIDAD</td>
      <td style="text-align: right; border-color: white; font-weight: bold;background-color: #FFCCFD;">{{number_format(($utilidad ?? 0), 2, ',', '.') }}</td>
      <td style="text-align: right; border-color: white; font-weight: bold;background-color: #FFCCFD;">{{number_format(($utilidad ?? 0), 2, ',', '.') }}</td>
    </tr> 
    <tr>
      <td style="text-align: center; border-color: white; font-weight: bold;background-color: #FFCCFD;">% M.O.</td>
      <td style="text-align: right; border-color: white; font-weight: bold;background-color: #FFCCFD;">{{number_format(($margen_operativo ?? 0), 2, ',', '.') }}</td>
      <td style="text-align: right; border-color: white; font-weight: bold;background-color: #FFCCFD;">{{number_format(($margen_operativo ?? 0), 2, ',', '.') }}</td>
    </tr> 
    <tr>
      <td style="text-align: center; border-color: white; font-weight: bold;background-color: #FFCCFD;">% RENTABILIDAD</td>
      <td style="text-align: right; border-color: white; font-weight: bold;background-color: #FFCCFD;">{{number_format(($rentabilidad ?? 0), 2, ',', '.') }}</td>
      <td style="text-align: right; border-color: white; font-weight: bold;background-color: #FFCCFD;">{{number_format(($rentabilidad ?? 0), 2, ',', '.') }}</td>
    </tr> 

  </table>
</body>
</html>
