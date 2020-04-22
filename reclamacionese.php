<?php
/*
********   INFORMACION ARCHIVO ***************** 
  Nombre  Archivo : reclamacionese.php 
  Fecha  Creacion : 20/09/2016
  Descripcion  : 
  Copia de archivo  reclamacionese.php    Proyecto  Pedidos
 *          
  Modificado  Fecha  : 
 *                  17/02/2017   Agregamos la modificacion para  subir  archivos   
 *                               del  escrip    de  reclamacionesp.php  
 *                                          
*/
///****Inicio   Librerias  Utilizadas  en Cronos
///****Cabecera Cronos 

session_start ();
$MM_restrictGoTo = "login.php";
if (!(isset($_SESSION['usuario_valido']))){
header("Location: ". $MM_restrictGoTo);
exit;
}
if ( $_SESSION["usuario_agente"] >= 400 && $_SESSION["usuario_agente"] <=450 ){ 

  ///****Cabecera Cronos
              require_once('heder_desarrollo.php');
             

}else{

 

  require_once('header.php');
}

///***Conexion  sap
require_once('conexion_sap/sap.php');
/*****Sintetizador de  Datos en el proyecto  pedidos   se  utiliza el   
formtato_datos2.php   pero     se  analiso   y son   identicos  los  archivos 
 por lo que se   dejo el  formato_datos.php  
 *  */
require_once('formato_datos.php');   //para evitar poner la funcion de conversion de tipos de datos
///***Conexion Mysql  
require_once('Connections/conecta1.php');
///*/**********
require_once('correos_array.php');

