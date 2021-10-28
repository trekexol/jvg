
  
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
  <h4 style="color: black; text-align: center">CUENTAS POR COBRAR</h4>
  <h5 style="color: black; text-align: center">Fecha de Emisión: {{ $date_end ?? $datenow ?? '' }}</h5>
   
  <?php 
    
    $total_por_facturar = 0;
    $total_por_cobrar = 0;
  
  ?>
<table style="width: 100%;">
  <tr>
    <th style="text-align: center; width:9%;">Fecha</th>
    <th style="text-align: center; width:12%;">Tipo</th>
    <th style="text-align: center; width:5%;">N°</th>
    <th style="text-align: center; width:1%;">Ctrl/Serie</th>
    <th style="text-align: center; width:22%;">Cliente</th>
    <th style="text-align: center;">Vendedor</th>
    <th style="text-align: center;">Total</th>
    <th style="text-align: center;">Abono</th>
    <th style="text-align: center;">Por Cobrar</th>
  </tr> 
  @foreach ($quotations as $quotation)
    <?php 
    
      if(isset($coin) && $coin != 'bolivares'){

        $quotation->amount_with_iva = ($quotation->amount_with_iva ?? 0) / ($quotation->bcv ?? 1);
        $quotation->amount_anticipo = ($quotation->amount_anticipo ?? 0) / ($quotation->bcv ?? 1);

        $por_cobrar = (($quotation->amount_with_iva ?? 0) - ($quotation->amount_anticipo ?? 0));
        $total_por_cobrar += $por_cobrar;
        $total_por_facturar += $quotation->amount_with_iva;
      }else{
        $por_cobrar = ($quotation->amount_with_iva ?? 0) - ($quotation->amount_anticipo ?? 0);
        $total_por_cobrar += $por_cobrar;
        $total_por_facturar += $quotation->amount_with_iva;
      }
      $tipo = '';
    if ($quotation->number_invoice > 0){
    $tipo = 'Factura';
    }

    if ($quotation->number_delivery_note > 0) {
    $tipo = 'Nota de Entrega';
    }
    
    ?>
    <tr>
      <th style="text-align: center; font-weight: normal;">{{ $quotation->date_quotation ?? ''}}</th>
      <th style="text-align: center; font-weight: normal;">{{ $tipo }}</th>
      <th style="text-align: center; font-weight: normal;">{{ $quotation->number_invoice ?? $quotation->number_delivery_note}}</th>
      <th style="text-align: center; font-weight: normal;">{{ $quotation->serie ?? ''}}</th>
      <th style="text-align: center; font-weight: normal;">{{ $quotation->name_client ?? ''}}</th>
      <th style="text-align: center; font-weight: normal;">{{''}}</th>
      <th style="text-align: right; font-weight: normal;">{{ number_format(($quotation->amount_with_iva ?? 0), 2, ',', '.') }}</th>
      <th style="text-align: right; font-weight: normal;">{{ number_format(($quotation->amount_anticipo ?? 0), 2, ',', '.') }}</th>
      <th style="text-align: right; font-weight: normal;">{{ number_format($por_cobrar, 2, ',', '.') }}</th>
    </tr> 
  @endforeach 

  <tr>
    <th style="text-align: center; font-weight: normal; border-color: white;"></th>
    <th style="text-align: center; font-weight: normal; border-color: white;"></th>
    <th style="text-align: center; font-weight: normal; border-color: white;"></th>
    <th style="text-align: center; font-weight: normal; border-color: white;"></th>
    <th style="text-align: center; font-weight: normal; border-color: white;"></th>
    <th style="text-align: center; font-weight: normal; border-color: white; border-right-color: black;"></th>
    <th style="text-align: right; font-weight: normal;">{{ number_format(($total_por_facturar ?? 0), 2, ',', '.') }}</th>
    <th style="text-align: right; font-weight: normal; border-color: white; border-right-color: black;"></th>
    <th style="text-align: right; font-weight: normal;">{{ number_format($total_por_cobrar, 2, ',', '.') }}</th>
  </tr> 
</table>

</body>
</html>
