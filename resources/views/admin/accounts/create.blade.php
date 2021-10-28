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
        <div class="col-md-12">
            <div class="card">
                <div class="card-header text-center font-weight-bold h3">Registro de Cuentas</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('accounts.store') }}" enctype="multipart/form-data" onsubmit="return validacion()">
                        @csrf
                       
                       
                        <div class="form-group row">
                            <label for="code_one" class="col-md-2 col-form-label text-md-right">Código</label>

                            <div class="col-md-1">
                                <input id="code_one" type="text" class="form-control @error('code_one') is-invalid @enderror" name="code_one" value="{{ old('code_one') }}" required autocomplete="code_one" autofocus>

                                @error('code_one')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            
                           <div class="col-md-1">
                                <input id="code_two" type="text" class="form-control @error('code_two') is-invalid @enderror" name="code_two" value="{{ old('code_two') }}" required autocomplete="code_two" autofocus>

                                @error('code_two')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-md-1">
                                <input id="code_three" type="text" class="form-control @error('code_three') is-invalid @enderror" name="code_three" value="{{ old('code_three') }}" required autocomplete="code_three" autofocus>

                                @error('code_three')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-md-1">
                                <input id="code_four" type="text" class="form-control @error('code_four') is-invalid @enderror" name="code_four" value="{{ old('code_four') }}" required autocomplete="code_four" autofocus>

                                @error('code_four')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <label for="period" class="col-md-2 col-form-label text-md-right">Periodo</label>

                            <div class="col-md-2">
                                <input id="period" type="text" class="form-control @error('period') is-invalid @enderror" name="period" value="{{ $datenow }}" required autocomplete="period">

                                @error('period')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                       
                        <div class="form-group row">
                            <label for="description" class="col-md-2 col-form-label text-md-right">Descripción</label>

                            <div class="col-md-4">
                                <input id="description" type="text" class="form-control @error('description') is-invalid @enderror" name="description" value="{{ old('description') }}" required autocomplete="description">

                                @error('description')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <label for="type" class="col-md-2 col-form-label text-md-right">Tipo</label>

                            <div class="col-md-4">
                            <select class="form-control" name="type" id="type">
                                <option value="Debe">Debe</option>
                                <option value="Haber">Haber</option>
                            </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="level" class="col-md-2 col-form-label text-md-right">Nivel</label>

                            <div class="col-md-4">
                                <input id="level" type="text" class="form-control @error('level') is-invalid @enderror" name="level" value="{{ old('level') }}" required autocomplete="level">

                                @error('level')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <label for="balance_previus" class="col-md-2 col-form-label text-md-right">Saldo Anterior</label>

                            <div class="col-md-4">
                                <input id="balance_previus" type="text" class="form-control @error('balance_previus') is-invalid @enderror" name="balance_previus" value="{{ old('balance_previus') }}" required autocomplete="balance_previus">

                                @error('balance_previus')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            
                        </div>
                        <div class="form-group row">
                            <label for="segmento" class="col-md-2 col-form-label text-md-right">Moneda</label>
                            <div class="col-md-4">
                                <select class="form-control" id="coin" name="coin" title="coin">
                                    <option value="BsS">Bolivares</option>
                                    <option value="$">Dolar</option>
                                </select>
                            </div>
                            <label for="rate" id="rate_label" class="col-md-2 col-form-label text-md-right">Tasa del Dia</label>

                            <div class="col-md-4">
                                <input id="rate" type="text" class="form-control @error('rate') is-invalid @enderror" name="rate" value="{{ $rate }}" autocomplete="rate">

                                @error('rate')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <br>
                        <div class="form-group row mb-0">
                            <div class="col-md-3 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                   Registrar Cuenta
                                </button>
                            </div>
                            <div class="col-md-2">
                                <a href="{{ route('accounts') }}" id="btnfacturar" name="btnfacturar" class="btn btn-danger" title="facturar">Volver</a>  
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
	 $(document).ready(function () {
            $("#code_one").mask('00', { reverse: true });
            
    });
    $(document).ready(function () {
            $("#code_two").mask('000', { reverse: true });
            
    });
    $(document).ready(function () {
            $("#code_three").mask('000', { reverse: true });
            
    });
    $(document).ready(function () {
            $("#code_four").mask('000', { reverse: true });
            
    });
    $(document).ready(function () {
            $("#level").mask('0', { reverse: true });
            
    });
    $(document).ready(function () {
            $("#period").mask('0000', { reverse: true });
            
    });
        
    $(document).ready(function () {
        $("#balance_previus").mask('000.000.000.000.000.000.000,00', { reverse: true });
    });

    $(document).ready(function () {
        $("#rate").mask('000.000.000.000.000.000.000,00', { reverse: true });
    });

    function validacion() {

        let level = document.getElementById("level").value; 

        if ((level > 0) && (level < 5)) {
          
            return true;
        }
        else {
            alert('El nivel debe estar entre 1 y 4');
            return false;
        }
    }


    </script>
@endsection


@section('validacion_usuario')
<script>
    
$(function(){
    soloNumeroPunto('code');
    soloAlfaNumerico('description');
    
});

</script>
@endsection