//$check = $_REQUEST['check'];
$check = isset($_POST['check'])  ?  '1'  :  '0'  ; 
//echo 'P'.$check;
if ($_SESSION["usuario_agente"]==187 || ($_SESSION["usuario_agente"] >= 400 && $_SESSION["usuario_agente"] <=450 ) ){  //Agente 187 Servicio al cliente
   $consulta_cliente="SELECT * FROM plataforma_ctesagtes order by CardName ASC";
   //$factura = "SELECT DISTINCT(f.n_factura) as numero_factura, t.empresa, f.n_remision, f.n_agente FROM factura f JOIN transportes t ON f.id_transporte = t.id ORDER BY n_factura ASC;";
               
    
}else {
 /*   
 $consulta_cliente = sprintf("SELECT * FROM plataforma_ctesagtes WHERE SlpCode = %s ORDER BY CardName ASC",
         GetSQLValueString($_SESSION["usuario_agente"], "int"));
*/ 
 
 $consulta_cliente = sprintf("SELECT * FROM plataforma_ctesagtes WHERE SlpCode = %s  or U_agente2=%s ORDER BY CardName ASC",
     GetSQLValueString($_SESSION["usuario_agente"], "int"),
    GetSQLValueString($_SESSION["usuario_agente"], "int"));
 /*
 $factura = sprintf("SELECT DISTINCT(f.n_factura) as numero_factura, t.empresa, f.n_remision, f.n_agente FROM factura f JOIN transportes t WHERE f.id_transporte = t.id AND f.n_agente = %s ORDER BY n_factura ASC;",
  GetSQLValueString($_SESSION["usuario_agente"], "int"));
 $sql_factura = mysqli_query($conecta1, $factura) or die (mysqli_error($conecta1));*/
}

 $sql_cliente=mssql_query($consulta_cliente);
 
 
  
 
 
 function tipo2($archivo2){
        $ext2= substr(strrchr($archivo2, '.'), 1);
        
        return $ext2;
        
    }
    

 IF (isset($_POST['cliente'])){
     
          
     $cliente = $_POST['cliente']; 
     $string_cliente_guardar=sprintf("SELECT * FROM plataforma_ctesagtes WHERE CardCode = %s",
          GetSQLValueString($cliente, "text"));
         $sql_cliente_guardar=mssql_query($string_cliente_guardar);
         $registro4=  mssql_fetch_array($sql_cliente_guardar);
         $algo = $registro4['SlpName'];
         $algo2 = $registro4['SlpCode'];
         $algo3 = $registro4['CardName'];
         $_SESSION['clientesesion'] = $registro4['SlpCode'];
         
    /* $factura = sprintf("SELECT DISTINCT(f.n_factura) as numero_factura, t.empresa, f.n_remision, f.n_agente FROM factura f JOIN transportes t WHERE f.id_transporte = t.id AND   f.cve_cte = %s    ORDER BY n_factura ASC",
        
      GetSQLValueString($cliente, "text")
      );
       $sql_factura = mysqli_query($conecta1, $factura) or die (mysqli_error($conecta1)); */

       ///*******CAMBIO   BUSQUEDA EN  SAP DE   FACTURA 

$factura = sprintf(" SELECT  DISTINCT (NUM_DOCTO) as numero_factura
      FROM VW_VENTAS_DC_FILTROS WHERE  COD_CLIENTE =%s",
         GetSQLValueString($cliente, "text")
      );
       $sql_factura = mssql_query( $factura) ;


         
  
}
IF (isset($_POST['factura'])){
         
    $nunfactura = $_POST['factura'];

     $factura = sprintf("SELECT DISTINCT(f.n_factura) as numero_factura, t.empresa, f.n_remision, f.n_agente FROM factura f JOIN transportes t WHERE f.id_transporte = t.id  AND f.n_factura = %s ORDER BY numero_factura ASC;",
             GetSQLValueString($nunfactura, "int"));
     
       $resultadofactura = mysqli_query($conecta1, $factura) or die (mysqli_error($conecta1));
    $variablefactura = mysqli_fetch_assoc($resultadofactura);
    $fact1 = $variablefactura['empresa'];
    $fact2 = $variablefactura['n_remision'];

$cliente = $_POST['cliente']; 
     $factura = sprintf(" SELECT  DISTINCT (NUM_DOCTO) as numero_factura
      FROM VW_VENTAS_DC_FILTROS WHERE  COD_CLIENTE =%s",
         GetSQLValueString($cliente, "text")
      );
       $sql_factura = mssql_query( $factura);


         
    
    
}
////****Funcion para  Generar la  Ruta del Archivo.
function    Get_New_Ruta_Archivo ($tipo_archivo,$tipo_for_office)
{
        $NombreOr ="";
        $ruta = '../pedidos/upload/'; ////'../pedidos/upload/';
        $nom_temporal =time(); 
        /* Entiendase  como 
         *      $RESUL_FUNCTION['Office_elem']=0 El elemento no es  un Formato  Office 
                $RESUL_FUNCTION['Office_elem']=1; El elemento es  un Formato  Office
         *          */
        $RESUL_FUNCTION['Office_elem']=0;   
       if(strpos($tipo_archivo, "pdf"))
       {
            $NombreOr ="R".$nom_temporal.'.pdf'; ///$_FILES['ARCH']['name'];
       }else{
             
                if(strpos($tipo_archivo, "jpg"))
                 {
                      $NombreOr ="R".$nom_temporal.'.jpg';
                 }else{
                     if(strpos($tipo_archivo, "jpeg"))
                     {
                         $NombreOr ="R".$nom_temporal.'.jpg';
                     }
                     else {
                             if(strpos($tipo_archivo,"jpe"))
                             {
                                 $NombreOr ="R".$nom_temporal.'.jpg';
                             }
                             else {
                                     if(strpos($tipo_archivo, "jfif"))
                                     {
                                         $NombreOr ="R".$nom_temporal.'.jpg';
                                     }
                                     else
                                     {
                                         if(strpos($tipo_archivo, "png")||strpos($tipo_archivo, "PNG"))
                                         {
                                            $NombreOr ="R".$nom_temporal.'.png';
                                         }else 
                                         {   
                                             ////*****Inicio  Validacion  Documentos office
                                                ///**  Validacion Documentos Word
                                              if($tipo_for_office == "dot"||$tipo_for_office == "doc"||$tipo_for_office == "docx"||$tipo_for_office =="dotm"||$tipo_for_office =="docm"||$tipo_for_office =="dotx"||$tipo_for_office =="dotm")
                                                {
                                                   $NombreOr ="R".$nom_temporal.'.'.$tipo_for_office;
                                                   $RESUL_FUNCTION['Office_elem']=1;
                                                }else {
                                                    ///****Validacion  Documento  Excel
                                                      if($tipo_for_office == "xlsx" || $tipo_for_office=="xlsm"||$tipo_for_office=="xltx"||$tipo_for_office=="xltm"||$tipo_for_office=="xlam")
                                                      {
                                                            $NombreOr ="R".$nom_temporal.'.'.$tipo_for_office;
                                                            $RESUL_FUNCTION['Office_elem']=1;
                                                      }else {
                                                    ///****Validacion  Documento  Power Point 
                                                            if($tipo_for_office == "ppa"||$tipo_for_office=="pot"||$tipo_for_office=="pps"||
                                                                    $tipo_for_office=="xml"||$tipo_for_office=="pptm"||$tipo_for_office== "pptx"||
                                                                    $tipo_for_office=="xlam"||$tipo_for_office=="potm"||$tipo_for_office=="ppam"||
                                                                    $tipo_for_office=="ppsx"||$tipo_for_office=="ppsm"||$tipo_for_office== "sldx"||
                                                                    $tipo_for_office=="sldm"||$tipo_for_office=="thmx")
                                                            {
                                                             $NombreOr ="R".$nom_temporal.'.'.$tipo_for_office;
                                                             $RESUL_FUNCTION['Office_elem']=1;
                                                            }
                                                      }
                                                }
                                             //////*****Fin  Validacion Documento  Office
                                         }
                                     }  
                             } 
                     }   

                 } 
             
         }
        $RESUL_FUNCTION['NOM_NEW']= $NombreOr;
        $RESUL_FUNCTION['Root']= $ruta.$NombreOr;
        $Destino  = $RESUL_FUNCTION;
    
   return   $Destino;
}
///********************************************************

  IF (isset($_POST['guardar'])){
       ////////////////////////ARCHIVO/////////////////////////////////  
   $ext2=  tipo2(basename($_FILES['archivo']['name']));
   $peso2 = $_FILES['archivo']['size'];
   
  // $nomnbrefile = $_FILES['archivo']['name'];

     ////******Varible Control Archivo
     $est_archivo = 0 ;/// Estodo  del  Archivo Entiendase    $est_archivo = 0 => Error  en el Archivo $est_archivo = 1 => Archivo Correcto 
     $mens_archivo="Correcto" ;/// Mensage Estado  Archivo 
     ////*********************************************************************************
     /*
    IF (($ext2=="doc" || $ext2=="docx" || $ext2=="pdf" || $ext2=="xls" || $ext2=="xlsx"
 || $ext2=="ppt" || $ext2=="pptx") && ($peso2 <= 10000000)){
        
        $name_temporal2=$_FILES['archivo']['tmp_name'];
        $uploaddir2 = 'upload/';
        $nowtime2 = time();$name_temporal2.
        $uploadfile2=$uploaddir2."R".$nowtime2.".".$ext2;
        $nombrefile2=$nowtime.".".$ext2;
        move_uploaded_file($_FILES['archivo']['tmp_name'], $uploadfile2);
      $concatenado2 = "upload/".$nombrefile2;
    
    } else {
        $concatenado2= "";
    } */
      
     /////***Inicio Modificacion  17/02/2017 *****************************************

     If($_FILLES['archivo']['tmp_name']==UPLOAD_ERR_OK)
     {
         //////////////////////ARCHIVO/////////////////////////////////  
            $tipo_for_office=  tipo2(basename($_FILES['archivo']['name'])); ////*** Variable  encargada de obtener el  tipo de extencion de los  Office
            $peso2 = $_FILES['archivo']['size'];
            //$name_temporal2=$_FILES['archivo']['tmp_name'];
            ///////
            $temporal = $_FILES['archivo']['tmp_name'];
            $tipo_archivo = $_FILES['archivo']['type'];
            ////****
            $New_ruta_and_vali =  Get_New_Ruta_Archivo($tipo_archivo,$tipo_for_office) ;
             
            ///Validamos  el Archivo
            if( strpos($tipo_archivo, "pdf")||strpos($tipo_archivo, "jpg")||strpos($tipo_archivo, "png")||strpos($tipo_archivo, "jpeg")||strpos($tipo_archivo, "jpe")
                    ||$New_ruta_and_vali['Office_elem']==1)
            {   
                
              move_uploaded_file($temporal,$New_ruta_and_vali['Root'] );   
               $est_archivo =1 ;
               
               
            }
            else
            {
                $est_archivo= 0 ;
              $mens_archivo="Error lo sentimos existen problemas con el archivo Por lo  que su  reclamo NO FUE CAPTURADO";
               
            }

         
     }
    if ($_FILES['archivo']['error']=='') //Si no existio ningun error, retornamos un mensaje por cada archivo subido
     {   
         if( $est_archivo==1 ){
         $mens_archivo="Archivo Agregado con Exito" ;
         }
     }
     if ($_FILES['archivo']['error']!=''||$est_archivo== 0)//Si existio algún error retornamos un el error por cada archivo.
     {
         $mens_archivo="Error lo sentimos existen problemas con el archivo Por lo  que su  reclamo NO FUE CAPTURADO";
     }
     
     
      $cliente = $_POST['cliente'];
      $CardName = $_POST['CardName'];
      $SlpCode = $_POST['SlpCode'];
      $SlpName = $_POST['SlpName'];
      $fecha = $_POST['fechareclamo'];
      $representante = $_POST['representante'];
      $email = $_POST['email'];
      $telefono = $_POST['telefono'];
      $remision = $_POST['n_remision'];
      $factura = $_POST['factura'];
      $transportacion = $_POST['transportacion'];
      $reclamacion = $_POST['reclamacion'];
      $motivo = $_POST['motivo'];
      $contacto = $_POST['contacto'];
      
       ////////////////////// 5. Indique lo que requiere el cliente //////////////////Esto  es  Una  Prueba Para el  Modulo de  Entregas  Saludos  17/02/2017
      $requiere=$_POST['requiere'];
      
if ($_SESSION["usuario_agente"] >= 400 && $_SESSION["usuario_agente"] <=450 )
{
$insert = sprintf("INSERT INTO reclamoe SET cve_cte = %s, nom_cte=%s, n_agente=%s, nom_agente=%s, fecha=%s, email=%s, n_remision=%s, n_factura=%s, transporte=%s, motivo=%s, observacion=%s, documento=%s, telefono=%s, contacto=%s, respuesta=%s, incidencia=%s ,num_desarrollador =%s ",
                 GetSQLValueString($cliente, "text"),
                 GetSQLValueString($CardName, "text"),
                 GetSQLValueString($SlpCode, "int"),
                 GetSQLValueString($SlpName, "text"),
                 GetSQLValueString($fecha, "date"),
                 GetSQLValueString($email, "text"),
                 GetSQLValueString($remision, "int"),
                 GetSQLValueString($factura, "int"),
                 GetSQLValueString($transportacion, "text"),
                 GetSQLValueString($motivo, "text"),
                 GetSQLValueString($reclamacion, "text"),
                 GetSQLValueString('upload/'.$New_ruta_and_vali['NOM_NEW'], "text"), ///$uploadfile2
                 GetSQLValueString($telefono, "text"),
                 GetSQLValueString($contacto, "text"),
                 GetSQLValueString($requiere, "text"),
                 GetSQLValueString($check, "text"),
                 GetSQLValueString($_SESSION["usuario_agente"], "int"));
                 


}else{

      $insert = sprintf("INSERT INTO reclamoe SET cve_cte = %s, nom_cte=%s, n_agente=%s, nom_agente=%s, fecha=%s, email=%s, n_remision=%s, n_factura=%s, transporte=%s, motivo=%s, observacion=%s, documento=%s, telefono=%s, contacto=%s, respuesta=%s, incidencia=%s",
                 GetSQLValueString($cliente, "text"),
                 GetSQLValueString($CardName, "text"),
                 GetSQLValueString($SlpCode, "int"),
                 GetSQLValueString($SlpName, "text"),
                 GetSQLValueString($fecha, "date"),
                 GetSQLValueString($email, "text"),
                 GetSQLValueString($remision, "int"),
                 GetSQLValueString($factura, "int"),
                 GetSQLValueString($transportacion, "text"),
                 GetSQLValueString($motivo, "text"),
                 GetSQLValueString($reclamacion, "text"),
                 GetSQLValueString('upload/'.$New_ruta_and_vali['NOM_NEW'], "text"), ///$uploadfile2
                 GetSQLValueString($telefono, "text"),
                 GetSQLValueString($contacto, "text"),
                 GetSQLValueString($requiere, "text"),
                 GetSQLValueString($check, "text"));





}

         
                 
                
     
  ////***** Validamos 
   /*  if($est_archivo==1  && $_FILLES['archivo']['tmp_name']==UPLOAD_ERR_OK )
     {*/               
        
        $resultado = mysqli_query($conecta1, $insert) or die (mysqli_error($conecta1));

        //Script para mandar correo
        if(empty($check) || $check==0)
        {
                  $fromname="Reclamaciones de Servicio/Entrega"; 
                  $subject="Nueva Reclamación de Servicio/Entrega";
                 // $destinatario="egonzalez@agroversa.com.mx";
                  $destinatario="emena@agroversa.com.mx";
                  $destinatario2[0]="aamaya@agroversa.com.mx";  //asistente
                  $destinatario2[1]="egonzalez@agroversa.com.mx";  //con copia a Sistemas

                  $mensaje="<p> Existe una nueva reclamación por parte de ".$representante."</p>";
                  $mensaje.="<p><a href='http://www.verur.com.mx/sistemas/pedidos/'> da clic aquí para ir al sitio:</a> </p>";
                   correos($fromname,$subject,$destinatario,$destinatario2,$mensaje);   //Mandar correo
              ////----- 
        }
        elseif(isset($check) || $check==1)
        {
            $fromname="Incidencias de Servicio/Entrega"; 
                  $subject="Nueva Incidencia de Servicio/Entrega";
                 // $destinatario="egonzalez@agroversa.com.mx";
                  $destinatario="emena@agroversa.com.mx";
                  $destinatario2[0]="aamaya@agroversa.com.mx";  //asistente
                  $destinatario2[1]="egonzalez@agroversa.com.mx";  //con copia a Sistemas

                  $mensaje="<p> Existe una nueva incidencia por parte de ".$representante."</p>";
                  $mensaje.="<p><a href='http://www.verur.com.mx/sistemas/pedidos/'> da clic aquí para ir al sitio:</a> </p>";
                   correos($fromname,$subject,$destinatario,$destinatario2,$mensaje);   //Mandar correo
              ////----- 
        }


    // }


  }
