<?php  
  require_once('_FILES_ORD/_OBJ_ORD.php');

  
/// _FILE_ORD.php
IF(isset($_POST['ARCHIVOFILE']))
 { 
    try{
    
        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        
        echo "HOLA PERROO :V " ; 
         $POB_ORD= CARGAR_ARCHIVO($_FILES,14720191);
        echo '</BR>';
        echo  $POB_ORD->GET_MENSAJE_ARCHIVO();
        echo '</BR>';
        echo $POB_ORD->GET_ERROR_OBJ();
        echo '</BR>';
        echo 'TIPODE ARCHIVO ';
        echo $POB_ORD->GET_TIPOARCHIVO();
        echo 'Nombre del Archivo';
        echo $POB_ORD->GET_NOMBRE_TEMPORAL();
        echo "Nuevo Nombre del  Documento";
        echo  $POB_ORD->GET_NEW_DIRECCION();

        echo  "<h2> Resultado Almacenado Archivo "; 
         echo  $retVal = ($POB_ORD->RESULTADO_ORDC()) ? "CORRECTO" : "INCORRECTO" ;


       /* if($objetoORD->GET_ESTATUS_ARCHIVO() ==1 ){
    
        echo  "TODO BIEN" ;
    
        }else {

            echo "HOLA PERRO ";
        }*/

    } catch (Exception $th) {
       echo  $th;  
    }
    


 }

?>
<form method="post" enctype="multipart/form-data">
    <div class="container">
        <div class="page-header">
                            <div class="form-group">
                                     <label class="col-sm-12 ">Ingrese Archivo de Orden compra </label>
                                    <div  class="auto-style2">
                                                 <input type="file" class ="form-control"  name="archORD" />       
                                                 <button name="ARCHIVOFILE" type="submit" class="btn btn-lg btn-primary pull-right">ARCHIVO</button>                                 
                                    </div>
                                </div>
        </div>
     </div>
  
</form>