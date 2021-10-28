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


<!-- container-fluid -->
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="row py-lg-2">
      <div class="col-md-2">
          <h2>Pagos</h2>
      </div>
      <div class="col-md-3">
        <a href="{{ route('expensesandpurchases.index_historial')}}" class="btn btn-info btn-icon-split">
            <span class="icon text-white-50">
                <i class="fas fa-file-alt"></i>
            </span>
            <span class="text">Gastos o Compras</span>
        </a>
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
                <th class="text-center">Fecha</th>
                <th class="text-center">Nº</th>
                <th class="text-center">Referencia</th>
                <th class="text-center">Tipo de Pago</th>
                <th class="text-center">Monto</th>
                <th class="text-center" width="5%"></th>
            </tr>
            </thead>
            
            <tbody>
                @if (empty($payment_expenses))
                @else  
                    @foreach ($payment_expenses as $payment_expense)
                        <tr>
                            <td class="text-center font-weight-bold">{{$payment_expense->created_at->format('d-m-Y')}}</td>
                            
                            <td class="text-center font-weight-bold">
                                <a href="{{ route('payment_expenses.movement',$payment_expense->id_expense) }}" title="Ver Movimiento" class="font-weight-bold text-dark">{{ $payment_expense->id }}</a>
                            </td>
                            
                            <td class="text-center font-weight-bold">{{ $payment_expense->reference}}</td>
                            <td class="text-center font-weight-bold">{{ $payment_expense->type}}</td>
                            <td class="text-right font-weight-bold">{{number_format($payment_expense->amount, 2, ',', '.')}}</td>
                            <td class="text-center">
                                <a href="#" onclick="pdf({{ $payment_expense->id }});" title="Mostrar"><i class="fa fa-file-alt"></i></a>
                                <a href="#"  class="delete" title="Borrar" data-id-expense={{$payment_expense->id_expense}} data-toggle="modal" data-target="#deleteModal"><i class="fa fa-trash text-danger"></i></a>                        
                            </td>
                            
                        </tr>     
                    @endforeach   
                @endif
            </tbody>
        </table>

      
        </div>
    </div>
</div>
<!-- Delete Warning Modal -->
<div class="modal modal-danger fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="Delete" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Eliminar Todos los Pagos</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
            <form action="{{ route('payment_expenses.deleteAllPayments') }}" method="post">
                @csrf
                @method('DELETE')
                <input id="id_expense_modal" type="hidden" class="form-control @error('id_expense_modal') is-invalid @enderror" name="id_expense_modal" readonly required autocomplete="id_expense_modal">
                    
                <h5 class="text-center">Seguro que desea eliminar todos los pagos pertenecientes a esta factura?</h5>
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-danger">Eliminar</button>
            </div>
            </form>
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

        $("body").toggleClass("sidebar-toggled");
        $(".sidebar").toggleClass("toggled");
        if ($(".sidebar").hasClass("toggled")) {
            $('.sidebar .collapse').collapse('hide');
        };
       
        function pdf(id_payment) {
            var nuevaVentana= window.open("{{ route('payment_expenses.pdf',['',''])}}"+"/"+id_payment+"/"+'bolivares',"ventana","left=800,top=800,height=800,width=1000,scrollbar=si,location=no ,resizable=si,menubar=no");   
        }

        $(document).on('click','.delete',function(){
            let id_expense = $(this).attr('data-id-expense');
        
            $('#id_expense_modal').val(id_expense);
        });
    </script>
@endsection
