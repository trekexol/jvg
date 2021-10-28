@extends('admin.layouts.dashboard')

@section('content')

<!-- container-fluid -->
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="row py-lg-2">
      <div class="col-md-6">
          <h2>Histórico de Transportes</h2>
      </div>
      <div class="col-md-6">
        <a href="{{ route('historictransports.selecttransport')}}" class="btn btn-primary btn-lg float-md-right" role="button" aria-pressed="true">Registrar Histórico de Transporte</a>
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
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Histórico de Transportes</h6>
    </div>
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
                <th>Empleado</th>
                <th>Transporte</th>
                <th>Fecha Inicio</th>
                <th>Fecha Final</th>
                 
                <th>Tools</th>
            </tr>
            </thead>
            
            <tbody>
                @if (empty($employees))
                @else  
                     @foreach ($employees as $emp)

                        @foreach ($emp->transports as $var)
                        <tr>
                            <td>{{ $emp->nombres}}</td>
                            <td>{{ $var->placa}}</td>
                            <td>{{ $var->pivot->date_begin}}</td>
                            <td>{{ $var->pivot->date_end}}</td>
                           
                          
                            <td>
                                <a href="historictransports/{{$emp->id }}/edit" title="Editar"><i class="fa fa-edit"></i></a>
                               </td>
                        </tr>  
                        @endforeach   
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