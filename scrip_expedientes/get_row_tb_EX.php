<?php
/*
********	 INFORMACION ARCHIVO ***************** 
	Nombre  Archivo : get_row_tb_EX.php
 	Fecha  Creacion :  14/03/2017 
	Descripcion  : 
 *          Escrip   para Obtener  tablas  
  */
///**
///**Librerias  para conexion  a  Mysql
 require_once('../Connections/conecta1.php');
//para evitar poner la funcion de conversion de tipos de datos
 require_once('../formato_datos.php');
 
 
 $NumElem  = filter_input(INPUT_POST, 'Num');
 
$string_1=("SELECT * FROM prospecto order by nombre");
$sql_1=mysqli_query($conecta1,$string_1) or die (mysqli_error($conecta1));

$TOLeLEMTOS = mysqli_num_rows($sql_1);

$sql_clientes=mysqli_query($conecta1,$string_1) or die (mysqli_error($conecta1));

 
             $I=0;
             while($rowc = mysqli_fetch_array($sql_clientes)) { 
                 
                 if($I==$NumElem){ 
                         $Identificador = $rowc['id_p'];
                        $nuevo=$rowc['c_nuevo'];   //1=nuevo
                         if($nuevo==1){  $td_estilo = '<td bgcolor="#92D025" title="Cliente Nuevo">'; } else {$td_estilo = "<td>";}  
                        $ROW__HTML_001 =$td_estilo.$rowc['nombre']."</td><td>".$rowc['clave_sap']."</td>"; 
                        $ROW__HTML_002 =$td_estilo; 
                                           if($nuevo==1){ $ROW__HTML_002 .='SI'; }else{ $ROW__HTML_002 .='NO'; }
                                           $ROW__HTML_002 .="</td>";
                              $ROW__HTML_003="";
                              for ($i =20; $i <=41; $i++) {    ///es el numero de documentos que se solicitan
                           //Buscar en la tabla de documento que otro id de documento se contempla
                                           $string_busca=sprintf("select * from documento where id_d=%s",
                                                        GetSQLValueString($i, "int"));


                                           $sql_buscar= mysqli_query($conecta1,$string_busca) or die (mysqli_error($conecta1));
                                           $datos_buscar=  mysqli_fetch_assoc($sql_buscar);
                                           $nombre= $datos_buscar['nombre'];
                                           $id1=$datos_buscar['id_d'];
                                           $id2=$datos_buscar['aux1']; 
                                         $ROW__HTML_003.="<td title=".utf8_encode($nombre).">";



                                           ///SE tiene que buscar en la tabla de entrega los id de los documento elegido
                                       $string_bd=  sprintf("select * from entrega where (id_d=%s or id_d=%s) and id_p=%s ",
                                                    GetSQLValueString($id1, "int"),
                                                     GetSQLValueString($id2, "int"),
                                                      GetSQLValueString($rowc['id_p'], "int")  );
                                       $query_bd= mysqli_query($conecta1,$string_bd) or die (mysqli_error($conecta1));
                                       $cuantos=  mysqli_num_rows($query_bd);

                                       if ($cuantos>0){
                                       $ROW__HTML_003.="<img title='".utf8_encode($nombre)."' src='images/ico-fichero.gif'>"; 
                                       }

                           $ROW__HTML_003.= "</td>";

                         }
                 
                         $I++;
                   }
                 $I++;
                
         ///   </tr>
             }
             
             $arreglores=  array(
                 "TotalElem"=>$TOLeLEMTOS,
                 "R1"=>$ROW__HTML_001,
                 "R2"=>$ROW__HTML_002,
                 "R3"=>$ROW__HTML_003,
                 "Datowork"=>$Identificador,
                 "Numconsuta" => $NumElem
                 
             );
              $json_rows = json_encode($arreglores);
              header('Content-type: application/json');
              echo $json_rows;

                 
            ?>
            