

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

    <title>Factura</title>
    <style>
        table {
            border-collapse: collapse;
        }
        td {
            font-family: Arial, Helvetica, sans-serif;
        }
        th{
            font-family: Arial, Helvetica, sans-serif;
        }
    </style>
</head>
<body>
<br><br><br><br><br><br><br>
<br>
<h4 style="color: black">FACTURA NRO: {{ str_pad($quotation->id, 6, "0", STR_PAD_LEFT)}}</h4>
<table width="50%" style=" border: 1px solid black;" >
    <tr>
        @if (isset($quotation->credit_days))
            <td style="width: 10%;border: 1px solid black;">Fecha de Emisión:</td>
            <td style="width: 10%;border: 1px solid black;"> {{$quotation->date_billing}} </td>

        @else
            <td style="width: 10%;border: 1px solid black;">Fecha de Emisión:</td>
            <td style="width: 10%;border: 1px solid black;">{{$quotation->date_billing}} </td>
        @endif
    </tr>
</table>
<table width="100%" style=" border: 1px solid black;" >
    <tr>
        <td style="font-size: 12px">Nombre / Razón Social:  {{ $quotation->clients['name'] }}</td>
    </tr>
</table>
<table width="100%" style=" border: 1px solid black;" >
    <tr>
        <td style="font-size: 12px;border: 1px solid black;" width="70%">Domicilio Fiscal: {{ $quotation->clients['direction'] }}</td>
        <td style="font-size: 12px;border: 1px solid black;">LICENCIA DE LICORES: &nbsp;  {{ $quotation->clients['licence'] }}</td>
    </tr>
</table>
<table width="100%" style=" border: 1px solid black;" >
    <tr>
        <th style="text-align: center;font-size: 12px;width: 8%;" >Teléfono</th>
        <th style="text-align: center;font-size: 12px;width: 9%;border: 1px solid black;">RIF/CI</th>
        <th style="text-align: center;font-size: 12px;width: 9%;border: 1px solid black;">Días de Credito</th>
        <th style="text-align: center;font-size: 12px;width: 9%;border: 1px solid black;">Fecha Venc.</th>
        <th style="text-align: center;font-size: 12px;width: 12%;border: 1px solid black;">Condiciones Pago.</th>
        <th style="text-align: center;font-size: 12px;border: 1px solid black;">Transporte.</th>
        <th style="text-align: center;font-size: 12px;border: 1px solid black;">Chofer</th>
        <th style="text-align: center;font-size: 12px;border: 1px solid black;">Cedula</th>
        <th style="text-align: center;font-size: 12px;border: 1px solid black;">Placa</th>
    <tr>
        <td style="font-size: 12px;text-align: center;border: 1px solid black;">{{ $quotation->clients['phone1'] }}</td>
        <td style="font-size: 12px;text-align: center;border: 1px solid black;">{{ $quotation->clients['type_code']}}{{ $quotation->clients['cedula_rif'] }}</td>
        @if(empty($quotation->credit_days))
            <td style="font-size: 12px;text-align: center;border: 1px solid black;"></td>
        @else
            <td style="font-size: 12px;text-align: center;border: 1px solid black;">{{ $quotation->credit_days}}</td>
        @endif
        @if(empty($quotation->credit_days))
            <td style="font-size: 12px;text-align: center;border: 1px solid black;"></td>
        @else
            <td style="font-size: 12px;text-align: center;border: 1px solid black;">{{$quotation->date_expiration}}</td>
        @endif
        @if(empty($quotation->credit_days))

            <td style="font-size: 12px;text-align: center;border: 1px solid black;">Contado</td>
        @else
            <td style="font-size: 12px;text-align: center;border: 1px solid black;">Credito</td>
        @endif

        <td style="font-size: 12px;text-align: center;border: 1px solid black;">{{$quotation->transports->type}}-{{ $quotation->transports->placa}}-{{$quotation->transports->modelos['description']}}</td>
        <td style="font-size: 12px;text-align: center;border: 1px solid black;">{{ $quotation->drivers['name'] }} {{ $quotation->drivers['last_name'] }}</td>
        <td style="font-size: 12px;text-align: center;border: 1px solid black;">{{ $quotation->drivers['type_code'] }}{{ $quotation->drivers['cedula'] }}</td>
        <td style="font-size: 12px;text-align: center;border: 1px solid black;">{{ $quotation->transports['placa'] }}</td>
    </tr>
</table>
<table width="100%" style=" border: 1px solid black;" >
    <tr>
        <td style="font-size: 12px"> Observaciones: {{$quotation->observation}} </td>
    </tr>
</table>
<table width="100%" style="border: 1px solid black;" >
    <tr>
        <td style="font-size: 12px;border: 1px solid black;" width="70%">Destino: {{$quotation->destiny}}</td>
        <td style="font-size: 12px;border: 1px solid black;">LICENCIA DE LICORES: {{$quotation->licence}}</td>
    </tr>
