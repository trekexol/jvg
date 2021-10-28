@extends('admin.layouts.dashboard')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header text-center font-weight-bold h3">Registro de Prestaciones</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('pdfnomina.prestaciones') }}" target="print_popup" onsubmit="pdf();">
                        @csrf

                        <div class="form-group row ">
                            <label for="date_begin" class="col-md-4 col-form-label text-md-right">Fecha de Solicitud:</label>

                            <div class="col-md-4">
                                <input id="date_begin" type="date" class="form-control @error('date_begin') is-invalid @enderror" name="date_begin" value="{{ $datenow }}" required autocomplete="date_begin">

                                @error('date_begin')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                       
                       
                        <div class="form-group row ">
                            <label for="id_employee" class="col-md-4 col-form-label text-md-right">Empleados:</label>
                            <div class="col-md-4">
                                <select class="form-control" required name="id_employee" id="id_employee">
                                    <option value="">Seleccione un Empleado</option>
                                    @foreach ($employees as $var)
                                        <option value="{{ $var->id }}">{{ $var->nombres }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        
                    <br>
                        <div class="form-group row mb-0">
                            <div class="col-md-4">
                            </div>
                            <div class="col-md-3">
                                <button type="submit" class="btn btn-primary">
                                   Ver Pretaciones
                                </button>
                            </div>
                           
                            <div class="col-md-2">
                                <a href="{{ route('nominas') }}" id="btnvolver" name="btnvolver" class="btn btn-danger" title="volver">Volver</a>  
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
            $("#days").mask('000', { reverse: true });
            
        });
        $(document).ready(function () {
            $("#bono").mask('000', { reverse: true });
            
        });
        $(document).ready(function () {
            $("#monday").mask('000', { reverse: true });
            
        });
        $(document).ready(function () {
            $("#lph").mask('000.000.000.000.000,00', { reverse: true });
            
        });
        function pdf() {
                window.open('about:blank','print_popup','width=1000,height=800')
               
            }

        
    </script>
@endsection