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
                <div class="card-header text-lg font-weight-bold">Registro de Gastos y Compras</div>

                <div class="card-body">

                        
                        <div class="form-group row">
                            <label for="providers" class="col-md-2 col-form-label text-md-right">Proveedor:</label>
                            <div class="col-md-3">
                                <input id="provider" type="text" class="form-control @error('provider') is-invalid @enderror" name="provider" value="{{ $provider->razon_social ?? '' }}" readonly required autocomplete="provider">
    
                                @error('provider')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                           
                            <label for="date-begin" class="col-md-3 col-form-label text-md-right">Fecha de Factura:</label>
                            <div class="col-md-3">
                                <input id="date-begin" type="date" class="form-control @error('date-begin') is-invalid @enderror" name="date-begin" value="{{ $datenow }}" readonly autocomplete="date-begin">
    
                                @error('date')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="invoice" class="col-md-2 col-form-label text-md-right">Factura de Compra:</label>

                            <div class="col-md-3">
                                <input id="invoice" type="text" class="form-control @error('invoice') is-invalid @enderror" name="invoice" value="{{ $expense->invoice ?? old('invoice') }}" readonly autocomplete="invoice">

                                @error('invoice')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <label for="serie" class="col-md-3 col-form-label text-md-right">N° de Control/Serie:</label>

                            <div class="col-md-3">
                                <input id="serie" type="text" class="form-control @error('serie') is-invalid @enderror" name="serie" value="{{ $expense->serie ?? old('serie') }}" readonly autocomplete="serie">

                                @error('serie')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                       
                        <div class="form-group row">
                           
                            <label for="observation" class="col-md-2 col-form-label text-md-right">Observaciones:</label>

                            <div class="col-md-2">
                                <input id="observation" type="text" class="form-control @error('observation') is-invalid @enderror" name="observation" value="{{ $expense->observation ?? old('observation') }}" readonly autocomplete="observation">

                                @error('observation')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <label for="rate" class="col-md-1 col-form-label text-md-right">Tasa:</label>
                            <div class="col-md-2">
                                <input id="rate" type="text" class="form-control @error('rate') is-invalid @enderror" name="rate" value="{{ $expense->rate ?? $bcv }}" required autocomplete="rate">
                                @error('rate')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <a href="#" onclick="refreshrate()" title="tasaactual"><i class="fa fa-redo-alt"></i></a>  
                                    <label  class="col-md-2 col-form-label text-md-right h6">Tasa actual:</label>
                                    <div class="col-md-2 col-form-label text-md-left">
                                        <label for="tasaactual" id="tasaacutal">{{ number_format($bcv, 2, ',', '.')}}</label>
                            </div>
                                    
                        </div>
                        
                        <form method="POST" action="{{ route('expensesandpurchases.store_detail') }}" enctype="multipart/form-data" onsubmit="return validacion()">
                            @csrf
                            <input id="id_expense" type="hidden" class="form-control @error('id_expense') is-invalid @enderror" name="id_expense" value="{{ $expense->id ?? -1}}" readonly required autocomplete="id_expense">
                            <input id="id_inventory" type="hidden" class="form-control @error('id_inventory') is-invalid @enderror" name="id_inventory" value="{{ $inventory->id ?? -1 }}" readonly required autocomplete="id_inventory">
                            <input id="id_user" type="hidden" class="form-control @error('id_user') is-invalid @enderror" name="id_user" value="{{ Auth::user()->id }}" required  readonly autocomplete="id_user">
                            <input id="rate_expense" type="hidden" class="form-control @error('rate_expense') is-invalid @enderror" name="rate_expense" value="{{ $expense->rate ?? -1}}" readonly required autocomplete="rate_expense"> 
                            <input id="coin_hidde" type="hidden" class="form-control @error('coin_hidde') is-invalid @enderror" name="coin_hidde" value="{{ $coin ?? 'bolivares'}}" readonly required autocomplete="coin_hidde">
                            
                                <div class="form-group row">
                                    <label for="type" class="col-md-2 col-form-label text-md-right">Tipo de Compra:</label>
                                
                                    <div class="col-md-4">
                                        <select id="type_form"  name="type_form" class="form-control" required>
                                            <option value="-1">Seleccionar</option>
                                            @if (isset($inventory))
                                                @if (isset($type) && ($type == 'MERCANCIA'))
                                                    <option value="1" selected>Inventario de Mercancia</option>
                                                    <option value="2">Activos Fijos</option>
                                                    <option value="3">Costos</option>
                                                @else
                                                    <option value="1">Inventario de Mercancia</option>
                                                    <option value="2">Activos Fijos</option>
                                                    <option value="3" selected>Costos</option>
                                                @endif
                                            @else
                                                <option value="1">Inventario de Mercancia</option>
                                                <option value="2">Activos Fijos</option>
                                                <option value="3">Costos</option>
                                            @endif
                                            
                                            
                                            <option value="4">Gastos - Personal</option>
                                            <option value="5">Gastos - Tributos</option>
                                            <option value="6">Gastos - Municipales</option>
                                            <option value="7">Gastos - Administración</option>
                                        </select>
                                    </div>
                                    
                                        <label id="code_inventary_label" for="code_inventary" class="col-md-2 col-form-label text-md-right">Código Inventario: </label>
                                        
                                        <div class="col-md-2">
                                            <input id="code_inventary" type="text" class="form-control @error('code_inventary') is-invalid @enderror" name="code_inventary" value="{{ $inventory->code ?? '' }}"  autocomplete="code_inventary">
            
                                            @error('code_inventary')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                            
                                        </div>
                                        
                                        <div id="btn_code_inventary" class="form-group col-md-1">
                                            <a href="" title="Buscar por Código" onclick="searchCodeInventory()"><i class="fa fa-search"></i></a>  
                                            <a id="btnselectinventory" href="{{ route('expensesandpurchases.selectinventary',[$expense->id,$coin,"mercancia"]) }}" title="Buscar"><i class="fa fa-eye"></i></a>  
                                        
                                        </div>
                                    
                                </div>
                                <div class="form-group row">
                                    <label id="centro_costo_label" for="centro_costo" class="col-md-2 col-form-label text-md-right">Centro Costo:</label>
                                        
                                    <div class="col-sm-3">
                                        <select class="form-control" id="centro_costo" name="centro_costo" title="centro_costo">
                                            <option value="">Ninguno</option>
                                            @if(!empty($branches))
                                                @foreach ($branches as $var)
                                                    <option value="{{ $var->id }}">{{ $var->description }}</option>
                                                @endforeach
                                                
                                            @endif
                                        
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="account" class="col-md-2 col-form-label text-md-right">Cargar a Cuenta:</label>
                                
                                    <div class="col-md-4">
                                        <select  id="account"  name="Account" class="form-control" required>
                                            <option value="">Seleccionar</option>
                                            @if (isset($accounts_inventory))
                                                @foreach ($accounts_inventory as $var)
                                                    <option value="{{ $var->id }}">{{ $var->description }}</option>
                                                @endforeach
                                            @endif
                                        </select>

                                        @if ($errors->has('account'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('account') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                   
                                    <label id="coinlabel" for="coin" class="col-md-2 col-form-label text-md-right">Moneda:</label>
        
                                    <div class="col-md-2">
                                        <select class="form-control" name="coin" id="coin">
                                            <option value="bolivares">Bolívares</option>
                                            @if($coin == 'dolares')
                                                <option selected value="dolares">Dolares</option>
                                            @else 
                                                <option value="dolares">Dolares</option>
                                            @endif
                                        </select>
                                    </div>
                                    
                                </div>
                                
                                <br>
                                <div class="form-row">
                                    <div class="form-group col-md-1">
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label for="description" >Descripción</label>
                                        <input id="description" type="text" class="form-control @error('description') is-invalid @enderror" name="description" value="{{ $inventory->products['description'] ?? old('description') }}"  required autocomplete="description">
        
                                        @error('description')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-1">
                                        <label for="amount" >Cantidad</label>
                                        <input id="amount_product"  type="text" class="form-control @error('amount') is-invalid @enderror" name="amount" value="0" required autocomplete="amount">
        
                                        @error('amount')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-1">
                                        @if (empty($inventory))
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="exento" id="gridCheck">
                                                <label class="form-check-label" for="gridCheck">
                                                    Exento
                                                </label>
                                            </div>
                                        @else  
                                            <div class="form-check">
                                                @if($inventory->products['exento'] == 1)
                                                    <input class="form-check-input" type="checkbox" name="exento" checked id="gridCheck">
                                                @else
                                                    <input class="form-check-input" type="checkbox" name="exento" id="gridCheck">
                                                @endif
                                                <label class="form-check-label" for="gridCheck">
                                                    Exento
                                                </label>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="form-group col-md-1">
                                        @if (empty($inventory))
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="islr" id="gridCheck2">
                                                <label class="form-check-label" for="gridCheck2">
                                                    ISLR
                                                </label>
                                            </div>
                                        @else  
                                            <div class="form-check">
                                                @if($inventory->products['islr'] == 1)
                                                    <input class="form-check-input" type="checkbox" name="islr" checked id="gridCheck2">
                                                @else
                                                    <input class="form-check-input" type="checkbox" name="islr" id="gridCheck2">
                                                @endif
                                                <label class="form-check-label" for="gridCheck2">
                                                    ISLR
                                                </label>
                                            </div>
                                        @endif
                                    </div>
                                    
                                    <div class="form-group col-md-3">
                                        <label for="price" >Precio</label>
                                        @if(isset($inventory->products['price_buy']))
                                            <input id="price" type="text" class="form-control @error('price') is-invalid @enderror" name="price" value="{{ number_format($inventory->products['price_buy'], 2, ',', '.')  }}"  required autocomplete="price">
                                        @else
                                            <input id="price" type="text" class="form-control @error('price') is-invalid @enderror" name="price"  required autocomplete="price">
                                        @endif
                                        @error('price')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                  
                                   
                                    <div class="form-group col-md-1">
                                        <button type="submit" title="Agregar"><i class="fa fa-plus"></i></button>  
                                    </div>
                                </div>    
                        </form>      





                               <div class="card-body">
                                <div class="table-responsive">
                                <table class="table table-light2 table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                    <tr>
                                        
                                        <th class="text-center">Descripción</th>
                                        <th class="text-center">Cantidad</th>
                                        <th class="text-center">Precio</th>
                                        <th class="text-center">Sub Total</th>
                                        <th class="text-center"><i class="fas fa-cog"></i></th>
                                      
                                    </tr>
                                    </thead>
                                    
                                    <tbody>
                                        @if (empty($expense_details))
                                        @else
                                        <?php
                                            $suma = 0.00;
                                        ?>
                                            @foreach ($expense_details as $var)
                                            <?php
                                                if($coin != 'bolivares'){
                                                    $var->price = $var->price / $expense->rate;
                                                }
                                                
                                            ?>
                                           
                                                <tr>
                                               
                                                @if($var->exento == 1)
                                                    <td style="text-align: center">{{ $var->description}} (E)</td>
                                                @else
                                                    <td style="text-align: center">{{ $var->description}}</td>
                                                @endif
                                                
                                                <td style="text-align: right">{{number_format($var->amount, 2, ',', '.')}}</td>
                                                <td style="text-align: right">{{number_format($var->price, 2, ',', '.')}}</td>
                                                <td style="text-align: right">{{number_format($var->price * $var->amount, 2, ',', '.')}}</td>
                                                <?php
                                                    $suma += $var->price * $var->amount;
                                                ?>
                                                    <td style="text-align: right">
                                                        <a href="{{ route('expensesandpurchases.editproduct',[$var->id,$coin]) }}" title="Editar"><i class="fa fa-edit"></i></a>  
                                                        <a href="#" class="delete" data-id={{$var->id}} data-description={{$var->description}} data-coin={{$coin}} data-toggle="modal" data-target="#deleteModal" title="Eliminar"><i class="fa fa-trash text-danger"></i></a>  
                                                   </td>
                                            
                                                </tr>
                                            @endforeach
                                            <tr>
                                                
                                                <td style="text-align: center">-------------</td>
                                                <td style="text-align: center">-------------</td>
                                                <td style="text-align: right">Total</td>
                                                <td style="text-align: right">{{number_format($suma, 2, ',', '.')}}</td>
                                                
                                                <td style="text-align: right"></td>
                                            
                                                </tr>
                                        @endif
                                    </tbody>
                                </table>
                                </div>
                            </div>
                            <div class="form-group row mb-0">
                                @if (empty($expense->date_delivery_note))
                                <div class="col-sm-3 offset-sm-1">
                                    @if($suma == 0)
                                        <a onclick="validate()" id="btnSendNote" name="btnfacturar" class="btn btn-success" title="facturar">Orden de Compra</a>  
                                    @else
                                        <a onclick="deliveryNoteSend()" id="btnSendNote" name="btnfacturar" class="btn btn-success" title="facturar">Orden de Compra</a>  
                                    @endif
                                </div>
                                @endif
                                <div class="col-sm-2">
                                    <a id="btnpayment" href="{{ route('expensesandpurchases.create_payment',[$expense->id,$coin]) }}" name="btnpayment" class="btn btn-info" title="Registrar">Registrar</a>  
                                </div>
                                <div class="col-sm-3  dropdown mb-4">
                                    <button class="btn btn-dark" type="button"
                                        id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="false"
                                        aria-expanded="false">
                                        <i class="fas fa-bars"></i>
                                        Opciones
                                    </button>
                                    <div class="dropdown-menu animated--fade-in" aria-labelledby="dropdownMenuButton">
                                        
                                        <a href="{{ route('export',$expense->id) }}" class="dropdown-item bg-info text-white h5">Descargar Plantilla Excel</a> 
                                        <a href="{{ route('export.guideaccount') }}" class="dropdown-item bg-dark text-white h5">Descargar Guia de Cuentas Excel</a> 
                                        <a href="{{ route('export.guideinventory') }}" class="dropdown-item bg-success text-white h5">Descargar Guia de Inventario Excel</a> 
                                        <form id="fileForm" method="POST" action="{{ route('import') }}" enctype="multipart/form-data" >
                                        @csrf
                                            <input id="file" type="file" value="import" accept=".xlsx" name="file" class="file">
                                            <input id="id_expense" type="hidden" class="form-control @error('id_expense') is-invalid @enderror" name="id_expense" value="{{ $expense->id ?? -1}}" readonly required autocomplete="id_expense"> 
                                            <input id="coin_hidde" type="hidden" class="form-control @error('coin_hidde') is-invalid @enderror" name="coin_hidde" value="{{ $coin ?? 'bolivares'}}" readonly required autocomplete="coin_hidde">
                                            
                                        </form>
                                    </div> 
                                </div> 
                            </div>
                           
                   
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Warning Modal -->
<div class="modal modal-danger fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="Delete" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Eliminar</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
            <form action="{{ route('expensesandpurchases.deleteDetail') }}" method="post">
                @csrf
                @method('DELETE')
                <input id="id_detail_modal" type="hidden" class="form-control @error('id_detail_modal') is-invalid @enderror" name="id_detail_modal" readonly required autocomplete="id_detail_modal">
                <input id="coin_modal" type="hidden" class="form-control @error('coin_modal') is-invalid @enderror" name="coin_modal" readonly required autocomplete="coin_modal">
                       
                <div id="description_modal" class="text-center h5"></div>
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-danger">Eliminar</button>
            </div>
            </form>
        </div>
    </div>
</div>
@endsection

@if (isset($inventory))
    @section('validacionExpense')
        <script>
                $("#code_inventary_label").show();
                $("#code_inventary").show();
                $("#btn_code_inventary").show();

                
        </script>
    @endsection
@endif

@section('consulta')
    <script>
        $('#dataTable').dataTable( {
        "ordering": false,
        "order": [],
            'aLengthMenu': [[50, 100, 150, -1], [50, 100, 150, "All"]],
            'iDisplayLength': '50'
        } );

        $(document).ready(function () {
            $("#rate").mask('000.000.000.000.000,00', { reverse: true });
            
        });

        $(document).ready(function () {
            $("#price").mask('000.000.000.000.000.000.000.000,00', { reverse: true });
            
        });

        $(document).ready(function () {
            $("#amount_product").mask('000.000.000.000.000.000.000.000,00', { reverse: true });
            
        });

        $(document).on('click','.delete',function(){
            let id = $(this).attr('data-id');
            let coin = $(this).attr('data-coin');
            let description = $(this).attr('data-description');

            document.getElementById("description_modal").innerHTML = "Seguro desea eliminar "+description+"?";
            $('#id_detail_modal').val(id);
            $('#coin_modal').val(coin);
            
        });
        
        $("body").toggleClass("sidebar-toggled");
        $(".sidebar").toggleClass("toggled");
        if ($(".sidebar").hasClass("toggled")) {
            $('.sidebar .collapse').collapse('hide');
        };

        $("#coin").on('change',function(){
            coin = $(this).val();
            window.location = "{{route('expensesandpurchases.create_detail', [$expense->id,'','',''])}}"+"/"+coin+"/"+"{{ $type ?? 'SERVICIO' }}"+"/"+"{{ $inventory->id ?? '' }}";
            
        });

        $("#file").on('change',function(){
            
            var file = document.getElementById("file").value;

            /*Extrae la extencion del archivo*/
            var basename = file.split(/[\\/]/).pop(),  // extract file name from full path ...
                                               // (supports `\\` and `/` separators)
            pos = basename.lastIndexOf(".");       // get last position of `.`

            if (basename === "" || pos < 1) {
                alert("El archivo no tiene extension");
            }          
            /*-------------------------------*/     

            if(basename.slice(pos + 1) == 'xlsx'){
                document.getElementById("fileForm").submit();
            }else{
                alert("Solo puede cargar archivos .xlsx");
            }            
               
        });

        function deliveryNoteSend() {
            window.location = "{{route('expensesandpurchases.createdeliverynote', [$expense->id,$coin])}}";
        }



        function validate() {
            alert('Debe ingresar al menos un producto para poder continuar');           
        }


        function refreshrate() {
       
            let rate = document.getElementById("rate").value; 
            window.location = "{{ route('expensesandpurchases.refreshrate',[$expense->id,$coin,'']) }}"+"/"+rate;
            
        }
    </script>
    @if ((isset($type))&& ($type == "SERVICIO"))
    <script>
        
        var type_var = 3;
    </script>
    @else
    <script>
       
        var type_var = '-1';
    </script>
    @endif
    <script>
        $("#code_inventary_label").hide();
        $("#code_inventary").hide();
        $("#btn_code_inventary").hide();
        $("#centro_costo_label").hide();
        $("#centro_costo").hide();

        controlador(type_var);

        $("#type_form").on('change',function(){
            type_var = $(this).val();
            document.getElementById("code_inventary").value = "";
            document.getElementById("description").value = "";
            document.getElementById("price").value = "";
            controlador(type_var);
        });

        function controlador(type_var)
        {
 
            if(type_var == 1){
                    $("#code_inventary_label").show();
                    $("#code_inventary").show();
                    $("#btn_code_inventary").show();
                    $("#centro_costo_label").hide();
                    $("#centro_costo").hide();
                    
                    
                }else if(type_var == 3){
                    $("#code_inventary_label").show();
                    $("#code_inventary").show();
                    $("#btn_code_inventary").show();
                    $("#centro_costo_label").show();
                    $("#centro_costo").show();

                    document.getElementById("code_inventary_label").innerHTML = "Código Servicio:";
                    document.getElementById("btnselectinventory").href = "{{ route('expensesandpurchases.selectinventary',[$expense->id,$coin,'servicio']) }}";


                }else if(type_var != "-1"){
                    $("#code_inventary_label").hide();
                    $("#code_inventary").hide();
                    $("#btn_code_inventary").hide();
                    $("#centro_costo_label").show();
                    $("#centro_costo").show();

                }else if(type_var == "-1"){
                    
                    $("#code_inventary_label").hide();
                    $("#code_inventary").hide();
                    $("#btn_code_inventary").hide();
                    $("#centro_costo_label").hide();
                    $("#centro_costo").hide();
                }
               
               if(type_var != "-1"){
                 
                searchCode(type_var);
               }
        }

        function searchCode(type_var){

            $.ajax({
                
                url:"{{ route('expensesandpurchases.listaccount') }}" + '/' + type_var,
                beforSend:()=>{
                    alert('consultando datos');
                },
                success:(response)=>{
                   
                    let account = $("#account");
                    let htmlOptions = `<option value='' >Seleccione..</option>`;
                    // console.clear();
                    if(response.length > 0){
                        
                        response.forEach((item, index, object)=>{
                            let {id,description} = item;
                            htmlOptions += `<option value='${id}' {{ old('Account') == '${id}' ? 'selected' : '' }}>${description}</option>`

                        });
                    }
                    account.html('');
                    account.html(htmlOptions);
                
                    
                
                },
                error:(xhr)=>{
                }
            })
        }

        function searchCodeInventory(){
            
            let reference_id = document.getElementById("code_inventary").value; 
            
            $.ajax({
                
                url:"{{ route('expensesandpurchases.listinventory',['']) }}" + '/' + reference_id,
                beforSend:()=>{
                    alert('consultando datos');
                },
                success:(response)=>{
                 
                    
                    if(response.length > 0){
                        response.forEach((item, index, object)=>{
                            let {id,type} = item;
                          
                           window.location = "{{route('expensesandpurchases.create_detail', [$expense->id,$coin,'',''])}}"+"/"+type+"/"+id;
                           
                        });
                    }else{
                       
                    }
                   
                },
                error:(xhr)=>{
                   
                }
            })
        }

                $("#account").on('change',function(){
                    var type_form_validate = document.getElementById("type_form").value; 

                   
                    if((type_form_validate != 1) && (type_form_validate != 3)){
                            var e = document.getElementById("account");
                            var text = e.options[e.selectedIndex].text;

                            document.getElementById("description").value = text;
                    
                    }
                        
                    
                });

        </script>
   
    
@endsection


@section('javascript')

<script>
    function validacion() {

        let amount = document.getElementById("amount_product").value; 

        if (amount < 1) {

        alert('La cantidad del Producto debe ser mayor a 1');
        return false;
        }
        else {
            return true;
        }



    }
  
</script>

@endsection



