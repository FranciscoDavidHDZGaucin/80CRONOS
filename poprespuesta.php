<?PHP
   session_start ();
   $MM_restrictGoTo = "index.php";
  if (!(isset($_SESSION['usuario_valido']))){   
      header("Location: ". $MM_restrictGoTo); 
  exit;
}
///Para este modulo estaremos usando acceso a la base de datos por medio de mysqli    
require('Connections/conecta1.php');  //conexion utilizando mysqli
require('formato_datos.php');   //para evitar poner la funcion de conversion de tipos de datos utilizamos el 2 ya que es el compatible con mysqli


$id=$_REQUEST["id"];	
 $str_consulta=sprintf("SELECT * FROM notas where id=%s",
                GetSQLValueString($id, "int")); 
                
$q_consulta=  mysqli_query($conecta1, $str_consulta) or die (mysqli_error($conecta1)); 
$q_datos=  mysqli_fetch_assoc($q_consulta);

?>

<html>
<head>
   <meta charset="utf-8">
    <title>Notas de Crédito</title>
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
 <script type="text/javascript">
		window.opener.location.reload();
	
    </script>	
</head>
<body>

 <div class="container">   
<p align="center" class="texto_resaltado"> Empresa. <?php echo $q_datos['empresa'];?> </p>
<p align="center" class="texto_resaltado"> Factura No. <?php echo $q_datos['factura'];?> </p>
<?php  if (isset($_REQUEST['actualizar'])){
      $id=$_POST['id']; 
      $comentario=$_POST['comentario']; 
      $respuesta=$_POST['respuesta']; 
      $usuario=$_POST['usuario'];
      
      switch ($usuario) {
          case 1:
                $campousuario="autoriza1";

              break;
            case 2:
                 $campousuario="autoriza2";

              break;
          case 3:
               $campousuario="autoriza3";

              break;
         case 4:
                $campousuario="director"; 
             
              break; 
      }
      
      
      if ($respuesta==1){
          //autorizar
          $comodin="A";
           $update=  sprintf("UPDATE notas_credito SET $campousuario=%s WHERE id=%s",
                    GetSQLValueString($respuesta, "int"),
                    GetSQLValueString($id, "int")); 
           
            $insertSQL=  sprintf("INSERT INTO comentarios_notas (id_nota,autor,accion,comentario)VALUES(%s,%s,%s,%s) ",
                    GetSQLValueString($id, "int"),
                    GetSQLValueString($usuario, "date"),
                    GetSQLValueString($comodin, "text"),
                    GetSQLValueString($comentario, "text"));  
           
      }else{
         //No autorizar
           $comodin="R";
           
           $update=  sprintf("UPDATE notas_credito SET $campousuario=%s, status=%s WHERE id=%s",
                    GetSQLValueString($respuesta, "int"),
                    GetSQLValueString($comodin, "text"),
                    GetSQLValueString($id, "int")); 
           
          $insertSQL=  sprintf("INSERT INTO comentarios_notas (id_nota,autor,accion,comentario)VALUES(%s,%s,%s,%s) ",
                    GetSQLValueString($id, "int"),
                    GetSQLValueString($usuario, "date"),
                    GetSQLValueString($comodin, "text"),
                    GetSQLValueString($comentario, "text"));  
              
      }
      
      
     
   //   echo $update;     
      $resultado=mysqli_query($conecta1,$update) or die (mysqli_error($conecta1));   //actualizar tabla de notas_credito 
      $resultado2=mysqli_query($conecta1,$insertSQL) or die (mysqli_error($conecta1));  //Crear registro en la tabla de comentarios
      
      //Crear registro en comentarios
      
      print '<strong>Datos Guardados Correctamente.</strong> ';
      print '<p><button  name="entrar" onClick="window.close()" >Cerrar</button></p>';
    
}else{
     if ($_REQUEST['respuesta']==1){
         $leyenda1="Autorizar";
         echo '<h1> AUTORIZAR</h1>';
     }else{
          $leyenda1="No Autorizar";   
         echo '<h1>NO AUTORZAR</h1>';
     }
         
     
    ?>
 <form name="form1" id="form1" Method="POST" action="poprespuesta.php">
           
            <textarea name="comentario" id="comentario" cols="35" rows="4"> Escribe aquí tu comentario </textarea>
             
            <br>
            <input type="hidden" name="id" value=" <?php echo $_REQUEST['id'];?>"  />
            <input type="hidden" name="respuesta" value=" <?php echo $_REQUEST['respuesta'];?>"  />
            <input type="hidden" name="usuario" value=" <?php echo $_REQUEST['usuario'];?>"  />
            
            <input type="submit" name="actualizar" value="<?php echo $leyenda1; ?>" onclick="return confirm('¿Desa Guardar los Cambios?');">
            <button  name="entrar" onClick="window.close()" >Cerrar</button>
 </form>    



<?php }?>


 </div> <!-- /.Canvas -->
      

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    
    <script src="select3/dist/js/vendor/jquery.min.js"></script>      
    <script src="select3/dist/js/flat-ui.min.js"></script>        
    <script src="select3/assets/js/application.js"></script>
    
    
    <script src="select2/buscar-cool.js"></script>   
    <script type="text/javascript" src="select2/jquery.min.1.10.2.js"></script>
    <script src="select2/select2.js"></script>   
    <!--<script src="select2/jasny-bootstrap.min.js"></script>-->
</body>
</html>