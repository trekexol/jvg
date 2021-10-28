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
                <div class="card-header text-center font-weight-bold h3">Registro de Sucursales</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('branches.store') }}" enctype="multipart/form-data">
                        @csrf

                        <div class="form-group row">
                            <label for="company_id" class="col-md-2 col-form-label text-md-right">Compañías</label>

                            <div class="col-md-4">
                                <select class="form-control" id="company_id" name="company_id">
                                    @foreach($companies as $var)
                                        <option value="{{ $var->id }}" {{ old('Companies') }}>{{ $var->razon_social }}</option>
                                    @endforeach
                                
                                </select>
                            </div>
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
                            
                        </div>

                        <div class="form-group row">
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
                            <label for="parroquia" class="col-md-2 col-form-label text-md-right">Parroquia</label>
                       
                            <div class="col-md-4">
                                <select class="form-control" id="parroquia"  name="Parroquia" required class="form-control" value="{{ old('Parroquia')}}" >
                                    <option value="">Selecciona un Parroquia</option>
                                </select>
                            </div>
                        </div>  

                   
                            
                    
                        <div class="form-group row">
                                <label for="description" class="col-md-2 col-form-label text-md-right">Descripción</label>

                                <div class="col-md-4">
                                    <input id="description" type="text" class="form-control @error('description') is-invalid @enderror" name="description" value="{{ old('description') }}" maxlength="150" required autocomplete="description">

                                    @error('description')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <label for="direction" class="col-md-2 col-form-label text-md-right">Dirección</label>

                                <div class="col-md-4">
                                    <input id="direction" type="text" class="form-control @error('direction') is-invalid @enderror" name="direction" value="{{ old('direction') }}" maxlength="150" required autocomplete="direction">

                                    @error('direction')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            
                        </div>
                        <div class="form-group row">
                            <label for="phone" class="col-md-2 col-form-label text-md-right">Teléfono</label>

                            <div class="col-md-4">
                                <input id="phone" type="text" class="form-control @error('phone') is-invalid @enderror" name="phone" value="{{ old('phone') }}" maxlength="30" required autocomplete="phone">

                                @error('phone')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <label for="phone2" class="col-md-2 col-form-label text-md-right">Teléfono 2</label>

                            <div class="col-md-4">
                                <input id="phone2" type="text" class="form-control @error('phone2') is-invalid @enderror" name="phone2" value="{{ old('phone2') }}" maxlength="30" required autocomplete="phone2">

                                @error('phone2')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                       

                        <div class="form-group row">
                            <label for="person_contact" class="col-md-2 col-form-label text-md-right">Persona Contácto</label>

                            <div class="col-md-4">
                                <input id="person_contact" type="text" class="form-control @error('person_contact') is-invalid @enderror" name="person_contact" value="{{ old('person_contact') }}" maxlength="160" required autocomplete="person_contact">

                                @error('person_contact')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                              
                            <label for="phone_contact" class="col-md-2 col-form-label text-md-right">Teléfono Contácto</label>

                            <div class="col-md-4">
                                <input id="phone_contact" type="text" class="form-control @error('phone_contact') is-invalid @enderror" name="phone_contact" value="{{ old('phone_contact') }}" maxlength="30" required autocomplete="phone_contact">

                                @error('phone_contact')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                       
                        <div class="form-group row">
                            <label for="observation" class="col-md-2 col-form-label text-md-right">Observación</label>

                            <div class="col-md-4">
                                <input id="observation" type="text" class="form-control @error('observation') is-invalid @enderror" name="observation" value="{{ old('observation') }}" maxlength="150" required autocomplete="observation">

                                @error('observation')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <label for="status" class="col-md-2 col-form-label text-md-right">Status</label>

                            <div class="col-md-4">
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
                                   Registrar Sucursal
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
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
        soloAlfaNumerico('direction');
        soloNumeros('phone');
        soloNumeros('phone2');
        sololetras('person_contact');
        soloNumeros('phone_contact');
        soloAlfaNumerico('observation');
       
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
