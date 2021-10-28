@extends('admin.layouts.dashboard')

@section('content')

{{-- VALIDACIONES-RESPUESTA--}}
@include('admin.layouts.success')   {{-- SAVE --}}
@include('admin.layouts.danger')    {{-- EDITAR --}}
@include('admin.layouts.delete')    {{-- DELELTE --}}
{{-- VALIDACIONES-RESPUESTA --}}


    <div class="row justify-content-left">
        <div class="col-md-12">
            <div class="card">
               
                <div class="card-body">
                  <!--  <div class="list-group">
                      <a href="#" class="list-group-item list-group-item-action">A simple default list group item</a>
                    
                      <a href="#" class="list-group-item list-group-item-action list-group-item-primary">A simple primary list group item</a>
                      <a href="#" class="list-group-item list-group-item-action list-group-item-secondary">A simple secondary list group item</a>
                      <a href="#" class="list-group-item list-group-item-action list-group-item-success">A simple success list group item</a>
                      <a href="#" class="list-group-item list-group-item-action list-group-item-danger">A simple danger list group item</a>
                      <a href="#" class="list-group-item list-group-item-action list-group-item-warning">A simple warning list group item</a>
                      <a href="#" class="list-group-item list-group-item-action list-group-item-info">A simple info list group item</a>
                      <a href="#" class="list-group-item list-group-item-action list-group-item-light">A simple light list group item</a>
                      <a href="#" class="list-group-item list-group-item-action list-group-item-dark">A simple dark list group item</a>
                    </div> -->
                    
                    <div class="row justify-content-center">
                        <div class="col-sm-3">
                          <div class="list-group" id="list-tab" role="tablist">
                            <li class="list-group-item list-group-item-action list-group-item-primary text-center" style="padding: 0;" id="list-home-list" data-bs-toggle="list" role="tab" aria-controls="home"><font size="-1">Balance General</font></li>
                            <li class="list-group-item list-group-item-action list-group-item-light text-center" style="padding: 2% 0;" id="list-profile-list" data-bs-toggle="list"  role="tab" aria-controls="profile"><font size="-1">Activo <br>{{ number_format($account_activo, 2, ',', '.')}}</font></li>
                            <li class="list-group-item list-group-item-action list-group-item-ligh text-center" style="padding: 2% 0;" id="list-messages-list" data-bs-toggle="list"  role="tab" aria-controls="messages"><font size="-1">Pasivo <br>{{ number_format($account_pasivo, 2, ',', '.')}}</font></li>
                            <li class="list-group-item list-group-item-action list-group-item-light text-center" style="padding: 2% 0;" id="list-settings-list" data-bs-toggle="list" role="tab" aria-controls="settings"><font size="-1">Patrimonio <br>{{ number_format($account_patrimonio, 2, ',', '.')}}</font></li>
                          </div>
                        </div>
                        <div class="col-sm-3">
                          <div class="list-group" id="list-tab" role="tablist">
                            <li class="list-group-item list-group-item-action list-group-item-danger text-center" style="padding: 0;" id="list-home-list" data-bs-toggle="list" role="tab" aria-controls="home"><font size="-1">Ganancias y Pérdidas</font></li>
                            <li class="list-group-item list-group-item-action list-group-item-light text-center" style="padding: 5% 0;" id="list-profile-list" data-bs-toggle="list"  role="tab" aria-controls="profile"><font size="-1">Ingresos {{ number_format(($account_ingresos * -1), 2, ',', '.')}}</font></li>
                            <li class="list-group-item list-group-item-action list-group-item-ligh text-center" style="padding: 5% 0;" id="list-messages-list" data-bs-toggle="list"  role="tab" aria-controls="messages"><font size="-1">Costos {{ number_format($account_costos, 2, ',', '.')}}</font></li>
                            <li class="list-group-item list-group-item-action list-group-item-light text-center" style="padding: 5% 0;" id="list-settings-list" data-bs-toggle="list" role="tab" aria-controls="settings"><font size="-1">Gastos {{ number_format($account_gastos, 2, ',', '.')}}</font></li>
                            <li class="list-group-item list-group-item-action list-group-item-warning text-center" style="padding: 5% 0;" id="list-messages-list" data-bs-toggle="list"  role="tab" aria-controls="messages"><font size="-1">Total {{ number_format(($account_ingresos * -1)-$account_costos-$account_gastos, 2, ',', '.')}}</font></li>
                          </div>
                        </div>
                        <div class="col-sm-3">
                          <div class="list-group" id="list-tab" role="tablist">
                            <li class="list-group-item list-group-item-action list-group-item-info text-center" style="padding: 0;" id="list-home-list" data-bs-toggle="list" role="tab" aria-controls="home"><font size="-1">Saldos Pendientes</font></li>
                            <li class="list-group-item list-group-item-action list-group-item-light text-center" style="padding: 2% 0;" id="list-profile-list" data-bs-toggle="list"  role="tab" aria-controls="profile"><font size="-1">Cuentas por Cobrar <br>{{ number_format($account_cuentas_por_cobrar, 2, ',', '.')}}</font></li>
                            <li class="list-group-item list-group-item-action list-group-item-ligh text-center" style="padding: 2% 0;" id="list-messages-list" data-bs-toggle="list"  role="tab" aria-controls="messages"><font size="-1">Cuentas por Pagar <br>{{ number_format($account_cuentas_por_pagar, 2, ',', '.')}}</font></li>
                            <li class="list-group-item list-group-item-action list-group-item-light text-center" style="padding: 2% 0;" id="list-settings-list" data-bs-toggle="list" role="tab" aria-controls="settings"><font size="-1">Préstamos a largo plazo<br>{{ number_format($account_prestamos, 2, ',', '.')}}</font></li>
                          </div>
                        </div>
                        <div class="col-sm-3">
                          <div class="list-group" id="list-tab" role="tablist">
                            <li class="list-group-item list-group-item-action list-group-item-success text-center" style="padding: 0;" id="list-home-list" data-bs-toggle="list" role="tab" aria-controls="home"><font size="-1">Balance de Bancos</font></li>
                            <li class="list-group-item list-group-item-action list-group-item-light text-center" style="padding: 5% 0;" id="list-profile-list" data-bs-toggle="list"  role="tab" aria-controls="profile"><font size="-1">{{ $account_banco1_name }} {{ number_format($account_banco1, 2, ',', '.')}}</font></li>
                            <li class="list-group-item list-group-item-action list-group-item-ligh text-center" style="padding: 5% 0;" id="list-messages-list" data-bs-toggle="list"  role="tab" aria-controls="messages"><font size="-1">{{ $account_banco2_name }} {{ number_format($account_banco2, 2, ',', '.')}}</font></li>
                            <li class="list-group-item list-group-item-action list-group-item-light text-center" style="padding: 5% 0;" id="list-settings-list" data-bs-toggle="list" role="tab" aria-controls="settings"><font size="-1">{{ $account_banco3_name }} {{ number_format($account_banco3, 2, ',', '.')}}</font></li>
                            <li class="list-group-item list-group-item-action list-group-item-warning text-center" style="padding: 5% 0;" id="list-messages-list" data-bs-toggle="list"  role="tab" aria-controls="messages"><font size="-1">Total {{ number_format($account_banco1+$account_banco2+$account_banco3, 2, ',', '.')}}</font></li>
                          </div>
                        </div>
                        
                  </div>
                
               <br>
              
                  <div class="row justify-content-center">
                      <div class="card shadow mb-2"  style="background-color: white">
                        <div class="card-header py-2" style="background-color: rgb(255, 185, 81)">
                            <h6 class="m-0 font-weight-bold text-center">Ingresos Correspondientes al periodo 2021</h6>
                        </div>
                        <div class="card-body">
                            <div class="chart-bar">
                                <canvas id="myBarChart"></canvas>
                            </div>
                            <hr>
                            Styling for the bar chart can be found in the
                            <code>/js/demo/chart-bar-demo.js</code> file.
                        </div>
                      </div>
                      <div class="col-sm-3" >
                          <div class="card shadow" style="background-color: white">
                            <!-- Card Header - Dropdown -->
                            <div class="card-header"  style="background-color: rgb(255, 185, 81)">
                                <h6 class="m-0 font-weight-bold text-center">Reporte de Ingresos,<br> Egresos y Gastos</h6>
                            </div>
                            <!-- Card Body -->
                            <div class="card-body" >
                                <div class="chart-pie pt-1" >
                                    <canvas id="myPieChart"></canvas>
                                </div>
                                <hr><h6>Styling for the bar chart can be found in the</h6>
                          
                            </div>
                          </div>
                        </div>

                  </div>
            


    </div>
  </div>
</div>
@endsection



@section('javascript')
     
      <!-- Page level custom scripts -->
      <script src="{{asset('vendor/sb-admin/js/demo/chart-area-demo.js')}}"></script>
      <script src="{{asset('vendor/sb-admin/js/demo/chart-pie-demo.js')}}"></script>
      <script src="{{asset('vendor/sb-admin/js/demo/chart-bar-demo.js')}}"></script>
@endsection
