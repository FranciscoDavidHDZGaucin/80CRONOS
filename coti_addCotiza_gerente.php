<?php
////coti_addCotiza_gerente.php 
/*
********	 INFORMACION ARCHIVO ***************** 
	Nombre  Archivo : coti_addCotiza_gerente.php  
 	Fecha  Creacion :  02/08/2017
	Descripcion  : 
 *              Escrip  Diseñado  para  generar Una  Nueva Cotizacion 
 *      Modificaciones :
 *                   24/07/2017    Utilizaos  $TyVisor  para  poder  controlar
 *                                 el tipo de desplegado de  agregado  de  cotizaciones
 *                             Entiendase  que   $TyVisor => 0 Lo   Utilizamos  para  que el Modulo FUncionen para agregar una  Cotizacion Nueva (De Forma  Normal)
 *                              Entiendase  que   $TyVisor => 1 Lo   Utilizamos  para  Habilitar la Opcion de Modificaion  Si la COtizacion FUe Regresada
 *                              Entiendase  que   $TyVisor => 2 Lo   Utilizamos  solo  como un visor.
 *     
 *          coti_addCotiza_gerente.php?TyPg=0                                 
 *  
 * 
  */
///coti_addCotiza_gerente.php?TyPg=0
////**Inicio De Session 
	session_start();
///****Cabecera Cronos
require_once('header_gerentes.php');
///*****Formato de  Datos          
require_once('conexion_sap/sap.php');
///mssql_select_db("AGROVERSA");
//mysqli_set_charset($conecta1, 'utf8');
 require_once('formato_datos.php');   //para evitar poner la funcion de conversion de tipos de datos
require_once('Connections/conecta1.php'); 
////****Obtenemos el estado  de la Modificacion 
$TyVisor = filter_input(INPUT_GET, 'TyPg');

$conexion =$conecata1;
 	$string_prod=("SELECT * FROM plataformaproductosl1 WHERE Currency='MXP' ORDER BY ItemName");
       $tabla = mssql_query($string_prod);

      //Estructura del Folio    n_agente+anio+ultimo_folio
 function folio_Cotizacion($agente ,$conexion){
  

   $anioactual = date("Y");
   ///Obtener la cantidad de pedidos que ha generado el agente en el aÃ±o actual
   $productos_string=sprintf("SELECT max(con_num) as mayor FROM pedidos.coti_folio_cotizaciones  where  num_agente =%s  and  anio=%s",
                     GetSQLValueString($agente, "int"),
                     GetSQLValueString($anioactual, "int"));
  ///***Realizamos la  Consulta
  $qery_folio=  mysqli_query($conecta1,$productos_string) ; 
  

  ///***Realizamos el  Fetch 
  $fecth_folio = mysqli_fetch_array($qery_folio); 
  ///
   $aleatorio= $fecth_folio['mayor'] ;

   $foliogenerado=$agente.$anioactual.$aleatorio;   //Agente + aÃ±o + nÃºmero aleatorio
     if(!$qery_folio){ $foliogenerado = 0000000;  }
   
   return $foliogenerado ;
    
 }
 
 
 function  getcveagenteByGerente($cve_gerente,$CONEXION)
 {    
      ///**Areglo  cve agentes 
        $AregloCveAgente =Array();
     ///***+Realizamos  Consulta  para Obtner  las  claves de los agentes  en funcion del Gerente
      $strycveagente=sprintf("select cve_age  from  pedidos.relacion_gerentes  where   cve_gte =%s",
             GetSQLValueString($cve_gerente, "int"));
     $quercveget = mysqli_query($CONEXION, $strycveagente);
     if(!$quercveget){
         
       $AregloCveAgente = "ERROR";  
     }
     
        while ($row = mysqli_fetch_array($quercveget)) {
               ///**Agregamos  el cve_agente 
               array_push($AregloCveAgente, $row['cve_age']); 
           } 
           return $AregloCveAgente;
 }
 
  ////***Funcion para Obtener los  clientes Respecto  al Gerente 
 function  GetArrClientesByGerentes($cve_gerente ,$CONEXION)
 {  
 
     //***Areglo FINAL  CONTODOS  LOS  CLIENTES
        $CLIENALL =  Array();
    
    
       $AregloCveAgente=  getcveagenteByGerente($cve_gerente,$CONEXION);

     ///***Ciclo para  Obtener  los Cliente
     foreach ($AregloCveAgente as  $cve_agente)
     {
         ////****Consulta   para obtener los  clientes
            $querycliente=sprintf("SELECT * FROM clientes_cronos WHERE SlpCode=%s OR U_agente2=%s",
             GetSQLValueString($cve_agente, "int"),
             GetSQLValueString($cve_agente, "int"));
           $cliente = mssql_query($querycliente);
           if(!$cliente)
           {
              $CLIENALL ="ERROR"; 
           }else {
                    /////****Ciclo para Agreegar  los  clientes   mssql_fetch_array
                    while ($row = mssql_fetch_array($cliente)) {
                        ////**Areglo base para almacenar  a  los  clientes  
                        $ClI =  array("CardCode"=>$row['CardCode'] ,"CardName"=>$row['CardName'] );  
                        ///**Agregamos  el cliente  al Areglo final
                        array_push($CLIENALL, $ClI); 
                    }
           }
     }    
     
    ////Retornamos   todos  los clientes  
    return  $CLIENALL ;
 }                     
////************************************************************
 
 
 ///////***
  $anioactual = date("Y");
   ///Obtener la cantidad de pedidos que ha generado el agente en el aÃ±o actual
   $productos_string=sprintf("SELECT count(con_num)  as mayor FROM pedidos.coti_folio_cotizaciones  where  num_agente =%s  and  anio=%s",
                     GetSQLValueString($_SESSION["usuario_rol"], "int"),
                     GetSQLValueString($anioactual, "int"));
  ///***Realizamos la  Consulta
  $qery_folio=  mysqli_query($conecta1,$productos_string) ; 
  
