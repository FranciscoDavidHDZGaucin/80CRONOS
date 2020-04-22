<?php
///****gast_validador_generador.php 



require_once('header_conta_gastos.php');
require_once('formato_datos.php');
 require_once('funciones.php');
 //mysqli_select_db($conecta1, $database_conecta1);
 require_once('Connections/conecta1.php');
 require_once('conexion_sap/sap.php');
      

$cursor=("CREATE TEMPORARY TABLE IF NOT EXISTS pedidos.gasto_temptb (
    AccountCode VARCHAR(50) NOT NULL
    , credit DECIMAL(12,2) NOT NULL DEFAULT 0.00
    , debit DECIMAL(12,2) NOT NULL DEFAULT 0.00
    , duedate VARCHAR(10) NOT NULL
	, linememo VARCHAR(50) NOT NULL
	, projectcode VARCHAR(10)  NULL
	, reference1 VARCHAR(50) NOT NULL
	, reference2 VARCHAR(50) NOT NULL
	, reference3 VARCHAR(50)  NULL
	, taxdate VARCHAR(10) NOT NULL
) ENGINE=MEMORY;");



?> 

<!--JQuery jquery.min.js" -->
<script src="bower_components/jquery/dist/jquery.min.js"></script>
<script src="script_gastos/fechas_fail_other.js"></script>
<script type="text/javascript">
  
    
</script> 

<div class="container">
<?php  
echo   $date_inicio=filter_input(INPUT_POST, 'fecha_ini' );   ///$_POST['fecha_ini'];
echo '<br>';
echo   $date_final=filter_input(INPUT_POST, 'fecha_fin' );   ///$_POST['fecha_fin'];
echo '<br>';
echo   $empleado=filter_input(INPUT_POST, 'empleado' );   ///$_POST['empleado'];
echo '<br>';
echo   $caracteres=strlen(filter_input(INPUT_POST, 'empleado' ));   ///$_POST['fecha_ini']);
echo '<br>';
echo   $fecha_poliza=filter_input(INPUT_POST, 'fecha3');    ///$_POST['fecha3'];
echo '<br>';
echo   $anio=substr($fecha_poliza,0,4);
echo '<br>';

echo   $mes=substr($fecha_poliza,5,2);
echo '<br>';
echo   $dia=substr($fecha_poliza,8,2);
echo '<br>';
echo   $fecha_formato=$anio.$mes.$dia;



