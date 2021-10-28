@extends('admin.layouts.dashboard')

@section('content')
  
    <!-- container-fluid -->
    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="row py-lg-2">
            <div class="col-md-6">
                <h2>Editar Unidad de Medida</h2>
            </div>

        </div>
    </div>
    <!-- /container-fluid -->

    {{-- VALIDACIONES-RESPUESTA--}}
@include('admin.layouts.success')   {{-- SAVE --}}
@include('admin.layouts.danger')    {{-- EDITAR --}}
@include('admin.layouts.delete')    {{-- DELELTE --}}
{{-- VALIDACIONES-RESPUESTA --}}

@if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{$error}}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header">Editar Unidad de Medida</div>
    
                    <div class="card-body">
            <form  method="POST"   action="{{ route('unitofmeasures.update',$var->id) }}" enctype="multipart/form-data" >
                @method('PATCH')
                @csrf()
                
                            <form>
                                <div class="form-group row">
                                    
                                    <label for="code" class="col-md-4 col-form-label text-md-right">Código</label>
                                    
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" id="code" name="code" value="{{ $var->code }}" >
                                    </div>
                                    
                                </div>
                              
                                <div class="form-group row">
                                    
                                    <label for="descripcion" class="col-md-4 col-form-label text-md-right">Descripción</label>
                                    
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" id="description" name="description" value="{{ $var->description }}" >
                                    </div>
                                    
                                </div>
                               
                                
                            
                                <div class="form-group row">
                                    <label for="segmento" class="col-md-4 col-form-label text-md-right">Status</label>
                                    <div class="col-md-4">
                                        <select class="form-control" id="status" name="status" title="status">
                                            @if($var->status == 1)
                                                <option value="1">Activo</option>
                                            @else
                                                <option value="0">Inactivo</option>
                                            @endif
                                            <option value="nulo">----------------</option>
                                            
                                            <div class="dropdown">
                                                <option value="1">Activo</option>
                                                <option value="0">Inactivo</option>
                                            </div>
                                            
                                            
                                        </select>
                                    </div>
                                </div>
                            <br>
                                <div class="form-group row">
                                    <div class="form-group col-sm-2">
                                    </div>    
                                    <div class="form-group col-sm-4">
                                        <button type="submit" class="btn btn-success btn-block"><i class="fa fa-send-o"></i>Actualizar</button>
                                    </div>
                                    <div class="form-group col-sm-4">
                                        <a href="{{ route('segments') }}" name="danger" type="button" class="btn btn-danger btn-block">Cancelar</a>
                                    </div>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
 @endsection
@section('validacion')
  <script>    
    $(function(){
        soloAlfaNumerico('code');
                       
    });
   </script>
@endsection