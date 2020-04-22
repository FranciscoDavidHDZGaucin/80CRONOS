<?php
////***pub_addprodcatalogo.php 
/*
********	 INFORMACION ARCHIVO ***************** 
	Nombre  Archivo : pub_addprodcatalogo.php 
 	Fecha  Creacion : 08/06/2017
	Descripcion  : 
 *              Escrip Encargado De Dar de Alta Modificar o Eliminar
 * 
 *              
  */
  require_once 'header_asisIC.php';
////**Inicio De Session 
	session_start();
///*****Formato de  Datos          
require_once('formato_datos.php');   //para evitar poner la funcion de conversion de tipos de datos

?> 
<!--JQuery jquery.min.js" -->
<script src="bower_components/jquery/dist/jquery.min.js"></script>
<script type="text/javascript">
   
   ///***Objeto  Producto 
    function ObjProd(cve_prod,nom_prod,PU,Cant,DES,img)
    {
        this.cve_prod = cve_prod;
        this.nom_prod =nom_prod;
        this.PU = PU;
        this.Cant =Cant;
        var  Pretotal = Cant * PU
        this.preTotal = Pretotal.toFixed(2);
        this.DES =DES;
        this.imgProd=img;
       
    }
    ////***Agreglo Main 
    var  AreCatProd=   new  Array(); 
    
    ////***Function  Para Obtener el elemento  por clave del
    function  GetProd(cvePrd)
    {
        var  OBJ;
       for(var i  in AreCatProd )
       {
         if( AreCatProd[i].cve_prod.localeCompare(cvePrd) == 0)
         {
            OBJ=AreCatProd[i];
            break;
	 }   
       }   
       return   OBJ;
    }
    
    
