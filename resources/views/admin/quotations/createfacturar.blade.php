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



<div class="container" >
    <div class="row justify-content-center" >
        
            <div class="card" style="width: 70rem;" >
                <div class="card-header" ><h3>Facturar</h3></div>
                <form method="POST" action="{{ route('quotations.storefacturacredit') }}" enctype="multipart/form-data">
                    @csrf   
                <div class="card-body" >

                        <input type="hidden" name="coin" value="{{$coin}}" readonly>

                        <!--Precio de costo de todos los productos-->
                        <input type="hidden" name="price_cost_total" value="{{$price_cost_total}}" readonly>
                        <input id="user_id" type="hidden" class="form-control @error('user_id') is-invalid @enderror" name="user_id" value="{{ Auth::user()->id }}" required autocomplete="user_id">
                       
                        <input type="hidden" id="total_mercancia_credit" name="total_mercancia_credit" value="{{$total_mercancia ?? 0 / ($bcv ?? 1)}}" readonly>
                        <input type="hidden" id="total_servicios_credit" name="total_servicios_credit" value="{{$total_servicios ?? 0 / ($bcv ?? 1)}}" readonly>

                        <div class="form-group row">
                            <label for="date-begin" class="col-md-2 col-form-label text-md-right">Fecha:</label>
                            <div class="col-md-3">
                                <input id="date-begin" type="date" class="form-control @error('date-begin') is-invalid @enderror" name="date-begin" value="{{ $quotation->date_billing ?? $quotation->date_delivery_note ?? $datenow }}" autocomplete="date-begin">
    
                                @error('date')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <label for="date-payment" class="col-md-3 col-form-label text-md-right">Fecha del Pago:</label>
                            <div class="col-md-3">
                                <input id="date-payment" type="date" class="form-control @error('date-payment') is-invalid @enderror" name="date-payment" value="{{ $datenow }}" autocomplete="date-payment">
    
                                @error('date')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="cedula_rif" class="col-md-2 col-form-label text-md-right">CI/Rif Cliente:</label>
                            <div class="col-md-4">
                                <input id="cedula_rif" type="text" class="form-control @error('cedula_rif') is-invalid @enderror" name="cedula_rif" value="{{ $quotation->clients['cedula_rif']  ?? '' }}" readonly required autocomplete="cedula_rif">

                                @error('cedula_rif')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <label for="serie" class="col-md-2 col-form-label text-md-right">N° de Control/Serie:</label>
                            <div class="col-md-3">
                                <input id="serie" type="text" class="form-control @error('serie') is-invalid @enderror" name="serie" value="{{ $quotation->serie ?? '' }}" readonly required autocomplete="serie">
                                @error('serie')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            
                        </div>

                        <div class="form-group row">
                            <label for="total_factura" class="col-md-2 col-form-label text-md-right">Total Factura:</label>
                            <div class="col-md-4">
                                <input id="total_factura" type="text" class="form-control @error('total_factura') is-invalid @enderror" name="total_factura" value="{{ number_format($quotation->total_factura / ($bcv ?? 1) , 2, ',', '.') ?? 0 }}" readonly required autocomplete="total_factura">
    
                                @error('total_factura')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <label for="base_imponible" class="col-md-2 col-form-label text-md-right">Base Imponible:</label>
                            <div class="col-md-3">
                                <input id="base_imponible" type="text" class="form-control @error('base_imponible') is-invalid @enderror" name="base_imponible" value="{{ number_format($quotation->base_imponible / ($bcv ?? 1) , 2, ',', '.') ?? 0 }}" readonly required autocomplete="base_imponible">
                                @error('base_imponible')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="porc_retencion_iva" class="col-md-4 col-form-label text-md-right">Porcentaje Retención Iva:</label>
                            <div class="col-md-2">
                                <input id="porc_retencion_iva" type="text" class="form-control @error('porc_retencion_iva') is-invalid @enderror" value="{{ $client->percentage_retencion_iva ?? 0 }}" readonly name="porc_retencion_iva" autocomplete="porc_retencion_iva">
    
                                @error('porc_retencion_iva')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <label for="porc_retencion_islr" class="col-md-3 col-form-label text-md-right">Porcentaje Retención ISLR:</label>
                            <div class="col-md-2">
                                <input id="porc_retencion_islr" type="text" class="form-control @error('porc_retencion_islr') is-invalid @enderror" value="{{ $client->percentage_retencion_islr ?? 0 }}" readonly name="porc_retencion_islr"  autocomplete="porc_retencion_islr">
                                @error('porc_retencion_islr')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                       
                        <div class="form-group row">
                            <label for="iva_amount" class="col-md-2 col-form-label text-md-right">Monto de Iva</label>
                            <div class="col-md-4">
                                <input id="iva_amount" type="text" class="form-control @error('iva_amount') is-invalid @enderror" name="iva_amount" readonly required autocomplete="iva_amount">

                                @error('iva_amount')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <label for="iva_retencion" class="col-md-2 col-form-label text-md-right">IVA Percibido:</label>

                            <div class="col-md-3">
                                <input id="iva_retencion" type="text" class="form-control @error('iva_retencion') is-invalid @enderror" name="iva_retencion" value="{{ number_format(($total_iva_pcb ?? 0) / ($bcv ?? 1), 2, ',', '.') }}" readonly required autocomplete="iva_retencion">

                                @error('iva_retencion')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="grand_totals" class="col-md-2 col-form-label text-md-right">Total General</label>
                            <div class="col-md-4">
                                <input id="grand_total" type="text" class="form-control @error('grand_total') is-invalid @enderror" name="grand_total"  readonly required autocomplete="grand_total"> 
                           
                                @error('grand_total')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <label for="islr_retencion" class="col-md-2 col-form-label text-md-right">Retencion ISLR:</label>

                            <div class="col-md-3">
                                <input id="islr_retencion" type="text" class="form-control @error('islr_retencion') is-invalid @enderror" name="islr_retencion" value="{{ number_format($total_retiene_islr / ($bcv ?? 1), 2, ',', '.') }}" readonly required autocomplete="islr_retencion">

                                @error('islr_retencion')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        
                        
                        <div class="form-group row">

                            <label for="anticipo" class="col-md-2 col-form-label text-md-right">Menos Anticipo:</label>
                            @if (empty($anticipos_sum))
                                <div class="col-md-3">
                                    <input id="anticipo" type="text" class="form-control @error('anticipo') is-invalid @enderror" name="anticipo" placeholder="0,00"  value="0,00" readonly required autocomplete="anticipo"> 
                            
                                    @error('anticipo')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            @else
                                <div class="col-md-3">
                                    <input id="anticipo" type="text" class="form-control @error('anticipo') is-invalid @enderror" name="anticipo" value="{{ number_format($anticipos_sum ?? 0, 2, ',', '.') ?? 0.00 }}" readonly required autocomplete="anticipo"> 
                            
                                    @error('anticipo')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            @endif
                            <div class="col-md-1">
                                <a href="{{ route('anticipos.selectanticipo',[$quotation->id_client,$coin,$quotation->id]) }}" title="Productos"><i class="fa fa-eye"></i></a>  
                            </div>
                            <label for="iva" class="col-md-1 col-form-label text-md-right">IVA:</label>
                            <div class="col-md-2">
                                <select class="form-control" name="iva" id="iva">
                                    @if(isset($quotation->iva_percentage))
                                        <option value="{{ $quotation->iva_percentage }}">{{ $quotation->iva_percentage }}%</option>
                                    @else
                                        <option value="16">16%</option>
                                        <option value="12">12%</option>
                                    @endif
                                    
                                </select>
                            </div>
                            
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
                        <div class="form-group row">
                            
                        </div>
             
                        <input type="hidden" name="id_quotation" value="{{$quotation->id}}" readonly>

                        <div class="form-group row">
                            <label for="total_pays" class="col-md-2 col-form-label text-md-right">Total a Pagar</label>
                            <div class="col-md-4">
                                <input id="total_pay" type="text" class="form-control @error('total_pay') is-invalid @enderror" name="total_pay" readonly  required autocomplete="total_pay"> 
                           
                                @error('total_pay')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            @if (isset($is_after) && ($is_after == true))
                                <div class="col-md-2">
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" id="customSwitches">
                                        <label class="custom-control-label" for="customSwitches">Tiene Crédito</label>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <input id="credit" type="text" class="form-control @error('credit') is-invalid @enderror" name="credit" placeholder="Dias de Crédito" autocomplete="credit"> 
                                </div>
                            @endif
                            
                        </div>
                        <br>
                        @if (isset($is_after) && ($is_after == true))
                            <div class="form-group row" id="formenviarcredito">
                                
                                <div class="col-md-2">
                                </div>
                                <div class="col-md-3">
                                    <button type="submit" class="btn btn-primary">
                                        Guardar Factura
                                    </button>
                                </div>
                                <div class="col-md-2">
                                    <a href="{{ route('quotations.create',[$quotation->id,$coin]) }}" id="btnfacturar" name="btnfacturar" class="btn btn-danger" title="facturar">Volver</a>  
                                </div>
                            </div>
                        @endif
                        
                        
            </form>           
            <form method="POST" action="{{ route('quotations.storefactura') }}" enctype="multipart/form-data">
                @csrf   

                        <input type="hidden" name="id_quotation" value="{{$quotation->id}}" readonly>

                        <input type="hidden" id="date-begin-form" name="date-begin-form" value="{{$quotation->date_billing ?? $quotation->date_delivery_note ?? $datenow}}" readonly>

                        <input type="hidden" id="date-payment-form" name="date-payment-form" value="{{$datenow}}" readonly>

                        <input type="hidden" name="coin" value="{{$coin}}" readonly>

                        <!--Precio de costo de todos los productos-->
                        <input type="hidden" name="price_cost_total" value="{{$price_cost_total}}" readonly>

                        <!--CANTIDAD DE PAGOS QUE QUIERO ENVIAR-->
                        <input type="hidden" id="amount_of_payments" name="amount_of_payments"  readonly>

                        
                        <!--Total del pago que se va a realizar-->
                        <input type="hidden" id="base_imponible_form" name="base_imponible_form"  readonly>

                        <!--Total del pago que se va a realizar-->
                        <input type="hidden" id="sub_total_form" name="sub_total_form" value="{{ $quotation->total_factura / ($bcv ?? 1)}}" readonly>
                        
                        <!--Total de la factura sin restarle nada que se va a realizar-->
                        <input type="hidden" id="grandtotal_form" name="grandtotal_form"  readonly>
                        
                        <!--Total del pago que se va a realizar-->
                        <input type="hidden" id="total_pay_form" name="total_pay_form"  readonly>

                      

                        <!--Porcentaje de iva aplicado que se va a realizar-->
                        <input type="hidden" id="iva_form" name="iva_form"  readonly>
                        <input type="hidden" id="iva_amount_form" name="iva_amount_form" readonly>

                        <input type="hidden" id="iva_percibido_form" name="iva_percibido_form" value="{{$total_iva_pcb ?? 0}}" readonly>

                        <!--Anticipo aplicado que se va a realizar-->
                        <input type="hidden" id="anticipo_form" name="anticipo_form"  readonly>

                        <input id="user_id" type="hidden" class="form-control @error('user_id') is-invalid @enderror" name="user_id" value="{{ Auth::user()->id }}" required autocomplete="user_id">
                        
                        <input type="hidden" id="total_retiene_iva" name="total_retiene_iva"  readonly>
                        <input type="hidden" id="total_retiene_islr" name="total_retiene_islr" value="{{$total_retiene_islr / ($bcv ?? 1)}}" readonly>

                        <input type="hidden" id="total_mercancia" name="total_mercancia" value="{{$total_mercancia ?? 0 / ($bcv ?? 1)}}" readonly>
                        <input type="hidden" id="total_servicios" name="total_servicios" value="{{$total_servicios ?? 0 / ($bcv ?? 1)}}" readonly>

                        
                        <div class="form-group row" id="formulario1" >
                            <label id="label_amount_pays" for="amount_pays" class="col-md-2 col-form-label text-md-right">Forma de Pago:</label>
                            <div class="col-md-3">
                                <input id="amount_pay" type="text" class="form-control @error('amount_pay') is-invalid @enderror"  name="amount_pay" placeholder="0,00" required autocomplete="amount_pay"> 
                           
                                @error('amount_pay')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                          
                            <div class="col-md-3">
                                    <select  id="payment_type" required name="payment_type" class="form-control">
                                        <option selected value="">Tipo de Pago 1</option>
                                        <option value="1">Cheque</option>
                                        <option value="2">Contado</option>
                                        
                                        
                                        <option value="5">Depósito Bancario</option>
                                        <option value="6">Efectivo</option>
                                        <option value="7">Indeterminado</option>
                                        
                                        <option value="9">Tarjeta de Crédito</option>
                                        <option value="10">Tarjeta de Débito</option>
                                        <option value="11">Transferencia</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <select  id="account_bank"  name="account_bank" class="form-control">
                                        <option selected value="0">Seleccione una Opcion</option>
                                        @foreach($accounts_bank as $account)
                                                <option  value="{{$account->id}}">{{ $account->description }}</option>
                                           @endforeach
                                       
                                    </select>
                                    <select  id="account_efectivo"  name="account_efectivo" class="form-control">
                                        <option selected value="0">Seleccione una Opcion</option>
                                        @foreach($accounts_efectivo as $account)
                                                <option  value="{{$account->id}}">{{ $account->description }}</option>
                                           @endforeach
                                       
                                    </select>
                                    <select  id="account_punto_de_venta"  name="account_punto_de_venta" class="form-control">
                                        <option selected value="0">Seleccione una Opcion</option>
                                        @foreach($accounts_punto_de_venta as $account)
                                                <option  value="{{$account->id}}">{{ $account->description }}</option>
                                           @endforeach
                                       
                                    </select>
                                    <input id="credit_days" type="text" class="form-control @error('credit_days') is-invalid @enderror" name="credit_days" placeholder="Dias de Crédito" autocomplete="credit_days"> 
                           
                                    @error('credit_days')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                    <br>
                                    <input id="reference"  maxlength="40" type="text" class="form-control @error('reference') is-invalid @enderror" name="reference" placeholder="Referencia" autocomplete="reference"> 
                           
                                    @error('reference')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="form-group col-md-1">
                                    <a id="btn_agregar" class="btn btn-info btn-circle" onclick="addForm()" title="Agregar"><i class="fa fa-plus"></i></a>  
                                </div>
                        </div>
                        <div id="formulario2" class="form-group row" style="display:none;">
                                <label for="amount_pay2s" class="col-md-2 col-form-label text-md-right">Forma de Pago 2:</label>
                                <div class="col-md-3">
                                    <input id="amount_pay2" type="text" class="form-control @error('amount_pay2') is-invalid @enderror" placeholder="0,00" name="amount_pay2"   autocomplete="amount_pay2"> 
                            
                                    @error('amount_pay2')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                          
                                <div class="col-md-3">
                                    <select  id="payment_type2" name="payment_type2" class="form-control">
                                        <option selected value="0">Tipo de Pago 2</option>
                                        <option value="1">Cheque</option>
                                        <option value="2">Contado</option>
                                        
                                        
                                        <option value="5">Depósito Bancario</option>
                                        <option value="6">Efectivo</option>
                                        <option value="7">Indeterminado</option>
                                    
                                        <option value="9">Tarjeta de Crédito</option>
                                        <option value="10">Tarjeta de Débito</option>
                                        <option value="11">Transferencia</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <select  id="account_bank2"  name="account_bank2" class="form-control">
                                        <option selected value="0">Seleccione una Opcion</option>
                                        @foreach($accounts_bank as $account)
                                                <option  value="{{$account->id}}">{{ $account->description }}</option>
                                           @endforeach
                                       
                                    </select>
                                    <select  id="account_efectivo2"  name="account_efectivo2" class="form-control">
                                        <option selected value="0">Seleccione una Opcion</option>
                                        @foreach($accounts_efectivo as $account)
                                                <option  value="{{$account->id}}">{{ $account->description }}</option>
                                           @endforeach
                                       
                                    </select>
                                    <select  id="account_punto_de_venta2"  name="account_punto_de_venta2" class="form-control">
                                        <option selected value="0">Seleccione una Opcion</option>
                                        @foreach($accounts_punto_de_venta as $account)
                                                <option  value="{{$account->id}}">{{ $account->description }}</option>
                                           @endforeach
                                       
                                    </select>
                                    <input id="credit_days2" type="text" class="form-control @error('credit_days2') is-invalid @enderror" name="credit_days2" placeholder="Dias de Crédito" autocomplete="credit_days2"> 
                           
                                    @error('credit_days2')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                    <br>
                                    <input id="reference2" maxlength="40"  type="text"  class="form-control @error('reference2') is-invalid @enderror" name="reference2" placeholder="Referencia" autocomplete="reference2"> 
                           
                                    @error('reference2')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="form-group col-md-1">
                                    <a id="btn_agregar2" class="btn btn-danger btn-circle" onclick="deleteForm()" title="Eliminar"><i class="fa fa-trash"></i></a>  
                                </div>
                                
                        </div>
                       
                        <div id="formulario3" class="form-group row" style="display:none;">
                            <label for="amount_pay3s" class="col-md-2 col-form-label text-md-right">Forma de Pago 3:</label>
                            <div class="col-md-3">
                                <input id="amount_pay3" type="text" class="form-control @error('amount_pay3') is-invalid @enderror" placeholder="0,00" name="amount_pay3" placeholder="Monto del Pago"  autocomplete="amount_pay3"> 
                        
                                @error('amount_pay3')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                      
                            <div class="col-md-3">
                                <select  id="payment_type3"  name="payment_type3" class="form-control">
                                    <option selected value="0">Tipo de Pago 3</option>
                                    <option value="1">Cheque</option>
                                    <option value="2">Contado</option>
                                    
                                    
                                    <option value="5">Depósito Bancario</option>
                                    <option value="6">Efectivo</option>
                                    <option value="7">Indeterminado</option>
                                    
                                    <option value="9">Tarjeta de Crédito</option>
                                    <option value="10">Tarjeta de Débito</option>
                                    <option value="11">Transferencia</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select  id="account_bank3"  name="account_bank3" class="form-control">
                                    <option selected value="0">Seleccione una Opcion</option>
                                    @foreach($accounts_bank as $account)
                                            <option  value="{{$account->id}}">{{ $account->description }}</option>
                                       @endforeach
                                   
                                </select>
                                <select  id="account_efectivo3"  name="account_efectivo3" class="form-control">
                                    <option selected value="0">Seleccione una Opcion</option>
                                    @foreach($accounts_efectivo as $account)
                                            <option  value="{{$account->id}}">{{ $account->description }}</option>
                                       @endforeach
                                   
                                </select>
                                <select  id="account_punto_de_venta3"  name="account_punto_de_venta3" class="form-control">
                                    <option selected value="0">Seleccione una Opcion</option>
                                    @foreach($accounts_punto_de_venta as $account)
                                            <option  value="{{$account->id}}">{{ $account->description }}</option>
                                       @endforeach
                                   
                                </select>
                                <input id="credit_days3" type="text" class="form-control @error('credit_days3') is-invalid @enderror" name="credit_days3" placeholder="Dias de Crédito" autocomplete="credit_days3"> 
                       
                                @error('credit_days3')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                                <br>
                                <input id="reference3" maxlength="40"  type="text" class="form-control @error('reference3') is-invalid @enderror" name="reference3" placeholder="Referencia" autocomplete="reference3"> 
                       
                                @error('reference3')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group col-md-1">
                                <a id="btn_agregar3" class="btn btn-danger btn-circle" onclick="deleteForm()" title="Eliminar"><i class="fa fa-trash"></i></a>  
                            </div>
                            
                        </div>
                        <div id="formulario4" class="form-group row" style="display:none;">
                            <label for="amount_pay4s" class="col-md-2 col-form-label text-md-right">Forma de Pago 4:</label>
                            <div class="col-md-3">
                                <input id="amount_pay4" type="text" class="form-control @error('amount_pay4') is-invalid @enderror" placeholder="0,00" name="amount_pay4" placeholder="Monto del Pago"  autocomplete="amount_pay4"> 
                        
                                @error('amount_pay4')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                      
                            <div class="col-md-3">
                                <select  id="payment_type4"  name="payment_type4" class="form-control">
                                    <option selected value="0">Tipo de Pago 4</option>
                                    <option value="1">Cheque</option>
                                    <option value="2">Contado</option>
                                    
                                    
                                    <option value="5">Depósito Bancario</option>
                                    <option value="6">Efectivo</option>
                                    <option value="7">Indeterminado</option>
                                    
                                    <option value="9">Tarjeta de Crédito</option>
                                    <option value="10">Tarjeta de Débito</option>
                                    <option value="11">Transferencia</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select  id="account_bank4"  name="account_bank4" class="form-control">
                                    <option selected value="0">Seleccione una Opcion</option>
                                    @foreach($accounts_bank as $account)
                                            <option  value="{{$account->id}}">{{ $account->description }}</option>
                                       @endforeach
                                   
                                </select>
                                <select  id="account_efectivo4"  name="account_efectivo4" class="form-control">
                                    <option selected value="0">Seleccione una Opcion</option>
                                    @foreach($accounts_efectivo as $account)
                                            <option  value="{{$account->id}}">{{ $account->description }}</option>
                                       @endforeach
                                   
                                </select>
                                <select  id="account_punto_de_venta4"  name="account_punto_de_venta4" class="form-control">
                                    <option selected value="0">Seleccione una Opcion</option>
                                    @foreach($accounts_punto_de_venta as $account)
                                            <option  value="{{$account->id}}">{{ $account->description }}</option>
                                       @endforeach
                                   
                                </select>
                                <input id="credit_days4" type="text" class="form-control @error('credit_days4') is-invalid @enderror" name="credit_days4" placeholder="Dias de Crédito" autocomplete="credit_days4"> 
                       
                                @error('credit_days4')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                                <br>
                                <input id="reference4" maxlength="40"  type="text" class="form-control @error('reference4') is-invalid @enderror" name="reference4" placeholder="Referencia" autocomplete="reference4"> 
                       
                                @error('reference4')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group col-md-1">
                                <a id="btn_agregar4" class="btn btn-danger btn-circle" onclick="deleteForm()" title="Eliminar"><i class="fa fa-trash"></i></a>  
                            </div>
                            
                        </div>
                        <div id="formulario5" class="form-group row" style="display:none;">
                            <label for="amount_pay5s" class="col-md-2 col-form-label text-md-right">Forma de Pago 5:</label>
                            <div class="col-md-3">
                                <input id="amount_pay5" type="text" class="form-control @error('amount_pay5') is-invalid @enderror" placeholder="0,00" name="amount_pay5" placeholder="Monto del Pago"  autocomplete="amount_pay5"> 
                        
                                @error('amount_pay5')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                      
                            <div class="col-md-3">
                                <select  id="payment_type5"  name="payment_type5" class="form-control">
                                    <option selected value="0">Tipo de Pago 5</option>
                                    <option value="1">Cheque</option>
                                    <option value="2">Contado</option>
                                    
                                    
                                    <option value="5">Depósito Bancario</option>
                                    <option value="6">Efectivo</option>
                                    <option value="7">Indeterminado</option>
                                
                                    <option value="9">Tarjeta de Crédito</option>
                                    <option value="10">Tarjeta de Débito</option>
                                    <option value="11">Transferencia</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select  id="account_bank5"  name="account_bank5" class="form-control">
                                    <option selected value="0">Seleccione una Opcion</option>
                                    @foreach($accounts_bank as $account)
                                            <option  value="{{$account->id}}">{{ $account->description }}</option>
                                       @endforeach
                                   
                                </select>
                                <select  id="account_efectivo5"  name="account_efectivo5" class="form-control">
                                    <option selected value="0">Seleccione una Opcion</option>
                                    @foreach($accounts_efectivo as $account)
                                            <option  value="{{$account->id}}">{{ $account->description }}</option>
                                       @endforeach
                                   
                                </select>
                                <select  id="account_punto_de_venta5"  name="account_punto_de_venta5" class="form-control">
                                    <option selected value="0">Seleccione una Opcion</option>
                                    @foreach($accounts_punto_de_venta as $account)
                                            <option  value="{{$account->id}}">{{ $account->description }}</option>
                                       @endforeach
                                   
                                </select>
                                <input id="credit_days5" type="text" class="form-control @error('credit_days5') is-invalid @enderror" name="credit_days5" placeholder="Dias de Crédito" autocomplete="credit_days5"> 
                       
                                @error('credit_days5')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                                <br>
                                <input id="reference5" maxlength="40"  type="text" class="form-control @error('reference5') is-invalid @enderror" name="reference5" placeholder="Referencia" autocomplete="reference5"> 
                       
                                @error('reference5')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group col-md-1">
                                <a id="btn_agregar5" class="btn btn-danger btn-circle" onclick="deleteForm()" title="Eliminar"><i class="fa fa-trash"></i></a>  
                            </div>
                            
                        </div>
                        <div id="formulario6" class="form-group row" style="display:none;">
                            <label for="amount_pay6s" class="col-md-2 col-form-label text-md-right">Forma de Pago 6:</label>
                            <div class="col-md-3">
                                <input id="amount_pay6" type="text" class="form-control @error('amount_pay6') is-invalid @enderror" placeholder="0,00" name="amount_pay6" placeholder="Monto del Pago"  autocomplete="amount_pay6"> 
                        
                                @error('amount_pay6')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                      
                            <div class="col-md-3">
                                <select  id="payment_type6"  name="payment_type6" class="form-control">
                                    <option selected value="0">Tipo de Pago 6</option>
                                    <option value="1">Cheque</option>
                                    <option value="2">Contado</option>
                                    
                                    
                                    <option value="5">Depósito Bancario</option>
                                    <option value="6">Efectivo</option>
                                    <option value="7">Indeterminado</option>
                                    
                                    <option value="9">Tarjeta de Crédito</option>
                                    <option value="10">Tarjeta de Débito</option>
                                    <option value="11">Transferencia</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select  id="account_bank6"  name="account_bank6" class="form-control">
                                    <option selected value="0">Seleccione una Opcion</option>
                                    @foreach($accounts_bank as $account)
                                            <option  value="{{$account->id}}">{{ $account->description }}</option>
                                       @endforeach
                                   
                                </select>
                                <select  id="account_efectivo6"  name="account_efectivo6" class="form-control">
                                    <option selected value="0">Seleccione una Opcion</option>
                                    @foreach($accounts_efectivo as $account)
                                            <option  value="{{$account->id}}">{{ $account->description }}</option>
                                       @endforeach
                                   
                                </select>
                                <select  id="account_punto_de_venta6"  name="account_punto_de_venta6" class="form-control">
                                    <option selected value="0">Seleccione una Opcion</option>
                                    @foreach($accounts_punto_de_venta as $account)
                                            <option  value="{{$account->id}}">{{ $account->description }}</option>
                                       @endforeach
                                   
                                </select>
                                <input id="credit_days6" type="text" class="form-control @error('credit_days6') is-invalid @enderror" name="credit_days6" placeholder="Dias de Crédito" autocomplete="credit_days6"> 
                       
                                @error('credit_days6')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                                <br>
                                <input id="reference6" maxlength="40"  type="text" class="form-control @error('reference6') is-invalid @enderror" name="reference6" placeholder="Referencia" autocomplete="reference6"> 
                       
                                @error('reference6')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group col-md-1">
                                <a id="btn_agregar6" class="btn btn-danger btn-circle" onclick="deleteForm()" title="Eliminar"><i class="fa fa-trash"></i></a>  
                            </div>
                            
                        </div>
                        <div id="formulario7" class="form-group row" style="display:none;">
                            <label for="amount_pay7s" class="col-md-2 col-form-label text-md-right">Forma de Pago 7:</label>
                            <div class="col-md-3">
                                <input id="amount_pay7" type="text" class="form-control @error('amount_pay7') is-invalid @enderror" placeholder="0,00" name="amount_pay7" placeholder="Monto del Pago"  autocomplete="amount_pay7"> 
                        
                                @error('amount_pay7')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                      
                            <div class="col-md-3">
                                <select  id="payment_type7"  name="payment_type7" class="form-control">
                                    <option selected value="0">Tipo de Pago 7</option>
                                    <option value="1">Cheque</option>
                                    <option value="2">Contado</option>
                                    
                                    
                                    <option value="5">Depósito Bancario</option>
                                    <option value="6">Efectivo</option>
                                    <option value="7">Indeterminado</option>
                                    
                                    <option value="9">Tarjeta de Crédito</option>
                                    <option value="10">Tarjeta de Débito</option>
                                    <option value="11">Transferencia</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select  id="account_bank7"  name="account_bank7" class="form-control">
                                    <option selected value="0">Seleccione una Opcion</option>
                                    @foreach($accounts_bank as $account)
                                            <option  value="{{$account->id}}">{{ $account->description }}</option>
                                       @endforeach
                                   
                                </select>
                                <select  id="account_efectivo7"  name="account_efectivo7" class="form-control">
                                    <option selected value="0">Seleccione una Opcion</option>
                                    @foreach($accounts_efectivo as $account)
                                            <option  value="{{$account->id}}">{{ $account->description }}</option>
                                       @endforeach
                                   
                                </select>
                                <select  id="account_punto_de_venta7"  name="account_punto_de_venta7" class="form-control">
                                    <option selected value="0">Seleccione una Opcion</option>
                                    @foreach($accounts_punto_de_venta as $account)
                                            <option  value="{{$account->id}}">{{ $account->description }}</option>
                                       @endforeach
                                   
                                </select>
                                <input id="credit_days7" type="text" class="form-control @error('credit_days7') is-invalid @enderror" name="credit_days7" placeholder="Dias de Crédito" autocomplete="credit_days7"> 
                       
                                @error('credit_days7')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                                <br>
                                <input id="reference7" maxlength="40"  type="text" class="form-control @error('reference7') is-invalid @enderror" name="reference7" placeholder="Referencia" autocomplete="reference7"> 
                       
                                @error('reference7')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group col-md-1">
                                <a id="btn_agregar7" class="btn btn-danger btn-circle" onclick="deleteForm()" title="Eliminar"><i class="fa fa-trash"></i></a>  
                            </div>
                            
                        </div>
                        <br>
                        <div class="form-group row" id="enviarpagos">
                            <div class="col-md-2">
                            </div>
                            <div class="col-md-3">
                                <button type="submit" class="btn btn-primary">
                                    Guardar Factura
                                 </button>
                            </div>
                            
                            <div class="col-md-2">
                            @if(isset($quotation->date_delivery_note))
                                 <a href="{{ route('quotations.indexdeliverynote') }}" id="btnfacturar" name="btnfacturar" class="btn btn-danger" title="facturar">Volver</a>  
                            @else
                                @if (isset($is_after) && ($is_after == false))
                                    <a href="{{ route('invoices') }}" id="btnfacturar" name="btnfacturar" class="btn btn-danger" title="facturar">Volver</a>                             
                                @else
                                    <a href="{{ route('quotations.create',[$quotation->id,$coin]) }}" id="btnfacturar" name="btnfacturar" class="btn btn-danger" title="facturar">Volver</a>  
                                @endif
                            @endif
                             </div>
                        </div>
                        
                    </form>    
                </div>
            </div>
        </div>
