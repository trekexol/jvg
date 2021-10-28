@extends('admin.layouts.dashboard')

@section('content')



<div class="container-fluid">
    <form class="needs-validation" novalidate>
        <div class="form-row">
            <div class="col-md-1 mb-3">
                    <label for="vendedor">Cédula/Rif:</label>
                    <select class="custom-select" required>
                        <option value="J">J-</option>
                        <option value="G">G</option>
                        <option value="V">V</option>
                        <option value="E">E</option>
                    </select>
            </div>
            <div class="col-md-3 mb-3">
                <label for="codigo">Código</label>
                <input type="text" class="form-control" id="codigo" placeholder="Codigo"  required>  
            </div>
           
            <div class="col-md-4 mb-3">
                <label for="vendedor">Vendedor:</label>
                <select class="custom-select" required>
                    <option value="">Seleccionar</option>
                    <option value="1">One</option>
                    <option value="2">Two</option>
                    <option value="3">Three</option>
                </select>
            </div>
        </div>
        <div class="form-row">
            <div class="col-md-4 mb-3">
                <label for="cliente">Nombre / Razón Social:</label>
                <input type="text" class="form-control" id="cliente"   required>
             </div>
             <div class="col-md-6 mb-3">
                <label for="direccion">Dirección:</label>
                <input type="text" class="form-control" id="direccion"  required>     
            </div>
        </div>
        <div class="form-row">
            <div class="col-md-4 mb-3">
                <label for="pais">Pais:</label>
                <input type="text" class="form-control" id="pais" value="Venezuela" required>     
            </div>
            <div class="col-md-4 mb-3">
                <label for="ciudad">Ciudad:</label>
                <input type="text" class="form-control" id="ciudad"  required>     
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
        <br>
        <div class="form-row">
            <div class="col-md-1 mb-3">
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" id="customCheck1">
                    <label class="custom-control-label" for="customCheck1">Tiene Crédito?</label>
                </div>
            </div>
           
            <div class="col-md-3 mb-3">
            <label for="dias_credito">Dias de Crédito:</label>
                <input type="number" class="form-control" id="dias_credito"  required>     
            </div>
            <div class="col-md-4 mb-3">
            <label for="maximo_credito">Monto Máximo de crédito:</label>
                <input type="number" class="form-control" id="maximo_credito"  required>     
            </div>
        </div>
        <div class="form-row">
            <div class="col-md-4 mb-3">
                <label for="porcentaje_iva">Porcentaje Retención IVA:</label>
                <input type="number" class="form-control" id="porcentaje_iva"  required>     
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