////*Validacion para Una Nueva  Cotizacion 
if($TyVisor==0)
{    
  ///***Realizamos el  Fetch 
  $fecth_folio = mysqli_fetch_array($qery_folio); 
  ///
 $aleatorio= $fecth_folio['mayor']+1 ; 
 
 if(!$qery_folio){ $foliogenerado = 0000000;  }else {     $foliogenerado= $_SESSION["usuario_rol"].$anioactual.$aleatorio;    }
}
////*Validacion para Una Nueva  Cotizacion 
if($TyVisor==1||$TyVisor==2)
{    
$foliogenerado = filter_input(INPUT_POST, 'FOLIO');
///**Obtenemos el N# del  Agente 
$idagente =  $_SESSION["usuario_rol"];
$string_get_info = "SELECT com_agent FROM coti_encabeca_cotizacion  where  folio=".$foliogenerado." and   cve_gerente =".$idagente  ;
        $qery_info = mysqli_query($conecta1, $string_get_info);
        
 $comentCoti = mysqli_fetch_array($qery_info);       

    
    
} 



 
///********************************************************************
$idagente =$_SESSION["usuario_rol"];
 
                      
                      

?>
<style type="text/css">
	
.ContInputmain.col-lg-12.col-sm-12 {
    padding-top: 40px;
}
input[type=checkbox]
{
  /* Double-sized Checkboxes */
  -ms-transform: scale(2); /* IE */
  -moz-transform: scale(2); /* FF */
  -webkit-transform: scale(2); /* Safari and Chrome */
  -o-transform: scale(2); /* Opera */
  padding: 10px;
}
.checkboxtext
{
  /* Checkbox text */
  font-size: 110%;
  display: inline;
}
</style> 
<!--JQuery jquery.min.js" -->
<script src="bower_components/jquery/dist/jquery.min.js"></script>
<script type="text/javascript">
		
		///*****Folio   Maestro
		var   masteFolio  = <?php echo $foliogenerado ; //folio_Cotizacion($_SESSION["usuario_agente"],$conexion); ?>; 
                ///****Numero Agente 
                var   numAgen  = <?php echo $_SESSION["usuario_rol"]; ?>;
                ///***Tipo De Despliegue 
                var  typDespl = <?php echo $TyVisor; ?>;
                
		///****Areglo  Lista Productos  a Cotizar 
		var  AreLisProdCot =  new  Array(); 
		///***Variable  pára alamcenar Clientes  del  Folio sin Guardar
                var  clienSinSave ;
                ///***Varaible  para alamacenar las cadenass   html  de los clientes
                 var cliTab;
		///*** Objeto  ProdACotiza 
		function  ProdACotiza (folio,cve_prod,nom_prod,cantsol,presol,boniporCant,boniporPre,boniPre,boniCatotal,EnableBoni,cve_prodABoni,nom_prodABoni) 
		{

				this.folio= folio; ///****Folio de la Cotizacion 
				this.cve_prod = cve_prod; ///**** Clave  del  Producto  Solicitado
				this.nom_prod = nom_prod; ///***Nombre  del  Producto  Solicitado 
				this.cantsol= cantsol; ///***Cantidad  Solicitada 
				this.presol =presol;///***Precio Solicitado
				this.boniporCant = boniporCant; ///***  Bonificacion Por   Cantidad
				this.boniporPre =boniporPre;  ///****Bonificacion por  Precio ($)
				this.boniPre = boniPre; ///*** Precio de la  Bonificacion 
				this.boniCatotal= boniCatotal; ///***Bonificacion A  APLICAR
				this.EnableBoni = EnableBoni; ////**Habilitar   Bonificacion 
				this.cve_prodABoni =cve_prodABoni; ///*** Clave del  Producto Bonificado  
				this.nom_prodABoni =nom_prodABoni; ////***Nombre del  Producto   Binificado 
                              
                                this.venta = (cantsol * presol)///***Calculamos  la  venta 
                               


		}
		///****Funcion para  Detectar   que no Se repita  los productos 
		function   ItsInArre (ProdACotiza)
		{
			var  itsElem  =  false;  
			for (var  i  in  AreLisProdCot)
			{
				if( AreLisProdCot[i].cve_prod  ==  ProdACotiza.cve_prod)
				{
					itsElem=  true; 
				} 
			} 

			return  itsElem;
		} 
		///***Funcion para  Eliminar Del  Areglo AreLisProdCot
		function  DelEleAre(cve_prod)////***Valor de entrada  Clave del Producto 
		{	
			var   AuxAre =   new  Array(); 
                        console.log(cve_prod);
			for (var  i  in  AreLisProdCot)
			{
				if( AreLisProdCot[i].cve_prod.localeCompare(cve_prod) == 0)
				{
					///AuxAre.push(AreLisProdCot[i]);
                                        AreLisProdCot.splice(i,1);
				} 
			}
			console.log(AreLisProdCot);
			 /***Asignammos el  Nuevo areglo 
			 AreLisProdCot = new Array(); 
			 AreLisProdCot =AuxAre; */
		}
                ///***Funcion para  Obtener el Elemento por CVE PROD
                function GetObjArGen (cve_prod)
		{
                    var OBj;
                    for (var  i  in  AreLisProdCot)
			{
				if( AreLisProdCot[i].cve_prod.localeCompare(cve_prod) == 0)
				{
					OBj=AreLisProdCot[i];
				} 
			}
                        return  OBj;
                }
                 ////****Funcion para Cargar   la tabla de  los productos  Sin agregar Elemntos
                function CalVenTotal()
                {
                    ///***Varaible para  El  Agregado de  elementos a la  tabla 
                            var sumaVenta =0 ; 
                             for(var i  in AreLisProdCot ){
                                            sumaVenta += AreLisProdCot[i].venta;
                             }	


                   return sumaVenta;
                }
                  ////*****Convertir a  Formato  Moneda 
                    function currency(value, decimals, separators) {
                        decimals = decimals >= 0 ? parseInt(decimals, 0) : 2;
                        separators = separators || ['.', "'", ','];
                        var number = (parseFloat(value) || 0).toFixed(decimals);
                        if (number.length <= (4 + decimals))
                            return number.replace('.', separators[separators.length - 1]);
                        var parts = number.split(/[-.]/);
                        value = parts[parts.length > 1 ? parts.length - 2 : 0];
                        var result = value.substr(value.length - 3, 3) + (parts.length > 1 ?
                            separators[separators.length - 1] + parts[parts.length - 1] : '');
                        var start = value.length - 6;
                        var idx = 0;
                        while (start > -3) {
                            result = (start > 0 ? value.substr(start, 3) : value.substr(0, 3 + start))
                                + separators[idx] + result;
                            idx = (++idx) % 2;
                            start -= 3;
                        }
                        return (parts.length == 3 ? '-' : '') + result;
                    }

