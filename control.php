<?php
/*
********	 INFORMACION ARCHIVO ***************** 
	Nombre  Archivo : control.php 
 	Fecha  Creacion : 14/10/2016 
	Descripcion  : 
	Copia  del  Proyecto   Proyeccion Alias presupuestos 
 *      Nombre  del  Archivo  Origen : control.php  
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
///*********Inicio  Codigo  Copiado********************
 mysqli_select_db($conecata1, $database_conecta1);

    if (isset($_REQUEST['guardar'])){
        
        if ($_POST['asesor']==1){
            $asesor=1;
        }else{
            $asesor=0;
        }
        
        
         if ($_POST['gerente']==1){
            $gerente=1;
        }else{
            $gerente=0;
        }
        
         $string_actualiza=sprintf("UPDATE configurar set capturar=%s, capturarg=%s",
                            GetSQLValueString($asesor, "int"), 
                            GetSQLValueString($gerente, "int")); 
         $sql_actualizar=mysqli_query($conecta1,$string_actualiza) or die (mysqli_error($conecta1));
          $mensaje="DATOS GUARDADOS";
    }
   
   $string="Select * from configurar limit 1";
   $sql_string=mysqli_query($conecta1,$string) or die (mysqli_error($conecta1));
   $datos=  mysqli_fetch_assoc($sql_string);
///*********Fin   Codigo Copiado*****************
 ?> 
<style>
    
    .center_form{     
       margin-left: 400px;  
    }
    .mensage_save {
            font-style: italic;
            font-size: large;
    } 
    
</style> 
    <form class="form-group " name="form1" method="POST" action="control.php">
        <div class="row center_form"> 
        <div  class="form-group">   
            <input type="checkbox" id="asesor" name="asesor" <?php  if ($datos['capturar']==1){ echo 'checked';}?> value="1"/>
            <label for="checkbox1">Acceso a Asesor para Modificar</label><br>
            <input type="checkbox" id="gerente" name="gerente" <?php  if ($datos['capturarg']==1){ echo 'checked';}?> value="1"/>
            <label for="checkbox2">Acceso a Gerente para Modificar</label>
        </div> 
        <div  class="form-group">
             <input  class ="btn  btn-success"  type="submit" id="guardar" name="guardar"   value="Guardar" />
            <?php echo "<p class='text-senter mensage_save'>".$mensaje. "</p>"; ?>
            
        </div>
        </div>
       
    </form> 
 
<?php  require_once('foot.php');?> 
