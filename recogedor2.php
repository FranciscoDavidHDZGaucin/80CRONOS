<?php
/*
********	 INFORMACION ARCHIVO ***************** 
	Nombre  Archivo : recogedor2.php
 	Fecha  Creacion : 23/09/2016
	Descripcion  : 
	Copia  archivo  recogedor2.php parte  del  Proyecto  Pedidos
	Modificado  Fecha  : 
*/
///***Inicio Checamos que el  Usuario  siga  Logeado  
session_start ();
   $MM_restrictGoTo = "login.php";
 if (!(isset($_SESSION['usuario_valido']))){   
      header("Location: ". $MM_restrictGoTo); 
  exit;
}   
///***Fin  Checamos que el  Usuario  siga  Logeado
///****Agregamos librerias
require_once('formato_datos.php');
require_once('Connections/conecta1.php');   
///****
 $agente=$_REQUEST["agente"];
 $remision=$_REQUEST["remision"];
 $clave=$_REQUEST["cliente"];
 
 //mysql_select_db($database_conecta1,$conecta1);
	  $consulta=sprintf("select * from encabeza_pedido where n_agente=%s and n_remision=%s and cve_cte=%s",
						GetSQLValueString($agente,"int"),
						GetSQLValueString($remision,"int"),
						GetSQLValueString($clave,"text"));
	$dato=mysqli_query($conecta1,$consulta) or die (mysql_error($conecta1));		
    $encabezado=mysqli_fetch_assoc($dato);		
	
	
	
$consulta_sql=sprintf("Select  sum(total_prod) as subtotal from detalle_pedido where n_remision=%s and  n_agente=%s",
									  GetSQLValueString($_REQUEST["remision"],"int"),
									  GetSQLValueString($_REQUEST["agente"],"int"));

						$resultado2=mysqli_query($conecta1,$consulta_sql) or die (mysql_error($conecta1));
						$suma_pedidos=mysqli_fetch_assoc($resultado2);		
?>
<html>
<head>
<title>Pedidos</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link href="css/estilos1.css" rel="stylesheet" type="text/css" />
<style>
body {
		
		 background: none repeat scroll 0% 0% #ffe58b;
		
	}
</style>
</head>
<body>



<p align="center" class="texto_resaltado"> Remision  No. <?php echo $remision;?> </p>
<?php 
//echo "El formulario ha enviado variables.<br>"; 
//echo "Id Movimiento: '".$_REQUEST["con_consecutivo"]."'"; 
print'<p>Cliente: '.$encabezado['nom_cte'].'  </p> ';
print'<p>Fecha: '.$encabezado['fecha_alta'].'  </p> ';
print'<p>Plazo: '.$encabezado['plazo'].' dias </p> ';
print'<p>Agente: '.$encabezado['nom_age'].' </p> ';
print'<p>Comentario: '.$encabezado['observacion'].' </p> ';
print'<p>Método de Pago: '.$encabezado['medio_pago'].' </p> ';
print'<p>Cuenta: '.$encabezado['cuenta'].' </p> ';
print'<p>Destino: '.$encabezado['destino'].' </p> ';
print'<p>Observaciones: '.$encabezado['observacion'].' </p> ';
print'<p>Total Remisión: $'.$suma_pedidos['subtotal'].' </p> ';

