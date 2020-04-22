/*
********	 INFORMACION ARCHIVO ***************** 
	Nombre  Archivo : coti_Arreglomain.js
 	Fecha  Creacion : 20/06/2017
	Descripcion  : 
                    Escrip Contenedor  de la  Variable  Areglo Principal
                    ArglMain =>  Arreglo Principal Main 

  */
////**+Funcion  Para Modificar  un Elemento del  Areglo
 /////***Funcion para  Convertir a Moneda
    function ConverMoney(value)
    {       
        var  str = value+""; 
         return str.replace(/\D/g, "")
                .replace(/([0-9])([0-9]{2})$/, '$1.$2')
                .replace(/\B(?=(\d{3})+(?!\d)\.?)/g, ",");
    }
    
function  UpdateProd (cve_prod,Prsol,Cantsol)
{
      var  tableTr ="";
    for(var  i  in ArglMain ){
       if( ArglMain[i].cve_prod.localeCompare(cve_prod) == 0)
        {
		ArglMain[i].UpdateProd(Cantsol,Prsol);		
                tableTr += "<tr><td>"+ArglMain[i].cve_prod+"</td><td>"+ ArglMain[i].nom_prod+"</td><td><input  type='number' class='CSL"+ArglMain[i].cveProd+" form-control' value='"
                    + ArglMain[i].cant_sol+"'></td><td '><input   type='number' class='PSL form-control' value='"+ ArglMain[i].prec_sol+"' CVP='"+ArglMain[i].cveProd+"'  ></td><td>"+ConverMoney(ArglMain[i].cost_inv)
                                        +"</td><td>"+ConverMoney(ArglMain[i].cost_proy)+"</td><td>"+ArglMain[i].CMG_POR_INV+"</td><td>"+ConverMoney(ArglMain[i].Vta)+"</td><td>"+ConverMoney(ArglMain[i].CST)+"</td><td>"+
                                               ConverMoney(ArglMain[i].CMG)+"</td><td>"+ArglMain[i].CMG_POR+"</td><td>"+ArglMain[i].limite_dc+"</td></tr>";
        } 
         tableTr += "<tr><td>"+ArglMain[i].cve_prod+"</td><td>"+ ArglMain[i].nom_prod+"</td><td><input  type='number' class='CSL"+ArglMain[i].cveProd+" form-control' value='"
                    + ArglMain[i].cant_sol+"'></td><td '><input   type='number' class='PSL form-control' value='"+ ArglMain[i].prec_sol+"' CVP='"+ArglMain[i].cveProd+"'  ></td><td>"+ConverMoney(ArglMain[i].cost_inv)
                                        +"</td><td>"+ConverMoney(ArglMain[i].cost_proy)+"</td><td>"+ArglMain[i].CMG_POR_INV+"</td><td>"+ConverMoney(ArglMain[i].Vta)+"</td><td>"+ConverMoney(ArglMain[i].CST)+"</td><td>"+
                                               ConverMoney(ArglMain[i].CMG)+"</td><td>"+ArglMain[i].CMG_POR+"</td><td>"+ArglMain[i].limite_dc+"</td></tr>";
                                             
                                 
   }
   
   return  tableTr;
  
}





