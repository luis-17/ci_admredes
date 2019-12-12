 <!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta charset="utf-8" />
    <title>Sistema para la Gestión de Planes de Salud</title>

    <meta name="description" content="overview &amp; stats" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />

    <!-- bootstrap & fontawesome -->
    <link rel="stylesheet" href="<?=  base_url()?>public/assets/css/bootstrap.css" />
    <link rel="stylesheet" href="<?=  base_url()?>public/assets/css/font-awesome.css" />
    <script src="https://code.jquery.com/jquery-1.10.1.min.js"></script>

    <!-- page specific plugin styles -->

    <!-- text fonts -->
    <link rel="stylesheet" href="<?=  base_url()?>public/assets/css/ace-fonts.css" />

    <!-- ace styles -->
    <link rel="stylesheet" href="<?=  base_url()?>public/assets/css/ace.css" class="ace-main-stylesheet" id="main-ace-style" />
    <!--<script type="text/javascript" src="<?=  base_url()?>public/fancybox/lib/jquery.mousewheel-3.0.6.pack.js"></script>-->
    <!-- FancyBox -->
    <!-- Add jQuery library -->
    <script type="text/javascript" src="https://code.jquery.com/jquery-latest.min.js"></script>
    
    <!-- Add mousewheel plugin (this is optional) -->
    <script type="text/javascript" src="<?=  base_url()?>public/fancybox/lib/jquery.mousewheel-3.0.6.pack.js"></script>

    <!-- Add fancyBox -->
    <link rel="stylesheet" href="<?=  base_url()?>public/fancybox/source/jquery.fancybox.css?v=2.1.5" type="text/css" media="screen" />
    <script type="text/javascript" src="<?=  base_url()?>public/fancybox/source/jquery.fancybox.pack.js?v=2.1.5"></script>

    <script>
        $(".fancybox")
        .attr('rel', 'gallery')
        .fancybox({
            type: 'iframe',
            autoSize : false,
            beforeLoad : function() {         
                this.width  = parseInt(this.element.data('fancybox-width'));  
                this.height = parseInt(this.element.data('fancybox-height'));
            }
        });
    </script>
    <!-- inline styles related to this page -->

    <!-- ace settings handler -->
    <script src="<?=  base_url()?>public/assets/js/ace-extra.js"></script>

    <!-- HTML5shiv and Respond.js for IE8 to support HTML5 elements and media queries -->
    <!-- para paginacion -->
    <script src="<?=base_url()?>public/pagination/jquery.dataTables.min.css"></script>
    <script src="<?=base_url()?>public/pagination/jquery-1.12.4.js"></script>
    <script src="<?=base_url()?>public/pagination/jquery.dataTables.min.js"></script>
    <script src="<?=base_url()?>public/pagination/dataTables.bootstrap.min.js"></script>
  </head>
<body>

