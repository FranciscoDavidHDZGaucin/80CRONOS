///****
/*
********	 INFORMACION ARCHIVO ***************** 
	Nombre  Archivo : pub_func_publicidad.js
 	Fecha  Creacion :  03/05/2017 
	Descripcion  : 
                Escrip  Contenedor  de  Todas  las  Funciones Utilizadas
                para la plataforma de  Publicidad. 

  */
 //**Variables  Globales 
 var  CveProdCON =  new  Array();
 var CanTVAL=new  Array();
 ///*Objeto Publicidad 
 function  pup_Producto(nf,cve,nom,cant,come)
 {
    this.nf= nf; ///Numero  de Folio
    this.cve= cve; //Clave del  Producto 
    this.nom=nom; // Nombre del  Producto 
    this.cant=cant ; //Cantidad  de  Producto 
    this.come=come; //Comentarios

 }


 ///***Get All Cve Productos 
 function   Get_Prod_Cve()
 {  var  result ;
     
      $.ajax({
                type:'POST',
                url: 'pub_scrip_publicidad/pubGetCveProd.php',
                success: function (datos) { 
                       return result =datos.COD
                }
             });
             
     
 }
 
 ///****Funcion Para  Obtener los  Productos Que tienen Cantidad  
function  GeArrCanti(Arr_Prod)
{   
     var  ArCveProd = CveProdCON;
     var  CantidaVal =  CanTVAL ;
    
    for( var  i =0; i< Arr_Prod.length ; i++  )
    {
        var  clsInpCant = ".inp_cant"+Arr_Prod[i];
        ///**Obtenemos  el  Valor 
         var  cantVal=$(clsInpCant).val();
        if(cantVal>0 && cantVal != "" &&cantVal != null&& ExisCVe(Arr_Prod[i])==false  ){
           ArCveProd.push(Arr_Prod[i]); 
           CantidaVal.push(cantVal);
           
        } 
    }
    ///***Retornamos  
   var  ArGToSend =  {"ArCveProd":ArCveProd ,"CanTVal":CantidaVal}; 
     var   JsonTosEND = JSON.stringify(ArGToSend)
        
    return  JsonTosEND ; 
}
  ///****Funcion Para   Agregar elemtos  Uno  Por Uno 
function  GeArrByOne(CveProd)
{   
   
    
        var  clsInpCant = ".inp_cant"+CveProd;
        ///**Obtenemos  el  Valor 
         var  cantVal=$(clsInpCant).val();
        if(cantVal>0 && cantVal != "" &&cantVal != null){
           CveProdCON.push(CveProd);        ///[i]  = Arr_Prod[i]; 
           CanTVAL.push(cantVal);           ///[i] = cantVal;
           
        } 
    
    ///***Retornamos  
   var  ArGToSend =  {"ArCveProd":CveProdCON ,"CanTVal":CanTVAL}; 
     var   JsonTosEND = JSON.stringify(ArGToSend)
        
    return  JsonTosEND ; 
}   
///***Funcion para  Revisar que el  Arglo CveProdCON no este repetido
function   ExisCVe(elemento)
{
    var   result= false;
   for(var i=0 ; i<CveProdCON.length;i++ )
   {
     if(CveProdCON[i] == elemento)  
     {
         result =true ;
     }
       
   }
  return  result;  
     
}
//***Funcion para  Retornar   Json  Encabezado  
function  Get_Encabeza_Pub ()
{
     var   EncJson  =  {
            "AGE" : $("#NomAge").val(), ///Agente 
            "FECH" : $("#fechSol").val(),///Fecha
            "ZO" : $("#ZNa").val(),///Zona 
            "REG" : $("#reg").val(),///Region o Unidad
            "CLI" : $("#client").val(),///Cliente
            "PRO" : $("#PROV").val(),//Provedor
            "MOT" : $("#motvSOL").val(),//Motivos de la  Solicitud
            "FOL" : $("#folISol").val(),//Numero de Folio
            "NUMAGE" : $("#NumAge").val(),//Numero  de  Agente

      };

    return EncJson;

}
///***Function para  Retornar Json Productos  Los Productos  
function    Get_Detalle_Pub ()
{
    var  Arreglo_Prod =  new Array(); 
    for(var  i= 0 ; i<ArCveProd.length ; i++ )
    {
        /// (nf,cve,nom,cant,come)
        //***Obtenemos  el Nombre  del  Producto  
        var   classNombre  =  ".nomPr"+ArCveProd[i] ; 
        var   classComentario =".CoMe"+ArCveProd[i] ; 

        var NombreProd = $(classNombre).val();
        var Comentarios = $(classComentario).val();

        Arreglo_Prod.push(new pup_Producto($("#folISol").val(),ArCveProd[i],NombreProd,CanTVal[i],Comentarios ));




    }

    return   Arreglo_Prod;
}
//****Funcion para Detectar   Elementos  Vacios
function  IsEmptySol()
{   
    var  sin_pro = false;
    var    Client_Pro =  false;
    try {
        if(ArCveProd.length == null ||ArCveProd.length == "" )
        {
            sin_pro = true ;
        }
    }catch(e){
    
    sin_pro = true ;
    }
    if($("#client").val() == "" || $("#client").val() == null ||$("#PROV").val() =="" || $("#PROV").val()== null)
    {
        Client_Pro = true; 
    }
    
   return   {"PRO":sin_pro, "CPR": Client_Pro};
}
