@extends('admin.layouts.dashboard')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header text-center font-weight-bold h3">Registro Liquidación</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('pdfnomina.liquidacion_auto') }}" target="print_popup">
                        @csrf

                        <div class="form-group row">
                            <label for="date_begin" class="col-md-2 col-form-label text-md-right">Fecha de Liquidacion:</label>

                            <div class="col-md-4">
                                <input id="date_begin" type="date" class="form-control @error('date_begin') is-invalid @enderror" name="date_begin" value="{{ $datenow }}" required autocomplete="date_begin">

                                @error('date_begin')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <label for="id_employee" class="col-md-2 col-form-label text-md-right">Empleado:</label>
                            <div class="col-md-4">
                                <select class="form-control" required name="id_employee" id="id_employee">
                                    <option value="">Seleccione un Empleado</option>
                                    @foreach ($employees as $var)
                                        <option value="{{ $var->id }}">{{ $var->nombres }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        
                        <div class="form-group row">
                            <label for="motivo" class="col-md-2 col-form-label text-md-right">Motivo:</label>
                            <div class="col-md-4">
                                <select class="form-control" required name="motivo" id="motivo">
                                    <option value="">Seleccione un Motivo</option>
                                    <option value="Renuncia Voluntaria">Renuncia Voluntaria</option>
                                    <option value="Despido">Despido</option>
                                    <option value="Culminación del Contrato">Culminación del Contrato</option>
                                    <option value="Abandono Laboral">Abandono Laboral</option>
                                    <option value="Fallecimiento">Fallecimiento</option>
                                </select>
                            </div>
                            <label for="motivo" class="col-md-2 col-form-label text-md-right">Tipo de Utilidad:</label>
                            <div class="col-md-4">
                                <select class="form-control" required name="utilidad" id="utilidad">
                                    <option value="">Seleccione un tipo</option>
                                    <option value="Utilidad Acumulada">Utilidad Acumulada</option>
                                    <option value="Utilidad Fraccionada">Utilidad Fraccionada</option>
                                    <option value="Sin Utilidad">Sin Utilidad</option>
                                </select>
                            </div>
                        </div>  
                       
                        <div class="form-group row">
                                <div class="col-sm-2 col-form-label text-md-right">Conceptos:</div>
                                <div class="col-sm-1">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="gridCheck1" name="faov">
                                        <label class="form-check-label" for="gridCheck1">
                                            FAOV
                                        </label>
                                    </div>
                                </div>
                                <div class="col-sm-1">
                                    <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="gridCheck2" name="inces">
                                    <label class="form-check-label" for="gridCheck2">
                                        INCES
                                    </label>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="gridCheck3" name="adicionales">
                                    <label class="form-check-label" for="gridCheck3">
                                        ADICIONALES
                                    </label>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="gridCheck4" name="bono_alimenticio">
                                    <label class="form-check-label" for="gridCheck4">
                                        BONO ALIMENTICIO
                                    </label>
                                    </div>
                                </div>
                        </div>
                        <div class="form-group row">
                            <label for="lunes" class="col-md-2 col-form-label text-md-right">Lunes (Opcional):</label>

                            <div class="col-md-4">
                                <input id="lunes" type="text" class="form-control @error('lunes') is-invalid @enderror" name="lunes"  autocomplete="lunes">

                                @error('lunes')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <label for="dias_no_laborados" class="col-md-2 col-form-label text-md-right">Dias No Laborados:</label>

                            <div class="col-md-4">
                                <input id="dias_no_laborados" type="text" value="0" required class="form-control @error('dias_no_laborados') is-invalid @enderror" name="dias_no_laborados"  autocomplete="dias_no_laborados">

                                @error('dias_no_laborados')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="otras_asignaciones" class="col-md-2 col-form-label text-md-right">Otras Asignaciones:</label>

                            <div class="col-md-4">
                                <input id="otras_asignaciones" type="text" value="0,00" required class="form-control @error('otras_asignaciones') is-invalid @enderror" name="otras_asignaciones"  autocomplete="otras_asignaciones">

                                @error('otras_asignaciones')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <label for="otras_deducciones" class="col-md-2 col-form-label text-md-right">Otras Deducciones:</label>

                            <div class="col-md-4">
                                <input id="otras_deducciones" type="text" value="0,00" required class="form-control @error('otras_deducciones') is-invalid @enderror" name="otras_deducciones"  autocomplete="otras_deducciones">

                                @error('otras_deducciones')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="meses_utilidades" class="col-md-2 col-form-label text-md-right">Meses de Utilidades:</label>

                            <div class="col-md-4">
                                <input id="meses_utilidades" type="text" value="0" class="form-control @error('meses_utilidades') is-invalid @enderror" name="meses_utilidades"  autocomplete="meses_utilidades">

                                @error('meses_utilidades')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
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
            $("#lunes").mask('000', { reverse: true });
            
        });
        $(document).ready(function () {
            $("#dias_no_laborados").mask('000', { reverse: true });
            
        });
        $(document).ready(function () {
            $("#otras_asignaciones").mask('000.000.000.000.000,00', { reverse: true });
            
        });
        $(document).ready(function () {
            $("#otras_deducciones").mask('000.000.000.000.000,00', { reverse: true });
            
        });
        $(document).ready(function () {
            $("#meses_utilidades").mask('000', { reverse: true });
            
        });
        function pdf() {
                window.open('about:blank','print_popup','width=1000,height=800')
               
            }

        
    </script>
@endsection