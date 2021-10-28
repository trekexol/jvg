@extends('admin.layouts.dashboard')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-sm-12">
            <div class="card">
                <form method="POST" action="{{ route('reports.store_debtstopay') }}">
                    @csrf

                <input type="hidden" name="id_provider" value="{{$provider->id ?? null}}" readonly>

                <div class="card-header text-center h4">
                        Cuentas por Pagar
                </div>

                <div class="card-body">
                        <div class="form-group row">
                            <label for="date_end" class="col-sm-1 col-form-label text-md-right">hasta </label>

                            <div class="col-sm-3">
                                <input id="date_end" type="date" class="form-control @error('date_end') is-invalid @enderror" name="date_end" value="{{ date('Y-m-d', strtotime($date_end ?? $datenow))}}" required autocomplete="date_end">

                                @error('date_end')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-sm-2">
                                <select class="form-control" name="type" id="type">
                                    @if (isset($provider))
                                        <option value="todo">Todo</option>
                                        <option selected value="proveedor">Por Proveedor</option>
                                    @else
                                        <option selected value="todo">Todo</option>
                                        <option value="proveedor">Por Proveedor</option>
                                    @endif
                                    
                                    
                                </select>
                            </div>
                            
                            <label id="provider_label1" for="providers" class="col-sm-2 text-md-right">Proveedor:</label>
                            @if (isset($provider))
                                <label id="provider_label2"  for="providers" class="col-sm-2 ">{{ $provider->razon_social }}</label>
                            @endif
                            
                            <div id="provider_label3" class="form-group col-sm-1">
                                <a href="{{ route('reports.select_provider') }}" title="Seleccionar Proveedor"><i class="fa fa-eye"></i></a>  
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
                    </form>
                        <div class="embed-responsive embed-responsive-16by9">
                            <iframe class="embed-responsive-item" src="{{ route('reports.debtstopay_pdf',[$coin ?? "bolivares",$date_end ?? $datenow,$provider->id ?? null]) }}" allowfullscreen></iframe>
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

    let provider  = "<?php echo $provider->razon_social ?? 0 ?>";  

    if(provider != 0){
        $("#provider_label1").show();
        $("#provider_label2").show();
        $("#provider_label3").show();
    }else{
        $("#provider_label1").hide();
        $("#provider_label2").hide();
        $("#provider_label3").hide();
    }
    

    $("#type").on('change',function(){
            type = $(this).val();
            
            if(type == 'todo'){
                $("#provider_label1").hide();
                $("#provider_label2").hide();
                $("#provider_label3").hide();
            }else{
                $("#provider_label1").show();
                $("#provider_label2").show();
                $("#provider_label3").show();
            }
        });

    </script> 

@endsection