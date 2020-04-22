<?php 
/*
********   INFORMACION ARCHIVO ***************** 
  Nombre  Archivo : reclamacionesp.php 
  Fecha  Creacion : 20/09/2016
  Descripcion  : 
             Copia del   proyecto pedidos   nombre del archivo que se  copio   reclamacionesp.php 
  Modificado  Fecha  : 
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

///**Correos 
require_once('correos_array.php');
///****FIN    Librerias  Utilizadas  en Cronos 

if ($_SESSION["usuario_agente"]==187 || ($_SESSION["usuario_agente"] >= 400 && $_SESSION["usuario_agente"] <=450 )){  //Agente 187 Servicio al cliente
   $consulta_cliente="SELECT * FROM plataforma_ctesagtes order by CardName ASC";
   //$factura = "SELECT DISTINCT(f.n_factura) as numero_factura, t.empresa, f.n_remision, f.n_agente FROM factura f JOIN transportes t ON f.id_transporte = t.id ORDER BY n_factura ASC;";
               
    
}else{
    $consulta_cliente = sprintf("SELECT * FROM plataforma_ctesagtes WHERE SlpCode=%s OR U_agente2=%s ORDER BY CardName ASC   ",
     GetSQLValueString($_SESSION["usuario_agente"], "int"),
    GetSQLValueString($_SESSION["usuario_agente"], "int"));


/*
 $factura = sprintf("SELECT DISTINCT(f.n_factura) as numero_factura, t.empresa, f.n_remision, f.n_agente FROM factura f JOIN transportes t WHERE f.id_transporte = t.id AND f.n_agente = %s ORDER BY n_factura ASC;",
  GetSQLValueString($_SESSION["usuario_agente"], "int"));
 $sql_factura = mysqli_query($conecta1, $factura) or die (mysqli_error($conecta1));

"SELECT DISTINCT(f.n_factura) as numero_factura, t.empresa, f.n_remision, f.n_agente FROM factura f JOIN transportes t WHERE f.id_transporte = t.id AND f.n_agente = %s and  f.cve_cte = %s    ORDER BY n_factura ASC";*/

    
}

 $sql_cliente=mssql_query($consulta_cliente);
 
 $consulta_productos = "SELECT * FROM plataformaproductosl2";
 $sql_productos = mssql_query($consulta_productos);
 
 
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
  
  

$factura = sprintf(" SELECT  DISTINCT (NUM_DOCTO) as numero_factura
      FROM VW_VENTAS_DC_FILTROS WHERE  COD_CLIENTE =%s",
         GetSQLValueString($cliente, "text")
      );
       $sql_factura = mssql_query( $factura) ;




}


