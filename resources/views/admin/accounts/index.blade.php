@extends('admin.layouts.dashboard')

@section('content')

<!-- container-fluid -->
<div class="container-fluid">

    <div class="row py-lg-2">
        <div class="col-sm-2" style="background: rgb(146, 23, 146); color: white;">
                Totales Generales:
        </div>
        <div class="col-sm-2" style="background: rgb(216, 216, 216); color: rgb(0, 0, 0);">
            Saldo Anterior: {{ number_format($total_saldo_anterior ?? 0, 2, ',', '.')}}
        </div>
        <div class="col-sm-3" style="background: rgb(216, 216, 216); color: rgb(0, 0, 0);">
            Débitos: {{ number_format($total_debe ?? 0, 2, ',', '.')}}
        </div>
        <div class="col-sm-3" style="background: rgb(216, 216, 216); color: rgb(0, 0, 0);">
            Créditos: {{ number_format($total_haber ?? 0, 2, ',', '.')}}
        </div>
        <div class="col-sm-2" style="background: rgb(216, 216, 216); color: rgb(0, 0, 0);">
            Saldo Actual: {{ number_format(($total_saldo_anterior ?? 0) + ($total_debe ?? 0) - ($total_haber), 2, ',', '.')}}
        </div>
    </div>
    <!-- Page Heading -->
    <div class="row py-lg-2">
        
        <div class="col-sm-2  dropdown mb-4">
            <button class="btn btn-light2 text-dark " type="button"
                id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="false"
                aria-expanded="false">
                <i class="fas fa-bars"></i>
                Opciones
                
            </button>
            <div class="dropdown-menu animated--fade-in"
                aria-labelledby="dropdownMenuButton">
                <a class="dropdown-item" href="{{ route('accounts.index_previous_exercise') }}">Ver Ejercicios Anteriores</a>
            </div>
        </div> 
       
            <div class="col-sm-3">
                <a href="#" class="btn btn-light2" data-toggle="modal" data-target="#cierreModal">
                    <i class="fas fa-times" ></i>
                    Cierre de Ejercicio
                    
                </a>
            </div>
            
            <div class="col-sm-3">
                <a href="{{ route('accounts.create')}}" class="btn btn-light2" role="button" aria-pressed="true">
                    
                    <i class="fas fa-pencil-alt" ></i>Registrar una Cuenta
                    
                </a>
            </div>
            <div class="col-sm-2">
                <select class="form-control" name="coin" id="coin">
                    @if(isset($coin))
                        <option disabled selected value="{{ $coin }}">{{ $coin }}</option>
                        <option disabled  value="{{ $coin }}">-----------</option>
                    @else
                        <option disabled selected value="bolivares">Moneda</option>
                    @endif
                    
                    <option  value="bolivares">Bolívares</option>
                    <option value="dolares">Dólares</option>
                </select>
            </div>
            <div class="col-sm-2">
                <select class="form-control" name="level" id="level">
                    @if(isset($level))
                        <option disabled selected value="{{ $level }}">Nivel {{ $level }}</option>
                        <option disabled  value="{{ $level }}">-----------</option>
                    @else
                        <option disabled selected value="5">Niveles</option>
                    @endif
                    
                    <option  value="1">Nivel 1</option>
                    <option value="2">Nivel 2</option>
                    <option  value="3">Nivel 3</option>
                    <option  value="4">Nivel 4</option>
                    <option value="5">Todos</option>
                </select>
            </div>
        
    </div>
  </div>
  <!-- /.container-fluid -->
  {{-- VALIDACIONES-RESPUESTA--}}
  @include('admin.layouts.success')   {{-- SAVE --}}
  @include('admin.layouts.danger')    {{-- EDITAR --}}
  @include('admin.layouts.delete')    {{-- DELELTE --}}
  {{-- VALIDACIONES-RESPUESTA --}}
<!-- DataTales Example -->

