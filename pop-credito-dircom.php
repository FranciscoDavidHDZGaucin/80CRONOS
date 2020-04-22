<?PHP
/*
********	 INFORMACION ARCHIVO ***************** 
	Nombre  Archivo : pop-credito-dirconm.php 
 	Fecha  Creacion : 23/09/2016
	Descripcion  : 
           Copia  archivo  pop-credito-dirconm.php   parte  del  Proyecto  Pedidos 
	Modificado  Fecha  : 
*/

   session_start ();
   $MM_restrictGoTo = "index.php";
   if (!(isset($_SESSION['usuario_valido']))){   
      header("Location: ". $MM_restrictGoTo); 
  exit;
}
///Para este modulo estaremos usando acceso a la base de datos por medio de mysqli    
require('Connections/conecta1.php');  //conexion utilizando mysqli
require('formato_datos.php');   //para evitar poner la funcion de conversion de tipos de datos utilizamos el 2 ya que es el compatible con mysqli
require('buscar_email.php');   //funcion para obtener el email de un usuario en especifico
require('correos_array.php');   //funcion para mandar correos


mysqli_select_db($conecta1, $database_conecta1);

$respuesta=$_REQUEST['respuesta'];   //1 =Autoriza   2= Solicita Autorizacion 3= Rechaza
$id=$_REQUEST['id'];    //Folio del registro

if ($respuesta==1){
    $leyenda1="Autorizar";
    
}else{
     $leyenda1="Solicitar  Autorización";
}
    
   if (isset($_REQUEST['actualizar'])){
       $respuesta=$_REQUEST['respuesta'];   //1 =Autoriza   2= Solicita Autorizacion 3= Rechaza 
       $comentario=$_REQUEST['comentarios'];
       $fechahora_hoy=date("Y-m-d H:i:s");
       $id=$_REQUEST['id'];    //Folio del registro
       
       
       switch ($respuesta) {
           case 1:   ///Aqui se autoriza el pedido
               
                       
               
                         $update=  sprintf("UPDATE encabeza_pedido SET estatus='E', vbo_dircom=%s, comentario_dircom=%s, timeres_dircom=%s WHERE id=%s",
                                     GetSQLValueString($respuesta, "int"),
                                     GetSQLValueString($comentario, "text"),
                                     GetSQLValueString($fechahora_hoy, "date"),  
                                    
                                     GetSQLValueString($id, "int")); 
               
               
               
                          $buscar_datos=sprintf("select * from encabeza_pedido where id=%s",
                                        getSQLValueString($id,"int"));
				
			   $result0= mysqli_query($conecta1,$buscar_datos) or die (mysqli_error($conecta1));   
                                     
			   $result0_datos=  mysqli_fetch_assoc($result0);     
                           $dato1="CXC";
				$dato2="AUTORIZACION X DIRCOM";
				$dato3="E";
				$fecha_hoy=date("Y-m-d H:i:s");
				 /// Crear registro en la tabla de historia_estatus
                                $insertSQL = sprintf("INSERT INTO historia_estatus (id, n_remision, cve_cte, n_agente,fecha_alta,nom_cte, nom_age, fecha, cve_prod, nom_prod, estatus_b)VALUES (%s,%s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                                   GetSQLValueString($result0_datos['id'], "int"),
                                   GetSQLValueString($result0_datos['n_remision'], "int"),
                                   GetSQLValueString($result0_datos['cve_cte'], "text"),
                                   GetSQLValueString($result0_datos['n_agente'], "int"),
                                   GetSQLValueString($result0_datos['fecha_alta'], "date"),	
                                   GetSQLValueString($result0_datos['nom_cte'], "text"),
                                   GetSQLValueString($result0_datos['nom_age'], "text"),
                                   GetSQLValueString($fecha_hoy, "date"),
                                   GetSQLValueString($dato1, "text"),
                                   GetSQLValueString($dato2, "text"),
                                   GetSQLValueString($dato3, "text"));
                                
                                $result= mysqli_query($conecta1,$update) or die (mysqli_error($conecta1));                                   
				$result2=mysqli_query($conecta1,$insertSQL) or die (mysqli_error($conecta1));
                                
                                ///Mandar correo
                                
                                    switch ($result0_datos["tipo_agente"])  //1=Local 2= Foraneo 3=Verur 4=Cultivos
                            {
					case 1:
							//Agente Local
						$usuario="faclocal";	
						$destinatario=email($usuario);
                                                $usuario2="credito_aux";	
						$destinatario2[0]=email($usuario2);
                                                
								
					break;
                                    case 2:
						//Agente Foraneo	
						$usuario="facforanea";	
						$destinatario=email($usuario);
						$usuario1="facforanea2";	
						$destinatario2[0]=email($usuario1);
                                                $usuario2="credito_aux";	
						$destinatario2[1]=email($usuario2);
                                                
						
                                       break;
                                  case 3:
						//Agente Verur
						$usuario="facverur";	
						$destinatario=email($usuario);
						 $usuario2="credito_aux";	
						$destinatario2[0]=email($usuario2);
						
					break; 
                                    case 4:
						//Agente Foraneo	
						$usuario="facforanea";	
						$destinatario=email($usuario);
						$usuario1="facforanea2";	
						$destinatario2[0]=email($usuario1);
                                                $usuario2="credito_aux";	
						$destinatario2[1]=email($usuario2);
                                                
						
						
					
					break; 
                            }   
                                                 $fromname="Pedidos de Ventas";
						$subject="Remision Autorizada por Dirección Comercial No: ".$result0_datos['n_remision'];
						$mensaje="<p> Cliente : ".$result0_datos['cve_cte']."</p>";
                                                $mensaje.="<p> , ".$result0_datos['nom_cte']."</p>";
                                                $mensaje.="<p> Agente : ".$result0_datos['nom_age']."</p>";
                                                $mensaje.="<p> Monto Total del Pedido : ".$result0_datos['total']."</p>";
                                                $mensaje.="<p> Plazo : ".$result0_datos['plazo']."</p>";
						
						
						correos($fromname,$subject,$destinatario,$destinatario2,$mensaje);   //Mandar correo
			  
                                

               break;
           case 2:   //Se pasa al siguiente usuario para que si es posible se autorice
                    //Actualizar la tabla encabezado pedido
                           $update=  sprintf("UPDATE encabeza_pedido SET vbo_dircom=%s, comentario_dircom=%s, timeres_dircom=%s WHERE id=%s",
                                     GetSQLValueString($respuesta, "int"),
                                     GetSQLValueString($comentario, "text"),
                                     GetSQLValueString($fechahora_hoy, "date"),  
                                     GetSQLValueString($id, "int")); 

                         // echo $update;
                          $sql_update=mysqli_query($conecta1,$update) or die (mysqli_error($conecta1));   
                          //Mandar correo de aviso al Jefe de Credito y Cobranza con copia a  Argentina  
                          $buscar_datos=sprintf("select * from encabeza_pedido where id=%s",
                                        getSQLValueString($id,"int"));
				
			   $result0= mysqli_query($conecta1,$buscar_datos) or die (mysqli_error($conecta1));   
                                     
			   $result0_datos=  mysqli_fetch_assoc($result0);    
                          
                             ///Mandar correo
                           
                        $usuario="sistemas";	 //Dirección General
                        $destinatario=email($usuario);  
                        $usuario2="sistemas_qv";	//Argentina
                        $destinatario2[0]=email($usuario2);

                         $fromname="Pedidos de Ventas";
                        $subject="Remision para Autorizar: ".$result0_datos['n_remision'];
                        $mensaje="<p> Cliente : ".$result0_datos['cve_cte']."</p>";
                        $mensaje.="<p> , ".$result0_datos['nom_cte']."</p>";
                        $mensaje.="<p> Agente : ".$result0_datos['nom_age']."</p>";
                        $mensaje.="<p> Monto Total del Pedido : ".$result0_datos['total']."</p>";
                        $mensaje.="<p> Plazo : ".$result0_datos['plazo']."</p>";


                        correos($fromname,$subject,$destinatario,$destinatario2,$mensaje);   //Mandar correo

                                
           break;
       
           

         
       }
       
       
       
      
      echo '<script type="text/javascript">window.close()</script>';
       
   }          