</div>
@endsection

@section('quotation_facturar')
    <script src="{{asset('js/facturar.js')}}"></script> 
@endsection   


@section('consulta')
    <script>
        $("#credit").hide();
        $("#formenviarcredito").hide();
        var switchStatus = false;
        $("#customSwitches").on('change', function() {
            if ($(this).is(':checked')) {
                switchStatus = $(this).is(':checked');
                $("#credit").show();
                $("#formulario1").hide();
                $("#formulario2").hide();
                $("#formulario3").hide();
                $("#formulario4").hide();
                $("#formulario5").hide();
                $("#formulario6").hide();
                $("#formulario7").hide();
                $("#formenviarcredito").show();
                $("#enviarpagos").hide();
                number_form = 1; 
            }
            else {
            switchStatus = $(this).is(':checked');
                $("#credit").hide();
                $("#formulario1").show();
                $("#formenviarcredito").hide();
                $("#enviarpagos").show();
            }
        });


        $(document).ready(function () {
            $("#credit").mask('0000', { reverse: true });
            
        });
        $("#coin").on('change',function(){
            coin = $(this).val();
            window.location = "{{route('quotations.createfacturar', [$quotation->id,''])}}"+"/"+coin;
        });

        $("#date-begin").on('change',function(){
            document.getElementById("date-begin-form").value = $(this).val();
            
        });

        $("#date-payment").on('change',function(){
            document.getElementById("date-payment-form").value = $(this).val();
            
        });

        $("body").toggleClass("sidebar-toggled");
        $(".sidebar").toggleClass("toggled");
        if ($(".sidebar").hasClass("toggled")) {
            $('.sidebar .collapse').collapse('hide');
        };
    </script>
    <script type="text/javascript">

            calculate();

            function calculate() {
                let inputIva = document.getElementById("iva").value; 

                //let totalIva = (inputIva * "<?php echo $quotation->total_factura; ?>") / 100;  

                let totalFactura = "<?php echo $quotation->total_factura  / ($bcv ?? 1) ?>";       

                //AQUI VAMOS A SACAR EL MONTO DEL IVA DE LOS QUE ESTAN EXENTOS, PARA LUEGO RESTARSELO AL IVA TOTAL
                let totalBaseImponible = "<?php echo $quotation->base_imponible  / ($bcv ?? 1) ?>";

                let totalIvaMenos = (inputIva * "<?php echo $quotation->base_imponible  / ($bcv ?? 1) ; ?>") / 100;  

                let iva_percibido = "<?php echo $total_iva_pcb / ($bcv ?? 1) ; ?>";

               
                /*Toma la Base y la envia por form*/
                let base_imponible_form = document.getElementById("base_imponible").value; 

                var montoFormat = base_imponible_form.replace(/[$.]/g,'');

                var montoFormat_base_imponible_form = montoFormat.replace(/[,]/g,'.');    

                document.getElementById("base_imponible_form").value =  montoFormat_base_imponible_form;
                /*-----------------------------------*/
                /*Toma la Base y la envia por form*/
                let sub_total_form = document.getElementById("total_factura").value; 

                var montoFormat = sub_total_form.replace(/[$.]/g,'');

                var montoFormat_sub_total_form = montoFormat.replace(/[,]/g,'.');    

                //document.getElementById("sub_total_form").value =  montoFormat_sub_total_form;
                /*-----------------------------------*/

                var total_iva_exento =  parseFloat(totalIvaMenos);

                var iva_format = total_iva_exento.toLocaleString('de-DE', {minimumFractionDigits: 2,maximumFractionDigits: 2});
               

                //document.getElementById("retencion").value = parseFloat(totalIvaMenos);
                //------------------------------

                document.getElementById("iva_amount").value = iva_format;


                var numbertotalfactura = parseFloat(totalFactura).toFixed(2);
                var numbertotal_iva_exento = parseFloat(total_iva_exento).toFixed(2);
                
                // var grand_total = parseFloat(totalFactura) + parseFloat(totalIva);
                var grand_total = parseFloat(numbertotalfactura) + parseFloat(numbertotal_iva_exento) + parseFloat(iva_percibido);
                

                var grand_totalformat = grand_total.toLocaleString('de-DE', {minimumFractionDigits: 2,maximumFractionDigits: 2});
                


                document.getElementById("grand_total").value = grand_totalformat;


                let inputAnticipo = document.getElementById("anticipo").value;  

                var montoFormat = inputAnticipo.replace(/[$.]/g,'');

                var montoFormat_anticipo = montoFormat.replace(/[,]/g,'.');

                if(inputAnticipo){
                    
                    document.getElementById("anticipo_form").value =  montoFormat_anticipo;
                }else{
                    document.getElementById("anticipo_form").value = 0;
                }


                var total_pay = parseFloat(totalFactura) + total_iva_exento - montoFormat_anticipo;

               
                //retencion de iva

                let porc_retencion_iva = "<?php echo $client->percentage_retencion_iva ?>";
                var calc_retencion_iva = total_iva_exento * porc_retencion_iva / 100;
                var total_retencion_iva = calc_retencion_iva.toLocaleString('de-DE', {minimumFractionDigits: 2,maximumFractionDigits: 2});
            
               // document.getElementById("iva_retencion").value =  total_retencion_iva;
                    
                document.getElementById("total_retiene_iva").value =  calc_retencion_iva;
                
                //-----------------------

                //retencion de islr
                    var total_islr_retencion = document.getElementById("total_retiene_islr").value;
                //------------------------------------

                var total_pay = total_pay - calc_retencion_iva - total_islr_retencion + parseFloat(iva_percibido);

                var total_payformat = total_pay.toLocaleString('de-DE', {minimumFractionDigits: 2,maximumFractionDigits: 2});
                
                document.getElementById("total_pay").value =  total_payformat;

                document.getElementById("total_pay_form").value =  total_pay.toFixed(2);

                document.getElementById("iva_form").value =  inputIva;

                document.getElementById("iva_amount_form").value = document.getElementById("iva_amount").value;
               
                document.getElementById("grandtotal_form").value = grand_totalformat;
                

                //Quiere decir que el monto total a pagar es negativo o igual a cero
                if(total_pay.toFixed(2) <= 0){
                    document.getElementById("amount_pay").required = false;
                    document.getElementById("payment_type").required = false;
                    $("#amount_pay").hide();
                    $("#payment_type").hide();
                    $("#btn_agregar").hide();
                    $("#label_amount_pays").hide();
                }
            }        
                
              
       
            $("#iva").on('change',function(){
                //calculate();


                let inputIva = document.getElementById("iva").value; 

                //let totalIva = (inputIva * "<?php echo $quotation->total_factura; ?>") / 100;  

                let totalFactura = "<?php echo $quotation->total_factura  / ($bcv ?? 1) ?>";       

                //AQUI VAMOS A SACAR EL MONTO DEL IVA DE LOS QUE ESTAN EXENTOS, PARA LUEGO RESTARSELO AL IVA TOTAL
                let totalBaseImponible = "<?php echo $quotation->base_imponible  / ($bcv ?? 1) ?>";

                let totalIvaMenos = (inputIva * "<?php echo $quotation->base_imponible  / ($bcv ?? 1) ; ?>") / 100;  


                /*Toma la Base y la envia por form*/
                let base_imponible_form = document.getElementById("base_imponible").value; 

                var montoFormat = base_imponible_form.replace(/[$.]/g,'');

                var montoFormat_base_imponible_form = montoFormat.replace(/[,]/g,'.');    

                document.getElementById("base_imponible_form").value =  montoFormat_base_imponible_form;
                /*-----------------------------------*/
                /*Toma la Base y la envia por form*/
                let sub_total_form = document.getElementById("total_factura").value; 

                var montoFormat = sub_total_form.replace(/[$.]/g,'');

                var montoFormat_sub_total_form = montoFormat.replace(/[,]/g,'.');    

                //document.getElementById("sub_total_form").value =  montoFormat_sub_total_form;
                /*-----------------------------------*/


                var total_iva_exento =  parseFloat(totalIvaMenos);

                var iva_format = total_iva_exento.toLocaleString('de-DE', {minimumFractionDigits: 2,maximumFractionDigits: 2});
               
                //document.getElementById("retencion").value = parseFloat(totalIvaMenos);
                //------------------------------



                document.getElementById("iva_amount").value = iva_format;


                // var grand_total = parseFloat(totalFactura) + parseFloat(totalIva);
                var grand_total = parseFloat(totalFactura) + parseFloat(total_iva_exento);

                var grand_totalformat = grand_total.toLocaleString('de-DE', {minimumFractionDigits: 2,maximumFractionDigits: 2});

                document.getElementById("grand_total").value = grand_totalformat;



                let inputAnticipo = document.getElementById("anticipo").value;  

                var montoFormat = inputAnticipo.replace(/[$.]/g,'');

                var montoFormat_anticipo = montoFormat.replace(/[,]/g,'.');

                if(inputAnticipo){
                    
                    document.getElementById("anticipo_form").value =  montoFormat_anticipo;
                }else{
                    document.getElementById("anticipo_form").value = 0;
                }        

                var total_pay = parseFloat(totalFactura) + total_iva_exento - montoFormat_anticipo;

                // var total_pay = parseFloat(totalFactura) + total_iva_exento - inputAnticipo;

                //retencion de iva
                
                let porc_retencion_iva = "<?php echo $client->percentage_retencion_iva ?>";
                var calc_retencion_iva = total_iva_exento * porc_retencion_iva / 100;
                var total_retencion_iva = calc_retencion_iva.toLocaleString('de-DE', {minimumFractionDigits: 2,maximumFractionDigits: 2});
            
                //document.getElementById("iva_retencion").value =  total_retencion_iva;
                    
                document.getElementById("total_retiene_iva").value =  calc_retencion_iva;
                
                //-----------------------

                //retencion de islr
                    var total_islr_retencion = document.getElementById("total_retiene_islr").value;
                //------------------------------------

                var total_pay = total_pay - calc_retencion_iva - total_islr_retencion;
                var total_payformat = total_pay.toLocaleString('de-DE', {minimumFractionDigits: 2,maximumFractionDigits: 2});

                document.getElementById("total_pay").value =  total_payformat;

                document.getElementById("total_pay_form").value =  total_pay.toFixed(2);

                document.getElementById("iva_form").value =  inputIva;
              
                document.getElementById("iva_amount_form").value = document.getElementById("iva_amount").value;

                document.getElementById("grandtotal_form").value = grand_totalformat;

                 //Quiere decir que el monto total a pagar es negativo o igual a cero
                 if(total_pay.toFixed(2) <= 0){
                    document.getElementById("amount_pay").required = false;
                    document.getElementById("payment_type").required = false;
                    $("#amount_pay").hide();
                    $("#payment_type").hide();
                    $("#btn_agregar").hide();
                    $("#label_amount_pays").hide();
                }
               
            });

            $("#anticipo").on('keyup',function(){
                //calculate();



                let inputIva = document.getElementById("iva").value; 

                //let totalIva = (inputIva * "<?php echo $quotation->total_factura; ?>") / 100;  

                let totalFactura = "<?php echo $quotation->total_factura ?>";       

                //AQUI VAMOS A SACAR EL MONTO DEL IVA DE LOS QUE ESTAN EXENTOS, PARA LUEGO RESTARSELO AL IVA TOTAL
                let totalBaseImponible = "<?php echo $quotation->base_imponible ?>";

                let totalIvaMenos = (inputIva * "<?php echo $quotation->base_imponible; ?>") / 100;  


                /*Toma la Base y la envia por form*/
                let base_imponible_form = document.getElementById("base_imponible").value; 

                var montoFormat = base_imponible_form.replace(/[$.]/g,'');

                var montoFormat_base_imponible_form = montoFormat.replace(/[,]/g,'.');    

                document.getElementById("base_imponible_form").value =  montoFormat_base_imponible_form;
                /*-----------------------------------*/
                /*Toma la Base y la envia por form*/
                let sub_total_form = document.getElementById("total_factura").value; 

                var montoFormat = sub_total_form.replace(/[$.]/g,'');

                var montoFormat_sub_total_form = montoFormat.replace(/[,]/g,'.');    

                //document.getElementById("sub_total_form").value =  montoFormat_sub_total_form;
                /*-----------------------------------*/





                var total_iva_exento =  parseFloat(totalIvaMenos);

                var iva_format = total_iva_exento.toLocaleString('de-DE', {minimumFractionDigits: 2,maximumFractionDigits: 2});

                //document.getElementById("retencion").value = parseFloat(totalIvaMenos);
                //------------------------------



                document.getElementById("iva_amount").value = iva_format;


                // var grand_total = parseFloat(totalFactura) + parseFloat(totalIva);
                var grand_total = parseFloat(totalFactura) + parseFloat(total_iva_exento);

                var grand_totalformat = grand_total.toLocaleString('de-DE', {minimumFractionDigits: 2,maximumFractionDigits: 2});


                document.getElementById("grand_total").value = grand_totalformat;



                let inputAnticipo = document.getElementById("anticipo").value;  

                var montoFormat = inputAnticipo.replace(/[$.]/g,'');

                var montoFormat_anticipo = montoFormat.replace(/[,]/g,'.');

                if(inputAnticipo){
                    
                    document.getElementById("anticipo_form").value =  montoFormat_anticipo;
                }else{
                    document.getElementById("anticipo_form").value = 0;
                }


                var total_pay = parseFloat(totalFactura) + total_iva_exento - montoFormat_anticipo;

               //retencion de iva
                
               let porc_retencion_iva = "<?php echo $client->percentage_retencion_iva ?>";
                var calc_retencion_iva = total_iva_exento * porc_retencion_iva / 100;
                var total_retencion_iva = calc_retencion_iva.toLocaleString('de-DE', {minimumFractionDigits: 2,maximumFractionDigits: 2});
            
               // document.getElementById("iva_retencion").value =  total_retencion_iva;
                    
                document.getElementById("total_retiene_iva").value =  calc_retencion_iva;
                
                //-----------------------

                //retencion de islr
                    var total_islr_retencion = document.getElementById("total_retiene_islr").value;
                //------------------------------------

                var total_pay = total_pay - calc_retencion_iva - total_islr_retencion;

                var total_payformat = total_pay.toLocaleString('de-DE', {minimumFractionDigits: 2,maximumFractionDigits: 2});

                document.getElementById("total_pay").value =  total_payformat;

                document.getElementById("total_pay_form").value =  total_pay.toFixed(2);

                document.getElementById("iva_form").value =  inputIva;

                document.getElementById("iva_amount_form").value = document.getElementById("iva_amount").value;
               
                document.getElementById("grandtotal_form").value = grand_totalformat;

                 //Quiere decir que el monto total a pagar es negativo o igual a cero
                 if(total_pay.toFixed(2) <= 0){
                    document.getElementById("amount_pay").required = false;
                    document.getElementById("payment_type").required = false;
                    $("#amount_pay").hide();
                    $("#payment_type").hide();
                    $("#btn_agregar").hide();
                    $("#label_amount_pays").hide();
                }
                
            });

       
    </script>
@endsection
