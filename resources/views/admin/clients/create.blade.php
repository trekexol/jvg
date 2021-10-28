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
                <div class="card-header text-center font-weight-bold h3">Registro de Clientes</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('clients.store') }}" enctype="multipart/form-data">
                        @csrf
                        <input id="id_user" type="hidden" class="form-control @error('id_user') is-invalid @enderror" name="id_user" value="{{ Auth::user()->id }}" required autocomplete="id_user">

                        <div class="form-group row">
                            <label for="type_code" class="col-md-2 col-form-label text-md-right">Código, Cédula / Rif:</label>

                                <div class="col-md-1">
                                    <select class="form-control" name="type_code" id="type_code">
                                        <option value="J-">J-</option>
                                        <option value="G-">G-</option>
                                        <option value="V-">V-</option>
                                        <option value="E-">E-</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <input id="cedula_rif" type="text" class="form-control @error('cedula_rif') is-invalid @enderror" name="cedula_rif" value="{{ old('cedula_rif') }}" required autocomplete="cedula_rif">

                                    @error('cedula_rif')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                            <label for="vendor" class="col-md-2 col-form-label text-md-right">Vendedor:</label>

                            <div class="col-md-3">
                            <select class="form-control" id="id_vendor" name="id_vendor">
                                <option value="">Seleccione un Vendedor</option>
                                @foreach($vendors as $vendor)
                                    <option value="{{ $vendor->id }}">{{ $vendor->name }}</option>
                                @endforeach

                            </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-2">
                                <label for="name">Nombre:</label>
                            </div>
                            <div class="col-sm-4">
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" placeholder =" Nombre o Razon Social" required autocomplete="name" autofocus>
                                @error('name')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-sm-2">
                                <label for="licencia">Licencia:</label>
                            </div>
                            <div class="col-sm-4">
                                <input id="licencia" type="text" class="form-control @error('licencia') is-invalid @enderror" name="Licencia" value="{{ old('Licencia') }}" placeholder =" Licencia de Licores" required autocomplete="licencia" autofocus>
                                @error('licencia')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-2">
                                <label for="city">Ciudad:</label>
                            </div>
                            <div class="col-sm-4">
                                <input id="city" type="text" class="form-control @error('city') is-invalid @enderror" name="city" value="{{ old('city') }}" required autocomplete="city">
                                @error('city')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-sm-2">
                                <label for="country">Pais:</label>
                            </div>
                            <div class="col-sm-4">
                                <input id="country" type="text" class="form-control @error('country') is-invalid @enderror" name="country" value="Venezuela" required autocomplete="country">
                                @error('country')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-2">
                                <label for="direction">Dirección:</label>
                            </div>
                            <div class="col-sm-10">
                                <input id="direction" type="text" class="form-control @error('direction') is-invalid @enderror" name="direction" value="{{ old('direction') }}" required autocomplete="direction">

                                @error('direction')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-2">
                                <label for="direction_destiny">Dirección Destino:</label>
                            </div>
                            <div class="col-sm-10">
                                <input id="direction_destiny" type="text" class="form-control @error('direction_destiny') is-invalid @enderror" name="Direccion_Destino" value="{{ old('Direccion_Destino') }}" required autocomplete="direction_destiny">

                                @error('direction_destiny')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-2">
                                <label for="direction_delivery">Dirección Entrega:</label>
                            </div>
                            <div class="col-sm-10">
                                <input id="direction_delivery" type="text" class="form-control @error('direction_delivery') is-invalid @enderror" name="Direccion_Entrega" value="{{ old('Direccion_Entrega') }}" required autocomplete="direction_delivery">

                                @error('direction_delivery')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-2">
                                <label for="phone1">Teléfono:</label>
                            </div>
                            <div class="col-sm-4">
                                <input id="phone1" type="text" class="form-control @error('phone1') is-invalid @enderror" name="phone1" value="{{ old('phone1') }}" placeholder="0000 000-0000"  autocomplete="phone1">

                                @error('phone1')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-sm-2">
                                <label for="phone2">Teléfono 2:</label>
                            </div>
                            <div class="col-sm-4">
                                <input id="phone2" type="text" class="form-control @error('phone2') is-invalid @enderror" name="phone2" value="{{ old('phone2') }}" placeholder="0000 000-0000"  autocomplete="phone2">

                                @error('phone2')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="email" class="col-md-2 col-form-label text-md-right">Tiene Crédito</label>

                            <div class="form-check">
                                <input class="form-check-input position-static" type="checkbox" id="has_credit" name="has_credit" onclick="calc();" value="option1" aria-label="...">
                              </div>

                              <label id="days_credit_label" for="days_credit_label" class="col-md-2 col-form-label text-md-right">Dias de Crédito</label>

                              <div class="col-md-2">
                                  <input id="days_credit" type="text" class="form-control @error('days_credit') is-invalid @enderror" name="days_credit" value="{{ old('days_credit') }}"  autocomplete="days_credit">

                                  @error('days_credit')
                                      <span class="invalid-feedback" role="alert">
                                          <strong>{{ $message }}</strong>
                                      </span>
                                  @enderror
                              </div>
                        </div>

                        <div class="form-group row">
                            <label for="amount_max_credit" class="col-md-2 col-form-label text-md-right">Monto Máximo de Crédito</label>

                            <div class="col-md-4">
                                <input id="amount_max_credit" type="text" class="form-control @error('amount_max_credit') is-invalid @enderror" name="amount_max_credit" value="{{ old('amount_max_credit') }}"  autocomplete="amount_max_credit">

                                @error('amount_max_credit')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>


                        </div>

                        <div class="form-group row">
                            <label for="percentage_retencion_iva" class="col-md-2 col-form-label text-md-right">Porcentaje Retención <br>de Iva</label>

                            <div class="col-md-4">
                                <input id="percentage_retencion_iva" type="text" class="form-control @error('percentage_retencion_iva') is-invalid @enderror" name="percentage_retencion_iva" value="{{ old('percentage_retencion_iva') }}"  autocomplete="percentage_retencion_iva">

                                @error('percentage_retencion_iva')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                              <label for="percentage_retencion_islr" class="col-md-2 col-form-label text-md-right">Porcentaje Retención de ISLR</label>

                              <div class="col-md-3">
                                  <input id="percentage_retencion_islr" type="text" class="form-control @error('percentage_retencion_islr') is-invalid @enderror" name="percentage_retencion_islr" value="{{ old('percentage_retencion_islr') }}"  autocomplete="percentage_retencion_islr">

                                  @error('percentage_retencion_islr')
                                      <span class="invalid-feedback" role="alert">
                                          <strong>{{ $message }}</strong>
                                      </span>
                                  @enderror
                              </div>
                        </div>






                        <br>
                        <div class="form-group row mb-0">
                            <div class="col-md-3">
                            </div>
                            <div class="col-md-3">
                                <button type="submit" class="btn btn-primary">
                                   Registrar Cliente
                                </button>

                            </div>
                            <div class="col-md-3">
                                <a href="{{ route('clients') }}" name="danger" type="button" class="btn btn-danger">Cancelar</a>
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
        soloAlfaNumerico('code_client');
        soloAlfaNumerico('razon_social');
        sololetras('name');
        sololetras('country');
        sololetras('city');
        soloAlfaNumerico('direction');
        sololetras('seller');
    });

        $("#days_credit_label").hide();
        $("#days_credit").hide();
        document.getElementById('days_credit').value = 0;


    function calc()
    {
        if (document.getElementById('has_credit').checked)
        {
            $("#days_credit_label").show();
            $("#days_credit").show();

            document.getElementById('days_credit').value = 0;
        } else {
            $("#days_credit_label").hide();
            $("#days_credit").hide();
            document.getElementById('days_credit').value = 0;
        }
    }

        $(document).ready(function () {
            $("#cedula_rif").mask('00000000000000', { reverse: true });

        });
        $(document).ready(function () {
            $("#phone1").mask('0000 000-0000', { reverse: true });

        });
        $(document).ready(function () {
            $("#phone2").mask('0000 000-0000', { reverse: true });

        });
        $(document).ready(function () {
            $("#amount_max_credit").mask('000.000.000.000.000.000,00', { reverse: true });

        });
        $(document).ready(function () {
            $("#percentage_retencion_iva").mask('000', { reverse: true });

        });
        $(document).ready(function () {
            $("#percentage_retencion_islr").mask('000', { reverse: true });

        });
    </script>
@endsection