if (strlen(filter_input(INPUT_POST, 'fecha_ini' ))>1){
		$op1="1";
		echo "entro al verdadero";
                echo '<br>';
	}else{
		$op1="0";
		echo "NO entro al verdadero";
                echo '<br>';
	}
		
	if ($empleado!=0){
		$op2="1";
	}else{
		$op2="0";
	}	
	$dato=$op1.$op2;
        
	switch ($dato) {    //consulta donde se obtiene los gastos para convertirlos en poliza
		case '00':   //Todos
					//$query=("select * from poliza where pago>0");
					$consulta_sql=("SELECT poliza.id, poliza.id_gto,poliza.agente, poliza.nom_age, poliza.pago, poliza.subtot_pago, poliza.iva_pago, poliza.tasa_iva, poliza.ieps, poliza.es_gas, poliza.retencion, poliza.rfc_gas, poliza.rfc_gasto, poliza.deducible, agente.formato_cta, agente.usu_centrocto, catalogo.nombre, catalogo.formato_cta as sys_gasto FROM poliza  inner join agente on poliza.agente=agente.usu_consecutivo  inner join catalogo on poliza.id_gto=catalogo.id where poliza.pago>0");
			break;
		case '10':   //Solo Fecha
				$consulta_sql=sprintf("SELECT poliza.id, poliza.id_gto, poliza.agente, poliza.nom_age, poliza.pago, poliza.subtot_pago, poliza.iva_pago, poliza.tasa_iva, poliza.ieps, poliza.es_gas, poliza.retencion,poliza.f_pago, poliza.rfc_gas, poliza.rfc_gasto, poliza.deducible, agente.formato_cta, agente.usu_centrocto, catalogo.nombre, catalogo.formato_cta as sys_gasto FROM poliza  inner join agente on poliza.agente=agente.usu_consecutivo  inner join catalogo on poliza.id_gto=catalogo.id where poliza.pago>0 and poliza.f_pago>=%s and poliza.f_pago<=%s order by poliza.nom_age",
				GetSQLValueString($date_inicio,"date"), 
				GetSQLValueString($date_inicio,"date"));
				//$query = sprintf("select * from poliza where pago>0 and  fecha>=%s and f_pago<=%s order by f_pago desc", 
			break;
		case '01':   //Solo Empleado
				$consulta_sql=sprintf("SELECT poliza.id, poliza.id_gto, poliza.agente, poliza.nom_age, poliza.pago, poliza.subtot_pago, poliza.iva_pago, poliza.tasa_iva, poliza.ieps, poliza.es_gas, poliza.retencion, poliza.rfc_gas,poliza.rfc_gasto, poliza.deducible, agente.formato_cta, agente.usu_centrocto, catalogo.nombre, catalogo.formato_cta as sys_gasto FROM poliza  inner join agente on poliza.agente=agente.usu_consecutivo  inner join catalogo on poliza.id_gto=catalogo.id where poliza.pago>0 and poliza.agente=%s",
				GetSQLValueString($empleado,"int"));
				//$listado=mysqli_query($conecta1,$query) or die(mysqli_error($conecta1));
				//$query = sprintf("select *from poliza where pago>0 and agente=%s order by f_pago desc",
			break;	
		case '11':   //Fecha y empleado
				//$query = sprintf("select * from poliza where pago>0 and agente=%s and f_pago>=%s and f_pago<=%s order by fecha desc", 
				$consulta_sql=sprintf("SELECT poliza.id, poliza.id_gto, poliza.agente, poliza.nom_age, poliza.pago, poliza.subtot_pago, poliza.iva_pago, poliza.tasa_iva, poliza.ieps, poliza.es_gas, poliza.retencion, poliza.rfc_gas, poliza.rfc_gasto, poliza.deducible, agente.formato_cta, agente.usu_centrocto, catalogo.nombre, catalogo.formato_cta as sys_gasto FROM poliza  inner join agente on poliza.agente=agente.usu_consecutivo  inner join catalogo on poliza.id_gto=catalogo.id where poliza.pago>0 and poliza.agente=%s and poliza.f_pago>=%s and poliza.f_pago<=%s",
				GetSQLValueString($empleado,"int"), 
				GetSQLValueString($date_inicio,"date"), 
				GetSQLValueString($date_inicio,"date"));
			break;
	}
        
     echo  "Validacion 00010 :Qery  Ejecutado".$consulta_sql;
     echo '<br>';


if(!$query_agentes=mysqli_query($conecta1,$consulta_sql))
    {
       echo  "Validacion 00012 : Error Al query_agentes   "; 
       echo '<br>';
    }else {
       echo  "Validacion 0012 : Exito  Al query_agentes   ";
       echo '<br>';
    }
     
if(!$ejecutar_consulta=mysqli_query($conecta1,$consulta_sql))
    {
       echo  "Validacion 00013 : Error Al ejecutar_consulta  "; 
       echo '<br>';
    }else {
       echo  "Validacion 0013 : Exito  Al ejecutar_consulta   ";
       echo '<br>';
    }



$Total = mysqli_num_rows ($ejecutar_consulta);	
	






