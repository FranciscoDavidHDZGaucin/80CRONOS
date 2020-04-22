<?php

///****adi_update_cierre_mes
 /*
********	 INFORMACION ARCHIVO ***************** 
	Nombre  Archivo : adi_update_cierre_mes.php
 	Fecha  Creacion : 29/11/2016
	Descripcion  : 
                Scrip  para  Obtener  el CIERRE de  Mes
	Modificado  Fecha  : 
*/
///***Conexion Mysql  
require_once('../Connections/conecta1.php');
///***Formato  de Datosa
require_once('../formato_datos.php');   //para evitar poner la funcion de conversion de tipos de datos
////***Conexion   Sap 
require_once('../conexion_sap/sap.php');
///***Seleccion de la Bd 
 //mssql_select_db("AGROVERSA");
 ///****Function   para obtner la  venta  
 function   Get_Venta($cve_alamacen ,$cve_producto,$fecha_req)
 {
     ////***Conexion   Sap 
    require_once('../conexion_sap/sap.php');
    ///***Seleccion de la Bd 
     mssql_select_db("AGROVERSA");
   ///****Cadena  para  Obtener  la  Venta  Total  de la  Bodega
   $string_cadena_Get_VenTotal_Bodega  = "Select sum(cantidad)as VEN_POR_BODEGA    from   ventas_adicionales  where WhsCode  = '".$cve_alamacen."' and   codigo like  '".$cve_producto."' AND Annio = YEAR('".$fecha_req."') AND  Mes = Month('".$fecha_req."')";
   ///****Cadena  para  Obtener  las   Devoluciones  en   Bodega  
   $string_cedena_Get_Devo_Bodega  = "select  sum(Quantity)as DEVO_POR_BODEGA  from  devoluciones_adicionales  where  WhsCode ='".$cve_alamacen."' AND  ItemCode  like '".$cve_producto."' AND   Annio = YEAR('".$fecha_req."')  AND Mes =   MONTH('".$fecha_req."')";
   ///****Realizamos  Petecion  Para  Obtener  La  Venta  Total   de  RTES  Bodega 
    $qery_VenTotal_Bodega = mssql_query($string_cadena_Get_VenTotal_Bodega);
    ///****Realizamos  Petecion  Para  Obtener  Las  Devolucion  Totales por   Bodega
    $qery_Devo_Bodega = mssql_query($string_cedena_Get_Devo_Bodega);
    ///****Arreglos  Accociativo   Venta  Totalde  RTES  Bodega
        $fetch_Total_RTES_Bodega = mssql_fetch_array($qery_VenTotal_Bodega); 
        ///****Arreglos  Accociativo   Devoluciones DE  VENTA  TOTALES   RTES  Bodega
        $fecha_Devoluciones_Bodega =mssql_fetch_array($qery_Devo_Bodega);
        ///****Obtenemos la Cantidad   Venta  Totales  RTES Bodega
   $CAN_VENTOTLS =  $fetch_Total_RTES_Bodega['VEN_POR_BODEGA'];
   ///****Obtenemos la Cantidad  Devolucion  RTES  Bodegas
   $CAN_DEVOLUCIONES_VENTOTAL_RTES =  $fetch_Total_RTES_Bodega['DEVO_POR_BODEGA'];
        ////*** Resta  de elementos  Venta  Total de  RTES  Bodega
        return $res_vet_RTES_BODEGA  =  $CAN_VENTOTLS- $CAN_DEVOLUCIONES_VENTOTAL_RTES ;
     
 }
 
 ////*****Obtenemos  la fecha Actal
 $date_NOW  =   date("Y-m-d");
 ///***Mes
 $date_month_now =date("m", strtotime($date_NOW));  
 ///***Año 
 $date_year_now =date("Y", strtotime($date_NOW));
 ///***Dias 
 $date_day_now =date("d", strtotime($date_NOW));
///****Fin DE obtencion Fecha  actual  
 ///***Validamos que el  mes  no sea   0 
 $mes_anterior =$date_month_now-1; 
 if($date_month_now == 1)
 {      
     $mes_anterior = 12;
 }  
