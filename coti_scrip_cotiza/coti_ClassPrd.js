///coti_ClassPrd.js

/*
********	 INFORMACION ARCHIVO ***************** 
	Nombre  Archivo : coti_ClassPrd.js
 	Fecha  Creacion : 19/06/2017
	Descripcion  : 
                    Escrip   Contenedor  De la  Classe Producto
                    diseñada  para  poder  calcular un producto al Momento  de  
                    Obtener   un Elemento desde el  Php 
        Listado  de Metodos  Dentro de la Classe 
        
        Modificaciones : 
                17/07/2017   Se Agregan los  campos  
                                   bo_cto_inv  => Costo de  Bonificacion  de Inventario 
                                   boCto_proy => Costo de Bonificacion   Proyectada   
  */
class  Producto{
  
    constructor(cve_prod,nom_prod,cant_sol,prec_sol,cost_inv,cost_proy,boni_act,cve_prod_bo,nom_prod_bo,cant_bo,boni_prec,ventl_prod,limite_dc,boni_por_pre,boniAplicar )
    {
       this.cve_prod = cve_prod ; ////Clave del  Producto 
       this.nom_prod = nom_prod; //// Nombre del  Producto
       this.cant_sol = cant_sol; ////Cantidad  Solicitada 
       this.prec_sol = prec_sol; ////Precio  SOlicitado 
       this.cost_inv = cost_inv ; ////Costo  de  Inventario 
       this.cost_proy =cost_proy ; //// Costo  Proyectado  
       this.boni_act = boni_act ; //// Estatus   de  Bonificacio 
       this.cve_prod_bo =cve_prod_bo ; /// Clave  Producto  a  Bonificar 
       this.nom_prod_bo =nom_prod_bo ; ///Nombre del Producto  
       this.cant_bo = cant_bo ;   /// Bonificacion  por Cantidad 
       this.boni_prec  = boni_prec; /// Bonificacion ´Precio Sol
      this.ventl_prod  = ventl_prod; // Venta  Total del  Producto
       this.limite_dc =limite_dc;   ////Limite Direccion Comercial
       this.boni_por_pre = boni_por_pre; ///Bonificacion por  Precio sol 
       this.boniAplicar =boniAplicar; ////Bonificacion a Aplicar a
        ////***Calculos  Bonificacion  
       this.bo_cto_inv = parseFloat(( cost_inv *this.boniAplicar ).toFixed(2)) ;
       this.boCto_proy =parseFloat(( cost_proy *this.boniAplicar ).toFixed(2)) ; 
       ///////**Variables Con  Caclculos 
       this.CMG_POR_INV = Math.round(parseFloat( (((this.prec_sol -  this.cost_inv)/this.prec_sol)*100).toFixed(2)))  ; ////CMG % Inv 
       this.CMG_PROY = parseFloat(((this.prec_sol - this.cost_proy)/this.prec_sol).toFixed(2)); ////CMG % Proy
       this.Vta = parseFloat( (this.cant_sol * this.prec_sol).toFixed(2)  ); ///Venta 
       this.CST = parseFloat( ( this.cant_sol *  this.cost_proy ).toFixed(2)  ) //Costo
       this.CMG  = parseFloat( ( this.Vta-this.CST ).toFixed(2)  );
       this.CMG_POR = Math.round(parseFloat( ( (this.CMG /this.Vta)*100   ).toFixed(2)))  ;
      
    }
    ////**Propiedad  para Retornar la  Clave del  Producto  
     ClaveProd()
    {
        return  this.cve_prod;
    }
    get EstBoni()
    {
        return this.boni_act; 
    }
    ////Calcular CMG %
    CMG_POR_CL()
    {
       var  auxCMG_POR  = (this.CMG /this.Vta).toFixed(2);
       this.CMG_POR =  parseInt(  parseFloat(auxCMG_POR)*100) ;
       
        
    }
    
    
    ////Modificar  Objeto 
   UpdateProd(CNT_SOL,PRE_SOL)
    {
        this.cant_sol = CNT_SOL ;
        this.prec_sol =PRE_SOL;
        this.ExecuteCal ();
    }
    ExecuteCal ()
    {
      this.CMG_POR_INV = Math.round(parseFloat( (((this.prec_sol -  this.cost_inv)/this.prec_sol)*100).toFixed(2)))  ; ////CMG % Inv 
       this.CMG_PROY = parseFloat(((this.prec_sol - this.cost_proy)/this.prec_sol).toFixed(2)); ////CMG % Proy
       this.Vta = parseFloat( (this.cant_sol * this.prec_sol)  ); ///Venta 
       this.CST = parseFloat( ( this.cant_sol *  this.cost_proy ).toFixed(2)  ) //Costo
       this.CMG  = parseFloat( ( this.Vta-this.CST ).toFixed(2)  );
       this.CMG_POR = Math.round(parseFloat( ( (this.CMG /this.Vta)*100   ).toFixed(2)))  ;
        ////***Calculos  Bonificacion  
       this.BO_cto_inv = parseFloat(( this.cost_inv * this.boniAplicar ).toFixed(2)) ;
       this.BO_cto_proy =parseFloat(( this.cost_proy *this.boniAplicar ).toFixed(2)) ; 
        
    }
    
    
    
}