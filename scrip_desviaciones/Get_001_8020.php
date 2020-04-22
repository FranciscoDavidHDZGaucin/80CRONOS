<?php
///****Get_001_8020
/*
********	 INFORMACION ARCHIVO ***************** 
	Nombre  Archivo : Get_001_8020.php 
 	Fecha  Creacion :15/05/2017 
	Descripcion  : 
 *             Escrip   encargado  de   Hacer la  Consulta  con la BD  y además de estar  encargado  de  Calcular la ( Venta  * PU )  
  */
require_once('../formato_datos.php');
 require_once('../Connections/conecta1.php');
 require_once('../conexion_sap/sap.php');
 ///mssql_select_db("AGROVERSA");  
///***
 $INICIOFE  = filter_input(INPUT_POST,'Inic');
$FINFE = filter_input(INPUT_POST, 'Fin');

 
 
  ///***+Funcion para  Obtener el Precio Unitario 
function  Get_PrecioProd($cve_pro)
{
 ///**+Generamos Cadena
 $str_prod = sprintf("SELECT ItemCode,Price FROM  plataformaproductosl1 where  Currency = 'MXP' and  itemCode =%s",
 GetSQLValueString($cve_pro, "text"));
 ///**Obtenemos  Qery   
 $qerProd =  mssql_query($str_prod); 
 ///**Convertimos  a Fetch    
 $fetchElm =mssql_fetch_array($qerProd);
 ///***Retornamos el Precio
   return  $fetchElm['Price'];
} 
                                                                                                                                 ////desv_GetAllVentVSPro            
$string_get_TbDes = "select cve_prod,cve_agen, tot_linea, sum(tot_cant) as VentaReal,sum(tot_linea) as VentaLine  from   pedidos.desv_getallventvspro where   falta_fac2  >='".$INICIOFE."'  and  falta_fac2 <='".$FINFE."' and cve_agen !=186 and cve_agen !=195 and cve_agen !=99 and cve_agen !=196 and cve_agen !=102  group by cve_agen ,cve_prod";
                                   




/*
$string_get_TbDes = sprintf("select cve_prod,cve_agen, tot_linea, sum(tot_cant) as VentaReal,sum(tot_linea) as VentaLine  from   pedidos.desv_GetAllVentVSPro where   falta_fac2  >=%s  and  falta_fac2 <=%s  group by cve_agen ,cve_prod",
                                    GetSQLValueString($INICIOFE, "date"),
                                    GetSQLValueString($FINFE, "date"));
*/
///****Obtenemos  las  Fechas 


///***Cadena  para  Hacer la  consulta nom_agen=150 and
//$string_get_TbDes = "SELECT cve_prod ,cve_age as  nom_agen, demanda ,mes ,anio  FROM pedidos.pronostico  where   mes = 3  and  anio = 2017 GROUP BY cve_prod" ;   ///"SELECT cve_prod , nom_agen, demanda, sum(tot_cant) as VentaReal,sum(tot_cant)-demanda AS Variacion ,mes ,anio ,falta_fac2 ,tot_cant,tot_linea FROM pedidos.desv_GetAllVentVSPro  where   falta_fac2 >= '2017-03-01' and falta_fac2 <= '2017-03-30'    group by cve_prod ";
///**
///$string_get_TbDes = "select cve_prod,cve_agen, tot_linea, sum(tot_cant) as VentaReal,sum(tot_linea) as VentaLine  from   pedidos.desv_GetAllVentVSPro where   falta_fac2  >=  '2017-03-01' and  falta_fac2 <= '2017-03-31'  group by cve_agen ,cve_prod" ;



////*Realizamso  Peticion  
$qery_des = mysqli_query($conecta1, $string_get_TbDes);

///***Areglo para Alamcenar  la  Variacion   Negativa 
$Are_VarPu_NE = array();
///***Areglo para Alamcenar  la  Variacion   Positivo  
$Are_VarPu_PO = array();
///**Variable TOTALNeg 
$ToltNeg =0; 
//***Variable TOTALPos
$ToltPos =0;
///***Ciclo para  Obtener la  VarPu 
while ( $fetch_desv= mysqli_fetch_array($qery_des)  )
{    
      /***Calculaos VentaReal select  Get_VentaReal(cve_agente ,cve_producto ,fechaIni ,fechaFin) ;
       $stringGetVtaReal   = sprintf("select  Get_VentaReal(%s ,%s,%s,%s) as RES",
                                GetSQLValueString($fetch_desv['cve_agen'], "int"), 
                                GetSQLValueString($fetch_desv['cve_prod'], "text"),
                                GetSQLValueString('2017-03-01', "date"),
                                GetSQLValueString('2017-03-31', "date"));
       $qeryGetVtreal =  mysqli_query($conecta1,$stringGetVtaReal);
       $fethVeta =  mysqli_fetch_array($qeryGetVtreal);*/
       
       ////****Obtenemos  la  Demanda   select  Get_Proyeccion(150  ,'BIO2005', '2017-03-31') as Demanda
       $string_getDemanda  = sprintf("select  Get_Proyeccion(%s ,%s, %s) as demanda",
                                GetSQLValueString($fetch_desv['cve_agen'], "int"), 
                                GetSQLValueString($fetch_desv['cve_prod'], "text"),
                                GetSQLValueString($INICIOFE, "date"));
      $qeryGetDemanda=  mysqli_query($conecta1,$string_getDemanda);
       $fethDemanda =  mysqli_fetch_array($qeryGetDemanda); 
       
            ///****Venta real  
       if($fethDemanda['demanda'] == null) {  $demanda = 0;}else {$demanda=$fethDemanda['demanda'];}

      ////***Calculamos la  Variacion 
       $variacion = $fetch_desv['VentaReal']-$demanda;
		
       
     /// **** Variacion por  Precio  Unitario  sin importar negativos O Positivos
       $preProd =$fetch_desv['VentaLine']/$fetch_desv['VentaReal'];     ///Get_PrecioProd($fetch_desv['cve_prod']);
       
       
      IF($fetch_desv['VentaReal'] > 0){ 
      
            $VarPu =$variacion*($preProd) ;
            ///***Generamos el  Objeto con Inforamcion Basica  para  despues  Buscar los elementos  Seleccionados
            $objeVar =  array("cve_prod"=>$fetch_desv['cve_prod'] , "nom_agen"=>$fetch_desv['cve_agen'],"VaA"=>$variacion,"VetR"=>$fetch_desv['VentaReal'],"PRO"=>$demanda ,"PrePro"=> $preProd,"VarPu"=> $VarPu ,"Porcent"=>0 );
           ///***Separamos  las  Variacions  si  son Positivos (+)
            if($variacion>0)
            {
                array_push($Are_VarPu_PO, $objeVar);
                $ToltPos += $VarPu;
            }
            ///***Separamos  las  Variacions  si  son Negativos (-)
            if($variacion<0)
            {
                array_push($Are_VarPu_NE, $objeVar);
                $ToltNeg += ($VarPu);//*-1;
            }

      }
     
}
///****Generamos Json De Resultado 
$arrayResult  =   array(
    "ToltNeg" => $ToltNeg,
    "ToltPos" => $ToltPos, 
    "Are_VarPu_NE"=> json_encode($Are_VarPu_NE) ,
    "Are_VarPu_PO"=> json_encode($Are_VarPu_PO)
);
 ///***Enviamos el  Objeto  Json 
header('Content-type: application/json');
 ECHO json_encode($arrayResult); 

?> 