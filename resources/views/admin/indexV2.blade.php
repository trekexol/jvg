@extends('admin.layouts.dashboard')

@section('content')

{{-- VALIDACIONES-RESPUESTA--}}
@include('admin.layouts.success')   {{-- SAVE --}}
@include('admin.layouts.danger')    {{-- EDITAR --}}
@include('admin.layouts.delete')    {{-- DELELTE --}}
{{-- VALIDACIONES-RESPUESTA --}}

<div class="container">
  <div class="row justify-content-center">
      <div class="col-md-12">
          <div class="card">
              <div class="card-header">Menú Principal</div>

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
                    <div class="row">
                    <div class="col-3">
                      <div class="list-group" id="list-tab" role="tablist">
                        <a class="list-group-item list-group-item-action list-group-item-primary text-center" id="list-home-list" data-bs-toggle="list" href="#list-home" role="tab" aria-controls="home">Facturación</a>
                        <a class="list-group-item list-group-item-action list-group-item-light" id="list-profile-list" data-bs-toggle="list" href="#list-profile" role="tab" aria-controls="profile">Clientes</a>
                        <a class="list-group-item list-group-item-action list-group-item-light" id="list-messages-list" data-bs-toggle="list" href="#list-messages" role="tab" aria-controls="messages">Facturas</a>
                        <a class="list-group-item list-group-item-action list-group-item-light" id="list-settings-list" data-bs-toggle="list" href="#list-settings" role="tab" aria-controls="settings">Cobros</a>
                        <a class="list-group-item list-group-item-action list-group-item-light" id="list-messages-list" data-bs-toggle="list" href="#list-messages" role="tab" aria-controls="messages">Notas de Entrega</a>
                        <a class="list-group-item list-group-item-action list-group-item-light" id="list-settings-list" data-bs-toggle="list" href="#list-settings" role="tab" aria-controls="settings">Productos y Servicios</a>
                      </div>
                    </div>
                    <div class="col-3">
                      <div class="list-group" id="list-tab" role="tablist">
                        <a class="list-group-item list-group-item-action list-group-item-success text-center" id="list-home-list" data-bs-toggle="list" href="#list-home" role="tab" aria-controls="home">Proveedores</a>
                        <a class="list-group-item list-group-item-action list-group-item-light" id="list-profile-list" data-bs-toggle="list" href="#list-profile" role="tab" aria-controls="profile">Gastos y Compras</a>
                        <a class="list-group-item list-group-item-action list-group-item-light" id="list-messages-list" data-bs-toggle="list" href="#list-messages" role="tab" aria-controls="messages">Ordenes de Compra</a>
                        <a class="list-group-item list-group-item-action list-group-item-light" id="list-settings-list" data-bs-toggle="list" href="#list-settings" role="tab" aria-controls="settings">Pagos</a>
                        <a class="list-group-item list-group-item-action list-group-item-light" id="list-messages-list" data-bs-toggle="list" href="#list-messages" role="tab" aria-controls="messages">Ordenes de Pago</a>
                        <a class="list-group-item list-group-item-action list-group-item-light" id="list-settings-list" data-bs-toggle="list" href="#list-settings" role="tab" aria-controls="settings">Productos y Servicios</a>
                      </div>
                    </div>
                    <div class="col-6">
                      <div class="list-group" id="list-tab" role="tablist">
                        <li class="list-group-item list-group-item-action list-group-item-primary text-center" id="list-home-list" data-bs-toggle="list" role="tab" aria-controls="home">Balance General</li>
                        <li class="list-group-item list-group-item-action list-group-item-light text-center" id="list-profile-list" data-bs-toggle="list"  role="tab" aria-controls="profile">Activo 144.500.000,00</li>
                        <li class="list-group-item list-group-item-action list-group-item-ligh text-center" id="list-messages-list" data-bs-toggle="list"  role="tab" aria-controls="messages">Pasivo -11.400.000,00</li>
                        <li class="list-group-item list-group-item-action list-group-item-light text-center" id="list-settings-list" data-bs-toggle="list" role="tab" aria-controls="settings">Capital 133.100.000,00</li>
                      </div>
                    </div>
                    
                  </div>
                <br><br>
                 
                <div class="row justify-content-center">
                 
                    <div class="col-3">
                      <div class="list-group" id="list-tab" role="tablist">
                        <a class="list-group-item list-group-item-action list-group-item-warning text-center" id="list-home-list" data-bs-toggle="list" href="#list-home" role="tab" aria-controls="home">Nómina</a>
                        <a class="list-group-item list-group-item-action list-group-item-light" id="list-profile-list" data-bs-toggle="list" href="#list-profile" role="tab" aria-controls="profile">Empleados</a>
                        <a class="list-group-item list-group-item-action list-group-item-light" id="list-messages-list" data-bs-toggle="list" href="#list-messages" role="tab" aria-controls="messages">Generar Nómina</a>
                        <a class="list-group-item list-group-item-action list-group-item-light" id="list-settings-list" data-bs-toggle="list" href="#list-settings" role="tab" aria-controls="settings">Índices BCV</a>
                      </div>
                    </div>
                    <div class="col-3">
                      <div class="list-group" id="list-tab" role="tablist">
                        <a class="list-group-item list-group-item-action list-group-item-info text-center" id="list-home-list" data-bs-toggle="list" href="#list-home" role="tab" aria-controls="home">Otros</a>
                        <a class="list-group-item list-group-item-action list-group-item-light" id="list-profile-list" data-bs-toggle="list" href="#list-profile" role="tab" aria-controls="profile">Depósitos Bancarios</a>
                        <a class="list-group-item list-group-item-action list-group-item-light" id="list-messages-list" data-bs-toggle="list" href="#list-messages" role="tab" aria-controls="messages">Movimientos Bancarios</a>
                        <a class="list-group-item list-group-item-action list-group-item-light" id="list-settings-list" data-bs-toggle="list" href="#list-settings" role="tab" aria-controls="settings">Plan de Cuentas</a>
                        <a class="list-group-item list-group-item-action list-group-item-light" id="list-messages-list" data-bs-toggle="list" href="#list-messages" role="tab" aria-controls="messages">Ajuste Contable</a>
                        <a class="list-group-item list-group-item-action list-group-item-light" id="list-settings-list" data-bs-toggle="list" href="#list-settings" role="tab" aria-controls="settings">Ejercicio Anterior</a>
                      </div>
                    </div>
                      <div class="col-6">
                          <div class="list-group" id="list-tab" role="tablist">
                            <li class="list-group-item list-group-item-action list-group-item-danger text-center" id="list-home-list" data-bs-toggle="list" role="tab" aria-controls="home">Ganancias y Pérdidas</li>
                            <li class="list-group-item list-group-item-action list-group-item-light text-center" id="list-profile-list" data-bs-toggle="list"  role="tab" aria-controls="profile">Ingresos 49.225.211,00</li>
                            <li class="list-group-item list-group-item-action list-group-item-ligh text-center" id="list-messages-list" data-bs-toggle="list"  role="tab" aria-controls="messages">Egresos 15.262.427,00</li>
                            <li class="list-group-item list-group-item-action list-group-item-light text-center" id="list-settings-list" data-bs-toggle="list" role="tab" aria-controls="settings">Gastos 3.309.615,40</li>
                            <li class="list-group-item list-group-item-action list-group-item-warning text-center" id="list-messages-list" data-bs-toggle="list"  role="tab" aria-controls="messages">Total 30.653.168,60</li>
                          </div>
                      </div>
                    
                </div>
                <br><br>
                <div class="row justify-content-center">
                  <div class="col-6">
                    
                  </div>
                  <div class="col-6">
                    <div class="list-group" id="list-tab" role="tablist">
                      <li class="list-group-item list-group-item-action list-group-item-info text-center" id="list-home-list" data-bs-toggle="list" role="tab" aria-controls="home">Saldos Pendientes</li>
                      <li class="list-group-item list-group-item-action list-group-item-light text-center" id="list-profile-list" data-bs-toggle="list"  role="tab" aria-controls="profile">Cuentas por Cobrar -500,00</li>
                      <li class="list-group-item list-group-item-action list-group-item-ligh text-center" id="list-messages-list" data-bs-toggle="list"  role="tab" aria-controls="messages">Cuentas por Pagar -2.552.873,40</li>
                      <li class="list-group-item list-group-item-action list-group-item-light text-center" id="list-settings-list" data-bs-toggle="list" role="tab" aria-controls="settings">Préstamos Bancarios 0,00</li>
                    </div>
                  </div>
                  
                </div>
                <br><br>
                <div class="row justify-content-center">
                  <div class="col-6">
                   
                  </div>
                  <div class="col-6">
                    <div class="list-group" id="list-tab" role="tablist">
                      <li class="list-group-item list-group-item-action list-group-item-success text-center" id="list-home-list" data-bs-toggle="list" role="tab" aria-controls="home">Balance de Bancos</li>
                      <li class="list-group-item list-group-item-action list-group-item-light text-center" id="list-profile-list" data-bs-toggle="list"  role="tab" aria-controls="profile">Banesco -41.420.670,00</li>
                      <li class="list-group-item list-group-item-action list-group-item-ligh text-center" id="list-messages-list" data-bs-toggle="list"  role="tab" aria-controls="messages">Exterior 8.120.000,00</li>
                      <li class="list-group-item list-group-item-action list-group-item-light text-center" id="list-settings-list" data-bs-toggle="list" role="tab" aria-controls="settings">Mercantil 8.010.810,40</li>
                      <li class="list-group-item list-group-item-action list-group-item-warning text-center" id="list-messages-list" data-bs-toggle="list"  role="tab" aria-controls="messages">Total -25.289.860,00</li>
                    </div>
                  </div>
                </div>




              </div>
          </div>
      </div>
  </div>
</div>
@endsection