///****Objeto  conexion  Mysql 
    $mysqli_PRO =   new mysqli($hostname_conecta1,$username_conecta1,$password_conecta1,$database_conecta1); 
 
///**** Incio  para obtener   todos  los  registros de la tabla de adicionales 
  ///***Generamos  la  Cadena  
   $string_Tb_Adicionales =  "SELECT id,codigo_pro,almacen, Year(fecha_sol) as Anio, MONTH(fecha_sol)as mes ,fecha_sol FROM pedidos.adicionales  where fecha_cierre_mes   is   null order  by  id  asc ";
   ///***Generamos la  consulta 
   $qery_Tb_Adicionalles =   $mysqli_PRO->query($string_Tb_Adicionales);
 
 ///****Valida mos  que no existe  ningun  registro en   la tabla de adicionales con   la  fecha  a  modificar 
 ///***Generamos  la  Cadena    ***adi_get_num_elem_fecha_cierre_mes` ( mes  int , anio  int )
 $string_exis_fecha = sprintf("select  adi_get_num_elem_fecha_cierre_mes ( %s, %s) AS  RESUL", 
                                GetSQLValueString($date_month_now, "int"),
                                 GetSQLValueString($date_year_now, "int"));
 ///****Realizamos  el  Qery 
 $qery_exis_fech  = $mysqli_PRO->query($string_exis_fecha);
 ///**Obtenemos  Areglo  fetch  $fec =$ROW->fetch_array(MYSQLI_ASSOC);
 $arrego_fetch_exis_fecha = $qery_exis_fech->fetch_array(MYSQLI_ASSOC);
 ///***Obtenemos  le  Resultado  de la consulta 
 $RES_FECHA_EXIST = $arrego_fetch_exis_fecha['RESUL'];
 
 
               
 
 
 ///********************************************************************
        ///***Incio  de la  validacion  para  Determinar  si  debemos  actualizar  los  elementos   o  no 
        $res_update="";
        if($RES_FECHA_EXIST==0)
        {
            if($date_NOW > $mes_anterior|| ($date_NOW ==1 && $mes_anterior == 12 ) )
            {
           //Si es  igula  a  0 Debemos  de proseguir para  insertar   las  ventas  de  cierre 
           ///***Realizamos la consulta  para obtener  
            while($Tb_adi= $qery_Tb_Adicionalles->fetch_array(MYSQLI_ASSOC))
            {
               ///****
               $fecha_solicitud  = $Tb_adi['fecha_sol'];
               $fech_sol_mes =  date("m", strtotime($fecha_solicitud));
               if ($fech_sol_mes==$mes_anterior)
               {
                    ///***Buscamos en  SQL  LA venta  para  el  mes   y cve producto   y  almacen  pedido 
                   ///***Obtenemos  la  Venta  Get_Venta($cve_alamacen ,$cve_producto,$fecha_req)
                    $venta_Cierre_Mes  =  Get_Venta($Tb_adi['almacen'] ,$Tb_adi['codigo_pro'],$date_year_now."-".$mes_anterior."-".$date_day_now);
                    $Insert_venta_cierre_mes = sprintf("Update adicionales set  vent_cierre_mes= %s, fecha_cierre_mes=%s  where  id = %s",
                    GetSQLValueString($venta_Cierre_Mes,"int"),
                            GetSQLValueString($date_NOW,"date"),
                    GetSQLValueString($Tb_adi['id'],"int") );

                     $mysqli_PRO =   new mysqli($hostname_conecta1,$username_conecta1,$password_conecta1,$database_conecta1); 

                    if(!$ROW =$mysqli_PRO->query($Insert_venta_cierre_mes)){
                     $res_update = "Error";
                    }
               }    
            }
            ////****
            }
        }
 
 
  ///**Realizamos  consulta SQL para  las  ventas  para   
 
     
 

 $areglo_json   = array (
      "MES" => $date_month_now,
      "YEAR" =>$RES_FECHA_EXIST,
      "DAY" =>$res_update,
 );
 ///***Enviamos el  Objeto  Json 
header('Content-type: application/json');
 ECHO json_encode($areglo_json); 
 
 ?> 
         