<?php
/*
********	 INFORMACION ARCHIVO ***************** 
	Nombre  Archivo : crear1.php 
 	Fecha  Creacion : 13/10/2016 
	Descripcion  : 
	Copia  del  Proyecto   Proyeccion Alias presupuestos 
 *      Nombre  del  Archivo  Origen : crear1.php  
 *      Servidor : .17   
	Modificado  Fecha  : 
*/
////**Inicio De Session 
session_start();
///****Cabecera Cronos 
require_once('header.php');
///***Conexion  sap
require_once('conexion_sap/sap.php');
/*****Sintetizador de  Datos en el proyecto  pedidos   se  utiliza el   
formtato_datos2.php   pero     se  analiso   y son   identicos  los  archivos 
 por lo que se   dejo el  formato_datos.php  
 *  */
require_once('formato_datos.php');   //para evitar poner la funcion de conversion de tipos de datos
///***Conexion Mysql  
require_once('Connections/conecta1.php');
///****
require_once('funciones_proyecciones.php');   
/////**********Inicio  Codigo  Copiado********************************
 mysqli_select_db($conecata1, $database_conecta1);
    
  //  $string_postulados=("Select * from postulado order by id desc, f_alta  desc ");
  //  $sql_postulados=mysqli_query($conecta1,$string_postulados) or die (mysqli_error($conecta1));
    
 if (isset($_REQUEST['almacen'])) {
     
     $valor=$_REQUEST['almacen'];
    
   //  $string2= "SELECT * FROM almacenes where almacen='$valor'";
     $string2= sprintf("Select * from almacenes_proyeccion WHERE almacen=%s ",  
                    GetSQLValueString($valor, "text"));           
     
     $sql2=mysqli_query($conecta1,$string2) or die (mysqli_error($conecta1));
     $datos2=   mysqli_fetch_assoc($sql2);
   
     $string=sprintf("select * from almacenes_proyeccion where agente=%s order by nombre_alma",
                GetSQLValueString($_SESSION['usuario_agente'], "int")); 
    $sql=mysqli_query($conecta1,$string) or die (mysqli_error($conecta1));
    
     $_SESSION['cve_alma']=$valor;
     if ($_SESSION['cve_alma']=="NA"){
             $_SESSION['nombre_alma']="NA";
     }else{
         $_SESSION['nombre_alma']=$datos2['nombre_alma'];
     }
         
     
  //  echo $string2;
     
 
    
  
 }   
   $string=sprintf("select * from almacenes_proyeccion where agente=%s order by nombre_alma",
                GetSQLValueString($_SESSION['usuario_agente'], "int")); 
    $sql=mysqli_query($conecta1,$string) or die (mysqli_error($conecta1));
  ////*****************Fin Codigo  Copiado   
?>  

 <?php require_once 'submenu_proyeccion.php'  ?>

<div  class="container"> 
    <form  class="form-inline" name="form1" action="crear1.php" method="post">
        <div   class="form-group"> 
            <p><?php   echo  $_SESSION['usuario_agente']; ?></p> 
            <select class="form-control" name="almacen" required onchange="this.form.submit()">
                 <option value="">Seleccione Almacen</option>
               

             <?php		
             while ($row=mysqli_fetch_array($sql))
             {
                     if ($row['almacen']==$valor){

                      echo '<option selected value="'.$row['almacen'].'">'.$row['nombre_alma'].'</option>';	
                     }else{
                             echo '<option value="'.$row['almacen'].'">'.$row['nombre_alma'].'</option>';	
                     }	
             }
             ?>
            </select>
        </div>
        <div class="form-group"> 
            <label class="form-control" for="nombre">Clave: *</label>
             <input class="form-control" type="text" id="nombre" readonly name="nombre" size="6" value="<?php echo $valor; ?>"  />
        </div> 
<br>
  </form>
  
    <form  id="Formulario1" class="form-inline" name="Formulario1" method="post" action="crear2.php"  >
        <div  class="form-group"> 
            <input class="form-control" type="hidden" name="cve_alma" id="clave_cliente" value=" <?php echo $valor;?>"  />
            <input  class="form-control" type="hidden" name="nombre_alma" value=" <?php echo $datos2['descripcion'];?>"  />
            <?php  
              if ($valor!=""){
                 
           ?>
                 <input  class="btn  btn-success "  type="submit"   id="aceptar" name="aceptar" value="Continuar" />   
                  
           <?php   } ?>
            
           
		
        </div> 
    </form>  
</div> 
<?php  require_once('foot.php');?>  