</script> 
<script type="text/javascript">
$(document).ready(function(){
     ////***Seccion  para  Desabilitar  
     $("#Modificar").hide();
    ////****Funcion para capturar la informacion  
    function  NewObjPro()
    {
        return   new  ObjProd(
                   $("#cvPr").val(), ///Clave Producto
                   $("#moPr").val(), ///Nombre Producto
                   $("#preUni").val(), ///Pu
                   $("#cant").val(), ///Cantidad 
                   $("#txtDes").val(),///Descripcion
                   "" ///Imagen       
                );
    }
    ///Btn  para Cargar la Imagen
        $('#btnIMG').change(function(e) {
                addImage(e); 
        });
    ///Btn para  Cargar Mandar Agregar 
        $("#CapturarProd").click(function(){
                var Obj = NewObjPro();
           /// console.log(Obj);
            ////************************************************
           if(Obj.cve_prod.length == 0 ||Obj.cve_prod.length < 6 )
           {
                ///****No selecciono  Ningun Archivo  
                  $("#msn001").text("La Clave del Producto No Debe Estar  Vacía O Es menor de 6 Caracteres");
                   $("#ModalMNs").modal("show");
           }else{
            ////****Validamos   si esta  vacio  el  file 
              if($('#btnIMG').get(0).files.length === 0)
              {
                  ///****No selecciono  Ningun Archivo  
                  $("#msn001").text("Lo Sentimos no ha  agregado Ningun Archivo");
                   $("#ModalMNs").modal("show");
              }else{
                    ///******Obtenemos el Archivo
                      var archi =  document.getElementById('btnIMG');
                      var   file = archi.files[0];
                      var   datos =  new  FormData();
                      datos.append("ARCH",file );
                      datos.append("OBJ",JSON.stringify(Obj));
                      var formato = file.type.split('/').pop();
                  ///****Peticion Ajax  
                  $.ajax({
                             type: 'POST',
                              url:   'pub_scrip_publicidad/pub_addcatalogoprod.php',
                              data: { "OBJ": JSON.stringify(Obj),"FOR":formato,"ACCI":1},
                              success: function (respuesta) {
                                 
                                  if(respuesta.Res001==1)
                                  {
                                      Send_File(datos)
                                  }else{
                                        if(respuesta.Res001 == 2)
                                        {
                                              $("#msn001").text("El Archivo Debe ser  .jpg , .png");
                                             $("#ModalMNs").modal("show");
                                        }else {
                                             if(respuesta.Res001 == 3)
                                                {
                                                      $("#msn001").text("La Clave ya Existe  No se puede Agregar");
                                                     $("#ModalMNs").modal("show");
                                                }
                                            
                                        }
                                  }
                              } 
                          });
                   } ///***Fin Else  de  Validacion
                 }
         ////************************************************
        });
   ////****Funcion para enviar  elementos
   function  Send_File(datos)
   {
            ///*********************************************************************+
             $.ajax({
                       url: "pub_scrip_publicidad/pub_subirImgprod.php",
                       type: "POST",
                       data:datos ,
                      contentType:false, //Debe estar en false para que pase el objeto sin procesar
                      processData:false, //Debe estar en false para que JQuery no procese los datos a enviar
                      cache:false, //Para que el formulario no guarde cache
                     success: function (datos) {
                        /// console.log(datos.msg +"*******"+ datos.ruta)
                         if(datos.estado == 0)
                         {
                             $("#msn001").text("El Archivo Debe ser  .jpg , .png");
                             $("#ModalMNs").modal("show");
                         }else{
                            $("#mensERROR").text("Exito !!! ");
                             $("#msn001").text("Se Guardo Correctamente la Imagen");
                             $("#ModalMNs").modal("show");
                             GeTable()
                             EmptyElem()
                             
                         }
            
                     } 
             });
            ///**************************************************
  }
   ////********************************
        
/////////**************************************************
     function addImage(e){
      var file = e.target.files[0],
      imageType = /image.*/;
    
      if (!file.type.match(imageType))
       return;
      var reader = new FileReader();
      reader.onload = fileOnload;
      reader.readAsDataURL(file);
     }
  
     function fileOnload(e) {
        var result=e.target.result;
        var img = new Image();
        img.src = result;
        img.onload =  function () {
              
            if(img.width==220 && img.height==220)
            {
                $('#imgSalida').attr("src",result);  
            }else{
                  $("#msn001").text("La Imagen no Tiene las  Dimensiones Correctas.Debe tener una Dimension de 220x220 ");
                 $("#ModalMNs").modal("show");
                 $('#imgSalida').attr("src","images/warniGif.gif");  
            }
        }
      }
///////////******************************************************
function  GeTable()
{
    ///****Peticion Ajax  
                  $.ajax({
                             type: 'POST',
                              url:   'pub_scrip_publicidad/pub_GetableCatProd.php',
                              success: function (respuesta) {
                                 var AregloProd =  JSON.parse(respuesta.allelem);
                                 var  tableTr ="";
                                 AreCatProd =  new Array();
                                 
                                 for(var  i  in AregloProd )
                                 {
                                     
                                      var vst = "";
                                     if(AregloProd[i].IMG != "" ){
                                                 vst = '<a href="#"  data-toggle="tooltip" title="Producto con Imagen!"  ><span class="glyphicon glyphicon-ok"></span></a>' ;
                                     }    
                                    AreCatProd.push( new ObjProd(AregloProd[i].cveProd,AregloProd[i].nomProd,AregloProd[i].PU,AregloProd[i].canti,AregloProd[i].Descrip,AregloProd[i].IMG)) 
                                    tableTr += "<tr><td>"+AregloProd[i].cveProd+vst+"</td><td>"+AregloProd[i].nomProd+"</td><td>"+AregloProd[i].PU
                                            +"</td><td>"+ AregloProd[i].canti+"</td><td>"+AregloProd[i].PreTotl
                                            +"</td><td type='button'  class='updctprod btn btn-success  ' cvpro='"+AregloProd[i].cveProd+"'><span class='glyphicon glyphicon-edit'></span>"
                                            +"</td><td type='button'  class='desbtn btn btn-info  ' cvDes='"+AregloProd[i].cveProd+"'><span class='glyphicon glyphicon-search'></span></td>"
                                            +"<td class='delPrdo btn btn-danger' type='button' elcveprod='"+AregloProd[i].cveProd+"'> <span class='glyphicon glyphicon-trash'></span></td></tr>" 
                                     
                                     
                                 }
                                
                               
                                $("#tbCont").html(tableTr);
                              } 
                          });
    
    
    
}
    ////**** Btn  Descripcion 
    $(document).on("click",".desbtn",function(){

                ///***Obtenemos la  clave a
                var  cvel =  $(this).attr('cvDes');
                var  ObjpROD =  GetProd(cvel);
                console.log(ObjpROD);
                $("#titleDes").text("Descripcion Producto  Clave:"+ObjpROD.cve_prod+"  Nombre:"+ObjpROD.nom_prod);
                $("#msnDes001").val(ObjpROD.DES);
                
                $("#menDescr").modal("show");

    });
    ////****Btn Modificar Un Elemento 
    $(document).on("click",".updctprod",function(){

                ///***Obtenemos la  clave a
                var  cvel =  $(this).attr('cvpro');
                var  ObjpROD =  GetProd(cvel);
              ///  console.log(ObjpROD);
         
                $("#cvPr").val(ObjpROD.cve_prod) ///Clave Producto
                $("#moPr").val(ObjpROD.nom_prod) ///Nombre Producto
                $("#preUni").val(ObjpROD.PU) ///Pu
                $("#cant").val(ObjpROD.Cant) ///Cantidad 
                $("#txtDes").val(ObjpROD.DES)
                $("#ptl").val(ObjpROD.preTotal)
                ////**Mostramos  la Imagen
                $('#imgSalida').attr("src","pub_catalogo/"+ObjpROD.imgProd);
                ////**Desabilitamos el  Btn de  Agregar
                 $("#cvPr").prop("disabled",true);
                $("#CapturarProd").hide()
                $("#Modificar").show();
                
    });
    ////***Btn para Eliminar un Producto 
    $(document).on("click",".delPrdo",function(){

                ///***Obtenemos la  clave a
                var  cvel =  $(this).attr('elcveprod');
                var  ObjpROD =  GetProd(cvel);
               $("#titleDELP").text("Esta a Punto de  Eliminar el  Producto con Clave:"+ObjpROD.cve_prod +"  y  Nombre:"+ObjpROD.nom_prod  );
               $("#conBtnDel").html('<button   cvePr= "'+cvel+'" type="button" id="delAcept"  data-dismiss="modal" class="delAcept btn btn-info"> Aceptar  <span class="glyphicon glyphicon-pencil"></span></button>');
               $("#menDelPROD").modal("show"); 
                
                
    });
    ////*Calcular Precio  Total   
    $("#cant").keyup(function(){
      
      var  pu =  $("#preUni").val();
      var  canti = $("#cant").val();
      if(pu>0  &&canti >0  ){ 
        var totl = (pu*canti).toFixed(2);
        $("#ptl").val(totl);
        }
    });
    
    ///****Boton de Elimnar   Elementos CVE
  $(document).on("click",".delAcept",function(){
        var   cveElemento =   $(this).attr("cvePr");
        console.log(cveElemento)
         ///****Peticion Ajax  
                  $.ajax({
                             type: 'POST',
                              url:   'pub_scrip_publicidad/pub_delprodcat.php',
                              data: { "CVE": cveElemento},
                              success: function (respuesta) {
                                    GeTable();
                                  
                              } 
                          });
        
        
    });
   ////** Btn Para Abrir  Opciones de   Modificar  
    $("#Modificar").click(function(){
        
         ////****Validamos   si esta  vacio  el  file 
              if($('#btnIMG').get(0).files.length === 0)
              {
                 ////***Modificamos  solo  la  Informacion pub_updateCatProd.php
                 $("#menUpdateProd").modal("show");
                 $("#titleUpdate").text("Esta a Punto  de Modificar la Informacion del Producto");
                 
              }else{
                  $("#menUpdateProd").modal("show");
                 $("#titleUpdate").text("Esta a Punto  de Modificar la Informacion e Imagen.Este Procedimiento Eliminara la Imagen Anterior ");
                 
                   } ///***Fin Else  de  Validacion
        
    });
    ///***Btn Aceptar  Modificar
    $("#updateAcept").click(function(){
         ////***Capturamos el Objeto  A Modificar 
         var Obj = NewObjPro();
         ////****Validamos   si esta  vacio  el  file 
              if($('#btnIMG').get(0).files.length === 0)
              {
                 ////***Modificamos  solo  la  Informacion pub_updateCatProd.php
                 
                  ///****Peticion Ajax  
                  $.ajax({
                             type: 'POST',
                              url:   'pub_scrip_publicidad/pub_updateCatProd.php',
                              data: { "OBJ": JSON.stringify(Obj)},
                              success: function (respuesta) {
                                    GeTable();
                                    
                                    ///**Desabilitamos el  Btn de  Agregar
                                    EmptyElem()
                                    $("#cvPr").prop("disabled",false);
                                   $("#CapturarProd").show();
                                   $("#Modificar").hide();
                                   

                              } 
                          });
                 
              }else{
                  
                  ////**Modificamos   Tambien  el  Archivo 
                    ///******Obtenemos el Archivo
                      var archi =  document.getElementById('btnIMG');
                      var   file = archi.files[0];
                      var   datos =  new  FormData();
                      datos.append("ARCH",file );
                      datos.append("OBJ",JSON.stringify(Obj));
                      var formato = file.type.split('/').pop();
                  ///****Peticion Ajax  
                  $.ajax({
                             type: 'POST',
                              url:   'pub_scrip_publicidad/pub_addcatalogoprod.php',
                              data: { "OBJ": JSON.stringify(Obj),"FOR":formato,"ACCI":2},
                              success: function (respuesta) {
                                 
                                  if(respuesta.Res001==1)
                                  { 
                                      ///**Desabilitamos el  Btn de  Agregar
                                    EmptyElem()
                                    $("#cvPr").prop("disabled",false);
                                   $("#CapturarProd").show();
                                   $("#Modificar").hide();
                                      Send_File(datos)
                                      
                                  }else{
                                        if(respuesta.Res001 == 2)
                                        {
                                              $("#msn001").text("El Archivo Debe ser  .jpg , .png");
                                             $("#ModalMNs").modal("show");
                                        }
                                  }
                              } 
                          });
                   } ///***Fin Else  de  Validacion
        
        
        
    });
    ////***Funcion a  Cero 
    function  EmptyElem(){
                $("#cvPr").val("") ///Clave Producto
                $("#moPr").val("") ///Nombre Producto
                $("#preUni").val(0.00) ///Pu
                $("#cant").val(0) ///Cantidad 
                $("#txtDes").val("")
                $("#ptl").val(0.00)
                ////**Mostramos  la Imagen
                $('#imgSalida').attr("src","images/warniGif.gif");
                $("#btnIMG").replaceWith($("#btnIMG").val('').clone(true));
    }
    
   ////***Evento para  Detectar que  la Clave del Producto tenga  espacios 
    $("#cvPr").keyup(function(){
        
        
         if (/\s/.test($(this).val())) { 
             ////****Se  Detectaron Espacios 
              $("#CapturarProd").attr('disabled',true);  
              $("#ModalMNs").modal("show");
              $("#msn001").text("La clave del Producto  NO Puede  Contener Espacios");
        }else{
            $("#CapturarProd").attr('disabled',false);
        }
        
    });
    
////*Obtenemos  la  tabla ***
GeTable()
////**Activamos  Tooltip
$('[data-toggle="tooltip"]').tooltip();  

});
</script>
<style>
    #imgSalida {
    border: 2px solid #73AD21;
    position: relative;
    top: 9px;
    left: 80px;
}

    div#contallImg {
    border: 2px solid #177323;
    border-radius: 25px;
}
 thead, tbody { display: block; }

