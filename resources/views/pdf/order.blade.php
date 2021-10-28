
  
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
 
<title>Pedido</title>
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
  <h4 style="color: black">PEDIDO NRO: {{ str_pad($quotation->number_order ?? $quotation->id, 6, "0", STR_PAD_LEFT)}}</h4>

 
<table>
  <tr>
    <th style="font-weight: normal; width: 40%;">Concesión Postal:</th>
    <th style="font-weight: normal;">Nº {{ $company->franqueo_postal ?? ''}}</th>
   
  </tr>
  <tr>
    <td style="width: 40%;">Fecha de Emisión:</td>
    <td>{{ $quotation->date_delivery_note ?? ''}}</td>
    
  </tr>
  
</table>




<table style="width: 100%;">
  <tr>
    <th style="font-weight: normal; font-size: medium;">Nombre / Razón Social: &nbsp;  {{ $quotation->clients['name'] ?? ''}}</th>
    
   
  </tr>
  <tr>
    <td>Domicilio Fiscal: &nbsp;  {{ $quotation->clients['direction'] ?? ''}}
    </td>
    
    
  </tr>
  
</table>




<table style="width: 100%;">
  <tr>
    <th style="text-align: center;">Teléfono</th>
    <th style="text-align: center;">RIF/CI</th>
    <th style="text-align: center;">N° Control / Serie</th>
    <th style="text-align: center;">Condición de Pago</th>
    <th style="text-align: center;">Transporte</th>
   
  </tr>
  <tr>
    <td style="text-align: center;">{{ $quotation->clients['phone1'] ?? ''}}</td>
    <td style="text-align: center;">{{ $quotation->clients['cedula_rif'] ?? ''}}</td>
    <td style="text-align: center;">{{ $quotation->serie }}</td>
    <td style="text-align: center;">Pedido</td>
    <td style="text-align: center;">{{ $quotation->transports['placa'] ?? '' }}</td>
    
    
  </tr>
  
</table>

<table style="width: 100%;">
  <tr>
  <th style="font-weight: normal; font-size: medium;">Observaciones: &nbsp; {{ $quotation->observation ?? ''}} </th>
</tr>
  
</table>

<table style="width: 100%;">
  <tr>
    <th style="text-align: center; width: 100%;">Productos</th>
  </tr> 
</table>
<table style="width: 100%;">
  <tr>
    <th style="text-align: center; ">Código</th>
    <th style="text-align: center; ">Descripción</th>
    <th style="text-align: center; ">Cantidad</th>
    <th style="text-align: center; ">P.V.J.</th>
    <th style="text-align: center; ">Desc</th>
    <th style="text-align: center; ">Total</th>
  </tr> 
  @foreach ($inventories_quotations as $var)
      <?php
      $percentage = (($var->price * $var->amount_quotation) * $var->discount)/100;

      $total_less_percentage = ($var->price * $var->amount_quotation) - $percentage;

      $total_less_percentage = $total_less_percentage / ($bcv ?? 1);
      ?>
    <tr>
      <th style="text-align: center; font-weight: normal;">{{ $var->code_comercial }}</th>
      <th style="text-align: center; font-weight: normal;">{{ $var->description }}</th>
      <th style="text-align: center; font-weight: normal;">{{ number_format($var->amount_quotation, 0, '', '.') }}</th>
      <th style="text-align: center; font-weight: normal;">{{ number_format($var->price / ($bcv ?? 1), 2, ',', '.')  }}</th>
      <th style="text-align: center; font-weight: normal;">{{ $var->discount }}%</th>
      <th style="text-align: right; font-weight: normal;">{{ number_format($total_less_percentage, 2, ',', '.') }}</th>
    </tr> 
  @endforeach 
</table>


<?php
  $iva = ($quotation->base_imponible * $quotation->iva_percentage)/100;

  $total = $quotation->total_factura + $iva;

  $total_petro = $total / ($bcv ?? 1) / $company->rate_petro;

  $iva = $iva / ($bcv ?? 1);

  $total = $total / ($bcv ?? 1);
?>

<table style="width: 100%;">
  <tr>
    <th style="text-align: right; font-weight: normal; width: 79%; border-bottom-color: white;">Sub Total</th>
    <th style="text-align: right; font-weight: normal; width: 21%;">{{ number_format($quotation->total_factura / ($bcv ?? 1), 2, ',', '.') }}</th>
  </tr> 
  <tr>
    <th style="text-align: right; font-weight: normal; width: 79%; border-bottom-color: white;">Base Imponible</th>
    <th style="text-align: right; font-weight: normal; width: 21%;">{{ number_format($quotation->base_imponible / ($bcv ?? 1), 2, ',', '.') }}</th>
  </tr> 
  <tr>
    <th style="text-align: right; font-weight: normal; width: 79%; border-bottom-color: white;">Ventas Exentas</th>
    <th style="text-align: right; font-weight: normal; width: 21%;">{{ number_format($quotation->ventas_exentas / ($bcv ?? 1), 2, ',', '.') }}</th>
  </tr> 
  <tr>
    <th style="text-align: right; font-weight: normal; width: 79%; border-bottom-color: white;">I.V.A.{{ $quotation->iva_percentage }}%</th>
    <th style="text-align: right; font-weight: normal; width: 21%;">{{ number_format($iva, 2, ',', '.') }}</th>
  </tr> 
  <tr>
    <th style="text-align: right; font-weight: normal; width: 79%; border-bottom-color: white;">MONTO TOTAL</th>
    <th style="text-align: right; font-weight: normal; width: 21%;">{{ number_format($total, 2, ',', '.') }}</th>
  </tr> 
  <tr>
    <th style="text-align: right; font-weight: normal; width: 79%; border-bottom-color: white; font-size: small;">MONTO TOTAL Petro</th>
    <th style="text-align: right; font-weight: normal; width: 21%;">{{ number_format($total_petro, 6, ',', '.') }}</th>
  </tr> 
  <tr>
    <th style="text-align: left; font-weight: normal; width: 79%; border-top-color: rgb(17, 9, 9); border-right-color: white; font-size: small;"><pre>NOTA DE ENTREGA        </pre></th>
    <th style="text-align: right; font-weight: normal; width: 21%; "></th>
  </tr> 
  
  
  
  
</table>

</body>
</html>
