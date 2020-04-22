
<?php
/*
********	 INFORMACION ARCHIVO ***************** 
	Nombre  Archivo : popupentrega.php
 	Fecha  Creacion : 20/09/2016     
	Descripcion  : 
 *              Archivo   copiado  del  proyecto pedidos 
	
	Modificado  Fecha  : 
*/
///***Inicio Checamos que el  Usuario  siga  Logeado  
session_start ();
   $MM_restrictGoTo = "login.php";
 if (!(isset($_SESSION['usuario_valido']))){   
      header("Location: ". $MM_restrictGoTo); 
  exit;
}   
///***Fin  Checamos que el  Usuario  siga  Logeado
///****Agregamos librerias
require_once('formato_datos.php');
require_once('Connections/conecta1.php');   
require('correos_array.php'); //Utilizar la clase enviar correos con adjunto incluido
function tipo2($archivo2){
        $ext2= substr(strrchr($archivo2, '.'), 1);
        
        return $ext2;
        
    }
    
$mensaje="";


$id=$_REQUEST["id"];

 $str_consulta=sprintf("SELECT * FROM prospecto where id_p=%s",
                GetSQLValueString($id, "int")); 
                
$q_consulta=  mysqli_query($conecta1, $str_consulta) or die (mysqli_error($conecta1)); 
$q_datos=  mysqli_fetch_assoc($q_consulta);




$fecha_hoy=date('Y-m-d');


