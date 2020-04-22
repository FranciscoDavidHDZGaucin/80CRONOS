<?php
/*
********	 INFORMACION ARCHIVO ***************** 
	Nombre  Archivo : dirnc.php  
 	Fecha  Creacion : 23/09/2016     
	Descripcion  : 
            Copia  archivo  dirnc.php    parte  del  Proyecto  Pedidos
 *          Se cambio  el   archivo  foot.php  por  agente_footer.php 
	Modificado  Fecha  : 
*/
///****Inicio   Librerias  Utilizadas  en Cronos
///****Cabecera Cronos 
require_once('header_direccion.php');
///***Conexion  sap
///require_once('conexion_sap/sap.php');
/*****Sintetizador de  Datos en el proyecto  pedidos   se  utiliza el   
formtato_datos2.php   pero     se  analiso   y son   identicos  los  archivos 
 por lo que se   dejo el  formato_datos.php  
 *  */
require_once('formato_datos.php');   //para evitar poner la funcion de conversion de tipos de datos
///***Conexion Mysql  
require_once('Connections/conecta1.php');
///**Uso de  la Base  de Datos
///mssql_select_db("AGROVERSA"); 
///*****
///require('correos.php');   //funcion para mandar correos
///*******************
//require('calculodv.php');   //funcion para obtener el email de un usuario en especifico
///*****
///****FIN    Librerias  Utilizadas  en Cronos 



if (isset($_REQUEST['autorizar'])){
    $varias=$_REQUEST['seleccion'];
    $nfilas = count ($varias);	
    $respuesta="1";  //autorizar
    $comodin="A";
   for ($i=0; $i<$nfilas; $i++)
     {
       ///el número de notas de crédito seleccionadas
       
     
     $update=  sprintf("UPDATE notas_credito SET director=%s WHERE id=%s",
                    GetSQLValueString($respuesta, "int"),
                    GetSQLValueString($varias[$i], "int")); 
      // echo "<br>".$update; 
      $resultado=mysqli_query($conecta1,$update) or die (mysqli_error($conecta1));   //actualizar tabla de notas_credito
      }
    
}

if (isset($_REQUEST['noautorizar'])){
    $varias=$_REQUEST['seleccion'];
    $nfilas = count ($varias);	
    $respuesta="0";  //No autorizar
    $comodin="R";
   for ($i=0; $i<$nfilas; $i++)
     {
       ///el número de notas de crédito seleccionadas
       
      
        $update=  sprintf("UPDATE notas_credito SET director=%s, status=%s WHERE id=%s",
                    GetSQLValueString($respuesta, "int"),
                    GetSQLValueString($comodin, "text"),
                    GetSQLValueString($varias[$i], "int"));  
       // echo "<br>".$update; 
        $resultado=mysqli_query($conecta1,$update) or die (mysqli_error($conecta1));   //actualizar tabla de notas_credito
      }
    
}

?>

<script type="text/javascript">
    function marcar(source) 
    {
        checkboxes=document.getElementsByTagName('input'); //obtenemos todos los controles del tipo Input
        for(i=0;i<checkboxes.length;i++) //recoremos todos los controles
        {
            if(checkboxes[i].type == "checkbox") //solo si es un checkbox entramos
            {
                checkboxes[i].checked=source.checked; //si es un checkbox le damos el valor del checkbox que lo llamó (Marcar/Desmarcar Todos)
            }
        }
    }
</script>
<div class="espacio-datos">
     
             <h3>Notas de Crédito Pendientes por Autorizar</h3>   
             <form name="form_autoriza" method="GET" action="dirnc.php">
             <div class="botones-control">
                    <input type="checkbox" onclick="marcar(this);" /> Marcar/Desmarcar Todos  
                    <input type="submit" name="autorizar" value="Autorizar" onclick="return confirm('¿Esta apunto de Autorizar?');">    
                    <input type="submit" name="noautorizar" value="No Autorizar" onclick="return confirm('¿Esta apunto de NO Autorizar?');">    
             </div>    
                 <br> 
    <div class="table-responsive">
	<table  class="table table-responsive table-bordered">
        <thead>
            <tr>
                <th>Cliente</th>
                <th>Agente</th>
                <th>F_Vence</th>
                <th>F_Pago</th>
                <th>Dv</th>
                <th>Total</th>
                <th>Pago</th>
                <th>Concepto</th>
                <th>$Desc</th>
                <th></th>
               
            </tr> 
            
        </thead>   
        
        <tbody>
            <?php 
                //$str_consulta="SELECT * FROM notas where isnull(nc_sap) and ((n_diasv>30) or (porce_desc>6) or (id_concepto>1)) and autoriza1<>0 and autoriza1<>0 and autoriza3<>0 and director=0 and status<>'R' Order by cve_cte";
                $str_consulta="SELECT * FROM notas where isnull(nc_sap) and ((n_diasv>30) or (porce_desc>6) or (tipo>1))  and director=0 and status<>'R' Order by cve_cte";
                $q_consulta=  mysqli_query($conecta1, $str_consulta) or die (mysqli_error($conecta1));
                
                
            while ($reg = mysqli_fetch_array($q_consulta)) { 
                 ?>   
            
            <tr>
               
                <td><a href="popnc.php?id=<?php echo $reg['id']; ?>" target="_blank" onClick="window.open(this.href, this.target, 'width=430,height=500,scrollbars=yes'); return false;"><?php echo $reg['cliente']; ?></a></td>
                <td><?php echo $reg['agente']; ?></td>
                <td><?php echo $reg['fecha_vence']; ?></td>
                <td><?php echo $reg['fecha_pago']; ?></td>
                <td title="Días Vencido"><?php 
                // echo   $reg['n_diasv2'];
               if (is_null($reg['n_diasv2'])){
                   echo  $reg['n_diasv'];  
               }else{
                   echo  $reg['n_diasv2'];  
               }
                    
               //   echo   $reg['n_diasv'].",".$reg['n_diasv2']; 
               
                 // echo  $reg['n_diasv2'];  
               
                ?>
                </td>
                <td><?php echo $reg['moneda']." ".number_format($reg['total_fac'], 2, '.', ','); ?></td>
                <td><?php echo "$".number_format($reg['importe_pago'], 2, '.', ','); ?></td>
                <td><?php echo $reg['nombre']; ?></td>
                <td><?php echo "$".number_format($reg['importe_desc'], 2, '.', ',')." "."%".$reg['porce_desc']; ?></td>
                <td> <input type="CHECKBOX" name="seleccion[]" value="<?php echo $reg['id'] ?>"> </td>
            </tr>           
            <?php } ?>
        </tbody> 
    </table>
    </div>   
    </form> <!-- Form para capturar el total de Notas seleccionadas  -->            
</div> <!-- Div de Contenido  -->

<?php
//incluye el pie de pagina cuando se entra como vendedor
include('foot.php');
?>