</script>
<script type="text/javascript">
$(document).ready(function(){
		
		$("#folMa").text("Folio:"+masteFolio);
	///****Funcion para  Calcular   Las  Bonificaciones
		function bonicalculate(ProdACotiza) {
			 ////****Obteneos las  variables
                         var  cantSol =  $("#cantSol").val()    ;///Cantidad  Solicitada  Bonificacion
                         var  bonixCant =  $("#boniCant").val() ;///Bonificacion por Cantidad 
                         var   bonixpre = $("#boniPORpre").val();///Bonificacion por Precio 
                         var  preBoni = $("#boniPreci").val();///Precio de la  Bonificacion 
                         
                         var cantidad;
                         $("#boniCantTotal").val(0)
                         
			if( $("#boniPORpre").val()!="" && ($("#boniCant").val()=="" || $("#boniCant").val()==0)){
			 cantidad =(parseFloat(cantSol)*parseFloat(bonixpre))/parseFloat(preBoni);
			}
			if($("#boniCant").val()!="" && ($("#boniPORpre").val() == ""|| $("#boniPORpre").val() == 0)){
			 cantidad = (parseFloat(cantSol)*parseFloat(bonixCant) );
			}
			var  cantRedon =cantidad.toFixed(2);
			
			console.log(cantRedon)
			$("#boniCantTotal").val(cantidad.toFixed(2))
			return ProdACotiza;
		}
	///****Funcion para  Retornar Un Nuevo   Objeto  
	function  NewProdACotiza() 
	{
           var canSol = $("#cantSol").val();
           var preSol = $("#preSol").val() ;
           var SolCan =  parseFloat(canSol);
           var PreSOl = parseFloat(preSol);
		return   new  ProdACotiza (
			masteFolio,
			$("#cve_prod").val() ,
			$("#nom_prod").val(),
			SolCan.toFixed(2),
			PreSOl.toFixed(2),
			$("#boniCant").val(),
			$("#boniPORpre").val(),
			$("#boniPreci").val(),
			$("#boniCantTotal").val(),
			$("#boniChe").val(),
			$("#boniProd").val(),
			$("#boniProd option:selected").attr('nomProd'));
	}
	///***Elemtos A Cero 
	function  ElemTo0 ()
	{

			$("#cantSol").val(0)
			$("#preSol").val(0)
			$("#boniCant").val("")
			$("#boniPORpre").val("")
			$("#boniPreci").val("")
			$("#boniCantTotal").val("")
			$("#boniChe").attr('value', 'false');
			$("#boniChe").prop( "checked", false )
			$("#conBoninput").hide();
			

	}
	///****Obtenemos los   elementos  Seleccionados  
	$("#producto").click(function(){

				$("#btnAddPro").prop('disabled',false);
				var cve_prod  =$("#producto  option:selected").val() ; 
				var nom_prod  = $("#producto  option:selected").attr('nomProd');

				$("#cve_prod").val(cve_prod);
				$("#nom_prod").val(nom_prod);
				$.ajax({
                type:'POST',
                url: 'coti_scrip_cotiza/coti_GetPreGen.php',
                data:{"cvePro":cve_prod}, 
                success: function (datos) { 
                 				$("#preGen").val(datos.restotal);		
                     }
             });

				///$('#producto option:selected').appendTo('#boniProd option:selected');
				$("#boniProd option[value="+ cve_prod +"]").attr("selected",true);  

	});
	///****Agregamos  Productos a la Tabla
	$("#btnAddPro").click(function(){

		///***Creamos el  Objeto  
		var  ObjetoNew =  NewProdACotiza()  
		if(ItsInArre(ObjetoNew)==false || AreLisProdCot.length ==0)
		{	
			
			//***Agregamos  un Nuevo  Elemento  
			
			NewRowTb (ObjetoNew);
			 ElemTo0 ()	
			//console.log(ObjetoNew);
			
			///console.log(JSON.stringify(ObjetoNew));
			$.ajax({
                                    type:'POST',
                                    url: 'coti_scrip_cotiza/coti_addDetProd.php',
                                    data: {"keyOpc":1, "ObjProd" :JSON.stringify(ObjetoNew)}, 
                                    success: function (datos) { 
                                                                    ///console.log(datos.restotal);		
                                         }
                          });
	
		} else{
		
		$('#CONTMOD').html("<h6>Error !!! <br> No Se Puede Agregar El Producto !!<br>Ya Existe</h6>");
		 $('#ModalMNs').modal('show');
		}

	});
 ///****BTN  Eliminar elemento  
    $(document).on("click",".btnEli",function(){
       
    	 ///***Elimiamos elemento  de la  tabla
    	  var  parent =  $(this).parent().get(0);
          
          if($(this).attr('elemeBoni') != "" ||$(this).attr('elemeBoni') != null)
          {
             var  classboni  = ".boniElmElim"; 
             classboni +=   $(this).attr('elemeBoni');
            $(classboni).remove();
         
          }
            
              ///**Obtenemos  EWl Objeto
                var    ObjToDel = GetObjArGen ($(this).attr('cveprodel'));
              
              
               ///console.log(JSON.stringify(ObjetoNew));
			$.ajax({
                                    type:'POST',
                                    url: 'coti_scrip_cotiza/coti_delOrUpdprod.php',
                                    data: {"keyOpc":2, "ObjProd" :JSON.stringify(ObjToDel)}, 
                                    success: function (datos) { 
                                                            console.log(datos.EstQery);	
                                                        if(datos.EstQery==true){   
                                                        ///**Eliminamos  Elemento del  areglo  General
                                                              DelEleAre( ObjToDel.cve_prod);///$(this).attr('cveprodel'));
                                                            ///***Eliminamos el elemento 
                                                            $(parent).remove();
                                                            ///**Calculaos  la venta  Total  sin el  Producto 
                                                            $(".vtotal").text(CalVenTotal()) ;
                                                       
                                                           
                                                          
                                                        }
                                                            
                                         }
                          });
                                    
	});
        ///****BTN  Eliminar elemento   Cliente 
    $(document).on("click",".btnEliCLIE",function(){
    	 ///***Elimiamos elemento  de la  tabla
            var  parent =  $(this).parent().get(0);
             var CveCli =  $(this).attr('CVELEM');
               ////****Agregamos al cliente 
                 $.ajax({
                type:'POST',
                url: 'coti_scrip_cotiza/coti_addORdelCliente.php',
                data: { "FOL" :masteFolio, "CVE":CveCli,"keyOpc":2}, 
                success: function (datos) { 
                              
                                 
                                 $(parent).remove(); 
                     }
                 });
             
             
             
	});
        ///****Btna  Modificar 
        $(document).on("click",".btnUpdate",function(){
            ///****Desabilitamos el Btn de  Aagregar 
            $("#btnAddPro").prop('disabled',true );
            ///***Visualizamos el   BOTON  DE Modificar 
             $("#conUpdBtn").show(); ////****Contenedor  Btn Update 
             $("#conaddBtn").hide(); ////****Contenedor Btn  add 
            ///***Obtenemos el  Objeto 
               var ElmUpdate =    GetObjArGen ($(this).attr('cveprodel'));
               ///console.log(ElmUpdate);
             ///***Asignamos los valores  a los Inputs 
             $("#cve_prod").val(ElmUpdate.cve_prod) 
             $("#nom_prod").val(ElmUpdate.nom_prod)
	     $("#cantSol").val(ElmUpdate.cantsol)
	     $("#preSol").val(ElmUpdate.presol)
	///***Revisasmos  si  tiene Bonificacion 
            if(ElmUpdate.EnableBoni.localeCompare('true')==0 )
            {
                ///***Bonificacion Habilitada
			$("#conBoninput").show();
			  $("#boniChe").attr('value', 'true');
                ///***Habilitamas   todos  los  campos 
                	$("#boniCant").val(ElmUpdate.boniporCant)
			$("#boniPORpre").val(ElmUpdate.boniporPre)
			$("#boniPreci").val(ElmUpdate.boniPre)
			$("#boniCantTotal").val(ElmUpdate.boniCatotal)
                        
		$("#boniProd option[value="+ElmUpdate.cve_prodABoni +"]").attr("selected",true);  
		
			///$("#boniProd option:selected").attr('nomProd')
                 
            }else {
                ///***Bonificacion Desabilitada 
			$("#conBoninput").hide();
                        $("#boniChe").attr('value', 'false');
                        $("#boniCant").val(0)
			$("#boniPORpre").val(0)
			$("#boniPreci").val(0)
			$("#boniCantTotal").val(0)
                        $("#boniProd option[value="+ElmUpdate.cve_prod +"]").attr("selected",true); 
                        $("#boniChe").attr('value', 'false');
                        
            }
                    
            
        });
	//**** Check   Bonificacion
	$("#boniChe").change(function(){

		if($("#boniChe").is(":checked")){	
			///***Bonificacion Habilitada
			$("#conBoninput").show();
			  $(this).attr('value', 'true');
		}else{ 
			///***Bonificacion Desabilitada 
			$("#conBoninput").hide();
			 $(this).attr('value', 'false');
		}
	});
        ////***Check   Zona
        $("#zona").change(function(){
            if($("#zona").is(":checked")){	
			///***Bonificacion Habilitada
			$("#btnAddClient").prop("disabled",true);
			  $(this).attr('value', 'true');
		}else{ 
			///***Bonificacion Desabilitada 
			$("#btnAddClient").prop("disabled",false);
			 $(this).attr('value', 'false');
		}
            
        });
	//****Evento  para generar  el  calculo de la  Bonificacion 
	$("#boniPreci").change(function(){
				bonicalculate(); 
	});
        ///****Evento  para   desplegar el  Modal para  Seleccionar   clientes
        $("#btnAddClient").click(function(){
            
            $("#ModClie").modal('show');
            cliTab="";
           for(var i in clienSinSave)
           {
                   cliTab += "<tr><td>"+clienSinSave[i].cve_cliente+"-"+clienSinSave[i].nom_cliente+"</td><td type ='button' CVELEM ='"+clienSinSave[i].cve_cliente+"'  class='btnEliCLIE  btn  btn-danger' ><span class='glyphicon glyphicon-trash'></span></td></tr>";  
           }
           $("#tbCliente").html(cliTab);
            
        });
        ///***Evento de  Seleccion de  cliente
       
        $("#selCliente").click(function(){
                var cve_cliente =$("#selCliente  option:selected").val() ; 
		var nom_cliente = $("#selCliente  option:selected").attr('nombreProd');
               
                 ////****Agregamos al cliente 
                 $.ajax({
                type:'POST',
                url: 'coti_scrip_cotiza/coti_addORdelCliente.php',
                data: { "FOL" :masteFolio, "CVE":cve_cliente,"keyOpc":1}, 
                success: function (datos) { 
                                 if(datos.EstQery == true){
                                   
                                   
                                <?php  if($TyVisor ==0||$TyVisor ==1){ ?> 
                                           
                                            cliTab += "<tr><td>"+cve_cliente+"-"+nom_cliente+"</td><td type ='button'  CVELEM='"+cve_cliente+"' class='btnEliCLIE  btn  btn-danger' ><span class='glyphicon glyphicon-trash'></span></td></tr>";  
                                <?php } ?>
                                
                                <?php  if($TyVisor ==2){ ?> 
                                           cliTab += "<tr><td>"+cve_cliente+"-"+nom_cliente+"</td></tr>";  
 
                                <?php } ?> 

                                }else {
                                    cliTab +=  "<tr><td>ERROR DE ELEMENTO ESTA REPETIDO EL CLIENTE </td></tr>"
                                } 
                                $("#tbCliente").html(cliTab);
                                
                     }
                 });
                
            
        });
        ///***Boton  para Modificar Un Producto 
        $("#btnModi").click(function(){
             ///***Creamos el  Objeto  
		var  ObjetoNew =  NewProdACotiza()  
			///***Eliinamos El Elemento 
                        DelEleAre(ObjetoNew.cve_prod);
                        
			//***Agregamos  un Nuevo  Elemento  
			NewRowTb (ObjetoNew);
			ElemTo0 ()
                        
			console.log(ObjetoNew);
			
			///console.log(JSON.stringify(ObjetoNew));
			$.ajax({
                                    type:'POST',
                                    url: 'coti_scrip_cotiza/coti_addDetProd.php',
                                    data: {"keyOpc":2, "ObjProd" :JSON.stringify(ObjetoNew)}, 
                                    success: function (datos) { 
                                            ///console.log(datos.restotal);
                                            $("#conUpdBtn").hide(); ////****Contenedor  Btn Update 
                                            $("#conaddBtn").show(); ////****Contenedor Btn  add 
                                         }
                          });
	
		
             
                
        }); 
        ////***Boton  Para GUardar la  Cotizacion 
        $("#btnSaveall").click(function(){
          ////****Validamos que existan elementos  en el arreglo maestro 
          if(AreLisProdCot.length >0)
          {
            $("#ModaSAVEcAB").modal("show");
              
          }else {
              
              alert("Lo sentimos No Agrego Productos  Imposible  Guardar");
              
          }
            
            
            
        });
        ///**Btn  para Guardar  elementos
        $("#btnSaALL").click(function(){
            $.ajax({
                                    type:'POST',
                                    url: 'coti_scrip_cotiza/coti_addCabGerenCotiz.php',
                                    data: {"folio":masteFolio,"typDes":typDespl, "nAge" : numAgen, "txtComent":$("#txtcomen").val(),"ZONA":$("#zona").val()}, 
                                    success: function (datos) { 
                                                if(datos.EstQery == true )
                                                {
                                                    ///****Se realiso  la  Insercion 
                                                    window.location.href ="http://192.168.101.05/sistemas/cronos/index_gerentes.php"
                                                }else
                                                {
                                                    
                                                }
                                         }
                          });
        });
        ///****Calcular Bonificacion con BOTON 
        $("#btnCalBoni").click(function(){
            bonicalculate(); 
            
        });

   //****Funcion para para Mostrar Nuevo elemento en la  tabla 
    function  NewRowTb (ObjetoNew)
    {
    		 ////***Agregamos el  elemento   al  Areglo 
		 AreLisProdCot.push(ObjetoNew);

		///***Varaible para  El  Agregado de  elementos a la  tabla 
		var   tbElem ,sumaVenta =0 ; 
		 for(var i  in AreLisProdCot ){

			 if(AreLisProdCot[i].EnableBoni == 'false') 	
			 {		
                                        <?php  if($TyVisor ==0||$TyVisor ==1){ ?> 
                                                ///*****Producto  a  Cotizar                currency(, 2, [',', ",", '.'])
                                                tbElem  += "<tr ><td>"+AreLisProdCot[i].cve_prod +"</td><td>"+AreLisProdCot[i].nom_prod+"</td><td>$"+currency(AreLisProdCot[i].presol, 2, [',', ",", '.'])+"</td><td>"+AreLisProdCot[i].cantsol+"</td><td></td><td></td><td></td><td></td><td>$"+currency(AreLisProdCot[i].venta, 2, [',', ",", '.']) +"</td><td type ='button' class='btnEli  btn  btn-danger'  cveprodel="+AreLisProdCot[i].cve_prod +"><span class='glyphicon glyphicon-trash'></span></td><td type ='button' class='btnUpdate btn  btn-success' cveprodel="+AreLisProdCot[i].cve_prod +"><span class='glyphicon glyphicon-check'></span></td></tr>";	
                                       
                                        <?php } ?>

                                        <?php  if($TyVisor ==2){ ?> 
                                                ///*****Producto  a  Cotizar 
                                                tbElem  += "<tr ><td>"+AreLisProdCot[i].cve_prod +"</td><td>"+AreLisProdCot[i].nom_prod+"</td><td>$"+currency(AreLisProdCot[i].presol, 2, [',', ",", '.'])+"</td><td>"+AreLisProdCot[i].cantsol+"</td><td></td><td></td><td></td><td></td><td>$"+currency(AreLisProdCot[i].venta, 2, [',', ",", '.'])+"</td></tr>";	
                                       
                                        <?php } ?>


                         }else {
			 		
                                         <?php  if($TyVisor ==0||$TyVisor ==1){ ?> 
                                                ///*****Producto  a  Cotizar 
                                                tbElem  += "<tr><td>"+AreLisProdCot[i].cve_prod +"</td><td>"+AreLisProdCot[i].nom_prod+"</td><td>$"+currency(AreLisProdCot[i].presol, 2, [',', ",", '.'])+"</td><td>"+AreLisProdCot[i].cantsol+"</td><td>"+AreLisProdCot[i].boniporPre+"</td><td></td><td></td><td></td><td>$"+currency(AreLisProdCot[i].venta, 2, [',', ",", '.'])+"</td><td type ='button' class='btnEli  btn  btn-danger' elemeBoni='"+AreLisProdCot[i].cve_prodABoni+"'  cveprodel="+AreLisProdCot[i].cve_prod +"><span class='glyphicon glyphicon-trash'></span></td><td type ='button' class='btnUpdate btn  btn-success'  cveprodel="+AreLisProdCot[i].cve_prod +"><span class='glyphicon glyphicon-check'></span></td></tr>";
                                                ////****Mostramos el  Producto Bonificado  si existe 
                                                tbElem  += "<tr class='boniElmElim"+AreLisProdCot[i].cve_prodABoni+"   alert alert-success'><td>Bonificado</td><td>"+AreLisProdCot[i].nom_prodABoni +"</td><td></td><td></td><td>"+AreLisProdCot[i].boniporPre+"</td><td>"+AreLisProdCot[i].boniporCant+"</td><td>"+AreLisProdCot[i].boniPre+"</td><td>"+AreLisProdCot[i].boniCatotal+"</td><td></td><td></td></tr>"

                                        <?php } ?>

                                        <?php  if($TyVisor ==2){ ?> 
                                               ///*****Producto  a  Cotizar 
			 		tbElem  += "<tr><td>"+AreLisProdCot[i].cve_prod +"</td><td>"+AreLisProdCot[i].nom_prod+"</td><td>"+currency(AreLisProdCot[i].presol, 2, [',', ",", '.'])+"</td><td>"+AreLisProdCot[i].cantsol+"</td><td>"+AreLisProdCot[i].boniporPre+"</td><td></td><td></td><td></td><td>"+currency(AreLisProdCot[i].venta, 2, [',', ",", '.'])+"</td></tr>";
			 		////****Mostramos el  Producto Bonificado  si existe 
			 		tbElem  += "<tr class='boniElmElim"+AreLisProdCot[i].cve_prodABoni+"   alert alert-success'><td>Bonificado</td><td>"+AreLisProdCot[i].nom_prodABoni +"</td><td></td><td></td><td>"+AreLisProdCot[i].boniporPre+"</td><td>"+AreLisProdCot[i].boniporCant+"</td><td>"+AreLisProdCot[i].boniPre+"</td><td>"+AreLisProdCot[i].boniCatotal+"</td><td></td><td></td></tr>"
			 
                                        <?php } ?>
                         } 
                                sumaVenta += AreLisProdCot[i].venta;

			 /*
		tbElem  += "<tr><td>"+AreLisProdCot[i].cve_prod +"</td><td>"+AreLisProdCot[i].nom_prod+"</td><td>"+AreLisProdCot[i].presol+"</td><td>"+AreLisProdCot[i].cantsol+"</td><td>"+AreLisProdCot[i].cve_prodABoni+"</td><td>"+AreLisProdCot[i].boniporPre+"</td><td>"+AreLisProdCot[i].boniporCant+"</td><td>"+AreLisProdCot[i].boniPre+"</td><td>"+AreLisProdCot[i].boniCatotal+"</td><td type ='button' class='btnEli  btn  btn-danger'  CVEpROD="+AreLisProdCot[i].cve_prod +"><span class='glyphicon glyphicon-trash'></span></td><td type ='button' class='btnUpdate btn  btn-success'><span class='glyphicon glyphicon-check'></span></td></tr>"*/
			}
                     
                     tbElem  +=   "<tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td><h6>Total Venta:</h6></td><td><h6 class='vtotal' >$"+currency(sumaVenta.toFixed(2), 2, [',', ",", '.'])+"</h6></td><td></td><td></td></tr>";
                        
		$("#tbCont").html(tbElem);

    }
   
    ///****Funcion Para Obtener Los productos  de Un determinado Folio Si es que no se generaro el Encabezado 
    function GetProdSinSave()
    {

    		$.ajax({
                type:'POST',
                url: 'coti_scrip_cotiza/coti_prodsinsave.php',
                data: { "FL" :masteFolio , "EST":0}, 
                success: function (datos) { 
                					var    AregloResult  = JSON.parse(datos.allelem);
                                                        ////****Obtenemos  Los  Clientes  Asignados  a la  Cotizacion
                                                         clienSinSave= JSON.parse(datos.CliS)
                                                        for(var  i  in   AregloResult)
                					{
                							var ObjSinSave = new  ProdACotiza (
														masteFolio,
														AregloResult[i].cveProd ,
														AregloResult[i].nomProd,
														AregloResult[i].cantSol,
														AregloResult[i].preSol,
														AregloResult[i].boniPorCant,
														AregloResult[i].boniPorPre,		
														AregloResult[i].boniPreSol,
														AregloResult[i].boniPreAp,		
														AregloResult[i].boniEst,
														AregloResult[i].cveProdBoni,
														AregloResult[i].NomProdBoni);
                								///console.log(ObjSinSave);
												///****Agregamos el  elemento  a  la tabla
														NewRowTb (ObjSinSave);
											
                                                       }
                                                       

                     }
             });
    		
    }
///*Mandaos Obtener el Folio 
GetProdSinSave()


});
</script>
<div  class="container">
	<div  class="form-inline"> 
            <?php  if($TyVisor==1||$TyVisor==0){ ?> 
		<div class="col-lg-6  col-sm-6"> <h4>Nueva  Cotizacion :</h4></div>
            <?php } ?>   
            <?php  if($TyVisor ==2){ ?> 
		<div class="col-lg-6  col-sm-6"> <h4>Cotizacion :</h4></div>
            <?php } ?>    
		<div class="col-lg-6  col-sm-6"> <div class="form-inline" ><h5 id="folMa"></h5></div></div>
	</div>
    <!--***************************************************************************-->    
 <?php  if($TyVisor !=2){ ?> 
<div  class="col-lg-12 col-sm-12">
		
		<div class="col-lg-7  col-sm-7">
			<div class="input-group input-group select2-bootstrap-prepend">
                <span class="input-group-btn">
                    <button class="btn btn-default" type="button" data-select2-open="select2-button-addons-multi-input-group">
                        <span class="glyphicon glyphicon-search"></span>
                    </button>
                </span>
                <select name="producto" class="form-control select2" id="producto" >
                    <option>Producto</option>
                    <?php
                    while ($row = mssql_fetch_array($tabla)) {
                       
                            echo '<option value="' . $row['ItemCode'] . '"  nomProd="'.$row['ItemName'] .'"  >' . $row['ItemName'] . '-' . $row['ItemCode'] . '</option>';
                        
                    }
                    ?>
                </select>
            </div> 



		</div>
		<div class="col-lg-5  col-sm-5">
			<button  type="button" id="btnSaveall"   class="btn btn-success" >Guardar <span class="glyphicon glyphicon-floppy-disk"></span></button>
		</div>
	</div>
    
        <div class="row">
                            
                            <div  class="row">
						<div  class="col-lg-6 col-sm-6">
							 <label><strong>Asignar Cotizacion Por Zona</strong> </label>
						</div>
						<div  class="col-lg-6 col-sm-6">
                                                    <input checked value="true" type="checkbox"  id="zona">

						</div>
                            </div>
                            <div class="form-inline">
                                <label><strong>Asignar Cotizacion Por Cliente</strong></label>
                                <button disabled  type="button"  id="btnAddClient"  class="btn btn-success" >Clientes <span class="glyphicon glyphicon-plus"> </span></button>
                          </div>
                        
    </div>
    
    
    
	<div  class="ContInputmain col-lg-12  col-sm-12"> 
		<div class="col-lg-6  col-sm-6">
				<div class="form-group">
					<label>Clave Producto</label>
					<input disabled  type ="text"  class="form-control"  id="cve_prod"> 
 				</div>
				<div class="form-group">
					<label>Nombre Producto</label>
					<input disabled type ="text"  class="form-control"  id="nom_prod"> 
 				</div>
 				<div class="row">
 					<div class="col-lg-6 col-sm-6">
 							<div class="form-group">
								<label>Precio General</label>
								<input disabled type ="number"  class="form-control"  id="preGen"> 
			 				</div>

 					</div>
 					<div class="col-lg-6 col-sm-6">
 						<div class="form-group">
								<label>Precio Solicitado</label>
								<input step="any"  type ="number"  class="form-control"  id="preSol"> 
			 			</div>

 					</div>
				</div>
				<div class="row">
 					<div class="col-lg-6 col-sm-6">
 							<div class="form-group">
								<label>Cantidad Solicitada</label>
								<input step="any" type ="number"  class="form-control"  id="cantSol"> 
			 				</div>

 					</div>
 					<div id="conaddBtn" class="col-lg-3 col-sm-3">
 						<button disabled type="button"  id="btnAddPro"  class="btn btn-success" >Agregar <span class="glyphicon glyphicon-plus"> </span></button>

 					</div>
                                        
                                    <div  hidden id="conUpdBtn"  class="col-lg-3 col-sm-3">
                                        <button    type="button"  id="btnModi"  class="btn btn-info" > Modificar <span class="glyphicon glyphicon-plus"> </span></button>

 					</div>
				</div>
                  <!--  <div class="row">
                            
                            <div  class="row">
						<div  class="col-lg-6 col-sm-6">
							 <label><strong>Asignar Cotizacion Por Zona</strong> </label>
						</div>
						<div  class="col-lg-6 col-sm-6">
                                                    <input checked value="true" type="checkbox"  id="zona">

						</div>
                            </div>
                            <div class="form-inline">
                                <label><strong>Asignar Cotizacion Por Cliente</strong></label>
                                <button disabled  type="button"  id="btnAddClient"  class="btn btn-success" >Clientes <span class="glyphicon glyphicon-plus"> </span></button>
                          </div>
                        
                    </div>-->

		</div>
		<div class="col-lg-6  col-sm-6">
				<div   class="form-inline">
				<div  class="row">
						<div  class="col-lg-6 col-sm-6">
							<label>Bonificacion </label>
						</div>
						<div  class="col-lg-6 col-sm-6">
							<input value="false" type="checkbox"  id="boniChe">

						</div>
                                                
                                    
				</div>
				</div>
				<div hidden  id="conBoninput">

					<div class="row">
					 		
				                <select name="boniProd" class="form-control " id="boniProd" >
				                   <option>Producto</option>
				                     <?php
				                     $tbbonipro = mssql_query($string_prod);
				                    while ($row = mssql_fetch_array($tbbonipro)) {
				                       
				                            echo '<option value="' . $row['ItemCode'] . '"  nomProd="'.$row['ItemName'] .'"  >' . $row['ItemName'] . '-' . $row['ItemCode'] . '</option>';
				                        
				                    }
				                    ?>
				                </select>
				                
           					 

					</div>

					<div class="row">
	 					<div class="col-lg-6 col-sm-6">
	 							<div class="form-group">
									<label>Bonificar ($) </label>
									<input step="any" type ="number"  class="form-control"  id="boniPORpre"> 
				 				</div>

	 					</div>
	 					<div class="col-lg-6 col-sm-6">
	 						<div class="form-group">
									<label>Bonificar (Cantidad) </label>
									<input step="any"  type ="number"  class="form-control"  id="boniCant"> 
				 				</div>
	 					</div>
					</div>
					<div class="row">
	 					<div class="col-lg-6 col-sm-6">
	 							<div class="form-group">
									<label>Precio Bonificación: </label>
									<input step="any" type ="number"  class="form-control"  id="boniPreci"> 
				 				</div>

	 					</div>
	 					<div class="col-lg-6 col-sm-6">
	 						<div class="form-group">
									<label>Bonificacion a Aplicar</label>
									<input  step="any" disabled type ="number"  class="form-control"  id="boniCantTotal"> 
				 				</div>
	 					</div>
					</div>
                                    <div class="row">
                                        <button  type="button" id="btnCalBoni" class="btn btn-success"  >Calcular  Bonificacion<span class="glyphicon glyphicon-plus"></button>
                                        
                                    </div> 
				</div>
		</div>
	</div>
 <?php } ?>    
