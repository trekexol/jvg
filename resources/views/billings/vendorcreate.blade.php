@extends('admin.layouts.dashboard')

@section('content')



<div class="container-fluid">
    <form class="needs-validation" novalidate>
        <div class="form-row">
            <div class="col-md-4 mb-3">
                <label for="codigo">Código</label>
                <input type="text" class="form-control" id="codigo" required>  
            </div>
        </div>
        <div class="form-row">
            <div class="col-md-4 mb-3">
                <label for="nombre">Nombre</label>
                <input type="text" class="form-control" id="nombre"   required>
             </div>
             <div class="col-md-6 mb-3">
                <label for="domicilio">Domicilio:</label>
                <input type="text" class="form-control" id="domicilio"  required>     
            </div>
        </div>
        <div class="form-row">
            <div class="col-md-4 mb-3">
                <label for="telefono1">Teléfono 1:</label>
                <input type="text" class="form-control" id="telefono1"  required>     
            </div>
            <div class="col-md-4 mb-3">
            <label for="telefono2">Teléfono 2:</label>
                <input type="text" class="form-control" id="telefono2"  required>     
            </div>
        </div>
        <div class="form-row">
            <div class="col-md-4 mb-3">
                <label for="comision">Comisión:</label>
                <input type="text" class="form-control" id="comision" value="Venezuela" required>     
            </div>
            <div class="col-md-4 mb-3">
                <label for="correo">Correo:</label>
                <input type="text" class="form-control" id="correo"  required>     
            </div>
        </div>
       
        <br>
        <div class="form-row">
            <div class="col-md-2 mb-3">
                <label for="correo">Tipo de Comisión:</label>
            </div>  
              
            <div class="col-md-3 mb-2">
                <div class="custom-control custom-radio custom-control-inline">
                    <input type="radio" id="customRadioInline1" name="customRadioInline1" class="custom-control-input">
                    <label class="custom-control-label" for="customRadioInline1">porcentaje de venta</label>
                </div>
            </div>
            <div class="col-md-3 mb-2">
                <div class="custom-control custom-radio custom-control-inline">
                    <input type="radio" id="customRadioInline2" name="customRadioInline1" class="custom-control-input">
                    <label class="custom-control-label" for="customRadioInline2">porcentaje de la ganancia</label>
                </div>
            </div>    
            <div class="col-md-3 mb-2">
                <div class="custom-control custom-radio custom-control-inline">
                    <input type="radio" id="customRadioInline3" name="customRadioInline1" class="custom-control-input">
                    <label class="custom-control-label" for="customRadioInline3">Monto fijo</label>
                </div>
            </div>    
        </div>
        
        <div class="form-row">
            <div class="col-md-4 mb-3">
                <label for="fecha_nacimiento">Fecha Nacimiento:</label>
                <input type="date" class="form-control" id="fecha_nacimiento"  required>     
            </div>
            <div class="col-md-4 mb-3">
                <label for="porcentaje_islr">Porcentaje Retención ISLR:</label>
                <input type="number" class="form-control" id="porcentaje_islr"  required>     
            </div>
        </div>
        <br>
        <div class="form-row">
            <div class="col-md-5 mb-3">
            </div>
            <button type="button" class="btn btn-primary">Registrar</button>
            <div class="col-md-1 mb-3">
            </div>
            <button type="button" class="btn btn-danger">Cancelar</button>
        </div>
    </form>

</div>
<br><br>



<script>
// Example starter JavaScript for disabling form submissions if there are invalid fields
(function() {
  'use strict';
  window.addEventListener('load', function() {
    // Fetch all the forms we want to apply custom Bootstrap validation styles to
    var forms = document.getElementsByClassName('needs-validation');
    // Loop over them and prevent submission
    var validation = Array.prototype.filter.call(forms, function(form) {
      form.addEventListener('submit', function(event) {
        if (form.checkValidity() === false) {
          event.preventDefault();
          event.stopPropagation();
        }
        form.classList.add('was-validated');
      }, false);
    });
  }, false);
})();
</script>
@endsection
