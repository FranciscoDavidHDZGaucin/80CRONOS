<?php
session_start ();
 switch ($_SESSION["usuario_rol"]) {
    case 10:

        require_once 'header_direccion.php';
        break;
     case 69:
         require_once 'header_inteligencia.php';

        break;
     case 96:
         require_once 'header_inteligencia.php';
    break;  
    case 100:
        require_once 'header_planeador.php';
   break;   

    default:
        require_once 'header_gerentes.php';
        break;
}



  require_once('formato_datos.php');
  require_once('Connections/conecta1.php');
  require_once('conexion_sap/sap.php');
  ///mssql_select_db("AGROVERSA");    
  require_once ('funciones.php');
 $idgerente=$_SESSION["usuario_rol"];
 
 $date = date('Y-m-d H:i:s');

 
 $listaprecios;
$listacomercial = "plataformaproductosl7";
$numerogerente = $_SESSION["usuario_rol"];



if($numerogerente=="3"){
    $listaprecios = "plataformaproductosl4";
} else if ($numerogerente=="1"){
    $listaprecios = "plataformaproductosl5";
} else if ($zona=="6"){
    $listaprecios = "plataformaproductosl6";
} else if ($zona==""){
    
} else if ($zona=="2"){
    $listaprecios = "plataformaproductosl3";    
} else if ($zona=="10"){
    $listaprecios = "plataformaproductosl8";    
}

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
  

