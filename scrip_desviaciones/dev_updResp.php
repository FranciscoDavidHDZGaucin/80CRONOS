<?php
/////****dev_updResp.php 
/*
********	 INFORMACION ARCHIVO ***************** 
	Nombre  Archivo : dev_updResp.php
 	Fecha  Creacion : 08/07/2017
	Descripcion  : 
 *              Escrip  Diseñado  Para Agregar  las   Respuestas 
 *              a las  Variaciones 
 *
  */
require_once('../formato_datos.php');
require_once('../Connections/conecta1.php');

///****Funcion para  Obtener El  ID 
 Function  GetIdUpdate($ObjeVar,$conecxion)
 {
   $Res= 0;
    //**select id from pedidos.desv_desviaciones_8020     WHERE cve_agente= 145  and  cve_prod = "2103" and  fech_Ini = '2017-05-01'
   $strGetID = sprintf("select id from pedidos.desv_desviaciones_8020 WHERE cve_agente=%s  and  cve_prod =%s and  fech_Ini=%s",
                           GetSQLValueString($ObjeVar->{'cve_Age'}, "int"),
                           GetSQLValueString($ObjeVar->{'cve_prod'}, "text"),
                           GetSQLValueString($ObjeVar->{'fech_Ini'}, "date")
           );

    $qeryGetID = mysqli_query($conecxion, $strGetID); 
    if($qeryGetID)
    {
      $fetch = mysqli_fetch_array($qeryGetID);
       $Res = $fetch['id'];  
        
    }
    
    return $Res;
 }
///***Varaible de Error
/*Entiendase que el  Estado  Inicial de la  varible 
 *          $ERRORqERYS => 0  => Sin Ningun Error al  momento  de ejecutar el  Escript 
 *          $ERRORqERYS => 1  => Error al  momento de Obtener el  Id de  la  Variacion
 *          $ERRORqERYS => 2  => Error al  momento de Realizar el  Update 
 *          $ERRORqERYS => 3  => Error al  momento de Obtener ID de la  Cabezara.
 *          $ERRORqERYS => 4  => Error al  momento de  Hacer  Update a la  cabezara  de  la  Desviacion
 *  */
 $ERRORqERYS = 0 ; 
////**********************************************************************
/***Varible  $TipoUpdate
        Entiendase que  $TipoUpdate => 0  Modificacion  Para Solo  Un Objeto  Y 
 *                      $TipoUpdate => 1  Obtenemos el Arreglo copleto  para Mandar Modificar
 *                                        Todas las   desviaciones  del  Agente 
 *  */
$TipoUpdate = filter_input(INPUT_POST, 'MAUPD');