$fecha_hoy=date("Y-m-d");
///****FIN    Librerias  Utilizadas  en Cronos 
?> 
<div  class="container"> 
  </div>
    <script> 
    function folio(){
    <?php 
      $folio = "SHOW TABLE STATUS like 'reclamoe'";
         $folioquery = mysqli_query($conecta1, $folio) or die (mysqli_error($conecta1));
         $folioassoc = mysqli_fetch_assoc($folioquery);
         $foliotal = $folioassoc['Auto_increment'];
    
    ?>
            
            alert ("Reclamación enviada con folio: <?php echo $foliotal ?>");
    
    }
    
</script>
<style>
    

.ConFrm {
    background: #fff;
    height: 500px;
    position: relative;
    top: -250px;
}
.ContInputs{
     background: #fffdfde6;
     height:auto;/*500px;*/
     top :50px;
     border-radius: 24px;
     box-shadow: -5px 7px 57px #999
    
}


.ldtrans{
    
    opacity: 0.5;
}
.contTITLE{
       position: absolute;
    top: -48px;
    left: 16px;
}
.titlemain{
    font-size: 50px; 
    color:white;
}
.contnfileFinal {
   margin-top: 50px;
  margin-bottom: 50px;
}
.btn-file {
  position: relative;
  overflow: hidden;
  }
