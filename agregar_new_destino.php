<!-- ********	 INFORMACION ARCHIVO *****************
	Nombre  Archivo : agregar_new_destino.php 
 	Fecha  Creacion : 15/08/2016
	Descripcion  : 
            *Por definir  
	Modificado  Fecha  : 

///****Forma de llammar  a  agregar_new_destino.php 
   <a href="agregar_new_destino.php?cve_cte=<?php //echo $_POST['cliente']?>" target="_blank" 
                                   onClick="window.open(this.href, this.target, 'width=1000,height=600,scrollbars=yes'); return false;">
                                <span class="glyphicon glyphicon-plus"></span> New  Destino     
                                </a>

--> 
<?php
session_start ();
   $MM_restrictGoTo = "login.php";
 if (!(isset($_SESSION['usuario_valido']))){   
      header("Location: ". $MM_restrictGoTo); 
  exit;
}
//*** Agregamos las conexiones  
require_once ('Connections/conecta1.php');
///**Agregamos  funcion para validar
require_once ('funciones_valida_datos.php') ;
///****Obtenemos el  cve_cte  del  Url y validamos 
IF ($_GET['cve_cte'] != 'Cliente')
{
///*** Es  verdadero    Mostramos  la clave  
$clave_cliente  =  $_GET['cve_cte']; 
}
else 
{
 //Mostramos un alert   para cerrar  la ventana
    echo    '<script> alert("Lo sentimos Para  poder   agregarun nuevo destino debe de existir un cliente  Seleccionado");'
    .'window.close; '. '</script> ';
}

///* Hacemos Consulta para  los  estados tb  estados_mexico
$string_con_estados = sprintf("Select id,nom_cap FROM estados_mexico;");
$resul_con_estados = mysqli_query($conecta1,$string_con_estados ) or   die   (mysqli_errno($conecta1));
//***Fin  de Consulta  Estados
///***Capturamos  Datos 
IF(isset($_POST['guardar']))
{
 $clave_cliente  =
 $_calle =$_POST['calle'];
 $_colonia  =$_POST['colonia'];
 $_ciudad = $_POST['ciudad'];
 $_cp  = $_POST['cp'] ; 
 $_estado = $_POST['estado']; 
 $_pais  =$_POST['pais'];
 
 ///***Validamos la  Informacion
$_resval =  valida_datos_new_destino($_calle, $_colonia, $_ciudad, $_cp, $_estado, $_pais);
$_tiporesul = gettype($_resval) ;
 if($_tiporesul == 'boolean' ) 
 {
     //*****Realizamos la   Insercion de Datos
    ///  echo  '<script>alert("Todos  los  campos estan  bien");</script>';
     
 }
 else
 {// Mostrar los errores:
    for( $contador=0; $contador < count($_resval); $contador++ )
    {
       echo  $_resval[$contador]. "<br/>";    
    }
   ///  echo  '<script>alert("'.$contenedoror.'");</script>';
    
 }

 
 
 
}