?> 

  <?php 
    mysql_select_db($database_conecta1,$conecta1);
    $consulta=sprintf("select * from detalle_pedido where n_agente=%s and n_remision=%s",
    GetSQLValueString($_REQUEST["agente"],"int"),
    GetSQLValueString($_REQUEST["remision"],"int"));
    $sql_consulta=mysql_query($consulta,$conecta1) or die (mysql_error());



         ?>
                <table width="390" border="1" align="left">
                <tr>
                        <td width="50">Codigo:</td>
                        <td width="100">Producto:</td>
                        <td width="30">Cantidad:</td>
                        <td width="30">Precio:</td>
                        <td width="30">Dcto%:</td>
                        <td width="30">IEPS%:</td>
                        <td width="30">IVA:</td>
                        <td width="50">Importe:</td>
                </tr>

        <?php	while ($reg = mysql_fetch_array($sql_consulta)) {  ?>


                <tr>
                        <td class="formato_tabla"><?php echo $reg['cve_prod']; ?></td>
                        <td class="formato_tabla"><?php echo $reg['nom_prod']; ?></td>
                        <td class="formato_tabla"><?php echo $reg['cant_prod']; ?></td>
                        <td class="formato_tabla"><?php echo $reg['precio_prod']; ?></td>
                        <td class="formato_tabla"><?php echo $reg['dcto_prod']; ?></td>
                        <td class="formato_tabla"><?php echo $reg['ieps']; ?></td>
                        <td class="formato_tabla"><?php echo $reg['iva']; ?></td>
                        <td class="formato_tabla"><?php echo number_format($reg['total_prod'], 2, '.', ','); ?></td>

                        <?php
                         switch ($reg['estatus']){
                         case "E":
                                print '<td><img src="iconos/emitida.PNG" title="Emitida" /> </td>';
                                break;
                         case "C":
                                print '<td><img src="iconos/cancelada.PNG" title="Cancelada" /> </td>';
                                break;
                         case "P":
                                print '<td><img src="iconos/parcial.PNG"/ title="Facturada Parcial"> </td>';
                                break;
                         case "F":
                                print '<td><img src="iconos/facturada.PNG" title="Facturada" /> </td>';
                                break;
                         case "A":
                                print '<td><img src="iconos/autorizar.PNG" title="Por Autorizar" /> </td>';
                                break;
                        case "NA":
                                print '<td><img src="iconos/na.PNG" title="NO Autorizado" /> </td>';
                                break;	
                                }
                         ?>

                </tr>
           <?php } ?>
              </table>
					  
	                 	  
			  
			 
<p> </p>
<br></br>
<br></br>
<br></br>
<br></br>
<div id="pie">
  <p align="center" class="texto_resaltado">HISTORIAL DE LA REMISION </p>
</div>



		<div id="historia">
		<table width="580" border="1" align="left">
								
                            <td width="30" >#Rem</td>

                                    <td width="100">Agente:</td>
                                    <td width="100">Cliente:</td>
                                    <td width="60">Fecha:</td>
                                    <td width="100">Producto:</td>
                                    <td width="50">Precio:</td>
                                    <td width="50">Precio Objetivo:</td>
                                    <td width="50">Cantidad:</td>
                                    <td width="50">Dato:</td>
                                    <td width="50">Dato2:</td>
                            </tr>

                            <?php 
                            $query1=sprintf("select * from historia_estatus where  n_remision=%s AND n_agente=%s and cve_cte=%s order by fecha asc, n_remision, n_agente, cve_cte",
                                                     GetSQLValueString($_REQUEST["remision"],"int"),
                                                      GetSQLValueString($_REQUEST["agente"],"int"),
                                                     GetSQLValueString($clave,"text"));

                            mysql_select_db($database_conecta1,$conecta1);
                            $sql_consulta=mysql_query($query1,$conecta1) or die (mysql_error());	

                            while ($reg = mysql_fetch_array($sql_consulta)) 
                            { ?>
                            <tr>
                                    <td class="formato_tabla"><?php echo $reg['n_remision']; ?></td>
                                    <td class="formato_tabla" ><?php echo $reg['nom_age']; ?></td>
                                    <td class="formato_tabla"><?php echo $reg['nom_cte']; ?></td>
                                    <td class="formato_tabla"><?php echo $reg['fecha']; ?></td>
                                    <td class="formato_tabla" title="<?php echo $reg['cve_prod']; ?>"><?php echo $reg['nom_prod']; ?></td>
                                    <td class="formato_tabla"><?php echo $reg['estatus_a']; ?></td>
                                    <td class="formato_tabla"><?php echo $reg['estatus_b']; ?></td>
                                    <td class="formato_tabla"><?php echo $reg['comentario']; ?></td>
                                    <td class="formato_tabla"><?php echo $reg['comentario1']; ?></td>
                                    <td class="formato_tabla"><?php echo $reg['comentario2']; ?></td>
                            </tr>
                    <?php			
               } 
               ?>
								
								
		</table>
		</div>

</body>
</html>