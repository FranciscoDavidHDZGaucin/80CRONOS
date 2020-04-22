<?php
////***desv_agente.php 
/*
********	 INFORMACION ARCHIVO ***************** 
	Nombre  Archivo : desv_agente.php 
 	Fecha  Creacion : 28/06/2017
	Descripcion  : 
 *              Escrip  Diseñado  para Mostrar  las  Desviaciones    para   cada unos de los   agentes  
 *      Modificaion  : 
 *              27/07/2017  Modificacion de la consulta  para  obtener   desviaciones  en funcion de las 
 *                          fechas  inicio  y fin   obtenidas en formato  JSON 
 * 
  */

////**Inicio De Session 
session_start();
///****Cabecera Cronos 
require_once('header_gerentes.php');
require_once('formato_datos.php');   //para evitar poner la funcion de conversion de tipos de datos
///***Conexion Mysql  
require_once('Connections/conecta1.php');
////***Conexion   Sap 
require_once('conexion_sap/sap.php');
///***Seleccion de la Bd 
/// mssql_select_db("AGROVERSA"); 
/////****
 $EstatusQery  =  "Todo  Bien" ; 

$MesEnCurso =  date("m");
$YearCurso =  date("Y");

if($MesEnCurso ==1)
{
$MesMenos=12;    
}else{
$MesMenos  = $MesEnCurso -2 ; 
} 
$MesAnterior =  $YearCurso."-".$MesMenos."-1";
/***Codigo  desabilitado  por Modificacion  27/07/2017*************************************************************************************************/
////*** Obtenemos  la Informacion en  formato JSON  

 $oBJfEHCAS = json_decode(filter_input(INPUT_POST,'FechbS'));
 
 
$strGetDes = sprintf("SELECT * FROM pedidos.desv_desviaciones_8020  where  cve_agente =%s and  fech_Ini =%s and fech_fin=%s  and desvConts=0",
 GetSQLValueString($_SESSION['usuario_agente'], "int"), GetSQLValueString($oBJfEHCAS->feIni, "date"), GetSQLValueString($oBJfEHCAS->feFin, "date"));
/////**

$qery = mysqli_query($conecta1,$strGetDes );

if(!$qery)
{
   $EstatusQery  =  "Fail :( " ; 
    
}else {   
    $ArrayObj  =  Array();
    $fech_Ini ="00/00/00000";
    $fech_fin ="00/00/00000";
    while($row = mysqli_fetch_array($qery)){

      ////pRODUCTO  ,DEMMANDA ,VENTA REAL, LA VARIACION 
    /////cve_prod,porcent,variacion 
    ////****Obtenemos  la  Demanda   select  Get_Proyeccion(150  ,'BIO2005', '2017-03-31') as Demanda
           $string_getDemanda  = sprintf("select  Get_Proyeccion(%s ,%s, %s) as demanda",
                                    GetSQLValueString($_SESSION['usuario_agente'], "int"), 
                                    GetSQLValueString($row['cve_prod'], "text"),
                                    GetSQLValueString($oBJfEHCAS->feIni, "date"));
          $qeryGetDemanda=  mysqli_query($conecta1,$string_getDemanda);
           $fethDemanda =  mysqli_fetch_array($qeryGetDemanda);
        //////*********************************
           $string_prod= sprintf("SELECT ItemName FROM plataformaproductosl1 WHERE ItemCode=%s  ", GetSQLValueString($row['cve_prod'], "text"));

                                    $qernomprod = mssql_query($string_prod);
                                    $fetchNoProd =mssql_fetch_array($qernomprod);

             IF($fethDemanda['demanda']==NULL){  $demanda = 0 ;}else {$demanda =$fethDemanda['demanda'];}                        
           
         $fech_Ini =$row['fech_Ini'] ;
        $fech_fin = $row['fech_fin']; 
        
        $Obj =   Array( "cve_Age"=>$row['cve_agente'],
                        "cve_prod"=>$row['cve_prod'],
                        "nom_prod"=>$fetchNoProd['ItemName'],
                        "variacion"=>$row['variacion'],
                        "VTReal"=>$row['VentReal'],
                        "Demanda"=>$demanda,
                        "fech_Ini" =>$row['fech_Ini'],
                        "fech_fin" =>$row['fech_fin'],
                        "ResNVL1"=>$row['NIVEL1'],
                        "ResNVL2"=>$row['NIVEL2'],
                        "ResNVL3"=>$row['NIVEL3'],
                        "ResNVL4"=>$row['NIVEL4'],
                        "OpcAct"=>false 
                        );
            array_push($ArrayObj,$Obj);

           
       
    }
    $Json = json_encode($ArrayObj);
    //**************************************Obtener el Primer Nivel1 de Respuesta
    ////***select *from pedidos.desv_respDesv where  id in (SELECT  distinct(NVL1) FROM pedidos.desv_respDesv) 
    $strGetRes = "select *from pedidos.desv_respDesv where  id in (SELECT  distinct(NVL1) FROM pedidos.desv_respDesv)";
    $qeryRES = mysqli_query($conecta1,$strGetRes );
    $ArrayObjres =  Array();
     /////*** NVL1,COMENT
     while($row = mysqli_fetch_array($qeryRES)){
            $Obj =   Array( "NVL1"=>$row['NVL1'],
                            "COMENT"=>$row['COMENT'],
                            "OPCULTIVO" => $row['OPCULTIVO']);
                array_push($ArrayObjres,$Obj);
     }
     $JsonNIVEL1 = json_encode($ArrayObjres);
    
     
}
  
