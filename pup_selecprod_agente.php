<?php
/////pup_selecprod_agente.php
/*
********	 INFORMACION ARCHIVO ***************** 
	Nombre  Archivo : pup_selecprod_agente.php
 	Fecha  Creacion : 02/05/2017    
	Descripcion  : 
 *              Escrip  Diseñado  para  Seleccionar los productos A solicitar por 
 *              el Agente.
 *         Modificaciones : 
 *              08/05/2017    Se  Agrega  la  variable    UbdatePub encargada  de controlar cuando se  realiza  una modificacion a
 *                            una solicitud del  pedido. 
  */
////**Inicio De Session 
	session_start();
          $MM_restrictGoTo = "index.php";
   if (!(isset($_SESSION['usuario_valido']))){   
      header("Location: ". $MM_restrictGoTo); 
  exit;
}
///****Cabecera Cronos 
	require_once('header.php');
///***Conexion Mysql  
	require_once('Connections/conecta1.php');
require_once('formato_datos.php');   //para evitar poner la funcion de conversion de tipos de datos

////***+Opcion de Modificacion  Activada 
if( $_SESSION['UbdatePub'] ==1 )
{
    ///***Obtenemos  N Folio 
    $N_FOLIO = filter_input(INPUT_POST,'updateSol');
    $_SESSION['NFuPDATE'] =$N_FOLIO; 

}





?>
<!--JQuery jquery.min.js" -->
<script src="bower_components/jquery/dist/jquery.min.js"></script>

