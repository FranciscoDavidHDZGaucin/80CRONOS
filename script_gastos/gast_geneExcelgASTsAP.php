<?php
//////gast_geneExcelgASTsAP.php

/*
******* INFORMACION ARCHIVO ***************** 
	Nombre  Archivo : gast_geneExcelgASTsAP.php
 	Fecha  Creacion : 21/12/2017
	Descripcion  : 
 *             Escrip  para Genera  reporte  Excel para  Cargar  Sap 
 * 
	Modificado  Fecha  :

 *  
 */                        


$FECHA_GEN = DATE("d/m/Y");
 require_once('formato_datos.php');
 require_once('Connections/conecta1.php');
 require_once('conexion_sap/sap.php');
 require_once('funciones.php');
 require_once('fpdf/fpdf.php');
 mssql_select_db("AGROVERSA");  
 
 
  //**Libreria Excel 
require_once ('PHPExcel/PHPExcel.php');
///****************************************************************************************************
if ($_SESSION['fecha_start']!="" or $_SESSION['fecha_end']!=""){
		$op1="1";
	}else{
		$op1="0";
	}
		
	if ($_SESSION['empleado']!=0){
		$op2="1";
	}else{
		$op2="0";
	}	

   
 $todo=$op1.$op2;
	//echo $empleado . "<br>";
	//echo $todo . "<br>";

	
	switch ($todo) {
		case '00':   //Todos
					$query=("SELECT IF (pedidos.poliza.agente >= 1 AND pedidos.poliza.agente  <= 6,
                                                (SELECT  zona   FROM pedidos.relacion_gerentes WHERE cve_gte = pedidos.poliza.agente  group by cve_gte), 
                                                 IF(pedidos.poliza.agente >= 400 AND pedidos.poliza.agente  <= 450, nom_age,(SELECT nom_empleado  FROM pedidos.relacion_gerentes  where  cve_age = pedidos.poliza.agente ))) AS NombreGenall,
                                                 (round(sum(subtot_pago),2) + round(sum(iva_pago),2))  as pagado, 
                                                 round(sum(subtot_pago),2) as subtot, 
                                                 round(sum(iva_pago),2) as iva  
                                                 from pedidos.poliza  group by agente");
			break;
		case '10':   //Solo Fecha
				$query = sprintf("SELECT IF (pedidos.poliza.agente >= 1 AND pedidos.poliza.agente  <= 6,
                                                (SELECT  zona   FROM pedidos.relacion_gerentes WHERE cve_gte = pedidos.poliza.agente  group by cve_gte), 
                                                 IF(pedidos.poliza.agente >= 400 AND pedidos.poliza.agente  <= 450, nom_age,(SELECT nom_empleado  FROM pedidos.relacion_gerentes  where  cve_age = pedidos.poliza.agente ))) AS NombreGenall,
                                                 (round(sum(subtot_pago),2) + round(sum(iva_pago),2))  as pagado, 
                                                 round(sum(subtot_pago),2) as subtot, 
                                                 round(sum(iva_pago),2) as iva  
                                                 from pedidos.poliza  where poliza.f_pago>=%s and poliza.f_pago<=%s  group by agente", 
				GetSQLValueString($_SESSION['fecha_start'],"date"), 
				GetSQLValueString($_SESSION['fecha_end'],"date"));
				
				$suma_iva=sprintf("select round(sum(iva_pago)),2) as total_iva from poliza where pago>0 and  f_pago>=%s and f_pago<=%s",
				GetSQLValueString($_SESSION['fecha_start'],"date"), 
				GetSQLValueString($_SESSION['fecha_end'],"date"));
				
				$suma_sub=sprintf("select round(sum(subtot_pago)),2) as total_sub from poliza where pago>0 and  f_pago>=%s and f_pago<=%s",
				GetSQLValueString($_SESSION['fecha_start'],"date"), 
				GetSQLValueString($_SESSION['fecha_end'],"date"));
				
				$suma_tot=sprintf("select round(sum(pago)),2) as total_total from poliza where pago>0 and  f_pago>=%s and f_pago<=%s",
				GetSQLValueString($_SESSION['fecha_start'],"date"), 
				GetSQLValueString($_SESSION['fecha_end'],"date"));
				
				$suma_ret=sprintf("select round(sum(retencion)),2) as total_ret from poliza where pago>0 and  f_pago>=%s and f_pago<=%s",
				GetSQLValueString($_SESSION['fecha_start'],"date"), 
				GetSQLValueString($_SESSION['fecha_end'],"date"));
			break;
		case '01':   //Solo Empleado
				$query = sprintf("SELECT IF (pedidos.poliza.agente >= 1 AND pedidos.poliza.agente  <= 6,
                                                (SELECT  zona   FROM pedidos.relacion_gerentes WHERE cve_gte = pedidos.poliza.agente  group by cve_gte), 
                                                 IF(pedidos.poliza.agente >= 400 AND pedidos.poliza.agente  <= 450, nom_age,(SELECT nom_empleado  FROM pedidos.relacion_gerentes  where  cve_age = pedidos.poliza.agente ))) AS NombreGenall,
                                                 (round(sum(subtot_pago),2) + round(sum(iva_pago),2))  as pagado, 
                                                 round(sum(subtot_pago),2) as subtot, 
                                                 round(sum(iva_pago),2) as iva  
                                                 from pedidos.poliza where poliza.agente=%s  group by agente",
				GetSQLValueString($_SESSION['empleado'],"int"));
				
			break;	
		case '11':   //Fecha y empleado
				$query = sprintf("SELECT IF (pedidos.poliza.agente >= 1 AND pedidos.poliza.agente  <= 6,
                                                (SELECT  zona   FROM pedidos.relacion_gerentes WHERE cve_gte = pedidos.poliza.agente  group by cve_gte), 
                                                 IF(pedidos.poliza.agente >= 400 AND pedidos.poliza.agente  <= 450, nom_age,(SELECT nom_empleado  FROM pedidos.relacion_gerentes  where  cve_age = pedidos.poliza.agente ))) AS NombreGenall,
                                                 (round(sum(subtot_pago),2) + round(sum(iva_pago),2))  as pagado, 
                                                 round(sum(subtot_pago),2) as subtot, 
                                                 round(sum(iva_pago),2) as iva  
                                                 from pedidos.poliza   where poliza.agente=%s and poliza.f_pago>=%s and poliza.f_pago<=%s  group by agente", 
				GetSQLValueString($_SESSION['empleado'],"int"), 
				GetSQLValueString($_SESSION['fecha_start'],"date"), 
				GetSQLValueString($_SESSION['fecha_end'],"date"));				
				
			break;
	}  

                          $query_iva=mysqli_query($suma_iva,$conecta1) or die (mysql_error());
			  $query_sub=mysqli_query($suma_sub,$conecta1) or die (mysql_error());
                         $query_tot=mysqli_query($suma_tot,$conecta1) or die (mysql_error());
			  $query_ret=mysqli_query($suma_ret,$conecta1) or die (mysql_error());
			  
			  $row_iva = mysqli_fetch_array($query_iva);
			  $row_sub = mysqli_fetch_array($query_sub);
			  $row_tot = mysqli_fetch_array($query_tot);
			  $row_ret = mysqli_fetch_array($query_ret);
