@extends('admin.layouts.dashboard')

@section('content')

<ul class="nav nav-tabs justify-content-center" id="myTab" role="tablist">
    <li class="nav-item" role="presentation">
        <a class="nav-link font-weight-bold" style="color: black;" id="home-tab"  href="{{ route('expensesandpurchases') }}" role="tab" aria-controls="home" aria-selected="true">Gastos y Compras</a>
    </li>
    <li class="nav-item" role="presentation">
        <a class="nav-link font-weight-bold" style="color: black;" id="home-tab"  href="{{ route('expensesandpurchases.indexdeliverynote') }}" role="tab" aria-controls="home" aria-selected="true">Notas de Entrega</a>
    </li>
    <li class="nav-item" role="presentation">
        <a class="nav-link active font-weight-bold" style="color: black;" id="profile-tab"  href="{{ route('expensesandpurchases.index_historial') }}" role="tab" aria-controls="profile" aria-selected="false">Historial</a>
    </li>
    <li class="nav-item" role="presentation">
        <a class="nav-link font-weight-bold" style="color: black;" id="profile-tab"  href="{{ route('anticipos.index_provider') }}" role="tab" aria-controls="profile" aria-selected="false">Anticipo a Proveedores</a>
    </li>
</ul>
<form method="POST" action="{{ route('expensesandpurchases.multipayment') }}" enctype="multipart/form-data" >
    @csrf
<!-- container-fluid -->
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="row py-lg-2">
      <div class="col-md-6">
          <h2>Historial de Gastos y Compras</h2>
      </div>
      <div class="col-md-2">
        <a href="{{ route('payment_expenses')}}" class="btn btn-info btn-icon-split">
            <span class="icon text-white-50">
                <i class="fas fa-hand-holding-usd"></i>
            </span>
            <span class="text">Pagos</span>
        </a>
    </div>
      <div class="col-md-4">
        <button type="submit" title="Agregar" id="btncobrar" class="btn btn-primary  float-md-right" >Cobrar Gastos o Compras</a>
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
                
                <th class="text-center">Factura de Compra</th>
                <th class="text-center">N° de Control/Serie</th>
                <th class="text-center">Proveedor</th>
                <th class="text-center">Fecha</th>
                <th class="text-center">Monto</th>
                <th class="text-center">Iva</th>
                <th class="text-center">Total</th>
                <th ></th>
                <th ></th>
               
            </tr>
            </thead>
            
            <tbody>
                @if (empty($expensesandpurchases))
                @else  
                    @foreach ($expensesandpurchases as $expensesandpurchase)
                        <tr>
                           
                            <td class="text-center">
                                <a href="{{ route('expensesandpurchases.create_expense_voucher',[$expensesandpurchase->id,$expensesandpurchase->coin ?? 'bolivares']) }}" title="Ver Detalle" class="text-center text-dark font-weight-bold">
                                    {{$expensesandpurchase->invoice ?? ''}}
                                </a>
                            </td>
                            <td class="text-center">{{$expensesandpurchase->serie ?? ''}}</td>
                            <td class="text-center">{{$expensesandpurchase->providers['razon_social'] ?? ''}}</td>
                            <td class="text-center">{{$expensesandpurchase->date}}</td>
                            <td class="text-right">{{number_format($expensesandpurchase->amount, 2, ',', '.')}}</td>
                            <td class="text-right">{{number_format($expensesandpurchase->amount_iva, 2, ',', '.')}}</td>
                            <td class="text-right">{{number_format($expensesandpurchase->amount_with_iva, 2, ',', '.')}}</td>
                            @if ($expensesandpurchase->status == "C")
                            <td class="text-center font-weight-bold">
                                <a href="{{ route('expensesandpurchases.create_expense_voucher',[$expensesandpurchase->id,$expensesandpurchase->coin ?? 'bolivares']) }}" title="Ver Detalle" class="text-center text-success font-weight-bold">Pagado</a>
                            </td>
                            <td>
                            </td>
                            @elseif ($expensesandpurchase->status == "X")
                            <td class="text-center font-weight-bold">
                                Reversado
                            </td>
                            <td>
                            </td>
                            @else
                            <td class="text-center font-weight-bold">
                                <a href="{{ route('expensesandpurchases.create_payment_after',[$expensesandpurchase->id,$expensesandpurchase->coin]) }}" title="Cobrar Factura" class="font-weight-bold text-dark">Click para Pagar</a>
                            </td>
                            <td>
                                <input type="checkbox" name="check{{ $expensesandpurchase->id }}" value="{{ $expensesandpurchase->id }}" onclick="buttom();" id="flexCheckChecked">    
                            </td>
                            @endif
                        </tr>     
                    @endforeach   
                @endif
            </tbody>
        </table>
        </div>
    </div>
</div>
</form>
@endsection

@section('javascript')

<script>
    $('#dataTable').dataTable( {
      "ordering": false,
      "order": [],
        'aLengthMenu': [[50, 100, 150, -1], [50, 100, 150, "All"]]
    } );

    $("body").toggleClass("sidebar-toggled");
        $(".sidebar").toggleClass("toggled");
        if ($(".sidebar").hasClass("toggled")) {
            $('.sidebar .collapse').collapse('hide');
        };

    $("#btncobrar").hide();

    function buttom(){
        
        $("#btncobrar").show();
    }
</script>
    
@endsection
