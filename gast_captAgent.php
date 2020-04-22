<?php
////****gast_captAgent.php

/*
********	 INFORMACION ARCHIVO ***************** 
	Nombre  Archivo : gast_captAgent.php 
	Fecha  Creacion : 24/10/2017
	Descripcion  : 
 *              Escrip  Diseñado  para  Capturar  los Gastos de Agente
	*/

////**Inicio De Session 
	session_start();
///****Cabecera Cronos

				
 ///***Heder  Gerentes
						if($_SESSION["usuario_agente"] ==1 ||
								$_SESSION["usuario_agente"] ==2 ||
							$_SESSION["usuario_agente"] ==3 ||
							$_SESSION["usuario_agente"] ==1 ||
							$_SESSION["usuario_agente"] ==1 ||
							$_SESSION["usuario_agente"] ==1 ){
		
								 require_once('header_gerentes.php');   
							}else {
								if($_SESSION["usuario_agente"] >= 400 && $_SESSION["usuario_agente"] < 499 )
								{
										 require_once('heder_desarrollo.php'); 
								}else {
									 
										require_once('header.php');

 
								}    

							}

require_once('Connections/conecta1.php');
require_once('formato_datos.php');


$consulta =mysqli_query($conecta1, "SELECT nombre, id, retencion FROM catalogo  order by nombre asc")

///*****Formato de  Datos          
///require_once('formato_datos.php');   //para evitar poner la funcion de conversion de tipos de datos
/*

 <div class="col-lg-12 col-sm-12 col-xs-12">
				<div  class="col-lg-3 col-sm-3 col-xs-3">
						<div  class="form-group-sm form-group-lg">
								<label><strong>Factura</strong><label>
								<input  type="number"  min="0"  id="numfact" class="form-control" >    
						</div> 
				</div> 
				<div  class="col-lg-7 col-sm-7 col-xs-7">
						<div  class="form-group-sm form-group-lg">
								<label><strong>Concepto</strong><label>
												<select  type="select"  id="optionconcept" class="form-control" >
														<option>Primer Ejemplar</option>
												</select>
						</div>
				</div>
				<div  class="col-lg-2 col-sm-2 col-xs-2">
						<div  class="form-group-sm form-group-lg">
								<label><strong>Fecha</strong><label>
										<input  type="date"  id="fech"   class="form-control">
						 </div>    
						
				</div>
		</div> 



 *  */
?> 
<!--JQuery jquery.min.js" -->
<script src="bower_components/jquery/dist/jquery.min.js"></script>
<script src="script_gastos/fechas_fail_other.js"></script>
<script type="text/javascript">
		var  NUMAG = <?PHP echo $_SESSION["usuario_agente"];  ?>;
		var  tosendInfo =  null;
		var otherInfo = <?php echo  $_SESSION['ConteCuent']; ?>; 
		
