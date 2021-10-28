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
                <div class="card-header text-center font-weight-bold h3">Registro de Empleados</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('employees.store') }}" enctype="multipart/form-data">
                        @csrf
                       
                        <div class="form-group row">
                            <label for="nombres" class="col-md-2 col-form-label text-md-right">Nombres</label>

                            <div class="col-md-4">
                                <input id="nombres" type="text" class="form-control @error('nombres') is-invalid @enderror" name="nombres" value="{{ old('nombres') }}" required autocomplete="nombres" autofocus>

                                @error('nombres')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <label for="apellidos" class="col-md-2 col-form-label text-md-right">Apellidos</label>

                            <div class="col-md-4">
                                <input id="apellidos" type="text" class="form-control @error('apellidos') is-invalid @enderror" name="apellidos" value="{{ old('apellidos') }}" required autocomplete="apellidos">

                                @error('apellidos')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                       
                        <div class="form-group row">
                            <label for="id_empleado" class="col-md-2 col-form-label text-md-right">Cédula</label>

                            <div class="col-md-4">
                                <input id="id_empleado" type="text" class="form-control @error('id_empleado') is-invalid @enderror" name="id_empleado" value="{{ old('id_empleado') }}" required autocomplete="id_empleado">

                                @error('id_empleado')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <label for="telefono1" class="col-md-2 col-form-label text-md-right">Teléfono</label>

                            <div class="col-md-4">
                                <input id="telefono1" type="text" class="form-control @error('telefono1') is-invalid @enderror" name="telefono1" value="{{ old('telefono1') }}" placeholder="Ej: 0414 xxx-xxxx" required autocomplete="telefono1">

                                @error('telefono1')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="code_employee" class="col-md-2 col-form-label text-md-right">Código de Empleado (Opcional)</label>

                            <div class="col-md-4">
                                <input id="code_employee" type="text" class="form-control @error('code_employee') is-invalid @enderror" name="code_employee" value="{{ old('code_employee') }}" autocomplete="code_employee">

                                @error('code_employee')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <label for="amount_utilities" class="col-md-2 col-form-label text-md-right">Monto de Utilidades</label>
                            <div class="col-md-4">
                                <select class="form-control" name="amount_utilities" id="amount_utilities">
                                    <option value="Ma">Máximo</option>
                                    <option value="Mi">Minimo</option>
                                </select>
                                </div>
                        </div>


                        <div class="form-group row">
                            <label for="email" class="col-md-2 col-form-label text-md-right">Correo Electrónico</label>

                            <div class="col-md-4">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            
                        </div>

                        <div class="form-group row">
                            <label for="fecha_ingreso" class="col-md-2 col-form-label text-md-right">Fecha de Ingreso</label>

                            <div class="col-md-4">
                                <input id="fecha_ingreso" type="date" class="form-control @error('fecha_ingreso') is-invalid @enderror" name="fecha_ingreso" value="{{ old('fecha_ingreso') }}" required autocomplete="fecha_ingreso">

                                @error('fecha_ingreso')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <label for="fecha_nacimiento" class="col-md-2 col-form-label text-md-right">Fecha de Nacimiento</label>

                            <div class="col-md-4">
                                <input id="fecha_nacimiento" type="date" class="form-control @error('fecha_nacimiento') is-invalid @enderror" name="fecha_nacimiento" value="{{ old('fecha_nacimiento') }}" required autocomplete="fecha_nacimiento">

                                @error('fecha_nacimiento')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="position" class="col-md-2 col-form-label text-md-right">Cargo</label>

                            <div class="col-md-4">
                            <select class="form-control" id="position_id" name="position_id">
                                @foreach($position as $var)
                                    <option value="{{ $var->id }}">{{ $var->name }}</option>
                                @endforeach
                              
                            </select>
                            </div>
                            <label for="profession" class="col-md-2 col-form-label text-md-right">Profesión</label>

                            <div class="col-md-4">
                            <select class="form-control" id="profession_id" name="profession_id">
                                @foreach($profession as $var)
                                    <option value="{{ $var->id }}">{{ $var->name }}</option>
                                @endforeach
                              
                            </select>
                            </div>
                        </div>

               
                        <div class="form-group row">
                                    
                                        <label for="estado" class="col-md-2 col-form-label text-md-right">Estado</label>
                                    
                                    <div class="col-md-4">
                                        <select id="estado"  name="estado" class="form-control" required>
                                            <option value="">Seleccione un Estado</option>
                                            @foreach($estados as $index => $value)
                                                <option value="{{ $index }}" {{ old('Estado') == $index ? 'selected' : '' }}>
                                                    {{ $value }}
                                                </option>
                                            @endforeach
                                            </select>
    
                                            @if ($errors->has('estado_id'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('estado_id') }}</strong>
                                                </span>
                                            @endif
                                    </div>
                                   
                                        <label for="municipio" class="col-md-2 col-form-label text-md-right">Municipio</label>
                                    
                                    <div class="col-md-4">
                                        <select  id="municipio"  name="Municipio" class="form-control" required>
                                            <option value="">Selecciona un Municipio</option>
                                        </select>

                                        @if ($errors->has('municpio_id'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('municpio_id') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>  

                                <div class="form-group row">
                                        <label for="parroquia" class="col-md-2 col-form-label text-md-right">Parroquia</label>
                                   
                                    <div class="col-md-4">
                                        <select class="form-control" id="parroquia"  name="Parroquia" required class="form-control" value="{{ old('Parroquia')}}" >
                                            <option value="">Selecciona un Parroquia</option>
                                        </select>
                                    </div>
                                
                                   
                                        <label for="direccion" class="col-md-2 col-form-label text-md-right">Dirección</label>
                                    
                                    <div class="col-md-4">
                                        
                                        <input type="text" class="form-control" id="direccion" name="direccion" required value="{{ old('direccion')}}" placeholder="Ej: La Paz">
                                    </div>
                        </div>

                        
                        <div class="form-group row">
                            
                            <label for="salarytype" class="col-md-2 col-form-label text-md-right">Tipo Sueldo</label>

                            <div class="col-md-4">
                            <select class="form-control" id="salarytype_id" name="salarytype_id">
                                @foreach($salarytype as $var)
                                    <option value="{{ $var->id }}">{{ $var->name }}</option>
                                @endforeach
                              
                            </select>
                            </div>
                            <label for="monto_pago" class="col-md-2 col-form-label text-md-right">Monto Pago</label>

                            <div class="col-md-4">
                                <input id="monto_pago" type="text" class="form-control @error('monto_pago') is-invalid @enderror" name="monto_pago" value="{{ old('monto_pago') }}" placeholder="Ej: 19.55" required autocomplete="monto_pago">

                                @error('monto_pago')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        
                       
                        <div class="form-group row">
                            <label for="acumulado_prestaciones" class="col-md-2 col-form-label text-md-right">Acumulado Prestaciones</label>

                            <div class="col-md-4">
                                <input id="acumulado_prestaciones" type="text" class="form-control @error('acumulado_prestaciones') is-invalid @enderror" name="acumulado_prestaciones" value="{{ old('acumulado_prestaciones') }}" required autocomplete="acumulado_prestaciones">

                                @error('acumulado_prestaciones')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <label for="acumulado_utilidades" class="col-md-2 col-form-label text-md-right">Acumulado Utilidades</label>

                            <div class="col-md-4">
                                <input id="acumulado_utilidades" type="text" class="form-control @error('acumulado_utilidades') is-invalid @enderror" name="acumulado_utilidades" value="{{ old('acumulado_utilidades') }}" required autocomplete="acumulado_utilidades">

                                @error('acumulado_utilidades')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="form-group row">
                            <label for="centro_costo" class="col-md-2 col-form-label text-md-right">Centro Costo</label>

                            <div class="col-md-4">
                                <select class="form-control" id="centro_costo" name="centro_costo">
                                    @foreach($centro_costo as $var)
                                        <option value="{{ $var->id }}">{{ $var->description }}</option>
                                    @endforeach
                                  
                                </select>
                                </div>
                          
                        </div>
                        
                        <br>
                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                   Registrar Empleado
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
        $(document).ready(function () {
            $("#id_empleado").mask('000.000.000.000.000', { reverse: true });
            
        }); 
        $(document).ready(function () {
            $("#telefono1").mask('0000 000-0000', { reverse: true });
            
        }); 
        $(document).ready(function () {
            $("#acumulado_prestaciones").mask('000.000.000.000.000,00', { reverse: true });
            
        });
        $(document).ready(function () {
            $("#acumulado_utilidades").mask('000.000.000.000.000,00', { reverse: true });
            
        });
        $(document).ready(function () {
            $("#monto_pago").mask('000.000.000.000.000,00', { reverse: true });
            
        });
        
        </script>
    <script>    
        $(function(){
            soloLetras('nombres');
            soloLetras('apellidos');
          
            soloAlfaNumerico('code_employee');
            soloAlfaNumerico('direccion');
        });
    </script>
@endsection

@section('javascript')
    <script>
            
            $("#estado").on('change',function(){
                var estado_id = $(this).val();
                $("#municipio").val("");
                $("#parroquia").val("");
                // alert(estado_id);
                getMunicipios(estado_id);
            });

        function getMunicipios(estado_id){
            // alert(`../municipio/list/${estado_id}`);
            $.ajax({
                url:`../municipio/list/${estado_id}`,
                beforSend:()=>{
                    alert('consultando datos');
                },
                success:(response)=>{
                    let municipio = $("#municipio");
                    let htmlOptions = `<option value='' >Seleccione..</option>`;
                    // console.clear();
                    if(response.length > 0){
                        response.forEach((item, index, object)=>{
                            let {id,descripcion} = item;
                            htmlOptions += `<option value='${id}' {{ old('Municipio') == '${id}' ? 'selected' : '' }}>${descripcion}</option>`

                        });
                    }
                    //console.clear();
                    // console.log(htmlOptions);
                    municipio.html('');
                    municipio.html(htmlOptions);
                
                    
                
                },
                error:(xhr)=>{
                    alert('Presentamos inconvenientes al consultar los datos');
                }
            })
        }

        $("#municipio").on('change',function(){
                var municipio_id = $(this).val();
                var estado_id    = document.getElementById("estado").value;
                getParroquias(municipio_id,estado_id);
            });

        function getParroquias(municipio_id,estado_id){
            // alert(`../parroquia/list/${municipio_id}/${estado_id}`);
            $.ajax({
                url:`../parroquia/list/${municipio_id}/${estado_id}`,
                beforSend:()=>{
                    alert('consultando datos');
                },
                success:(response)=>{
                    let parroquia = $("#parroquia");
                    let htmlOptions = `<option value='' >Seleccione..</option>`;
                    // console.clear();
                    if(response.length > 0){
                        response.forEach((item, index, object)=>{
                            let {id,descripcion} = item;
                            htmlOptions += `<option value='${id}' {{ old('Parroquia') == '${descripcion}' ? 'selected' : '' }} >${descripcion}</option>`

                        });
                    }
                    // console.clear();
                    // console.log(htmlOptions);
                    parroquia.html('');
                    parroquia.html(htmlOptions);
                },
                error:(xhr)=>{
                    alert('Presentamos inconvenientes al consultar los datos');
                }
            })
        }
	
    </script>
@endsection