.btn-file input[type=file] {   
    position: absolute;
    top: 0;
    right: 0;
    min-width: 100%;
    min-height: 100%;
    font-size: 100px;
    text-align: right;
    filter: alpha(opacity=0);
    opacity: 0;
    outline: none;
    background: white;
    cursor: inherit;
    display: block;
   /* background-image:url('images/uppercase.png');*/
}
/*.txtcoment{
    overflow: hidden;
}*/
.moneyapli{
  -moz-appearance: textfield;
  text-align: end;   
}
.errost.modal-header {
    background-color: #f00c;
    box-shadow: 5px 2px 4px;
}
.saveelem.modal-header { 
        background-color: #1db46fcc;
        box-shadow: 5px 4px 4px;
}

.titleerr {
    font-size: xx-large;
    color: white;
}
/***************/

.dumdivstyle.jumbotron {
    /*background: #000;*/
    height: 400px;
   /*background-image:url('images/IMGJUMBO.jpg');*/
   position: relative;
   top:-17px;
   box-shadow: -2px 11px 10px #999; 
}
.slider {
     width: 100%;
  margin: auto;
  overflow: hidden;

  position: relative;
top:-17px;

}

.slider ul {
  display: flex;
  padding: 0;
  width: 400%;
  
  animation: cambio 20s infinite alternate linear;
  position: relative;
 top:-17px;

}