?> 
<!--JQuery jquery.min.js" -->
<script src="bower_components/jquery/dist/jquery.min.js"></script>
<script  type="text/javascript">
 var   ArregloObj = <?PHP echo $Json;?>;
 var   aregloNVL1  =<?PHP echo $JsonNIVEL1;?>;
 const  PagReturn=  <?php echo "'http://192.168.101.5/sistemas/cronos/desv_estAgeDesvi.php?TypePg=1'"; ?>;
        
 var   ArregObjCult = new  Array(); 
 
 const   fech_Ini = <?php 
                            $dtiNI= new DateTime($fech_Ini);
                            echo "'".$dtiNI->format("d/m/Y")."'"; 
 
                       ?>;
 const   fech_fin = <?php 
                             $dtiFIN= new DateTime($fech_fin);
                            echo "'".$dtiFIN->format("d/m/Y")."'";
                             
                     ?>;
////**+Funcion Existen  Desviaciones
function  GetExisDesv()
{   var  result =  false; 
    if(ArregloObj.length >0)
    { result =  true ;  } 
   return   result; 
}

   
</script> 
<script type="text/javascript">
$(document).ready(function(){

 ////**Mostramos  las  Fechas
$("#fechINC").text(""+fech_Ini);
$("#fechFIN").text(""+fech_fin);

/*****
 * Funcion UpdateNvlRespuesta para Modifiacar Respuestas de los Niveles
 * Entiendase Que NIVEL === 
 *          NVL1 =>  1
 *          NVL2 =>  2
 *          NVL3 =>  3
 *          NVL4 =>  4 
   Y RES =>Respuesta  Selccionada 
   NIVEL => Nivel a Modificar 
   OPCULT => Opcion de Cultivo => Esta Varible Tiene  2 Estados
             Utilice  esta variable cuando  la  Opcion de CUltivo  Aplique en Un nivel diferente al NVEL4
        Entiendase  Que :  
                            OPCULT => 0  => El Nivel NO Tiene  Opcion de  Seleccion de Cultivo 
                            OPCULT => 1  => El Nivel SI Tiene  Opcion de  Seleccion de Cultivo         
*/
function    UpdateNvlRespuesta(CVEPROD,NIVEL,RES,OPCULT)
{ let  fainelem =  false;
  for(var i in ArregloObj )
  {
     
     if(ArregloObj[i].cve_prod.localeCompare(CVEPROD)===0)
     {    
         ///***Opcion de Nivel  a Modifacar
          switch(NIVEL){
              
                    case "NVL1" : 
                            ArregloObj[i].ResNVL1 = RES;
                        break; 
                    case "NVL2" : 
                         ArregloObj[i].ResNVL2 = RES;
                        break;    
                    case "NVL3" :  
                         ArregloObj[i].ResNVL3 = RES;
                         if(OPCULT =="1")
                         {
                            ArregloObj[i].OpcAct= true;
                         } 
                         
                        break;
                    case "NVL4" :  
                         ArregloObj[i].ResNVL4 = RES;
                         ArregloObj[i].OpcAct=true ;
                        break;
              
          }
        fainelem =  true; 
         break 
     } 
      
    
  }
   return  fainelem;  
    
}

function  GenTabla(ArregloObjeto,aregloNVL1)
{   
    var trTable = "";
  
    for(var   i  in ArregloObjeto ){
      ////****Asignaos estado Positivo  o Negativo  para los  selects 
      if(ArregloObjeto[i].variacion > 0)
      {///Variacion Postiva 
        var  selctElmNl1 ="<select  class='NVL1 form-control' id='NVL1"+ArregloObjeto[i].cve_prod+"' cvePrd='"+ArregloObjeto[i].cve_prod+"' cltOpc ='"+ArregloObjeto[i].OPCULTIVO+"' VARi=1 >"
      }else{
        ///Variacion Negativa   
        var  selctElmNl1 ="<select  class='NVL1 form-control' id='NVL1"+ArregloObjeto[i].cve_prod+"' cvePrd='"+ArregloObjeto[i].cve_prod+"' cltOpc ='"+ArregloObjeto[i].OPCULTIVO+"' VARi=2 >"
      } 
      ////***Generamos Options del Primer nivel  NVL1*****************
     for(var   j  in aregloNVL1 ){
     
            if(ArregloObjeto[i].variacion > 0)
            {
                ///Variacion Postiva  Omitios  la Opcion '4', 'COMPETENCIA'
                if(aregloNVL1[j].NVL1 != 4)
                { 
                    selctElmNl1 += "<option  value='"+aregloNVL1[j].NVL1+"'>"+aregloNVL1[j].COMENT+"</option>"; 
                 }
            }else{
              ///Variacion Negativa Option Normal
              selctElmNl1 += "<option  value='"+aregloNVL1[j].NVL1+"'>"+aregloNVL1[j].COMENT+"</option>";
            }
      
      
      } 
      ///***Finalizamos el  select 
      selctElmNl1 += "</select>"; 
    ////*****Condicion para Detectar TIPO DE  Boton para  Guardar  o Actualizar
    if( ArregloObjeto[i].ResNVL1 == 0)
    {
        var  BtnGAc = "</td><td><button id='SVBTN"+ArregloObjeto[i].cve_prod+"'  type='button' cvePrd='"+ArregloObjeto[i].cve_prod+"'  class='SVBTN  btn  btn-info btn-sm'>Guardar<span class='glyphicon glyphicon-floppy-disk'></span></button>";
                
    }else{
       var  BtnGAc = "</td><td><button id='SVBTN"+ArregloObjeto[i].cve_prod+"'  type='button' cvePrd='"+ArregloObjeto[i].cve_prod+"'  class='SVBTN  btn btn-success btn-sm'>Actualizar <span class='glyphicon glyphicon-refresh'></span></button>";
         
    }
    
    
    
    /////***Agregamos la  Variacion  con su Informacion Correspondiente 
    if(ArregloObjeto[i].variacion > 0)
    {
        ///Variacion Postiva 
        trTable += "<tr class='trPOSITIVO' ><td>"+ArregloObjeto[i].cve_prod+"</td><td>"+ArregloObjeto[i].nom_prod+
                "</td><td>"+ArregloObjeto[i].Demanda+"</td><td>"+ArregloObjeto[i].VTReal+"</td><td>"+ArregloObjeto[i].variacion+
                BtnGAc+ ////BtN 
               "</td><td class='TDOPC1'>"+selctElmNl1+"</td><td class='TDOPC2' id='OPC1"+ArregloObjeto[i].cve_prod+
                "'></td><td class='TDOPC3' id='OPC2"+ArregloObjeto[i].cve_prod+
                "'></td><td  class='TDOPC3' id='OPC3"+ArregloObjeto[i].cve_prod+"'></td></tr>";
    }else{
        var converVariacion  =ArregloObjeto[i].variacion*-1 ;
        ////**Variacion Negativa 
       trTable += "<tr class='trNegativo'><td>"+ArregloObjeto[i].cve_prod+"</td><td>"+ArregloObjeto[i].nom_prod+
               "</td><td>"+ArregloObjeto[i].Demanda+"</td><td>"+ArregloObjeto[i].VTReal+"</td><td>"+converVariacion+
               BtnGAc+ ////BtN 
               "</td><td class='TDOPC1'>"+  selctElmNl1+"</td><td class='TDOPC2' id='OPC1"+ArregloObjeto[i].cve_prod+
               "'></td><td class='TDOPC3' id='OPC2"+ArregloObjeto[i].cve_prod+
               "'></td><td  class='TDOPC3' id='OPC3"+ArregloObjeto[i].cve_prod+"'></td></tr>";
    }
  
  
    }  
     $("#tbdes").html(trTable); 
     PutSelectSave()
     SelectElmSave();
     
     
}

////***Function  para Retornar un Elemento sELECT
function  GetSelecHTML(ARGNVL,NUMNVL,CVEPROD,ESCULT,OpcNext)
{
   var  selctElmNl1 ="<select class='"+NUMNVL+" form-control' id='"+NUMNVL+CVEPROD+"' cvePrd='"+CVEPROD+"' escult='"+ESCULT+"'>"
   var  k =0 ;
    for(var   j  in ARGNVL ){
        
         selctElmNl1 += "<option  value='"+ARGNVL[j].NVL1+"' cltOpc ='"+ARGNVL[j].OPCULTIVO+"' Vari="+ARGNVL[j].VAR+">"+ARGNVL[j].COMENT+"</option>";
     } 
    selctElmNl1 += "</select>";
    
    return selctElmNl1;
}
///***Function  Eliminar Select   Select 
function    DelEleSelect(NUMNVL,CVEPROD)
{
    var  idElmen = "#"+NUMNVL+CVEPROD;
    $(idElmen).remove();
    
}
///***Funcion para Retornar Elemento a Actulizar 
function  GetObjUpdta(CVEPROD)
{
    var OBG ;
   for( var   i  in  ArregloObj )
   {
      if(ArregloObj[i].cve_prod.localeCompare(CVEPROD)===0)
      {
         OBG =ArregloObj[i];
         break;
      } 
       
   }
   return  OBG; 
}
///***Funcion Validar Si la  Respuesta  Tiene Almenos 2  NIVELES  CONTESTADOS 
function  RespMore2(ObjV)
{
  var result = false; 
  if(ObjV.ResNVL1 != 0 && ObjV.ResNVL2 != 0)
  {
    result = true ;
  }
  return result;
    
}
/**Funcion Para  Validar  Todo el  Areglo de Objetos
    Si  Existe  Almenos Un Elemento que No contenga 2 respuestas  se retorna  False 
 **/
function  GetALLmore2()
{
   var  Result =  true; 
  for(var   i  in ArregloObj ){
      if(RespMore2(ArregloObj[i])==false)
      {
          Result =false;
          break;
      }
  }
  return  Result;
}

//***Function Agregar SelectsSelecionados
function PutSelectSave()
{
  for(var   i  in ArregloObj ){
       ////**Campo  Vari Funcion
       var funVari
       if(ArregloObj[i].variacion > 0) {  funVari = 1 ;}else{ funVari = 0 ; }    
       /////**Seleccionamos la si exite  nivel  1*
       if(ArregloObj[i].ResNVL1 != 0)
       {
        $("#NVL1"+ArregloObj[i].cve_prod+" option[value="+ ArregloObj[i].ResNVL1 +"]").attr("selected",true); 
       }     
       ///***Agregamos Nivel  2  Si Existe Inforacion  
       if(ArregloObj[i].ResNVL2 != 0)
       {
          GenerarNIVEL2(ArregloObj[i].ResNVL1,ArregloObj[i].OpcAct,funVari,ArregloObj[i].cve_prod)
       }
       ///***Agregamos Nivel  3  Si Existe Inforacion  
       if(ArregloObj[i].ResNVL3 != 0)
       {
          GenerarNIVEL3(ArregloObj[i].ResNVL2,ArregloObj[i].OpcAct,funVari,ArregloObj[i].cve_prod)
       }
     ///***Agregamos Nivel  4  Si Existe Inforacion  
       if(ArregloObj[i].ResNVL4 != 0)
       {  
          GenerarNIVEL4(ArregloObj[i].ResNVL3,ArregloObj[i].OpcAct,funVari,ArregloObj[i].cve_prod,0)
        }
 }

    
}
$("#btnRetornRespuestas").click(function(){
    
    SelectElmSave();
});




////***FUncion Para Seleccionar  Elementos 
function SelectElmSave()
{
      ////////*********
  for(var   i  in ArregloObj ){
       ////**Campo  Vari Funcion
       var funVari
       if(ArregloObj[i].variacion > 0) {  funVari = 1 ;}else{ funVari = 0 ; }    
       /////**Seleccionamos la si exite  nivel  1*
       if(ArregloObj[i].ResNVL1 != 0)
       {
        $("#NVL1"+ArregloObj[i].cve_prod+" option[value="+ ArregloObj[i].ResNVL1 +"]").attr("selected",true); 
       }     
       ///***Agregamos Nivel  2  Si Existe Inforacion  
       if(ArregloObj[i].ResNVL2 != 0)
       {
          $("#NVL2"+ArregloObj[i].cve_prod+" option[value="+ ArregloObj[i].ResNVL2 +"]").attr("selected",true); 
       }
       ///***Agregamos Nivel  3  Si Existe Inforacion  
       if(ArregloObj[i].ResNVL3 != 0)
       {
            $("#NVL3"+ArregloObj[i].cve_prod+" option[value="+ ArregloObj[i].ResNVL3 +"]").attr("selected",true);  
       }
     ///***Agregamos Nivel  4  Si Existe Inforacion  
       if(ArregloObj[i].ResNVL4 != 0)
       {  
            $("#NVL4"+ArregloObj[i].cve_prod+" option[value="+ ArregloObj[i].ResNVL4 +"]").attr("selected",true);  
        }
 }
}


//////***Seleccion Priemr  Nivel
$(document).on("click",".NVL1",function(){
                
                
                var  classEl =  "#"+$(this).attr('id');
                ////cvePrd
                var  PRODCVE = $(this).attr('cvePrd');
                var  opsitionElm =  classEl+"  option:selected";
               ///***Obtenemos el  Value Seleccionado 
               var OpcNext =$(opsitionElm).val() ; 
               ///***oBTENEMOS oPCION DE cULTIVO 
                var cultOpc = $(opsitionElm).attr('cltOpc');
                  ////***Obtenemos el  Tipo de Variacio 
                var   VARi = $(this).attr('VARi');
                
              GenerarNIVEL2(OpcNext,cultOpc,VARi,PRODCVE)
               
                
            
});
///****Funcion para  Obtener  el  Nivel2 Y Agregarlo
function   GenerarNIVEL2(OpcNext,cultOpc,VARi,PRODCVE)
{
     ////****Agregamos la  Seleccion  al  Objeto
               UpdateNvlRespuesta(PRODCVE,"NVL1",OpcNext,0);
               ///****Eliminamos Select  de Niveles Inferiores
               DelEleSelect("NVL2",PRODCVE)
                DelEleSelect("NVL3",PRODCVE)
                 DelEleSelect("NVL4",PRODCVE)
               ////****Agregamos al cliente 
               $.ajax({
                        type:'POST',
                        url: 'scrip_desviaciones/desv_GetNVOpc.php',
                        data:{"OpcNvl":OpcNext, "NUMVL":1  ,"OPCULT":cultOpc,"VARi":VARi}, 
                        success: function (datos) {
                              
                          var ARGNVL = JSON.parse(datos.OBJ);
                          ////**Generamos el HTML DEL SELECT
                          var selc = GetSelecHTML(ARGNVL,"NVL2",PRODCVE,0,OpcNext)
                         ////**Generamos el Identificador de  la Pocicion del  Select 
                          var  tdPosition  =  "#OPC1"+PRODCVE;
                          $(tdPosition).html(selc);
                         
                          
                         }
                 });
    
    
}
///****Funcion para  Obtener  el  Nivel3 Y Agregarlo
function   GenerarNIVEL3(OpcNext,cultOpc,VARi,PRODCVE){
    ////**Si es 0 Obtener la variacion anterior
                if(VARi==0){  
                    
                      var getIdElmVari = "#NVL1"+PRODCVE;      
                       VARi= $(getIdElmVari).attr('VARi');        
                  }
                 ///***Agregamos la  Opcio Relacionada
                 UpdateNvlRespuesta(PRODCVE,"NVL2",OpcNext,0); 
               ////****Agregamos al cliente 
               $.ajax({
                        type:'POST',
                        url: 'scrip_desviaciones/desv_GetNVOpc.php',
                        data:{"OpcNvl":OpcNext, "NUMVL":2,"OPCULT":cultOpc,"VARi":VARi }, 
                        success: function (datos) {
                            
                          var ARGNVL = JSON.parse(datos.OBJ);
                         
                          ////**Validamos que  no se repita  el select  Cultivo 
                                if(datos.SELCULT ==2 ||datos.SELCULT ==1  ){
                                ////**Generamos el HTML DEL SELECT
                                   var selc = GetSelecHTML(ARGNVL,"NVL3",PRODCVE,1,OpcNext)
                              
                                }else{
                                   ////**Generamos el HTML DEL SELECT
                                   var selc = GetSelecHTML(ARGNVL,"NVL3",PRODCVE,0,OpcNext)
                                  
                               }        
                         ////****Si No existe Opcion Siguiente  Elimnamos  el  Selecta
                                if(datos.ELMN == 0){
                                     DelEleSelect("NVL3",PRODCVE)
                                }else{
                                 ////**Generamos el Identificador de  la Pocicion del  Select 
                                 var  tdPosition  =  "#OPC2"+PRODCVE;
                                 $(tdPosition).html(selc);
                               
                                 
                               }
                            
                        }
                 });
    
}
//****Funcion para  Obtener  el  Nivel3 Y Agregarlo
function   GenerarNIVEL4(OpcNext,cultOpc,VARi,PRODCVE,Escultivo){
    ////****Validamos 
               if(Escultivo == "0"){
                ///***Agregamos la  Opcio Relacionada
                 UpdateNvlRespuesta(PRODCVE,"NVL3",OpcNext,0); 
                }
               ////****Validamos 
               if(Escultivo == "1"){
                ///***Agregamos la  Opcio Relacionada
                 UpdateNvlRespuesta(PRODCVE,"NVL3",OpcNext,1); 
           
                } 
               ////****Agregamos al cliente 
               $.ajax({
                        type:'POST',
                        url: 'scrip_desviaciones/desv_GetNVOpc.php',
                        data:{"OpcNvl":OpcNext, "NUMVL":3,"OPCULT":cultOpc,"VARi":VARi }, 
                        success: function (datos) {
                            
                          var ARGNVL = JSON.parse(datos.OBJ)
                          ///******Validamos si es Un cultivo 
                                 if(Escultivo == "0" && (datos.SELCULT ==2 ||datos.SELCULT ==1)  ){
                                ////**Generamos el HTML DEL SELECT
                                   var selc = GetSelecHTML(ARGNVL,"NVL4",PRODCVE,1,OpcNext);
                                ////****Si No existe Opcion Siguiente  Elimnamos  el  Selecta
                                            if(datos.ELMN == 0){
                                                 DelEleSelect("NVL4",PRODCVE)
                                            }else{
                                             ////**Generamos el Identificador de  la Pocicion del  Select 
                                             var  tdPosition  =  "#OPC3"+PRODCVE;
                                             $(tdPosition).html(selc);
                                           
                                            }
                                 }
                            
                        }
                 });
    
}

//////***Seleccion Priemr  Nive2
$(document).on("click",".NVL2",function(){
                
                
                var  classEl =  "#"+$(this).attr('id');
                ////cvePrd
                var  PRODCVE = $(this).attr('cvePrd');
                var  opsitionElm =  classEl+"  option:selected";
               ///***Obtenemos el  Value Seleccionado 
               var OpcNext =$(opsitionElm).val() ; 
                ///***oBTENEMOS oPCION DE cULTIVO 
                var cultOpc = $(opsitionElm).attr('cltOpc');
               ////***Obtenemos el  Tipo de Variacio 
                var   VARi = $(opsitionElm).attr('VARi');
                
                 GenerarNIVEL3(OpcNext,cultOpc,VARi,PRODCVE)
            
});
//////***Seleccion Priemr  Nivel3
$(document).on("click",".NVL3",function(){
                
                
                var  classEl =  "#"+$(this).attr('id');
                ////cvePrd
                var  PRODCVE = $(this).attr('cvePrd');
                var  opsitionElm =  classEl+"  option:selected";
               ///***Obtenemos el  Value Seleccionado 
               var OpcNext =$(opsitionElm).val() ; 
               ///***oBTENEMOS oPCION DE cULTIVO 
                var cultOpc = $(opsitionElm).attr('cltOpc');
                ///**Obtenemos Si aplica para cultivo 
                 var Escultivo = $(this).attr('escult');
               ////***Obtenemos el  Tipo de Variacio 
                var   VARi = $(opsitionElm).attr('VARi'); 
               
               GenerarNIVEL4(OpcNext,cultOpc,VARi,PRODCVE,Escultivo)
                
            
});
//////***Seleccion Priemr  Nivel4
$(document).on("click",".NVL4",function(){
                
  var  classEl =  "#"+$(this).attr('id');
                ////cvePrd
                var  PRODCVE = $(this).attr('cvePrd');
                var  opsitionElm =  classEl+"  option:selected";
               ///***Obtenemos el  Value Seleccionado 
               var OpcNext =$(opsitionElm).val() ; 
               ///***oBTENEMOS oPCION DE cULTIVO 
                var cultOpc = $(opsitionElm).attr('cltOpc');
                ///**Obtenemos Si aplica para cultivo 
                 var Escultivo = $(this).attr('escult');
               ////***Obtenemos el  Tipo de Variacio 
                var   VARi = $(opsitionElm).attr('VARi'); 
               
                ///***Agregamos la  Opcio Relacionada
                 UpdateNvlRespuesta(PRODCVE,"NVL4",OpcNext,1); 
               


            
});
/////**Boton para  Guardar Respuesta  Unica*
$(document).on("click",".SVBTN",function(){
    
    ////cvePrd
    var  PRODCVE = $(this).attr('cvePrd');
    var  ObjUpdt = GetObjUpdta(PRODCVE); 
    ////***Validamos que Exista  al menos   2  preguntas  contestadas 
    if(RespMore2(ObjUpdt)==true)
    {
        ///***Mandamos  Modificar el Objeto 
        $.ajax({
                    type:'POST',
                    url: 'scrip_desviaciones/dev_updResp.php',
                    data:{"MAUPD":0,"ELEM":JSON.stringify(ObjUpdt) }, 
                    success: function (datos) {
                         
                    }
        });
        
       $(this).remove(); 
        
    }else
    {
         alert("Lo Sentimos No Agregado Ninguna respuesta a la Variacion");
    }
    SelectElmSave()
});
 
 ////***Boton Guardar   y terminar  
 $("#BTNfinish").click(function(){
     
    if(GetALLmore2()==true){
        $("#titlEmeN").text("Está a punto de Guardar Todas las Desviaciones de forma PERMANENTE con Fecha de Inicio: 01/06/2017 y Fecha Fin:30/06/2017. Si está seguro presione Aceptar para Guardar las Desviaciones");
        $("#updateAcept").attr("disabled",false);
    
    }else{
     $("#titlEmeN").text("Es Imposible Guardar las  Desviaciones. Se  detectaron Desviaciones Sin Rango de Respuesta  Minima.");
    $("#updateAcept").attr("disabled",true);   
    }
            
            $("#menSaveALL").modal("show");
     
 });
 /////***Btn Aceptar  Y MODIFICAR 
 $("#updateAcept").click(function(){
      ///***Mandamos  Modificar el Objeto 
        $.ajax({
                    type:'POST',
                    url: 'scrip_desviaciones/dev_updResp.php',
                    data:{"MAUPD":1, "OBJETOS":JSON.stringify(ArregloObj) }, 
                    success: function (datos) {
                        console.log(datos.ERRORES);
                        console.log(datos.cadenaCabe);
                        if(datos.ERRORES==0)
                        {
                            $("#titlEmeN").text("La Modificacion fue  realizada  con Exito");
                            ////**Exito  en Update  
                                   window.location.href=PagReturn;
         
                            
                        }else{
                            $("#titlEmeN").text("Hemos Tenido Problemas !! Intente De Nuevo.");
         
                        }
                        
                         
                    }
        });
    
 });
 
 if(GetExisDesv()==true){ 
     $("#menSINdES").remove(); 
    GenTabla(ArregloObj,aregloNVL1)   
    
 }else {
     $("#PrinTable").remove(); 
     $("#BTNfinish").remove();
     $("#Variblesconten").remove();
     
 } 	

});
</script>
<style>