?>

<html>
<head>
<meta charset="utf-8">
    <title>Definir Pedido Dircom</title>
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
<script language="javascript" type="text/javascript">	  
     function Solo_Numerico(variable){
        Numer=parseInt(variable);
        if (isNaN(Numer)){
            return "";
        }
        return Numer;
    }
    function ValNumero(Control){
        Control.value=Solo_Numerico(Control.value);
    }
    
    function validar(){
       var numero = document.getElementById("comentarios").value; 
       var ncarateres;
       
       ncarateres=numero.length;
       if (ncarateres>0){
           return true;
       }else{
           alert("Falta introducir comentarios"); 
           return false
       }
           
         
       
    
         
         
    }
</script>    
 <body onunload="window.opener.location = window.opener.location;">


    <h5><?php echo $leyenda1; ?></h5>
    <h5><?php echo 'Pedido#'. $id; ?></h5>
 <form name="form1" id="form1" Method="POST" action="pop-credito-dircom.php" onsubmit="validar()">
     
                          
     
     
     
     <p>Comentarios</p>    
     <textarea name="comentarios" id="comentarios" rows="10" cols="50" required></textarea>
            
      
            
            <input type="hidden" name="id" value=" <?php echo $id;?>"  />
            <input type="hidden" name="respuesta" value=" <?php echo $respuesta;?>"  />
            <input type="submit" name="actualizar" value="Guardar" onclick="return confirm('¿Desa Guardar los Cambios?');">
            <input type="button" name="cerrar" value="Cancelar" onclick="window.close()">
 </form>    
</body>
</html>