<style>
	#header .header-top {border-bottom:50px solid #5D6A87;}
	#top-categ span,
	#tptnmobilemenu .toggler {background-color:#2AC97A;}
	.blockcart {background-color:#F5535E;}
	.product-title a {color:#105BD5;}
	.product-price-and-shipping .price,
	.product-price {color:#F13340;}
	li.new {background-color:#2AC97A;}
	li.discount,
	li.on-sale {background-color:#F5535E;}
	.btn-primary {background:#FF9F00;}
	.tptncarousel{background:#fff;border:1px solid #ddd;margin-top:30px;position:relative;overflow:hidden}
	.tptncarousel h4{border-bottom:1px solid #e5e5e5;font-size:15px;margin:0;padding:15px 60px 15px 15px;text-transform:uppercase}
	.tptncarousel .prodcrsl{overflow:hidden}
	.tptncarousel .owl-wrapper{display:none;position:relative;-webkit-transform:translateZ(0);-moz-transform:translateZ(0);-ms-transform:translateZ(0)}
	.tptncarousel .owl-item{float:left;border-right:1px solid #e5e5e5}
	.tptncarousel .product-miniature{float:none;padding:0;width:auto}
	.tptncarousel .owl-controls{position:absolute;top:15px;right:15px}
	.tptncarousel .owl-controls .owl-buttons div{color:#aaa;cursor:pointer;font-family:Material Icons;font-size:18px;float:left;transition:all .5s}
	.tptncarousel .owl-controls .owl-buttons div:hover{color:#333}.tptncarousel .owl-controls .owl-prev{margin-right:10px}

	.tptncarousel .owl-controls .owl-prev{margin-right:10px}.tptncarousel .owl-controls .owl-prev:before{content:"\E5C4"}.tptncarousel .owl-controls .owl-next:before{content:"\E5C8"}.tptncarousel .no-products{color:#888;font-size:14px;margin:0;padding:15px}#tptnbrands .brand-item{padding:15px;text-align:center}#tptnbrands a:hover img{opacity:.6}#tptnmobilemenu{position:absolute;left:15px;z-index:101}#tptnmobilemenu .toggler{font-size:24px;color:#fff;cursor:pointer;text-align:center;height:50px;line-height:50px;width:50px}#tptnmobilemenu .toggler:after{font-family:Material Icons;content:"\E5D2"}#tptnmobilemenu .toggler.active:after{content:"\E5CD"}#tptnmobilemenu .mobile-menu{background:#fff;border-left:1px solid #ddd;border-right:1px solid #ddd;display:none;position:absolute;left:0;top:50px;width:300px}


	#notifications ul{margin-bottom:0}.product-flags li{color:#fff;font-size:12px;padding:4px 8px;cursor:default;position:absolute;top:15px;z-index:100}.product-flags li.new{left:15px}.product-flags li.discount{right:15px}.product-flags li.on-sale{left:15px;top:40px}.product-flags li.online-only{background:#888;left:15px;top:65px}.product-flags li.pack{background:#888;left:15px;top:90px}#left-column .block-column{background:#fff;border:1px solid #ddd;margin-top:30px}#left-column .block-column h4{border-bottom:1px solid #e5e5e5;font-size:15px;padding:14px 15px;margin:0;text-transform:uppercase}

	.product-miniature .product-description{border-top:1px solid #e5e5e5;padding:15px 0 0}.product-miniature .product-title{font-size:13px;font-weight:400}

	.product-miniature .thumbnail-container:hover .highlighted-informations{display:block}.product-miniature .thumbnail-container:hover .highlighted-informations.no-variants{display:none}.product-miniature img{vertical-align:top}.product-miniature .product-description{border-top:1px solid #e5e5e5;padding:15px 0 0}
	.product-miniature .product-title{font-size:13px;font-weight:400}.product-miniature .product-title a:hover{color:#888}.product-miniature .product-price-and-shipping{margin:10px 0 0;font-size:15px}.product-miniature .product-price-and-shipping span{margin:0 2px}.product-miniature .product-price-and-shipping .regular-price{color:#aaa;text-decoration:line-through}.product-miniature .highlighted-informations{background:hsla(0,0%,100%,.8);position:absolute;bottom:75px;display:none;width:100%;padding:10px 0;text-align:center}.product-miniature .highlighted-informations .color{margin:0 3px;vertical-align:middle}.pagination{margin-top:0;display:block}.pagination>div:first-child{line-height:2.5rem}.pagination .page-list{border:1px solid #ddd;float:right}.pagination .page-list li{border-left:1px solid #ddd;float:left}.pagination .page-list li:first-child{border-left:none}



	.product-flags li{color:#fff;font-size:12px;padding:4px 8px;cursor:default;position:absolute;top:15px;z-index:100}.product-flags li.new{left:15px}.product-flags li.discount{right:15px}.product-flags li.on-sale{left:15px;top:40px}.product-flags li.online-only{background:#888;left:15px;top:65px}.product-flags li.pack{background:#888;left:15px;top:90px}


	.product-flags li{color:#fff;font-size:12px;padding:4px 8px;cursor:default;position:absolute;top:15px;z-index:100}.product-flags li.new{left:15px}.product-flags li.discount{right:15px}.product-flags li.on-sale{left:15px;top:40px}.product-flags li.online-only{background:#888;left:15px;top:65px}.product-flags li.pack{background:#888;left:15px;top:90px}
	
     input.CANTINT.form-control {
    margin-bottom: 16px;
    margin-left: 41px;
    margin-top: 8px;
    }
    button.ADDBTN.btn.btn-sucess.btn-sm {
    margin-left: 40px;
    margin-top: 11px;
    }
    h1.h3.product-title {
    font-size: 17px;
    margin-top: -7px;
    }
   button#btn_analizar {
    background-color: #39a4ec;
    border-bottom-color: rgba(82, 211, 255, 0.78);
}
button.ADDBTN.btn.btn-sucess.btn-sm {
    margin-left: 40px;
    margin-top: 11px;
    background-color: #13a529;
}
</style>
<script type="text/javascript" src="pub_scrip_publicidad/pub_func_publicidad.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
		var     json_arre_cve_prod;
		//**Btn  Analizar *******************
                 $('#btn_analizar').click(function(event){
                      $.ajax({
                            type:'POST',
                            url: 'pub_scrip_publicidad/pubGetCveProd.php',
                            success: function (datos) { 
                                 json_arre_cve_prod = JSON.parse(datos.COD)
                                 ///console.log(json_arre_cve_prod[0]);
                                 
                              /// console.log(GeArrCanti(json_arre_cve_prod));
                              $('#SenInfo').val(GeArrCanti(json_arre_cve_prod));
                             
                            }
                         });
                        /// console.log(result)
                        
                        
                 });//**Fin Btn Analizar
                
                 
                 
                ///**Btn Add Elemento
                $(document).on("click",".ADDBTN",function(){
                   ///***Obtenemos Identificador  Input Cantidad
                   var Identificador =$(this).attr('idinput');
                   //***Generamos  Class Cantidad
                   var class_Cant = ".inp_cant"+Identificador; 
                   //**Obtenemos el value del INPUT
                   var value_cantida =  $(class_Cant).val();
                   ///Generamos Json Para envio
                    
                   ///console.log(GeArrByOne(Identificador));
                    $('#SenInfo').val(GeArrByOne(Identificador));
                    //***
                   
                });//*Fin Btn  Add Elemento
                
	});
</script>

<div  class="container">
	<!--Incio contenedor-->
	<!---------Incio Articulos------------>
	<section class="tptncarousel clearfix">
                <div   class="row">
                    <div class="col-sm-4"><h3>Productos</h3></div>
                    <br> 
                    <div class="col-sm-8">
                        <div   class="col-sm-6"></div> 
                        <div   class="col-sm-6">
                            <div class="col-sm-6">
                                  <button id="btn_analizar" type="button" class="btn-sm btn-info">Analizar Todo</button>
                            </div>
                            <div class="col-sm-6"> 
                            <form action="pub_gensol_agente.php" method ="post">
                                <input name="SenInfo"  id="SenInfo"  type="text" hidden ></input>
                                <button  type="submit" class="btn-sm btn-info">Generar Solicitud</button>
                            </form>
                            </div>
                        </div>
                    </div>
                </div> 
                <div class="prodcrsl" style="opacity: 1; display: block;">
                     <?php
                     ///****Codigo  para  Agregar  todos  los productos
                     ///****Realizamos la  Consulta de los Productos  que  tengan  Imagen
                    $qery_get_Productos = mysqli_query($conecta1, "SELECT * FROM pedidos.pub_catalogo_publicidad  where  imagen_prod !='' ");
                    $cont_elem =1; ///Contador de elementos para mostrar  en una linea
                     while($row = mysqli_fetch_array($qery_get_Productos))
                    { 
                    if($cont_elem==1){   
                        ///***Div  Separador de Elementos
                        echo '<div class="owl-wrapper-outer"><div class="owl-wrapper" style="width: 2884px; left: 0px; display: block;">';
                    }
                   ?>
			        <!-----Inicio Producto----->
                                <div class="owl-item" style="width: 206px;">
					<article class="product-miniature js-product-miniature col-xs-12 col-sm-6 col-lg-4" data-id-product="1" data-id-product-attribute="46" itemscope="" itemtype="http://schema.org/Product">
						<div class="thumbnail-container">
							<a href="#" class="thumbnail product-thumbnail">
                                                            <img src=<?php echo "pub_catalogo/".$row['imagen_prod'] ;?> alt="">
							</a>
							<div class="product-description">
								<h1 class="h3 product-title" itemprop="name"><?php echo $row['articulo'] ;?></h1>
								<div class="product-price-and-shipping">
								
                                                                    <div  class="row">
                                                                        <div class="col-sm-6"> 
                                                                            
                                                                            <input  type="number"  placeholder ="Cantidad"  class="inp_cant<?php echo  $row['codig_prod']; ?> CANTINT input-sm  form-control"
                                                                             <?php ////***Validacion para  Obtener la  Cantidad  Si la  Opcion esta en Modificar              
                                                                                  
                                                                             if( $_SESSION['UbdatePub'] ==1 )
                                                                                {   
                                                                                       ///***Generamos  consulta 
                                                                                         $string_getProd = sprintf("SELECT  cantidad_solici FROM pedidos.pub_detalle_publicidad  where  pub_folio =%s and pub_cvepro =%s ",
                                                                                                GetSQLValueString($N_FOLIO, "int"),
                                                                                                  GetSQLValueString($row['codig_prod'], "text")  
                                                                                                    );
                                                                                   //Realizaos la Peticion 
                                                                                   $mwqery_sol = mysqli_query($conecta1, $string_getProd) ;
                                                                                    ///***Realizamos  Qery 
                                                                                    $resulfetchCant = mysqli_fetch_array($mwqery_sol);
                                                                                    ///**Imprimimos  Cantidad
                                                                                    echo  'value="'.$resulfetchCant['cantidad_solici'].'"';
                                                                                   
                                                                                 }
                                                                         ///***Final Codigo Input para Modificar ?>  >
                                                                         
                                                                        </div>
                                                                         <div class="col-sm-6">
                                                                             <button   type="button"   class="ADDBTN btn btn-sucess btn-sm" idinput="<?php echo  $row['codig_prod'];?>" > <span class="glyphicon glyphicon-plus"></span> </button>
                                                                         </div>
                                                                    </div> 
								</div>
							</div>
							
			      			</div>
                                        </article>
			      </div>
                              <!------Fin Producto---------------->
                    <?php      
                    if($cont_elem==1){   
                        ///***Div  Separador de Elementos
                        echo ' </div>';
                      }
                    if($cont_elem==5){ $cont_elem = 1 ;}
                    ///**Incrementamos 
                    $cont_elem ++;
                    }//fin  Cliclo  While ?>
             </div>
                     
		</div>
	</section>
	<!--Fin Cotenedor-->  
        
</div> 
<script type="text/javascript" src="/pub_scrip_publicidad/bottom-ca3789.js"></script>
<?php   
///****Agregamos  fOOT
require_once('foot.php');
?> 


