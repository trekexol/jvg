@extends('admin.layouts.dashboard')

@section('content')

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
                <div class="card-header text-center font-weight-bold h3">Registro de Tipos de Salario</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('salarytypes.store') }}">
                        @csrf

                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">Nombre</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>

                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="description" class="col-md-4 col-form-label text-md-right">Descripción</label>

                            <div class="col-md-6">
                                <input id="description" type="text" class="form-control @error('description') is-invalid @enderror" name="description" value="{{ old('description') }}" required autocomplete="description">

                                @error('description')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                       
                        <div class="form-group row">
                            <label for="rol" class="col-md-4 col-form-label text-md-right">Status</label>

                            <div class="col-md-6">
                            <select class="form-control" name="status" id="status">
                                <option value="1">Activo</option>
                                <option value="0">Inactivo</option>
                            </select>
                            </div>
                        </div>
                    <br>
                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                   Registrar Salario
                                </button>
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
        soloAlfaNumerico('name');
        soloAlfaNumerico('description');
        
       
    });
    </script>
@endsection