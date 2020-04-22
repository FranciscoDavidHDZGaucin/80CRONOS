<?php   

//////***proyec_addproyeccion.php
/*
********	 INFORMACION ARCHIVO ***************** 
	Nombre  Archivo : proyec_addproyeccion.php
 	Fecha  Creacion : 07/03/2018
	Descripcion  : 

		SCRIPT ENCARGADO DE CAPTURAR  LAS   PROYECCIONES
		
	Varible ERROR
		 typeError =0 => nINGUN ERRO  ENONTRADO 
 *       typeError =1 => Error Obtener  Configuracion de meses  
 		 typeError =3=> Error Insertar  Proyeccio Confirmacion    
  */

////*****CONTROL DE  INCIO  DE   SECION

session_start ();
$MM_restrictGoTo = "login.php";
if (!(isset($_SESSION['usuario_valido']))){
header("Location: ". $MM_restrictGoTo);
exit;
}
require_once( '../CONEC_UNIFICADO/conecction.php');  
require_once('../formato_datos.php');
require_once('../Connections/conecta1.php');

///****Numero de agente 
$NUMAGENT = $_SESSION["usuario_agente"];
///***Obtenemos  los  Arreglos  json 		
$AREMAINPROYEC = json_decode(filter_input(INPUT_POST, 'ObjsPro'));
$typeError = 0; 