.slider li {
  width: 100%;
  list-style: none;
    position: relative;
top:-17px;

}

.slider img {
  width: 100%;

   position: relative;
   top:-17px;

}

@keyframes cambio {
  0% {margin-left: 0;}
  20% {margin-left: 0;}
  
  25% {margin-left: -100%;}
  45% {margin-left: -100%;}
  
  50% {margin-left: -200%;}
  70% {margin-left: -200%;}
  
  75% {margin-left: -300%;}
  100% {margin-left: -300%;}
}

.contenderforms{
 /*background: #000;*/
    /*height: 400px;*/
   /*background-image:url('images/IMGJUMBO.jpg');*/
 
     position: relative;
  bottom: 605px;
   box-shadow: -2px 11px 10px #999; 
}



</style>


<!--<div class="dumdivstyle  jumbotron">--> 
    <div class="slider">
      <ul>
        <li>
  <img src="/arte_cronos/img_vrs/zarzamoraVRS1.png" alt="">
   <img src="arte_cronos/img_vrs/zarzamoraVRS1.png" alt="">
 </li>
        <li>
  <img src="arte_cronos/img_vrs/zarzamoraVRS2.png" alt="">
</li>
        <li>
 <img src="arte_cronos/img_vrs/zarzamoraVRS3.png" alt="">