<!--<section class="seccion-bloque grad"> -->
  

      <!-- /section:basics/sidebar -->
      <div class="main-content">
        <div class="main-content-inner">

          <!-- /section:basics/content.breadcrumbs -->
          <div class="page-content">
            <!-- /section:settings.box -->
            <div class="page-header">
              <h1>
                Registrar Gasto
                <small>
                  <i class="ace-icon fa fa-angle-double-right"></i>
                </small>
              </h1>
            </div><!-- /.page-header -->
  <!--<div class="container">-->
      <div class="row">

                    <form action="<?=base_url()?>index.php/guardaGasto" method="post">  
                      <div class="table-responsive">
                          <table id = "tblGasto" class="table table-bordered table-striped table-highlight">
                            <thead>
                              <th>Detalle</th>
                              <th>Costo Bruto</th>
                              <th>Proveedor</th>
                              <th>Monto Neto a pagar</th>
                              <th>Nº Factura</th>
                              <th>Ap. Pago</th>
                            </thead>
                            <tbody>
                              <tr>
                                <td><p><b class="text-primary"></p> <input type="hidden" id= "idplan" name= "idplan" value=">"></td>
                                  <input type="hidden" name="idplandetalle" value="">
                                  <input type="hidden" id= "espe" name= "espe" value="">
                                  <input type="hidden" name="liqdetalleid" value="">
                                </td>
                                <td width="130px"><div class="input-group">
                                    <span class="input-group-addon">S/.</span>
                                    <input onkeyup="" type="number" id= "monto" name= "monto" placeholder="0,00" step="0.01" class="txtCal item1 form-control" value="" >
                                  </div>
                                </td>          
                                <td>                                          
                                  <select name="proveedor" class="prov form-control" id= "proveedor"  >
                                    <option value=0>--- Seleccionar Proveedor ---</option>
                            </select>  
                                </td>
                                <td  width="130px"><div id="sumaNeto" class="input-group">
                                  <span id= "netospan" class="input-group-addon">S/.</span>
                                  <input type="text" id= "neto" name= "neto" class="form-control txtCal2" placeholder="0,00" value="" /></div>
                                </td>
                                <td  width="200px"><input type="text" id= "factura" name= "factura" class="form-control" placeholder="000-000000" value="" /></td>
                                <td  width="3%"><input type="hidden" name="aprovpago" id="aprovpago" value="" />
                                  <input type="hidden" name="estado" id="estado" value="">
                                  <input type="checkbox" id="pago" name="pago"></td>          
                              </tr> 
                              <tr>
                              <td align="right"><span><b>TOTAL  :</b></span></td>
                              <td align="right"><b>S/. <span id="total_sum_value"></span></b></td>
                              <input type="hidden" name="cont" id="cont" value="">
                              <input type="hidden" name="liq_id" value="">
                              <input type="hidden" class="form-control" name="total" id="total" value="">
                              <input type="hidden" name="sin_id" value="">
                              <td align="right"><span><b>TOTAL NETO REDSALUD  :</b></span></td>
                              <td align="right"><b>S/. <span id="total_sum_value_neto"></span></b></td>
                              <input type="hidden" class="form-control" name="total_neto" id="total_neto" value="">
                          </tr>

                            </tbody>
                          </table>
                      </div>

                      <div class="row">
                        <div class="col-sm-4">
                          <div class="checkbox">
                         <label><input type="checkbox" id="trigger" name="question" onclick=""> El mismo proveedor factura todo el siniestro.</label>
                         <input type="hidden" id= "presscheck" name= "presscheck" value="1">
                        </div>
                        </div>
                        <div class="col-sm-4">
                        <div class="form-group" id="hidden_fields1">
                          <b class="text-primary">Proveedor:</b>
                          <select name="proveedorPrin" class="form-control" id= "proveedorPrin" disabled="true">
                                  <option value="0">--- Seleccionar Proveedor ---</option>
                          </select>
                        </div>
                        </div>
                        <div class="col-sm-4">
                          <div class="form-group" id="hidden_fields2">
                          <b class="text-primary">Ingrese Nº de Factura:</b>
                          <input type="text" class="form-control" value="" id="numFact" name="numFact" placeholder="000-000000" disabled="true">
                        </div>  
                        </div>                        
                      </div>

                      <div class="form-group" id="hidden_fields3">
                          <input type="checkbox" id="cerrar_atencion" name="cerrar_atencion" value="1">
                          <b class="text-primary">Cerrar Siniestro</b>
                      </div>

                      <fieldset style="padding-top: 25px;">
                        <input type="hidden" class="form-control" name="idsiniestro" id="idsiniestro" value="">

                        <div class="row">
                          <div class="col-sm-6">
                            <input class="btn btn-info" name="enviar" type="submit" value="Guardar">
                          </div>
                          

                        </div>
                      </fieldset>


                    </form>


      </div>   

    </div>
  </div> 
  </div>    
     <!--</div>-->
