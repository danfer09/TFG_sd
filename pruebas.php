<?php
$nuevoNombre = "paco";
$b="2";
//$sql = "UPDATE profesores SET nombre=".$nuevoNombre." WHERE id=".$b;
echo "UPDATE profesores SET nombre='".$nuevoNombre."' WHERE id=".$b;

?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>css demo</title>
  <style>
  div {
    width: 60px;
    height: 60px;
    margin: 5px;
    float: left;
  }
  </style>
  <script src="https://code.jquery.com/jquery-1.10.2.js"></script>
</head>
<body>
 
<span id="result">&nbsp;</span>
<div style="background-color:blue;"></div>
<div style="background-color:rgb(15,99,30);"></div>
<div style="background-color:#123456;"></div>
<div style="background-color:#f11;"></div>
 
<script>
$( "div" ).click(function() {
  var color = $( this ).css( "background-color" );
  $( "#result" ).html( "That div is <span style='color:" +
    color + ";'>" + color + "</span>." );
});
</script>
 
</body>
</html>