?> 
<html>
    <head>
        <meta charset="UTF-8">
        <title>Nuevo  Destino</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!-- Loading Bootstrap -->
        <link href="select3/dist/css/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
         <!-- Loading Flat UI -->
        <link href="select3/dist/css/flat-ui.css" rel="stylesheet">
        <link href="select2/gh-pages.css" rel="stylesheet">
        <link href="select2/select2.css" rel="stylesheet">
        <link rel="shortcut icon" href="select3/dist/img/favicon.ico">
        <!--Agregamos estilo-->
        <style>
           body {
              min-height: 2000px;
              padding-top: 70px;
             }
             #save{
                margin-top: 50px;
             }
             
             
        </style>     
    </head>
    <body>
        <div class="container">
         <!--Punto de  inicio  de la   forma  para   agregar Un Nuevo Destino--> 
         <div id ="main" class ="col-lg-12 col-md-12 ">
         <!--Inicio Form-->
     <form  method="POST"   class ="form-horizontal"   role ="form">    
         <div  class ="panel panel-default">
             <!--Declaramos   Titulo ---> 
             <div  class ="pannel-heading">
                 <div  class ="col-lg-12  col-sm-12 col-md-12"> 
                     <div   class ="col-lg-7 col-sm-7 col-md-7"> 
                        <h3>Cliente : <?php echo $clave_cliente ; ?> </h3> 
                     </div> 
                     <div id ="btnDiv" class ="col-lg-4  col-sm-4  col-md-4">
                         <button   id ="save"  name ="guardar"  class="btn btn-success">
                                 Guardar
                                 <span class="glyphicon glyphicon-floppy-disk"></span>
                         </button>
                     </div> 
                 </div> 
              </div>
             <div   class ="panel-body">
                
                  
                      <!--Inicio FormaCONTENEDORA-->
                    <div class ="col-lg-12 col-md-12">
                     <!--Contenedor_01 de  Forma--> 
                    <div class ="col-lg-6 col-md-6"> 
                                <!--Calle -->
                                <div  class ="form-group"> 
                                    <div class ="col-lg-12 col-md-12  col-sm-12" >
                                      <label    class ="control-label" >Calle : </label> 
                                       <input type="text" class="form-control" name="calle" placeholder="Ingrese Calle">
                                   </div> 
                                </div>
                                <!----->
                                <!--Colonia  -->
                                <div   class="form-group">
                                      <div class ="col-lg-12 col-md-12  col-sm-12" >
                                         <label   class ="control-label">Colonia :</label>
                                         <input type  ="text"  class ="form-control"  name="colonia" placeholder="Ingrese Colonia"> 
                                    </div> 
                                </div> 
                                <!----> 
                                <!--Ciudad -->
                                <div   class ="form-group">
                                    <div class ="col-lg-12 col-md-12  col-sm-12" >
                                      <label  class ="control-label">Ciudad :</label>
                                      <input  type ="text"  class ="form-control" name ="ciudad"  placeholder="Ingrese Ciudad"> 
                                    </div> 
                                </div> 
                                <!---->
                               
                        </div> 
                       <!--FIN Contenedor_01 de  Forma--> 
                        <!--Contenedor_02 de  Forma--> 
                        <div class ="col-lg-6 col-md-6"> 
                             <!--C.p  -->
                                <div class ="form-group">
                                    <div class ="col-lg-6 col-md-6  col-sm-6" > 
                                        <label   class ="control-label">Cp :</label>
                                        <input  type ="text"  class ="form-control" name ="cp"  placeholder="Ingrese  C.p">
                                    </div> 
                                </div>
                                <!--Estado--> 
                                <div class ="form-group">
                                    <div class ="col-lg-12 col-md-12  col-sm-12" >
                                     <label    class ="control-label">Estado :</label>
                                     <!--Inicio Selectd Estado--->
                                    <div class="input-group input-group select2-bootstrap-prepend">
                                            <span class="input-group-btn">
                                                <button class="btn btn-default" type="button" data-select2-open="select2-button-addons-multi-input-group">
                                                    <span class="glyphicon glyphicon-search"></span>
                                                </button>
                                            </span>
                                            <select name="estado" class="form-control select2" id="estado" required>
                                                <option value="">Elija</option>
                                                <?php
                                                ///**Mostramos Todos los estados  que existen  en  la  tb_estados_mexico
                                                while ($file = mysqli_fetch_array($resul_con_estados)) {

                                                    echo '<option selected value="' . $file['id'] . '">' . utf8_encode($file['nom_cap']). '</option>';
                                                }
                                                ?>
                                            </select>
                                       </div>
                                   <!--FIN Selectd Estado--> 
                                    </div> 
                                </div>
                                <!--Pais--> 
                                <div class ="form-group">
                                    <div class ="col-lg-12 col-md-12  col-sm-12" >
                                      <label    class ="control-label">Pais :</label>
                                      <input  type ="text"  class ="form-control" name ="pais"  placeholder="Ingrese  Pais">     
                                    </div> 
                                </div>
                        </div>
                        <!--FIN Contenedor_02 de  Forma--> 
                        </div>
                        <!--Fin FormaCONTENEDORA--> 
                  
                  
             </div> 
         </div> 
           </form> 
         <!--Fin Form--->    
      
        </div> 
         <!--Fin Punto  de Inicio-->    
     <!--En el   archivo  foot.php es  el  que contine  el   fin del  archivo
     utilizamos  la  misma  base del   proyecto--> 
    <?php require 'foot.php';   ?>