</table>
<table width="100%" style="border: 1px solid black;" >
    <tr>
        <td style="font-size: 12px;border: 1px solid black;" width="70%"> Dirección Entrega: {{$quotation->delivery}}</td>
    </tr>
</table>
<table width="100%" style="border: 1px solid black;"  >
    <thead>
    <tr >
        <th style="font-size: 10px;border: 1px solid black;" >COD PRO</th>
        <th style="font-size: 10px;border: 1px solid black;" >CAJAS</th>
        <th style="font-size: 10px;border: 1px solid black;" >B.x.C</th>
        <th style="font-size: 10px;border: 1px solid black;" >Capac</th>
        <th style="font-size: 10px;border: 1px solid black;" >Lts-Gr</th>
        <th style="font-size: 10px;border: 1px solid black;" >°Alcohol</th>
        <th style="font-size: 10px;border: 1px solid black;" >Descripcion Producto</th>
        <th style="font-size: 10px;border: 1px solid black;" >P.V.P.</th>
        <th style="font-size: 10px;border: 1px solid black;" >Desc.</th>
        <th style="font-size: 10px;border: 1px solid black;" >SubTotal</th>
        <th style="font-size: 10px;border: 1px solid black;" >Base Impo.</th>
        <th style="font-size: 10px;border: 1px solid black;" >IVA.</th>
        <th style="font-size: 10px;border: 1px solid black;" >Base Impo. Pcb</th>
        <th style="font-size: 10px;border: 1px solid black;" >IVA Percibido</th>
        <th style="font-size: 10px;border: 1px solid black;" >Total de Venta</th>
        <th style="font-size: 10px;border: 1px solid black;" >Total $</th>
    </tr>
    </thead>
    <tbody style="font-size: 10px;border: 1px solid black;">
    @if (empty($inventories_quotations))
    @else
        @foreach ($inventories_quotations as $inventories_quotation)
            @php
                $codigo = $inventories_quotation->code_comercial ?? '' ;        //CODIGO

            @endphp

            <tr>
                <td style="font-size: 10px;text-align: center;border: 1px solid black;" >{{$codigo}}</td>
                <td style="font-size: 10px;text-align: center;border: 1px solid black;">{{$inventories_quotation->amount_quotation}}</td>
                <td style="font-size: 10px;text-align: center;border: 1px solid black;">{{$inventories_quotation->bottle}}</td>
                <td style="font-size: 10px;text-align: center;border: 1px solid black;">{{$inventories_quotation->capacity}}</td>
                <td style="font-size: 10px;text-align: center;border: 1px solid black;">{{$inventories_quotation->liter}}</td>
                <td style="font-size: 10px;text-align: center;border: 1px solid black;">{{$inventories_quotation->degree}}</td>
                <td style="font-size: 10px;text-align: left;border: 1px solid black;">{{$inventories_quotation->description}}</td>
                <td style="font-size: 10px;text-align: right;border: 1px solid black;">{{number_format($inventories_quotation->price,2,",",".")}}</td>
                <td style="font-size: 10px;text-align: center;border: 1px solid black;">{{$inventories_quotation->discount}}</td>
                <td style="font-size: 10px;text-align: right;border: 1px solid black;">{{number_format($inventories_quotation->price * $inventories_quotation->amount_quotation,2,",",".")}}</td> <!-- Sub-TOTAL-->
            @if($inventories_quotation->retiene_iva_quotation == "0")       <!-- BASE IMPONIBLE-->
                <td style="font-size: 10px;text-align: right;border: 1px solid black; ">{{number_format($inventories_quotation->price * $inventories_quotation->amount_quotation,2,",",".")}}</td>
                @else
                    <td style="font-size: 10px;text-align: right;border: 1px solid black;">0</td>
                @endif
                @if($inventories_quotation->retiene_iva_quotation == "0") <!-- IVA-->
                <td style="font-size: 10px;text-align: right;border: 1px solid black;">{{number_format($inventories_quotation->price * $inventories_quotation->amount_quotation * 12 / 100,2,",",".")}}</td>
                @else
                    <td style="font-size: 10px;text-align: right;border: 1px solid black;">0</td>
                @endif
                @if($inventories_quotation->retiene_iva_quotation == "0") <!-- BASE.IMPONIBLE.IVA-->
                <td style="font-size: 10px;text-align: right;border: 1px solid black;">{{number_format($inventories_quotation->price * $inventories_quotation->amount_quotation * 15 / 100  ,2,",",".")}}</td>
                @else
                    <td style="font-size: 10px;text-align: right;border: 1px solid black;">0</td>
                @endif
                @if($inventories_quotation->retiene_iva_quotation == "0") <!-- .IVA PERCIBIDO-->
                <td style="font-size: 10px;text-align: right;border: 1px solid black;">{{number_format($inventories_quotation->price * $inventories_quotation->amount_quotation * 15 / 100 * 12 / 100,2,",",".")}}</td>
                @else
                    <td style="font-size: 10px;text-align: right;border: 1px solid black;">0</td>
                @endif
                @if($inventories_quotation->retiene_iva_quotation == "0")  <!-- TOTAL DE VENTA -->
                <td style="font-size: 10px;text-align: center;border: 1px solid black;">{{number_format($inventories_quotation->price * $inventories_quotation->amount_quotation + $inventories_quotation->price * $inventories_quotation->amount_quotation * $quotation->iva_percentage / 100 + $inventories_quotation->price * $inventories_quotation->amount_quotation * $quotation->base_imponible_pcb / 100 * $quotation->iva_percibido / 100 ,2,",",".")}}</td>
                @else
                    <td style="font-size: 10px;text-align: center;border: 1px solid black;">{{number_format($inventories_quotation->price * $inventories_quotation->amount_quotation ,2,",",".")}}</td>
                @endif
                @if($inventories_quotation->retiene_iva_quotation == "0") <!-- TOTAL DE VENTA DOLARES -->
                <td style="font-size: 10px;text-align: center;border: 1px solid black;">${{number_format(($inventories_quotation->price * $inventories_quotation->amount_quotation + $inventories_quotation->price * $inventories_quotation->amount_quotation * $quotation->iva_percentage / 100 + $inventories_quotation->price * $inventories_quotation->amount_quotation * $quotation->base_imponible_pcb / 100 * $quotation->iva_percibido / 100) / $inventories_quotation->rate ,2,",",".")}}</td>
                @else
                    <td style="font-size: 10px;text-align: center;border: 1px solid black;">${{number_format($inventories_quotation->price * $inventories_quotation->amount_quotation / $inventories_quotation->rate )}}</td>
                @endif

            </tr>
        @endforeach
    @endif
    </tbody>
