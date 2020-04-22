
<?PHP
/*
********	 INFORMACION ARCHIVO ***************** 
	Nombre  Archivo : login.php  
 	Fecha  Creacion : ???? 
	Descripcion  : 
 *                  Login Para Incicio  de Session 
 *     
 *      Modificaciones : 
 *           
 *              10/11/2017  
 *                          * Agregado de  Usuarios de DeSARROLLO
 *                          * Generamos  Variable   $_SESSION['ConteCuent']
 *                      ESTRUCTURA    
 *                          $_SESSION['ConteJsCuent'] ={
 *                                  "cc"=>000,                                
 *                                  "cuenta"=>00000,
 *                                  "SYScuent"=>Sys0000000    
<?PHP
/*
********	 INFORMACION ARCHIVO ***************** 
	Nombre  Archivo : login.php  
 	Fecha  Creacion : ???? 
	Descripcion  : 
 *                  Login Para Incicio  de Session 
 *     
 *      Modificaciones : 
 *           
 *              10/11/2017  
 *                          * Agregado de  Usuarios de DeSARROLLO
 *                          * Generamos  Variable   $_SESSION['ConteCuent']
 *                      ESTRUCTURA    
 *                          $_SESSION['ConteJsCuent'] ={
 *                                  "cc"=>000,                                
 *                                  "cuenta"=>00000,
 *                                  "SYScuent"=>Sys0000000    
 *                          }
  */




