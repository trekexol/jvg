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
                <div class="card-header">Registro de Tipo de Nómina</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('paymenttypes.store') }}">
                        @csrf

                       

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
                            <label for="type" class="col-md-4 col-form-label text-md-right">Tipo</label>

                            <div class="col-md-6">
                            <select class="form-control" name="type" id="type">
                                <option value="Efectivo">Efectivo</option>
                                <option value="Credito">Crédito</option>
                                <option value="Anticipo">Anticipo</option>
                            </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="credit_days" class="col-md-4 col-form-label text-md-right">Días de Crédito</label>

                            <div class="col-md-6">
                                <input id="credit_days" type="number" class="form-control @error('credit_days') is-invalid @enderror" name="credit_days" value="{{ old('credit_days') }}" required autocomplete="credit_days">

                                @error('credit_days')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="pide_ref" class="col-md-4 col-form-label text-md-right">Pide Referencia</label>
                            <div class="col-md-6">
                            <select class="form-control" name="pide_ref" id="pide_ref">
                                <option value="Si">Si</option>
                                <option value="No">No</option>
                            </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="small_box" class="col-md-4 col-form-label text-md-right">Caja Chica</label>

                            <div class="col-md-6">
                                <input id="small_box" type="text" class="form-control @error('small_box') is-invalid @enderror" name="small_box" value="{{ old('small_box') }}" required autocomplete="small_box">

                                @error('small_box')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="nature" class="col-md-4 col-form-label text-md-right">Naturaleza</label>

                            <div class="col-md-6">
                            <select class="form-control" name="nature" id="nature">
                                <option value="Efectivo">Efectivo</option>
                                <option value="Credito">Crédito</option>
                                <option value="Anticipo">Anticipo</option>
                            </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="point" class="col-md-4 col-form-label text-md-right">Punto</label>
                            <div class="col-md-6">
                            <select class="form-control" name="point" id="point">
                                <option value="Si">Si</option>
                                <option value="No">No</option>
                            </select>
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
                                   Registrar Nómina
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
        soloAlfaNumerico('description');
        soloAlfaNumerico('small_box');
       
    });
    </script>
@endsection