.trNegativo{
    background: rgba(255, 0, 0, 0.46);
}
.trPOSITIVO{
    background: rgb(51, 164, 113);
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

</style>

<div  class="container">
    <div id="Variblesconten" class="col-lg-12  col-xs-12">
       
        <div  class="col-lg-6 col-xs-6"><h3>Registro De Desviaciones:</h3></div>
        <div  class="contFecht col-lg-6  col-xs-6">
            <div  class="col-xs-6">
               <strong>Fecha  Inicial :</strong><p><strong id="fechINC"></strong></p>  
            </div>
            <div  class="col-xs-6">
                   <strong>Fecha Final :</strong><p><strong id="fechFIN"></strong></p>
            </div>
        </div>
        <div class="row" >
            <button   id="btnRetornRespuestas"  class="btn btn-success"> Obtener Ultimas Respuestas </button> 
            
        </div>
        
        
    </div>
    <div id="menSINdES" class="col-xs-12">
        <div  class="col-lg-2 col-xs-2"></div>
        <div  class="col-lg-8  col-xs-8">
            <h2 id="mensL">No Existen Desviaciones</h2>
        </div>
        <div  class="col-lg-2 col-xs-2"></div>
        
        
    </div> 
    
    
    <div  class="col-lg-12  col-xs-12">
        <table id="PrinTable" class="table  table-hover">
            <thead>
                <tr>
                    <th>Clave Prod</th>
                    <th>Nombre</th>
                    <th>Demanda</th>
                    <th>Venta Real</th>
                    <th>Variacion</th>
                    <th></th>
                    <th></th>
                </tr>
            </thead>
            <tbody id="tbdes">
                
            </tbody>
            
        </table>
        
        
    </div>
    <div class="col-xs-12">
        <button  type="button"   id="BTNfinish"  class="btn btn-info  btn-lg">Guardar Desviaciones</button>   
        
    </div>
    
    
    
     <!---Modal Modificar Elementos   ----->    
     <div  class="modal fade" id="menSaveALL" role="dialog">
        <div  class="modal-dialog">

          <!-- Modal content-->
          <div  class="modal-content">
            <div class="modal-header">
                   <button type="button" id=""  class="close_coment close" data-dismiss="modal">&times;</button>
                   <h4>Atencion !!! </h4>
                   <P><strong id="titlEmeN"></strong></p>
            </div>
            <div class="modal-body">
      
                <div class="form-group">
                    <div  class="row" class="well">
                        
                            <div class="col-sm-1" ></div>
                            <div class="col-sm-4" ><button disabled  type="button" id="updateAcept"   class="btn btn-info"> Aceptar  <span class="glyphicon glyphicon-pencil"></span></button></div>
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
    
    
    
</div>
<?php   
///****Agregamos  fOOT
require_once('foot.php');
?> 