tbody {
    height: 500px;       /* Just for the demo          */
    overflow-y: auto;    /* Trigger vertical scroll    */
   
}
th,td{
        max-width: 10.5vw;
    min-width: 10.5vw;
}
td.updctprod.btn.btn-success {
    height: 41px;
    min-width: 93px;
  
}
td.btn.btn-danger {
       height: 41px;
    min-width: 93px;
 
} 
td.desbtn.btn.btn-info {
       height: 41px;
    min-width: 93px;
}
.ModTb,.DesTb,ElTb{
    height: 41px;
    min-width: 93px;
}
.contnfileFinal {
   margin-top: 50px;
  margin-bottom: 50px;
}


</style>
<div  class="container">
    
    <div class="row">
          
           <div class="col-sm-5"></div>  
           <div class="col-sm-3"><button type="button" class="btn btn-info" data-toggle="collapse" data-target="#collapNewProd" >Agregar Producto</button></div> 
           <div class="col-sm-1"></div> 
           <div class="col-sm-3"><button type="button" class="btn btn-info" data-toggle="collapse" data-target="#btnColapseListProd" > Lista Productos</button></div>
    </div>
    <div id="collapNewProd" class=" col-lg-12 col-sm-12">
        <div class="row">
            <h2>Nuevo Producto</h2>
        </div>
        <div id="contallImg"  class="col-lg-4 col-sm-4 ">
            <div class="row"><h6>Imagen para el Producto :</h6></div>
            <div  class="contImgPrin row" >
                <img id="imgSalida"   src="images/warniGif.gif" />
            </div>
            <br>
            <div class="row" >
               
                <input   type="file" id="btnIMG" class="btn btn-success"> 
               
            </div>
            
           
        </div>
        <div class="col-lg-8 col-sm-8">
            <div class="row">
                <div class="col-sm-1"><label><strong>Clave:</strong></label></div> 
                <div class="col-sm-5"><input type="text" class="form-control" id="cvPr" >  </div>
                <div class="col-sm-1"><label><strong>Nombre:</strong></label></div> 
                <div class="col-sm-5"><input type="text" class="form-control" id="moPr" >  </div>
           </div>
            <br>
             <div class="row">
                <div class="col-sm-3"><label><strong>Precio Unitario:</strong></label></div> 
                <div class="col-sm-3"> <input type="number" class=" form-control" id="preUni" >   </div>
                <div class="col-sm-1"><label><strong>Cantidad</strong></label></div> 
                <div class="col-sm-2"> <input type="text" class="prtl form-control" id="cant"> </div>
           </div>
            <br>
             <div class="row">
                <div class="col-sm-2"><label><strong>Precio Total</strong></label></div> 
                <div class="col-sm-3"><input id="ptl" disabled type="number" class="form-control">  </div>
                <div class="col-sm-2"></div>
                <div class="col-sm-4"><button   type="button" id="CapturarProd" class="btn btn-success">Agregar</button>
                
                <button   type="button" id="Modificar" class="btn btn-success">Modificar</button>
                </div>
                
           </div>
            <br>
            <div class="row"> 
            <div class="col-sm-2"><label><strong>Descripcion:</strong></div>
                <div class="col-sm-10"><textarea   type="textarea" class="form-control" id="txtDes"> </textarea></div>
            
            </div>
             
       
           
        </div>
    </div>
    <br>
    <br>
    <br>
    <div id="btnColapseListProd" class="col-lg-12 col-sm-12 collapse">
        <div  class="row" >
            <h3>Lista Productos</h3>  
        </div>
        <div  class="row">
            <table class="table ">
                <thead>
                <th>Clave</th>
                <th>Nombre</th>
                <th>PU</th>
                <th>Cantidad</th> 
                <th>Precio Total</th>
                <th class="ModTb">Modificar</th><!--Modificar-->
                <th class="DesTb">Descripcion</th><!--Mostrar Descripcion-->
                <th class="ElTb">Eliminar</th><!--Eliminar--> 
                </thead>
                <tbody id="tbCont" >

                </tbody>
            </table>
       </div>     
        
    </div> 
    
    <!---Modal  Mensages----->    
     <div  class="modal fade" id="ModalMNs" role="dialog">
        <div  class="modal-dialog">

          <!-- Modal content-->
          <div  class="modal-content">
            <div class="modal-header">
                   <button type="button" id="close_coment"  class="close_coment close" data-dismiss="modal">&times;</button>
          
                <H3 id="mensERROR">Error !!! </h3>
            </div>
            <div class="modal-body">
      
                <div class="form-group">
                    <div  class="row" class="well">
                        
                            <div class="col-sm-2" ></div>
                            <div class="col-sm-8" ><h5 id="msn001" >La Imagen no Tiene las  Dimensiones Correctas.Debe tener una Dimension de 220x220 </h5></div>
                            <div class="col-sm-2" ></div>
                        
                    </div>
                </div>
             
            </div>
         </div>
                
        </div>
      </div>
  <!-------------------> 
  <!---Modal Mostrar  ----->    
     <div  class="modal fade" id="menDescr" role="dialog">
        <div  class="modal-dialog">

          <!-- Modal content-->
          <div  class="modal-content">
            <div class="modal-header">
                   <button type="button" id=""  class="close_coment close" data-dismiss="modal">&times;</button>
          
                   <P><strong id="titleDes"> </strong></p>
            </div>
            <div class="modal-body">
      
                <div class="form-group">
                    <div  class="row" class="well">
                        
                            <div class="col-sm-2" ></div>
                            <div class="col-sm-8" ><textarea id="msnDes001" class="form-control" ></textarea></div>
                            <div class="col-sm-2" ></div>
                        
                    </div>
                </div>
             
            </div>
         </div>
                
        </div>
      </div>
  <!-------------------> 
    <!---Modal Modificar Elementos   ----->    
     <div  class="modal fade" id="menUpdateProd" role="dialog">
        <div  class="modal-dialog">

          <!-- Modal content-->
          <div  class="modal-content">
            <div class="modal-header">
                   <button type="button" id=""  class="close_coment close" data-dismiss="modal">&times;</button>
                   <h4>Atencion !!! </h4>
                   <P><strong id="titleUpdate"></strong></p>
            </div>
            <div class="modal-body">
      
                <div class="form-group">
                    <div  class="row" class="well">
                        
                            <div class="col-sm-1" ></div>
                            <div class="col-sm-4" ><button   type="button" id="updateAcept"  data-dismiss="modal" class="btn btn-info"> Aceptar  <span class="glyphicon glyphicon-pencil"></span></button></div>
                            <div class="col-sm-2" ></div>
                            <div class="col-sm-4" ><button   type="button" id="updateCancel"  data-dismiss="modal" class="btn btn-danger"> Cancelar  <span class="glyphicon glyphicon-remove"></span></button></div>
                            <div class="col-sm-1" ></div>
                        
                    </div>
                </div>
             
            </div>
         </div>
                
        </div>
      </div>
  <!-------------------> 
  <!---Modal Eliminar   Elementos   ----->    
     <div  class="modal fade" id="menDelPROD" role="dialog">
        <div  class="modal-dialog">

          <!-- Modal content-->
          <div  class="modal-content">
            <div class="modal-header">
                   <button type="button" id=""  class="close_coment close" data-dismiss="modal">&times;</button>
                   <h4>Atencion !!! </h4>
                   <P><strong id="titleDELP"></strong></p>
            </div>
            <div class="modal-body">
      
                <div class="form-group">
                    <div  class="row" class="well">
                        
                            <div class="col-sm-1" ></div>
                            <div class="col-sm-4" id='conBtnDel'></div>
                            <div class="col-sm-2" ></div>
                            <div class="col-sm-4" ><button   type="button" id="delCancel"  data-dismiss="modal" class="btn btn-danger"> Cancelar  <span class="glyphicon glyphicon-remove"></span></button></div>
                            <div class="col-sm-1" ></div>
                        
                    </div>
                </div>
             
            </div>
         </div>
                
        </div>
      </div>
  <!------------------->   
    

</div>
<?php   
///****Agregamos  fOOT
require_once('foot.php');
?> 