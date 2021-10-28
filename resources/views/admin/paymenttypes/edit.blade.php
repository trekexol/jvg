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
                    <div class="card-header">Editar Tipo de Pago</div>
    
                    <div class="card-body">
                    <form  method="POST"   action="{{ route('paymenttypes.update',$var->id) }}" enctype="multipart/form-data" >
                        @method('PATCH')
                        @csrf()
                      
                       
                        <div class="form-group row">
                            <label for="description" class="col-md-4 col-form-label text-md-right">Descripción</label>

                            <div class="col-md-6">
                                <input id="description" type="text" class="form-control @error('description') is-invalid @enderror" name="description" value="{{ $var->description }}" required autocomplete="description">

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
                                <select class="form-control" id="nature" name="type" title="type">
                                    @if($var->type == 'Efectivo')
                                        <option value="Efectivo">Efectivo</option>
                                    @elseif($var->type == 'Credito')
                                        <option value="Credito">Crédito</option>
                                    @else
                                        <option value="Anticipo">Anticipo</option>
                                    @endif
                                    
                                    <option value="nulo">----------------</option>
                                    
                                    <div class="dropdown">
                                        <option value="Efectivo">Efectivo</option>
                                        <option value="Credito">Crédito</option>
                                        <option value="Anticipo">Anticipo</option>
                                    </div>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="credit_days" class="col-md-4 col-form-label text-md-right">Días de Crédito</label>

                            <div class="col-md-6">
                                <input id="credit_days" type="number" class="form-control @error('credit_days') is-invalid @enderror" name="credit_days" value="{{ $var->credit_days }}" required autocomplete="credit_days">

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
                                <select class="form-control" id="pide_ref" name="pide_ref" title="pide_ref">
                                    @if($var->pide_ref == 'Si')
                                        <option value="Si">Si</option>
                                    @else
                                        <option value="No">No</option>
                                    @endif
                                    <option value="nulo">----------------</option>
                                    
                                    <div class="dropdown">
                                        <option value="Si">Si</option>
                                        <option value="No">No</option>
                                    </div>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="small_box" class="col-md-4 col-form-label text-md-right">Caja Chica</label>

                            <div class="col-md-6">
                                <input id="small_box" type="text" class="form-control @error('small_box') is-invalid @enderror" name="small_box" value="{{ $var->small_box }}" required autocomplete="small_box">

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
                                <select class="form-control" id="nature" name="nature" title="nature">
                                    @if($var->nature == 'Efectivo')
                                        <option value="Efectivo">Efectivo</option>
                                    @elseif($var->nature == 'Credito')
                                        <option value="Credito">Crédito</option>
                                    @else
                                        <option value="Anticipo">Anticipo</option>
                                    @endif
                                    
                                    <option value="nulo">----------------</option>
                                    
                                    <div class="dropdown">
                                        <option value="Efectivo">Efectivo</option>
                                        <option value="Credito">Crédito</option>
                                        <option value="Anticipo">Anticipo</option>
                                    </div>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="point" class="col-md-4 col-form-label text-md-right">Punto</label>
                            <div class="col-md-6">
                                <select class="form-control" id="point" name="point" title="point">
                                    @if($var->point == 'Si')
                                        <option value="Si">Si</option>
                                    @else
                                        <option value="No">No</option>
                                    @endif
                                    <option value="nulo">----------------</option>
                                    
                                    <div class="dropdown">
                                        <option value="Si">Si</option>
                                        <option value="No">No</option>
                                    </div>
                                    
                                       
                                </select>
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
                                <a href="{{ route('paymenttypes') }}" name="danger" type="button" class="btn btn-danger btn-block">Cancelar</a>
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