@extends('admin.layouts.dashboard')

@section('content')
<div class="container-fluid">
    <div class="row py-lg-2">
       
        <div class="col-sm-6">
            <h2>Seleccione una Cuenta</h2>
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
    
    </div>
</div>
  <!-- /.container-fluid -->
  {{-- VALIDACIONES-RESPUESTA--}}
  @include('admin.layouts.success')   {{-- SAVE --}}
  @include('admin.layouts.danger')    {{-- EDITAR --}}
  @include('admin.layouts.delete')    {{-- DELELTE --}}
  {{-- VALIDACIONES-RESPUESTA --}}
<!-- DataTales Example -->
<!-- container-fluid -->
<div class="container-fluid">
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
                    <th style="text-align: right;"></th>
                    <th style="text-align: right;">Código</th>
                    <th style="text-align: right;">Descripción</th>
                    <th style="text-align: right;">Nivel</th>
                    <th style="text-align: right;">Tipo</th>
                    
                    <th style="text-align: right;">Saldo Anterior</th>
                    <th style="text-align: right;">Debe</th>
                    <th style="text-align: right;">Haber</th>
                    <th style="text-align: right;">Saldo Actual</th>
                   
                    
                </tr>
                </thead>
                
                <tbody>
                    @if (empty($accounts))
                    @else  
                        @foreach ($accounts as $account)
                        @if(isset($level))
                            @if($level >= $account->level)
                            <tr>
                                <td style="text-align:right; color:black; ">  
                                    @if (isset($id_detail))
                                        <a href="{{ route('detailvouchers.edit',[$coin,$id_detail,$account->id]) }}" title="Seleccionar"><i class="fa fa-check"></i></a>
                                    @else
                                        <a href="{{ route('detailvouchers.create',[$coin,$header->id,$account->id]) }}" title="Seleccionar"><i class="fa fa-check"></i></a>
                                    @endif
                                </td>
                                <td style="text-align:right; color:black; font-weight: bold;">{{$account->code_one}}.{{$account->code_two}}.{{$account->code_three}}.{{$account->code_four}}.{{ str_pad($account->code_five, 3, "0", STR_PAD_LEFT)}}</td>
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
                                            
                                            <td style="text-align:right; color:black; font-weight: bold;">{{number_format($balance_new + $account->debe - $account->haber, 2, ',', '.')}}</td>
                                        @else
                                            <td style="text-align:right; color:black; font-weight: bold;">{{number_format($account->balance, 2, ',', '.')}}<br>{{number_format($balance_new, 2, ',', '.')}} $</td>
                                            <!-- Cuando quiero ver mis saldos todos en bolivares y mi cuenta es en dolares-->
                                            <td style="text-align:right; color:black; font-weight: bold;">
                                                <a href="{{ route('accounts.movements',[$account->id,$coin]) }}" style="color: black; font-weight: bold;" title="Ver Movimientos">{{number_format($account->debe, 2, ',', '.')}} <br> {{number_format($account->dolar_debe ?? 0, 2, ',', '.')}}$</a>                      
                                            </td>
                                            <td style="text-align:right; color:black; ">
                                                <a href="{{ route('accounts.movements',[$account->id,$coin]) }}" style="color: black; font-weight: bold;" title="Ver Movimientos">{{number_format($account->haber, 2, ',', '.')}}<br> {{number_format($account->dolar_haber ?? 0, 2, ',', '.')}}$</a>
                                            </td>
                                            
                                            <td style="text-align:right; color:black; font-weight: bold;">{{number_format($account->balance + $account->debe - $account->haber, 2, ',', '.')}} <br> {{number_format($balance_new + $account->dolar_debe - $account->dolar_haber, 2, ',', '.')}}$</td>
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
                                            <td style="text-align:right; color:black; font-weight: bold;">{{number_format($balance_new + $account->debe - $account->haber, 2, ',', '.')}}</td>
                                        @else
                                            <td style="text-align:right; color:black; font-weight: bold;">{{number_format($balance_new, 2, ',', '.')}}</td>
                                            <!-- Cuando quiero ver mis saldos todos en bolivares y mi cuenta es en bolivares-->
                                            <td style="text-align:right; color:black; font-weight: bold;">
                                                <a href="{{ route('accounts.movements',[$account->id,$coin]) }}" style="color: black; font-weight: bold;" title="Ver Movimientos">  {{number_format($account->debe, 2, ',', '.')}}</a>                    
                                            </td>
                                            <td style="text-align:right; color:black; ">
                                                <a href="{{ route('accounts.movements',[$account->id,$coin]) }}" style="color: black; font-weight: bold;" title="Ver Movimientos"> {{number_format($account->haber, 2, ',', '.')}}</a>
                                            </td>
                                            <td style="text-align:right; color:black; font-weight: bold;">{{number_format($balance_new + $account->debe - $account->haber, 2, ',', '.')}}</td>
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
                                            
                                            <td style="text-align:right; color:black; font-weight: bold;">{{number_format($balance_new + $account->debe - $account->haber, 2, ',', '.')}}</td>
                                        @else
                                            <td style="text-align:right; color:black; font-weight: bold;">{{number_format($account->balance, 2, ',', '.')}}<br>{{number_format($balance_new, 2, ',', '.')}} $</td>
                                            <!-- Cuando quiero ver mis saldos todos en bolivares y mi cuenta es en dolares-->
                                            <td style="text-align:right; color:black; font-weight: bold;">{{number_format($account->debe, 2, ',', '.')}} <br> {{number_format($account->dolar_debe ?? 0, 2, ',', '.')}}$</td>
                                            <td style="text-align:right; color:black; ">{{number_format($account->haber, 2, ',', '.')}}<br> {{number_format($account->dolar_haber ?? 0, 2, ',', '.')}}$</td>
                                            
                                            <td style="text-align:right; color:black; font-weight: bold;">{{number_format($account->balance + $account->debe - $account->haber, 2, ',', '.')}} <br> {{number_format($balance_new + $account->dolar_debe - $account->dolar_haber, 2, ',', '.')}}$</td>
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
                                            <td style="text-align:right; color:black; font-weight: bold;">{{number_format($balance_new + $account->debe - $account->haber, 2, ',', '.')}}</td>
                                        @else
                                            <td style="text-align:right; color:black; font-weight: bold;">{{number_format($balance_new, 2, ',', '.')}}</td>
                                            <!-- Cuando quiero ver mis saldos todos en bolivares y mi cuenta es en bolivares-->
                                            <td style="text-align:right; color:black; font-weight: bold;">
                                                {{number_format($account->debe, 2, ',', '.')}}                      
                                            </td>
                                            <td style="text-align:right; color:black; ">
                                                {{number_format($account->haber, 2, ',', '.')}}
                                            </td>
                                            <td style="text-align:right; color:black; font-weight: bold;">{{number_format($balance_new + $account->debe - $account->haber, 2, ',', '.')}}</td>
                                        @endif
                                    @endif
                                @endif
                               
                            </tr>   
                            @endif
    
    
    
    
                        @else
                        <tr>
                            <td style="text-align:right; color:black; ">  
                                @if (isset($id_detail))
                                    <a href="{{ route('detailvouchers.edit',[$coin,$id_detail,$account->id]) }}" title="Seleccionar"><i class="fa fa-check"></i></a>
                                @else
                                    <a href="{{ route('detailvouchers.create',[$coin,$header->id,$account->id]) }}" title="Seleccionar"><i class="fa fa-check"></i></a>
                                @endif
                            </td>
                            <td style="text-align:right; color:black; font-weight: bold;">{{$account->code_one}}.{{$account->code_two}}.{{$account->code_three}}.{{$account->code_four}}.{{ str_pad($account->code_five, 3, "0", STR_PAD_LEFT)}}</td>
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
                                        
                                        <td style="text-align:right; color:black; font-weight: bold;">{{number_format($balance_new + $account->debe - $account->haber, 2, ',', '.')}}</td>
                                    @else
                                        <td style="text-align:right; color:black; font-weight: bold;"> {{number_format($account->balance, 2, ',', '.')}}<br>{{number_format($balance_new ?? $account->balance, 2, ',', '.')}}$</td>
                                        <!-- Cuando quiero ver mis saldos todos en bolivares y mi cuenta es en dolares-->
                                        <td style="text-align:right; color:black; font-weight: bold;">
                                            <a href="{{ route('accounts.movements',[$account->id,$coin]) }}" style="color: black; font-weight: bold;" title="Ver Movimientos">{{number_format($account->debe, 2, ',', '.')}} <br> {{number_format($account->dolar_debe ?? 0, 2, ',', '.')}}$</a>                      
                                        </td>
                                        <td style="text-align:right; color:black; ">
                                            <a href="{{ route('accounts.movements',[$account->id,$coin]) }}" style="color: black; font-weight: bold;" title="Ver Movimientos">{{number_format($account->haber, 2, ',', '.')}}<br> {{number_format($account->dolar_haber ?? 0, 2, ',', '.')}}$</a>
                                        </td>
                                        
                                        <td style="text-align:right; color:black; font-weight: bold;">{{number_format($account->balance + $account->debe - $account->haber, 2, ',', '.')}} <br> {{number_format($balance_new + $account->dolar_debe - $account->dolar_haber, 2, ',', '.')}}$</td>
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
                                        
                                        <td style="text-align:right; color:black; font-weight: bold;">{{number_format($balance_new + $account->dolar_debe - $account->dolar_haber, 2, ',', '.')}}</td>
                                    @else
                                        <td style="text-align:right; color:black; font-weight: bold;"> {{number_format($account->balance, 2, ',', '.')}}</td>
                                        <!-- Cuando quiero ver mis saldos todos en bolivares y mi cuenta es en dolares-->
                                        <td style="text-align:right; color:black; font-weight: bold;">
                                            <a href="{{ route('accounts.movements',[$account->id,$coin]) }}" style="color: black; font-weight: bold;" title="Ver Movimientos">{{number_format($account->debe, 2, ',', '.')}}</a>                      
                                        </td>
                                        <td style="text-align:right; color:black; ">
                                            <a href="{{ route('accounts.movements',[$account->id,$coin]) }}" style="color: black; font-weight: bold;" title="Ver Movimientos">{{number_format($account->haber, 2, ',', '.')}}</a>
                                        </td>
                                        
                                        <td style="text-align:right; color:black; font-weight: bold;">{{number_format($account->balance + $account->debe - $account->haber, 2, ',', '.')}}</td>
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
                                        
                                        <td style="text-align:right; color:black; font-weight: bold;">{{number_format($account->balance + $account->debe - $account->haber, 2, ',', '.')}}</td>
                                    @else
                                        <td style="text-align:right; color:black; font-weight: bold;"> {{number_format($account->balance, 2, ',', '.')}}<br>{{number_format($balance_new ?? $account->balance, 2, ',', '.')}}$</td>
                                        <!-- Cuando quiero ver mis saldos todos en bolivares y mi cuenta es en dolares-->
                                        <td style="text-align:right; color:black; font-weight: bold;">
                                            {{number_format($account->debe, 2, ',', '.')}} <br> {{number_format($account->dolar_debe ?? 0, 2, ',', '.')}}$                      
                                        </td>
                                        <td style="text-align:right; color:black; ">
                                            {{number_format($account->haber, 2, ',', '.')}}<br> {{number_format($account->dolar_haber ?? 0, 2, ',', '.')}}$
                                        </td>
                                        <td style="text-align:right; color:black; font-weight: bold;">{{number_format($account->balance + $account->debe - $account->haber, 2, ',', '.')}}<br>{{number_format($balance_new + $account->dolar_debe - $account->dolar_haber, 2, ',', '.')}}$</td>
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
                                        
                                        <td style="text-align:right; color:black; font-weight: bold;">{{number_format($account->balance + $account->debe - $account->haber, 2, ',', '.')}} </td>
                                    @else 
                                        <td style="text-align:right; color:black; font-weight: bold;"> {{number_format($account->balance, 2, ',', '.')}}</td>                                 
                                        <td style="text-align:right; color:black; font-weight: bold;">
                                            {{number_format($account->debe, 2, ',', '.')}}                     
                                        </td>
                                        <td style="text-align:right; color:black; ">
                                            {{number_format($account->haber, 2, ',', '.')}}
                                        </td>
                                        <td style="text-align:right; color:black; font-weight: bold;">{{number_format($account->balance + $account->debe - $account->haber, 2, ',', '.')}}</td>
                                    @endif
                                @endif
                            @endif
                           
                               
                        </tr>   
                        
                        @endif
                        @endforeach   
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
  
@endsection
@section('javascript')
    <script>
        $('#dataTable').DataTable({
            "ordering": false,
            "order": [],
            'aLengthMenu': [[50, 100, 150, -1], [50, 100, 150, "All"]]
        });
        $("#coin").on('change',function(){
            var coin = $(this).val();
            window.location = "{{route('detailvouchers.selectaccount', ['','',''])}}"+"/"+coin+"/"+"{{ $header->id }}"+"/"+"{{ $id_detail }}";
        });
    </script>
@endsection                      