</table>
<br>
<table border="0" >
    <tr>
        <td style="font-size: 8px;text-align: center" width="250px"><strong>ESTA FACTURA VA SIN TACHADURAS NI ENMIENDAS</strong></td>
        <td style="font-size: 8px;text-align: center" width="100px"><strong>TOTAL FACTURA Bs.</strong></td>
        <td style="font-size: 10px;text-align: right" width="200px"><strong>{{number_format($total ,2,",",".")}}</strong></td>
        <td style="font-size: 10px;text-align: right" width="93px"><strong>{{number_format($total_retiene ,2,",",".")}}</strong></td>
        <td style="font-size: 10px;text-align: right" width="80px"><strong>{{number_format($total_iva ,2,",",".")}}</strong></td>
        <td style="font-size: 10px;text-align: right" width="85px"><strong>{{number_format($total_base_impo_pcb ,2,",",".")}}</strong></td>
        <td style="font-size: 10px;text-align: right" width="75px"><strong>{{number_format($total_iva_pcb ,2,",",".")}}</strong></td>
        <td style="font-size: 10px;text-align: right" width="85px"><strong>{{number_format($total_venta ,2,",",".")}}</strong></td>
    </tr>
</table>
<br>
<table border="0" >
    <tr>
        <td style="font-size: 8px;text-align: center" width="230px"><strong>ESTA FACTURA VA SIN TACHADURAS NI ENMIENDAS</strong></td>
        <td style="font-size: 8px;text-align: center" width="100px"><strong>TASA: {{number_format($rate ,2,",",".")}} BSF</strong></td>
        <td style="font-size: 8px;text-align: center" width="100px"><strong>TOTAL FACTURA USD.</strong></td>
        <td style="font-size: 10px;text-align: right" width="130px"><strong>REF :${{number_format($total / $rate ,2,",",".")}}</strong></td>
        <td style="font-size: 10px;text-align: right" width="93px"><strong>REF :${{number_format($total_retiene / $rate  ,2,",",".")}}</strong></td>
        <td style="font-size: 10px;text-align: right" width="80px"><strong>REF :${{number_format($total_iva / $rate ,2,",",".")}}</strong></td>
        <td style="font-size: 10px;text-align: right" width="85px"><strong>REF :${{number_format($total_base_impo_pcb / $rate ,2,",",".")}}</strong></td>
        <td style="font-size: 10px;text-align: right" width="75px"><strong>REF :${{number_format($total_iva_pcb / $rate ,2,",",".")}}</strong></td>
        <td style="font-size: 10px;text-align: right" width="75px"><strong>REF :${{number_format($total_venta / $rate ,2,",",".")}}</strong></td>
    </tr>
</table>
<br>
</body>
</html>
