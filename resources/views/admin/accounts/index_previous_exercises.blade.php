@extends('admin.layouts.dashboard')

@section('content')


<!-- container-fluid -->
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="row py-lg-2">
        <div class="col-sm-8 h4">
            Historial de Ejercicios Contables
        </div>
        <div class="col-sm-4">
            <a href="{{ route('accounts') }}" class="btn btn-light2"><i class="fas fa-eye" ></i>
                &nbsp Plan de Cuentas
            </a>
        </div>
    </div>
    <!-- Page Heading -->
  </div>
  {{-- VALIDACIONES-RESPUESTA--}}
@include('admin.layouts.success')   {{-- SAVE --}}
@include('admin.layouts.danger')    {{-- EDITAR --}}
@include('admin.layouts.delete')    {{-- DELELTE --}}
{{-- VALIDACIONES-RESPUESTA --}}
<div class="card shadow mb-4">
    <div class="card-body">
        <div class="table-responsive">
        <table class="table table-light2 table-bordered" id="dataTable" width="100%" cellspacing="0">
            <thead>
            <tr>
                <th class="text-center">Fecha de Inicio</th>
                <th class="text-center">Fecha de Cierre</th>
                
            </tr>
            </thead>
            
            <tbody>
                @if (empty($account_historial))
                @else
                    @foreach ($account_historial as $var)
                    <tr>
                    <td class="text-center">
                        <a href="#" onclick="pdf({{ $var->date_begin }},{{ $var->date_end }});" title="Crear">{{$var->date_begin ?? ''}}</a>
                    </td>
                    <td class="text-center">{{$var->date_end ?? ''}}</td>
                    </tr>
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

    function pdf(date_begin,date_end) {

        var nuevaVentana= window.open("{{ route('pdf.previousexercise',['',''])}}" + '/' + date_begin + '/' + date_end,"ventana","left=800,top=800,height=800,width=1000,scrollbar=si,location=no ,resizable=si,menubar=no");
 
    }
    </script> 
@endsection