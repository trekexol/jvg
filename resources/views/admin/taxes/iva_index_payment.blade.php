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
                    <div class="card-header text-center font-weight-bold h3">Debito Fiscal IVA por Pagar</div>

                    <div class="card-body">
                       
                            <div class="form-group row">
                                
                                <label id="date_begin" class="col-sm-3 col-form-label text-md-right" for="type" >Fecha del Retiro Mes:</label>
                               
                                <div class="col-sm-3">
                                    <select class="form-control" id="filtro_mount" name="Filtro_Meses" >
                                        <option value="0">Seleccione..</option>
                                        <option value="01">Enero-01</option>
                                        <option value="02">Febrero-02</option>
                                        <option value="03">Marzo-03</option>
                                        <option value="04">Abril-04</option>
                                        <option value="05">Mayo-05</option>
                                        <option value="06">Junio-06</option>
                                        <option value="07">Julio-07</option>
                                        <option value="08">Agosto-08</option>
                                        <option value="09">Septiembre-09</option>
                                        <option value="10">Octubre-10</option>
                                        <option value="11">Noviembre-11</option>
                                        <option value="12">Dicienmbre-12</option>
                                    </select>
                                   
                                </div>
                               
                                <label id="date_begin" class="col-sm-3 col-form-label text-md-right" for="type" >Fecha del Retiro Año:</label>
                                
                                <div class="col-sm-3">
                                    <select class="form-control" id="filtro_year" name="Filtro_Year" >
                                        <option value="{{ $year_anterior }}">{{ $year_anterior }}</option>
                                        <option selected value="{{ $datenow }}">{{ $datenow }}</option>
                                    </select>
                                </div>
                            </div>
                            <br>
                            <div class="form-group row mb-0">
                                <div class="col-sm-3 offset-sm-3">
                                    <a onclick="consultar()" type="submit" class="btn btn-primary">
                                        Consultar Impuesto
                                    </a>
                                </div>
                                <div class="col-sm-3">
                                    <a href="{{ route('bankmovements') }}" id="btnfacturar" name="btnfacturar" class="btn btn-danger" title="facturar">Volver</a>
                                </div>
                            </div>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('javascript_iva_payment')

    <script>

        var fecha = new Date();
        var ano = fecha.getFullYear();
        document.getElementsByName("Fecha_Year")[0].value = ano ;
    </script>
    <script>
        function consultar(){
            let filtro_mount = document.getElementById("filtro_mount").value; 
            let filtro_year = document.getElementById("filtro_year").value; 
            
            window.location = "{{route('taxes.iva_payment', ['',''])}}"+"/"+filtro_mount+"/"+filtro_year;
                           
        }
        
    </script>

@endsection
