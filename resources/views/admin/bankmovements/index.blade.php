@extends('admin.layouts.dashboard')

@section('content')


  <!-- /.container-fluid -->
  {{-- VALIDACIONES-RESPUESTA--}}
  @include('admin.layouts.success')   {{-- SAVE --}}
  @include('admin.layouts.danger')    {{-- EDITAR --}}
  @include('admin.layouts.delete')    {{-- DELELTE --}}
  {{-- VALIDACIONES-RESPUESTA --}}
<!-- DataTales Example -->
<div class="row justify-content-left">
    <div class="col-md-2">
    </div>
    <div class="col-md-4">
        <a href="{{ route('bankmovements.indexmovement') }}" class="btn btn-info" title="Transferencia">Movimientos Bancarios</a>
    </div>
</div>
<br>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header text-center font-weight-bold h3">Consulta de Caja Y Bancos</div>

                <div class="card-body">
                        <div class="table-responsive">
                        <table class="table table-light2 table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                            <tr> 
                            
                                <th class="text-center">Descripción</th>
                                
                                <th class="text-center">Saldo Actual</th>
                            
                                <th class="text-center">Opciones</th>
                            </tr>
                            </thead>
                            
                            <tbody>
                                @if (empty($accounts))
                                @else  
                                <?php
                                    $intercalar = true;
                                    $total = 0;
                                ?>
                            
                                    @foreach ($accounts as $var)
                                    <tr>
                                        @if(($var))
                                        <?php 
                                            $intercalar = false;
                                            $total += ($var->balance_previus + $var->debe) - $var->haber;
                                        ?>
                                            <td style="text-align:right; color:black;">{{$var->description}}</td>
                                        
                                            <td style="text-align:right; color:black;">{{number_format(($var->balance_previus + $var->debe) - $var->haber, 2, ',', '.')}}</td>
                                                            
                                        
                                            <td style="text-align:right; color:black;">  
                                                <a href="{{ route('bankmovements.createdeposit',$var->id) }}" title="Depositar"><i class="fa fa-download"></i></a>
                                                <a href="{{ route('bankmovements.createretirement',$var->id) }}" title="Retiro"><i class="fa fa-upload"></i></a>
                                                <a href="{{ route('bankmovements.createtransfer',$var->id) }}" title="Transferencia"><i class="fa fa-exchange-alt"></i></a>
                                          </td>
                                         
                                        @endif
                                    </tr>  
                                    @endforeach   
                                    <tr>
                                       
                                        <td style="background: #E0D7CD; text-align:right; color:black;">Totales</td>
                                        <td style="background: #E0D7CD; text-align:right; color:black;">{{number_format($total, 2, ',', '.')}}</td>
                                        
                                        <td style="background: #E0D7CD; text-align:right; color:black;"></td>
                                    
                                        </tr>
                                @endif
                            </tbody>
                        </table>
                        </div>
    </div>
</div>
</div>
</div>
</div>
</div>
  
@endsection

@section('javascript')
    <script>
    $('#dataTable').DataTable({
        "ordering": false,
        "order": [],
        'aLengthMenu': [[50, 100, 150, -1], [50, 100, 150, "All"]]
    });
    </script> 
@endsection