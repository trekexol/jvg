
  
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

  <br>
  <h2 style="color: black; text-align: center">CUENTAS POR PAGAR</h2>
  <br>
  <h2 style="color: black; text-align: center">Fecha de Emisión: {{ $date_end ?? $datenow ?? '' }}</h2>
   
  <?php 
    
    $total_por_facturar = 0;
    $total_por_pagar = 0;
  
  ?>
<table style="width: 100%;">
  <tr>
    <th style="text-align: center; ">Fecha</th>
    <th style="text-align: center; ">N° Factura</th>
    <th style="text-align: center; ">Razon Rocial</th>
    <th style="text-align: center; ">N° Serie</th>
    <th style="text-align: center; ">Total</th>
    <th style="text-align: center; ">Abono</th>
    <th style="text-align: center; ">Por Pagar</th>
  </tr> 
  @if (isset($expenses))
  @foreach ($expenses as $expense)
    <?php 

    if(isset($coin) && $coin != "bolivares"){
      $expense->amount_with_iva = $expense->amount_with_iva / $expense->rate;
      $expense->amount_anticipo = $expense->amount_anticipo / $expense->rate;
    }
    
    $por_pagar = ($expense->amount_with_iva ?? 0) - ($expense->amount_anticipo ?? 0);
    $total_por_pagar += $por_pagar;
    $total_por_facturar += $expense->amount_with_iva;
    ?>
    <tr>
      <th style="text-align: center; font-weight: normal;">{{ $expense->date ?? ''}}</th>
      <th style="text-align: center; font-weight: normal;">{{ $expense->id ?? ''}}</th>
      <th style="text-align: center; font-weight: normal;">{{ $expense->name_provider ?? ''}}</th>
      <th style="text-align: center; font-weight: normal;">{{ $expense->serie ?? ''}}</th>
      <th style="text-align: right; font-weight: normal;">{{ number_format(($expense->amount_with_iva ?? 0), 2, ',', '.') }}</th>
      <th style="text-align: right; font-weight: normal;">{{ number_format(($expense->amount_anticipo ?? 0), 2, ',', '.') }}</th>
      <th style="text-align: right; font-weight: normal;">{{ number_format($por_pagar, 2, ',', '.') }}</th>
    </tr> 
  @endforeach 
  @endif
  <tr>
    <th style="text-align: center; font-weight: normal; border-color: white;"></th>
    <th style="text-align: center; font-weight: normal; border-color: white;"></th>
    <th style="text-align: center; font-weight: normal; border-color: white;"></th>
    <th style="text-align: center; font-weight: normal; border-color: white; border-right-color: black;"></th>
    <th style="text-align: right; font-weight: normal;">{{ number_format(($total_por_facturar ?? 0), 2, ',', '.') }}</th>
    <th style="text-align: right; font-weight: normal; border-color: white; border-right-color: black;"></th>
    <th style="text-align: right; font-weight: normal;">{{ number_format($total_por_pagar, 2, ',', '.') }}</th>
  </tr> 
</table>

</body>
</html>