<div class="card shadow mb-4">
   
    <div class="card-body">
        <div class="container">
            @if (session('flash'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{session('flash')}}
                <button type="button" class="close" data-dismiss="alert" aria-label="close">
                    <span aria-hidden="true">&times; </span>
                </button>
            </div>   
        @endif
        </div>
        <div class="table-responsive">
        <table class="table table-light2 table-bordered" id="dataTable" width="100%" cellspacing="0">
            <thead>
            <tr > 
               
                <th style="text-align: right;">Código</th>
                <th style="text-align: right;">Descripción</th>
                <th style="text-align: right;">Nivel</th>
                <th style="text-align: right;">Tipo</th>
                
                <th style="text-align: right;">Saldo Anterior</th>
                <th style="text-align: right;">Debe</th>
                <th style="text-align: right;">Haber</th>
                <th style="text-align: right;">Saldo Actual</th>
               
                <th style="text-align: right;"></th>
            </tr>
            </thead>
            
            <tbody>
                @if (empty($accounts))
                @else  
                    @foreach ($accounts as $account)
                    @if(isset($level))
                        @if($level >= $account->level)
                        <tr>
                            <td style="text-align:left; color:black; font-weight: bold;">{{$account->code_one}}{{ ($account->code_two == 0) ? '' : '.'.$account->code_two }}{{ ($account->code_three == 0) ? '' : '.'.$account->code_three }}{{ ($account->code_four == 0) ? '' : '.'.$account->code_four }}{{ ($account->code_five == 0) ? '' : '.'.str_pad($account->code_five, 3, "0", STR_PAD_LEFT) }}</td>
                            <td style="text-align:right; color:black;">
                                @if(isset($account->coin))
                                    <a href="{{ route('accounts.edit',$account->id) }}" style="color: black; font-weight: bold;" title="Ver Movimientos">{{$account->description}} ({{ $account->coin }})</a>
                                @else
                                    <a href="{{ route('accounts.edit',$account->id) }}" style="color: black; font-weight: bold;" title="Ver Movimientos">{{$account->description}}</a>
                               @endif
                            </td>
                            <td style="text-align:right; color:black; ">{{$account->level}}</td>
                            <td style="text-align:right; color:black; ">{{$account->type}}</td>

                            <?php 
                                $balance_new = $account->balance;
                                if(isset($account->coin)){
                                    $balance_new = $account->balance / ($account->rate ?? 1);
                                
                                }
                                if($coin != 'bolivares'){
                                    //si la moneda seleccionada fue dolares, convertimos los balances de bs a dolares segun su tasa
                                    if(($account->balance != 0) && ($account->rate != 0)){
                                        $balance_new = $account->balance / $account->rate;
                                    }
                                }
                            ?>
                               
                            <!-- Cuando el status de la cuenta es M, quiere decir que tiene movimientos-->
                            @if ($account->status == "M")
                                @if((isset($account->coin)) && ($account->coin != "Bs"))
                                    @if($coin != "bolivares")
                                        <!-- Cuando quiero ver mis saldos todos en dolares-->
                                        <td style="text-align:right; color:black; font-weight: bold;">{{number_format($balance_new, 2, ',', '.')}}</td>
                                      
                                        <td style="text-align:right; color:black; font-weight: bold;">
                                            <a href="{{ route('accounts.movements',$account->id) }}" style="color: black; font-weight: bold;" title="Ver Movimientos">{{number_format($account->debe, 2, ',', '.')}}</a>                      
                                        </td>
                                        <td style="text-align:right; color:black; ">
                                            <a href="{{ route('accounts.movements',$account->id) }}" style="color: black; font-weight: bold;" title="Ver Movimientos">{{number_format($account->haber, 2, ',', '.')}}</a>
                                        </td>
                                        <!-- solo se suma el balance general si son codigo 1 hasta el 3 -->
                                        @if ($account->code_one <= 3)
                                            <td style="text-align:right; color:black; font-weight: bold;">{{number_format($balance_new + $account->debe - $account->haber, 2, ',', '.')}}</td>             
                                        @else
                                            <td style="text-align:right; color:black; font-weight: bold;">{{number_format($account->debe - $account->haber, 2, ',', '.')}}</td>                                                       
                                        @endif
                                    @else
                                        <td style="text-align:right; color:black; font-weight: bold;">{{number_format($account->balance, 2, ',', '.')}}<br>{{number_format($balance_new, 2, ',', '.')}} $</td>
                                        <!-- Cuando quiero ver mis saldos todos en bolivares y mi cuenta es en dolares-->
                                        <td style="text-align:right; color:black; font-weight: bold;">
                                            <a href="{{ route('accounts.movements',[$account->id,$coin]) }}" style="color: black; font-weight: bold;" title="Ver Movimientos">{{number_format($account->debe, 2, ',', '.')}} <br> {{number_format($account->dolar_debe ?? 0, 2, ',', '.')}}$</a>                      
                                        </td>
                                        <td style="text-align:right; color:black; ">
                                            <a href="{{ route('accounts.movements',[$account->id,$coin]) }}" style="color: black; font-weight: bold;" title="Ver Movimientos">{{number_format($account->haber, 2, ',', '.')}}<br> {{number_format($account->dolar_haber ?? 0, 2, ',', '.')}}$</a>
                                        </td>
                                        <!-- solo se suma el balance general si son codigo 1 hasta el 3 -->
                                        @if ($account->code_one <= 3)
                                            <td style="text-align:right; color:black; font-weight: bold;">{{number_format($account->balance + $account->debe - $account->haber, 2, ',', '.')}}</td>             
                                        @else
                                            <td style="text-align:right; color:black; font-weight: bold;">{{number_format($account->debe - $account->haber, 2, ',', '.')}}</td>                                                       
                                        @endif
                                    @endif
                                @else
                                    @if($coin != "bolivares")
                                        <td style="text-align:right; color:black; font-weight: bold;">{{number_format($balance_new, 2, ',', '.')}}</td>
                                        <!-- Cuando quiero ver mis saldos todos en bolivares y mi cuenta es en bolivares-->
                                        <td style="text-align:right; color:black; font-weight: bold;">
                                            <a href="{{ route('accounts.movements',[$account->id,$coin]) }}" style="color: black; font-weight: bold;" title="Ver Movimientos"> {{number_format($account->debe, 2, ',', '.')}}</a>                     
                                        </td>
                                        <td style="text-align:right; color:black; ">
                                            <a href="{{ route('accounts.movements',[$account->id,$coin]) }}" style="color: black; font-weight: bold;" title="Ver Movimientos"> {{number_format($account->haber, 2, ',', '.')}}</a>
                                        </td>
                                        <!-- solo se suma el balance general si son codigo 1 hasta el 3 -->
                                        @if ($account->code_one <= 3)
                                            <td style="text-align:right; color:black; font-weight: bold;">{{number_format($balance_new + $account->debe - $account->haber, 2, ',', '.')}}</td>             
                                        @else
                                            <td style="text-align:right; color:black; font-weight: bold;">{{number_format($account->debe - $account->haber, 2, ',', '.')}}</td>                                                       
                                        @endif
                                    @else
                                        <td style="text-align:right; color:black; font-weight: bold;">{{number_format($balance_new, 2, ',', '.')}}</td>
                                        <!-- Cuando quiero ver mis saldos todos en bolivares y mi cuenta es en bolivares-->
                                        <td style="text-align:right; color:black; font-weight: bold;">
                                            <a href="{{ route('accounts.movements',[$account->id,$coin]) }}" style="color: black; font-weight: bold;" title="Ver Movimientos">  {{number_format($account->debe, 2, ',', '.')}}</a>                    
                                        </td>
                                        <td style="text-align:right; color:black; ">
                                            <a href="{{ route('accounts.movements',[$account->id,$coin]) }}" style="color: black; font-weight: bold;" title="Ver Movimientos"> {{number_format($account->haber, 2, ',', '.')}}</a>
                                        </td>
                                        <!-- solo se suma el balance general si son codigo 1 hasta el 3 -->
                                        @if ($account->code_one <= 3)
                                            <td style="text-align:right; color:black; font-weight: bold;">{{number_format($balance_new + $account->debe - $account->haber, 2, ',', '.')}}</td>             
                                        @else
                                            <td style="text-align:right; color:black; font-weight: bold;">{{number_format($account->debe - $account->haber, 2, ',', '.')}}</td>                                                       
                                        @endif
                                    @endif
                                @endif
                            <!-- Cuando el status de la cuenta es 1, quiere decir que NO tiene movimientos-->
                            @else
                                @if($account->coin == "$")
                                    @if($coin != "bolivares")
                                        <td style="text-align:right; color:black; font-weight: bold;">{{number_format($balance_new, 2, ',', '.')}}</td>
                                        <!-- Cuando quiero ver mis saldos todos en dolares-->
                                        <td style="text-align:right; color:black; font-weight: bold;">{{number_format($account->debe, 2, ',', '.')}}</td>
                                        <td style="text-align:right; color:black; ">{{number_format($account->haber, 2, ',', '.')}}</td>
                                        <!-- solo se suma el balance general si son codigo 1 hasta el 3 -->
                                        @if ($account->code_one <= 3)
                                            <td style="text-align:right; color:black; font-weight: bold;">{{number_format($balance_new + $account->debe - $account->haber, 2, ',', '.')}}</td>             
                                        @else
                                            <td style="text-align:right; color:black; font-weight: bold;">{{number_format($account->debe - $account->haber, 2, ',', '.')}}</td>                                                       
                                        @endif
                                    @else
                                        <td style="text-align:right; color:black; font-weight: bold;">{{number_format($account->balance, 2, ',', '.')}}<br>{{number_format($balance_new, 2, ',', '.')}} $</td>
                                        <!-- Cuando quiero ver mis saldos todos en bolivares y mi cuenta es en dolares-->
                                        <td style="text-align:right; color:black; font-weight: bold;">{{number_format($account->debe, 2, ',', '.')}} <br> {{number_format($account->dolar_debe ?? 0, 2, ',', '.')}}$</td>
                                        <td style="text-align:right; color:black; ">{{number_format($account->haber, 2, ',', '.')}}<br> {{number_format($account->dolar_haber ?? 0, 2, ',', '.')}}$</td>
                                        <!-- solo se suma el balance general si son codigo 1 hasta el 3 -->
                                        @if ($account->code_one <= 3)
                                            <td style="text-align:right; color:black; font-weight: bold;">{{number_format($account->balance + $account->debe - $account->haber, 2, ',', '.')}} <br> {{number_format($balance_new + $account->dolar_debe - $account->dolar_haber, 2, ',', '.')}}$</td>             
                                        @else
                                            <td style="text-align:right; color:black; font-weight: bold;">{{number_format($account->debe - $account->haber, 2, ',', '.')}} <br> {{number_format($account->dolar_debe - $account->dolar_haber, 2, ',', '.')}}$</td>                                                       
                                        @endif
                                    @endif
                                @else
                                    @if($coin != "bolivares")
                                        <td style="text-align:right; color:black; font-weight: bold;">{{number_format($balance_new, 2, ',', '.')}}</td>
                                        <!-- Cuando quiero ver mis saldos todos en bolivares y mi cuenta es en bolivares-->
                                        <td style="text-align:right; color:black; font-weight: bold;">
                                            {{number_format($account->debe, 2, ',', '.')}}                      
                                        </td>
                                        <td style="text-align:right; color:black; ">
                                            {{number_format($account->haber, 2, ',', '.')}}
                                        </td>
                                        @if ($account->code_one <= 3)
                                            <td style="text-align:right; color:black; font-weight: bold;">{{number_format($balance_new + $account->debe - $account->haber, 2, ',', '.')}}</td>             
                                        @else
                                            <td style="text-align:right; color:black; font-weight: bold;">{{number_format($account->debe - $account->haber, 2, ',', '.')}}</td>                                                       
                                        @endif
                                    @else
                                        <td style="text-align:right; color:black; font-weight: bold;">{{number_format($balance_new, 2, ',', '.')}}</td>
                                        <!-- Cuando quiero ver mis saldos todos en bolivares y mi cuenta es en bolivares-->
                                        <td style="text-align:right; color:black; font-weight: bold;">
                                            {{number_format($account->debe, 2, ',', '.')}}                      
                                        </td>
                                        <td style="text-align:right; color:black; ">
                                            {{number_format($account->haber, 2, ',', '.')}}
                                        </td>
                                        @if ($account->code_one <= 3)
                                            <td style="text-align:right; color:black; font-weight: bold;">{{number_format($balance_new + $account->debe - $account->haber, 2, ',', '.')}}</td>             
                                        @else
                                            <td style="text-align:right; color:black; font-weight: bold;">{{number_format($account->debe - $account->haber, 2, ',', '.')}}</td>                                                       
                                        @endif
                                    @endif
                                @endif
                            @endif
                            <td style="text-align:right; color:black; ">  
                                @if($account->code_five == 0)
                                    <a href="{{ route('accounts.createlevel',$account->id) }}" title="Crear"><i class="fa fa-plus" style="color: orangered"></i></a>
                                @endif
                            </td>
                        </tr>   
                        @endif




                    @else
                    <tr>
                        <td style="text-align:left; color:black; font-weight: bold;">{{$account->code_one}}{{ ($account->code_two == 0) ? '' : '.'.$account->code_two }}{{ ($account->code_three == 0) ? '' : '.'.$account->code_three }}{{ ($account->code_four == 0) ? '' : '.'.$account->code_four }}{{ ($account->code_five == 0) ? '' : '.'.str_pad($account->code_five, 3, "0", STR_PAD_LEFT) }}</td>
                        <td style="text-align:right; color:black;">
                            @if(isset($account->coin))
                                <a href="{{ route('accounts.edit',$account->id) }}" style="color: black; font-weight: bold;" title="Ver Movimientos">{{$account->description}} ({{ $account->coin }})</a>
                            @else
                                <a href="{{ route('accounts.edit',$account->id) }}" style="color: black; font-weight: bold;" title="Ver Movimientos">{{$account->description}}</a>
                           @endif
                        </td>
                        <td style="text-align:right; color:black; ">{{$account->level}}</td>
                        <td style="text-align:right; color:black; ">{{$account->type}}</td>

                        <?php 
                            $balance_new = null;
                            if(isset($account->coin)){
                                $balance_new = $account->balance / ($account->rate ?? 1);
                            
                            }else if($coin != 'bolivares'){
                                //si la moneda seleccionada fue dolares, convertimos los balances de bs a dolares segun su tasa
                                if(($account->balance != 0) && ($account->rate != 0)){
                                    $balance_new = $account->balance / $account->rate;
                                }
                            }
                        ?>
                           
                        <!-- Cuando el status de la cuenta es M, quiere decir que tiene movimientos-->
                        @if ($account->status == "M")
                            @if((isset($account->coin)) && ($account->coin != "Bs"))
                                @if($coin != "bolivares")
                                    <td style="text-align:right; color:black; font-weight: bold;">{{number_format($balance_new ?? $account->balance, 2, ',', '.')}}</td>
                                    <!-- Cuando quiero ver mis saldos todos en dolares-->
                                    <td style="text-align:right; color:black; font-weight: bold;">
                                        <a href="{{ route('accounts.movements',[$account->id,$coin]) }}" style="color: black; font-weight: bold;" title="Ver Movimientos">{{number_format($account->debe, 2, ',', '.')}}</a>                      
                                    </td>
                                    <td style="text-align:right; color:black; ">
                                        <a href="{{ route('accounts.movements',[$account->id,$coin]) }}" style="color: black; font-weight: bold;" title="Ver Movimientos">{{number_format($account->haber, 2, ',', '.')}}</a>
                                    </td>
                                    @if ($account->code_one <= 3)
                                        <td style="text-align:right; color:black; font-weight: bold;">{{number_format($balance_new + $account->debe - $account->haber, 2, ',', '.')}}</td>             
                                    @else
                                        <td style="text-align:right; color:black; font-weight: bold;">{{number_format($account->debe - $account->haber, 2, ',', '.')}}</td>                                                       
                                    @endif
                                @else
                                    <td style="text-align:right; color:black; font-weight: bold;"> {{number_format($account->balance, 2, ',', '.')}}<br>{{number_format($balance_new ?? $account->balance, 2, ',', '.')}}$</td>
                                    <!-- Cuando quiero ver mis saldos todos en bolivares y mi cuenta es en dolares-->
                                    <td style="text-align:right; color:black; font-weight: bold;">
                                        <a href="{{ route('accounts.movements',[$account->id,$coin]) }}" style="color: black; font-weight: bold;" title="Ver Movimientos">{{number_format($account->debe, 2, ',', '.')}} <br> {{number_format($account->dolar_debe ?? 0, 2, ',', '.')}}$</a>                      
                                    </td>
                                    <td style="text-align:right; color:black; ">
                                        <a href="{{ route('accounts.movements',[$account->id,$coin]) }}" style="color: black; font-weight: bold;" title="Ver Movimientos">{{number_format($account->haber, 2, ',', '.')}}<br> {{number_format($account->dolar_haber ?? 0, 2, ',', '.')}}$</a>
                                    </td>
                                    @if ($account->code_one <= 3)
                                        <td style="text-align:right; color:black; font-weight: bold;">{{number_format($account->balance + $account->debe - $account->haber, 2, ',', '.')}} <br> {{number_format($balance_new + $account->dolar_debe - $account->dolar_haber, 2, ',', '.')}}$</td>             
                                    @else
                                        <td style="text-align:right; color:black; font-weight: bold;">{{number_format($account->debe - $account->haber, 2, ',', '.')}} <br> {{number_format($account->dolar_debe - $account->dolar_haber, 2, ',', '.')}}$</td>                                                       
                                    @endif
                                @endif
                            <!-- Cuando la cuenta tiene movimientos-->
                            @else
                                @if($coin != "bolivares")
                                    <td style="text-align:right; color:black; font-weight: bold;">{{number_format($balance_new ?? $account->balance, 2, ',', '.')}}</td>
                                    <!-- Cuando quiero ver mis saldos todos en dolares-->
                                    <td style="text-align:right; color:black; font-weight: bold;">
                                        <a href="{{ route('accounts.movements',[$account->id,$coin]) }}" style="color: black; font-weight: bold;" title="Ver Movimientos">{{number_format($account->debe, 2, ',', '.')}}</a>                      
                                    </td>
                                    <td style="text-align:right; color:black; ">
                                        <a href="{{ route('accounts.movements',[$account->id,$coin]) }}" style="color: black; font-weight: bold;" title="Ver Movimientos">{{number_format($account->haber, 2, ',', '.')}}</a>
                                    </td>
                                    @if ($account->code_one <= 3)
                                        <td style="text-align:right; color:black; font-weight: bold;">{{number_format($balance_new + $account->dolar_debe - $account->dolar_haber, 2, ',', '.')}}</td>             
                                    @else
                                        <td style="text-align:right; color:black; font-weight: bold;">{{number_format($account->dolar_debe - $account->dolar_haber, 2, ',', '.')}}</td>                                                       
                                    @endif
                                @else
                                    <td style="text-align:right; color:black; font-weight: bold;"> {{number_format($account->balance, 2, ',', '.')}}</td>
                                    <!-- Cuando quiero ver mis saldos todos en bolivares y mi cuenta es en dolares-->
                                    <td style="text-align:right; color:black; font-weight: bold;">
                                        <a href="{{ route('accounts.movements',[$account->id,$coin]) }}" style="color: black; font-weight: bold;" title="Ver Movimientos">{{number_format($account->debe, 2, ',', '.')}}</a>                      
                                    </td>
                                    <td style="text-align:right; color:black; ">
                                        <a href="{{ route('accounts.movements',[$account->id,$coin]) }}" style="color: black; font-weight: bold;" title="Ver Movimientos">{{number_format($account->haber, 2, ',', '.')}}</a>
                                    </td>
                                    @if ($account->code_one <= 3)
                                        <td style="text-align:right; color:black; font-weight: bold;">{{number_format($account->balance + $account->debe - $account->haber, 2, ',', '.')}}</td>             
                                    @else
                                        <td style="text-align:right; color:black; font-weight: bold;">{{number_format($account->debe - $account->haber, 2, ',', '.')}}</td>                                                       
                                    @endif
                                @endif
                            @endif
                        @else
                            @if((isset($account->coin)) && ($account->coin != "Bs"))
                                @if($coin != "bolivares")
                                    <td style="text-align:right; color:black; font-weight: bold;">{{number_format($balance_new ?? $account->balance, 2, ',', '.')}}</td>
                                    <!-- Cuando quiero ver mis saldos todos en dolares-->
                                    <td style="text-align:right; color:black; font-weight: bold;">
                                        {{number_format($account->debe, 2, ',', '.')}}                     
                                    </td>
                                    <td style="text-align:right; color:black; ">
                                        {{number_format($account->haber, 2, ',', '.')}}
                                    </td>
                                    @if ($account->code_one <= 3)
                                        <td style="text-align:right; color:black; font-weight: bold;">{{number_format($account->balance + $account->debe - $account->haber, 2, ',', '.')}}</td>             
                                    @else
                                        <td style="text-align:right; color:black; font-weight: bold;">{{number_format($account->debe - $account->haber, 2, ',', '.')}}</td>                                                       
                                    @endif
                                @else
                                    <td style="text-align:right; color:black; font-weight: bold;"> {{number_format($account->balance, 2, ',', '.')}}<br>{{number_format($balance_new ?? $account->balance, 2, ',', '.')}}$</td>
                                    <!-- Cuando quiero ver mis saldos todos en bolivares y mi cuenta es en dolares-->
                                    <td style="text-align:right; color:black; font-weight: bold;">
                                        {{number_format($account->debe, 2, ',', '.')}} <br> {{number_format($account->dolar_debe ?? 0, 2, ',', '.')}}$                      
                                    </td>
                                    <td style="text-align:right; color:black; ">
                                        {{number_format($account->haber, 2, ',', '.')}}<br> {{number_format($account->dolar_haber ?? 0, 2, ',', '.')}}$
                                    </td>
                                    @if ($account->code_one <= 3)
                                        <td style="text-align:right; color:black; font-weight: bold;">{{number_format($account->balance + $account->debe - $account->haber, 2, ',', '.')}} <br>{{number_format($balance_new + $account->dolar_debe - $account->dolar_haber, 2, ',', '.')}}$</td>             
                                    @else
                                        <td style="text-align:right; color:black; font-weight: bold;">{{number_format($account->debe - $account->haber, 2, ',', '.')}} <br>{{number_format($account->dolar_debe - $account->dolar_haber, 2, ',', '.')}}$</td>                                                       
                                    @endif
                                @endif
                            @else
                                <!-- Sin movimientos , cuenta en bolivares y la moneda seleccionada fue ver en bolivares-->
                                @if($coin != "bolivares")
                                    <td style="text-align:right; color:black; font-weight: bold;"> {{number_format($account->balance, 2, ',', '.')}}</td>                  
                                    <td style="text-align:right; color:black; font-weight: bold;">
                                        {{number_format($account->debe, 2, ',', '.')}}                     
                                    </td>
                                    <td style="text-align:right; color:black; ">
                                        {{number_format($account->haber, 2, ',', '.')}}
                                    </td>
                                    @if ($account->code_one <= 3)
                                        <td style="text-align:right; color:black; font-weight: bold;">{{number_format($account->balance + $account->debe - $account->haber, 2, ',', '.')}}</td>             
                                    @else
                                        <td style="text-align:right; color:black; font-weight: bold;">{{number_format($account->debe - $account->haber, 2, ',', '.')}}</td>                                                       
                                    @endif
                                @else 
                                    <td style="text-align:right; color:black; font-weight: bold;"> {{number_format($account->balance, 2, ',', '.')}}</td>                                 
                                    <td style="text-align:right; color:black; font-weight: bold;">
                                        {{number_format($account->debe, 2, ',', '.')}}                     
                                    </td>
                                    <td style="text-align:right; color:black; ">
                                        {{number_format($account->haber, 2, ',', '.')}}
                                    </td>
                                    @if ($account->code_one <= 3)
                                        <td style="text-align:right; color:black; font-weight: bold;">{{number_format($account->balance + $account->debe - $account->haber, 2, ',', '.')}}</td>             
                                    @else
                                        <td style="text-align:right; color:black; font-weight: bold;">{{number_format($account->debe - $account->haber, 2, ',', '.')}}</td>                                                       
                                    @endif
                                @endif
                            @endif
                        @endif
                        <td style="text-align:right; color:black; ">  
                            @if($account->code_five == 0)
                                <a href="{{ route('accounts.createlevel',$account->id) }}" title="Crear"><i class="fa fa-plus" style="color: orangered"></i></a>
                            @endif
                        </td>
                           
                    </tr>   
                    
                    @endif
                    @endforeach   
                @endif
            </tbody>
        </table>
        </div>
    </div>
</div>
   <!-- Logout Modal-->
   <div class="modal fade" id="cierreModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
   aria-hidden="true">
   <div class="modal-dialog" role="document">
       <div class="modal-content">
           <div class="modal-header">
               <h5 class="modal-title" id="exampleModalLabel">Seguro que desea realizar el Cierre del Ejercicio?</h5>
               <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                   <span aria-hidden="true">×</span>
               </button>
           </div>
           <div class="modal-body">Seleccione "Cerrar Ejercicio" si desea archivar sus movimientos de cuentas</div>
           <div class="modal-footer">
               <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
               <a class="btn btn-primary" href="{{ route('accounts.year_end') }}" >
               Cerrar Ejercicio
              </a>
           </div>
       </div>
   </div>
</div>
@endsection

@section('javascript')

    <script>
    $('#dataTable').DataTable({
        "ordering": false,
        "order": [],
        'aLengthMenu': [[-1, 50, 100, 150, 200], ["Todo",50, 100, 150, 200]]
    });

    $("#coin").on('change',function(){
        var coin = $(this).val();
        var level = document.getElementById("level").value; 
        window.location = "{{route('accounts', ['',''])}}"+"/"+coin+"/"+level;
    });
    $("#level").on('change',function(){
        var level = $(this).val();
        var coin = document.getElementById("coin").value; 
        window.location = "{{route('accounts', ['',''])}}"+"/"+coin+"/"+level;
    });

        $("body").toggleClass("sidebar-toggled");
        $(".sidebar").toggleClass("toggled");
        if ($(".sidebar").hasClass("toggled")) {
            $('.sidebar .collapse').collapse('hide');
        };
    
    </script> 

@endsection