</li>
        <li>
  <img src="arte_cronos/img_vrs/zarzamoraVRS4.png" alt="">
</li>
      </ul>

</div>

<div class ="contenderforms"> 

  <form method="post" enctype="multipart/form-data" onsubmit="folio()">
    <fieldset class="fieldset">
   
<!-- <image  src="image</IMGJUMBO.jpg"  >--> 
    <div  id="mainform">  
        <div class="contTITLE  col-lg-12 col-sm-12  col-xs-12">
          <strong class="titlemain">
            Reclamaciones Servicio
     
          </strong>
         
        </div> 
        <div class="  col-lg-12 col-sm-12  col-xs-12" >
        <div class="ldtrans col-lg-3 col-sm-3 col-xs-12" ></div>
        <div class="ContInputs  col-lg-6 col-sm-6 col-xs-12" >
          <!--**********Inicio  Contenedor Informacion dentro del Recuadro  blanco***********<?php /// $dt = new DateTime();  echo $dt->format("d/m/Y") ;  ?>***********-->
          <div class="col-lg-12 col-sm-12 col-xs-12"> 
            <strong>Cuestionario de Atención a Reclamos (Servicios / Entregas) FTO-399  Rev.1</strong> 
          </div>
            <div class="col-lg-12 col-sm-12 col-xs-12"> 
                    <div  class="col-lg-6 col-sm-12 col-xs-12">
                         <legend>Información General</legend>
        <?php if($_SESSION["usuario_agente"]==187){  ?>
        <h4><label style="color: green">INCIDENCIA: <input type="checkbox" name="check" <?php if($check==1){ ?> checked <?php } ?> ></label></h4>
        <?php
        }
        ?>
                    </div>
                       
                    <div  class="col-lg-6 col-sm-12 col-xs-12"> 
                         <label >Fecha: </label><input class="form-control" readonly type="date" name="fechareclamo" value="<?php echo $fecha_hoy;?>">
                    </div>
            </div>
          <div class="row"> </div>
            <div class="col-lg-12 col-sm-12 col-xs-12">
               
                  <strong>Cliente: </strong>
                      <div class="input-group input-group select2-bootstrap-prepend">
                <span class="input-group-btn">
                    <button class="btn btn-default" type="button" data-select2-open="select2-button-addons-multi-input-group">
                        <span class="glyphicon glyphicon-search"></span>
                    </button>
                </span>
                      <select required class="form-control select2"   name="cliente" title="Cliente" onchange="this.form.submit()">
                          <option value="">Elija</option>
                        <?php

                        WHILE ($registro3=  mssql_fetch_array($sql_cliente)){
                        IF($cliente == $registro3['CardCode']){
                             echo '<option selected value="'.$registro3['CardCode'].'">'.utf8_encode($registro3['CardName']).'</option>';
                            
                               
                        }else{
                             echo '<option value="'.$registro3['CardCode'].'">'.utf8_encode($registro3['CardName']).'</option>';
                        }

                                

                          }
                          ?>

                      </select>
                    </div>
                
            </div>
            <br><br>
            <div class="col-lg-12 col-sm-12 col-xs-12">
                <div class="col-lg-12 col-sm-12 col-xs-12">
                         <strong>Representante: </strong>
                         <input required class="form-control"    type="text" name="representante" value="<?php echo $algo; ?>">
      


                </div>
                <div class="col-lg-12 col-sm-12 col-xs-12"></div>
                <div class="col-lg-12 col-sm-12 col-xs-12">
                           <strong>Factura: </strong> 
                                         <div class="input-group input-group select2-bootstrap-prepend">
                <span class="input-group-btn">
                    <button class="btn btn-default" type="button" data-select2-open="select2-button-addons-multi-input-group">
                        <span class="glyphicon glyphicon-search"></span>
                    </button>
                </span>
                    
                           <select required  class="form-control"  name="factura" title="Factura" onchange="this.form.submit()">
                                 
                                  <option value="0">No Aplica</option>
                                <?php

                                WHILE ($registro5=  mssql_fetch_array($sql_factura)){
                                IF($nunfactura == $registro5['numero_factura']){
                                    
                                     echo '<option selected value="'.$registro5['numero_factura'].'">'.$registro5['numero_factura'].'</option>';
                                }else{
                                    
                                    echo '<option value="'.$registro5['numero_factura'].'">'.$registro5['numero_factura'].'</option>';
                                }
                                
                                }
                                ?>
                            
                            </select>
                             </div>
                </div>
            </div>
            <div class="col-lg-12 col-sm-12 col-xs-12">


        
       
                <div class="col-lg-5 col-sm-12 col-xs-12">
                        <label>E-mail (Cliente): </label><input required  class="form-control"  type="email" name="email">
                </div>
                <div class="col-lg-1 col-sm-1 col-xs-1"></div>
                <div class="col-lg-5 col-sm-12 col-xs-12">
                     <label>Teléfono (Cliente): </label><input required class="form-control" type="text" name="telefono"> 
                </div>
            </div>
             <div class="col-lg-12 col-sm-12 col-xs-12">
                  <label>Contacto de cliente: </label><input required class="form-control" type="text" name="contacto">
         
            </div>
            <div class="col-lg-12 col-sm-12 col-xs-12">
               
      
                 <div class="row">
                      <div class=" col-lg-4 col-sm-12 col-xs-12">
                           <label>Remisión: </label><input class="form-control" type="text" name="remision" value="<?php echo $fact2; ?>">
                     </div>
                      <div class=" col-lg-8 col-sm-12 col-xs-12">
                            <label> Motivo </label>
                            <select  required  class="form-control"   name="motivo" title="motivo">
                                <option value="">Elija</option>
                               <option value="0">Faltante de Caja/Saco</option>
                                <option value="1">Daño (cajas/envases)</option>
                                <option value="2">Tardanza en la entrega (fuera de política)</option>
                                <option value="3">Comunicación interna</option>
                                <option value="4">Entrega incorrecta</option>
                                <option value="5">Cobranza</option>
                         
                                <option value="6">Faltante de Producto en Caja Cerrada</option>
                            
                            </select>
                     </div> 


                 </div>
                 <div class="row">
                     <label>Transportación: </label><input  class="form-control"  type="text" size="80" name="transportacion" value="<?php echo $fact1; ?>">
        

                 </div>
                 <div class="row">
                      <label>Detalle Reclamación</label>
                      <textarea rows="4" class="form-control" cols="40" name="reclamacion" ></textarea>

                 </div>



            </div>
            <div class="  col-lg-12 col-sm-12 col-xs-12"> <!--contnfileFinal-->
                <div class=" col-lg-12 col-sm-12 col-xs-12">
                  <div class="row"><legend>IV. Indique lo que requiere el cliente </legend>
                            </div> 
                     <div class=" col-lg-8 col-sm-12 col-xs-12">
                          <fieldset>
                           

                           <label> Requiere </label>
                          <select  required class="form-control" name="requiere" title="requiere">
                              <option value="">Elija</option>
                              <option value="0">Reposición</option>
                              <option value="1">Nota de crédito</option>
                              <option value="2">Devolución</option>
                              <option value="3">Inspección</option>
                              <option value="4">Cambio físico</option>

                          
                          </select><br><br>
                           
                          </fieldset>
                     </div>
                    <div class=" col-lg-4 col-sm-12 col-xs-12">
                       <!-- <button id="readXML" type="button"  class="btn btn-info"   >Leer XML</button>-->
                     </div>
                </div>
                <div class=" col-lg-12 col-sm-12 col-xs-12">
                    <label for="archivo">Archivo (Word,Excel,PowerPoint o PDF maximo 10Mb)</label>
                    <input  class="form-control" type="file" name="archivo" value="" /><br><br>
        
        
                     <input type='submit'  class="form-control  btn-success" value='Guardar'  name="guardar"/> <br>
                </div>

            </div> 

            </div>

           <!--**********Fin Contenedor Informacion dentro del Recuadro  blanco**********************-->
        </div>
        <!---******************************************************-->
        
        <div class="ldtrans col-lg-3 col-sm-3 col-xs-12" ></div>
    </div>
    
     
    
    
    
</div>
</div>





<!-- ********************INICIO RECLAMOS***************************************---->

       

    
       
       
       
        
        
        



        <input type="hidden" required name="SlpName" value="<?php echo $registro4['SlpName']; ?>"/>
        <input type="hidden" required name="SlpCode" value="<?php echo $registro4['SlpCode']; ?>"/>
        <input type="hidden" required name="CardCode" value="<?php echo $_POST['cliente']; ?>"/>
        <input type="hidden" required name="CardName" value="<?php echo $registro4['CardName']; ?>"/>
        
           <input type="hidden" required name="empresa" value="<?php echo $registro5['empresa']; ?>"/>
        <input type="hidden" required name="n_remision" value="<?php echo $variablefactura['n_remision']; ?>"/>
        <input type="hidden" required name="inputfactura" value="<?php echo $_POST['factura']; ?>"/>
    </fieldset>
</form>
</div>
 <?php require_once('foot.php');?>     