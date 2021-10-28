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
                    <div class="card-header text-center font-weight-bold h3">Registro de Productos</div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('products.store') }}" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group row">
                                <div class="col-sm-2">
                                    <label for="type">Tipo:</label>
                                </div>
                                <div class="col-sm-4">
                                    <select class="form-control" name="Tipo" id="type">
                                        <option value="MERCANCIA">Mercancía</option>
                                        <option value="SERVICIO">Servicio</option>
                                    </select>
                                    @error('type')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                                <div class="col-sm-2">
                                    <label for="description">Descripción:</label>
                                </div>
                                <div class="col-sm-4">
                                    <input id="description" type="text" class="form-control @error('description') is-invalid @enderror" name="Descripcion" value="{{ old('Descripcion') }}" required autocomplete="description">
                                    @error('description')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-2">
                                    <label for="segment">Segmento:</label>
                                </div>
                                <div class="col-sm-4">
                                    <select id="segment"  name="Segmento" class="form-control" required>
                                        <option value="">Seleccione un Segmento</option>
                                        @foreach($segments as $index => $value)
                                            <option value="{{ $index }}" {{ old('Segment') == $index ? 'selected' : '' }}>
                                                {{ $value }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('segment'))
                                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('segment') }}</strong>
                                    </span>
                                    @endif
                                </div>
                                <div class="col-sm-2">
                                    <label for="subsegment">Sub-Segmento:</label>
                                </div>
                                <div class="col-sm-4">
                                    <select  id="subsegment"  name="Sub_Segmento" class="form-control" required>
                                        <option value="">Selecciona un Sub Segmento</option>
                                    </select>
                                    @if ($errors->has('subsegment'))
                                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('subsegment') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-2">
                                    <label for="twosubsegment">Sub-Segmento 2 (Opcional):</label>
                                </div>
                                <div class="col-sm-4">
                                    <select  id="twosubsegment"  name="twoSubsegment" class="form-control" >
                                        <option value=""></option>
                                    </select>
                                    @if ($errors->has('twosubsegment'))
                                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('twosubsegment') }}</strong>
                                    </span>
                                    @endif
                                </div>
                                <div class="col-sm-2">
                                    <label for="threesubsegment">Sub-Segmento 3 (Opcional):</label>
                                </div>
                                <div class="col-sm-4">
                                    <select  id="threesubsegment"  name="threeSubsegment" class="form-control" >
                                        <option value=""></option>
                                    </select>
                                    @if ($errors->has('threesubsegment'))
                                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('threesubsegment') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-2">
                                    <label for="unit_of_measure_id">Unidad de Medida:</label>
                                </div>
                                <div class="col-sm-4">
                                    <select class="form-control" id="unit_of_measure_id" name="Unidad_Medida">
                                        @foreach($unitofmeasures as $var)
                                            <option value="{{ $var->id }}">{{ $var->description }}</option>
                                        @endforeach
                                    </select>
                                    @error('unit_of_measure_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                                <div class="col-sm-2">
                                    <label for="code_comercial">Código Comercial:</label>
                                </div>
                                <div class="col-sm-4">
                                    <input id="code_comercial" type="text" class="form-control @error('code_comercial') is-invalid @enderror" name="Codigo_Comercial" value="{{ old('Codigo_Comercial') }}" autocomplete="code_comercial">
                                    @error('code_comercial')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-2">
                                    <label for="price">Precio:</label>
                                </div>
                                <div class="col-sm-4">
                                    <input id="price" type="text" class="form-control @error('price') is-invalid @enderror" name="Precio" value="{{ old('Precio') }}"  autocomplete="price">
                                    @error('price')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                                <div class="col-sm-2">
                                    <label for="price_buy">Precio Compra:</label>
                                </div>
                                <div class="col-sm-4">
                                    <input id="price_buy" type="text" class="form-control @error('price_buy') is-invalid @enderror" name="Precio_Compra" value="{{ old('Precio_Compra') }}"  autocomplete="price_buy">

                                    @error('price_buy')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-2">
                                    <label for="cost_average">Costo Promedio:</label>
                                </div>
                                <div class="col-sm-4">
                                    <input id="cost_average" type="text" class="form-control @error('cost_average') is-invalid @enderror" name="Costo_Promedio" value="{{ old('Costo_Promedio') }}"  autocomplete="cost_average">
                                    @error('cost_average')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                                <div class="col-sm-2">
                                    <label for="photo_product">Foto del Producto:</label>
                                </div>
                                <div class="col-sm-4">
                                    <input type="image" src="" alt="" width="48" height="48">
                                    @error('photo_product')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-2">
                                    <label for="money">Moneda:</label>
                                </div>
                                <div class="col-sm-4">
                                    <select class="form-control" name="Moneda" id="money">
                                        <option value="D">Dolar</option>
                                        <option value="Bs">Bolívares</option>
                                    </select>
                                    @error('money')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                                <div class="col-sm-2">
                                    <label for="exento">Exento:</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input position-static" type="checkbox" id="exento" name="exento" value="1" aria-label="...">
                                </div>
                                <div class="col-sm-2">
                                    <label for="islr">Islr:</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input position-static" type="checkbox" id="islr" name="islr" value="1" aria-label="...">
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-2">
                                    <label for="box">Cajas:</label>
                                </div>
                                <div class="col-sm-4">
                                    <input id="box" type="text" class="form-control @error('box') is-invalid @enderror" name="Cajas" value="{{ old('Cajas') }}" required autocomplete="box">
                                    @error('box')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                                <div class="col-sm-2">
                                    <label for="degree">Grado de Alcohol:</label>
                                </div>
                                <div class="col-sm-4">
                                    <input id="degree" type="text" class="form-control @error('degree') is-invalid @enderror" name="Grado" value="{{ old('Grado') }}" required autocomplete="degree">
                                    @error('degree')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-2">
                                    <label for="bottle">Botellas por Caja:</label>
                                </div>
                                <div class="col-sm-4">
                                    <input id="bottle" type="text" class="form-control @error('bottle') is-invalid @enderror" name="Botellas" value="{{ old('Botellas') }}" required autocomplete="bottle">
                                    @error('bottle')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                                <div class="col-sm-2">
                                    <label for="liter">Litros por Botellas:</label>
                                </div>
                                <div class="col-sm-2">
                                    <input id="liter" type="text" class="form-control @error('liter') is-invalid @enderror" name="Litros" value="{{ old('Litros') }}"  required autocomplete="liter">
                                    @error('liter')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                                <div class="col-sm-2">
                                    <input type="button" onclick="litros();" value="Calcular">

                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-2">
                                    <label for="capacity">Capacidad de Litros:</label>
                                </div>
                                <div class="col-sm-4">
                                    <input id="capacity" type="text" class="form-control @error('capacity') is-invalid @enderror" name="Capacidad" value="0" onclick="litros();" required autocomplete="capacity" readonly>
                                    @error('capacity')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                                <div class="col-sm-2">
                                    <label for="special_impuesto">Impuesto Especial:</label>
                                </div>
                                <div class="col-sm-4">
                                    <input id="special_impuesto" type="text" class="form-control @error('special_impuesto') is-invalid @enderror" name="Impuesto_Especial" value="{{ old('Impuesto_Especial') }}" required autocomplete="special_impuesto">

                                    @error('special_impuesto')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>


                            </div>
                            <p id="valueInput"></p>
                            <br>
                            <div class="form-group row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        Registrar Producto
                                    </button>
                                </div>
                                <div class="col-md-2">
                                    <a href="{{ route('products') }}" id="btnfacturar" name="btnfacturar" class="btn btn-danger" title="facturar">Volver</a>
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
        function litros(){
            var n1 = document.getElementById('bottle').value;
            var n2 = document.getElementById('liter').value;
            var n3 = document.getElementById('box').value;

            if ( n1 == '' || n1 == null) {
                alert("Verifique el Precio Costo del Producto");
            } else {
                // var n2 = document.getElementById('xponcetaje').value; // PORCENTAJE
                var n2_format  = n2.replace(",", "." );
                var resultado       = (parseFloat(n1) * parseFloat(n3)  * parseFloat(n2_format));
                document.getElementsByName("Capacidad")[0].value = resultado;
            }
        }

        $(document).ready(function () {
            $("#price").mask('000.000.000.000.000,00', { reverse: true });

        });
        $(document).ready(function () {
            $("#price_buy").mask('000.000.000.000.000,00', { reverse: true });

        });
        $(document).ready(function () {
            $("#cost_average").mask('000.000.000.000.000,00', { reverse: true });

        });

        $(document).ready(function () {
            $("#degree").mask('000.000.000.000.000,00', { reverse: true });

        });

        $(document).ready(function () {
            $("#liter").mask('000.000.000.000.000,00', { reverse: true });

        });

        $(document).ready(function () {
            $("#special_impuesto").mask('000.000.000.000.000,00', { reverse: true });

        });

        $(function(){
            soloAlfaNumerico('code_comercial');
            soloAlfaNumerico('description');
        });
    </script>
@endsection

@section('products')
    <script>
        $("#segment").on('change',function(){
            var segment_id = $(this).val();
            $("#subsegment").val("");

            // alert(segment_id);
            getSubsegment(segment_id);
        });

        function getSubsegment(segment_id){
            // alert(`../subsegment/list/${segment_id}`);
            $.ajax({
                url:`../subsegment/list/${segment_id}`,
                beforSend:()=>{
                    alert('consultando datos');
                },
                success:(response)=>{
                    let subsegment = $("#subsegment");
                    let htmlOptions = `<option value='' >Seleccione..</option>`;
                    // console.clear();
                    if(response.length > 0){
                        response.forEach((item, index, object)=>{
                            let {id,description} = item;
                            htmlOptions += `<option value='${id}' {{ old('Sub_Segmento') == '${id}' ? 'selected' : '' }}>${description}</option>`

                        });
                    }
                    //console.clear();
                    // console.log(htmlOptions);
                    subsegment.html('');
                    subsegment.html(htmlOptions);



                },
                error:(xhr)=>{

                }
            })
        }

        $("#subsegment").on('change',function(){
            var subsegment_id = $(this).val();
            var segment_id    = document.getElementById("segment").value;

            get2Subsegment(subsegment_id);
        });


        function get2Subsegment(subsegment_id){

            $.ajax({
                url:"{{ route('twosubsegments.list','') }}" + '/' + subsegment_id,
                beforSend:()=>{
                    alert('consultando datos');
                },
                success:(response)=>{
                    let subsegment = $("#twosubsegment");
                    let htmlOptions = `<option value='' >Seleccione..</option>`;
                    // console.clear();
                    if(response.length > 0){
                        response.forEach((item, index, object)=>{
                            let {id,description} = item;
                            htmlOptions += `<option value='${id}' {{ old('Subsegment') == '${id}' ? 'selected' : '' }}>${description}</option>`

                        });
                    }
                    //console.clear();
                    // console.log(htmlOptions);
                    subsegment.html('');
                    subsegment.html(htmlOptions);



                },
                error:(xhr)=>{

                }
            })
        }



        $("#twosubsegment").on('change',function(){
            var subsegment_id = $(this).val();
            var segment_id    = document.getElementById("segment").value;

            get3Subsegment(subsegment_id);
        });


        function get3Subsegment(subsegment_id){

            $.ajax({
                url:"{{ route('threesubsegments.list','') }}" + '/' + subsegment_id,
                beforSend:()=>{
                    alert('consultando datos');
                },
                success:(response)=>{
                    let subsegment = $("#threesubsegment");
                    let htmlOptions = `<option value='' >Seleccione..</option>`;
                    // console.clear();
                    if(response.length > 0){
                        response.forEach((item, index, object)=>{
                            let {id,description} = item;
                            htmlOptions += `<option value='${id}' {{ old('Subsegment') == '${id}' ? 'selected' : '' }}>${description}</option>`

                        });
                    }
                    //console.clear();
                    // console.log(htmlOptions);
                    subsegment.html('');
                    subsegment.html(htmlOptions);



                },
                error:(xhr)=>{

                }
            })
        }
    </script>
@endsection

