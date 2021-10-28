@extends('admin.layouts.dashboard')

@section('content')
  
    <!-- container-fluid -->
    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="row py-lg-2">
            <div class="col-md-6">
                <h2>Editar Segundo Sub Segmento</h2>
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
                    <div class="card-body">
                        <form  method="POST"   action="{{ route('twosubsegments.update',$var->id) }}" enctype="multipart/form-data" >
                            @method('PATCH')
                            @csrf()
                
                                <div class="form-group row">
                                    <label for="segmento" class="col-md-4 col-form-label text-md-right">Sub Segmento
                                        @error('segments')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </label>
        
                                    <div class="col-md-6">
                                    <select class="form-control" id="segment_id" name="segment_id">
                                        <option value="{{ $var->subsegment_id }}" {{ old('Segments') }}>{{ $var->subsegments['description'] }}</option>
                                        <option value="nulo">----------------</option>
                                        @if (empty($subsegments))
                                        @else
                                            <div class="dropdown">
                                                @foreach($subsegments as $segment)
                                                    <option value="{{ $segment->id }}" {{ old('Segments') }}>{{ $segment->description }}</option>
                                                @endforeach
                                            </div>
                                        @endif
                                    </select>
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
     soloAlfaNumerico('description');
    
 });
 </script>
@endsection