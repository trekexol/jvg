@extends('admin.layouts.dashboard')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-sm-12">
            <div class="card">
                <form method="POST" action="{{ route('reports.store_accounts_receivable') }}">
                    @csrf

                <input type="hidden" name="id_client" value="{{$client->id ?? null}}" readonly>

                <div class="card-header text-center h4">
                        Cuentas por Cobrar
                </div>

                <div class="card-body">
                        <div class="form-group row">
                            <label for="date_end" class="col-sm-1 col-form-label text-md-right">hasta:</label>

                            <div class="col-sm-3">
                                <input id="date_end" type="date" class="form-control @error('date_end') is-invalid @enderror" name="date_end" value="{{ date('Y-m-d', strtotime($date_end ?? $datenow))}}" required autocomplete="date_end">

                                @error('date_end')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            
                            <label id="client_label1" for="clients" class="col-sm-1 text-md-right">Cliente:</label>
                            @if (isset($client))
                                <label id="client_label2" name="id_client" value="{{ $client->id }}" for="clients" class="col-sm-2 ">{{ $client->name }}</label>
                            @endif
                            
                            <div id="client_label3" class="form-group col-sm-1">
                                <a href="{{ route('reports.select_client') }}" title="Seleccionar Cliente"><i class="fa fa-eye"></i></a>  
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
                            <div class="col-sm-1">
                            <button type="submit" class="btn btn-primary ">
                                Buscar
                             </button>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-sm-2 offset-sm-1">
                                <select class="form-control" name="type" id="type">
                                    @if (isset($client))
                                        <option value="todo">Todo</option>
                                        <option selected value="cliente">Por Cliente</option>
                                    @else
                                        <option selected value="todo">Todo</option>
                                        <option value="cliente">Por Cliente</option>
                                    @endif
                                </select>
                            </div>
                            <div class="col-sm-4">
                                <select class="form-control" name="typeinvoice" id="typeinvoice">
                                    @if (isset($typeinvoice))
                                        @if ($typeinvoice == 'notas')
                                            <option selected value="notas">Notas de Entrega</option>
                                        @elseif($typeinvoice == 'facturas')
                                            <option selected value="facturas">Facturas</option>
                                        @else
                                            <option selected value="todo">Facturas y Notas de Entrega</option>
                                        @endif
                                        <option disabled value="todo">-----------------</option>
                                        <option value="todo">Facturas y Notas de Entrega</option>
                                        <option value="notas">Notas de Entrega</option>
                                        <option value="facturas">Facturas</option>
                                    @else
                                        <option selected value="todo">Facturas y Notas de Entrega</option>
                                        <option value="notas">Notas de Entrega</option>
                                        <option value="facturas">Facturas</option>
                                    @endif
                                </select>
                            </div>
                        </div>
                    </form>
                        <div class="embed-responsive embed-responsive-16by9">
                            <iframe class="embed-responsive-item" src="{{ route('reports.accounts_receivable_pdf',[$coin ?? 'bolivares',$date_end ?? $datenow,$typeinvoice ?? 'todo',$client->id ?? null]) }}" allowfullscreen></iframe>
                          </div>
                        
                        </div>
                </div>
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

    $("body").toggleClass("sidebar-toggled");
    $(".sidebar").toggleClass("toggled");
    if ($(".sidebar").hasClass("toggled")) {
        $('.sidebar .collapse').collapse('hide');
    };

    let client  = "<?php echo $client->name ?? 0 ?>";  

    if(client != 0){
        $("#client_label1").show();
        $("#client_label2").show();
        $("#client_label3").show();
    }else{
        $("#client_label1").hide();
        $("#client_label2").hide();
        $("#client_label3").hide();
    }
    

    $("#type").on('change',function(){
            type = $(this).val();
            
            if(type == 'todo'){
                $("#client_label1").hide();
                $("#client_label2").hide();
                $("#client_label3").hide();
            }else{
                $("#client_label1").show();
                $("#client_label2").show();
                $("#client_label3").show();
            }
        });

    </script> 

@endsection