</script> 
<script type="text/javascript">
$(document).ready(function(){
	$("#onlyfor").remove();
	

				
	$("#BTNSAVE").click(function(){
				 /// console.log(ExistPDF_AND_XML());
				 Valir_InformacionGasto()
	 }); 
	////*****Guardar  ProcesoFinal  
	$("#btnSave").click(function(){
			
								 $.ajax({
										type:'POST',
										url: 'script_gastos/gast_addGastoGen.php',
										data:{"INFOj":JSON.stringify(tosendInfo),"OTHERINFO":JSON.stringify(otherInfo)}, 
										success: function (inserdb) {
																				
													console.log(inserdb)              
												 if(inserdb.Res001 ==  0)
												 {
														ShowErro("Lo Sentimos Ocurrio  Un Error Intente mas tarde !!! "+inserdb.ERROR );
												 }else{
																 if(tosendInfo.concepto != 106 )   
																 {   
																		 Send_File() 
																 }else{
																		 window.setInterval(ReFrESHpA(),190000);
													 ExitoTOsAVE(); 
																 }
												 }   

								 }});
				
			
	}); 
	 ////****Funcion para enviar  elementos
	 function  Send_File()
	 {
						var archi =   document.getElementById("filArch");
						var   datos =  new  FormData();
						for(var i =0 ;i< archi.files.length ;i++  )
						{
								var file  = archi.files[i];
								datos.append(file.name,file );
						 
						}
						///*********************************************************************+
						 $.ajax({
											 url: "script_gastos/gast_uptfileGasto.php",
											 type: "POST",
											 data:datos ,
											contentType:false, //Debe estar en false para que pase el objeto sin procesar
											processData:false, //Debe estar en false para que JQuery no procese los datos a enviar
											cache:false, //Para que el formulario no guarde cache
										 success: function (datos) {
												/// console.log(datos.msg +"*******"+ datos.ruta)
												 if(datos.estado == 0)
												 {
													
														ShowUpElments("Lo Sentimos Ocurrio un Error !!! Al momento de Subir Archivos")
														 
												 }else{
														 
													 /* $("#mensERROR").text("Exito !!! ");
														 $("#msn001").text("Se Guardo Correctamente la Imagen");
														 $("#ModalMNs").modal("show");
													*/
												 window.setInterval(ReFrESHpA(),190000);
													 ExitoTOsAVE();
														/*Forzamos la recarga*/
													///  location.reload(true); 
												 }
						
										 } 
						 });
						///**************************************************
	}
//////***Validamos  Informacion para  DespUES  pROCEDER  A  aGREGAR   
	function   Valir_InformacionGasto()
	{     
			tosendInfo = null;
			var  XmlExistPdf = ExistPDF_AND_XML();
			////**Obtenemos  la  Informacion
			var  GetINFO=GetInformacion(XmlExistPdf);
			
			////***Revisamos que  Existam  Archivos  Xml y PDF  
			if(XmlExistPdf.ResFinal==false && GetINFO.MainInfoSend.concepto !=106  )
			{
				 ////****Mandamo  Mensaje de Alerta   No Existe Archivo XML
				 ShowErro("Lo Sentimos No Existe Archivo Xml o Pdf Imposible Guardar")
			}else{
					
							///****Revisamos si  no Existen  Errores  en  captura
								if(GetINFO.ResultCap.AllEle == false)
								{
								 ////**Existen  Errores
								 ShowErro( GetINFO.ResultCap.htmlerror)
								}else{
									///***Iniciamos  Porcedimiento  de Envio  de Informacion 
									tosendInfo = GetINFO.MainInfoSend;
									SaveForm(GetINFO.MainInfoSend.facturaNum)
								}
			}
			
			
	}
 ////*******Mostrar  MenSAGE DE Error  
 function ShowErro(txt){
			$("#cabecssMy").css("background-color","#f00c" );
			$("#titleInformaci").html("Error en Gasto");
			$("#contSaveInfo").attr("hidden",true);
			$("#nfMen").html(txt)
			$("#ModalMNs").modal("show");
			
	}
	function ShowUpElments(txt){
			$("#cabecssMy").css("background-color","#f00c" );
			$("#titleInformaci").html("Error en Gasto");
			$("#contSaveInfo").attr("hidden",true);
			$("#nfMen").html(txt)
			$("#ModalMNs").modal("show");
			
	}
	/*
		.errost.modal-header 
		.saveelem.modal-header */
	////Funcion  para  ostrar  Dialog Forma  sAVE 
	function  SaveForm(Folio)
	{
		 $("#cabecssMy").css("background-color","#1db46fcc" );
			$("#titleInformaci").html("Guardar Elementos");
			$("#contSaveInfo").attr("hidden",false);
			$("#nfMen").html("Esta a punto de Guardar el Gasto con Folio:<strong>"+Folio+"</strong>")
			$("#ModalMNs").modal("show");
	
	}
	function  ExitoTOsAVE()
	{
			$("#cabecssMy").css("background-color","#1db46fcc" );
			$("#titleInformaci").html("Exito");
			$("#contSaveInfo").attr("hidden",true);
			$("#contCancela").empty();
			 $("#contCancela").html("<button  type='button' data-dismiss='modal' class='btn  btn-info' onclick='ReFrESHpA()'    >Terminar</button>");
			$("#nfMen").html("El Gasto se Guardo Correctamente Espere que se recargue la pagina")
			$("#ModalMNs").modal("show");
	}
	////**Intervalo para Recargar la Pagina 
	function ReFrESHpA()
	{
		 window.open(location.reload(true)) 
	}
	////****Funcion para   Buscar  XML 
	function  ExistXML()
	{
				var Result  = {"ExisXML":false,"XMLELM": new  FormData()} 
						var archi =  document.getElementById("filArch");
						var   datos =  new  FormData();
						for(var i =0 ;i< archi.files.length ;i++  )
						{
								var file  = archi.files[i];
							 /// datos.append("ARCH"+i,file );
								var  tipoArchivo  = {"id":i ,"ARCH": file.type.split('/').pop() }; 
								if(tipoArchivo.ARCH.localeCompare("xml") == 0){
												
											 Result.ExisXML = true;
											 Result.XMLELM = file;
								}
								
						
						}
						
			return  Result; 
			
	}
	///****Funcion para  Validar que  Existe  XML Y PDF  Cargados  
	function  ExistPDF_AND_XML()
	{
				var Result  = {"ExisXML":false,"ExisPDF":false} 
				var finalRs = {"ResFinal": false, "name_xml":"", "name_pdf":"","ExisPDF":false}  
						var archi =  document.getElementById("filArch");
						var   datos =  new  FormData();
						for(var i =0 ;i< archi.files.length ;i++  )
						{
								var file  = archi.files[i];
							 /// datos.append("ARCH"+i,file );
								var  tipoArchivo  = {"id":i ,"ARCH": file.type.split('/').pop() }; 
								if(tipoArchivo.ARCH.localeCompare("xml") == 0){
											 Result.ExisXML = true;
											 finalRs.name_xml =file.name;
								}
								if(tipoArchivo.ARCH.localeCompare("pdf") == 0){
											 finalRs.ExisPDF = true;
											 finalRs.name_pdf =file.name;
								}
								
						
						}
						
						if(Result.ExisXML == true )///&& Result.ExisPDF == true )
						{
								finalRs.ResFinal = true; 
						}
						
						
			return  finalRs; 
			
	}
	
	
	///****Function Get Elementos  Form  
	function   GetInformacion(InfoFile){

			var TotalFIN = Mayfor0(parseFloat($("#gastotal").val().replace(",","")))
			var Ivaresu = 0 ;
			/*
			if($("#optionConcpto option:selected").val() != 106 )
							{
							 Ivaresu= Mayfor0(parseFloat($("#tasaivaporcent").val().replace(",","")))   
							}
								*/
			var InforToSend = {
				 ////****Obtenemos  Informacion del  Gasto 
					"numage" :NUMAG,
					"facturaNum" : IsEmpty($("#facturnum").val()) ,
					"capdate" :GetFecha() ,
					"concepto": $("#optionConcpto option:selected").val() ,
					"nomconcepto":$("#optionConcpto option:selected").text() ,
				 
					"tasaIVApor": $("#selecIvAoPTION option:selected").val() ,
					"subTotal": Mayfor0(parseFloat($("#subtotl").val().replace(",",""))) ,
					"total":TotalFIN  ,
					"IVA":Mayfor0(parseFloat($("#tasaivaporcent").val().replace(",",""))), 
							
					"comen": IsEmpty($("#comentTXT").val()),
				 ///****Obtenemos  el  Nombre de los  Archivos 
					"nom_xml" :InfoFile.name_xml , 
					"nom_pdf" :InfoFile.name_pdf ,
					"existPdf":InfoFile.ExisPDF 
					
			}
	 
		return  {  "ResultCap":TypeErrorCapt(InforToSend), "MainInfoSend": InforToSend } 
	}
	///****Validar que no false  la Informacion 
	function   TypeErrorCapt(InformJSON)
	{
			 ///***Json de Respuesta
			 var   JsonResult = {
												 ///***Variable   Determina Si los  Elemntos  capturados  todos  son True 
														"AllEle":false,
														"typeErro" : new  Array(),
														"htmlerror" : ""
												}
												
			 if(InformJSON.facturaNum  == false ){
					 JsonResult.typeErro.push("Erro No Agrego el Folio");
					 JsonResult.htmlerror += "* Erro No Agrego el Folio<br>" 
			 }
			 if(InformJSON.subTotal == false ){
						JsonResult.typeErro.push("Erro No Agrego el Sub Total");
						 JsonResult.htmlerror += "*Erro No Agrego el Sub Total<br>" 
			 }
			 if(InformJSON.total == false ){
					 JsonResult.typeErro.push("Erro No Agrego el Total");
					 JsonResult.htmlerror += "*Erro No Agrego el Total<br>" 
			 }
			 if(InformJSON.IVA == false  ){
					 JsonResult.typeErro.push("Erro No Agrego el IVA");
					 JsonResult.htmlerror += "*Erro No Agrego el IVA<br>" 
			 }
			 if(InformJSON.comen == false ){
					 JsonResult.typeErro.push("Erro No Agrego el Comentario");
					 JsonResult.htmlerror += "*Erro No Agrego el Comentario<br>" 
			 }
			 if(InformJSON.concepto == "0" ){
					 JsonResult.typeErro.push("Erro No Agrego Concepto");
					 JsonResult.htmlerror += "*Erro No Agrego Concepto<br>" 
			 }
			 if(InformJSON.facturaNum != false &&
					InformJSON.subTotal != false &&
					InformJSON.total != false &&
					
					InformJSON.comen != false &&
					InformJSON.concepto !="0")
				{
					 JsonResult.AllEle = true;
				}
			 
		 return   JsonResult;
	}
	
	
	///Validadar Fecha 
	function GetFecha(){
		var  respu =  false;

		if(InputDate_enable()== true)
		{
				if(ValidarFecha($("#fech").val())===true)
					{
						respu =  convertoTODB($("#fech").val());
					}

		}else {

				respu =$("#fech").val(); 

		}

		return  respu;      
 } 
 ////***Validamos  que Exista  mayor   A 0
 function Mayfor0(value)
 { 
		 var  respu =  false;
	 if(value >= 0 && value!="")
	 {
			respu = value; 
			 
	 }    
	 return  respu;  
 }
 ///***rEVISAMOS QUE NO SEA  EMPTY  LOS COMENTARIOS 
 function  IsEmpty(value)
 {
		 var  result= value; 
	if(value=="" || value == null || value.length == 0)
	{
		 result =false; 
	}    
		 return   result;
 }
///************************************************************************************
$("#subtotl").on({
								"focus": function (event) {
										$(event.target).select();
								},
								"keyup": function (event) {
										$(event.target).val(function (index, value ) {
												return value.replace(/\D/g, "")
																		.replace(/([0-9])([0-9]{2})$/, '$1.$2')
																		.replace(/\B(?=(\d{3})+(?!\d)\.?)/g, ",");
										});
								}
 });
$("#tasaivaporcent").on({
								"focus": function (event) {
										$(event.target).select();
								},
								"keyup": function (event) {
										$(event.target).val(function (index, value ) {
												return value.replace(/\D/g, "")
																		.replace(/([0-9])([0-9]{2})$/, '$1.$2')
																		.replace(/\B(?=(\d{3})+(?!\d)\.?)/g, ",");
										});
								}
 });/*
$("#gastotal").on({
								"focus": function (event) {
										$(event.target).select();
								},
								"keyup": function (event) {
										$(event.target).val(function (index, value ) {
												return value.replace(/\D/g, "")
																		.replace(/([0-9])([0-9]{2})$/, '$1.$2')
																		.replace(/\B(?=(\d{3})+(?!\d)\.?)/g, ",");
										});
								}
 });*/
 $("#subtotl").keyup(function(){
     $("#gastotal").val(0);
      var   suma  = parseFloat($(this).val().replace(",","")) +parseFloat($("#tasaivaporcent").val().replace(",","")); 
      
      
      console.log(suma); 
      $("#gastotal").val(suma);
 });
$("#tasaivaporcent").keyup(function(){
     $("#gastotal").val(0);
      var   suma  =parseFloat($(this).val().replace(",","")) + parseFloat($("#subtotl").val().replace(",",""));  
 
      console.log(suma); 
       $("#gastotal").val(suma);
       
     
 });
 
 //************************************************************************************ 
////*****Convertir a  Formato  Moneda  currency(ArglMain[i].CMG, 2, [',', ",", '.'])
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
});
</script>
</div>
<style>
		