//****************************************IFS DE GERENTES
 IF (isset($_REQUEST['autorizar'])){
     
     $remisionautoriza = $_REQUEST['autorizar'];

        $actualizarge = sprintf("UPDATE encabeza_convenio SET au_dc = 0, au_an=0, au_ge=1, estatus='A',fechacomp_real=%s WHERE n_remision=%s",
        GetSQLValueString($date, "date"),
        GetSQLValueString($remisionautoriza,'int'));
        $queryactualizarge = mysqli_query($conecta1, $actualizarge) or die (mysqli_error($conecta1));
      
         //notificar por mail autorizacion de Convenio por parte del Gerente     
           ///******Inicio para  enviar    correo Autorizaciona   Jefe  inteligencia   Comercial
             ////****Obtenemos el  correo  del Jefe  de  Inteligencia  comercial
            $strin_jefeIAC=  "SELECT  email FROM usuarios_locales  where  rol =69";
            ////** Realizamos  Qery 
            $qery_jefeIAC = mysqli_query($conecta1, $strin_jefeIAC);
            ////**Fetch  
            $fetch_jefeIAC = mysqli_fetch_array($qery_jefeIAC);
            ///***Obtenemos el  mail del  correo 
            $mail_jefeIAC = $fetch_jefeIAC['email'];  
             ///**Inicio Mensage de  Autorizacion del   Conveniod 
      ///** Obtenemos la Informacion  Basica del  convenio para  Agregar  al correo
            $string_infoconve = sprintf("SELECT *  FROM  encabeza_convenio where n_remision =%s", 
                  GetSQLValueString($_REQUEST['autorizar'],'int'));
       ///**+ Realizaos  el Qery  
            $qery_infoconve = mysqli_query($conecta1, $string_infoconve);
       //***  Realizamos el Feth 
            $fetch_infoconve = mysqli_fetch_array($qery_infoconve); 
////*****Si el Pedido  no esta  Autoirzado  No se envia  en  correo*********************************************************   
    ////*****Informacion a Enviar al  Gerente
    $STRING_CADENA_Html_001 = "<table><thead><th><h4>Buen Día se ha Autorizado Un  Convenio.</h4></th><thead>";        
    $STRING_CADENA_Html_002 = "<tbody><tr><td><h4>Folio Convenio: ".$fetch_infoconve['n_remision']."</h4></td></tr>";
    $STRING_CADENA_Html_003 = "<tr><td>N# Cliente ".$fetch_infoconve['cve_cte']."</td><td> Cliente :".$fetch_infoconve['nom_cte']."</td></tr>";
    $STRING_CADENA_Html_004 = "<tr><td>Fecha Inicial : ".$fetch_infoconve['fechainicio']."</td><td>Fecha Fin: ".$fetch_infoconve['fechafin']."</td></tr>";
    $STRING_CADENA_Html_005 = "<tr><td>Monto de Convenio: ". "$".number_format($fetch_infoconve['total'])."</td></tr>"; 
    ///$STRING_CADENA_Html_006= "<tr><td> Correo Destino GERENTE :".$mail_jefeIAC."</td></tr>"; 
    $TRING_CADENA_Html_007 = "</tbody></table>";
    /////****
    //******************        
    ////***Armamos la informacion Html  a Enviar
    $strin_Fina_HTML =$STRING_CADENA_Html_001.$STRING_CADENA_Html_002 .$STRING_CADENA_Html_003.$STRING_CADENA_Html_004.$STRING_CADENA_Html_005.$TRING_CADENA_Html_007;
           ////****Mandamos 
            correos("Convenios", "Estus de Autorizacion Convenio",$mail_jefeIAC,"",$strin_Fina_HTML );
      /////**Fin Mensage de  Autorizacion del   Conveniod 
      ///******Fin  para  enviar    correo Autorizaciona   Jefe  inteligencia   Comercial
     
     
 }
 
 
  
  
  //****************************************IFS DE ANALISTA DE NEGOCIOS

  IF (isset($_REQUEST['autorizaran'])){
      $remisionautoriza = $_REQUEST['autorizaran'];
         $actualizautorizaran = sprintf("UPDATE encabeza_convenio SET estatus='A',au_dc=0, au_an=1, fechacomp_real=%s WHERE n_remision=%s",
  GetSQLValueString($date, "date"),
           GetSQLValueString($_REQUEST['autorizaran'],'int'));
    $queryrautorizaran=mysqli_query($conecta1, $actualizautorizaran) or die (mysqli_error($conecta1));
      //notificar por mail autorizacion de Convenio por parte del Analista de Negocios     
 
    ///******Inicio para  enviar    correo Autorizaciona   Jefe  inteligencia   Comercial
             ////****Obtenemos el  correo  del Director General 
            $strin_DIRG=  "SELECT  email FROM usuarios_locales  where  extra1 =99";
            ////** Realizamos  Qery 
            $qery_jefeIAC = mysqli_query($conecta1, $strin_DIRG);
            ////**Fetch  
            $fetch_DIRG = mysqli_fetch_array($qery_jefeIAC);
            ///***Obtenemos el  mail del  correo 
            $mail_DIRG = $fetch_DIRG['email'];  
            
           /// send_correo_autorizacion($mail_DIRG,$remisionautoriza);
            ///*****
            ///**Inicio Mensage de  Autorizacion del   Conveniod 
      ///** Obtenemos la Informacion  Basica del  convenio para  Agregar  al correo
            $string_infoconve = sprintf("SELECT *  FROM  encabeza_convenio where n_remision =%s", 
                  GetSQLValueString($_REQUEST['autorizaran'],'int'));
       ///**+ Realizaos  el Qery  
            $qery_infoconve = mysqli_query($conecta1, $string_infoconve);
       //***  Realizamos el Feth 
            $fetch_infoconve = mysqli_fetch_array($qery_infoconve); 
////*****Si el Pedido  no esta  Autoirzado  No se envia  en  correo*********************************************************   
    ////*****Informacion a Enviar al  Gerente
    $STRING_CADENA_Html_001 = "<table><thead><th><h4>Buen Día se ha Autorizado Un  Convenio.</h4></th><thead>";        
    $STRING_CADENA_Html_002 = "<tbody><tr><td><h4>Folio Convenio: ".$fetch_infoconve['n_remision']."</h4></td></tr>";
    $STRING_CADENA_Html_003 = "<tr><td>N# Cliente ".$fetch_infoconve['cve_cte']."</td><td> Cliente :".$fetch_infoconve['nom_cte']."</td></tr>";
    $STRING_CADENA_Html_004 = "<tr><td>Fecha Inicial : ".$fetch_infoconve['fechainicio']."</td><td>Fecha Fin: ".$fetch_infoconve['fechafin']."</td></tr>";
    $STRING_CADENA_Html_005 = "<tr><td>Monto de Convenio: ". "$".number_format($fetch_infoconve['total'])."</td></tr>"; 
    ///$STRING_CADENA_Html_006= "<tr><td> Correo Destino INTCOMER :".$mail_DIRG."</td></tr>"; 
    $TRING_CADENA_Html_007 = "</tbody></table>";
    /////****
    //******************        
    ////***Armamos la informacion Html  a Enviar
    $strin_Fina_HTML =$STRING_CADENA_Html_001.$STRING_CADENA_Html_002 .$STRING_CADENA_Html_003.$STRING_CADENA_Html_004.$STRING_CADENA_Html_005.$STRING_CADENA_Html_006.$TRING_CADENA_Html_007;


    ////****Mandamos 
    correos("Convenios", "Estus de Autorizacion Convenio",$mail_DIRG,"",$strin_Fina_HTML );
      /////**Fin Mensage de  Autorizacion del   Conveniod 
      $_REQUEST['autorizaran'] =NULL;      
      ///******Fin  para  enviar    correo Autorizaciona   Jefe  inteligencia   Comercial  
  
    
    
    
  }

  
  
  
  
   //****************************************IFS DE DIRECCIÓN COMERCIAL
   IF (isset($_REQUEST['autorizardc'])){
     
      $remisionautoriza = $_REQUEST['autorizardc'];                 
   $actualizautorizardc = sprintf("UPDATE encabeza_convenio SET estatus='E',fechacomp_real=%s, au_dc=1 WHERE n_remision=%s",
  GetSQLValueString($date, "date"),
           GetSQLValueString($_REQUEST['autorizardc'],'int'));
    $queryrautorizardc=mysqli_query($conecta1, $actualizautorizardc) or die (mysqli_error($conecta1));
    
    $acutalizarconveniodetalle=sprintf("UPDATE detalle_convenio SET estatus='E' WHERE n_remision=%s",
            GetSQLValueString($_REQUEST['autorizardc'],'int'));
   $queryrautorizardcdetalle=mysqli_query($conecta1, $acutalizarconveniodetalle) or die (mysqli_error($conecta1));
    
     //notificar por mail autorizacion de Convenio por parte de direccion Comercial 
         ///******Inicio para  enviar    correo Autorizaciona   Jefe  inteligencia   Comercial
         ///**Obtenemos el correo del  Jefe  de Inteligencia  Comercial 
        $strin_IntComer =  "SELECT  email FROM usuarios_locales  where  rol =69";
        ////** Realizamos  Qery 
        $qery_IntComer = mysqli_query($conecta1, $strin_IntComer);
        ////**Fetch  
        $fetch_IntComer = mysqli_fetch_array($qery_IntComer);
        ///***Obtenemos el  mail del  correo 
        $mail_IntComer = $fetch_IntComer['email'];   
            
           /// send_correo_autorizacion($mail_DIRG,$remisionautoriza);
            ///*****
            ///**Inicio Mensage de  Autorizacion del   Conveniod 
      ///** Obtenemos la Informacion  Basica del  convenio para  Agregar  al correo
            $string_infoconve = sprintf("SELECT *  FROM  encabeza_convenio where n_remision =%s", 
                  GetSQLValueString($_REQUEST['autorizardc'],'int'));
       ///**+ Realizaos  el Qery  
            $qery_infoconve = mysqli_query($conecta1, $string_infoconve);
       //***  Realizamos el Feth 
            $fetch_infoconve = mysqli_fetch_array($qery_infoconve); 
 //****-------------------------------------------------------------------------------           
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
            
            
////*****Si el Pedido  no esta  Autoirzado  No se envia  en  correo*********************************************************   
    ////*****Informacion a Enviar al  Gerente
    $STRING_CADENA_Html_001 = "<table><thead><th><h4>Buen Día se ha Autorizado Un  Convenio.</h4></th><thead>";        
    $STRING_CADENA_Html_002 = "<tbody><tr><td><h4>Folio Convenio: ".$fetch_infoconve['n_remision']."</h4></td></tr>";
    $STRING_CADENA_Html_003 = "<tr><td>N# Cliente ".$fetch_infoconve['cve_cte']."</td><td> Cliente :".$fetch_infoconve['nom_cte']."</td></tr>";
    $STRING_CADENA_Html_004 = "<tr><td>Fecha Inicial : ".$fetch_infoconve['fechainicio']."</td><td>Fecha Fin: ".$fetch_infoconve['fechafin']."</td></tr>";
    $STRING_CADENA_Html_005 = "<tr><td>Monto de Convenio: ". "$".number_format($fetch_infoconve['total'])."</td></tr>"; 
    ///$STRING_CADENA_Html_006= "<tr><td> Correo Destino Inteligencia :".$mail_IntComer."<td> Correo Gerente".$mail_gerente."</td><td>Correo  Agente".$correo_agente."</td></tr>"; 
    $TRING_CADENA_Html_007 = "</tbody></table>";
    /////****
    //******************        
    ////***Armamos la informacion Html  a Enviar
    $strin_Fina_HTML =$STRING_CADENA_Html_001.$STRING_CADENA_Html_002 .$STRING_CADENA_Html_003.$STRING_CADENA_Html_004.$STRING_CADENA_Html_005.$TRING_CADENA_Html_007;
     /****    Mandamos Correo 
            * ***** Al Gerente  y  Agente  ademas  del jefe de  Inteligenia comercial as
           */
                $array_cc =  array();
                $array_cc[0] =$mail_IntComer;
                $array_cc[1] =$mail_gerente;
                $array_cc[2] =$correo_agente;
                           

            ////****Mandamos bmesta@agroversa.com.mx
            correos("Convenios", "Estus de Autorizacion Convenio",'BCASTANEDA@agroversa.com.mx',$array_cc,$strin_Fina_HTML );
      /////**Fin Mensage de  Autorizacion del   Conveniod 
      $_REQUEST['autorizardc'] =NULL;      
      ///******Fin  para  enviar    correo Autorizaciona   Jefe  inteligencia   Comercial  
 }
  

  
  
  
   if($idgerente!=='10' && $idgerente!=='69' || $idgerente!=='96' || $idgerente!=='100'){
    $consultaremisiones = sprintf("SELECT * FROM relacion_gerentes_encabeza_convenio WHERE cve_gte=%s AND estatus = 'A' AND au_dc=0 AND au_an=0 AND au_ge=0 ORDER BY fecha_alta DESC ",
                        GetSQLValueString($idgerente, "int"));
 }
 if ($idgerente=='10'){
   $consultaremisiones = "SELECT * FROM relacion_gerentes_encabeza_convenio WHERE au_dc = 0 AND au_ge=1 AND au_an=1 AND estatus = 'A' ORDER BY fecha_alta DESC ";    
 }
 if ($idgerente=='69'){
   $consultaremisiones = "SELECT * FROM relacion_gerentes_encabeza_convenio WHERE au_dc = 0 AND au_ge=1 AND au_an=0 AND estatus = 'A' ORDER BY fecha_alta DESC ";    
     
 }
  if ($idgerente=='96'){
   $consultaremisiones = "SELECT * FROM relacion_gerentes_encabeza_convenio WHERE au_dc = 0 AND au_ge=1 AND au_an=0 AND estatus = 'A' ORDER BY fecha_alta DESC ";    
     
 }
 if ($idgerente=='100'){
    $consultaremisiones = "SELECT * FROM relacion_gerentes_encabeza_convenio WHERE au_dc = 0 AND au_ge=1 AND au_an=0 AND estatus = 'A' ORDER BY fecha_alta DESC ";    
      
  }
 $queryremisiones=mysqli_query($conecta1, $consultaremisiones) or die (mysqli_error($conecta1));
 
 
 ?>
