<?php 
session_start ();
$MM_restrictGoTo = "login.php";
if (!(isset($_SESSION['usuario_valido']))){
header("Location: ". $MM_restrictGoTo);
exit;
}

require_once('../formato_datos.php');   
require_once('../Connections/conecta1.php');

$directorio_BY_SERCH = '/var/www/sistemas/cronos/';
$id = $_GET['id'];
$idArch = $_SESSION["IDARCH"];
$archivo = $_SESSION["ordcompra_delete"];
$ARCHIVO = $_GET['name'];; 
$arc_file_MAIN = $directorio_BY_SERCH.$ARCHIVO;

if ($id !== "") { 
    
    try {
        
        $EXPRECION = 'ORDC_PEDIDOS/' ; 
        // $sustitución = '' ; 
         ///*****RUTAS ORIGEN 
        $RUTA_ORIGEN_PDF  = $arc_file_MAIN;
         //******RUTAS  DESTINO   
        $RUTA_DESTINO_PDF  = '/var/www/sistemas/cronos/ORDC_PEDIDOS/ELIMINADOS_ORDC/'.str_replace($EXPRECION, $sustitución,$ARCHIVO );
        ///****MANDAMOS Archivo 
          rename($RUTA_ORIGEN_PDF,$RUTA_DESTINO_PDF ); 
         
         $strg_delete_ord = sprintf(
            "DELETE FROM  pedidos.TB_LOG_CARGA_ORDC_PEDIDOS where ID =%s ",
            GetSQLValueString($id , "int")
         ); 
         @mysqli_query($conecta1,$strg_delete_ord );
           
    
    
        $GASTrESULTADO =   Array(
            "ERROR"=> 888,
            "MMSSGES" =>"CORRECTO"  
    
        );
        header('Location: ../pedidos.php');

    } catch (Exception   $th) {
        $GASTrESULTADO =   Array(
            "ERROR"=> 1 , 
            "MMSSGES" => $th->getMessage() 
        );
    }
    

}else {
    $GASTrESULTADO =   Array(
        "ERROR"=> 1 , 
        "MMSSGES" =>  "ERROR  GENERAL"    
    );
}




  
///**Convertimos a  Json  
$convert_json  =  json_encode($GASTrESULTADO);
header('Content-type: application/json');
echo  $convert_json ;
?>
 