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
                    <div class="card-header">Editar Recibo de Vacaciones</div>
    
                    <div class="card-body">
                    <form  method="POST"   action="{{ route('receiptvacations.update',$var->id) }}" enctype="multipart/form-data" >
                        @method('PATCH')
                        @csrf()
                        
                        <input type="hidden" class="form-control" name="employee_id" value="{{ $var->employee_id }}" readonly>
                       
                       
                        <div class="form-group row">
                            <label for="date_begin" class="col-md-2 col-form-label text-md-right">Fecha de Inicio</label>

                            <div class="col-md-4">
                                <input id="date_begin" type="date" class="form-control @error('date_begin') is-invalid @enderror" name="date_begin" value="{{ $var->date_begin }}" required autocomplete="date_begin" min="2021">

                                @error('date_begin')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <label for="date_end" class="col-md-2 col-form-label text-md-right">Fecha de Final</label>

                            <div class="col-md-4">
                                <input id="date_end" type="date" class="form-control @error('date_end') is-invalid @enderror" name="date_end" value="{{ $var->date_end }}" required autocomplete="date_end" min="2021">

                                @error('date_end')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="days_vacations" class="col-md-2 col-form-label text-md-right">Dias de Vacaciones</label>

                            <div class="col-md-4">
                                <input id="days_vacations" type="number" class="form-control @error('days_vacations') is-invalid @enderror" name="days_vacations" value="{{ $var->days_vacations }}" required autocomplete="days_vacations" min="1" max="12">
                            @error('days_vacations')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                            </div>
                            <label for="bono_vacations" class="col-md-2 col-form-label text-md-right">Bono de Vacaciones</label>

                            <div class="col-md-4">
                                <input id="bono_vacations" type="number" class="form-control @error('bono_vacations') is-invalid @enderror" name="bono_vacations" value="{{ $var->bono_vacations }}" required autocomplete="bono_vacations" min="1" max="12">
                            @error('bono_vacations')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="days_feriados" class="col-md-2 col-form-label text-md-right">Cantidad de Dias Feriados</label>

                            <div class="col-md-4">
                                <input id="days_feriados" type="number" class="form-control @error('days_feriados') is-invalid @enderror" name="days_feriados" value="{{ $var->days_feriados }}" required autocomplete="days_feriados" step="any" />

                                @error('days_feriados')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <label for="lph" class="col-md-2 col-form-label text-md-right">Lph</label>

                            <div class="col-md-4">
                                <input id="lph" type="number" class="form-control @error('lph') is-invalid @enderror" name="lph" value="{{ $var->lph }}" required autocomplete="lph" step="any" />

                                @error('lph')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="sso" class="col-md-2 col-form-label text-md-right">Sso</label>

                            <div class="col-md-4">
                                <input id="sso" type="number" class="form-control @error('sso') is-invalid @enderror" name="sso" value="{{ $var->sso }}" required autocomplete="sso" step="any" />

                                @error('sso')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                           
                            <label for="seguro_paro_forzoso" class="col-md-2 col-form-label text-md-right">Seguro Paro Forzoso</label>

                            <div class="col-md-4">
                                <input id="seguro_paro_forzoso" type="number" class="form-control @error('seguro_paro_forzoso') is-invalid @enderror" name="seguro_paro_forzoso" value="{{ $var->seguro_paro_forzoso }}" required autocomplete="seguro_paro_forzoso" step="any" />

                                @error('seguro_paro_forzoso')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        

                        <div class="form-group row">
                            <label for="ultimo_sueldo" class="col-md-2 col-form-label text-md-right">Último Sueldo</label>

                            <div class="col-md-4">
                                <input id="ultimo_sueldo" type="number" class="form-control @error('ultimo_sueldo') is-invalid @enderror" name="ultimo_sueldo" value="{{ $var->ultimo_sueldo }}" required autocomplete="ultimo_sueldo" step="any" />

                                @error('ultimo_sueldo')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <label for="total_pagar" class="col-md-2 col-form-label text-md-right">Total a Pagar</label>

                            <div class="col-md-4">
                                <input id="total_pagar" type="number" class="form-control @error('total_pagar') is-invalid @enderror" name="total_pagar" value="{{ $var->total_pagar }}" required autocomplete="total_pagar" step="any" />

                                @error('total_pagar')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                       
                        <div class="form-group row">
                            <label for="status" class="col-md-2 col-form-label text-md-right">Status</label>
                            <div class="col-md-4">
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
                                <a href="{{ route('receiptvacations') }}" name="danger" type="button" class="btn btn-danger btn-block">Cancelar</a>
                            </div>
                        </div>

                    </form>
               
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