if (isset($_REQUEST['agregar'])){
    
    $id=$_REQUEST['id'];
    $doc=$_POST['documento'];
    $fecha=$_POST['fecha'];
    $comentario=$_POST['comentario'];
    
     ////////////////////////ARCHIVO/////////////////////////////////  
   $ext2=  tipo2(basename($_FILES['archivo']['name']));
   $peso2 = $_FILES['archivo']['size'];


 $mensaje="Problemas con el Archivo. cierre la ventana e intente de Nuevo"; 
    IF (($ext2=="doc" || $ext2=="docx" || $ext2=="pdf" || $ext2=="xls" || $ext2=="xlsx"
          || $ext2=="ppt" || $ext2=="pptx" || $ext2=="jpg" || $ext2=="JPG" || $ext2=="jpeg" || $ext2=="JPEG") && ($peso2 <= 21000000)){
        
       $name_temporal2=$_FILES['archivo']['tmp_name'];
        $uploaddir2 = '../pedidos/upload/';
        $nowtime2 = time();$name_temporal2.
        $uploadfile2=$uploaddir2."C".$nowtime2.".".$ext2;
        $nombrefile2="C".$nowtime2.".".$ext2;
        move_uploaded_file($_FILES['archivo']['tmp_name'], $uploadfile2);
      $concatenado2 = "upload/".$nombrefile2;
      
      
      
        $str_doc=sprintf("SELECT * FROM documento where id_d=%s",
                        GetSQLValueString($doc, "int")); 

        $q_doc=  mysqli_query($conecta1, $str_doc) or die (mysqli_error($conecta1)); 
        $datos_doc=  mysqli_fetch_assoc($q_doc);

     
     $destinatario="badame@agroversa.com.mx";
     $destinatario2[0]="ktapia@agroversa.com.mx";  //CC
    //$destinatario2[1]="fnavarro@agroversa.com.mx"; //CC
     // $destinatario2[2]="egonzalez@agroversa.com.mx"; //CC
    $destinoad[0]=$concatenado2;   //Add
     $fromname="Notificacion Expedientes"; 
             $subject="Actualización Expediente Cliente: ".$q_datos['nombre'];
             $mensajem="<H1>Se ha actualizado información del documento: ".utf8_encode($datos_doc['nombre'])."</H1>";
             $mensajem.="<p>Comentario: ".$comentario."</p>";
            
         
    
      
      
      
         $insertSQL=sprintf("INSERT INTO entrega(id_p,id_d,fecha,comentario,archivo)VALUES(%s,%s,%s,%s,%s)",
                        GetSQLValueString($id, "int"),
                        GetSQLValueString($doc, "int"),
                        GetSQLValueString($fecha, "date"),
                        GetSQLValueString($comentario, "text"),
                         GetSQLValueString($concatenado2, "text"));     
          
     @mysqli_query($conecta1,$insertSQL) or die (mysqli_error($conecta1)); 
      correos($fromname,$subject,$destinatario,$destinatario2,$mensajem,$destinoad);   //Mandar correo   
        $mensaje="Datos Guardados"; 
    
    } else {
        $concatenado2= "";
    }
    
    /*
     $insertSQL=sprintf("INSERT INTO entrega(id_p,id_d,fecha,comentario,archivo)VALUES(%s,%s,%s,%s,%s)",
           GetSQLValueString($id, "int"),
           GetSQLValueString($doc, "int"),
           GetSQLValueString($fecha, "date"),
           GetSQLValueString($comentario, "text"),
            GetSQLValueString($concatenado2, "text"));     
          
     $resultado=mysqli_query($conecta1,$insertSQL) or die (mysqli_error($conecta1));  
     $mensaje="Datos Guardados"; 
 // echo "Cliente Numero: ".$id." El documento".$doc;
 // echo $insertSQL;
     

        $str_doc=sprintf("SELECT * FROM documento where id_d=%s",
                        GetSQLValueString($doc, "int")); 

        $q_doc=  mysqli_query($conecta1, $str_doc) or die (mysqli_error($conecta1)); 
        $datos_doc=  mysqli_fetch_assoc($q_doc);

     
     $destinatario="jgarcia@agroversa.com.mx";
     $destinatario2[0]="badame@agroversa.com.mx";  //CC
     $destinatario2[1]="fnavarro@agroversa.com.mx"; //CC
     // $destinatario2[2]="egonzalez@agroversa.com.mx"; //CC
    $destinoad[0]=$concatenado2;   //Add
     $fromname="Notificacion Expedientes"; 
             $subject="Actualización Expediente Cliente: ".$q_datos['nombre'];
             $mensajem="<H1>Se ha actualizado información del documento: ".utf8_encode($datos_doc['nombre'])."</H1>";
             $mensajem.="<p>Comentario: ".$comentario."</p>";
            
         
     correos($fromname,$subject,$destinatario,$destinatario2,$mensajem,$destinoad);   //Mandar correo   
    */
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Credito y Cobranza</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Loading Bootstrap 
    <link href="select3/dist/css/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">-->
    
    <!-- Loading Flat UI -->
    <link href="select3/dist/css/flat-ui.css" rel="stylesheet">
 
    <link href="select2/gh-pages.css" rel="stylesheet">
    <link href="select2/select2.css" rel="stylesheet">
      
      
    <link rel="shortcut icon" href="select3/dist/img/favicon.ico">

  </head>

  <body>
      <div  class ="container"> 
      <!--****Inicio Codigo  Copia ---->
       <p align="center" class="texto_resaltado"> Nombre <?php echo $q_datos['nombre'];?> </p>

        <form name="form1" id="form1" method="POST" enctype="multipart/form-data" action="popupentrega.php">
            <label for="documento">Documento</label>
                <select name="documento"  style="width: 250px;" required>
                    <option value="">Elija documento</option>
                     <?php 
                     $string_conceptos=sprintf("SELECT id_d, nombre FROM documento where tp=%s and id_d NOT IN(select id_d from entrega WHERE id_p=%s)",
                                        GetSQLValueString($q_datos['t_persona'],"text"),
                                        GetSQLValueString($id,"int"));    
                     $query_conceptos=  mysqli_query($conecta1, $string_conceptos) or die (mysqli_error($conecta1));

                  while ($row3= mysqli_fetch_array($query_conceptos)){
                        echo '<option value="'.$row3['id_d'].'">'.utf8_encode($row3['nombre']).'</option>';	
                  }

                 ?>
        </select>
            <br>
            <label for="fecha">Fecha</label>
            <input type="date" name="fecha" id="fecha" value="<?php echo $fecha_hoy;?>">
            <br>

            <textarea COLS=45 ROWS=5 NAME="comentario" placeholder="Ingrese un Comentario"></textarea> 
            <br>
             <label for="archivo">Archivo (Word,Excel,PowerPoint o PDF maximo 10Mb)</label>
              <input  type="file" name="archivo" value="" /><br>

            <input type="hidden" name="id" value=" <?php echo $id;?>"  />
            <br>
             <input type="submit" name="agregar" value="Guardar"  >
             <button  name="entrar" onClick="window.close()" >Cerrar</button>
        </form>


        <?php echo "<p class='mensaje'>".$mensaje."</p>";?>

      <!--*****FIN  Codigo Copia---->
       </div> 
    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster  -->
    
    <script src="select3/dist/js/vendor/jquery.min.js"></script>      
    <script src="select3/dist/js/flat-ui.min.js"></script>        
    <script src="select3/assets/js/application.js"></script>
    
    
    <script src="select2/buscar-cool.js"></script>   
    <script type="text/javascript" src="select2/jquery.min.1.10.2.js"></script>
    <script src="select2/select2.js"></script>  
    <!--<script src="select2/jasny-bootstrap.min.js"></script>-->
  </body>
</html>      

