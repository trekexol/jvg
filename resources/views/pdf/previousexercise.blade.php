
  
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
 
<title>Inventario</title>
<style>
  table, td, th {
    border: 1px solid black;
  }
  
  table {
    border-collapse: collapse;
    width: 50%;
  }
  
  th {
    
    text-align: left;
  }
  </style>
</head>

<body>


 <br><br>
  <h4 style="color: black">Empresa: {{ $company->razon_social ?? ''}}</h4>
  <br>
  <h4 style="color: black">Rif: {{ $company->code_rif ?? ''}}</h4>

  <br>
  <h2 style="color: black; text-align: center">Ejercicio Anterior</h2>
  <br>
  <h4 style="color: black; text-align: center">Fecha de Emisión: {{ $datenow }}</h4>
   
 


</body>
</html>