// Iniciar sesión
   session_start();
  
   //Redireccionamiento Temporal por Mantenmimiento
  // $restrictGoTo = "mantenimiento.html";
   //header("Location: ". $restrictGoTo); 
 
     require_once('conexion_sap/sap.php');
     require_once('formato_datos.php');   //para evitar poner la funcion de conversion de tipos de datos
    require_once('Connections/conecta1.php');
    require_once ('funciones.php');
      
    
    
    
     function getRealIP() {
             if (!empty($_SERVER['HTTP_CLIENT_IP']))
                 return $_SERVER['HTTP_CLIENT_IP'];
 
             if (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
                 return $_SERVER['HTTP_X_FORWARDED_FOR'];
 
             return $_SERVER['REMOTE_ADDR'];
         }
 
  
   
    
 // Si se ha enviado el formulario
    $usuario = rtrim($_REQUEST['usuario']);
    $clave =rtrim( $_REQUEST['clave']);
     $centinela=0;
     
       $getip=getRealIP();   
       $ip=substr($getip, 0, 7);
     
     
    if (isset($_REQUEST['usuario']) && isset($_REQUEST['clave']))
    {
   // echo $usuario ."<br>";
    //echo $clave ."<br>";
    
   //echo "segunda  vez";
    // Comprobar que el usuario está autorizado a entrar
      
     //mssql_select_db("AGROVERSA");
     $query=("SELECT RateDate, Currency, Rate FROM ORTT where Rate>0 ORDER   BY RateDate DESC ");
   $tabla = mssql_query($query);
   $l=mssql_fetch_array($tabla); 
   
   $query_result=("SELECT OSLP.SlpCode FROM OSLP WHERE OSLP.U_usuario='$usuario' AND OSLP.U_contra='$clave'");	 
   $query_result2=("SELECT OSLP.SlpCode, OSLP.SlpName, OSLP.U_rol, OSLP.U_tipo2, OSLP.U_email,OSLP.Memo,OSLP.U_Zona   FROM OSLP WHERE OSLP.U_usuario='$usuario' AND OSLP.U_contra='$clave'");	 
   $result=mssql_query($query_result);
    $nfilas = mssql_num_rows($result);
    
    $result2=mssql_query($query_result2);
    $datos2=mssql_fetch_assoc($result2);
    
       
    mssql_free_result($result);
    mssql_free_result($result2);
    mssql_free_result($tabla);
          
             
    
    /////Datos necesarios para la captura de la proyeccion EAGA
     $string_mes=("SELECT * FROM config_meses where activo=1 order by id");
     $sql_mes=mysqli_query($conecta1,$string_mes) or die (mysqli_error($conecta1));
     
     $contador=1;
      while ($row=  mysqli_fetch_array($sql_mes))
              
       {
          switch ($contador) {
              case 1:
              $mes1=$row['mes'];
              $anio1=$row['anio'];    
                  $contador=$contador+1;
                  break;
 
              case 2:
                 $mes2=$row['mes'];
              $anio2=$row['anio'];    
                   $contador=$contador+1;
                  break;
              
               case 3:
                   $mes3=$row['mes'];
                  $anio3=$row['anio'];   
                    $contador=$contador+1;
                   break;
          }
       }
       
        $_SESSION['mes1']=$mes1;     
        $_SESSION['mes2']=$mes2;   
        $_SESSION['mes3']=$mes3;   
        
        $_SESSION['anio1']=$anio1; 
        $_SESSION['anio2']=$anio2;   
        $_SESSION['anio3']=$anio3;   
    
    //////////EAGA  14-10-2016
    
    
    
     
    
    //Aquí va ir una sentencia donde vamos a comparar el campo de U_tipo  para identificar el tipo de usuario que esta entrando
    //1 = Agente 2 =Autoriza 3 = Ventas Facturacion 
      $MM_redirectLoginSuccess = "index.php";
      $MM_redirectLoginFailed = "login.php";
     
      
    //echo $usuario. "<br>";
    //echo $clave. "<br>";
    //echo $nfilas. "<br>";
        
    // Los datos introducidos son correctos
       if ($nfilas > 0)
       {
           
           
           
           
           
           
             $usuario_valido = $usuario;
             $idusuario;
             // Con register_globals On
             // session_register ("usuario_valido");
             // Con register_globals Off
   ////******Obtenemos la Informacion  para  la ;
              $strGETInofo_Gastos=sprintf("SELECT cc,cuenta,cuenta_sys,nom_age,cve_gte  FROM pedidos.relacion_gerentes  WHERE cve_age =%s",
                        GetSQLValueString($datos2['SlpCode'],"int"));
              $queryByGastos=mysqli_query($conecta1, $strGETInofo_Gastos) or die (mysqli_error($conecta1));
              $ResGasto = mysqli_fetch_array($queryByGastos);
              
              $aregGaastos= Array(
                             "cc"=>$ResGasto['cc'],
                             "cuenta"=>$ResGasto['cuenta'],
                             "cuenta_sys"=>$ResGasto['cuenta_sys'],
                             "nomAge"=>$ResGasto['nom_age'],
                             "zona" =>$ResGasto['cve_gte']
              );
             $_SESSION['ConteCuent']= json_encode($aregGaastos);
              
         ///*********FIN  Obtener  Inforamcion Plataforma de Gastos    
 //Codigo normal, si hay que bloquear acceso hay que comentar el codigo
                     $_SESSION["usuario_valido"] = $usuario_valido;
                     $_SESSION["usuario_agente"] = $datos2['SlpCode'];
                     $_SESSION["usuario_nombre"] = $datos2['SlpName'];
                     $_SESSION["usuario_rol"] = $datos2['U_rol'];
                     $_SESSION["usuario_tipo"] = $datos2['U_tipo2'];
                     $_SESSION["Agente"]=$datos2['Memo'];
                     $_SESSION["Zona"]=$datos2['U_Zona'];
                     $_SESSION["tipo_cambio"] = $l['Rate']; 
                     $_SESSION["email"]=$datos2['U_email'];
                     $_SESSION["incrementopp"]=6;   //variable para el concepto incremento al precio por credito  10-10-2016 EAGA
                     $_SESSION['beta'] = 1;   ///1 habilita opciones 0 Deshabilita
                 
                     $stringzona=sprintf("SELECT * FROM tarifa_sd WHERE zona=%s",
                     GetSQLValueString($datos2['U_Zona'], "text"));
                     $query_string1=mysqli_query($conecta1, $stringzona) or die (mysqli_error($conecta1));
                     $assoczona = mysqli_fetch_assoc($query_string1);
                     
                     $tarifap=$assoczona['tarifa_p'];
                     $tarifal=$assoczona['tarifa_l'];
                     
                     $_SESSION['tarifa_global']= $tarifap;
                     $_SESSION['tipousuario_proyeccion']=101;   /// 1=Agente   2=Gerente  3=Planeador Validar para conocer si es agente Gerente o Planeador    utilizado en proyeccion EAGA 14-10-2016
                       entrada($usuario,$getip);  ///Registrar la entrada en la tabla log_in_out   
                     
             header("Location: " . $MM_redirectLoginSuccess );
         
             
    
       }else{
           
           
          //en esta opcion sabes que el usuario que se quiere firmar no es un agente
      
       mysql_select_db($database_conecta1,$conecta1);  //Seleccionar la Base de datos
       
       $string1=sprintf("Select * FROM usuarios_locales WHERE usuario=%s and clave=%s",
                        GetSQLValueString($usuario,"text"),
            GetSQLValueString($clave,"text"));
                                    
       
                          $query_string1=mysqli_query($conecta1, $string1) or die (mysqli_error($conecta1));
       $nregistros=mysqli_num_rows($query_string1);
       
       if ($nregistros>0){
         $reg = mysqli_fetch_array($query_string1);
         //$paginadestino=$reg['pagina_inicio']; //hay que validar para que utilice el campo de 
                                   
                                 
                                  $paginadestino=$reg['pagina_iniciocronos']; //hay que validar para que utilice el campo de 
                                   $_SESSION['beta'] = 1;
                                   $_SESSION["usuario_valido"] = $usuario;
                                   $_SESSION["nombre_local"] = $reg['nombre'];
           $_SESSION["email"]=$reg['email'];
           $_SESSION["p_inicio"]=$paginadestino;
           $_SESSION["usuario_rol"] = $reg['rol'];
                                   $_SESSION["tipo_cambio"] = $l['Rate']; 
                                   $_SESSION["agente_default"] = $reg['extra1'];
                                   $_SESSION["cliente_default"] = $reg['extra2'];
                                   $_SESSION["zona1"] = $reg['extra1'];
                                   $_SESSION["zona2"] = $reg['extra2'];   //Cuando es un gerente este campo contiene el numero de zona al que representa
                                   $_SESSION["descripcion"] = $reg['nombre'];
                                   $_SESSION['id']=$reg['id'];
           $_SESSION['tipousuario_proyeccion']=$reg['rol']; 
                                  
                                   
                                   ////******Obtenemos la Informacion  para  la ;
                                       ////****vARIASDAS 
                                         $_SESSION["usuario_agente"] =$reg['rol'];
                                         $aregGaastos= Array(
                                                          "cc"=>$reg['cc'],
                                                          "cuenta"=>$reg['cuenta'],
                                                          "cuenta_sys"=>$reg['cuenta_sys'],
                                                          "nomAge"=>$reg['nombre'],
                                                          "zona" =>$reg['extra2'], 
                                                          "rol" => $reg['rol']
                                           );
                                          $_SESSION['ConteCuent']= json_encode($aregGaastos);
                                   ///*********FIN  Obtener  Inforamcion Plataforma de Gastos  
                                   entrada($usuario,$getip);  ///Registrar la entrada en la tabla log_in_out   
          header("Location: " . $paginadestino );
       }else{
                              $centinela=1;
         
       }
       
     }	
   
    }
 ?>
 <!DOCTYPE html>
 <html lang="en">
     <head>
         <meta http-equiv="content-type" content="text/html; charset=UTF-8"> 
         <meta charset="utf-8">
         <title>Acceso a Plataforma Cronos</title>
         <meta name="generator" content="Agroveresa" />
         <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
         <meta name="description" content="Acceso al modulo Cronos" />
         <link href="select2/bootstrap.min.css" rel="stylesheet">   
         
      
 
         <!-- CSS code from Bootply.com editor -->
         
         <style type="text/css">
             .modal-footer {   border-top: 0px; }
             /***Definimos la Imangen de  Fondo ****/  
               .fondo-001{
               width: 100%;
               height: 100%;
               background-image:url("arte_cronos/fon/fondo_1280_800.jpg");
               background-position: top center;
               background-size: cover;
              }
              .modal-content ,submit :hover 
                  {
                 background: rgba(234,245,229,0.69);
                 background: -moz-linear-gradient(left, rgba(234,245,229,0.69) 0%, rgba(249,252,248,0.69) 0%, rgba(101,173,67,0.69) 0%, rgba(101,173,67,0.53) 76%);
                 background: -webkit-gradient(left top, right top, color-stop(0%, rgba(234,245,229,0.69)), color-stop(0%, rgba(249,252,248,0.69)), color-stop(0%, rgba(101,173,67,0.69)), color-stop(76%, rgba(101,173,67,0.53)));
                 background: -webkit-linear-gradient(left, rgba(234,245,229,0.69) 0%, rgba(249,252,248,0.69) 0%, rgba(101,173,67,0.69) 0%, rgba(101,173,67,0.53) 76%);
                 background: -o-linear-gradient(left, rgba(234,245,229,0.69) 0%, rgba(249,252,248,0.69) 0%, rgba(101,173,67,0.69) 0%, rgba(101,173,67,0.53) 76%);
                 background: -ms-linear-gradient(left, rgba(234,245,229,0.69) 0%, rgba(249,252,248,0.69) 0%, rgba(101,173,67,0.69) 0%, rgba(101,173,67,0.53) 76%);
                 background: linear-gradient(to right, rgba(234,245,229,0.69) 0%, rgba(249,252,248,0.69) 0%, rgba(101,173,67,0.69) 0%, rgba(101,173,67,0.53) 76%);
                 filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#eaf5e5', endColorstr='#65ad43', GradientType=1 );
                 }
              .btn-primary{
                     background: rgba(142,223,104,1);
                 background: -moz-linear-gradient(left, rgba(142,223,104,1) 0%, rgba(142,223,104,1) 17%, rgba(142,223,104,1) 100%);
                 background: -webkit-gradient(left top, right top, color-stop(0%, rgba(142,223,104,1)), color-stop(17%, rgba(142,223,104,1)), color-stop(100%, rgba(142,223,104,1)));
                 background: -webkit-linear-gradient(left, rgba(142,223,104,1) 0%, rgba(142,223,104,1) 17%, rgba(142,223,104,1) 100%);
                 background: -o-linear-gradient(left, rgba(142,223,104,1) 0%, rgba(142,223,104,1) 17%, rgba(142,223,104,1) 100%);
                 background: -ms-linear-gradient(left, rgba(142,223,104,1) 0%, rgba(142,223,104,1) 17%, rgba(142,223,104,1) 100%);
                 background: linear-gradient(to right, rgba(142,223,104,1) 0%, rgba(142,223,104,1) 17%, rgba(142,223,104,1) 100%);
                 filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#8edf68', endColorstr='#8edf68', GradientType=1 );
                 }
                 img { 
                     height: 80px;
                     margin-top: -16px;
                     margin-right: 40px;
                 }
             
         </style>
     </head>
     
     <!-- HTML code from Bootply.com editor -->
     
     <body  >
         
         <!--login modal-->
 <div id="loginModal" class="modal show fondo-001" tabindex="-1" role="dialog" aria-hidden="true">
   <div class="modal-dialog">
   <div class="modal-content">
       <div class="modal-header">
           <!--  <h2 class="text-center">Acceso a Cronos</h2> --> 
           <img  src="arte_cronos/fon/logo_auto_12_2016.png">  
      </div>
       <div class="modal-body">
           <form class="form col-md-12 center-block" method="POST" action="login.php">
             <div class="form-group">
               <input type="text" class="form-control input-lg" required placeholder="usuario" name="usuario">
             </div>
             <div class="form-group">
               <input type="password" class="form-control input-lg" required placeholder="Password" name="clave">
             </div>
             <div class="form-group">
               <input type="submit" class="btn btn-primary btn-lg btn-block" value="Accesar" name="accesar">
              
             </div>
           </form>
       </div>
      
       <div class="modal-footer">
            <?php if ($centinela==1){  ?>
           <div class="col-md-12">
              <p class="text-danger text-center">Datos Incorrectos</p></span>
     </div>
           <?php }   ?>
       </div>
       
   </div>
   </div>
 </div>
         
         <script type='text/javascript' src="//ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
 
 
         <script type='text/javascript' src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
 
 
         
         <!-- JavaScript jQuery code from Bootply.com editor  -->
         
         <script type='text/javascript'>
         
         $(document).ready(function() {
         
             
         
         });
         
         </script>
         
       
         
         <style>
             .ad {
               position: absolute;
               bottom: 70px;
               right: 48px;
               z-index: 992;
               background-color:#f3f3f3;
               position: fixed;
               width: 155px;
               padding:1px;
             }
             
             .ad-btn-hide {
               position: absolute;
               top: -10px;
               left: -12px;
               background: #fefefe;
               background: rgba(240,240,240,0.9);
               border: 0;
               border-radius: 26px;
               cursor: pointer;
               padding: 2px;
               height: 25px;
               width: 25px;
               font-size: 14px;
               vertical-align:top;
               outline: 0;
             }
             
             .carbon-img {
               float:left;
               padding: 10px;
             }
             
             .carbon-text {
               color: #888;
               display: inline-block;
               font-family: Verdana;
               font-size: 11px;
               font-weight: 400;
               height: 60px;
               margin-left: 9px;
               width: 142px;
               padding-top: 10px;
             }
             
             .carbon-text:hover {
               color: #666;
             }
             
             .carbon-poweredby {
               color: #6A6A6A;
               float: left;
               font-family: Verdana;
               font-size: 11px;
               font-weight: 400;
               margin-left: 10px;
               margin-top: 13px;
               text-align: center;
             }
         </style>
       
         
     </body>
 </html>
 *                          }
  */