if($Total<>0) {
 echo  "Validacion 0001 : El Total  Entra Validacion  ";
    
    if(!$crear_cursor=mysqli_query($conecta1, $cursor))
    {
       echo  "Validacion 0002 : Error Al Creear  Tabla Temporal   "; 
       echo '<br>';
    }else {
       echo  "Validacion 0002 : Exito  Al Creear  Tabla Temporal   ";
       echo '<br>';
    }        
          
	while ($reg_datos = mysqli_fetch_array($ejecutar_consulta)) {   
		   	
         //Identificar que tipo de gasto es el que se esta consultando
		
        $tipo=1;		
		 if ($reg_datos['es_gas']==1) {
				$tipo=2;
		}
		
		 if ($reg_datos['retencion']>0 and $reg_datos['nombre']=="FLETES" )   {
				$tipo=3;
		}
		$comodin=0;
		$comodin2="";
		$comodinfecha=$fecha_formato;
		
		
		 if ($reg_datos['deducible']==1) {
			 //Gasto se va a la cuenta de NO DEDUCIBLES
				   ///Crear la linea del Agente Credit
                                    echo "Validacion 0003 : Gasto se va a la cuenta de NO DEDUCIBLES   ";  
                                                ///echo "Deducible";	  
							$crear_registros=sprintf("INSERT INTO gasto_temptb (AccountCode, credit, debit, duedate,linememo,projectcode,reference1,reference2,taxdate) VALUES (%s,%s,%s,%s,%s,%s,%s,%s,%s)",						
							GetSQLValueString($reg_datos['formato_cta'],"text"),
							GetSQLValueString($reg_datos['pago'],"double"),
							GetSQLValueString($comodin,"double"),
							GetSQLValueString($comodinfecha,"text"),
							GetSQLValueString($reg_datos['nom_age'],"text"),
							GetSQLValueString($comodin2,"text"),
							GetSQLValueString($reg_datos['nom_age'],"text"),
							GetSQLValueString($reg_datos['nombre'],"text"),
							GetSQLValueString($comodinfecha,"text"));
							 
                                                        if(!$insertarlo=mysqli_query($conecta1,$crear_registros))
                                                        {
                                                           echo  "Validacion 0004 : Error Insercion Gastos Temporales"; 
                                                           echo '<br>';
                                                        }else {
                                                           echo  "Validacion 0004 : Exito  Insercion Gastos  Temporales";
                                                           echo '<br>';
                                                        }   
						   
						   ///
						   
						   $sys_cuenta="_SYS00000000714";
						   $cuenta="NO DEDUCIBLE";
						   ///Crear la linea de NO DEDUCIBLE
						   $crear_registros=sprintf("INSERT INTO gasto_temptb (AccountCode, credit, debit, duedate,linememo,projectcode,reference1,reference2, reference3,taxdate) VALUES (%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)",
							GetSQLValueString($sys_cuenta,"text"),
							GetSQLValueString($comodin,"double"),
							GetSQLValueString($reg_datos['subtot_pago'],"double"),
							GetSQLValueString($comodinfecha,"text"),
							GetSQLValueString($cuenta,"text"),
							GetSQLValueString($reg_datos['usu_centrocto'],"text"),
							GetSQLValueString($reg_datos['nom_age'],"text"),
							GetSQLValueString($cuenta,"text"),
							GetSQLValueString($reg_datos['rfc_gasto'],"text"),
							GetSQLValueString($comodinfecha,"text"));
							
						   
                                                        if(!$insertarlo=mysqli_query($conecta1,$crear_registros))
                                                        {
                                                           echo  "Validacion 0005 : Error Insercion Gastos Temporales"; 
                                                           echo '<br>';
                                                        }else {
                                                           echo  "Validacion 0005 : Exito  Insercion Gastos  Temporales";
                                                           echo '<br>';
                                                        } 
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
							GetSQLValueString($reg_datos['formato_cta'],"text"),
							GetSQLValueString($reg_datos['pago'],"double"),
							GetSQLValueString($comodin,"double"),
							GetSQLValueString($comodinfecha,"text"),
							GetSQLValueString($reg_datos['nom_age'],"text"),
							GetSQLValueString($comodin2,"text"),
							GetSQLValueString($reg_datos['nom_age'],"text"),
							GetSQLValueString($reg_datos['nombre'],"text"),
							GetSQLValueString($comodinfecha,"text"));
							///$insertarlo=mysqli_query($conecta1,$crear_registros) or die (mysqli_error($conecta1));
						   
                                                        if(!$insertarlo=mysqli_query($conecta1,$crear_registros))
                                                        {
                                                           echo  "Validacion 0006 : Error Insercion Gastos Temporales"; 
                                                           echo '<br>';
                                                        }else {
                                                           echo  "Validacion 0006 : Exito  Insercion Gastos  Temporales";
                                                           echo '<br>';
                                                        }
                                                        
						   ///
						   
						   ///Crear la linea del Gasto
						   $crear_registros=sprintf("INSERT INTO gasto_temptb (AccountCode, credit, debit, duedate,linememo,projectcode,reference1,reference2, reference3,taxdate) VALUES (%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)",
							GetSQLValueString($reg_datos['sys_gasto'],"text"),
							GetSQLValueString($comodin,"double"),
							GetSQLValueString($reg_datos['subtot_pago'],"double"),
							GetSQLValueString($comodinfecha,"text"),
							GetSQLValueString($reg_datos['nombre'],"text"),
							GetSQLValueString($reg_datos['usu_centrocto'],"text"),
							GetSQLValueString($reg_datos['nom_age'],"text"),
							GetSQLValueString($reg_datos['nombre'],"text"),
							GetSQLValueString($reg_datos['rfc_gasto'],"text"),
							GetSQLValueString($comodinfecha,"text"));
							//$insertarlo=mysqli_query($conecta1,$crear_registros) or die (mysqli_error($conecta1));
						   ///
						   if(!$insertarlo=mysqli_query($conecta1,$crear_registros))
                                                        {
                                                           echo  "Validacion 0007 : Error Insercion Gastos Temporales"; 
                                                           echo '<br>';
                                                        }else {
                                                           echo  "Validacion 0007 : Exito  Insercion Gastos  Temporales";
                                                           echo '<br>';
                                                        }
						   
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
							
							$crear_registros=sprintf("INSERT INTO gasto_temptb(AccountCode, credit, debit, duedate,linememo,projectcode,reference1,reference2, reference3, taxdate) VALUES (%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)",
							GetSQLValueString($cuenta_iva,"text"),
							GetSQLValueString($comodin,"double"),
							GetSQLValueString($reg_datos['iva_pago'],"double"),
							GetSQLValueString($comodinfecha,"text"),
							GetSQLValueString($nombre_iva,"text"),
							GetSQLValueString($comodin2,"text"),
							GetSQLValueString($reg_datos['nom_age'],"text"),
							GetSQLValueString($reg_datos['nombre'],"text"),
							GetSQLValueString($reg_datos['rfc_gasto'],"text"),
							GetSQLValueString($comodinfecha,"text"));
							///$insertarlo=mysqli_query($conecta1,$crear_registros) or die (mysqli_error($conecta1));
							if(!$insertarlo=mysqli_query($conecta1,$crear_registros))
                                                        {
                                                           echo  "Validacion 0008 : Error Insercion Gastos Temporales"; 
                                                           echo '<br>';
                                                        }else {
                                                           echo  "Validacion 0008 : Exito  Insercion Gastos  Temporales";
                                                           echo '<br>';
                                                        }	   
						   ///
				  
				  break;    
				case 2:    //Consumo de Gasolina
				
						 //echo "Gasolina";
						   $comodin=0;
						   $comodin2="";
						   $comodinfecha=$fecha_formato;
						   $sub=$reg_datos['subtot_pago'];
						   $ieps=$reg_datos['ieps'];
						   $combustible_sinieps=($sub-$ieps);
						   
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
							///$insertarlo=mysqli_query($conecta1,$crear_registros) or die (mysqli_error($conecta1));
						  
                                                        if(!$insertarlo=mysqli_query($conecta1,$crear_registros))
                                                        {
                                                           echo  "Validacion 0009 : Error Insercion Gastos Temporales"; 
                                                           echo '<br>';
                                                        }else {
                                                           echo  "Validacion 0009 : Exito  Insercion Gastos  Temporales";
                                                           echo '<br>';
                                                        }
                                                        
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
							///$insertarlo=mysqli_query($conecta1,$crear_registros) or die (mysqli_error($conecta1));
							if(!$insertarlo=mysqli_query($conecta1,$crear_registros))
                                                        {
                                                           echo  "Validacion 0010 : Error Insercion Gastos Temporales"; 
                                                           echo '<br>';
                                                        }else {
                                                           echo  "Validacion 0010 : Exito  Insercion Gastos  Temporales";
                                                           echo '<br>';
                                                        }	   
						   ///
							
							
						   
						   ///Agregar cuenta IEPS
							$formato_ctaieps="_SYS00000000989";
							$nombre_ieps="IEPS GASOLINA";
							$crear_registros=sprintf("INSERT INTO gasto_temptb(AccountCode, credit, debit, duedate,linememo,projectcode,reference1, reference2, reference3, taxdate) VALUES (%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)",
							GetSQLValueString($formato_ctaieps,"text"),
							GetSQLValueString($comodin,"double"),
							GetSQLValueString($ieps,"double"),
							GetSQLValueString($comodinfecha,"text"),
							GetSQLValueString($nombre_ieps,"text"),
							GetSQLValueString($reg_datos['usu_centrocto'],"text"),
							GetSQLValueString($reg_datos['nom_age'],"text"),
							GetSQLValueString($reg_datos['nombre'],"text"),
							GetSQLValueString($reg_datos['rfc_gas'],"text"),
							GetSQLValueString($comodinfecha,"text"));
							///$insertarlo=mysqli_query($conecta1,$crear_registros) or die (mysqli_error($conecta1));
							if(!$insertarlo=mysqli_query($conecta1,$crear_registros))
                                                        {
                                                           echo  "Validacion 0011 : Error Insercion Gastos Temporales"; 
                                                           echo '<br>';
                                                        }else {
                                                           echo  "Validacion 0011 : Exito  Insercion Gastos  Temporales";
                                                           echo '<br>';
                                                        }	   
						   /// 	
							
							///Crear la linea del Gasto
						   $crear_registros=sprintf("INSERT INTO gasto_temptb(AccountCode, credit, debit, duedate,linememo,projectcode,reference1, reference2, reference3, taxdate) VALUES (%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)",
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
                                                    ///	$insertarlo=mysqli_query($conecta1,$crear_registros) or die (mysqli_error($conecta1));
						   /// 
						   if(!$insertarlo=mysqli_query($conecta1,$crear_registros))
                                                        {
                                                           echo  "Validacion 0012 : Error Insercion Gastos Temporales"; 
                                                           echo '<br>';
                                                        }else {
                                                           echo  "Validacion 0012 : Exito  Insercion Gastos  Temporales";
                                                           echo '<br>';
                                                        }
											
				  break;
				case 3:   //Flete
						//Retencion   SYS00000000666	
						//echo "Con Retencion";
						   $comodin=0;
						   $comodin2="";
						   $comodinfecha=$fecha_formato;
						   $pago=$reg_datos['pago'];
						   $retencion=$reg_datos['retencion'];
						   $pago_retencion=($pago-$retencion);
						   
						  // echo $retencion."<br>" ;
						  // echo $pago."<br>" ;
						  // echo $pago_retencion."<br>" ;
						  // echo $comodin."<br>" ;
						   //echo $combustible_sinieps;
						   ///Crear la linea del Agente Credit
						  
						 // echo "agente" ;
							$crear_registros=sprintf("INSERT INTO gasto_temptb(AccountCode, credit, debit, duedate,linememo,projectcode,reference1, reference2,taxdate) VALUES (%s,%s,%s,%s,%s,%s,%s,%s,%s)",
							GetSQLValueString($reg_datos['formato_cta'],"text"),
							GetSQLValueString($pago_retencion,"double"),
							GetSQLValueString($comodin,"double"),
							GetSQLValueString($comodinfecha,"text"),
							GetSQLValueString($reg_datos['nom_age'],"text"),
							GetSQLValueString($comodin2,"text"),
							GetSQLValueString($reg_datos['nom_age'],"text"),
							GetSQLValueString($reg_datos['nombre'],"text"),
							GetSQLValueString($comodinfecha,"text"));
							///$insertarlo=mysqli_query($conecta1,$crear_registros) or die (mysqli_error($conecta1));
						   
						   ///
                                                        if(!$insertarlo=mysqli_query($conecta1,$crear_registros))
                                                        {
                                                           echo  "Validacion 0013 : Error Insercion Gastos Temporales"; 
                                                           echo '<br>';
                                                        }else {
                                                           echo  "Validacion 0013 : Exito  Insercion Gastos  Temporales";
                                                           echo '<br>';
                                                        }
						
						   
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
							
						//	echo "iva" ;
							$crear_registros=sprintf("INSERT INTO gasto_temptb(AccountCode, credit, debit, duedate,linememo,projectcode,reference1, reference2,taxdate) VALUES (%s,%s,%s,%s,%s,%s,%s,%s,%s)",
							GetSQLValueString($cuenta_iva,"text"),
							GetSQLValueString($comodin,"double"),
							GetSQLValueString($reg_datos['iva_pago'],"double"),
							GetSQLValueString($comodinfecha,"text"),
							GetSQLValueString($nombre_iva,"text"),
							GetSQLValueString($comodin2,"text"),
							GetSQLValueString($reg_datos['nom_age'],"text"),
							GetSQLValueString($reg_datos['nombre'],"text"),
							GetSQLValueString($comodinfecha,"text"));
							///$insertarlo=mysqli_query($conecta1,$crear_registros) or die (mysqli_error($conecta1));
							if(!$insertarlo=mysqli_query($conecta1,$crear_registros))
                                                        {
                                                           echo  "Validacion 00014 : Error Insercion Gastos Temporales"; 
                                                           echo '<br>';
                                                        }else {
                                                           echo  "Validacion 0014 : Exito  Insercion Gastos  Temporales";
                                                           echo '<br>';
                                                        }	   
						   ///
							
						  
						  
						//   echo "retencion" ;
						   ///Agregar cuenta Retencion
							$formato_ctaieps="_SYS00000000284";
							$nombre_ieps="IVA RETENIDO POR TRANSPORTE";
							$crear_registros=sprintf("INSERT INTO gasto_temptb(AccountCode, credit, debit, duedate,linememo,projectcode,reference1,reference2,taxdate) VALUES (%s,%s,%s,%s,%s,%s,%s,%s,%s)",
							GetSQLValueString($formato_ctaieps,"text"),
							GetSQLValueString($retencion,"double"),
							GetSQLValueString($comodin,"double"),
							GetSQLValueString($comodinfecha,"text"),
							GetSQLValueString($nombre_ieps,"text"),
							GetSQLValueString($comodin2,"text"),
							GetSQLValueString($reg_datos['nom_age'],"text"),
							GetSQLValueString($reg_datos['nombre'],"text"),
							GetSQLValueString($comodinfecha,"text"));
						///	$insertarlo=mysqli_query($conecta1,$crear_registros) or die (mysqli_error($conecta1));
						if(!$insertarlo=mysqli_query($conecta1,$crear_registros))
                                                        {
                                                           echo  "Validacion 0015 : Error Insercion Gastos Temporales"; 
                                                           echo '<br>';
                                                        }else {
                                                           echo  "Validacion 0015 : Exito  Insercion Gastos  Temporales";
                                                           echo '<br>';
                                                        }		   
						   /// 	
							
							
							//echo "Gasto" ;
							///Crear la linea del Gasto
						   $crear_registros=sprintf("INSERT INTO gasto_temptb(AccountCode, credit, debit, duedate,linememo,projectcode,reference1, reference2,taxdate) VALUES (%s,%s,%s,%s,%s,%s,%s,%s,%s)",
							GetSQLValueString($reg_datos['sys_gasto'],"text"),
							GetSQLValueString($comodin,"double"),
							GetSQLValueString($reg_datos['subtot_pago'],"double"),
							GetSQLValueString($comodinfecha,"text"),
							GetSQLValueString($reg_datos['nombre'],"text"),
							GetSQLValueString($reg_datos['usu_centrocto'],"text"),
							GetSQLValueString($reg_datos['nom_age'],"text"),
							GetSQLValueString($reg_datos['nombre'],"text"),
							GetSQLValueString($comodinfecha,"text"));
						///	$insertarlo=mysqli_query($conecta1,$crear_registros) or die (mysqli_error($conecta1));
						   /// 
						if(!$insertarlo=mysqli_query($conecta1,$crear_registros))
                                                        {
                                                           echo  "Validacion 0016 : Error Insercion Gastos Temporales"; 
                                                           echo '<br>';
                                                        }else {
                                                           echo  "Validacion 0016 : Exito  Insercion Gastos  Temporales";
                                                           echo '<br>';
                                                        }
				  break;
			
			  }           
				   
		}	
		   
		   
		   
	} 

}

$consultar=("SELECT * from gasto_temptb");
///$ejecutar_consulta2=mysqli_query($conecta1,$consultar) or die (mysqli_error($conecta1));



if(!$ejecutar_consulta2=mysqli_query($conecta1,$consultar))
{
   echo  "Validacion 0017 :Error  Obtener   Gastos  Temporales  "; 
   echo '<br>';
}else {
   echo  "Validacion 0017 : Exito Obtner  Gastos  Temporales";
   echo '<br>';
}

$Total2 =  mysqli_num_rows ($ejecutar_consulta2);

   echo  "Validacion 0018 : Total Elemnots  Temporales".$Total2;
   echo '<br>';                                                     





?> 
    
    
    
    
    
</div><!-- /.container -->
      
 <?php require_once('foot.php');?>     