<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->

<?php
 session_start ();
   $MM_restrictGoTo = "login.php";
 if (!(isset($_SESSION['usuario_valido']))){   
      header("Location: ". $MM_restrictGoTo); 
  exit;
}

 require_once('formato_datos.php');
 require_once('Connections/conecta1.php');
 require_once('conexion_sap/sap.php');
 require_once('funciones.php');
 ///***Seleccion de la Bd 
/// mssql_select_db("AGROVERSA");
 //obtener el correo electronico del agente, el cual se encuentra en la tabla de SAP  
 function agente_mail_Array($agente){
        
         $result = array();
      ///buscar mail del cliente en SAP
            $string=  sprintf("Select U_email,Memo from OSLP where SlpCode=%s",
                                    getSQLValueString($agente,"int")); 
            $query=  mssql_query($string);
            $datos=  mssql_fetch_array($query);
            $result['mail_agente']=$datos['U_email'];  //mail actual
            $result['nombre_agente']=$datos['Memo'];
            return $result;
        /////    
 }
 ///****************************************************************************  
 ////*** Obtenemos el   tipo de  Usuario para   validar  los  rechazos
 $typeUsu = $_REQUEST['typUsu'];
 
 $remision = $_REQUEST['rechazar']; 
 

  $date = date('Y-m-d H:i:s');
 
