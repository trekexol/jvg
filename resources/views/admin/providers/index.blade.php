@extends('admin.layouts.dashboard')

@section('content')

<!-- container-fluid -->
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="row py-lg-2">
      <div class="col-md-6">
          <h2>Proveedores</h2>
      </div>
      <div class="col-md-6">
        <a href="{{ route('providers.create')}}" class="btn btn-primary float-md-right" role="button" aria-pressed="true">Registrar un Proveedor</a>
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
        <div class="table-responsive">
            <table class="table table-light2 table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                <tr> 
                    <th>Código Proveedor</th>
                    <th>Razón Social</th>
                    <th>Dirección</th>
                    <th>Ciudad</th>
                    <th>Pais</th>
                    <th>Telefono</th>
                    <th></th>
                </tr>
                </thead>
                
                <tbody>
                    @if (empty($providers))
                    @else  
                        @foreach ($providers as $var)
                            <tr>
                                <td>{{$var->code_provider}}</td>
                                <td>{{$var->razon_social}}</td>
                                <td>{{$var->direction}}</td>
                                <td>{{$var->city}}</td>
                                <td>{{$var->country}}</td>
                                <td>{{$var->phone1}}</td>
                                
                                <td>
                                    <a href="providers/{{$var->id }}/edit" title="Editar"><i class="fa fa-edit"></i></a>
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