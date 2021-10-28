
  
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
 
<title></title>
<style>
  table, td, th {
    border: 1px solid black;
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
  <h5 style="color: black">Empresa: {{ $company->razon_social ?? ''}} </h5>
  <h5 style="color: black"> Rif: {{ $company->code_rif ?? ''}}</h5>
  <h5 style="color: black;">Fecha de Emisión: {{ $datenow }}</h5>
   
  <h4 style="color: black; text-align: center">LIBRO DIARIO</h4>
 
  <h5 style="color: black;">Fecha desde: {{ $date_begin ?? '' }} / hasta {{ $date_end ?? ''}}</h5>
  
  @if (isset($detailvouchers))
      <?php 
        //inicializamos el marcador
        $id_header_old = 0;
        $marcador = true;
        $primera_tabla = false;
        $total_debe = 0;
        $total_haber = 0;
      ?>
    @foreach ($detailvouchers as $detail)
    
      <?php 
        if(($id_header_old == 0) || ($detail->id_header != $id_header_old)){
          $id_header_old = $detail->id_header;
          $marcador = true;
        }
        
      ?>
      @if ($marcador == true)
        @if ($primera_tabla == true)
          </table>
          <table style="width: 100%;">
            <tr>
              <td style="text-align: center; width: 20%;">Descripción</td>
              <td style="text-align: left; width: 40%;">{{ $description_final ?? '' }}</td>
              <td style="text-align: right; width: 20%;">{{ number_format($total_debe ?? 0, 2, ',', '.')}}</td>
              <td style="text-align: right; width: 20%;">{{ number_format($total_haber ?? 0, 2, ',', '.')}}</td>
            </tr>
          </table>
            <?php 
              $total_debe = 0;
              $total_haber = 0;
            ?>
          <br>
        @else
          <?php 
            $primera_tabla = true;
          ?>
        @endif

        <table style="width: 100%;">
          <tr>
            <th style="text-align: center; width: 15%;">Fecha</th>
            <th style="text-align: center; width: 5%;">Ref</th>
            <th style="text-align: center; width: 40%;">Cuenta</th>
            <th style="text-align: center; width: 20%;">Debe</th>
            <th style="text-align: center; width: 20%;">Haber</th>
          </tr>
      @endif
      
        <tr>
          <td style="text-align: center;">{{ $detail->date ?? ''}}</td>
          <td style="text-align: center;">{{ $detail->id_header ?? ''}}</td>
          <td style="text-align: left;">{{ $detail->account_description ?? ''}}</td>
          <td style="text-align: right;">{{ number_format($detail->debe ?? 0, 2, ',', '.')}}</td>
          <td style="text-align: right;">{{ number_format($detail->haber ?? 0, 2, ',', '.')}}</td>
        </tr>
        <?php 
          $marcador = false;
          $total_debe += $detail->debe;
          $total_haber += $detail->haber;
          $description_final = $detail->header_description;
        ?>
      
    @endforeach
    <table style="width: 100%;">
      <tr>
        <td style="text-align: center; width: 20%;">Descripción</td>
        <td style="text-align: center; width: 40%;">{{ $description_final ?? '' }}</td>
        <td style="text-align: right; width: 20%;">{{ number_format($total_debe ?? 0, 2, ',', '.')}}</td>
        <td style="text-align: right; width: 20%;">{{ number_format($total_haber ?? 0, 2, ',', '.')}}</td>
      </tr>
    </table>
  @endif
  
  


</body>
</html>