$RESULT ="";
////*****Ciclo principal para  cargar PROYECCIO0NES  cveclie
foreach ($AREMAINPROYEC as $OBJ) 
{

	///*****Obtenemos Meses  
    if(!$qerygetMeconfig = mysqli_query($conecta1, "select * from pedidos.proyec_mesasignacion")) 
	{
		   $typeError =  array(
		   						"NUM_ERROR"=>  1 , ///***Error Obtener  Configuracion de meses 
								"ERROR BD" => mysqli_error($conecta1)  
							)    ;  
	}else
	{
			////***Obtenemos  areglo fetch  
			$ProyEnCur = mysqli_fetch_array($qerygetMeconfig);
		    $CVE_PROD =   $OBJ->{'cveprod'} ; 
			$CVE_CLIENTE  =  $OBJ->{'cveclie'};  

			///******Ejecutamos  Procedimiento para   determinarsi se actualiza   el registro  ho se insertad 

			////***Nombre del  Procidimiento  a Utilizar 
			$sTOREPRO =   mssql_init(" backend.spGET_ESTATUS_PROYECCION");
			mssql_query("SET ANSI_NULLS ON");
			 mssql_query("SET ANSI_WARNINGS ON");
			/*Agregmos   los parametros a  enviar */
			mssql_bind($sTOREPRO, '@CVE_PROD ',$CVE_PROD, SQLVARCHAR );
			mssql_bind($sTOREPRO, '@CVE_CLIENTE',$CVE_CLIENTE, SQLVARCHAR);
		    mssql_bind($sTOREPRO, '@NUMAGE', $NUMAGENT, SQLINT4);

		    ///*****Obtenemos Meses que se Actulizaran hooo  Insertaran
		    if(!$qerygetUPDINSERT = mssql_execute($sTOREPRO)) 
			{
				$typeError =  array(
		   						"NUM_ERROR"=>  2 , ///***Error Obtener Determinacion de estatus 
								"ERROR BD" => mssql_get_last_message()  
							)    ;  



			}else
			{
				////***Obtenemos  areglo fetch de Proyecciones a Actualizar 
				$ProyActorUP = mssql_fetch_array($qerygetUPDINSERT);
				///Asignamos  Arreglo de capturas de  Plataforma 
				$JSONCANTIDAD = $OBJ->{'proycapt'};	
				////****Definimos Insercion or Update
				$Total = mssql_num_rows ($qerygetUPDINSERT);
				////***
				$RESULT =$ProyActorUP['TyAC_Confir'];
				 ///*****************************************************************************************************************************************************************************
				 ///*****************************************************************************************************************************************************************************
				 ///******************************Insercion y Update Confirmacion **********************************************************************************************************
				 ///*****************************************************************************************************************************************************************************
				 ///*****************************************************************************************************************************************************************************	
					if(is_null($ProyActorUP['TyAC_Confir'])==true || $Total ==0|| is_null($Total)== true  )
					{
						///*****No EXISTE  PROYECCION   procedemos a  agregarla
						$esatus="NO EXISTE WEEEEE :v "; 	
                                                if($OBJ->{'prsidcnf'} == 0 )
                                                {
                                                  
                                                       $IDPRS_DET  =0;///$OBJ->{'prsid'}; 
                                                }else{
                                                 $IDPRS_DET = $OBJ->{'prsidcnf'} ;

                                                }
						////**************STRING 	INSERCION CONFIRMACION ************************************************************************************************************************************************************
						$STR_INS_PROYEC_CONFIR = sprintf("Insert INTO proyec_proyeccion_main  SET id_prs=%s,num_agente=%s,cve_prod=%s,cve_cliente=%s,nom_prod=%s,nom_clien=%s,mes=%s,anio=%s,precio=%s,costo=%s,monto=%s,proyeccion=%s,familia=%s,ventahisto=%s",
						GetSQLValueString($IDPRS_DET ,"int"),  
						GetSQLValueString($NUMAGENT,"int"),
						GetSQLValueString($OBJ->{'cveprod'},"text"),
						GetSQLValueString($OBJ->{'cveclie'},"text"),
						GetSQLValueString($OBJ->{'nom_prod'},"text"),
						GetSQLValueString($OBJ->{'nom_cli'},"text"),
						GetSQLValueString($ProyEnCur['mes_confir'], "int"),
					    GetSQLValueString($ProyEnCur['year_confir'],"int"),
						GetSQLValueString($JSONCANTIDAD->{'preconfi'},"text"),
						GetSQLValueString(0,"int"),
						GetSQLValueString($JSONCANTIDAD->{'montoconfi'},"int"),
						GetSQLValueString($JSONCANTIDAD->{'ProyCNF'},"int"),
                                                GetSQLValueString($OBJ->{'familia'},"text") ,
                                                GetSQLValueString($OBJ->{'venta1CNF'},"text")
                                                        );
						///*****Obtenemos Meses que se Actulizaran hooo  Insertaran
					    if(!mysqli_query($conecta1, $STR_INS_PROYEC_CONFIR)) 
						{
							
							$typeError =  array(
		   						"NUM_ERROR"=>  3 , ///***Error Insertar  Proyeccio Confirmacion  
								"ERROR" => mysqli_error($conecta1)  
							)    ;  

						}

						///*****Obtenemos Meses que se Actulizaran hooo  Insertaran
					    if(!$IDLAST = mysqli_query($conecta1, "SELECT MAX(id) AS id FROM proyec_proyeccion_main")) 
						{
						
								$typeError =  array(
		   						"NUM_ERROR"=>  4 , ///***Error OBTNER ID Proyeccio Confirmacion  
								"ERROR" => mysqli_error($conecta1)  
							)    ;  


						}else{

								////***Obtenemos  ID Proyeciones  
								$ID_PROYEC = mysqli_fetch_array($IDLAST);
                                                                   if($OBJ->{'prsidcnf'} == 0 )
                                                                {

                                                                       $IDPRS_DET  =0;///$OBJ->{'prsid'}; 
                                                                }else{
                                                                 $IDPRS_DET = $OBJ->{'prsidcnf'} ;

                                                                }
								/*-----------------------Generamos  Cadena  InsertHistorico  ----------------------------------------*/ 
								$STR_HISTO_PROYEC = sprintf("Insert INTO proyec_historico  SET id_presu=%s ,id_proyec=%s, cve_agente=%s,cve_producto=%s,cve_cliente=%s,nom_prod=%s,nom_cliente=%s,mes=%s,anio=%s,precio=%s,costo=%s,monto=%s,proyeccion=%s,estatus_proyec
									=%s,familia=%s",
								GetSQLValueString($IDPRS_DET,"int"), 
								GetSQLValueString($ID_PROYEC['id'],"int"),  
								GetSQLValueString($NUMAGENT,"int"),
								GetSQLValueString($OBJ->{'cveprod'},"text"),
								GetSQLValueString($OBJ->{'cveclie'},"text"),
								GetSQLValueString($OBJ->{'nom_prod'},"text"),
								GetSQLValueString($OBJ->{'nom_cli'},"text"),
								GetSQLValueString($ProyEnCur['mes_confir'], "int"),
							    GetSQLValueString($ProyEnCur['year_confir'],"int"),
								GetSQLValueString($JSONCANTIDAD->{'preconfi'},"text"),
								GetSQLValueString(0,"int"),
								GetSQLValueString($JSONCANTIDAD->{'montoconfi'},"int"),
								GetSQLValueString($JSONCANTIDAD->{'ProyCNF'},"int"),
								GetSQLValueString('CNF',"text"),
                                                                GetSQLValueString($OBJ->{'familia'},"text")        
								); 
								///*****
								  if(!mysqli_query($conecta1, $STR_HISTO_PROYEC)) 
								{
								
								$typeError =  array(
						   						"NUM_ERROR"=>  5 , ///***Error Insertar  Proyeccio HISTORICA  Confirmacion  
												"ERROR" => mysqli_error($conecta1)  
											)    ; 




								}
						 }		

					}else
					{ //////Incio   Proceso de Modificacion   
			             ///****************************		
							////******Existe  Proyeccion  Confirmacion 
							$esatus="SI EXISTE WEEEEE :v "; 
							///****Buscamos  ID EN TB  PROYECCIONES  
						    $STR_GET_ID_TB_PROYEC = sprintf("SELECT id from proyec_proyeccion_main where num_agente=%s and cve_prod =%s and cve_cliente=%s and   mes =%s  and anio =%s ", 
							GetSQLValueString($NUMAGENT,"int"),
                                                        GetSQLValueString($OBJ->{'cveprod'},"text"),
                                                        GetSQLValueString($OBJ->{'cveclie'},"text"),
							GetSQLValueString($ProyEnCur['mes_confir'], "int"),
							GetSQLValueString($ProyEnCur['year_confir'],"int"));
						        //*****Obtenemos ID PROYECCION
							   if(!$qeryserID = mysqli_query($conecta1, $STR_GET_ID_TB_PROYEC)) 
								{
									$typeError =4; ///***Error oBTENER ID proyecion 
								}else{
										////***Obtenemos  ID Proyeciones  
										$ID_PROYEC = mysqli_fetch_array($qeryserID);
										$PROYEID = $ID_PROYEC['id'];
										////**************STRING 	Update  CONFIRMACION ********************id_prs=%s,****************************************************************************************************************************************
										$STR_UPDATE_PROYEC_CONFIR = sprintf("UPDATE  proyec_proyeccion_main  SET num_agente=%s,cve_prod=%s,cve_cliente=%s,nom_prod=%s,nom_clien=%s,mes=%s,anio=%s,precio=%s,costo=%s,monto=%s,proyeccion=%s where id=%s",
										///GetSQLValueString($OBJ->{'prsid'},"int"),  
										GetSQLValueString($NUMAGENT,"int"),
										GetSQLValueString($OBJ->{'cveprod'},"text"),
										GetSQLValueString($OBJ->{'cveclie'},"text"),
										GetSQLValueString($OBJ->{'nom_prod'},"text"),
										GetSQLValueString($OBJ->{'nom_cli'},"text"),
										GetSQLValueString($ProyEnCur['mes_confir'], "int"),
									    GetSQLValueString($ProyEnCur['year_confir'],"int"),
										GetSQLValueString($JSONCANTIDAD->{'preconfi'},"text"),
										GetSQLValueString(0,"int"),
										GetSQLValueString($JSONCANTIDAD->{'montoconfi'},"int"),
										GetSQLValueString($JSONCANTIDAD->{'ProyCNF'},"int"),
										GetSQLValueString($PROYEID,"int"));
										///*****Obtenemos Meses que se Actulizaran hooo  Insertaran
									    if(!mysqli_query($conecta1, $STR_UPDATE_PROYEC_CONFIR)) 
										{
												$typeError =  array(
												   						"NUM_ERROR"=>  6 , ///***Error UPDATE  Proyeccio Confirmacion   
																		"ERROR" => mysqli_error($conecta1)  
																	)    ; 
										}


										///****Buscamos  ID EN TB  historico  PROYECCIONES  
										    $STR_GET_ID_TB_HIS_PROYEC = sprintf("SELECT id from proyec_historico where id_proyec=%s ", 
											GetSQLValueString($PROYEID,"int"));
										///*****Obtenemos ID historico proyecciones  PROYECCION
										   if(!$qeryserhisID = mysqli_query($conecta1, $STR_GET_ID_TB_HIS_PROYEC)) 
											{
												
												$typeError =  array(
												   						"NUM_ERROR"=>  7 , ///***Error oBTENER ID Historico    
																		"ERROR" => mysqli_error($conecta1)  
																	)    ; 
											}else{

												////***Obtenemos  ID Proyeciones  
												$ID_HISPROYEC = mysqli_fetch_array($qeryserhisID);
											/*-----------------------Generamos  Cadena  Update  Historico  --------id_presu=%s,--------------------------------*/ 
											$STR_HISTO_PROYEC = sprintf("UPDATE proyec_historico  SET id_proyec=%s ,cve_agente=%s,cve_producto=%s,cve_cliente=%s,nom_prod=%s,nom_cliente=%s,mes=%s,anio=%s,precio=%s,costo=%s,monto=%s,proyeccion=%s ,estatus_proyec=%s  where id=%s",
											///GetSQLValueString($OBJ->{'prsid'},"int"), 
											GetSQLValueString($PROYEID,"int"), 
											GetSQLValueString($NUMAGENT,"int"),
											GetSQLValueString($OBJ->{'cveprod'},"text"),
											GetSQLValueString($OBJ->{'cveclie'},"text"),
											GetSQLValueString($OBJ->{'nom_prod'},"text"),
											GetSQLValueString($OBJ->{'nom_cli'},"text"),
											GetSQLValueString($ProyEnCur['mes_confir'], "int"),
										    GetSQLValueString($ProyEnCur['year_confir'],"int"),
											GetSQLValueString($JSONCANTIDAD->{'preconfi'},"text"),
											GetSQLValueString(0,"int"),
											GetSQLValueString($JSONCANTIDAD->{'montoconfi'},"int"),
											GetSQLValueString($JSONCANTIDAD->{'ProyCNF'},"int"),
											GetSQLValueString('CNF',"text"),
											GetSQLValueString($ID_HISPROYEC['id'],"int")
											); 
											///*****
											  if(!mysqli_query($conecta1, $STR_HISTO_PROYEC)) 
											{
											
												$typeError =  array(
												   						"NUM_ERROR"=>  7 , ///***Error Update  Proyeccio HISTORICA  Confirmacion   
																		"ERROR" => mysqli_error($conecta1)  
																	)    ; 
											}

										}


				         		}
				        }////******Fin  llave  de  Modificacion    NCF 

				        //******************************************************************************************************************************************************************************
						///*****************************************************************************************************************************************************************************
						///*********************Insercion y Update Revicion 1**************************************************************************************************************************************
						///*****************************************************************************************************************************************************************************
						///*****************************************************************************************************************************************************************************	

						if( is_null($ProyActorUP['TyAC_REV1'])==true ||  $ProyActorUP['TyAC_REV1']== 0  || $Total ==0|| is_null($Total)== true  )
						{
								///*****No EXISTE  PROYECCION   procedemos a  agregarla
								$esatus="NO EXISTE WEEEEE :v "; 	

									/*
										"ProyCNF":$(idmadeCNF).val(),
									    				"ProyREV1":$(idmadeREV1).val(),
									    				"ProyREV2":$(idmadeREV2).val()
														"ProyCNF":$(idmadeCNF).val(),
									    				"ProyREV1":$(idmadeREV1).val(),
									    				"ProyREV2":$(idmadeREV2).val(), 
									    				"preconfi":$(idmadeREV2).atrr('preconfi'),
									    				"montoconfi":$(idmadeREV2).atrr('montoconfi'),
									    				"prerev01":$(idmadeREV2).atrr('prerev01'),
									    				"montorev01":$(idmadeREV2).atrr('montorev01'), 
									    				"prerev02":$(idmadeREV2).atrr('prerev02'),
									    				"montorev02":$(idmadeREV2).atrr('montorev02')


									*/
								if($OBJ->{'prsidcnf'} == 0 )
                                                                    {
                                                                      
                                                                         $IDPRS_DET  =0;///$OBJ->{'prsid'}; 
                                                                        
                                                                    }else{
                                                                       $IDPRS_DET = $OBJ->{'prsidcnf'} ;
                                                                    }
								//$cantidadConfirmacion  = $JSONCANTIDAD->{'ProyCNF'};
								////**************STRING 	INSERCION CONFIRMACION ************************************************************************************************************************************************************
								$STR_INS_PROYEC_CONFIR = sprintf("Insert INTO proyec_proyeccion_main  SET id_prs=%s,num_agente=%s,cve_prod=%s,cve_cliente=%s,nom_prod=%s,nom_clien=%s,mes=%s,anio=%s,precio=%s,costo=%s,monto=%s,proyeccion=%s,familia=%s,ventahisto=%s ",
								GetSQLValueString($IDPRS_DET,"int"),  
								GetSQLValueString($NUMAGENT,"int"),
								GetSQLValueString($OBJ->{'cveprod'},"text"),
								GetSQLValueString($OBJ->{'cveclie'},"text"),
								GetSQLValueString($OBJ->{'nom_prod'},"text"),
								GetSQLValueString($OBJ->{'nom_cli'},"text"),
								GetSQLValueString($ProyEnCur['mes_rev1'], "int"),
							    GetSQLValueString($ProyEnCur['year_rev1'],"int"),
								GetSQLValueString($JSONCANTIDAD->{'prerev01'},"text"),
								GetSQLValueString(0,"int"),
								GetSQLValueString($JSONCANTIDAD->{'montorev01'},"int"),
								GetSQLValueString($JSONCANTIDAD->{'ProyREV1'},"int"),
                                                                GetSQLValueString($OBJ->{'familia'},"text"),
                                                                  GetSQLValueString($OBJ->{'venta1RV1'},"text")     
                                                                        
                                                                        );
								///*****Obtenemos Meses que se Actulizaran hooo  Insertaran
							    if(!mysqli_query($conecta1, $STR_INS_PROYEC_CONFIR)) 
								{
									$typeError =  array(
												   		 "NUM_ERROR"=>  8 ,///***Error Insertar  Proyeccio REV1   
														 "ERROR" => mysqli_error($conecta1)  
									         			)    ; 



								}

								///*****Obtenemos Meses que se Actulizaran hooo  Insertaran
							    if(!$IDLAST = mysqli_query($conecta1, "SELECT MAX(id) AS id FROM proyec_proyeccion_main")) 
								{
									
										$typeError =  array(
												   		 "NUM_ERROR"=>  9 ,///***Error OBTNER ID Proyeccio REVICION 1  
														 "ERROR" => mysqli_error($conecta1)  
									         			)    ; 


								}else{

										////***Obtenemos  ID Proyeciones  
										$ID_PROYEC = mysqli_fetch_array($IDLAST);
                                                                                    
										/*-----------------------Generamos  Cadena  InsertHistorico  ----------------------------------------*/ 
										$STR_HISTO_PROYEC = sprintf("Insert INTO proyec_historico  SET id_presu=%s ,id_proyec=%s, cve_agente=%s,cve_producto=%s,cve_cliente=%s,nom_prod=%s,nom_cliente=%s,mes=%s,anio=%s,precio=%s,costo=%s,monto=%s,proyeccion=%s ,estatus_proyec=%s,familia=%s",
										GetSQLValueString($IDPRS_DET ,"int"), 
										GetSQLValueString($ID_PROYEC['id'],"int"), 
										GetSQLValueString($NUMAGENT,"int"),
										GetSQLValueString($OBJ->{'cveprod'},"text"),
										GetSQLValueString($OBJ->{'cveclie'},"text"),
										GetSQLValueString($OBJ->{'nom_prod'},"text"),
										GetSQLValueString($OBJ->{'nom_cli'},"text"),
										GetSQLValueString($ProyEnCur['mes_rev1'], "int"),
									    GetSQLValueString($ProyEnCur['year_rev1'],"int"),
										GetSQLValueString($JSONCANTIDAD->{'prerev01'},"text"),
										GetSQLValueString(0,"int"),
										GetSQLValueString($JSONCANTIDAD->{'montorev01'},"int"),
										GetSQLValueString($JSONCANTIDAD->{'ProyREV1'},"int"),
										GetSQLValueString('RV1',"text"),
                                                                                 GetSQLValueString($OBJ->{'familia'},"text"));        
										///*****
										  if(!mysqli_query($conecta1, $STR_HISTO_PROYEC)) 
										{
										
												$typeError =  array(
												   		 "NUM_ERROR"=> 12 ,///***Error Insertar  Proyeccio HISTORICA  REVICION  
														 "ERROR" => mysqli_error($conecta1)  
									         			)    ; 


										}
								 }

						//************************************************************************************************
						}else
						{ //*********Incio  Update    RV1
								
								////******Existe  Proyeccion  Confirmacion 
								$esatus="SI EXISTE WEEEEE :v "; 
								///****Buscamos  ID EN TB  PROYECCIONES  
							     $STR_GET_ID_TB_PROYEC = sprintf("SELECT id from proyec_proyeccion_main where num_agente=%s and cve_prod =%s and cve_cliente=%s and   mes =%s  and anio =%s ", 
							GetSQLValueString($NUMAGENT,"int"),
                                                        GetSQLValueString($OBJ->{'cveprod'},"text"),
                                                        GetSQLValueString($OBJ->{'cveclie'},"text"),
								GetSQLValueString($ProyEnCur['mes_rev1'], "int"),
								GetSQLValueString($ProyEnCur['year_rev1'],"int"));
                                                                
                                                                
							   ///*****Obtenemos ID PROYECCION
							   if(!$qeryserID = mysqli_query($conecta1, $STR_GET_ID_TB_PROYEC)) 
								{
									
										$typeError =  array(
												   		 "NUM_ERROR"=> 13 ,///***Error oBTENER ID proyecion 
														 "ERROR" => mysqli_error($conecta1)  
									         			)    ; 




								}else{
										////***Obtenemos  ID Proyeciones  
										$ID_PROYEC = mysqli_fetch_array($qeryserID);
										
										////**************STRING 	Update  CONFIRMACION ***************************id_prs=%s,*********************************************************************************************************************************
										$STR_UPDATE_PROYEC_CONFIR = sprintf("UPDATE  proyec_proyeccion_main  SET num_agente=%s,cve_prod=%s,cve_cliente=%s,nom_prod=%s,nom_clien=%s,mes=%s,anio=%s,precio=%s,costo=%s,monto=%s,proyeccion=%s where id=%s",
										//GetSQLValueString($OBJ->{'prsid'},"int"),  
										GetSQLValueString($NUMAGENT,"int"),
										GetSQLValueString($OBJ->{'cveprod'},"text"),
										GetSQLValueString($OBJ->{'cveclie'},"text"),
										GetSQLValueString($OBJ->{'nom_prod'},"text"),
										GetSQLValueString($OBJ->{'nom_cli'},"text"),
										GetSQLValueString($ProyEnCur['mes_rev1'], "int"),
							    		GetSQLValueString($ProyEnCur['year_rev1'],"int"),
										GetSQLValueString($JSONCANTIDAD->{'prerev01'},"text"),
										GetSQLValueString(0,"int"),
										GetSQLValueString($JSONCANTIDAD->{'montorev01'},"int"),
										GetSQLValueString($JSONCANTIDAD->{'ProyREV1'},"int"),
										GetSQLValueString($ID_PROYEC['id'],"int"));
										///*****Obtenemos Meses que se Actulizaran hooo  Insertaran
									    if(!mysqli_query($conecta1, $STR_UPDATE_PROYEC_CONFIR)) 
										{
											$typeError =5; ///***Error UPDATE  Proyeccio Confirmacion  
										}

										///****Buscamos  ID EN TB  historico  PROYECCIONES  
										    $STR_GET_ID_TB_HIS_PROYEC = sprintf("SELECT id from proyec_historico where id_proyec=%s ", 
											GetSQLValueString($PROYEID,"int"));
										///*****Obtenemos ID historico proyecciones  PROYECCION
										   if(!$qeryserhisID = mysqli_query($conecta1, $STR_GET_ID_TB_HIS_PROYEC)) 
											{
												
												$typeError =  array(
												   		 "NUM_ERROR"=> 14 ,///***Error oBTENER ID Historico  revi1 
														 "ERROR" => mysqli_error($conecta1)  
									         			)    ; 


											}else{

												////***Obtenemos  ID Proyeciones  
												$ID_HISPROYEC = mysqli_fetch_array($qeryserhisID);
                                                                                           
											/*-----------------------Generamos  Cadena  Update  Historico  ----------------------------------------*/ 
											$STR_HISTO_PROYEC = sprintf("UPDATE proyec_historico  SET id_presu=%s,id_proyec=%s ,cve_agente=%s,cve_producto=%s,cve_cliente=%s,nom_prod=%s,nom_cliente=%s,mes=%s,anio=%s,precio=%s,costo=%s,monto=%s,proyeccion=%s,estatus_proyec=%s where id=%s",
											GetSQLValueString($OBJ->{'prsid'},"int"), 
											GetSQLValueString($PROYEID,"int"), 
											GetSQLValueString($NUMAGENT,"int"),
											GetSQLValueString($OBJ->{'cveprod'},"text"),
											GetSQLValueString($OBJ->{'cveclie'},"text"),
											GetSQLValueString($OBJ->{'nom_prod'},"text"),
											GetSQLValueString($OBJ->{'nom_cli'},"text"),
											GetSQLValueString($ProyEnCur['mes_rev1'], "int"),
								    		GetSQLValueString($ProyEnCur['year_rev1'],"int"),
											GetSQLValueString($JSONCANTIDAD->{'prerev01'},"text"),
											GetSQLValueString(0,"int"),
											GetSQLValueString($JSONCANTIDAD->{'montorev01'},"int"),
											GetSQLValueString($JSONCANTIDAD->{'ProyREV1'},"int"),
											GetSQLValueString('RV1',"text"),
											GetSQLValueString($ID_PROYEC['id'],"int"));
											///*****
											  if(!mysqli_query($conecta1, $STR_HISTO_PROYEC)) 
											{
												
												$typeError =  array(
												   		 "NUM_ERROR"=> 15 ,///***Error Update  Proyeccio HISTORICA  revicion 1  
														 "ERROR" => mysqli_error($conecta1)  
									         			)    ; 

											}

										}

								}
							}////////fin  Update   RV1
							//******************************************************************************************************************************************************************************
							///*****************************************************************************************************************************************************************************
							///*********************Insercion y Update Revicion 2*****RV2***********************************************************
							///*****************************************************************************************************************************************************************************
							///*****************************************************************************************************************************************************************************	
							if(is_null($ProyActorUP['TyAC_rev2'])==true || $Total ==0|| is_null($Total)== true  )
						    {
									///*****No EXISTE  PROYECCION   procedemos a  agregarla
									$esatus="NO EXISTE WEEEEE :v "; 	

										/*
											"ProyCNF":$(idmadeCNF).val(),
										    				"ProyREV1":$(idmadeREV1).val(),
										    				"ProyREV2":$(idmadeREV2).val()
															"ProyCNF":$(idmadeCNF).val(),
										    				"ProyREV1":$(idmadeREV1).val(),
										    				"ProyREV2":$(idmadeREV2).val(), 
										    				"preconfi":$(idmadeREV2).atrr('preconfi'),
										    				"montoconfi":$(idmadeREV2).atrr('montoconfi'),
										    				"prerev01":$(idmadeREV2).atrr('prerev01'),
										    				"montorev01":$(idmadeREV2).atrr('montorev01'), 
										    				"prerev02":$(idmadeREV2).atrr('prerev02'),
										    				"montorev02":$(idmadeREV2).atrr('montorev02')


										*/
									            if($OBJ->{'prsidrv2'} == 0 )
                                                                                    {
                                                                                       
                                                                                        $IDPRS_DET  = 0;//$OBJ->{'prsid'}; 
                                                                                    }else{
                                                                                   
                                                                                        $IDPRS_DET = $OBJ->{'prsidcnf'} ;
                                                                                    }
									//$cantidadConfirmacion  = $JSONCANTIDAD->{'ProyCNF'};
									////**************STRING 	INSERCION CONFIRMACION ************************************************************************************************************************************************************
									$STR_INS_PROYEC_CONFIR = sprintf("Insert INTO proyec_proyeccion_main  SET id_prs=%s,num_agente=%s,cve_prod=%s,cve_cliente=%s,nom_prod=%s,nom_clien=%s,mes=%s,anio=%s,precio=%s,costo=%s,monto=%s,proyeccion=%s,familia=%s,ventahisto=%s",
									GetSQLValueString($IDPRS_DET,"int"),  
									GetSQLValueString($NUMAGENT,"int"),
									GetSQLValueString($OBJ->{'cveprod'},"text"),
									GetSQLValueString($OBJ->{'cveclie'},"text"),
									GetSQLValueString($OBJ->{'nom_prod'},"text"),
									GetSQLValueString($OBJ->{'nom_cli'},"text"),
									GetSQLValueString($ProyEnCur['mes_rev2'], "int"),
								    GetSQLValueString($ProyEnCur['year_rev2'],"int"),
									GetSQLValueString($JSONCANTIDAD->{'prerev02'},"text"),
									GetSQLValueString(0,"int"),
									GetSQLValueString($JSONCANTIDAD->{'montorev02'},"int"),
									GetSQLValueString($JSONCANTIDAD->{'ProyREV2'},"int"),
                                                                        GetSQLValueString($OBJ->{'familia'},"text"),
                                                                        GetSQLValueString($OBJ->{'venta1RV2'},"text")
                                                                                );
									///*****Obtenemos Meses que se Actulizaran hooo  Insertaran
								    if(!mysqli_query($conecta1, $STR_INS_PROYEC_CONFIR)) 
									{
										
										$typeError =  array(
												   		 "NUM_ERROR"=> 16 ,///***Error Insertar  Proyeccio Confirmacion  
														 "ERROR" => mysqli_error($conecta1)  
									         			)    ; 

									}else{ 

										///*****Obtenemos Meses que se Actulizaran hooo  Insertaran
									    if(!$IDLAST = mysqli_query($conecta1, "SELECT MAX(id) AS id FROM proyec_proyeccion_main")) 
										{
											
												$typeError =  array(
												   		 "NUM_ERROR"=> 17 ,///***Error OBTNER ID Proyeccio REVICION 2  
														 "ERROR" => mysqli_error($conecta1)  
									         			)    ; 

										}else{

												////***Obtenemos  ID Proyeciones  
												$ID_PROYEC = mysqli_fetch_array($IDLAST);
                                                                                             
												/*-----------------------Generamos  Cadena  InsertHistorico  ----------------------------------------*/ 
												$STR_HISTO_PROYEC = sprintf("Insert INTO proyec_historico  SET id_presu=%s ,id_proyec=%s, cve_agente=%s,cve_producto=%s,cve_cliente=%s,nom_prod=%s,nom_cliente=%s,mes=%s,anio=%s,precio=%s,costo=%s,monto=%s,proyeccion=%s,estatus_proyec=%s,familia=%s ",
												GetSQLValueString($IDPRS_DET,"int"), 
												GetSQLValueString($ID_PROYEC['id'],"int"), 
												GetSQLValueString($NUMAGENT,"int"),
												GetSQLValueString($OBJ->{'cveprod'},"text"),
												GetSQLValueString($OBJ->{'cveclie'},"text"),
												GetSQLValueString($OBJ->{'nom_prod'},"text"),
												GetSQLValueString($OBJ->{'nom_cli'},"text"),
												GetSQLValueString($ProyEnCur['mes_rev2'], "int"),
											    GetSQLValueString($ProyEnCur['year_rev2'],"int"),
												GetSQLValueString($JSONCANTIDAD->{'prerev02'},"text"),
												GetSQLValueString(0,"int"),
												GetSQLValueString($JSONCANTIDAD->{'montorev02'},"int"),
												GetSQLValueString($JSONCANTIDAD->{'ProyREV2'},"int"),
												GetSQLValueString('RV2',"text"),
												GetSQLValueString($OBJ->{'familia'},"text")        );
												///*****
												  if(!mysqli_query($conecta1, $STR_HISTO_PROYEC)) 
												{
													
													$typeError =  array(
												   		 "NUM_ERROR"=> 18 ,///***Error Insertar  Proyeccio HISTORICA  REVICION  2 
														 "ERROR" => mysqli_error($conecta1)  
									         			)    ; 
												}
										    }
								  }		    

									///**************************************************************************************************
						   }else{
									
									////******Existe  Proyeccion  Confirmacion 
									$esatus="SI EXISTE WEEEEE :v "; 
									///****Buscamos  ID EN TB  PROYECCIONES  
								     $STR_GET_ID_TB_PROYEC = sprintf("SELECT id from proyec_proyeccion_main where num_agente=%s and cve_prod =%s and cve_cliente=%s and   mes =%s  and anio =%s ", 
                                                                        GetSQLValueString($NUMAGENT,"int"),
                                                                        GetSQLValueString($OBJ->{'cveprod'},"text"),
                                                                        GetSQLValueString($OBJ->{'cveclie'},"text"),
									GetSQLValueString($ProyEnCur['mes_rev2'], "int"),
									GetSQLValueString($ProyEnCur['year_rev2'],"int"));
                                                                        
                                                                        
                                                                        
								///*****Obtenemos ID PROYECCION
								   if(!$qeryserID = mysqli_query($conecta1, $STR_GET_ID_TB_PROYEC)) 
									{
										
										$typeError =  array(
												   		 "NUM_ERROR"=> 19 ,///***Error oBTENER ID proyecion 
														 "ERROR" => mysqli_error($conecta1)  
									         			)    ; 

									}else{
											////***Obtenemos  ID Proyeciones  
											$ID_PROYEC = mysqli_fetch_array($qeryserID);
											$PROYEID = $ID_PROYEC['id'];
                                                                                         
											////**************STRING 	Update  CONFIRMACION *********************id_prs=%s,***************************************************************************************************************************************
											$STR_UPDATE_PROYEC_CONFIR = sprintf("UPDATE  proyec_proyeccion_main  SET num_agente=%s,cve_prod=%s,cve_cliente=%s,nom_prod=%s,nom_clien=%s,mes=%s,anio=%s,precio=%s,costo=%s,monto=%s,proyeccion=%s where id=%s",
											///GetSQLValueString($OBJ->{'prsid'},"int"),  
											GetSQLValueString($NUMAGENT,"int"),
											GetSQLValueString($OBJ->{'cveprod'},"text"),
											GetSQLValueString($OBJ->{'cveclie'},"text"),
											GetSQLValueString($OBJ->{'nom_prod'},"text"),
											GetSQLValueString($OBJ->{'nom_cli'},"text"),
											GetSQLValueString($ProyEnCur['mes_rev2'], "int"),
								    		GetSQLValueString($ProyEnCur['year_rev2'],"int"),
											GetSQLValueString($JSONCANTIDAD->{'prerev02'},"text"),
											GetSQLValueString(0,"int"),
											GetSQLValueString($JSONCANTIDAD->{'montorev02'},"int"),
											GetSQLValueString($JSONCANTIDAD->{'ProyREV2'},"int"),
											GetSQLValueString($ID_PROYEC['id'],"int"));
											///*****Obtenemos Meses que se Actulizaran hooo  Insertaran
										    if(!mysqli_query($conecta1, $STR_UPDATE_PROYEC_CONFIR)) 
											{
											
												$typeError =  array(
												   		 "NUM_ERROR"=> 20 ,///***Error UPDATE  Proyeccio Confirmacion  
														 "ERROR" => mysqli_error($conecta1)  
									         			)    ; 
											}

											///****Buscamos  ID EN TB  historico  PROYECCIONES  
											    $STR_GET_ID_TB_HIS_PROYEC = sprintf("SELECT id from proyec_historico where id_proyec=%s ", 
												GetSQLValueString($PROYEID,"int"));
											///*****Obtenemos ID historico proyecciones  PROYECCION
											   if(!$qeryserhisID = mysqli_query($conecta1, $STR_GET_ID_TB_HIS_PROYEC)) 
												{
													
														$typeError =  array(
												   		 "NUM_ERROR"=> 21 ,///***Error oBTENER ID Historico  revi1 
														 "ERROR" => mysqli_error($conecta1)  
									         			)    ; 

												}else{

													////***Obtenemos  ID Proyeciones  
													$ID_HISPROYEC = mysqli_fetch_array($qeryserhisID);
                                                                                                
												/*-----------------------Generamos  Cadena  Update  Historico  -----id_presu=%s,-----------------------------------*/ 
												$STR_HISTO_PROYEC = sprintf("UPDATE proyec_historico  SET id_proyec=%s ,cve_agente=%s,cve_producto=%s,cve_cliente=%s,nom_prod=%s,nom_cliente=%s,mes=%s,anio=%s,precio=%s,costo=%s,monto=%s,proyeccion=%s,estatus_proyec=%s where id=%s",
												///GetSQLValueString($OBJ->{'prsid'},"int"), 
												GetSQLValueString($PROYEID,"int"), 
												GetSQLValueString($NUMAGENT,"int"),
													GetSQLValueString($OBJ->{'cveprod'},"text"),
													GetSQLValueString($OBJ->{'cveclie'},"text"),
													GetSQLValueString($OBJ->{'nom_prod'},"text"),
													GetSQLValueString($OBJ->{'nom_cli'},"text"),
													GetSQLValueString($ProyEnCur['mes_rev2'], "int"),
										    		GetSQLValueString($ProyEnCur['year_rev2'],"int"),
													GetSQLValueString($JSONCANTIDAD->{'prerev02'},"text"),
													GetSQLValueString(0,"int"),
													GetSQLValueString($JSONCANTIDAD->{'montorev02'},"int"),
													GetSQLValueString($JSONCANTIDAD->{'ProyREV2'},"int"),
													GetSQLValueString('RV2',"text"),
													GetSQLValueString($ID_PROYEC['id'],"int"));
												///*****
												  if(!mysqli_query($conecta1, $STR_HISTO_PROYEC)) 
												{
												
													$typeError =  array(
												   		 "NUM_ERROR"=> 22 ,///***Error Update  Proyeccio HISTORICA  revicion 1  
														 "ERROR" => mysqli_error($conecta1)  
									         			)    ; 
												}

											}




							///************************************************************************************************************
									}
								}



/**********************************************************************************************************/
	 		}///LLAVE  DETERMINACION DE  ESTATUS   
  	} /// LlAVE   CIERRE  CONFIGURACION DE  Meses  
	///****************************************************************************
	///*******************Fin Ciclo Principal *********************************
	///****************************************************************************
}




 $GASTrESULTADO =   Array(
 			///"Res001" => $JSONCANTIDAD,  ///Retornamos  Resultado  De INSERCION
          ///  "cadena" =>$CVE_PROD,
             "ERROR"=> $typeError   
     );  
///**Convertimos a  Json  
  $convert_json  =  json_encode($GASTrESULTADO);
  header('Content-type: application/json');
echo  $convert_json ;
?>