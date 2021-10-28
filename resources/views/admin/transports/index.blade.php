@extends('admin.layouts.dashboard')

@section('content')

<!-- container-fluid -->
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="row py-lg-2">
      <div class="col-md-6">
          <h2>Transportes</h2>
      </div>
      <div class="col-md-6">
        <a href="{{ route('transports.create')}}" class="btn btn-primary btn-lg float-md-right" role="button" aria-pressed="true">Registrar Transporte</a>
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
        <table class="table table-light2 table-bordered" id="dataTable" width="100%" cellspacing="0">
            <thead>
            <tr> 
                <th>Modelo</th>
                <th>Color</th>
                <th>Tipo</th>
                <th>Placa</th>
                <th>Foto del Transporte</th>
                <th>Status</th>
                <th>Tools</th>
            </tr>
            </thead>
            
            <tbody>
                @if (empty($transports))
                @else  
                    @foreach ($transports as $transport)
                        <tr>
                            <td>{{$transport->modelos['description']}}</td>
                            <td>{{$transport->colors['description']}}</td>
                            <td>{{$transport->type}}</td>
                            <td>{{$transport->placa}}</td>
                            <td>{{$transport->photo_transport}}</td>
                            @if($transport->status == 1)
                                <td>Activo</td>
                            @else
                                <td>Inactivo</td>
                            @endif
                            
                            <td>
                                <a href="transports/{{$transport->id }}/edit" title="Editar"><i class="fa fa-edit"></i></a>
                               </td>
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