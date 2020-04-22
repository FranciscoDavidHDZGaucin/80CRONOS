<?php
/*
******* INFORMACION ARCHIVO ***************** 
	Nombre  Archivo : .php
 	Fecha  Creacion : 21/12/2017
	Descripcion  : 
 *               Archivo para 
 *                  Generar  Tabla Dinamica  para La Generacion de Gastos 
 *                  Dado a las  validaciones  requeridas  se prosigue a
 *                  elvorar  una nueva tabla   Dianmica la cual SE  AJUSTE 
 *                  A LAS REQUISICIONES  DE HOY  A
 * 
 *                  Nota de Desarrollo 
 *                          Documentamos  el codifo  con EL COMENTARIO  ModificaGast
	Modificado  Fecha  :

 *  
 */    
/////****gast_gentbtemp.php  
require_once('../Connections/conecta1.php');
require_once('../formato_datos.php');
////*************************************************************************************
$cursor=("CREATE TEMPORARY TABLE IF NOT EXISTS pedidos.gasto_temptb (
    cuenta_contable INT NOT NULL
    , nombre_cuenta VARCHAR(10)  NULL
    , cc INT NOT NULL  
    , debit DECIMAL(12,2) NOT NULL DEFAULT 0.00
    , credit DECIMAL(12,2) NOT NULL DEFAULT 0.00
) ENGINE=MEMORY;");

///**********************************************************************
$date_inicio= $_SESSION['fecha_start'];
$empleado=$_SESSION['empleado'];
////******************************************
        if (strlen($date_inicio)>1){
		$op1="1";
		//echo "entro al verdadero";
	}else{
		$op1="0";
		//echo "NO entro al verdadero";
	}
		
	if ($empleado!=0){
		$op2="1";
	}else{
		$op2="0";
	}	
	$dato=$op1.$op2;
////*********************************************************************************
      switch ($dato) {
		case '00':   //Todos
					$query=("SELECT * from pedidos.poliza  group by agente");
			break;
		case '10':   //Solo Fecha
				$query = sprintf("SELECT * from pedidos.poliza  where poliza.f_pago =%s   group by agente", 
				GetSQLValueString($date_inicio,"date"));
				
				
			break;
		case '01':   //Solo Empleado
				$query = sprintf("SELECT * from pedidos.poliza where poliza.agente=%s  group by agente",
				GetSQLValueString($empleado,"int"));
				
			break;	
		case '11':   //Fecha y empleado
				$query = sprintf("SELECT * from pedidos.poliza   where poliza.agente=%s and poliza.f_pago =%s   group by agente", 
				GetSQLValueString($empleado,"int"), 
				GetSQLValueString($date_inicio,"date")); 			
				
			break;
	} 
