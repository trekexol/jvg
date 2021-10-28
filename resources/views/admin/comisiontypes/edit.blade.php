@extends('admin.layouts.dashboard')

@section('content')



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
                    <div class="card-header">Editar Indices BCV</div>
    
                    <div class="card-body">
                    <form  method="POST"   action="{{ route('comisiontypes.update',$var->id) }}" enctype="multipart/form-data" >
                        @method('PATCH')
                        @csrf()
                      
                       
                       
                        <div class="form-group row">
                            <label for="description" class="col-md-4 col-form-label text-md-right">Descripción</label>

                            <div class="col-md-6">
                                <input id="description" type="text" class="form-control @error('description') is-invalid @enderror" name="description" value="{{ $var->description }}" required autocomplete="description" min="2021">

                                @error('description')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                       
                       
                        <div class="form-group row">
                            <label for="status" class="col-md-4 col-form-label text-md-right">Status</label>
                            <div class="col-md-6">
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
                                <a href="{{ route('comisiontypes') }}" name="danger" type="button" class="btn btn-danger btn-block">Cancelar</a>
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