<!--***************************************************************************-->
    
	<div class ="col-lg-12 col-sm-12">
		<table class="table table-condensed "> 
		<thead>
			<tr>
				<th>Cve Prod</th>
				<th>Nom Prod</th>
				<th>Pre Sol</th>
				<th>Cant Sol</th>
				<th>Bonif ($)</th>
				<th>Bonif (Cant)</th>
				<th>Pre Bonifi</th>
				<th>Bonif Aplicar</th>
                                <th>Venta</th>
				<th></th>
				<th></th>
			</tr>
		</thead>
		<tbody id="tbCont"> 
                    
                </tbody>
		</table>


	</div>
    <br><br>
    <div class ="col-lg-12 col-sm-12"> 
        <h6>Comentarios</h6>
        <textarea required  class="form-control" id="txtcomen" value=" <?php  if($TyVisor==1||$TyVisor==2)
                                                                                { echo $comentCoti['com_agent'];} ?>">
           <?php  if($TyVisor==1||$TyVisor==2)
                   {  echo $comentCoti['com_agent'];} ?>    
        </textarea>
    
    
    </div> 

</div>
 <!--*********************-->
    
    <!---Modal  Mensages-->    
     <div  class="modal fade" id="ModalMNs" role="dialog">
        <div  class="modal-dialog">

          <!-- Modal content-->
          <div  class="modal-content">
            <div class="modal-header">
              
            </div>
            <div class="modal-body">
      
                <div class="form-group">
                    <div id="CONTMOD" class="row" class="well"> 
                        
                    </div>
                </div>
            </div>
            <div class="modal-footer">
              <button type="button" id="close_coment" class="close_coment btn btn-danger" data-dismiss="modal">Close</button>
             <!--  <button type="button" id="btn_update_comentarios"  class="btn btn-info" data-dismiss="modal"> <span class="glyphicon glyphicon-floppy-saved"></span> Guardar</button> -->
            </div>
          </div>

        </div>
      </div>
         <!---Fin Modal  Mensages--> 

        <!---Modal  Seleccionar Clientes  ********************************-->    
        <div  class="modal fade" id="ModClie" role="dialog">
        <div  class="modal-dialog">

          <!-- Modal content-->
          <div  class="modal-content">
            <div class="modal-header">
    			<h5>Seleccionar Clientes</h5>          
            </div>
            <div class="modal-body">
            	<div  class ="row">
            	<!--Contenedor  para   Seleccionar Cliente -->
                <div class="input-group input-group select2-bootstrap-prepend">
                    <span class="input-group-btn">
                      <button class="btn btn-default" type="button" data-select2-open="select2-button-addons-multi-input-group">
                        <span class="glyphicon glyphicon-search"></span>
                      </button>
                    </span>
                    <select name="cliente" class="form-control select2" id="selCliente" >
                      <option>Cliente</option>
                      <?php
                      ///***Obtenemso  el  Areglo 
                      $AregloCLIENTES = GetArrClientesByGerentes($_SESSION["usuario_rol"],$conecta1);
                       foreach( $AregloCLIENTES as $row) {
                        if ($row['CardCode'] == $_REQUEST['cliente']) {
                          echo '<option selected value="' . $row['CardCode'] .'" nombreProd="'.utf8_encode($row['CardName']).'"   >' . $row['CardCode'] . '-' . utf8_encode($row['CardName']) . '</option>';
                        } else {
                          echo '<option value="' . $row['CardCode'] .'" nombreProd="'.utf8_encode($row['CardName']).'"  >' . $row['CardCode'] . '-' . utf8_encode($row['CardName']) . '</option>';
                        }
                      }
                      ?>
                    </select>
                  </div>
                
            	</div>
                <br>
     			<div  class="col-sm-12"> 
     			<table  class="table table-condensed" >
     					<thead>
                                         <th>Cliente</th>
                                        <th></th>
     					</thead>
                                        <tbody  id="tbCliente">
                                            
                                        </tbody>	
     			</table>
				</div> 
            </div>
            <div class="modal-footer">
              <button type="button" id="closScLI" class="close_coment btn btn-danger" data-dismiss="modal">Close</button>
             <!--  <button type="button" id="btn_update_comentarios"  class="btn btn-info" data-dismiss="modal"> <span class="glyphicon glyphicon-floppy-saved"></span> Guardar</button> -->
            </div>
          </div>

        </div>
      </div>
	<!---Fin Modal  Seleccionar Clientes  ********************************-->    
         <!---Modal  Mensages  Envio-->    
     <div  class="modal fade" id="ModaSAVEcAB" role="dialog">
        <div  class="modal-dialog">

          <!-- Modal content-->
          <div  class="modal-content">
            <div class="modal-header">
                <h5>Envio de Cotizacion.</h5>
            </div>
            <div class="modal-body">
                <div class="row"><p>Esta a Punto de Guardar una Nueva  Cotizacion Con el <strong> N# Folio:<?php echo $foliogenerado;?></strong></p></div>
                <div   class="col-sm-12">
                    <div class="col-sm-3"></div> 
                    <div class="col-sm-2"><button type="button"  id="btnSaALL" class="btn btn-success">Guardar</button></div>
                    <div class="col-sm-2"></div>
                    <div class="col-sm-2"><button type="button"  class="close_coment btn btn-danger" data-dismiss="modal">Close</button></div>
                    <div class="col-sm-3"></div>
                </div> 
            </div>
            
          </div>

        </div>
      </div>
         <!---Fin Modal  Mensages-->
        
        
        
<?php   
///****Agregamos  fOOT
require_once('foot.php');
?> 