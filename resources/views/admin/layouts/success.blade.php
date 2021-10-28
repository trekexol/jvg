@if(Session::has('success'))
<div id="alerta" class="alert alert-success alert-dismissible fade show" role="alert">
    {{Session::get('success')}}
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
@endif

@section('validacion_time')

<script>
  $(document).ready(function() {
    setTimeout(function() {
        $("#alerta").fadeOut(1500);
    },5000);
 
   
});
  
</script>
    
@endsection
