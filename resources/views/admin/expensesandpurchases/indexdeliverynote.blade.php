@extends('admin.layouts.dashboard')

@section('content')
<ul class="nav nav-tabs justify-content-center" id="myTab" role="tablist">
    <li class="nav-item" role="presentation">
        <a class="nav-link font-weight-bold" style="color: black;" id="home-tab"  href="{{ route('expensesandpurchases') }}" role="tab" aria-controls="home" aria-selected="true">Gastos y Compras</a>
    </li>
    <li class="nav-item" role="presentation">
        <a class="nav-link active font-weight-bold" style="color: black;" id="home-tab"  href="{{ route('expensesandpurchases.indexdeliverynote') }}" role="tab" aria-controls="home" aria-selected="true">Notas de Entrega</a>
    </li>
    <li class="nav-item" role="presentation">
        <a class="nav-link font-weight-bold" style="color: black;" id="profile-tab"  href="{{ route('expensesandpurchases.index_historial') }}" role="tab" aria-controls="profile" aria-selected="false">Historial</a>
    </li>
    <li class="nav-item" role="presentation">
        <a class="nav-link font-weight-bold" style="color: black;" id="profile-tab"  href="{{ route('anticipos.index_provider') }}" role="tab" aria-controls="profile" aria-selected="false">Anticipo a Proveedores</a>
    </li>
</ul>



<!-- container-fluid -->
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="row py-lg-2">
      <div class="col-md-6">
          <h2>Notas de Entrega</h2>
      </div>
      
    </div>
  </div>
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
        <table class="table table-light2 table-bordered" id="dataTable" width="100%" cellspacing="0" >
            <thead>
            <tr> 
                <th class="text-center"></th>
                <th class="text-center">N° de Control/Serie</th>
                <th class="text-center">Proveedor</th>
                <th class="text-center">Fecha del Gasto o Compra</th>
                <th class="text-center">Fecha de la Nota de Entrega</th>
                <th class="text-center">Total</th>
               
            </tr>
            </thead>
            
            <tbody>
                @if (empty($expenses))
                @else  
                    @foreach ($expenses as $expense)
                        <tr>
                            <td class="text-center">
                            <a href="{{ route('expensesandpurchases.create_payment',[$expense->id,$expense->coin])}}" title="Seleccionar"><i class="fa fa-check"></i></a>
                            <a href="{{ route('expensesandpurchases.createdeliverynote',[$expense->id,$expense->coin])}}" title="Mostrar"><i class="fa fa-file-alt"></i></a>
                           </td>
                            <td class="text-center">{{$expense->serie}}</td>
                            <td class="text-center">{{ $expense->providers['razon_social']}}</td>
                            <td class="text-center">{{$expense->date}}</td>
                            <td class="text-center">{{$expense->date_delivery_note}}</td>
                            @if ($expense->coin == 'bolivares')
                                <td class="text-right">{{ number_format($expense->amount_with_iva ?? 0, 2, ',', '.')}}</td>
                            @else
                                <td class="text-right">{{ number_format(($expense->amount_with_iva ?? 0)/$expense->rate, 2, ',', '.')}}</td>
                            @endif
                            
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