// Iniciar sesión
   session_start();
  
  //Redireccionamiento Temporal por Mantenmimiento
 // $restrictGoTo = "mantenimiento.html";
  //header("Location: ". $restrictGoTo); 

    require_once('conexion_sap/sap.php');
    require_once('formato_datos.php');   //para evitar poner la funcion de conversion de tipos de datos
   require_once('Connections/conecta1.php');
   require_once ('funciones.php');
     
   
   
   
    function getRealIP() {
            if (!empty($_SERVER['HTTP_CLIENT_IP']))
                return $_SERVER['HTTP_CLIENT_IP'];

            if (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
                return $_SERVER['HTTP_X_FORWARDED_FOR'];

            return $_SERVER['REMOTE_ADDR'];
        }

 
  
   
// Si se ha enviado el formulario
   $usuario = rtrim($_REQUEST['usuario']);
   $clave =rtrim( $_REQUEST['clave']);
    $centinela=0;
    
      $getip=getRealIP();   
      $ip=substr($getip, 0, 7);
    
    
   if (isset($_REQUEST['usuario']) && isset($_REQUEST['clave']))
   {
  // echo $usuario ."<br>";
   //echo $clave ."<br>";
   
	//echo "segunda  vez";
   // Comprobar que el usuario está autorizado a entrar
     
    //mssql_select_db("AGROVERSA");
    $query=("SELECT RateDate, Currency, Rate FROM ORTT where Rate>0 ORDER   BY RateDate DESC ");
	$tabla = mssql_query($query);
	$l=mssql_fetch_array($tabla); 
	
	$query_result=("SELECT OSLP.SlpCode FROM OSLP WHERE OSLP.U_usuario='$usuario' AND OSLP.U_contra='$clave'");	 
	$query_result2=("SELECT OSLP.SlpCode, OSLP.SlpName, OSLP.U_rol, OSLP.U_tipo2, OSLP.U_email,OSLP.Memo,OSLP.U_Zona   FROM OSLP WHERE OSLP.U_usuario='$usuario' AND OSLP.U_contra='$clave'");	 
	$result=mssql_query($query_result);
	 $nfilas = mssql_num_rows($result);
	 
	 $result2=mssql_query($query_result2);
	 $datos2=mssql_fetch_assoc($result2);
	 
	 	 
	 mssql_free_result($result);
	 mssql_free_result($result2);
	 mssql_free_result($tabla);
         
            
	 
	 /////Datos necesarios para la captura de la proyeccion EAGA
    $string_mes=("SELECT * FROM config_meses where activo=1 order by id");
    $sql_mes=mysqli_query($conecta1,$string_mes) or die (mysqli_error($conecta1));
    
    $contador=1;
     while ($row=  mysqli_fetch_array($sql_mes))
             
      {
         switch ($contador) {
             case 1:
             $mes1=$row['mes'];
             $anio1=$row['anio'];    
                 $contador=$contador+1;
                 break;

             case 2:
                $mes2=$row['mes'];
             $anio2=$row['anio'];    
                  $contador=$contador+1;
                 break;
             
              case 3:
                  $mes3=$row['mes'];
                 $anio3=$row['anio'];   
                   $contador=$contador+1;
                  break;
         }
      }
      
			 $_SESSION['mes1']=$mes1;     
			 $_SESSION['mes2']=$mes2;   
			 $_SESSION['mes3']=$mes3;   
			 
			 $_SESSION['anio1']=$anio1; 
			 $_SESSION['anio2']=$anio2;   
			 $_SESSION['anio3']=$anio3;   
	 
	 //////////EAGA  14-10-2016
	 
	 
	 
	  
	 
	 //Aquí va ir una sentencia donde vamos a comparar el campo de U_tipo  para identificar el tipo de usuario que esta entrando
	 //1 = Agente 2 =Autoriza 3 = Ventas Facturacion 
     $MM_redirectLoginSuccess = "index.php";
     $MM_redirectLoginFailed = "login.php";
    
     
	 //echo $usuario. "<br>";
	 //echo $clave. "<br>";
	 //echo $nfilas. "<br>";
		   
   // Los datos introducidos son correctos
      if ($nfilas > 0)
      {
          
          
          
          
          
          
            $usuario_valido = $usuario;
            $idusuario;
            // Con register_globals On
            // session_register ("usuario_valido");
            // Con register_globals Off
	////******Obtenemos la Informacion  para  la ;
             $strGETInofo_Gastos=sprintf("SELECT cc,cuenta,cuenta_sys,nom_age,cve_gte  FROM pedidos.relacion_gerentes  WHERE cve_age =%s",
		                   GetSQLValueString($datos2['SlpCode'],"int"));
             $queryByGastos=mysqli_query($conecta1, $strGETInofo_Gastos) or die (mysqli_error($conecta1));
             $ResGasto = mysqli_fetch_array($queryByGastos);
             
             $aregGaastos= Array(
                            "cc"=>$ResGasto['cc'],
                            "cuenta"=>$ResGasto['cuenta'],
                            "cuenta_sys"=>$ResGasto['cuenta_sys'],
                            "nomAge"=>$ResGasto['nom_age'],
                            "zona" =>$ResGasto['cve_gte']
             );
            $_SESSION['ConteCuent']= json_encode($aregGaastos);
             
        ///*********FIN  Obtener  Inforamcion Plataforma de Gastos    
//Codigo normal, si hay que bloquear acceso hay que comentar el codigo
                    $_SESSION["usuario_valido"] = $usuario_valido;
                    $_SESSION["usuario_agente"] = $datos2['SlpCode'];
                    $_SESSION["usuario_nombre"] = $datos2['SlpName'];
                    $_SESSION["usuario_rol"] = $datos2['U_rol'];
                    $_SESSION["usuario_tipo"] = $datos2['U_tipo2'];
                    $_SESSION["Agente"]=$datos2['Memo'];
                    $_SESSION["Zona"]=$datos2['U_Zona'];
                    $_SESSION["tipo_cambio"] = $l['Rate']; 
                    $_SESSION["email"]=$datos2['U_email'];
                    $_SESSION["incrementopp"]=6;   //variable para el concepto incremento al precio por credito  10-10-2016 EAGA
                    $_SESSION['beta'] = 1;   ///1 habilita opciones 0 Deshabilita
                
                    $stringzona=sprintf("SELECT * FROM tarifa_sd WHERE zona=%s",
                    GetSQLValueString($datos2['U_Zona'], "text"));
                    $query_string1=mysqli_query($conecta1, $stringzona) or die (mysqli_error($conecta1));
                    $assoczona = mysqli_fetch_assoc($query_string1);
                    
                    $tarifap=$assoczona['tarifa_p'];
                    $tarifal=$assoczona['tarifa_l'];
                    
                    $_SESSION['tarifa_global']= $tarifap;
                    $_SESSION['tipousuario_proyeccion']=101;   /// 1=Agente   2=Gerente  3=Planeador Validar para conocer si es agente Gerente o Planeador    utilizado en proyeccion EAGA 14-10-2016
                      entrada($usuario,$getip);  ///Registrar la entrada en la tabla log_in_out   
                    
            header("Location: " . $MM_redirectLoginSuccess );
        
            
	 
      }else{
          
          
	       //en esta opcion sabes que el usuario que se quiere firmar no es un agente
		 
		  mysql_select_db($database_conecta1,$conecta1);  //Seleccionar la Base de datos
		  
		  $string1=sprintf("Select * FROM usuarios_locales WHERE usuario=%s and clave=%s",
		                   GetSQLValueString($usuario,"text"),
				   GetSQLValueString($clave,"text"));
                                   
			
                         $query_string1=mysqli_query($conecta1, $string1) or die (mysqli_error($conecta1));
			$nregistros=mysqli_num_rows($query_string1);
			
			if ($nregistros>0){
				$reg = mysqli_fetch_array($query_string1);
				//$paginadestino=$reg['pagina_inicio']; //hay que validar para que utilice el campo de 
                                  
                                
                                 $paginadestino=$reg['pagina_iniciocronos']; //hay que validar para que utilice el campo de 
                                  $_SESSION['beta'] = 1;
                                  $_SESSION["usuario_valido"] = $usuario;
                                  $_SESSION["nombre_local"] = $reg['nombre'];
				  $_SESSION["email"]=$reg['email'];
				  $_SESSION["p_inicio"]=$paginadestino;
				  $_SESSION["usuario_rol"] = $reg['rol'];
                                  $_SESSION["tipo_cambio"] = $l['Rate']; 
                                  $_SESSION["agente_default"] = $reg['extra1'];
                                  $_SESSION["cliente_default"] = $reg['extra2'];
                                  $_SESSION["zona1"] = $reg['extra1'];
                                  $_SESSION["zona2"] = $reg['extra2'];   //Cuando es un gerente este campo contiene el numero de zona al que representa
                                  $_SESSION["descripcion"] = $reg['nombre'];
                                  $_SESSION['id']=$reg['id'];
				  $_SESSION['tipousuario_proyeccion']=$reg['rol']; 
                                 
                                  
                                  ////******Obtenemos la Informacion  para  la ;
                                      ////****vARIASDAS 
                                        $_SESSION["usuario_agente"] =$reg['rol'];
                                        $aregGaastos= Array(
                                                         "cc"=>$reg['cc'],
                                                         "cuenta"=>$reg['cuenta'],
                                                         "cuenta_sys"=>$reg['cuenta_sys'],
                                                         "nomAge"=>$reg['nombre'],
                                                         "zona" =>$reg['extra2'], 
                                                         "rol" => $reg['rol']
                                          );
                                         $_SESSION['ConteCuent']= json_encode($aregGaastos);
                                  ///*********FIN  Obtener  Inforamcion Plataforma de Gastos  
                                  entrada($usuario,$getip);  ///Registrar la entrada en la tabla log_in_out   
				 header("Location: " . $paginadestino );
			}else{
                             $centinela=1;
				
			}
			
		}	
	
   }
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta http-equiv="content-type" content="text/html; charset=UTF-8"> 
        <meta charset="utf-8">
        <title>Acceso a Plataforma Cronos</title>
        <meta name="generator" content="Agroveresa" />
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
        <meta name="description" content="Acceso al modulo Cronos" />
        <link href="select2/bootstrap.min.css" rel="stylesheet">   
        
     

        <!-- CSS code from Bootply.com editor -->
        
        <style type="text/css">
            .modal-footer {   border-top: 0px; }
            /***Definimos la Imangen de  Fondo ****/  
              .fondo-001{
              width: 100%;
              height: 100%;
              background-image:url("arte_cronos/fon/fondo_1280_800.jpg");
              background-position: top center;
              background-size: cover;
             }
             .modal-content ,submit :hover 
                 {
                background: rgba(234,245,229,0.69);
                background: -moz-linear-gradient(left, rgba(234,245,229,0.69) 0%, rgba(249,252,248,0.69) 0%, rgba(101,173,67,0.69) 0%, rgba(101,173,67,0.53) 76%);
                background: -webkit-gradient(left top, right top, color-stop(0%, rgba(234,245,229,0.69)), color-stop(0%, rgba(249,252,248,0.69)), color-stop(0%, rgba(101,173,67,0.69)), color-stop(76%, rgba(101,173,67,0.53)));
                background: -webkit-linear-gradient(left, rgba(234,245,229,0.69) 0%, rgba(249,252,248,0.69) 0%, rgba(101,173,67,0.69) 0%, rgba(101,173,67,0.53) 76%);
                background: -o-linear-gradient(left, rgba(234,245,229,0.69) 0%, rgba(249,252,248,0.69) 0%, rgba(101,173,67,0.69) 0%, rgba(101,173,67,0.53) 76%);
                background: -ms-linear-gradient(left, rgba(234,245,229,0.69) 0%, rgba(249,252,248,0.69) 0%, rgba(101,173,67,0.69) 0%, rgba(101,173,67,0.53) 76%);
                background: linear-gradient(to right, rgba(234,245,229,0.69) 0%, rgba(249,252,248,0.69) 0%, rgba(101,173,67,0.69) 0%, rgba(101,173,67,0.53) 76%);
                filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#eaf5e5', endColorstr='#65ad43', GradientType=1 );
                }
             .btn-primary{
                    background: rgba(142,223,104,1);
                background: -moz-linear-gradient(left, rgba(142,223,104,1) 0%, rgba(142,223,104,1) 17%, rgba(142,223,104,1) 100%);
                background: -webkit-gradient(left top, right top, color-stop(0%, rgba(142,223,104,1)), color-stop(17%, rgba(142,223,104,1)), color-stop(100%, rgba(142,223,104,1)));
                background: -webkit-linear-gradient(left, rgba(142,223,104,1) 0%, rgba(142,223,104,1) 17%, rgba(142,223,104,1) 100%);
                background: -o-linear-gradient(left, rgba(142,223,104,1) 0%, rgba(142,223,104,1) 17%, rgba(142,223,104,1) 100%);
                background: -ms-linear-gradient(left, rgba(142,223,104,1) 0%, rgba(142,223,104,1) 17%, rgba(142,223,104,1) 100%);
                background: linear-gradient(to right, rgba(142,223,104,1) 0%, rgba(142,223,104,1) 17%, rgba(142,223,104,1) 100%);
                filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#8edf68', endColorstr='#8edf68', GradientType=1 );
                }
                img { 
                    height: 80px;
                    margin-top: -16px;
                    margin-right: 40px;
                }
            
        </style>
    </head>
    
    <!-- HTML code from Bootply.com editor -->
    
    <body  >
        
        <!--login modal-->
<div id="loginModal" class="modal show fondo-001" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog">
  <div class="modal-content">
      <div class="modal-header">
          <!--  <h2 class="text-center">Acceso a Cronos</h2> --> 
          <img  src="arte_cronos/fon/logo_auto_12_2016.png">  
     </div>
      <div class="modal-body">
          <form class="form col-md-12 center-block" method="POST" action="login.php">
            <div class="form-group">
              <input type="text" class="form-control input-lg" required placeholder="usuario" name="usuario">
            </div>
            <div class="form-group">
              <input type="password" class="form-control input-lg" required placeholder="Password" name="clave">
            </div>
            <div class="form-group">
              <input type="submit" class="btn btn-primary btn-lg btn-block" value="Accesar" name="accesar">
             
            </div>
          </form>
      </div>
     
      <div class="modal-footer">
           <?php if ($centinela==1){  ?>
          <div class="col-md-12">
             <p class="text-danger text-center">Datos Incorrectos</p></span>
	  </div>
          <?php }   ?>
      </div>
      
  </div>
  </div>
</div>
        
        <script type='text/javascript' src="//ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>


        <script type='text/javascript' src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>


        
        <!-- JavaScript jQuery code from Bootply.com editor  -->
        
        <script type='text/javascript'>
        
        $(document).ready(function() {
        
            
        
        });
        
        </script>
        
      
        
        <style>
            .ad {
              position: absolute;
              bottom: 70px;
              right: 48px;
              z-index: 992;
              background-color:#f3f3f3;
              position: fixed;
              width: 155px;
              padding:1px;
            }
            
            .ad-btn-hide {
              position: absolute;
              top: -10px;
              left: -12px;
              background: #fefefe;
              background: rgba(240,240,240,0.9);
              border: 0;
              border-radius: 26px;
              cursor: pointer;
              padding: 2px;
              height: 25px;
              width: 25px;
              font-size: 14px;
              vertical-align:top;
              outline: 0;
            }
            
            .carbon-img {
              float:left;
              padding: 10px;
            }
            
            .carbon-text {
              color: #888;
              display: inline-block;
              font-family: Verdana;
              font-size: 11px;
              font-weight: 400;
              height: 60px;
              margin-left: 9px;
              width: 142px;
              padding-top: 10px;
            }
            
            .carbon-text:hover {
              color: #666;
            }
            
            .carbon-poweredby {
              color: #6A6A6A;
              float: left;
              font-family: Verdana;
              font-size: 11px;
              font-weight: 400;
              margin-left: 10px;
              margin-top: 13px;
              text-align: center;
            }
        </style>
      
        
    </body>
</html>