IF (isset($_POST['rechazarboton'])){
      
      
     $rechazo = $_POST['comentariorechazo'];
      
  $actualizarrechazar = sprintf("UPDATE encabeza_convenio SET estatus='N', fechacomp_real=%s,"
         . " au_dc='0', au_ge='0', au_an='0', comentario_rechazo=%s  WHERE  n_remision=%s",
  GetSQLValueString($date, "date"),
  GetSQLValueString($rechazo, 'text'),
  GetSQLValueString($remision, 'int'));
  $queryrechazar=mysqli_query($conecta1, $actualizarrechazar) or die (mysqli_error($conecta1));
      
    ///**Inicio Mensage de  Autorizacion del   Conveniod 
      ///** Obtenemos la Informacion  Basica del  convenio para  Agregar  al correo
            $string_infoconve = sprintf("SELECT *  FROM  encabeza_convenio where n_remision =%s", 
                  GetSQLValueString($remision,'int'));
       ///**+ Realizaos  el Qery  
            $qery_infoconve = mysqli_query($conecta1, $string_infoconve);
       //***  Realizamos el Feth 
            $fetch_infoconve = mysqli_fetch_array($qery_infoconve); 
            
       ///***Obtenemos el  correo y nombre  de  Agente 
            $elementos_Agente = agente_mail_Array($fetch_infoconve['n_agente']);
               ///***Obtenemos el  Correo  Agente 
               $nom_agente= $elementos_Agente['nombre_agente'];
               ///***Obtenemos el correo 
               $correo_agente =  $elementos_Agente['mail_agente'];
       ////****Obtenemos el  Mail del  Gerente 
       ////*****Cadena de  Consulta
        $string_mail_agente = sprintf("SELECT  zona,mail   FROM pedidos.relacion_gerentes where cve_age = %s",
         GetSQLValueString($fetch_infoconve['n_agente'], "int")); 
        ///****** Hacemos la  consulta
        $qery_mail = mysqli_query($conecta1, $string_mail_agente);
        ////****Convertimos la  consulta  a  un elemento  fetc
        $mail_agente_fetch = mysqli_fetch_assoc($qery_mail);
        ///****Obtenemos  el  Correo  del  Gerente 
        $mail_gerente = $mail_agente_fetch['mail'];
        ////**Obtenemos  el  Nombre del  Gerente 
        $nombre_gerente =$mail_agente_fetch['zona'];        
               
          ///****     
               
////*****Si el Pedido  no esta  Autoirzado  No se envia  en  correo*********************************************************   

    ////*****Informacion a Enviar al  Gerente
    $STRING_CADENA_Html_001 = "<table><thead><th><h4>Buen Día se Rechazo  Un  Convenio.</h4></th><thead>";        
    $STRING_CADENA_Html_002 = "<tbody><tr><td><h4>Folio Convenio: ".$fetch_infoconve['n_remision']."</h4></td></tr>";
    $STRING_CADENA_Html_003 = "<tr><td>N# Cliente ".$fetch_infoconve['cve_cte']."</td><td> Cliente :".$fetch_infoconve['nom_cte']."</td></tr>";
    $STRING_CADENA_Html_004 = "<tr><td>Fecha Inicial : ".$fetch_infoconve['fechainicio']."</td><td>Fecha Fin: ".$fetch_infoconve['fechafin']."</td></tr>";
    $STRING_CADENA_Html_005 = "<tr><td>Monto de Convenio: ". "$".number_format($fetch_infoconve['total'])."</td></tr>"; 
    $STRING_CADENA_Html_006= "<tr><td> Comentarios :". $rechazo."</td></tr>"; 
   /// $STRING_CADENA_Html_Correos = "<tr><td> Correo Gerente:".$mail_gerente."</td><td> CorreoAgente :".$correo_agente."</td></tr>"; 
    $TRING_CADENA_Html_007 = "</tbody></table>";
    /////****
    //******************        
    ////***Armamos la informacion Html  a Enviar
    $strin_Fina_HTML =$STRING_CADENA_Html_001.$STRING_CADENA_Html_002 .$STRING_CADENA_Html_003.$STRING_CADENA_Html_004.$STRING_CADENA_Html_005.$STRING_CADENA_Html_006.$TRING_CADENA_Html_007;
    
    ///***Condicion para  determinar  el  tipo de Usuario para  determinar ell  envia de  correos 
    //If para Opcion Rechazo Gerente
    if($_REQUEST['typUsu']==79)
    {
           /****    Mandamos Correo 
            * ***** Solamente  al  Agente 
           */
           //Cadena de Envio Agente  
           correos("Convenios", "Rechazo de  Convenio",$correo_agente,"",$strin_Fina_HTML );
           //correos("Convenios", "Rechazo de  Convenio",'fhernandez@agroversa.com.mx',"",$strin_Fina_HTML );
        
    }
    //If para Opcion Rechazo  Jefe Inteligencia //typUsu=79 =>Para Identificar cuando es el jefe Inteligencia Commercial
    if($_REQUEST['typUsu']==69)
    {
   
         /****    Mandamos Correo 
            * ***** Al Gerente  y  Agente 
           */
           //Cadena de Envio Gerente y Agente  
           correos("Convenios", "Rechazo de  Convenio",$mail_gerente,$correo_agente,$strin_Fina_HTML );
           /// correos("Convenios", "Rechazo de  Convenio",'fhernandez@agroversa.com.mx',"",$strin_Fina_HTML );
        
        
        
    }
    //If  para Opcion Rechazo   Direccion  Comercial 
    if($_REQUEST['typUsu']==10)
    {
             ///**Obtenemos el correo del  Jefe  de Inteligencia  Comercial 
        $strin_IntComer =  "SELECT  email FROM usuarios_locales  where  rol =69";
        ////** Realizamos  Qery 
        $qery_IntComer = mysqli_query($conecta1, $strin_IntComer);
        ////**Fetch  
        $fetch_IntComer = mysqli_fetch_array($qery_IntComer);
        ///***Obtenemos el  mail del  correo 
        $mail_IntComer = $fetch_IntComer['email'];  
        
           /****    Mandamos Correo 
            * ***** Al Gerente  y  Agente  ademas  del jefe de  Inteligenia comercial as
           */
                $array_cc =  array();
                $array_cc[0] = $mail_IntComer;
                $array_cc[1] = $correo_agente;
                $array_cc[2] = $mail_gerente;
                            
            correos("Convenios", "Rechazo de  Convenio",'bcastaneda@agroversa.com.mx',$array_cc,$strin_Fina_HTML );
            /// correos("Convenios", "Rechazo de  Convenio",'fhernandez@agroversa.com.mx',$array_cc,$strin_Fina_HTML );
    } 
     /////**Fin Mensage de  Autorizacion del   Conveniod 
  
  
  
echo '<script>window.opener.location.reload(false);</script>';
      echo '<script>window.close();</script>';     
  }
 
?>
<html>
     <head>
    <meta charset="utf-8">
    <title>CRONOS</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Loading Bootstrap -->
    <link href="select3/dist/css/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Loading Flat UI -->
    <link href="select3/dist/css/flat-ui.css" rel="stylesheet">
 
    <link href="select2/gh-pages.css" rel="stylesheet">
    <link href="select2/select2.css" rel="stylesheet">
      
      
    <link rel="shortcut icon" href="select3/dist/img/favicon.ico">

    <!-- HTML5 shim, for IE6-8 support of HTML5 elements. All other JS at the end of file. -->
    <!--[if lt IE 9]>
      <script src="../../dist/js/vendor/html5shiv.js"></script>
      <script src="../../dist/js/vendor/respond.min.js"></script>
    <![endif]-->
  </head>
    <body>
        <form method="post">
         <p>
            <label for="comment">Comentario de rechazo:</label>
  <textarea name="comentariorechazo" class="form-control" rows="5" id="comment" required ></textarea>
        </p>
        <div class=" col-lg-12" >
        <p>
            <button name="rechazarboton" type="submit" class="btn btn-lg btn-primary pull-right" onclick="return confirm('¿Está Seguro de RECHAZAR?')">Rechazar</button>

        </p>
    </div>
        </form>
    </body>
</html>