IF (isset($_POST['factura'])){
         
    $nunfactura = $_POST['factura'];
    $cliente = $_POST['cliente']; 
      $factura = sprintf("SELECT DISTINCT(f.n_factura) as numero_factura, t.empresa, f.n_remision, f.n_agente FROM factura f JOIN transportes t WHERE f.id_transporte = t.id  AND f.n_factura = %s ORDER BY numero_factura ASC;",
             GetSQLValueString($nunfactura, "int"));
    $resultadofactura = mysqli_query($conecta1, $factura) or die (mysqli_error($conecta1));
    $variablefactura = mysqli_fetch_assoc($resultadofactura);
    $fact1 = $variablefactura['empresa'];
    $fact2 = $variablefactura['n_remision'];
    
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
    /*//////////////////////ARCHIVO/////////////////////////////////  
   $ext2=  tipo2(basename($_FILES['archivo']['name']));
   $peso2 = $_FILES['archivo']['size'];
  */ 
     ////******Varible Control Archivo
     $est_archivo = 0 ;/// Estodo  del  Archivo Entiendase    $est_archivo = 0 => Error  en el Archivo $est_archivo = 1 => Archivo Correcto 
     $mens_archivo="Correcto" ;/// Mensage Estado  Archivo 
   ///////////////////// 1.Información General /////////////////////
  
      $cliente = $_POST['cliente'];
      $CardName = $_POST['CardName'];
      $SlpCode = $_POST['SlpCode'];
      $SlpName = $_POST['SlpName'];
      $fechareclamo = $_POST['fechareclamo'];
      $representante = $_POST['representante'];
      $email = $_POST['email'];
       $telefono = $_POST['telefono'];
       $contacto=$_POST['contacto'];
      $claveprod = $_POST['ItemCode'];
      $productos = $_POST['ItemName'];
      $lote = $_POST['lote'];
      $motivo = $_POST['motivo'];
      $reclamacion = $_POST['reclamacion'];

      $factura = $_POST['factura'];
      
      ///////////////////// 2.Información complementaria //////////////
      $cantidadsurtida = $_POST['cantidadsurtida'];
      $fechasurtimiento = $_POST['fechasurtimiento'];
      $cantidadreclamada = $_POST['cantidadreclamada'];
      $presentacion = $_POST['presentacion'];
      $temperatura = $_POST['temperatura'];
      $interperie = $_POST['interperie'];
      
      ////////////////////// 3. Condiciones de aplicación //////////////////
      
      $dosis = $_POST['dosis'];
      $cultivo = $_POST['cultivo'];
      $dosisrecomendada = $_POST['dosisrecomendada'];
      $plaga = $_POST['plaga'];
      $condiciones = $_POST['condiciones'];
      $adyuvantes = $_POST['adyuvantes'];
      $fechaaplicacion = $_POST['fechaaplicacion'];
      $personal = $_POST['personal'];
      $humedo = $_POST['humedo'];
      
        ////////////////////// 5. Indique lo que requiere el cliente //////////////////
      $requiere=$_POST['requiere'];
      
      $productos = $_POST['productos'];
    $string_prd = sprintf("SELECT * FROM plataformaproductosl1 WHERE ItemCode = %s",
    GetSQLValueString($productos, "text"));
    $sql_produtcto_guardar=mssql_query($string_prd);
         $registroprod=  mssql_fetch_array($sql_produtcto_guardar);
         $algoprod = $registroprod['ItemCode'];
         $algoprod2 = $registroprod['ItemName'];
      
 

  /////***Inicio Modificacion  10/02/2017 *****************************************

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


if ($_SESSION["usuario_agente"] >= 400 && $_SESSION["usuario_agente"] <=450 )
{

          $superinsert = sprintf("INSERT INTO reclamop SET cve_cte = %s, nom_cte=%s, n_agente=%s, nom_agente=%s, fecha=%s, email=%s, telefono=%s, contacto=%s, lote=%s, nom_prod=%s, cve_prod=%s, motivo=%s, observacion=%s, cant_sur=%s, cant_re=%s, temp=%s, fecha_surtido=%s, presentacion=%s, interperie=%s, dosis_apli=%s, dosis_rec=%s, cond_amb=%s, t_aditivos=%s, personal_aplica=%s, suelo=%s, cultivo=%s, plaga=%s, fecha_aplica=%s, documento=%s, respuesta=%s  ,num_desarrollador =%s ",
                 GetSQLValueString($cliente, "text"),
                 GetSQLValueString($CardName, "text"),
                 GetSQLValueString($SlpCode, "int"),
                 GetSQLValueString($SlpName, "text"),
                 GetSQLValueString($fechareclamo, "date"),
                 GetSQLValueString($email, "text"),
                 GetSQLValueString($telefono, "text"),
                 GetSQLValueString($contacto, "text"),
                 GetSQLValueString($lote, "text"),
                 GetSQLValueString($algoprod2, "text"),
                 GetSQLValueString($algoprod, "text"),
                 GetSQLValueString($motivo, "text"),
                 GetSQLValueString($reclamacion, "text"),
                 GetSQLValueString($cantidadsurtida, "double"),
              GetSQLValueString($cantidadreclamada, "double"),
               GetSQLValueString($temperatura, "double"),
              GetSQLValueString($fechasurtimiento, "date"),
               GetSQLValueString($presentacion, "text"),
              GetSQLValueString($interperie, "int"),
                GetSQLValueString($dosis, "text"),
                GetSQLValueString($dosisrecomendada, "text"),
              GetSQLValueString($condiciones, "text"),
              GetSQLValueString($adyuvantes, "text"),
              GetSQLValueString($personal, "text"),
              GetSQLValueString($humedo, "int"),
               GetSQLValueString($cultivo, "text"),
               GetSQLValueString($plaga, "text"),
              GetSQLValueString($fechaaplicacion, "date"),
              GetSQLValueString('upload/'.$New_ruta_and_vali['NOM_NEW'], "text"), 
              GetSQLValueString($requiere, "text"),
                 GetSQLValueString($_SESSION["usuario_agente"], "int"));
}else{

       $superinsert = sprintf("INSERT INTO reclamop SET cve_cte = %s, nom_cte=%s, n_agente=%s, nom_agente=%s, fecha=%s, email=%s, telefono=%s, contacto=%s, lote=%s, nom_prod=%s, cve_prod=%s, motivo=%s, observacion=%s, cant_sur=%s, cant_re=%s, temp=%s, fecha_surtido=%s, presentacion=%s, interperie=%s, dosis_apli=%s, dosis_rec=%s, cond_amb=%s, t_aditivos=%s, personal_aplica=%s, suelo=%s, cultivo=%s, plaga=%s, fecha_aplica=%s, documento=%s, respuesta=%s ,n_factura=%s  ",
                 GetSQLValueString($cliente, "text"),
                 GetSQLValueString($CardName, "text"),
                 GetSQLValueString($SlpCode, "int"),
                 GetSQLValueString($SlpName, "text"),
                 GetSQLValueString($fechareclamo, "date"),
                 GetSQLValueString($email, "text"),
                 GetSQLValueString($telefono, "text"),
                 GetSQLValueString($contacto, "text"),
                 GetSQLValueString($lote, "text"),
                 GetSQLValueString($algoprod2, "text"),
                 GetSQLValueString($algoprod, "text"),
                 GetSQLValueString($motivo, "text"),
                 GetSQLValueString($reclamacion, "text"),
                 GetSQLValueString($cantidadsurtida, "double"),
              GetSQLValueString($cantidadreclamada, "double"),
               GetSQLValueString($temperatura, "double"),
              GetSQLValueString($fechasurtimiento, "date"),
               GetSQLValueString($presentacion, "text"),
              GetSQLValueString($interperie, "int"),
                GetSQLValueString($dosis, "text"),
                GetSQLValueString($dosisrecomendada, "text"),
              GetSQLValueString($condiciones, "text"),
              GetSQLValueString($adyuvantes, "text"),
              GetSQLValueString($personal, "text"),
              GetSQLValueString($humedo, "int"),
               GetSQLValueString($cultivo, "text"),
               GetSQLValueString($plaga, "text"),
              GetSQLValueString($fechaaplicacion, "date"),
              GetSQLValueString('upload/'.$New_ruta_and_vali['NOM_NEW'], "text"), 
              GetSQLValueString($requiere, "text"),
             GetSQLValueString($factura, "int"));



}




     ////***** Validamos 
    /* if($est_archivo==1  && $_FILLES['archivo']['tmp_name']==UPLOAD_ERR_OK )
     {*/
            $resultadosuperinsert = mysqli_query($conecta1, $superinsert) or die (mysqli_error($conecta1));
      
        //Script para mandar correo
          $fromname="Reclamaciones por Productos o Calidad"; 
          $subject="Nueva Reclamación de Producto";
         // $destinatario="egonzalez@agroversa.com.mx";
          $destinatario="emena@agroversa.com.mx";
          $destinatario2[0]="tsalas@grupoversa.com";  //asistente 
                  $destinatario2[1]="egonzalez@agroversa.com.mx";  //con copia a Sistemas
                  $destinatario2[2]="ifrias@grupoversa.com"; 
          $mensaje="<p> Existe una nueva reclamación por parte de ".$representante."</p>";
          $mensaje.="<p><a href='http://www.verur.com.mx/sistemas/pedidos/'> da clic aquí para ir al sitio:</a> </p>";
           correos($fromname,$subject,$destinatario,$destinatario2,$mensaje);   //Mandar correo
      ////-----
            
             
    /// }    
        
    
     
      
      
     /* Codigo resplado  
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
      
 /////***Fin  Modificacion  10/02/2017 *****************************************
    
   
 }
 $fecha_hoy=date("Y-m-d");


?> 
<script> 
    function folio(){
    <?php 
      $folio = "SHOW TABLE STATUS like 'reclamop'";
         $folioquery = mysqli_query($conecta1, $folio) or die (mysqli_error($conecta1));
         $folioassoc = mysqli_fetch_assoc($folioquery);
         $foliotal = $folioassoc['Auto_increment'];
         if($est_archivo == 1) {
    ?>
            alert ("Reclamación enviada con folio: <?php echo $foliotal." Estado Archivo: ".$mens_archivo; ?>");
         <?php } else { ?>
              alert ("<?php echo "Estado Archivo:".$mens_archivo; ?>");
         <?php }?>     
             
             
    }
  
    
</script>

    

    <?php /// echo $consulta_cliente;  ?>

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

.ContInputsII {
         background: #fffdfde6;
    height: auto;
    top: 93px;
    border-radius: 24px;
    box-shadow: -5px 7px 57px #999;
    left: 264PX;
    
}
.ContInputsIII {
        background: #fffdfde6;
    height: auto;
    top: 500px;
    border-radius: 24px;
    box-shadow: -5px 7px 57px #999;
    left: -305PX;
}
.ContInputsIV
{
       background: #fffdfde6;
    height: auto;
    top: 529px;
    border-radius: 24px;
    box-shadow: -5px 7px 57px #999;
    left: 262PX;
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
 

 
    <div  id="mainform">  
        <div class="contTITLE  col-lg-12 col-sm-12  col-xs-12">
          <strong class="titlemain">
             Reclamaciones Calidad
     
          </strong>
         
        </div> 
        <div class="  col-lg-12 col-sm-12  col-xs-12" >
        <div class="ldtrans col-lg-3 col-sm-3 col-xs-12" ></div>
        <div class="ContInputs  col-lg-6 col-sm-6 col-xs-12" >
          <!--**********Inicio  Contenedor Informacion dentro del Recuadro  blanco***********<?php /// $dt = new DateTime();  echo $dt->format("d/m/Y") ;  ?>***********-->
          <div class="col-lg-12 col-sm-12 col-xs-12"> 
            <H3>Cuestionario de Atención a Reclamos (CAR) SC-001   Rev. 2   </H3> 
            <?php //echo  $factura; ?>
          </div>
          <div class="col-lg-12 col-sm-12 col-xs-12" > 
            <div class="col-lg-2 col-sm-12 col-xs-12" > </div>
           <div class="col-lg-10 col-sm-12 col-xs-12" >
                   <H3>I. Información General</H3>
            </div>  

          </div>
            <div class="col-lg-12 col-sm-12 col-xs-12"> 
                    <div  class="col-lg-6 col-sm-12 col-xs-12">
                      
                    </div>
                       
                    <div  class="col-lg-6 col-sm-12 col-xs-12"> 
                        <label>Fecha: </label><input readonly  class="form-control"  type="date" name="fechareclamo" value="<?php echo $fecha_hoy;?>">
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
                     
                   
                    <select required class="form-control select2" name="cliente" title="Cliente" onchange="this.form.submit()">
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



         

                <div class="col-lg-5 col-sm-5 col-xs-12">
                     <label>Representante: </label><input required class="form-control"  type="text" name="representante" value="<?php echo $algo; ?>">

                </div>
                <div class="col-lg-1 col-sm-1 col-xs-12"></div>
                <div class="col-lg-6 col-sm-5 col-xs-12">
                         <label>Factura: </label>
                               <div class="input-group input-group select2-bootstrap-prepend">
                <span class="input-group-btn">
                    <button class="btn btn-default" type="button" data-select2-open="select2-button-addons-multi-input-group">
                        <span class="glyphicon glyphicon-search"></span>
                    </button>
                </span>
                     

            <select required name="factura" class="form-control select2" title="Factura" onchange="this.form.submit()"> <option value="0">No Aplica</option>
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
          <div class="col-lg-8 col-sm-12 col-xs-12">
                   <label>Producto(s): </label>
                               <div class="input-group input-group select2-bootstrap-prepend">
                <span class="input-group-btn">
                    <button class="btn btn-default" type="button" data-select2-open="select2-button-addons-multi-input-group">
                        <span class="glyphicon glyphicon-search"></span>
                    </button>
                </span>
                     
                  <select required name="productos" class="form-control select2" title="Productos">
                   <option value="">Elija</option>

                                  <?php

                                  WHILE ($registroproductos=  mssql_fetch_array($sql_productos)){
                      IF($productos == $registroproductos['ItemCode']){
                           echo '<option selected value="'.$registroproductos['ItemCode'].'">'.utf8_encode($registroproductos['ItemName']).'</option>';
                      }else{
                           echo '<option value="'.$registroproductos['ItemCode'].'">'.utf8_encode($registroproductos['ItemName']).'</option>';
                      }

                                          

                      }
                      ?>
                  </select>
        
                  </div>
                
                     
                </div>
                
                <div class="col-lg-3 col-sm-12 col-xs-12">
                    <label>Lote: </label><input required class="form-control"  type="text" name="lote">

                </div>
            </div>

       

            <div class="col-lg-12 col-sm-12 col-xs-12">
                 <div class="col-lg-6 col-sm-12 col-xs-12">
                   <label>E-mail (Cliente): </label><input required class="form-control"  type="email" name="email">
       

                 </div>
                 <div class="col-lg-2 col-sm-12 col-xs-12"></div>
                 <div class="col-lg-4 col-sm-12 col-xs-12">
                    
                         <label>Teléfono (Cliente): </label><input required class="form-control"  type="text" name="telefono">
        
                 </div>
            </div>
            <div class="col-lg-12 col-sm-12 col-xs-12">
               
      

                 <div class="col-lg-9 col-sm-12 col-xs-12">
                     <label>Contacto de cliente: </label><input required class="form-control"  type="text" name="contacto">
       

                 </div>
                 <div class="col-lg-3 col-sm-12 col-xs-12">
              

                  </div>
            <div class="  col-lg-12 col-sm-12 col-xs-12"> <!--contnfileFinal-->
                <div class=" col-lg-12 col-sm-12 col-xs-12">
                  
                     <div class=" col-lg-8 col-sm-12 col-xs-12">
                         <label> Motivo </label>
                        <select required   name="motivo" class="form-control" title="motivo">
                            <option value="">Elija</option>
                            <option value="0">Efectividad</option>
                            <option value="1">Anomalía empaque (Envases)</option>
                            <option value="2">Anomalía en etiqueta</option>
                            <option value="3">Derrame</option>
                            <option value="4">Asentamiento/suspensibilidad</option>
                            <option value="5">Precipitado</option>
                            <option value="6">Olor no característico</option>
                            <option value="7">Falta de contenido neto</option>
                            <option value="8">Genotipo</option>
                            <option value="9">Color no característico</option>
                            <option value="10">Producto Caduco</option>
                            <option value="11">Anomalía empaque Cajas/Saco</option>
                             
                         
                        
                        </select>
                     </div>
                    <div class=" col-lg-4 col-sm-12 col-xs-12">
                       
                     </div>
                </div>
                <div class=" col-lg-12 col-sm-12 col-xs-12">
                                <label>Detalle Reclamación: </label><br>
        <textarea required rows="4" class="form-control" cols="40" name="reclamacion" ></textarea>
        
                </div>

            </div> 

            </div>

           <!--**********Fin Contenedor Informacion dentro del Recuadro  blanco**********************-->
        </div>
        <!---******************************************************-->
        
        <div class="ldtrans col-lg-3 col-sm-3 col-xs-12" ></div>
    </div>
    
          <div class="ContInputsII  col-lg-6 col-sm-6 col-xs-12" >
            <!--**********INICIO Contenedor Informacion dentro del Recuadro  blanco**********************--> 
              <div class="  col-lg-12 col-sm-12  col-xs-12">
          <h3>
                    II. Información Complementaria
         </h3>
            </div> 
              <div class=" col-lg-12 col-sm-12">
                      <div class=" col-lg-4 col-sm-12">
                         <label>Cantidad Surtida: </label>
                         <input required class="form-control" type="text" name="cantidadsurtida">
         
                      </div>
                      <div class=" col-lg-1 col-sm-12"></div>
                      <div class=" col-lg-7  col-sm-12">
                         <label>Fecha de Surtimiento: </label><input required class="form-control" type="date" name="fechasurtimiento">
                      </div>
              </div> 
            <div class=" col-lg-12 col-sm-12 col-xs-12">
                    <div class=" col-lg-4 col-sm-12">
                              <label>Cantidad Reclamada: </label><input required class="form-control" type="text" name="cantidadreclamada">
        
                      </div>
                      <div class=" col-lg-1 col-sm-12"></div>
                      <div class=" col-lg-7  col-sm-12">
                              <label>Presentación: </label><input required class="form-control" type="text" name="presentacion">
        
                      </div>


            </div>

                 <div class=" col-lg-12 col-sm-12 col-xs-12">
                      <div class=" col-lg-7 col-sm-12">
                             <label>Temperatura de almacén aproximada: </label><input required class="form-control" type="text" name="temperatura">
                      </div>
                      <div class=" col-lg-5  col-sm-12">
                              <label> Expuesto a intemperie </label>
                               <select required class="form-control"  name="interperie" title="Interperie">
                              <option value="">Elija</option>
                              <option value="0">Sí</option>
                              <option value="1">No</option>
                          
                           </select>
        
                      </div>


        
                </div>

 <!--**********Fin Contenedor Informacion dentro del Recuadro  blanco**********************-->
          </div>
    <!--**************************************************************************************************************************************************************************************************************--->
    <div class="ContInputsIII  col-lg-6 col-sm-6 col-xs-12" >
            <!--**********INICIO Contenedor Informacion dentro del Recuadro  blanco**********************--> 
              <div class="  col-lg-12 col-sm-12  col-xs-12">
          <h3>
                  III. Condiciones de aplicación 
         </h3>
            </div> 
              <div class=" col-lg-12 col-sm-12">
                <label>Dosis Aplicada (semillas, densidad y fertilización utilizada): </label><input required  type="text" class="form-control" name="dosis">
              </div>
              <div class=" col-lg-12 col-sm-12">
                      <div class=" col-lg-4 col-sm-12">
                         <label>Cultivo: </label><input  type="text" class="form-control" name="cultivo">
                      </div>
                      <div class=" col-lg-1 col-sm-12"></div>
                      <div class=" col-lg-7  col-sm-12">
                         <label>Dosis recomendada: </label><input required class="form-control" type="text" name="dosisrecomendada"><br><br>
                      </div>
              </div> 
            <div class=" col-lg-12 col-sm-12 col-xs-12">
                    <div class=" col-lg-4 col-sm-12">
                           <label>Plaga: </label><input required class="form-control" type="text" name="plaga"><br><br> 
        
                      </div>
                      <div class=" col-lg-1 col-sm-12"></div>
                      <div class=" col-lg-7  col-sm-12">
                          <label>Condiciones Ambientales: </label><input required class="form-control" type="text" name="condiciones"><br><br>  
                      </div>


            </div>

                 <div class=" col-lg-12 col-sm-12 col-xs-12">
                      <div class=" col-lg-7 col-sm-12">
                          <label>Tipos de adyuvantes, aditivos y otros productos que se mezclaron: </label><input required class="form-control" type="text"  name="adyuvantes"><br><br> 
                      </div>
                      <div class=" col-lg-5  col-sm-12">
                             <label>Fecha de Aplicación: </label><input  type="date" class="form-control" name="fechaaplicacion"><br><br>
         
                      </div>


        
                </div>
                <div class=" col-lg-12 col-sm-12 col-xs-12">
                      <div class=" col-lg-7 col-sm-12">
                           <label>Personal presente en la aplicación: </label><input required class="form-control" type="text" name="personal"><br><br>
        
                      </div>
                      <div class=" col-lg-5  col-sm-12">
                              <label>En el caso de Semillas, Siembra en seco o húmedo y tipo de suelo: </label><input  type="text" class="form-control" name="humedo"><br><br>
                      </div>


        
                </div>
                <div class=" col-lg-12 col-sm-12 col-xs-12">
                      <div class=" col-lg-7 col-sm-12">
                           
                      </div>
                      <div class=" col-lg-5  col-sm-12">
                              
                      </div>


        
                </div>
                  
 <!--**********Fin Contenedor Informacion dentro del Recuadro  blanco**********************-->
          </div>
    
    <!--**************************************************************************************************************************************************************************************************************--->
    <div class="ContInputsIV col-lg-6 col-sm-6 col-xs-12" >
            <!--**********INICIO Contenedor Informacion dentro del Recuadro  blanco**********************--> 
              <div class="  col-lg-12 col-sm-12  col-xs-12">
          <h3>
                  IV. Indique lo que requiere el cliente  
         </h3>
            </div> 
              <div class=" col-lg-12 col-sm-12">
         
    <fieldset>
      
        
    </fieldset>
              </div>
              <div class=" col-lg-12 col-sm-12">
                      <div class=" col-lg-7 col-sm-12">
                          <label> Requiere </label>
                        <select required  class="form-control" name="requiere" title="requiere">
                            <option value="">Elija</option>
                            <option value="0">Reposición</option>
                            <option value="1">Nota de crédito</option>
                            <option value="2">Devolución</option>
                            <option value="3">Inspección</option>
                            <option value="4">Cambio físico</option>

                        
                        </select>
                      </div>
                      <div class=" col-lg-1 col-sm-12"></div>
                      <div class=" col-lg-4  col-sm-12">
                        
                      </div>
              </div> 
            <div class=" col-lg-12 col-sm-12 col-xs-12">
                    <div class=" col-lg-8 col-sm-12">
                            <legend>V. Archivo (En caso de que aplique)</legend>
        <label for="archivo">Archivo (Word,Excel,PowerPoint o PDF maximo 10Mb)</label>
        <input class="form-control" type="file" name="archivo" value="" />
                      </div>
                      <div class=" col-lg-1 col-sm-12"></div>
                      <div class=" col-lg-3  col-sm-12">
                          
                      </div>


            </div>

                 <div class=" col-lg-12 col-sm-12 col-xs-12">
                      <div class=" col-lg-7 col-sm-12">
                          <input input type='submit' value='Guardar' name="guardar" class="btn btn-primary" /> <br><br> 
                      </div>
                      <div class=" col-lg-5  col-sm-12">
                             
         
                      </div>


        
                </div>
                <div class=" col-lg-12 col-sm-12 col-xs-12">
                      <div class=" col-lg-7 col-sm-12">
                       
        
                      </div>
                      <div class=" col-lg-5  col-sm-12">
                              
                      </div>


        
                </div>
                <div class=" col-lg-12 col-sm-12 col-xs-12">
                      <div class=" col-lg-7 col-sm-12">
                           
                      </div>
                      <div class=" col-lg-5  col-sm-12">
                              
                      </div>


        
                </div>
                  
 <!--**********Fin Contenedor Informacion dentro del Recuadro  blanco**********************-->
          </div>
</div>
</div>


<!---*****************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************---->

   
  
    
     

     
      <input type="hidden" required name="SlpName" value="<?php echo $registro4['SlpName']; ?>"/>
        <input type="hidden" required name="SlpCode" value="<?php echo $registro4['SlpCode']; ?>"/>
        <input type="hidden" required name="CardCode" value="<?php echo $_POST['cliente']; ?>"/>
        <input type="hidden" required name="CardName" value="<?php echo $registro4['CardName']; ?>"/>
        
        
        <input type="hidden" required name="ItemCode" value="<?php echo $_POST['productos']; ?>"/>
        <input type="hidden" required name="ItemName" value="<?php echo $registroprod['ItemName']; ?>"/>
</form>
   <!---Modal  Para  el comentario ----->    
     <div  class="modal fade" id="Modal_comentarios" role="dialog">
        <div  class="modal-dialog">

          <!-- Modal content-->
          <div  class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <h4 class="modal-title">Comentarios</h4>
            </div>
            <div class="modal-body">
      
                <div class="form-group">
                    <div class="row" class="well"> 
                        <!-- <h5>Comentario  Anterior:</h5> --> 
                         <br>
                         <?php 
                             /// echo '<h4>'.$mens_archivo."  ".$New_ruta_and_vali['Root'].'</h4>';
                             /// echo $foliotal." Estado Archivo:".$mens_archivo
                             echo '<h4>'."Reclamación enviada con folio: ".$foliotal.'</h4>';
                             echo '<br>';
                             echo '<h3>'.'Estatus Archivo:'.$mens_archivo.'</h3>';
                         
                         ?> 
                        <!-------->
                    </div>
                </div>
            </div>
            <div class="modal-footer">
              <button type="button" id="close_coment" class="btn btn-default" data-dismiss="modal">Close</button>
             <!--  <button type="button" id="btn_update_comentarios"  class="btn btn-info" data-dismiss="modal"> <span class="glyphicon glyphicon-floppy-saved"></span> Guardar</button> -->
            </div>
          </div>

        </div>
      </div>

</div>
 <?php require_once('foot.php');?>