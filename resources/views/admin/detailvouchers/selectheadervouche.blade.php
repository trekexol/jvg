@extends('admin.layouts.dashboard')

@section('content')

<div class="container-fluid">
    <div class="row py-lg-2">
       
        <div class="col-md-6">
            <h2>Seleccione un Comprobante Cabecera</h2>
        </div>
        
    
    </div>
</div>

   

  {{-- VALIDACIONES-RESPUESTA--}}
@include('admin.layouts.success')   {{-- SAVE --}}
@include('admin.layouts.danger')    {{-- EDITAR --}}
@include('admin.layouts.delete')    {{-- DELELTE --}}
{{-- VALIDACIONES-RESPUESTA --}}

<!-- container-fluid -->
<div class="container-fluid">

<div class="card shadow mb-4">
    <div class="card-body">
        <div class="table-responsive">
        <table class="table table-light2 table-bordered" id="dataTable" width="100%" cellspacing="0">
            <thead>
            <tr>
                <th></th>
                <th>Fecha</th>
                <th>Referencia</th>
                <th>Descripción</th>
                
                
              
            </tr>
            </thead>
            
            <tbody>
                @if (empty($headervouchers))
                @else
                    @foreach ($headervouchers as $key => $var)
                    <tr>
                        <td>
                            <a href="{{route('detailvouchers.createselect',$var->id) }}" title="Seleccionar"><i class="fa fa-check"></i></a>  
                            </td>
                    <td>{{$var->date}}</td>
                    <td><a href="{{route('detailvouchers.createselect',$var->id) }}" title="Seleccionar">{{$var->reference}}</a></td>
                    <td>{{$var->description}}</td>
                    
                    </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
        </div>
    </div>
</div>

@endsection
