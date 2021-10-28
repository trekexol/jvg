@extends('admin.layouts.dashboard')

@section('content')
  
    <!-- container-fluid -->
    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="row py-lg-2">
            <div class="col-md-6">
                <h2>Editar Cuenta</h2>
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

    <div class="card shadow mb-4">
        <div class="card-body">
            <form  method="POST"   action="{{ route('accounts.update',$var->id) }}" enctype="multipart/form-data" >
                @method('PATCH')
                @csrf()
                <div class="container py-2">
                    <div class="row">
                        <div class="col-12 ">
                            <form >
                                <div class="form-group row">
                                    <label for="period" class="col-md-2 col-form-label text-md-right">Periodo</label>
        
                                    <div class="col-md-4">
                                        <input id="period" type="number" class="form-control @error('period') is-invalid @enderror" name="period" value="{{ $var->period }}" required autocomplete="period">
        
                                        @error('period')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <label for="code" class="col-md-2 col-form-label text-md-right">Código</label>
        
                                    <div class="col-md-4">
                                        <input id="code" type="text" class="form-control @error('code') is-invalid @enderror" name="code" value="{{ $var->code }}" required autocomplete="code" autofocus>
        
                                        @error('code')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
        
                               
                                <div class="form-group row">
                                    <label for="description" class="col-md-2 col-form-label text-md-right">Descripción</label>
        
                                    <div class="col-md-4">
                                        <input id="description" type="text" class="form-control @error('description') is-invalid @enderror" name="description" value="{{ $var->description }}" required autocomplete="description">
        
                                        @error('description')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    
                                    <label for="segmento" class="col-md-2 col-form-label text-md-right">Tipo</label>
                                    <div class="col-md-4">
                                        <select class="form-control" id="type" name="type" title="type">
                                            @if($var->type == 1)
                                                <option value="Debe">Debe</option>
                                            @else
                                                <option value="Haber">Haber</option>
                                            @endif
                                            <option value="nulo">----------------</option>
                                            
                                            <div class="dropdown">
                                                <option value="Debe">Debe</option>
                                                <option value="Haber">Haber</option>
                                            </div>
                                            
                                            
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="level" class="col-md-2 col-form-label text-md-right">Nivel</label>
        
                                    <div class="col-md-4">
                                        <input id="level" type="number" class="form-control @error('level') is-invalid @enderror" name="level" value="{{ $var->level }}" required autocomplete="level">
        
                                        @error('level')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <label for="balance_previus" class="col-md-2 col-form-label text-md-right">Balance Previo</label>
        
                                    <div class="col-md-4">
                                        <input id="balance_previus" type="number" class="form-control @error('balance_previus') is-invalid @enderror" name="balance_previus" value="{{ $var->balance_previus }}" required autocomplete="balance_previus">
        
                                        @error('balance_previus')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    
                                </div>
        
                                <div class="form-group row">
                                    <label for="debe" class="col-md-2 col-form-label text-md-right">Debe</label>
        
                                    <div class="col-md-4">
                                        <input id="debe" type="number" class="form-control @error('debe') is-invalid @enderror" name="debe" value="{{ $var->debe }}" required autocomplete="debe">
        
                                        @error('debe')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <label for="haber" class="col-md-2 col-form-label text-md-right">Haber</label>
        
                                    <div class="col-md-4">
                                        <input id="haber" type="number" class="form-control @error('haber') is-invalid @enderror" name="haber" value="{{ $var->haber }}" required autocomplete="haber">
        
                                        @error('haber')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="segmento" class="col-md-2 col-form-label text-md-right">Status</label>
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
                                <div class="form-group row mb-0">
                                    <div class="col-md-6 offset-md-4">
                                        <button type="submit" class="btn btn-primary">
                                           Actualizar Cuenta
                                        </button>
                                        <a href="{{ route('accounts') }}" name="danger" type="button" class="btn btn-danger">Cancelar</a>
                                    </div>
                                    
                                </div>
                            </form>
                        </div>
                    </div>
@endsection

@section('validacion_usuario')
<script>
    
$(function(){
    soloNumeroPunto('code');
    soloAlfaNumerico('description');
    
});

</script>
@endsection
            