.dumdivstyle.jumbotron {
		/*background: #000;*/
		height: 400px;
	 background-image:url('images/IMGJUMBO.jpg');
	 position: relative;
	 top:-17px;
	 box-shadow: -2px 11px 10px #999; 
}
.ConFrm {
		background: #fff;
		height: 500px;
		position: relative;
		top: -250px;
}
.ContInputs{
		 background: #fffdfde6;
		 height:auto;/*500px;*/
		 top :50px;
		 border-radius: 24px;
		 box-shadow: -5px 7px 57px #999
		
}


.ldtrans{
		
		opacity: 0.5;
}
.contTITLE{
		position :absolute;
		top:36px;
		left:-217px
}
.titlemain{
		font-size: 50px; 
		color:white;
}
.contnfileFinal {
	 margin-top: 50px;
	margin-bottom: 50px;
}
.btn-file {
	position: relative;
	overflow: hidden;
	}
.btn-file input[type=file] {   
		position: absolute;
		top: 0;
		right: 0;
		min-width: 100%;
		min-height: 100%;
		font-size: 100px;
		text-align: right;
		filter: alpha(opacity=0);
		opacity: 0;
		outline: none;
		background: white;
		cursor: inherit;
		display: block;
		background-image:url('images/uppercase.png');
}
/*.txtcoment{
		overflow: hidden;
}*/
.moneyapli{
	-moz-appearance: textfield;
	text-align: end;   
}
.errost.modal-header {
		background-color: #f00c;
		box-shadow: 5px 2px 4px;
}
.saveelem.modal-header { 
				background-color: #1db46fcc;
				box-shadow: 5px 4px 4px;
}