////************************************************************************************        
$query_agentes=mysqli_query($conecta1,$consulta_sql) or die (mysqli_error($conecta1));	
$ejecutar_consulta=mysqli_query($conecta1,$consulta_sql) or die (mysqli_error($conecta1));
$Total = mysqli_num_rows ($ejecutar_consulta);	
////******************************************************************************	
if($Total<>0) {

     $crear_cursor=mysqli_query($conecta1, $cursor) or die (mysqli_error($conecta1));
    /////**** Ciclo  While 001 
    while ($reg_datos = mysqli_fetch_array($ejecutar_consulta)) {   
    
             //Identificar que tipo de gasto es el que se esta consultando
             $tipo=1;
             ////****
             if ($reg_datos['id_gto']==99) { ///***Es Gasolina     //// ModificaGast  --No sE TOMA  REGISTRO USAMOS  CAPO ID--   if ($reg_datos['es_gas']==1) {
				$tipo=2;
             }
             ////***************************************************
                $comodin=0;
		$comodin2="";
		
             ////***************************************************
                if ($reg_datos['id_gto']==106) {  ///***Es No DEDUCIBLE //// ModificaGast  --No sE TOMA  REGISTRO USAMOS  CAPO ID--  if ($reg_datos['es_gas']==1) { 
                             //Gasto se va a la cuenta de NO DEDUCIBLES
				   ///Crear la linea del Agente Credit
							///echo "Deducible";	  
                                                   
                                                           $crear_registros=sprintf("INSERT INTO gasto_temptb (AccountCode, credit, debit, duedate,linememo,projectcode,reference1,reference2,taxdate) VALUES (%s,%s,%s,%s,%s,%s,%s,%s,%s)",						
                                                         
                                                            GetSQLValueString($reg_datos['cuenta_sys'],"text"),
                                                            GetSQLValueString($reg_datos['pago'],"double"),
                                                            GetSQLValueString($comodin,"double"),
                                                            GetSQLValueString($comodinfecha,"text"),
                                                            GetSQLValueString($reg_datos['nom_age'],"text"),
                                                            GetSQLValueString($comodin2,"text"),
                                                            GetSQLValueString($reg_datos['nom_age'],"text"),
                                                            GetSQLValueString($reg_datos['nom_gto'],"text"),
                                                            GetSQLValueString($comodinfecha,"text"));
                                                            $insertarlo=mysqli_query($conecta1,$crear_registros) or die (mysqli_error($conecta1));
                                                      
                                                        $sys_cuenta="_SYS00000000714";
						   $cuenta="NO DEDUCIBLE";
						   ///Crear la linea de NO DEDUCIBLE
						   $crear_registros=sprintf("INSERT INTO gasto_temptb (AccountCode, credit, debit, duedate,linememo,projectcode,reference1,reference2, reference3,taxdate) VALUES (%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)",
							GetSQLValueString($reg_datos['cuenta_sys'],"text"),
							GetSQLValueString($comodin,"double"),
							GetSQLValueString($reg_datos['subtot_pago'],"double"),
							GetSQLValueString($comodinfecha,"text"),
							GetSQLValueString($cuenta,"text"),
							GetSQLValueString($reg_datos['cc'],"text"),
							GetSQLValueString($reg_datos['nom_age'],"text"),
							GetSQLValueString($cuenta,"text"),
							GetSQLValueString($reg_datos['rfc_gasto'],"text"),
							GetSQLValueString($comodinfecha,"text"));
							$insertarlo=mysqli_query($conecta1,$crear_registros) or die (mysqli_error($conecta1));
						   ///
                                                            
                                                            
                                                            
                                                            
                }else{
                   switch ($tipo) {
				
			 
				case 1:    //Gasto Normal
						   $comodin=0;
						   $comodin2="";
						   $comodinfecha=$fecha_formato;
						  
						  // 	echo $comodinfecha;
						 // echo $reg_datos['sys_gasto']. "<br>";
						 // echo $reg_datos['retencion'];
						 // echo "Gasto";	   
						  ///Crear la linea del Agente Credit
								  
							$crear_registros=sprintf("INSERT INTO gasto_temptb (AccountCode, credit, debit, duedate,linememo,projectcode,reference1,reference2,taxdate) VALUES (%s,%s,%s,%s,%s,%s,%s,%s,%s)",						
							GetSQLValueString($reg_datos['cuenta_sys'],"text"),
							GetSQLValueString($reg_datos['pago'],"double"),
							GetSQLValueString($comodin,"double"),
							GetSQLValueString($comodinfecha,"text"),
							GetSQLValueString($reg_datos['nom_age'],"text"),
							GetSQLValueString($comodin2,"text"),
							GetSQLValueString($reg_datos['nom_age'],"text"),
							GetSQLValueString($reg_datos['nom_gto'],"text"),
							GetSQLValueString($comodinfecha,"text"));
							$insertarlo=mysqli_query($conecta1,$crear_registros) or die (mysqli_error($conecta1));
						   
						   ///
						   
						   ///Crear la linea del Gasto
						   $crear_registros=sprintf("INSERT INTO gasto_temptb (AccountCode, credit, debit, duedate,linememo,projectcode,reference1,reference2, reference3,taxdate) VALUES (%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)",
							GetSQLValueString($reg_datos['cuenta_sys'],"text"),
							GetSQLValueString($comodin,"double"),
							GetSQLValueString($reg_datos['subtot_pago'],"double"),
							GetSQLValueString($comodinfecha,"text"),
							GetSQLValueString($reg_datos['nom_gto'],"text"),
							GetSQLValueString($reg_datos['cc'],"text"),
							GetSQLValueString($reg_datos['nom_age'],"text"),
							GetSQLValueString($reg_datos['nom_gto'],"text"),
							GetSQLValueString($reg_datos['rfc_gasto'],"text"),
							GetSQLValueString($comodinfecha,"text"));
							$insertarlo=mysqli_query($conecta1,$crear_registros) or die (mysqli_error($conecta1));
						   ///
						   
						   
						   ///Crear la linea del IVA 
							  ///conocer la tasa iva
						   switch ($reg_datos['tasa_iva']) {
							case 0:
							  $cuenta_iva="_SYS00000000135";
							  $nombre_iva="IVA ACREDITABLE 0%";
							  break;    
							
							case 16:
							  $cuenta_iva="_SYS00000001051";
							  $nombre_iva="IVA ACREDITABLE 16%";
							  break;
							}
							
							$crear_registros=sprintf("INSERT INTO gasto_temptb(AccountCode, credit, debit, duedate,linememo,projectcode,reference1,reference2, reference3, taxdate) VALUES (%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)",
							GetSQLValueString($cuenta_iva,"text"),
							GetSQLValueString($comodin,"double"),
							GetSQLValueString($reg_datos['iva_pago'],"double"),
							GetSQLValueString($comodinfecha,"text"),
							GetSQLValueString($nombre_iva,"text"),
							GetSQLValueString($comodin2,"text"),
							GetSQLValueString($reg_datos['nom_age'],"text"),
							GetSQLValueString($reg_datos['nom_gto'],"text"),
							GetSQLValueString($reg_datos['rfc_gasto'],"text"),
							GetSQLValueString($comodinfecha,"text"));
							$insertarlo=mysqli_query($conecta1,$crear_registros) or die (mysqli_error($conecta1));
								   
						   ///
				  
				  break;    
				case 2:    ///Consumo de Gasolina
				
						 //echo "Gasolina";
						   $comodin=0;
						   $comodin2="";
						   $comodinfecha=$fecha_formato;
						   $subdivIva=$reg_datos['subtot_pago']/1.16;
						   $ieps_sinformato =$reg_datos['subtot_pago']- number_format($subdivIva, 2, '.', '');     
                                                   
                                                   $IEPS_FORMATO = number_format($ieps_sinformato, 2, '.', '');   
						   
						   //echo $sub."<br>" ;
						   //echo $combustible_sinieps;
						   ///Crear la linea del Agente Credit
						  
						  
							$crear_registros=sprintf("INSERT INTO gasto_temptb(AccountCode, credit, debit, duedate,linememo,projectcode,reference1, reference2, taxdate) VALUES (%s,%s,%s,%s,%s,%s,%s,%s,%s)",
							GetSQLValueString($reg_datos['formato_cta'],"text"),
							GetSQLValueString($reg_datos['pago'],"double"),
							GetSQLValueString($comodin,"double"),
							GetSQLValueString($comodinfecha,"text"),
							GetSQLValueString($reg_datos['nom_age'],"text"),
							GetSQLValueString($comodin2,"text"),
							GetSQLValueString($reg_datos['nom_age'],"text"),
							GetSQLValueString($reg_datos['nombre'],"text"),
							GetSQLValueString($comodinfecha,"text"));
							$insertarlo=mysqli_query($conecta1,$crear_registros) or die (mysqli_error($conecta1));
						   
						   ///
						
						   
						   ///Crear la linea del IVA 
							  ///conocer la tasa iva
						   switch ($reg_datos['tasa_iva']) {
							case 0:
							  $cuenta_iva="_SYS00000000135";
							  $nombre_iva="IVA ACREDITABLE 0%";
							  break;    
							case 11:
							  $cuenta_iva="_SYS00000001062";
							  $nombre_iva="IVA ACREDITABLE 11%";
							  break;
							case 16:
							  $cuenta_iva="_SYS00000001051";
							  $nombre_iva="IVA ACREDITABLE 16%";
							  break;
							}
							
							$crear_registros=sprintf("INSERT INTO gasto_temptb(AccountCode, credit, debit, duedate,linememo,projectcode,reference1, reference2, reference3, taxdate) VALUES (%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)",
							GetSQLValueString($cuenta_iva,"text"),
							GetSQLValueString($comodin,"double"),
							GetSQLValueString($reg_datos['iva_pago'],"double"),
							GetSQLValueString($comodinfecha,"text"),
							GetSQLValueString($nombre_iva,"text"),
							GetSQLValueString($comodin2,"text"),
							GetSQLValueString($reg_datos['nom_age'],"text"),
							GetSQLValueString($reg_datos['nombre'],"text"),
							GetSQLValueString($reg_datos['rfc_gas'],"text"),
							GetSQLValueString($comodinfecha,"text"));
							$insertarlo=mysqli_query($conecta1,$crear_registros) or die (mysqli_error($conecta1));
								   
						   ///
							
							
						   
						   ///Agregar cuenta IEPS
							$formato_ctaieps="_SYS00000000989";
							$nombre_ieps="IEPS GASOLINA";
							$crear_registros=sprintf("INSERT INTO gasto_temptb(AccountCode, credit, debit, duedate,linememo,projectcode,reference1, reference2, reference3, taxdate) VALUES (%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)",
							GetSQLValueString($formato_ctaieps,"text"),
							GetSQLValueString($comodin,"double"),
							GetSQLValueString($IEPS_FORMATO,"double"),
							GetSQLValueString($comodinfecha,"text"),
							GetSQLValueString($nombre_ieps,"text"),
							GetSQLValueString($reg_datos['usu_centrocto'],"text"),
							GetSQLValueString($reg_datos['nom_age'],"text"),
							GetSQLValueString($reg_datos['nombre'],"text"),
							GetSQLValueString($reg_datos['rfc_gas'],"text"),
							GetSQLValueString($comodinfecha,"text"));
							$insertarlo=mysqli_query($conecta1,$crear_registros) or die (mysqli_error($conecta1));
								   
						   /// 	
							
							///Crear la linea del Gasto
                   /*  $crear_registros=sprintf("INSERT INTO gasto_temptb(AccountCode, credit, debit, duedate,linememo,projectcode,reference1, reference2, reference3, taxdate) VALUES (%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)",
							GetSQLValueString($reg_datos['sys_gasto'],"text"),
							GetSQLValueString($comodin,"double"),
							GetSQLValueString($combustible_sinieps,"double"),
							GetSQLValueString($comodinfecha,"text"),
							GetSQLValueString($reg_datos['nombre'],"text"),
							GetSQLValueString($reg_datos['usu_centrocto'],"text"),
							GetSQLValueString($reg_datos['nom_age'],"text"),
							GetSQLValueString($reg_datos['nombre'],"text"),
							GetSQLValueString($reg_datos['rfc_gas'],"text"),
							GetSQLValueString($comodinfecha,"text"));
							$insertarlo=mysqli_query($conecta1,$crear_registros) or die (mysqli_error($conecta1));*/
						   /// 
						   
											
				  break;
			
			  }           
				   
		
                   
                    
                    
                ////****FIn Else    
                }
    /////***Fin Ciclo While 001     
    }
    
    
    
    
    
    
    
////***Fin CONDICION  Total    
}