<!--</section>-->

    <!-- basic scripts -->

      <!--[if !IE]> -->
    <script type="text/javascript">
      window.jQuery || document.write("<script src='<?=  base_url()?>public/assets/js/jquery.js'>"+"<"+"/script>");
    </script>

    <!-- <![endif]-->

    <!--[if IE]>
<script type="text/javascript">
 window.jQuery || document.write("<script src='../assets/js/jquery1x.js'>"+"<"+"/script>");
</script>
<![endif]-->
    <script type="text/javascript">
      if('ontouchstart' in document.documentElement) document.write("<script src='<?=  base_url()?>public/assets/js/jquery.mobile.custom.js'>"+"<"+"/script>");
    </script>
    <script src="<?=  base_url()?>public/assets/js/bootstrap.js"></script>

    <!-- page specific plugin scripts -->

    <!-- ace scripts -->
    <script src="<?=  base_url()?>public/assets/js/ace/elements.scroller.js"></script>
    <script src="<?=  base_url()?>public/assets/js/ace/elements.colorpicker.js"></script>
    <script src="<?=  base_url()?>public/assets/js/ace/elements.fileinput.js"></script>
    <script src="<?=  base_url()?>public/assets/js/ace/elements.typeahead.js"></script>
    <script src="<?=  base_url()?>public/assets/js/ace/elements.wysiwyg.js"></script>
    <script src="<?=  base_url()?>public/assets/js/ace/elements.spinner.js"></script>
    <script src="<?=  base_url()?>public/assets/js/ace/elements.treeview.js"></script>
    <script src="<?=  base_url()?>public/assets/js/ace/elements.wizard.js"></script>
    <script src="<?=  base_url()?>public/assets/js/ace/elements.aside.js"></script>
    <script src="<?=  base_url()?>public/assets/js/ace/ace.js"></script>
    <script src="<?=  base_url()?>public/assets/js/ace/ace.ajax-content.js"></script>
    <script src="<?=  base_url()?>public/assets/js/ace/ace.touch-drag.js"></script>
    <script src="<?=  base_url()?>public/assets/js/ace/ace.sidebar.js"></script>
    <script src="<?=  base_url()?>public/assets/js/ace/ace.sidebar-scroll-1.js"></script>
    <script src="<?=  base_url()?>public/assets/js/ace/ace.submenu-hover.js"></script>
    <script src="<?=  base_url()?>public/assets/js/ace/ace.widget-box.js"></script>
    <script src="<?=  base_url()?>public/assets/js/ace/ace.settings.js"></script>
    <script src="<?=  base_url()?>public/assets/js/ace/ace.settings-rtl.js"></script>
    <script src="<?=  base_url()?>public/assets/js/ace/ace.settings-skin.js"></script>
    <script src="<?=  base_url()?>public/assets/js/ace/ace.widget-on-reload.js"></script>
    <script src="<?=  base_url()?>public/assets/js/ace/ace.searchbox-autocomplete.js"></script>

    <!-- inline scripts related to this page -->

    <!-- the following scripts are used in demo only for onpage help and you don't need them -->
    <link rel="stylesheet" href="<?=  base_url()?>public/assets/css/ace.onpage-help.css" />
    <link rel="stylesheet" href="<?=  base_url()?>public/docs/assets/js/themes/sunburst.css" />

    <script type="text/javascript"> ace.vars['base'] = '..'; </script>
    <script src="<?=  base_url()?>public/assets/js/ace/elements.onpage-help.js"></script>
    <script src="<?=  base_url()?>public/assets/js/ace/ace.onpage-help.js"></script>
    <script src="<?=  base_url()?>public/docs/assets/js/rainbow.js"></script>
    <script src="<?=  base_url()?>public/docs/assets/js/language/generic.js"></script>
    <script src="<?=  base_url()?>public/docs/assets/js/language/html.js"></script>
    <script src="<?=  base_url()?>public/docs/assets/js/language/css.js"></script>
    <script src="<?=  base_url()?>public/docs/assets/js/language/javascript.js"></script>

</body></html>