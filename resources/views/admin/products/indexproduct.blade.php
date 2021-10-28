@extends('admin.layouts.dashboard')

@section('content')

<!-- container-fluid -->
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="row py-lg-2">
        <div class="col-sm-2">
            <h4>Codigo :{{$product_detail->code_comercial}}</h4>
        </div>
      <div class="col-sm-4">
        <h4>Nombre :{{$product_detail->description}}</h4>
      </div>
        <div class="col-sm-3">
            @if ($product_detail->money == 'Bs')
                <h4>Moneda : BOLIVARES</h4>
            @else
                <h4>Moneda : DOLARES</h4>
            @endif

        </div>
      <div class="col-sm-3">
        <a href="{{ route('products.createproduct',$product_detail->id)}}" class="btn btn-primary float-md-right" role="button" aria-pressed="true">Registrar un Precio</a>
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
            <tr>
                <th class="text-center">Nro</th>
                <th class="text-center">Precio$/BSF</th>
                <th class="text-center">Editar</th>
            </tr>
            </thead>

            <tbody>
                @if (empty($products))
                @else
                    @php
                        $count = 0;
                    @endphp
                    @foreach ($products as $product)
                        <tr>
                            <td class="text-center">
                                @php
                                    $count++;
                                    echo $count;
                                @endphp
                            </td>
                            <td class="text-center">{{number_format($product_detail->price ,2, ',', '.')}}</td>
                            <td class="text-center">
                                <a href="{{ route('products.editproduct',$product->id) }}"  title="Editar"><i class="fa fa-edit"></i></a>
                            </td>
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
        </script>
@endsection