<script>

function myFunction() {
    var person = prompt("Por favor escriba la razón por la cuál rechazó", "Razón");

   
    
    document.forma1.razonrechaza.value = person;
    
     return confirm('¿Está Seguro de RECHAZAR?');

    
}
</script>
<form method="post" action="pedidos_autoriza_gerentes.php" name="forma1">
<h3>Convenios por Autorizar <?php //echo $consultaremisiones; ?></h3>
<div class="table-responsive">
          <table  class="table table-responsive table-hover">
             <thead>
                 <tr>
                     <th>Folio</th>
                     <th>Fecha Alta</th>
                     <th>Cliente</th>
                    
                     <th>Agente</th>
                     <th>Total</th>
                     <th>Fecha Inicio</th>
                     <th>Fecha Fin</th>
                     <th>Autorización</th>
                 </tr>
             </thead>
             <tbody>
                 <?php 
         
                 WHILE ($registro1= mysqli_fetch_array($queryremisiones)){ 
                     
                     $querylista=sprintf("SELECT * FROM ".$listaprecios." WHERE ItemCode=%s",
                     GetSQLValueString($registro1['cve_prod'], "text"));
                     $resultadolista = mssql_query($querylista);
                     $fetchlista = mssql_fetch_array($resultadolista);
                     $codigolista = $fetchlista['ItemCode'];
                     
                     ?>
                 <tr>
                     
                     <?php
                     
                     if($idgerente!=='10' && $idgerente!=='69' && $idgerente!=='96'  && $idgerente!=='100'){
                     
                     ?>
                    <td><a href="convenio_detalle_representante.php?remision=<?php echo $registro1['n_remision'];  ?>" target="_blank" onClick="window.open(this.href, this.target, 'width=800,height=500,scrollbars=yes'); return false;"><?php echo $registro1['n_remision'];?></a></td>        
                    <?php
                     }?>
                    
                    <?php
                    if($idgerente=='10' || $idgerente=='69'){
                    ?>
                    <td><a href="convenio_detalle_representante_analista.php?remision=<?php echo $registro1['n_remision'];  ?>" target="_blank" onClick="window.open(this.href, this.target, 'width=800,height=500,scrollbars=yes'); return false;"><?php echo $registro1['n_remision'];?></a></td>        

                    <?php }?>
                    
                    
                    <td><?php echo $registro1['fecha_alta'];?></td> 
                    <td><?php echo $registro1['nom_cte'];?></td> 
               
                     <td><?php echo $registro1['nom_age'];?></td> 
                     <td><?php echo '$'.number_format(ceil($registro1['total']));?></td> 
                     <td><?php echo $registro1['fechainicio'];?></td>
                     <td><?php echo $registro1['fechafin'];?></td>
                     
                    <?php if ($numerogerente!=10 && $numerogerente!=69  && $numerogerente!=96 && $numerogerente!=100  ){ ?> 
                    <td><a href="convenios_autoriza_gerentes.php?autorizar=<?php echo $registro1['n_remision']; ?>&productoid=<?php echo $registro1['cve_prod']; ?>&precio=<?php echo $registro1['precio_representante']; ?>&preciogerente=<?php echo $fetchlista['Price'] ?>"  onclick="return confirm('¿Está Seguro de AUTORIZAR?')"><img src="images/circle.png"/></a>&nbsp;&nbsp;&nbsp;&nbsp;
                        <a href="convenios_rechazo_gerentes.php?rechazar=<?php echo $registro1['n_remision']; ?>&productoid=<?php echo $registro1['cve_prod']; ?>&typUsu=79"  target="_blank" onClick="window.open(this.href, this.target, 'width=800,height=500,scrollbars=yes'); return false;"><img src="images/delete.png"/></a></td>
                    
                    
                    <?php
                    
                    //<td> DE DIRECCIÓN COMERCIAL
                    } if ($numerogerente==10){   ?>
                    <td><a href="convenios_autoriza_gerentes.php?autorizardc=<?php echo $registro1['n_remision']; ?>&productoiddc=<?php echo $registro1['cve_prod']; ?>&preciodc=<?php echo $registro1['Price']; ?>&preciogerentedc=<?php echo $fetchlista['Price'] ?>"  onclick="return confirm('¿Está Seguro de AUTORIZAR?')"><img src="images/circle.png"/></a>&nbsp;&nbsp;&nbsp;&nbsp;
                        <a href="convenios_rechazo_gerentes.php?rechazar=<?php echo $registro1['n_remision']; ?>&productoiddc=<?php echo $registro1['cve_prod']; ?>&typUsu=10" onClick="window.open(this.href, this.target, 'width=800,height=500,scrollbars=yes'); return false;"><img src="images/delete.png"/></a></td>

                     <?php }
                      if ($numerogerente==69){  ?>
                         
                   <td><a href="convenios_autoriza_gerentes.php?autorizaran=<?php echo $registro1['n_remision']; ?>&productoidan=<?php echo $registro1['cve_prod']; ?>&preciodc=<?php echo $registro1['Price']; ?>&preciogerentedc=<?php echo $fetchlista['Price'] ?>"  onclick="return confirm('¿Está Seguro de AUTORIZAR?')"><img src="images/circle.png"/></a>&nbsp;&nbsp;&nbsp;&nbsp;
                        <a href="convenios_rechazo_gerentes.php?rechazar=<?php echo $registro1['n_remision']; ?>&typUsu=69&productoidan=<?php echo $registro1['cve_prod']; ?>"  onClick="window.open(this.href, this.target, 'width=800,height=500,scrollbars=yes'); return false;"><img src="images/delete.png"/></a></td>
 
                   <td style="display:none;"><input type="text" id="razonrechaza" name="razonrechaza" class="form-control" readonly ></td> 
                    <?php   } ?>
                 





                 </tr>
                   <?php 
                   

                   
                 } ?>
             </tbody>


         </table>

             </div>


</form>


<?php require_once 'foot.php';?>