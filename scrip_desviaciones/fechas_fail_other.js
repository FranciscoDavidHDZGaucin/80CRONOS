///***fechas_fail_other
/*
********	 INFORMACION ARCHIVO ***************** 
	Nombre  Archivo : fechas_fail_other.js 
 	Fecha  Creacion : 25/04/2017 
	Descripcion  : 
            Escrip Contenedor de las funciones  encargadas
            de  validar  la  Fecha  de  Requ  o  Cualquier  Otra 
            Se  implemento  este Procedimiento dado a que la estructura de  cronos  
            No permitio  cargar las  librerias  necesarias  para  el  correcto  funcionaiento 
            del  Input Date en   Diferentes  navegadores  como  FIrefox    Opera  o internet Explorer  que no esten
            Actualizados  a su version mas  reciente 
  */
 
  ///*****Metodo  General   con Subprocesos  aplicados ******************************************
 function  ValidarFecha(fecha)
 {
     var  result= false;
     ////***Validamos el  Formato
      if(validarFormatoFecha(fecha)==true)
      {
          ///**Validamos que Existe   
         if(existeFecha(fecha)==true)
         {
             result=  true;
         }
      }
     
     return result;
     
 }
 ///****+ Validar el   formato  de  la  Fecha  
function validarFormatoFecha(campo) {
      var RegExPattern = /^\d{1,2}\/\d{1,2}\/\d{2,4}$/;
      if ((campo.match(RegExPattern)) && (campo!='')) {
            return true;
      } else {
            return false;
      }
}
///**Validamos  que la Fecha  exista en el calendario
function existeFecha(fecha){
      var fechaf = fecha.split("/");
      var day = fechaf[0];
      var month = fechaf[1];
      var year = fechaf[2];
      var date = new Date(year,month,'0');
      if((day-0)>(date.getDate()-0)){
            return false;
      }
      return true;
}
///****Convertimos   La fecha  a  Formato Base de  Datos 
function   convertoTODB(FECH)
{
    
    var d=new Date(FECH.split("/").reverse().join("-").match(/(\d+)/g));
    var dd=d.getDate();
    var mm=d.getMonth()+1;
    var yy=d.getFullYear();
    var newdate = yy.toString()+"-"+mm.toString()+"-"+dd.toString();
    return  newdate ;
}
///*****Detectar  los  Input  date   Si son Validos en cualquier navegador 
function InputDate_enable(){
    var  resul = false;
    var datefield=document.createElement("input")
     datefield.setAttribute("type", "date")
     if (datefield.type!="date"){ //if browser doesn't support input type="date", load files for jQuery UI Date Picker
         resul= true ;
     }
     return   resul; 
 }  
    
  //***Validar   Fecha   Inicio   Sea  Menor  que  la  Fecha  Fin 
function  FechInicMenorQFin(fecInic, fecFin)
{
    var  fecINIC =  new  Date(fecInic);
    var  fecFIN =  new Date(fecFin);
    var   result =  false;
   if(fecINIC<fecFIN){
       
       result=  true  ;
   }
   return result;
    
}
      