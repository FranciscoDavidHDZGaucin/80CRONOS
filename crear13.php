<?php
/*
********	 INFORMACION ARCHIVO ***************** 
	Nombre  Archivo : crear12.php 
 	Fecha  Creacion : 13/10/2016 
	Descripcion  : 
	Copia  del  Proyecto   Proyeccion Alias presupuestos 
 *      Nombre  del  Archivo  Origen : crear12.php  
 *      Servidor : .17   
	Modificado  Fecha  : 
*/
////**Inicio De Session 
session_start();
///****Cabecera Cronos 
require_once('header_planeador.php');
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
require_once('funciones.php');   
/////************Inicio  Codigo Copiado*******************
mysqli_select_db($conecta1, $database_conecta1);
$string_agentes=sprintf("select distinctrow(cve_age), nom_age from vista_proyeccion2 where (anio=%s or anio=%s) and (mes=%s or mes=%s or mes=%s) order by nom_age",
                         GetSQLValueString( $_SESSION['anio1'], "int"), 
                         GetSQLValueString( $_SESSION['anio2'], "int"), 
                         GetSQLValueString($_SESSION['mes1'], "int"),  
                         GetSQLValueString($_SESSION['mes2'], "int"),    
                         GetSQLValueString($_SESSION['mes3'], "int"));  
                    
   
 $query_agentes=mysqli_query($conecta1,$string_agentes) or die (mysqli_error($conecta1));
    
    
 if (isset($_REQUEST['almacen'])) {
     
     $valor=$_REQUEST['almacen'];
    
   //  $string2= "SELECT * FROM almacenes where almacen='$valor'";
     $string2= sprintf("Select * from almacenes_proyeccion WHERE almacen=%s ",  
                    GetSQLValueString($valor, "text"));           
     
     $sql2=mysqli_query($conecta1,$string2) or die (mysqli_error($conecta1));
     $datos2=   mysqli_fetch_assoc($sql2);
   /*
      $string=("SELECT * FROM almacenes order by nombre_alma");
      $sql=mysqli_query($conecta1,$string) or die (mysqli_error($conecta1));
    */
     $_SESSION['cve_alma']=$valor;
     if ($_SESSION['cve_alma']=="NA"){
             $_SESSION['nombre_alma']="NA";
     }else{
         $_SESSION['nombre_alma']=$datos2['nombre_alma'];
     }
     
     /*
     $string=sprintf("select distinctrow(cve_alma), nom_alma from vista_proyeccion2 where (anio=%s or anio=%s) and (mes=%s or mes=%s or mes=%s) and cve_age=%s order by nom_age",
                         GetSQLValueString( $_SESSION['anio1'], "int"), 
                         GetSQLValueString( $_SESSION['anio2'], "int"), 
                         GetSQLValueString($_SESSION['mes1'], "int"),  
                         GetSQLValueString($_SESSION['mes2'], "int"),    
                         GetSQLValueString($_SESSION['mes3'], "int"),
                        GetSQLValueString($_SESSION['usuario_agente'], "int")); 
    */
     
     $string=sprintf("select * from almacenes_proyeccion where agente=%s order by nombre_alma",
                GetSQLValueString($_SESSION['usuario_agente'], "int")); 
    
    $sql=mysqli_query($conecta1,$string) or die (mysqli_error($conecta1));
 
     
 } 
 
 
 if (isset($_REQUEST['agentes'])) {
     
      $stringa= sprintf("Select * from relacion_gerentes WHERE cve_age=%s ",  
                     ////       GetSQLValueString($_SESSION['usuario_relaciongte'], "int")); 
                          GetSQLValueString($_REQUEST['agentes'], "int"));         
     
     $sqla=mysqli_query($conecta1,$stringa) or die (mysqli_error($conecta1));
     $datosa=   mysqli_fetch_assoc($sqla);
     
     
      $_SESSION["usuario_agente"]=$_REQUEST['agentes'];
      $_SESSION["usuario_nombre"]=$datosa['nom_age'];
      
      
      /*
     $string=sprintf("select distinctrow(cve_alma), nom_alma from vista_proyeccion2 where (anio=%s or anio=%s) and (mes=%s or mes=%s or mes=%s) and cve_age=%s order by nom_age",
                         GetSQLValueString( $_SESSION['anio1'], "int"), 
                         GetSQLValueString( $_SESSION['anio2'], "int"), 
                         GetSQLValueString($_SESSION['mes1'], "int"),  
                         GetSQLValueString($_SESSION['mes2'], "int"),    
                         GetSQLValueString($_SESSION['mes3'], "int"),
                         GetSQLValueString($_SESSION['usuario_agente'], "int")); 
    */
      
   $string=sprintf("select * from almacenes_proyeccion where agente=%s order by nombre_alma",
                GetSQLValueString($_SESSION['usuario_agente'], "int")); 
    
    $sql=mysqli_query($conecta1,$string) or die (mysqli_error($conecta1));
      
      
     
 }
 
 
 
 
 
   // echo $_SESSION['cve_alma']."<br>";
   // echo $_SESSION['usuario_agente'];
   
   $archivo=basename($_SERVER['PHP_SELF']);    
/////************Fin  Codigo Copiado*******************
?> 
<div  class="container">
  <?php    echo '<p class="text-center">Asesor  : '.$_SESSION["usuario_agente"].$_SESSION['usuario_nombre'].' </p> ';  ?>
    <div  class="row">    
    <!---<form class="form-inline" name="form1" action="<?php echo $archivo; ?>" method="post">-->
        <form class="form-inline" name="form1" action="crear13.php" method="post">
         <select class="form-control" name="agentes" id="agentes"  required onchange="this.form.submit()" >
        <option value="">--Seleccione Agente --</option>
         <?php

           while ($row4=mysqli_fetch_array($query_agentes))
             {
                 if ($row4['cve_age']==$_SESSION["usuario_agente"]){

                  echo '<option selected value="'.$row4['cve_age'].'">'.$row4['nom_age'].'</option>';	
                 }else{
                    echo '<option value="'.$row4['cve_age'].'">'.$row4['nom_age'].'</option>';	
                 }	
             }


         ?>
        
        </select>	    
            
            
            
    <select class="form-control"  name="almacen" required onchange="this.form.submit()">
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
       <div class="form-group">
        <label class ="form-control" for="nombre">Clave: *</label>
        <input class ="form-control" type="text" id="nombre" readonly name="nombre" size="6" value="<?php echo $valor; ?>"  />
       </div>  
        <br>
       
    </form>   
    </div>
    <div class ="row"> 
        <form  class="form-group" id="Formulario1" name="Formulario1" method="post" action="crear23_grilla.php"  >
      
		<input type="hidden" name="cve_alma" id="clave_cliente" value=" <?php echo $valor;?>"  />
		<input type="hidden" name="nombre_alma" value=" <?php echo $datos2['descripcion'];?>"  />
		<input type="submit"  class="btn btn-success" id="aceptar" name="aceptar" value="Continuar" /> 
        </form>
    </div>
    
    
    
</div> 
<?php  require_once('foot.php');?>  