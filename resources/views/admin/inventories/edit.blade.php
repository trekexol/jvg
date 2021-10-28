@extends('admin.layouts.dashboard')

@section('content')
  
    <!-- container-fluid -->
    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="row py-lg-2">
            <div class="col-md-6">
                <h2>Editar Empleado</h2>
            </div>

        </div>
    </div>
    <!-- /container-fluid -->

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

    <div class="card shadow mb-4">
        <div class="card-body">
            <form  method="POST"   action="{{ route('products.update',$product->id) }}" enctype="multipart/form-data" >
                @method('PATCH')
                @csrf()
                <div class="container py-2">
                    <div class="row">
                        <div class="col-12 ">
                            <form >
                               
                                <div class="form-group row">
                                        <label for="segment_id" class="col-md-2 col-form-label text-md-right">Segmento</label>
                                        <div class="col-md-4">   
                                            <select id="segment_id" name="segment_id" class="form-control" required>
                                                @foreach($segments as $var)
                                                    @if ( $var->segment_id == $var->id   )
                                                        <option  selected style="backgroud-color:blue;" value="{{ $var->id }}"><strong>{{ $var->description }}</strong></option>
                                                    @endif
                                                @endforeach
                                                <option class="hidden" disabled data-color="#A0522D" value="-1">------------------</option>
                                                @foreach($segments as $var2)
                                                    <option value="{{ $var2['id'] }}" >
                                                        {{ $var2['description'] }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div> 
                                        <label for="subsegment" class="col-md-2 col-form-label text-md-right">Sub Segmento</label>
                                        <div class="col-md-4">
                                            <select id="sub_segment_id" name="sub_segment_id" class="form-control" required>
                                                @foreach($subsegments as $var)
                                                    @if ( $var->sub_segment_id == $var->id   )
                                                        <option  selected style="backgroud-color:blue;" value="{{ $var->id }}"><strong>{{ $var->description }}</strong></option>
                                                    @endif
                                                @endforeach
                                                <option class="hidden" disabled data-color="#A0522D" value="-1">------------------</option>
                                                @foreach($subsegments as $var2)
                                                    <option value="{{ $var2['id'] }}" >
                                                        {{ $var2['description'] }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div> 
                                </div>
               
        
                                <div class="form-group row">
                                    <label for="unitofmeasure" class="col-md-2 col-form-label text-md-right">Unidad de Medida</label>
                                     <div class="col-md-4">
                                        <select id="unit_of_measure_id" name="unit_of_measure_id" class="form-control" required>
                                            @foreach($unitofmeasures as $var)
                                                @if ( $var->unit_of_measure_id == $var->id   )
                                                    <option  selected style="backgroud-color:blue;" value="{{ $var->id }}"><strong>{{ $var->description }}</strong></option>
                                                @endif
                                            @endforeach
                                            <option class="hidden" disabled data-color="#A0522D" value="-1">------------------</option>
                                            @foreach($unitofmeasures as $var2)
                                                <option value="{{ $var2['id'] }}" >
                                                    {{ $var2['description'] }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div> 
                                    <label for="code_comercial" class="col-md-2 col-form-label text-md-right">Código Comercial</label>
        
                                    <div class="col-md-4">
                                        <input id="code_comercial" type="text" class="form-control @error('code_comercial') is-invalid @enderror" name="code_comercial" value="{{ $product->code_comercial }}" required autocomplete="code_comercial">
        
                                        @error('code_comercial')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
        
                               
                                <div class="form-group row">
                                    <label for="type" class="col-md-2 col-form-label text-md-right">Tipo</label>
                                    <div class="col-md-4">
                                        <select class="form-control" id="type" name="type" title="type">
                                            @if($product->type == "MERCANCIA")
                                                <option value="MERCANCIA">Mercancía</option>
                                            @else
                                                <option value="SERVICIO">Servicio</option>
                                            @endif
                                            <option value="nulo">----------------</option>
                                            
                                            <div class="dropdown">
                                                <option value="MERCANCIA">Mercancía</option>
                                                <option value="SERVICIO">Servicio</option>
                                            </div>
                                            
                                            
                                        </select>
                                    </div>
                                    <label for="description" class="col-md-2 col-form-label text-md-right">descripción</label>
        
                                    <div class="col-md-4">
                                        <input id="description" type="text" class="form-control @error('description') is-invalid @enderror" name="description" value="{{ $product->description }}" required autocomplete="description">
        
                                        @error('description')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="price" class="col-md-2 col-form-label text-md-right">Precio</label>
        
                                    <div class="col-md-4">
                                        <input id="price" type="text" class="form-control @error('price') is-invalid @enderror" name="price" value="{{ $product->price }}" required autocomplete="price">
        
                                        @error('price')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <label for="price_buy" class="col-md-2 col-form-label text-md-right">Precio Compra</label>
        
                                    <div class="col-md-4">
                                        <input id="price_buy" type="text" class="form-control @error('price_buy') is-invalid @enderror" name="price_buy" value="{{ $product->price_buy }}" required autocomplete="price_buy">
        
                                        @error('price_buy')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
        
                                
                                <div class="form-group row">
                                    <label for="cost_average" class="col-md-2 col-form-label text-md-right">Costo Promedio</label>
        
                                    <div class="col-md-4">
                                        <input id="cost_average" type="text" class="form-control @error('cost_average') is-invalid @enderror" name="cost_average" value="{{ $product->cost_average }}" required autocomplete="cost_average">
        
                                        @error('cost_average')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <label for="photo_product" class="col-md-2 col-form-label text-md-right">Foto del Producto</label>
        
                                    <div class="col-md-4">
                                        <input type="image" src="" alt="" width="48" height="48">
                                        @error('photo_product')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                               
                                <div class="form-group row">
                                    <label for="money" class="col-md-2 col-form-label text-md-right">Moneda</label>
        
                                    <div class="col-md-4">
                                        <select class="form-control" id="money" name="money" title="money">
                                            @if($product->money == "D")
                                                <option value="D">Dolar</option>
                                            @else
                                                <option value="Bs">Bolívares</option>
                                            @endif
                                            <option value="nulo">----------------</option>
                                            
                                            <div class="dropdown">
                                                <option value="D">Dolar</option>
                                                <option value="Bs">Bolívares</option>
                                            </div>
                                            
                                            
                                        </select>
                                    </div>
                                    <label for="exento" class="col-md-2 col-form-label text-md-right">exento</label>
                                    <div class="form-check">
                                        @if($product->exento == "1")
                                            <input class="form-check-input position-static" type="checkbox" id="exento" name="exento" value="1" checked aria-label="...">
                                        @else
                                            <input class="form-check-input position-static" type="checkbox" id="exento" name="exento"  aria-label="...">
                                        @endif
                                    </div>
                                  
                                    <label for="islr" class="col-md-1 col-form-label text-md-right">Islr</label>
                                    <div class="form-check">
                                        @if($product->islr == "1")
                                            <input class="form-check-input position-static" type="checkbox" id="islr" name="islr" value="1" checked aria-label="...">
                                        @else
                                            <input class="form-check-input position-static" type="checkbox" id="islr" name="islr"  aria-label="...">
                                        @endif
                                    </div>
                                </div>
                               
                                <div class="form-group row">
                                    <label for="special_impuesto" class="col-md-2 col-form-label text-md-right">Impuesto Especial</label>
        
                                    <div class="col-md-4">
                                        <input id="special_impuesto" type="number" class="form-control @error('special_impuesto') is-invalid @enderror" name="special_impuesto" value="{{ $product->special_impuesto }}" required autocomplete="special_impuesto">
        
                                        @error('special_impuesto')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                   
                                    <label for="rol" class="col-md-2 col-form-label text-md-right">Status</label>
                
                                    <div class="col-md-4">
                                        <select class="form-control" id="status" name="status" title="status">
                                            @if($product->status == 1)
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
                                <div class="form-group row mb-0">
                                    <div class="col-md-3 offset-md-4">
                                        <button type="submit" class="btn btn-primary">
                                           Actualizar Producto
                                        </button>
                                    </div>
                                    <div class="col-md-2">
                                        <a href="{{ route('inventories') }}" id="btnfacturar" name="btnfacturar" class="btn btn-danger" title="facturar">Volver</a>  
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
@endsection
@section('validacion')
    <script>    
	$(function(){
        soloAlfaNumerico('code');
       
    });
    </script>
@endsection
                    @section('javascript_edit')
                    <script>
                            $("#estado").on('change',function(){
                                var estado_id = $(this).val();
                                // alert(estado_id);
                                getMunicipios(estado_id);
                            });
                
                        function getMunicipios(estado_id){
                           // alert(`../../municipio/list/${estado_id}`);
                            $.ajax({
                                url:`../../municipio/list/${estado_id}`,
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
                                            htmlOptions += `<option value='${id}'>${descripcion}</option>`;
                
                                        });
                                    }
                                    //console.clear();
                                    console.log(htmlOptions);
                                    municipio.html('');
                                    municipio.html(htmlOptions);
                                
                                    
                                
                                },
                                error:(xhr)=>{
                                    alert('Presentamos inconvenientes al consultar los datos');
                                }
                            })
                        }
                
                        $("#municipio").on('change',function(){
                                // var municipio_id = $(this).attr("id");
                                var municipio_id = $(this).val();
                                // alert(municipio_id);
                                var estado_id    = document.getElementById("estado").value;
                                getParroquias(municipio_id,estado_id);
                            });
                
                        function getParroquias(municipio_id,estado_id){
                            $.ajax({
                                url:`../../parroquia/list/${municipio_id}/${estado_id}`,
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
                                            htmlOptions += `<option value='${id}' >${descripcion}</option>`
                
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
                        // Funcion Solo Numero
                        $(function(){
                        soloNumeros('xtelf_local');
                        soloNumeros('xtelf_cel');
                        });
                    
                    </script>
                @endsection