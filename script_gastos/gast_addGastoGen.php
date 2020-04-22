<?php
/*
********	 INFORMACION ARCHIVO ***************** 
	Nombre  Archivo : gast_addGastoGen.php 
 	Fecha  Creacion : 01/11/2017 
	Descripcion  : 
			Archivo  para    Agregar Gasto
*/
////"Insert INTO poliza set id_gto=1 ,nom_gto='COMIDAS',agente=151,factura='7777', fecha='2017-11-13', subtot=2, iva=442.34, total=23.423, tasa_iva=16, observa='23423423',nom_xml='1.-65f8fb5e-04ec-42bc-8096-ce8638408f91.xml',nom_pdf=NULL,cc=329,cuenta='11221159000000' ,cuenta_sys='_SYS00000000917' "
///**Conexion Mysql 
include '../Connections/conecta1.php';
///***Formato  de Datosa
require_once('../formato_datos.php'); 
 ///****Funcion para  Validar  Estatus De Insercion Si eS un Gerente 
function   IsGerete($num_agente)
{   $estatus = 0; 
    if($num_agente >= 1  &&  $num_agente <= 6)
    {
        $estatus = 1; 
    }
    return   $estatus ;
    
}

///***Obtenemos  los  Arreglos  json 		
$JSON_INFO =  filter_input(INPUT_POST, 'INFOj');
///***Convertimos  LA  INFORMACION  JSON
$Arreglo_MAIN = json_decode($JSON_INFO );
////*****Obtenemos  el  Areglo  Json
$JSON_INFOcUENTA =filter_input(INPUT_POST,'OTHERINFO');
///**cONVERTIMOS EL  jSON 
$AREGcUNTA= json_decode($JSON_INFOcUENTA);

     //**Obtenemos los  Valores  
    $NumAge= $Arreglo_MAIN->{'numage'} ;//Numero  de  Agente
    $FOLIO = $Arreglo_MAIN->{'facturaNum'} ; ///FOLIO 
    $FECHA = $Arreglo_MAIN->{'capdate'} ;///Fecha
    $CONCEPTO= $Arreglo_MAIN->{'concepto'} ;///CONCEPTO
    $NOM_CONCEPTO =$Arreglo_MAIN->{'nomconcepto'} ;///CONCEPTO  
    $TASAIVAPOR= $Arreglo_MAIN->{'tasaIVApor'} ;/// Tasa Iva Porcentaje %
    $SUBTOTAL= floatval($Arreglo_MAIN->{'subTotal'}) ;///Sub Total
    $IVA= floatval( $Arreglo_MAIN->{'IVA'});//Iva
    $TOTAL= floatval($Arreglo_MAIN->{'total'}) ;//TOTAL
    $COMENT= $Arreglo_MAIN->{'comen'} ;//COMENTARIOS
    $XML_NOM= $Arreglo_MAIN->{'nom_xml'} ;// Nombre  Archivo   XML 
    $PDF_NOM= $Arreglo_MAIN->{'nom_pdf'} ;// Nombre  Archivo   PDF 
    ///******Obtenmos la  Infomacion de cuenta
    $CC = $AREGcUNTA->{'cc'}; /// CC CENTRO  DE COSTOS 
    $CUENTA = $AREGcUNTA->{'cuenta'}; ////  Cuenta COntable 
    $SYSCUENTA = $AREGcUNTA->{'cuenta_sys'}; //// Cuenta Sys COntable 
    $NOMAGE  = $AREGcUNTA->{'nomAge'};////****   Nombre Agente  
    $ZONA =  $AREGcUNTA->{'zona'}; ////zONA 
    $ROL = $AREGcUNTA->{'rol'}; ////Rol de los Agentes  asd 
    
   if($Arreglo_MAIN->{'numage'} == true )
   {    
       ////Validamos  si es  el  Gerente  para que se AAutorixe  el gato   automaticamente
     if(IsGerete($NumAge)==1 || $ZONA ==8  ){
        
         ////****Generamos  Cadena  De insercion  nom_age=%s,
        $String_Insert_GASTO = sprintf("Insert INTO poliza set id_gto=%s ,nom_gto=%s,agente=%s,nom_age=%s,factura=%s, fecha=%s, subtot=%s, iva=%s, total=%s, tasa_iva=%s, observa=%s,nom_xml=%s,nom_pdf=%s,cc=%s,cuenta=%s ,cuenta_sys=%s ,zona=%s, vbo_gerente=%s ",
                               GetSQLValueString($CONCEPTO, "int"),
                               GetSQLValueString($NOM_CONCEPTO, "text"),
                               GetSQLValueString($NumAge, "int"),
                               GetSQLValueString($NOMAGE, "text"),
                               GetSQLValueString($FOLIO,"text"),
                               GetSQLValueString($FECHA, "date"),
                               GetSQLValueString($SUBTOTAL, "double"),
                               GetSQLValueString($IVA, "double"),
                               GetSQLValueString($TOTAL, "double"),
                               GetSQLValueString($TASAIVAPOR, "int"),
                               GetSQLValueString($COMENT, "text"),
                               GetSQLValueString($XML_NOM, "text"), 
                               GetSQLValueString($PDF_NOM, "text"),
                               ////***Agregamos la CuentaS cONTABLES
                               GetSQLValueString($CC, "int"),
                               GetSQLValueString($CUENTA, "text"), 
                               GetSQLValueString($SYSCUENTA, "text"),
                               GetSQLValueString($ZONA, "int"),
                                ///****Se AUTORIZA  EL gASTO Automatica mente       
                                GetSQLValueString(1, "int")       
                            );
         
         
         
         
         
     } else {
       
       
        ////****Generamos  Cadena  De insercion  nom_age=%s,
        $String_Insert_GASTO = sprintf("Insert INTO poliza set id_gto=%s ,nom_gto=%s,agente=%s,nom_age=%s,factura=%s, fecha=%s, subtot=%s, iva=%s, total=%s, tasa_iva=%s, observa=%s,nom_xml=%s,nom_pdf=%s,cc=%s,cuenta=%s ,cuenta_sys=%s ,zona=%s ",
                               GetSQLValueString($CONCEPTO, "int"),
                               GetSQLValueString($NOM_CONCEPTO, "text"),
                               GetSQLValueString($NumAge, "int"),
                               GetSQLValueString($NOMAGE, "text"),
                               GetSQLValueString($FOLIO,"text"),
                               GetSQLValueString($FECHA, "date"),
                               GetSQLValueString($SUBTOTAL, "double"),
                               GetSQLValueString($IVA, "double"),
                               GetSQLValueString($TOTAL, "double"),
                               GetSQLValueString($TASAIVAPOR, "int"),
                               GetSQLValueString($COMENT, "text"),
                               GetSQLValueString($XML_NOM, "text"), 
                               GetSQLValueString($PDF_NOM, "text"),
                               ////***Agregamos la CuentaS cONTABLES
                               GetSQLValueString($CC, "int"),
                               GetSQLValueString($CUENTA, "text"), 
                               GetSQLValueString($SYSCUENTA, "text"),
                               GetSQLValueString($ZONA, "int")
                            );
     }
       
   }else {
       
       
       ////Validamos  si es  el  Gerente  para que se AAutorixe  el gato   automaticamente
     if(IsGerete($NumAge)==1 || $ZONA ==8 ){
        
         ////****Generamos  Cadena  De insercion  nom_age=%s,
        $String_Insert_GASTO = sprintf("Insert INTO poliza set id_gto=%s ,nom_gto=%s,agente=%s,nom_age=%s,factura=%s, fecha=%s, subtot=%s, iva=%s, total=%s, tasa_iva=%s, observa=%s,nom_xml=%s,nom_pdf=%s,cc=%s,cuenta=%s ,cuenta_sys=%s ,zona=%s, vbo_gerente=%s ",
                               GetSQLValueString($CONCEPTO, "int"),
                               GetSQLValueString($NOM_CONCEPTO, "text"),
                               GetSQLValueString($NumAge, "int"),
                               GetSQLValueString($NOMAGE, "text"),
                               GetSQLValueString($FOLIO,"text"),
                               GetSQLValueString($FECHA, "date"),
                               GetSQLValueString($SUBTOTAL, "double"),
                               GetSQLValueString($IVA, "double"),
                               GetSQLValueString($TOTAL, "double"),
                               GetSQLValueString($TASAIVAPOR, "int"),
                               GetSQLValueString($COMENT, "text"),
                               GetSQLValueString($XML_NOM, "text"), 
                               GetSQLValueString($PDF_NOM, "text"),
                               ////***Agregamos la CuentaS cONTABLES
                               GetSQLValueString($CC, "int"),
                               GetSQLValueString($CUENTA, "text"), 
                               GetSQLValueString($SYSCUENTA, "text"),
                               GetSQLValueString($ZONA, "int"),
                                ///****Se AUTORIZA  EL gASTO Automatica mente       
                                GetSQLValueString(1, "int")       
                            );
         
         
         
         
         
     } else {
       
        ////****Generamos  Cadena  De insercion  nom_age=%s,
        $String_Insert_GASTO = sprintf("Insert INTO poliza set id_gto=%s ,nom_gto=%s,agente=%s,nom_age,factura=%s, fecha=%s, subtot=%s, iva=%s, total=%s, tasa_iva=%s, observa=%s,nom_xml=%s,cc=%s,cuenta=%s ,cuenta_sys=%s,zona=%s",
                               GetSQLValueString($CONCEPTO, "int"),
                               GetSQLValueString($NOM_CONCEPTO, "text"),
                               GetSQLValueString($NumAge, "int"),
                               GetSQLValueString($NOMAGE, "text"),
                               GetSQLValueString($FOLIO,"text"),
                               GetSQLValueString($FECHA, "date"),
                               GetSQLValueString($SUBTOTAL, "double"),
                               GetSQLValueString($IVA, "double"),
                               GetSQLValueString($TOTAL, "double"),
                               GetSQLValueString($TASAIVAPOR, "int"),
                               GetSQLValueString($COMENT, "text"),
                               GetSQLValueString($XML_NOM, "text"),
                               ////***Agregamos la CuentaS cONTABLES
                               GetSQLValueString($CC, "int"),
                               GetSQLValueString($CUENTA, "text"), 
                               GetSQLValueString($SYSCUENTA, "text"),
                               GetSQLValueString($ZONA, "int")
                            );
     }
   }


///****Generamos  Qery  
$qery_Insertgasto = mysqli_query($conecta1, $String_Insert_GASTO) ; 
///***Validar  Qery  Cabeza
if(!$qery_Insertgasto)
{   ///***Error insert Consulta 
   $ExitGAS = 0; 
   $typeError =   mysqli_error($conecta1);
   
}else{
    ///**Insert Correct
    $ExitGAS = 1;
    $typeError ="No EXITE ERROR";
    
    
}
 
 $GASTrESULTADO =   Array(
 			"Res001" => $ExitGAS,  ///Retornamos  Resultado  De INSERCION
                        "cadena" =>$String_Insert_GASTO,
                        "ERROR"=> $typeError   
     );  
///**Convertimos a  Json  
  $convert_json  =  json_encode($GASTrESULTADO);
  header('Content-type: application/json');
echo  $convert_json ;


?> 