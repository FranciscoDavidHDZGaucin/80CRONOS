<?php
///Complemento de las guias de visita este archivo borra el documento


?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Documento sin título</title>
</head>

<body>
<?php
		
require_once('Connections/conecta1.php');
require_once('formato_datos.php'); 
	
  	$borrar=$_GET["borra"];

	
	$consulta="Delete from Captura where Id_Cap=$borrar";
	$c=mysqli_query($conecta1,$consulta);


 $resultad = "SELECT * 
FROM  Captura ";  
$sql = mysqli_query($conecta1, $resultad);
 
 
  while($row = mysqli_fetch_array($sql)) { 
      printf("<tr><td>&nbsp;%s</td> <td>&nbsp;%s&nbsp;</td><td>&nbsp;%s</td><td>&nbsp;%s</td><td>&nbsp;%s</td><td>&nbsp;%s</td><td>&nbsp;%s</td><td><a href='borrar.php?borra=$row[0]'>Eliminar</a></td></tr>", $row["FechaG"], $row["Ciudad"], $row["Cliente"], $row["Asunto"], $row["Resultado"], $row["Venta"], $row["ObjVenta"], $row["Week"], $row["Agente"], $row["Zona"]); 
					
   header("Location: captura_gv.php"); 
  
   } 
    
  
  
					
 

?>

</body>
</html>