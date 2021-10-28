@extends('admin.layouts.dashboard')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header text-center font-weight-bold h3">Registro de Segundo Sub Segmento</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('twosubsegments.store') }}">
                        @csrf

                        <div class="form-group row">
                            <label for="segmento" class="col-md-4 col-form-label text-md-right">Sub Segmento</label>

                            <div class="col-md-6">
                            <select  class="form-control" id="segment_id" name="segment_id" required>
                                <option disabled selected>Seleccionar</option>
                                @foreach($subsegments as $segment)
                                    <option value="{{ $segment->id }}" >{{ $segment->description }}</option>
                                @endforeach
                              
                            </select>
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
                        
                    <br>
                        <div class="form-group row mb-0">
                            <div class="col-md-2 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                   Registrar
                                </button>
                            </div>
                            <div class="col-md-2">
                                <a href="{{ route('twosubsegments') }}" id="btnregresar" name="btnregresar" class="btn btn-danger" title="regresar">Volver</a>  
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