///**Modificacion  para  solo  Un Objeto 
if($TipoUpdate == 0 ){
            ////***Obtenemos el  Objeto a Modificar 
            $JSON_ELEM = filter_input(INPUT_POST, 'ELEM');
            $OBJT=json_decode($JSON_ELEM);

            ///***Obtenemos  el  ID  de la  Variacion a MModificar  
            $ID_UPDTE = GetIdUpdate($OBJT,$conecta1);

            if($ID_UPDTE !=0){

                ////***Convertimos el  estado de la Opcion de  Cultivo 
                if($OBJT->{'OpcAct'}==false )
                {
                    $opcult = 0;
                }else{
                      $opcult = 1;
                }

             ///***Cadena  para Actualizar la  Variacion
            $strUpdRes = sprintf("Update pedidos.desv_desviaciones_8020 set NIVEL1=%s ,NIVEL2=%s,NIVEL3=%s, NIVEL4=%s ,Opcult=%s  where id =%s ",
                             GetSQLValueString($OBJT->{'ResNVL1'}, "int"),
                             GetSQLValueString($OBJT->{'ResNVL2'}, "int"),
                             GetSQLValueString($OBJT->{'ResNVL3'}, "int"),
                             GetSQLValueString($OBJT->{'ResNVL4'}, "int"),
                             GetSQLValueString($opcult, "int"),        
                             GetSQLValueString($ID_UPDTE, "int")        
                    );
             ///***Realizamos el  Qery
             $qeryUpdete = mysqli_query($conecta1, $strUpdRes);

             if(!$qeryUpdete)
             {
                 $ERRORqERYS =2; ///Error No  Se realizo  el  Update
             }

            }else{
                $ERRORqERYS =1; ///Error No Exite el  ID 
            } 

}
///****Modificacion para Arreglo de  Objeto  
if($TipoUpdate == 1)
{
        ///****Areglo  Para  Elementos a Modificar
     $Are_Resp= json_decode(filter_input(INPUT_POST, 'OBJETOS')); 
     foreach ($Are_Resp as $OBJT)
     {
         ///***Obtenemos  el  ID  de la  Variacion a MModificar  
            $ID_UPDTE = GetIdUpdate($OBJT,$conecta1);

            if($ID_UPDTE !=0){

                ////***Convertimos el  estado de la Opcion de  Cultivo 
                if($OBJT->{'OpcAct'}==false )
                {
                    $opcult = 0;
                }else{
                      $opcult = 1;
                }

             ///***Cadena  para Actualizar la  Variacion
            $strUpdRes = sprintf("Update pedidos.desv_desviaciones_8020 set NIVEL1=%s ,NIVEL2=%s,NIVEL3=%s, NIVEL4=%s ,Opcult=%s,desvConts=1  where id =%s ",
                             GetSQLValueString($OBJT->{'ResNVL1'}, "int"),
                             GetSQLValueString($OBJT->{'ResNVL2'}, "int"),
                             GetSQLValueString($OBJT->{'ResNVL3'}, "int"),
                             GetSQLValueString($OBJT->{'ResNVL4'}, "int"),
                             GetSQLValueString($opcult, "int"),        
                             GetSQLValueString($ID_UPDTE, "int")        
                    );
             ///***Realizamos el  Qery
             $qeryUpdete = mysqli_query($conecta1, $strUpdRes);

             if(!$qeryUpdete)
             {
                 $ERRORqERYS =2; ///Error No  Se realizo  el  Update
             }

            }else{
                $ERRORqERYS =1; ///Error No Exite el  ID 
            }
     }
     if($ERRORqERYS==0){
          /*
            "cve_Age"=>$row['cve_agente'],
                        "cve_prod"=>$row['cve_prod'],
                        "nom_prod"=>$fetchNoProd['ItemName'],
                        "variacion"=>$row['variacion'],
                        "VTReal"=>$row['VentReal'],
                        "Demanda"=>$demanda,
                        "fech_Ini" =>$row['fech_Ini'],
                        "fech_fin" =>$row['fech_fin'],
           *            */
         
         ///***Obtenemos el Primer Objeto  par Obtener la Informacion Basica  
         $ObjtDes = $Are_Resp[0];
         ///**Generamos Cadena  
         $strGetID_cab  = sprintf("select id  from desv_encabeza_desviacion  where   fech_fin=%s  and  fech_Ini=%s  and cve_agente =%s ", 
                                  GetSQLValueString($ObjtDes->{'fech_fin'}, "date"),
                                  GetSQLValueString($ObjtDes->{'fech_Ini'}, "date"),
                                  GetSQLValueString($ObjtDes->{'cve_Age'}, "int")
                            );
         ////**Realizamos el  QERY  
         $QERYgETID = mysqli_query($conecta1, $strGetID_cab);
         if(!$QERYgETID){
            $ERRORqERYS =3;
         }else{
             $fethID = mysqli_fetch_array($QERYgETID);
             ///****Realizamos la Modificacion a
             $strUodaEnca = sprintf("UPDATE desv_encabeza_desviacion SET estas_ans=1,fech_term_ans=NOW() where  id=%s",
                                 GetSQLValueString($fethID['id'], "int"));
             $qeryUpdateID = mysqli_query($conecta1, $strUodaEnca);
             if(!$qeryUpdateID){
                 $ERRORqERYS =4;
             }
             
         }
         
         
     }
    
}   



///****Generamos Json De Resultado 
$arrayResult  =   array( 
                        "ERRORES" => $ERRORqERYS
                    );
 ///***Enviamos el  Objeto  Json 
header('Content-type: application/json');
 ECHO json_encode($arrayResult); 

?> 