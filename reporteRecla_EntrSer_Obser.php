<?php
/////*reporteRecla_EntrSer_Obser.php 

/*
********	 INFORMACION ARCHIVO ***************** 
	Nombre  Archivo : reporteRecla_EntrSer_Obser.php 
 	Fecha  Creacion : 15/06/2017
	Descripcion  : 
               Pagina para  mostrar     las  Observaciones  
	Modificado  Fecha  : 
 *        
*/   
///**Librerias  para conexion  a  Mysql
 require_once('Connections/conecta1.php');
//para evitar poner la funcion de conversion de tipos de datos
 require_once('formato_datos.php');

?> 
<html>
    <head>
        <!--****Inicio  Librerias******************************************************-->
        <!-- Bootstrap Core CSS -->
        <link href="bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
        <!--JQuery jquery.min.js" -->
        <script src="bower_components/jquery/dist/jquery.min.js"></script> 
        <!-- Bootstrap js -->
        <script src="bower_components/bootstrap/dist/js/bootstrap.min.js" ></script> 
        <link rel="stylesheet" href="bower_components/bootstrap-select-1_11_0/dist/css/bootstrap-select.min.css">

     
     
    </head>
    <style>
      .obser_div{  
           -webkit-box-shadow: -6px 20px 40px -6px rgba(0,0,0,0.64);
            -moz-box-shadow: -6px 20px 40px -6px rgba(0,0,0,0.64);
            box-shadow: -6px 20px 40px -6px rgba(0,0,0,0.64);
                }
    </style>
        
    <body class="container">
        <br>
        <br>
        <div  class="col-lg-12  col-sm-12 col-md-12">
            <div   class="col-lg-2  col-sm-2" > 
            </div> 
            <div  id="cont_OBs" class="col-lg-10 col-sm-10 obser_div"> 
               <h3>Observaciones Reclamo</h3>
                <?php  
                     ///****Codigo  para  Mostrar  las  Observaciones del  cliente 
                     $nume_pagare = filter_input(INPUT_GET, 'NUM');
                     $cadena_str = sprintf("SELECT * from   reclamoe   where   id_reclamoe=%s",
                        GetSQLValueString($nume_pagare,"int"));
                     $ejecu_qery = mysqli_query($conecta1,$cadena_str)or die (mysqli_error($conecta1));
                     $resqe = mysqli_fetch_array($ejecu_qery);
                     
                     echo   '<br>';
                     echo  '<p>'.$resqe['observacion'].'<p>'; 
        
               ?> 
               <br> 
               <br>
            </div>
        </div>   
        <br> 
        <br> 
    </body> 
</html>