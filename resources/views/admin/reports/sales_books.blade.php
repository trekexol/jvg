
  
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
  <h4 style="color: black; text-align: center">LIBRO DE VENTAS</h4>
  <h5 style="color: black; text-align: center">Fecha de Emisión: {{ $datenow ?? '' }} / Fecha desde: {{ $date_begin ?? '' }} Fecha Hasta: {{ $date_end ?? '' }}</h5>
   
 
<table style="width: 100%;">
  <tr>
    <th style="text-align: center; ">Nº</th>
    <th style="text-align: center; ">Fecha</th>
    <th style="text-align: center; ">Rif</th>
    <th style="text-align: center; ">Razón Social</th>
    <th style="text-align: center; ">Serie</th>
    <th style="text-align: center; ">Monto</th>
    <th style="text-align: center; ">Base Imponible</th>
    <th style="text-align: center; ">Ret.Iva</th>
    <th style="text-align: center; ">Ret.Islr</th>
    <th style="text-align: center; ">Anticipo</th>
    <th style="text-align: center; ">IVA</th>
    <th style="text-align: center; ">Total</th>
  </tr> 
  @foreach ($quotations as $quotation)
    <tr>
      
      <td style="text-align: center; ">{{ $quotation->id ?? ''}}</td>
      <td style="text-align: center; ">{{ $quotation->date_billing ?? ''}}</td>
      
      <td style="text-align: center; font-weight: normal;">{{ $quotation->clients['cedula_rif'] ?? '' }}</td>
      <td style="text-align: center; font-weight: normal;">{{ $quotation->clients['name'] ?? '' }}</td>
      <td style="text-align: center; font-weight: normal;">{{ $quotation->serie ?? ''}}</td>
      @if (isset($coin) && ($coin == 'bolivares'))
        <td style="text-align: right; font-weight: normal;">{{ number_format(($quotation->amount ?? 0), 2, ',', '.') }}</td>
        <td style="text-align: right; font-weight: normal;">{{ number_format(($quotation->base_imponible ?? 0), 2, ',', '.') }}</td>
        <td style="text-align: right; font-weight: normal;">{{ number_format(($quotation->retencion_iva ?? 0), 2, ',', '.') }}</td>
        <td style="text-align: right; font-weight: normal;">{{ number_format(($quotation->retencion_islr ?? 0), 2, ',', '.') }}</td>
        <td style="text-align: right; font-weight: normal;">{{ number_format(($quotation->anticipo ?? 0), 2, ',', '.') }}</td>
        <td style="text-align: right; font-weight: normal;">{{ number_format(($quotation->amount_iva ?? 0), 2, ',', '.') }}</td>
        <td style="text-align: right; font-weight: normal;">{{ number_format(($quotation->amount_with_iva ?? 0), 2, ',', '.') }}</td>
      @else
        <td style="text-align: right; font-weight: normal;">{{ number_format(($quotation->amount / $quotation->bcv), 2, ',', '.') }}</td>
        <td style="text-align: right; font-weight: normal;">{{ number_format(($quotation->base_imponible / $quotation->bcv), 2, ',', '.') }}</td>
        <td style="text-align: right; font-weight: normal;">{{ number_format(($quotation->retencion_iva / $quotation->bcv), 2, ',', '.') }}</td>
        <td style="text-align: right; font-weight: normal;">{{ number_format(($quotation->retencion_islr / $quotation->bcv), 2, ',', '.') }}</td>
        <td style="text-align: right; font-weight: normal;">{{ number_format(($quotation->anticipo / $quotation->bcv), 2, ',', '.') }}</td>
        <td style="text-align: right; font-weight: normal;">{{ number_format(($quotation->amount_iva / $quotation->bcv), 2, ',', '.') }}</td>
        <td style="text-align: right; font-weight: normal;">{{ number_format(($quotation->amount_with_iva / $quotation->bcv), 2, ',', '.') }}</td>
      @endif
     
    </tr> 
  @endforeach 

  
</table>

</body>
</html>