$sql_consulta=mysqli_query($query,$conecta1) or die (mysql_error());
$Total = mysqli_num_rows($sql_consulta);
$acumulado;			







///****************************************************************************************************
  $sql_consulta=mysqli_query($conecta1,$query) ;
////Generamos el  objeto  Excel 
          $objExcel =  new PHPExcel(); 
     ///Asignamos  Propiedades  libro  Excel 
           $objExcel->getProperties()->setCreator("Reporte Gastos")
                       ->setLastModifiedBy("Fco David")  
                       ->setTitle("Reporte Gastos_Sap".$FECHA_GEN)
                       ->setSubject("")
                       ->setDescription("Reporte Gastos  SAP")
                       ->setKeywords("cono")
                       ->setCategory("Reporte Pedidos") ;  //Categorias
    ///*******************************************************************    
    
    //Declaramos  arreglo  con Titulos Indice de Hoja:0
        $titulosColumnas =  array ('Centro de Costos','Nombre','Sub Total','Iva','Total'); 
   ///Agregamos titulos  del Cliente 
            $objExcel->setActiveSheetIndex(0)
                ->setCellValue('A1',$titulosColumnas[0])
                ->setCellValue('B1',$titulosColumnas[1])
                ->setCellValue('C1',$titulosColumnas[2])
                ->setCellValue('D1',$titulosColumnas[3])
                ->setCellValue('E1',$titulosColumnas[4]);    
     ///******************************************************
      //**Ciclo  para  agregar  la informacion
    $i = 2 ;
  if($Total<>0) { 
    
       while ($row = mysqli_fetch_array($sql_consulta)) {  
                           $objExcel->setActiveSheetIndex(0)
                           ///  ->setCellValue('A'.$i,$ROW);
                              ->setCellValue('A'.$i,$row['cc'])
                               ->setCellValue('B'.$i,$row['NombreGenall']) 
                               ->setCellValue('C'.$i,number_format($row['subtot'], 2, '.', ','))
                               ->setCellValue('D'.$i,number_format($row['iva'], 2, '.', ','))//$row[''])
                               ->setCellValue('E'.$i,number_format($row['pagado'], 2, '.', ','));//$row['']);
         $i++; 
     }
  }
  /***************************************************************************************
        * Ahora procedemos a asignar el ancho de las columnas de forma automática en base al 
        contenido de cada una de ellas y lo hacemos con un ciclo de la siguiente forma.
        */
       // for($i = 'A'; $i <= 'AG'; $i++){
            $objExcel->setActiveSheetIndex(0)->getColumnDimension('A')->setAutoSize(TRUE);   
            $objExcel->setActiveSheetIndex(0)->getColumnDimension('B')->setAutoSize(TRUE);
            $objExcel->setActiveSheetIndex(0)->getColumnDimension('C')->setAutoSize(TRUE);
            $objExcel->setActiveSheetIndex(0)->getColumnDimension('D')->setAutoSize(TRUE);
             $objExcel->setActiveSheetIndex(0)->getColumnDimension('E')->setAutoSize(TRUE);
       // } 
        // Inmovilizar paneles
            $objExcel->getActiveSheet(0)->freezePaneByColumnAndRow(0,2);
        ///*********************************************
        // Se asigna el nombre a la hoja
            $objExcel->getActiveSheet()->setTitle('Reporte Gastos Sap');
    
  
     //*********Fin Cono  Competidores *****************************************************************      
       // Se activa la hoja para que sea la que se muestre el  libro   0    
            $objExcel->setActiveSheetIndex(0);
       /////**************************              
        $dipotio = 'Content-Disposition: attachment;filename=';   
       $nombre="Reporte_Pedidos".$FECHA_GEN.".xlsx";
       $DISPOTION =$dipotio.$nombre;
        // Se manda el archivo al navegador web, con el nombre que se indica, en formato 2007
          ///  header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Type: application/vnd.ms-excel');
            header($DISPOTION);
            header('Cache-Control: max-age=0');
             $objWriter = PHPExcel_IOFactory::createWriter($objExcel, 'Excel2007');
            $objWriter->save('php://output');
            exit; 
            
///***FIN   Generar Reporte  por cve_cliente    
?> 
