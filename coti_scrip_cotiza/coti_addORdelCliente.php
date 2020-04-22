<?php
///***coti_addORdelCliente.php 
/*
********	 INFORMACION ARCHIVO ***************** 
	Nombre  Archivo : coti_addORdelCliente.php 
 	Fecha  Creacion : 29/05/2017
	Descripcion  : 
 *                  Escrip  para  Insertar al  cliente que se
 *                  le  asignara   la cotizacion
 * 
  */
require_once('../formato_datos.php');
require_once('../Connections/conecta1.php');

$estQery =true; 
///***keyOpc => 1  Inserta   keyOpc => 2 Elimina 
$estInsertOrDel =  filter_input(INPUT_POST, 'keyOpc');
$fl = filter_input(INPUT_POST, 'FOL');
$cve =  filter_input(INPUT_POST, 'CVE');

if($estInsertOrDel ==1 ){
///***Validamos que no Exista el  Cliente  para el  folio 
$strxisClien = sprintf("SELECT  count(id) as NumClie from  coti_asig_cliente  where folio_coti=%s  and  cve_cliente=%s ",
        GetSQLValueString($fl, "int"),GetSQLValueString($cve, "text"));

$qerExisCli = mysqli_query($conecta1, $strxisClien);

$fetExisCli = mysqli_fetch_array($qerExisCli);

if($fetExisCli['NumClie'] == 0  )
{
    $STRiNSERT  = sprintf("INSERT  INTO coti_asig_cliente SET  folio_coti =%s ,cve_cliente =%s ",
            GetSQLValueString($fl, "int"),
            GetSQLValueString($cve, "text")      
        );
    $qeryInser  = mysqli_query($conecta1, $STRiNSERT);

    if(!$qeryInser){

       $estQery = false ;
    }
}else {
    
    $estQery=false;
} 
}
if($estInsertOrDel==2)
{
    ///Obtenemos  el  Id 
    ///***Validamos que no Exista el  Cliente  para el  folio 
$strxisClien = sprintf("SELECT  id  from  coti_asig_cliente  where folio_coti=%s  and  cve_cliente=%s ",
        GetSQLValueString($fl, "int"),GetSQLValueString($cve, "text"));

$qerExisCli = mysqli_query($conecta1, $strxisClien);

$fetExisCli = mysqli_fetch_array($qerExisCli);

$string_qery_delete_adi = sprintf("DELETE FROM coti_asig_cliente  where id=%s", 
 GetSQLValueString($fetExisCli['id'], "int"));
    $qeryDEL  = mysqli_query($conecta1, $string_qery_delete_adi);
    
}

$arreresl = array(  "EstQery" =>$estQery );
  ///***Enviamos el  Objeto  Json 
header('Content-type: application/json');
 ECHO json_encode($arreresl); 

?>