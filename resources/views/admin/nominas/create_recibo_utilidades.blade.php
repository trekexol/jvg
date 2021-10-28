@extends('admin.layouts.dashboard')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header text-center font-weight-bold h3">Registro Recibo de Utilidades</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('pdfnomina.utilidades') }}" target="print_popup">
                        @csrf
                        <div class="form-group row ">
                            <label for="date_end" class="col-md-4 col-form-label text-md-right">Fecha Hasta:</label>

                            <div class="col-md-4">
                                <input id="date_end" type="date" class="form-control @error('date_end') is-invalid @enderror" value="{{ $dateend }}" name="date_end" >

                                @error('date_end')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="days" class="col-md-4 col-form-label text-md-right">Dias de Utilidades:</label>

                            <div class="col-md-4">
                                <input id="days" type="text" class="form-control @error('days') is-invalid @enderror" name="days" required autocomplete="days">

                                @error('days')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                           
                        </div>  
                        <div class="form-group row">
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
                            <div class="col-md-2">
                            </div>
                            <div class="col-md-3">
                                <button type="submit" onclick="pdf();" class="btn btn-primary">
                                   Ver Recibo
                                </button>
                            </div>
                            <div class="col-md-3">
                                <button type="submit" name="guardar" value="guardar" class="btn btn-info">
                                   Guardar y descargar recibo
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
      
        function pdf() {
                window.open('about:blank','print_popup','width=1000,height=800')
               
            }

        
    </script>
@endsection