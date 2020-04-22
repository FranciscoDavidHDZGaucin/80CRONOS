<?php
///****report_expedi_assis.php  
/*
********	 INFORMACION ARCHIVO ***************** 
	Nombre  Archivo : report_expedi_assis.php
 	Fecha  Creacion :18/05/2017
	Descripcion  : 
 *               Escrip Diseñado  para Generar Reporte  En Excel  de todos  
 *              los  espedientes  Desarrollado  paraas  la sisitente  de  Direccion 
 *              Comercial.
  */
 require_once('Connections/conecta1.php');
 require_once('formato_datos.php');

$string_1=("SELECT * FROM prospecto order by  nombre ");

$sql_clientes=mysqli_query($conecta1,$string_1) or die (mysqli_error($conecta1));
 ///*********Inicio  Documento  Excel********************************************************
    ///Cargamos  Librea  para  Excel 
   require_once 'Classes/PHPExcel.php'; 
    ////Generamos el  objeto  Excel 
          $objExcel =  new PHPExcel(); 
     ///Asignamos  Propiedades  libro  Excel 
            $objExcel->getProperties()->setCreator("Reporte Expedientes")
                       ->setLastModifiedBy("Fco David")  
                       ->setTitle("Reporte_Expedientes")
                       ->setSubject("")
                       ->setDescription("Informe Expedientes")
                       ->setKeywords("")
                       ->setCategory("ReporteExpedietes") ; //Categorias
    ///*******************************************************************     
    ///Titulo del  Documento
        $tituloDoc  = "Reporte_Expedientes"; 
    ///Declaramos  arreglo  con Titulos Columnas  12
        $titulosColumnas =  array ('Cliente','SAP','Nuevo','20','21','22','23','24','25','26','27','28','29','30','31','32','33','34','35','36','37','38','39','40','42'); 
   ///Agregamos titulos  del Pedido 
            $objExcel->setActiveSheetIndex(0)
                
                ->setCellValue('A3',$titulosColumnas[0]) ////Cliente
                ->setCellValue('B3',$titulosColumnas[1]) ////SAP   
                ->setCellValue('C3',$titulosColumnas[2]) ///Nuevo 
                ->setCellValue('D3',$titulosColumnas[3])//20
                ->setCellValue('E3',$titulosColumnas[4])//21
                ->setCellValue('F3',$titulosColumnas[5])//22
                ->setCellValue('G3',$titulosColumnas[6])//23
                ->setCellValue('H3',$titulosColumnas[7])//24
               ->setCellValue('I3',$titulosColumnas[8]) //25
               ->setCellValue('J3',$titulosColumnas[9])//26
               ->setCellValue('K3',$titulosColumnas[10])//27
               ->setCellValue('L3',$titulosColumnas[11])//28
               ->setCellValue('M3',$titulosColumnas[12])//29
               ->setCellValue('N3',$titulosColumnas[13]) //30
                 ->setCellValue('O3',$titulosColumnas[14])//31
                ->setCellValue('P3',$titulosColumnas[15])//32
                ->setCellValue('Q3',$titulosColumnas[16])//33
                ->setCellValue('R3',$titulosColumnas[17])//34
                ->setCellValue('S3',$titulosColumnas[18])//35
               ->setCellValue('T3',$titulosColumnas[19]) //36
               ->setCellValue('U3',$titulosColumnas[20])//37
               ->setCellValue('V3',$titulosColumnas[21])//38
               ->setCellValue('W3',$titulosColumnas[22])//39
               ->setCellValue('X3',$titulosColumnas[23])//40
               ->setCellValue('Y3',$titulosColumnas[24]);//42      
     //**Ciclo  para  agregar  la informacion
     $i = 4 ; 
     while ($fila=mysqli_fetch_array($sql_clientes))
     {
                $nuevo=$fila['c_nuevo'];
                $AregloDoct  =  array ();
                if($nuevo==1){ $NuRes ='SI'; }else{  $NuRes ='NO'; } 
            for ($j =20; $j <=41; $j++) {  
                                    $string_busca=sprintf("select * from documento where id_d=%s",
                                                 GetSQLValueString($j, "int"));
                                    $sql_buscar= mysqli_query($conecta1,$string_busca) or die (mysqli_error($conecta1));
                                    $datos_buscar=  mysqli_fetch_assoc($sql_buscar);
                                    $nombre= $datos_buscar['nombre'];
                                    $id1=$datos_buscar['id_d'];
                                    $id2=$datos_buscar['aux1']; 
                                    
                                ///SE tiene que buscar en la tabla de entrega los id de los documento elegido
                                    $string_bd=  sprintf("select * from entrega where (id_d=%s or id_d=%s) and id_p=%s ",
                                                 GetSQLValueString($id1, "int"),
                                                  GetSQLValueString($id2, "int"),
                                                   GetSQLValueString($fila['id_p'], "int")  );
                                    $query_bd= mysqli_query($conecta1,$string_bd) or die (mysqli_error($conecta1));
                                    $cuantos=  mysqli_num_rows($query_bd);
                            if ($cuantos>0){
                                         array_push($AregloDoct, utf8_encode($nombre));
                                    }else {
                                         array_push($AregloDoct,"");
                                    }
                        }
                     $objExcel->setActiveSheetIndex(0)
                            ->setCellValue('A'.$i,$fila['nombre'])
                            ->setCellValue('B'.$i,$fila['clave_sap'])
                            ->setCellValue('C'.$i,$NuRes)
                            ->setCellValue('D'.$i,$AregloDoct[0] )
                            ->setCellValue('E'.$i,$AregloDoct[1] )
                            ->setCellValue('F'.$i,$AregloDoct[2] ) 
                            ->setCellValue('G'.$i,$AregloDoct[3] )
                            ->setCellValue('H'.$i,$AregloDoct[4] )  
                            ->setCellValue('I'.$i,$AregloDoct[5] )
                            ->setCellValue('J'.$i,$AregloDoct[6] )
                            ->setCellValue('K'.$i,$AregloDoct[7] ) 
                            ->setCellValue('L'.$i,$AregloDoct[8] )  
                            ->setCellValue('M'.$i,$AregloDoct[9] )
                            ->setCellValue('N'.$i,$AregloDoct[10] )
                            ->setCellValue('O'.$i,$AregloDoct[11] )
                            ->setCellValue('P'.$i,$AregloDoct[12] ) 
                            ->setCellValue('Q'.$i,$AregloDoct[13] )
                            ->setCellValue('R'.$i,$AregloDoct[14] )  
                            ->setCellValue('S'.$i,$AregloDoct[15] )
                            ->setCellValue('T'.$i,$AregloDoct[16] )
                            ->setCellValue('U'.$i,$AregloDoct[17] ) 
                            ->setCellValue('V'.$i,$AregloDoct[18] )  
                            ->setCellValue('W'.$i,$AregloDoct[19] )
                            ->setCellValue('X'.$i,$AregloDoct[20] )
                              ->setCellValue('Y'.$i,$AregloDoct[21] );
                     
                     
                        
         $i++; 
        
                
     }
     
     
    /***************************************************************************************
        * Ahora procedemos a asignar el ancho de las columnas de forma automática en base al 
        contenido de cada una de ellas y lo hacemos con un ciclo de la siguiente forma.
       */
        for($i = 'A'; $i <= 'Y'; $i++){
            $objExcel->setActiveSheetIndex(0)->getColumnDimension($i)->setAutoSize(TRUE);    
        }
        ///*********************************************
        // Se asigna el nombre a la hoja
            $objExcel->getActiveSheet()->setTitle('Reporte_Expedientes');

            // Se activa la hoja para que sea la que se muestre cuando el archivo se abre
            $objExcel->setActiveSheetIndex(0);

            // Inmovilizar paneles
            //$objPHPExcel->getActiveSheet(0)->freezePane('A4');
            $objExcel->getActiveSheet(0)->freezePaneByColumnAndRow(0,4);
       ///******************************************************
        // Se manda el archivo al navegador web, con el nombre que se indica, en formato 2007
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="Reporte_Expedientes.xlsx"');
            header('Cache-Control: max-age=0');

            $objWriter = PHPExcel_IOFactory::createWriter($objExcel, 'Excel2007');
            $objWriter->save('php://output');
            exit;   
     ////******Fin  Diseño  Documento  Excel
?>	