
  
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
 
<title>Nota de Entrega</title>
<style>
  table, td, th {
    border: 1px solid black;
    font-size: xx-small;
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
  <table>
    <tr>
      <th style="text-align: left; font-weight: normal; width: 10%; border-color: white; font-weight: bold;"> <img src="{{ asset(Auth::user()->company->foto_company ?? 'img/northdelivery.jpg') }}" width="90" height="30" class="d-inline-block align-top" alt="">
      </th>
      <th style="text-align: left; font-weight: normal; width: 90%; border-color: white; font-weight: bold;"><h4>{{Auth::user()->company->code_rif ?? ''}} </h4></th>
    </tr> 
  </table>
<div style="color: black;font-size: xx-small;font-weight: bold; text-align: right;">NOTA DE ENTREGA NRO: {{ str_pad($quotation->number_delivery_note ?? $quotation->id, 6, "0", STR_PAD_LEFT)}}</div>
<table>
  @if (isset($company->franqueo_postal))
  <tr>
    <th style="font-weight: normal; width: 40%;">Concesión Postal:</th>
    <th style="font-weight: normal;">Nº {{ $company->franqueo_postal ?? ''}}</th>
   
  </tr>
  @endif
  <tr>
    <td style="width: 40%;">Fecha de Emisión:</td>
    <td>{{ $quotation->date_delivery_note ?? ''}}</td>
    
  </tr>
  
</table>
<table style="width: 100%;">
  <tr>
    <th style="font-weight: normal;">Nombre / Razón Social: &nbsp;  {{ $quotation->clients['name'] ?? ''}} </th>
    <th style="font-weight: normal;">Vendedor: {{ $quotation->vendors['name'] ?? 'No aplica' }} {{ $quotation->vendors['surname'] ?? ''}} </th>
    
  </tr>
</table>
<table style="width: 100%;">
  <tr>
    <th style="font-weight: normal;">Domicilio Fiscal: &nbsp;  {{ $quotation->clients['direction'] ?? ''}}</th>
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
    <td style="text-align: center;">Nota de Entrega</td>
    <td style="text-align: center;">{{ $quotation->transports['placa'] ?? '' }}</td>
    
    
  </tr>
  
</table>

<table style="width: 100%;">
  <tr>
  <th style="font-weight: normal; ">Observaciones: &nbsp; {{ $quotation->observation ?? ''}} </th>
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
    <th style="text-align: right; font-weight: normal; width: 79%; border-top-color: rgb(17, 9, 9); ">MONTO TOTAL</th>
    <th style="text-align: right; font-weight: normal; width: 21%; border-top-color: rgb(17, 9, 9);">{{ number_format($total, 2, ',', '.') }}</th>
  </tr> 
  
</table>

<div style="color: black;font-size: xx-small; text-align: center;">Original Cliente</div>
<table>
  <tr>
    <th style="text-align: left; font-weight: normal; width: 10%; border-color: white; font-weight: bold;"> <img src="{{ asset(Auth::user()->company->foto_company ?? 'img/northdelivery.jpg') }}" width="90" height="30" class="d-inline-block align-top" alt="">
    </th>
    <th style="text-align: left; font-weight: normal; width: 90%; border-color: white; font-weight: bold;"><h4>{{Auth::user()->company->code_rif ?? ''}} </h4></th>
  </tr> 
</table>
<!-- Repeticion de lo mismo -->
<div style="color: black;font-size: xx-small;font-weight: bold; text-align: right;">NOTA DE ENTREGA NRO: {{ str_pad($quotation->number_delivery_note ?? $quotation->id, 6, "0", STR_PAD_LEFT)}}</div>
<table>
  @if (isset($company->franqueo_postal))
  <tr>
    <th style="font-weight: normal; width: 40%;">Concesión Postal:</th>
    <th style="font-weight: normal;">Nº {{ $company->franqueo_postal ?? ''}}</th>
   
  </tr>
  @endif
  <tr>
    <td style="width: 40%;">Fecha de Emisión:</td>
    <td>{{ $quotation->date_delivery_note ?? ''}}</td>
    
  </tr>
  
</table>
<table style="width: 100%;">
  <tr>
    <th style="font-weight: normal;">Nombre / Razón Social: &nbsp;  {{ $quotation->clients['name'] ?? ''}} </th>
    <th style="font-weight: normal;">Vendedor: {{ $quotation->vendors['name'] ?? 'No aplica' }} {{ $quotation->vendors['surname'] ?? ''}} </th>
    
  </tr>
</table>
<table style="width: 100%;">
  <tr>
    <th style="font-weight: normal;">Domicilio Fiscal: &nbsp;  {{ $quotation->clients['direction'] ?? ''}}</th>
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
    <td style="text-align: center;">Nota de Entrega</td>
    <td style="text-align: center;">{{ $quotation->transports['placa'] ?? '' }}</td>
    
    
  </tr>
  
</table>

<table style="width: 100%;">
  <tr>
  <th style="font-weight: normal; ">Observaciones: &nbsp; {{ $quotation->observation ?? ''}} </th>
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
    <th style="text-align: right; font-weight: normal; width: 79%; border-top-color: rgb(17, 9, 9); ">MONTO TOTAL</th>
    <th style="text-align: right; font-weight: normal; width: 21%; border-top-color: rgb(17, 9, 9);">{{ number_format($total, 2, ',', '.') }}</th>
  </tr> 
</table>
<div style="color: black;font-size: xx-small; text-align: center;">Copia (Sin Derecho a Crédito Fiscal)</div>

<table>
  <tr>
    <th style="text-align: left; font-weight: normal; width: 10%; border-color: white; font-weight: bold;"> <img src="{{ asset(Auth::user()->company->foto_company ?? 'img/northdelivery.jpg') }}" width="90" height="30" class="d-inline-block align-top" alt="">
    </th>
    <th style="text-align: left; font-weight: normal; width: 90%; border-color: white; font-weight: bold;"><h4>{{Auth::user()->company->code_rif ?? ''}} </h4></th>
  </tr> 
</table>

<!-- Repeticion de lo mismo -->
<div style="color: black;font-size: xx-small;font-weight: bold; text-align: right;">NOTA DE ENTREGA NRO: {{ str_pad($quotation->number_delivery_note ?? $quotation->id, 6, "0", STR_PAD_LEFT)}}</div>
<table>
  @if (isset($company->franqueo_postal))
  <tr>
    <th style="font-weight: normal; width: 40%;">Concesión Postal:</th>
    <th style="font-weight: normal;">Nº {{ $company->franqueo_postal ?? ''}}</th>
  </tr>
  @endif
  
  <tr>
    <td style="width: 40%;">Fecha de Emisión:</td>
    <td>{{ $quotation->date_delivery_note ?? ''}}</td>
    
  </tr>
  
</table>
<table style="width: 100%;">
  <tr>
    <th style="font-weight: normal;">Nombre / Razón Social: &nbsp;  {{ $quotation->clients['name'] ?? ''}} </th>
    <th style="font-weight: normal;">Vendedor: {{ $quotation->vendors['name'] ?? 'No aplica' }} {{ $quotation->vendors['surname'] ?? ''}} </th>
    
  </tr>
</table>
<table style="width: 100%;">
  <tr>
    <th style="font-weight: normal;">Domicilio Fiscal: &nbsp;  {{ $quotation->clients['direction'] ?? ''}}</th>
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
    <td style="text-align: center;">Nota de Entrega</td>
    <td style="text-align: center;">{{ $quotation->transports['placa'] ?? '' }}</td>
    
    
  </tr>
  
</table>

<table style="width: 100%;">
  <tr>
  <th style="font-weight: normal; ">Observaciones: &nbsp; {{ $quotation->observation ?? ''}} </th>
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
    <th style="text-align: right; font-weight: normal; width: 79%; border-top-color: rgb(17, 9, 9); ">MONTO TOTAL</th>
    <th style="text-align: right; font-weight: normal; width: 21%; border-top-color: rgb(17, 9, 9);">{{ number_format($total, 2, ',', '.') }}</th>
  </tr> 
</table>
<div style="color: black;font-size: xx-small; text-align: center;">Copia Contabilidad (Sin Derecho a Crédito Fiscal)</div>

</body>
</html>
