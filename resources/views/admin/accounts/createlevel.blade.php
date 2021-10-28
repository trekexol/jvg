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
                <div class="card-header text-center font-weight-bold h3">Insertar una nueva Cuenta</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('accounts.storeNewLevel') }}" enctype="multipart/form-data">
                        @csrf
                       
                        <input type="hidden" name="code_one" value="{{$var->code_one}}" readonly>
                        <input type="hidden" name="code_two" value="{{$var->code_two}}" readonly>
                        <input type="hidden" name="code_three" value="{{$var->code_three}}" readonly>
                        <input type="hidden" name="code_four" value="{{$var->code_four}}" readonly>
                        <input type="hidden" name="code_five" value="{{$var->code_five}}" readonly>

                        <input type="hidden" name="period" value="{{$var->period}}" readonly>

                        <div class="form-group row">
                            <label for="code" class="col-md-2 col-form-label text-md-right">Código</label>

                            <div class="col-md-4">
                                <input id="code" type="text" class="form-control @error('code') is-invalid @enderror" name="code" value="{{$var->code_one}}.{{$var->code_two}}.{{$var->code_three}}.{{$var->code_four}}.{{$var->code_five}}" required autocomplete="code" readonly autofocus>

                                @error('code')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <label for="level" class="col-md-2 col-form-label text-md-right">Nivel</label>

                            <div class="col-md-4">
                                <input id="level" type="number" class="form-control @error('level') is-invalid @enderror" name="level" value="{{ $var->level }}" readonly required autocomplete="level">

                                @error('level')
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
                           
                            <label for="balance_previus" class="col-md-2 col-form-label text-md-right">Balance Previo</label>

                            <div class="col-md-4">
                                <input id="balance_previus" type="text" autocomplete="off" placeholder='0,00' value="0,00" class="form-control @error('balance_previus') is-invalid @enderror" name="balance_previus" value="{{ old('balance_previus') }}"  required >

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
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                   Registrar Cuenta
                                </button>
                                <a href="{{ route('accounts') }}" name="danger" type="button" class="btn btn-danger">Cancelar</a>
                           
                            </div>
                            
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('validacion_usuario')

<script>

$(document).ready(function () {
    $("#balance_previus").mask('000.000.000.000.000.000.000,00', { reverse: true });
});

$(document).ready(function () {
    $("#rate").mask('000.000.000.000.000.000.000,00', { reverse: true });
});


$(function(){
    soloNumeroPunto('code');
    soloAlfaNumerico('description');
    
});

</script>
@endsection