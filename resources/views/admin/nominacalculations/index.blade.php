@extends('admin.layouts.dashboard')


@section('content')

 
<div class="container-fluid">

    <!-- Page Heading -->
    
    <div class="justify-content-left border-left-danger">
        <div class="card-body h4">
                <div class="row py-lg-2">
                    <div class="col-sm-12">
                        Nómina: {{ $nomina->description }} , {{ $nomina->date_format}} , Empleado: {{ $employee->nombres }} {{ $employee->apellidos }}
                    </div>
                </div>
                <div class="row py-lg-2">
                    <div class="col-sm-12">
                        Tipo: {{ $nomina->type }} , {{ $nomina->date_begin }} hasta {{ $nomina->date_end ?? 'Indefinido' }}</h2>
                
                    </div>
                    @if (Auth::user()->role_id  == '1' || Auth::user()->role_id  == '2' )
                </div>
        </div>
    </div>
                <div class="row py-lg-2">
                    <div class="col-sm-3">
                    </div>
                    <div class="col-sm-3">
                        <a href="{{ route('nominacalculations.create',[$nomina->id,$employee->id])}}"  class="btn btn-primary float-md-right" role="button" aria-pressed="true">Agregar Concepto</a>
                    </div>
                    <div class="col-sm-3">
                        <a href="#" onclick="pdf();" class="btn btn-info float-md-right" role="button" aria-pressed="true">Imprimir Recibo</a>
                    </div>
                    <div class="col-sm-2">
                        <a href="{{ route('nominas.selectemployee',$nomina->id)}}"  class="btn btn-danger float-md-right" role="button" aria-pressed="true">Volver</a>
                    </div>
                    @endif
                </div>
        
    
</div>

  {{-- VALIDACIONES-RESPUESTA--}}
@include('admin.layouts.success')   {{-- SAVE --}}
@include('admin.layouts.danger')    {{-- EDITAR --}}
@include('admin.layouts.delete')    {{-- DELELTE --}}
{{-- VALIDACIONES-RESPUESTA --}}

<div class="card shadow mb-4">
   
   
    <div class="card-body">
        <div class="table-responsive">
        <table class="table table-light2 table-bordered" id="dataTable" width="100%" cellspacing="0">
            <thead>
            <tr>
                
                <th class="text-center">Abreviatura</th>
                <th class="text-center">Concepto</th>
                <th class="text-center">Dias</th>
                <th class="text-center">Horas</th>
                <th class="text-center">Cantidad</th>
                <th class="text-center">Asignación</th>
                <th class="text-center">Deducción</th>
               <th class="text-center"></th>
              
            </tr>
            </thead>
            
            <tbody>
                @if (empty($nominacalculations))
                @else

                    <?php
                        $total_asignacion = 0;
                        $total_deduccion = 0;
                    ?>

                    @foreach ($nominacalculations as $key => $nominacalculation)
                    <tr>
                    
                    <td class="text-center ">
                        <a class="text-dark" href="{{ route('nominacalculations.edit',$nominacalculation->id) }}" title="Modificar">{{$nominacalculation->nominaconcepts['abbreviation']}}</a>  
                    </td>
                    <td class="text-center">{{$nominacalculation->nominaconcepts['description']}}</td>
                    <td class="text-right">{{number_format($nominacalculation->days, 0, '', '.')}}</td>
                    <td class="text-right">{{number_format($nominacalculation->hours, 0, '', '.')}}</td>
                    <td class="text-right">{{number_format($nominacalculation->cantidad, 2, ',', '.')}}</td>

                    @if($nominacalculation->nominaconcepts['sign'] == 'A')
                        <?php
                            $total_asignacion += $nominacalculation->amount;
                            
                        ?>
                        <td class="text-right">{{number_format($nominacalculation->amount, 2, ',', '.')}}</td>
                        <td></td>
                    @else
                        <?php
                            $total_deduccion += $nominacalculation->amount;
                        ?>
                        <td></td>
                        <td class="text-right">{{number_format($nominacalculation->amount, 2, ',', '.')}}</td>
                    @endif

                    @if (Auth::user()->role_id  == '1')
                    
                        <td class="text-right">
                            <a href="{{ route('nominacalculations.delete',$nominacalculation->id) }}" title="Eliminar"><i class="fa fa-trash-alt"></i></a>  
                        </td>
                        
                    @endif
                    </tr>
                    @endforeach
                        <tr>
                    
                        <td class="text-center">------------</td>
                        <td class="text-center">--------------------</td>
                        <td class="text-center">------------</td>
                        <td class="text-center">------------</td>
                        <td class="text-right">Sub Total: </td>
                        <td class="text-right">{{number_format($total_asignacion, 2, ',', '.')}}</td>
                        <td class="text-right">{{number_format($total_deduccion, 2, ',', '.')}}</td>
                        <td></td>
                        
                        </tr>
                        <tr>
                    
                            <td class="text-center">------------</td>
                            <td class="text-center">--------------------</td>
                            <td class="text-center">------------</td>
                            <td class="text-center">------------</td>
                            <td class="text-right">Total: </td>
                            <td class="text-right">{{number_format($total_asignacion - $total_deduccion, 2, ',', '.')}}</td>
                            <td></td>
    
                            <td></td>
    
                            
                        </tr>
                @endif
            </tbody>
        </table>
        </div>
    </div>
</div>

@endsection
@section('javascript')
    <script>
        $('#dataTable').dataTable( {
        "ordering": false,
        "order": [],
            'aLengthMenu': [[50, 100, 150, -1], [50, 100, 150, "All"]]
    } );

    function pdf() {
        var nuevaVentana= window.open("{{ route('nominas.print_nomina_calculation',[$nomina->id,$employee->id])}}","ventana","left=800,top=800,height=800,width=1000,scrollbar=si,location=no ,resizable=si,menubar=no");
    }
    </script>
@endsection