.titleerr {
		font-size: xx-large;
		color: white;
}
</style>
<div class="dumdivstyle  jumbotron">
	<!-- <image  src="image</IMGJUMBO.jpg"  >--> 
		<form  id="mainform">  
				<div class="contTITLE  col-lg-12 col-sm-12  col-xs-12">
								<strong class="titlemain">Captura de Gastos</strong>
				</div> 
				<div class="  col-lg-12 col-sm-12  col-xs-12" >
				<div class="ldtrans col-lg-3 col-sm-3 col-xs-12" ></div>
				<div class="ContInputs  col-lg-6 col-sm-6 col-xs-12" >
					<!--**********Inicio  Contenedor Informacion dentro del Recuadro  blanco***********<?php /// $dt = new DateTime();  echo $dt->format("d/m/Y") ;  ?>***********-->
						<div class="col-lg-12 col-sm-12 col-xs-12"> 
						<div  class="col-lg-4 col-sm-12 col-xs-12">
								<h4>Folio</h4>
								<input  type="text"  id="facturnum"   class="moneyapli form-control">
						</div>
								<div  class="col-lg-4 col-sm-2 col-xs-12"></div>
						<div  class="col-lg-4 col-sm-12 col-xs-12"> 
								<h4>Fecha</h4>
								<input  type="date" id="fech" value="" class="form-control"> 
						</div>
						</div>
					<div class="row"> </div>
						<div class="col-lg-12 col-sm-12 col-xs-12">
								<div class="col-lg-10 col-sm-12 col-xs-12">
										<h5>Concepto</h5>
										<select  id="optionConcpto" name="[concepto]" class="form-control">
												<option   value="0" >Seleccione---Concepto--</option>
												 <?php while($row= mysqli_fetch_array($consulta))
												{
														echo '<option value="'.$row['id'].'">'.$row['nombre'].'</option>';
												}
												?>    
										</select>
								</div>
								<div class="col-lg-2 col-sm-12 col-xs-12"></div>
						</div>

						<div class="col-lg-12 col-sm-12 col-xs-12">
								<div class="col-lg-5 col-sm-5 col-xs-12">
												<label><strong>Tasa IVA%</strong></label>
												<div class="contyiva"> 
														<select   id="selecIvAoPTION"    class="form-control"> 
																<option   value="16">16</option>
																<option   value="0">0</option>
														</select>
												</div>

								</div>
								<div class="col-lg-1 col-sm-1 col-xs-12"></div>
								<div class="col-lg-5 col-sm-5 col-xs-12">
										<label><strong>Sub Total</strong></label>
										<input id="subtotl" step='any' type="text" min="0" value="0" class="moneyapli form-control">

								</div>
						</div>
						<div class="col-lg-12 col-sm-12 col-xs-12">
								<div class="col-lg-5 col-sm-12 col-xs-12">
										 <label><strong>Iva $</strong></label>
										 <input id="tasaivaporcent"  step='any' type="text" min="0" value="0" class="moneyapli form-control"  required >					
										</div>
								<div class="col-lg-1 col-sm-1 col-xs-1"></div>
								<div class="col-lg-5 col-sm-12 col-xs-12">
												 <label><strong>Total</strong></label>
												<input id="gastotal" type="text" step='any' class="moneyapli form-control">
								</div>
						</div>
						 <div class="col-lg-12 col-sm-12 col-xs-12">
								 <label><strong>Comentarios</strong></label>
								 <textarea  id="comentTXT"  value="" class="txtcoment form-control"></textarea>
						</div>
						<div class="contnfileFinal  col-lg-12 col-sm-12 col-xs-12">
								<div class=" col-lg-12 col-sm-12 col-xs-12">
										 <div class=" col-lg-8 col-sm-12 col-xs-12">
												<input id="filArch" multiple="multiple"  type="file"  class="form-control">
										 </div>
										<div class=" col-lg-4 col-sm-12 col-xs-12">
											 <!-- <button id="readXML" type="button"  class="btn btn-info"   >Leer XML</button>-->
										 </div>
								</div>
								<div class="contnfileFinal col-lg-12 col-sm-12 col-xs-12">
										<button type="button"  id="BTNSAVE" class="btn  btn-info btn-lg">Guardar</button> 
								</div>

						</div> 

						</div>

					 <!--**********Fin Contenedor Informacion dentro del Recuadro  blanco**********************-->
				</div>
				<div class="ldtrans col-lg-3 col-sm-3 col-xs-12" ></div>
		</form>
		
		<!---Modal  Mensages----->    
		 <div  class="modal fade" id="ModalMNs" role="dialog">
				<div  class="modal-dialog">

					<!-- Modal content-->
					<div  class="modal-content">
						<div id="cabecssMy" class="errost modal-header"  >
								<button type="button" id=""  class="close_coment close" data-dismiss="modal">&times;</button>
								<h5  id="titleInformaci"  class="titleerr" ><h5>
						</div>
						<div class="modal-body">
			
								<div class="form-group">
										<div id="CONTMOD" class="row" class="well">
												<div class="col-sm-1"></div>
												<div class="col-sm-10"><strong id="nfMen">  </strong></div>
												<div class="col-sm-1"></div>
												
										</div>
										 <div    class="row" class="well">
												
														<div class="col-sm-4" ></div>
														<div class="col-sm-1" id="contSaveInfo" hidden ><button  type="button" id="btnSave"  class="btn btn-success"> Guardar</button></div>
														<div class="col-sm-2" ></div>
														<div class="col-sm-1" id="contCancela"  ><button  type="button" data-dismiss="modal" class="btn  btn-danger">  Cancelar</button></div>
														<div class="col-sm-4" ></div>
												
										</div>
										<div  class="row" class="well">
												
														<div class="col-sm-4" ></div>
														<div class="col-sm-1" ></div>
														<div class="col-sm-2" ></div>
														<div class="col-sm-1" ></div>
														<div class="col-sm-4" ></div>
												
										</div>
								</div>
						</div>
						<div class="modal-footer">
						<!--  <button type="button" id="btn_update_comentarios"  class="btn btn-info" data-dismiss="modal"> <span class="glyphicon glyphicon-floppy-saved"></span> Guardar</button> -->
						</div>
					</div>

				</div>
			</div>
		<!------------------->  
		
		
		
</div>
</div>

<?php   
///****Agregamos  fOOT
require_once('foot.php');
?> 


