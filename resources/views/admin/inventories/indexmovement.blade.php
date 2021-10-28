@extends('admin.layouts.dashboard')

@section('content')

<ul class="nav nav-tabs justify-content-center" id="myTab" role="tablist">
    <li class="nav-item" role="presentation">
      <a class="nav-link font-weight-bold" style="color: black;" id="home-tab"  href="{{ route('products') }}" role="tab" aria-controls="home" aria-selected="true">Productos</a>
    </li>
    <li class="nav-item" role="presentation">
      <a class="nav-link font-weight-bold" style="color: black;" id="profile-tab"  href="{{ route('inventories') }}" role="tab" aria-controls="profile" aria-selected="false">Inventarios</a>
    </li>
    <li class="nav-item" role="presentation">
      <a class="nav-link active font-weight-bold" style="color: black;" id="contact-tab"  href="{{ route('inventories.movement') }}" role="tab" aria-controls="contact" aria-selected="false">Movimientos de Inventario</a>
    </li>
    
  </ul>
  <!-- /.container-fluid -->
  {{-- VALIDACIONES-RESPUESTA--}}
  @include('admin.layouts.success')   {{-- SAVE --}}
  @include('admin.layouts.danger')    {{-- EDITAR --}}
  @include('admin.layouts.delete')    {{-- DELELTE --}}
  {{-- VALIDACIONES-RESPUESTA --}}
<!-- DataTales Example -->
<div class="card shadow mb-4">
    <div class="card-body">
        <div class="container">
            @if (session('flash'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{session('flash')}}
                <button type="button" class="close" data-dismiss="alert" aria-label="close">
                    <span aria-hidden="true">&times; </span>
                </button>
            </div>   
        @endif
        </div>
        <div class="table-responsive">
        <table class="table table-light2 table-bordered" id="dataTable" width="100%" cellspacing="0">
            <thead>
            <tr> 
                <th>Fecha</th>
                <th>Producto</th>
                <th>Movimiento</th>
                <th>Tipo</th>
                <th>Cantidad</th>
                <th>Costo</th>
                <th>Costo Total</th>
                
            </tr>
            </thead>
            
            <tbody>
                @if (empty($inventories_quotations))
                @else  
                    @foreach ($inventories_quotations as $var)
                        <tr>
                            <td>{{ $var->date_billing ?? $var->date_delivery_note ?? '' }}</td>
                            <td>{{ $var->description ?? ''}}</td>
                            @if (isset($var->date_billing))
                                <td>Factura (
                                    <a href="{{ route('quotations.createfacturado',[$var->id_quotation,$var->coin_quotation ?? 'bolivares']) }}" title="Ver Factura" class="font-weight-bold text-dark">{{ $var->id_quotation }}</a>
                                    )
                                </td>
                                <td>Salida</td>
                            @elseif(isset($var->date_delivery_note))
                                <td>Nota de Entrega (
                                    <a href="{{ route('quotations.createfacturado',[$var->id_quotation,$var->coin_quotation ?? 'bolivares']) }}" title="Ver Factura" class="font-weight-bold text-dark">{{ $var->id_quotation }}</a>
                                    )
                                </td>
                                <td>Salida</td>
                            @else
                                <td>Otro</td>
                                <td>Salida</td>
                            
                            @endif
                            
                            <td>{{number_format($var->amount_quotation ?? '' , 0, '', '.')}}</td> 
                            <td>{{number_format($var->amount_inventory, 2, ',', '.')}}</td>
                            <?php
                                $total = $var->amount_inventory * $var->amount_quotation; 
                            ?>

                            <td>{{number_format($total, 2, ',', '.')}}</td>
                           
                           
                        </tr>     
                    @endforeach   
                @endif
            </tbody>
        </table>
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
