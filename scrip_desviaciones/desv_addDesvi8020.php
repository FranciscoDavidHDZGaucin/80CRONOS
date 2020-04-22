<?php
////***desv_addDesvi8020.php 
/*
********	 INFORMACION ARCHIVO ***************** 
	Nombre  Archivo : desv_addDesvi8020.php
 	Fecha  Creacion : 26/05/2017
	Descripcion  :
 *                  Escrip para insertar  en la  tabla de  desviaciones 
 * 
 *      Modificaciones:
 *                 26/07/2017      Generamos  Encabezado  para  las  Desviaciones 
 *                                 El Encabezado esta en funcion del  Agente  
 *                  
 *       
  */
require_once('../formato_datos.php');
require_once('../Connections/conecta1.php');

$ElemDes = json_decode(filter_input(INPUT_POST, 'El8020'));
$feIni  = filter_input(INPUT_POST, 'FechIni');
$Finfe  = filter_input(INPUT_POST, 'FinFech');
///**Variable  Est 
$Est = true; ///Si alguno de los elementos tiene  algun error
//Elemento a Retornar en caso de Error
$errEle ;

///****Validamos que las  Desviaciones No existan  para no repertir  elementos
$cadenExISdES =  sprintf("SELECT count(id) as ID  FROM pedidos.desv_desviaciones_8020 where  month(%s) in (select month(fech_Ini) from pedidos.desv_desviaciones_8020 )  or month(%s) in (select month(fech_fin) from pedidos.desv_desviaciones_8020 )", ///or fech_fin <=%s",
             GetSQLValueString($feIni, "date"),
            GetSQLValueString($Finfe, "date")   
        );
 $qeryElme = mysqli_query($conecta1, $cadenExISdES);  
 
 $FETHID = mysqli_fetch_array($qeryElme);
 ///Variable ExisteDesv 
 $ExisteDesv = false ;
if( $FETHID['ID'] == 0){
    ///***Ciclo  De Insert Desviaciones  
    foreach ($ElemDes as $value) {

        $strAddDes = sprintf("INSERT   INTO desv_desviaciones_8020  set cve_prod=%s,cve_agente =%s ,porcent =%s,fech_Ini =%s,fech_fin=%s ,variacion=%s,VentReal=%s",
                GetSQLValueString($value->{'cve_prod'}, "text"),
                GetSQLValueString($value->{'nom_agen'}, "int"),
                GetSQLValueString($value->{'Porcent'}, "int"),
                GetSQLValueString($feIni, "date"),
                GetSQLValueString($Finfe, "date"),
                GetSQLValueString($value->{'VaA'}, "double"),
                GetSQLValueString($value->{'VetR'}, "double") 
                        );

          $qeryElme = mysqli_query($conecta1, $strAddDes);      
          if(!$qeryElme){
              $Est=false ; 
              $errEle = $value;
              break;

          }      

    }
   /////***Incio  agregado  el  26/07/2017 Generamos el  Encabezado  de las  desviaciones  
   
    
   ////*Obtenemos Consulta  para  Generar  el  encabezado 
    $strGet = sprintf("SELECT  concat(cve_agente,year(fech_Ini),month(fech_Ini)) as cve_desvi,cve_agente ,fech_Ini,fech_fin  FROM pedidos.desv_desviaciones_8020 where  fech_Ini =%s    group by cve_agente",
                        GetSQLValueString($feIni, "date"));
    $qeryGetEnca = mysqli_query($conecta1, $strGet);
    while ($row = mysqli_fetch_array($result)) {
        
        ////***Generamos cadena  de Insert  
        $strcab = sprintf("INSERT  INTO desv_encabeza_desviacion SET  cve_desvi=%s,cve_agente=%s,fech_Ini=%s,fech_fin=%s,fech_make_desv =Now() ",
                 GetSQLValueString((int)$row["cve_desvi"], "int"),
                 GetSQLValueString($row["cve_agente"], "date"),
                 GetSQLValueString($feIni, "date"),
                 GetSQLValueString($Finfe, "date")
                
                );
        $qeryInsEncabeza = mysqli_query($conecta1, $strcab);
         if(!$qeryInsEncabeza){
              $Est=false ; 
              $errEle = $strcab;
              break;

          }   
       
    }
    
    
    
   ////***Fin **************************************************************************************** 
}else {
    
    $ExisteDesv=true; 
}


///****Generamos Json De Resultado 
$arrayResult  =   array(
   "ElemError" => $errEle,
   "Est" => $Est,
    "EXIDES"=> $ExisteDesv
);
 ///***Enviamos el  Objeto  Json 
header('Content-type: application/json');
 ECHO json_encode($arrayResult); 


?> 