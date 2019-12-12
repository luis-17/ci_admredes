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
                Registrar Triaje
                <small>
                  <i class="ace-icon fa fa-angle-double-right"></i>
                </small>
              </h1>
            </div><!-- /.page-header -->
  <!--<div class="container">-->
      <div class="row">
      </div>   
        <form method="post" action="<?=base_url()?>index.php/reg_triaje">
          <input type="hidden" name="idasegurado" value="<?=$idasegurado?>">
          <input type="hidden" name="idsiniestro" value="<?=$idsiniestro?>">
          <input type="hidden" name="idtriaje" value="<?=$idtriaje?>">
          <div class="row">
            <div class="col-12 col-sm-6 col-md-6">
              <div class="form-group">
                <input type="text" class="form-control" value="<?=$nombre_esp?>" placeholder="Especialidad" disabled>
              </div>
            </div>
            <div class="col-12 col-sm-6 col-md-6">
              <div class="form-group">
                <input type="text" name="motivo" class="form-control" value="<?=$motivo_consulta?>" placeholder="Motivo de Consulta" required>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-12 col-sm-4 col-md-4">
              <div class="form-group">
                <input type="text" class="form-control" value="<?=$presion_arterial_mm?>" name="pa" placeholder="PA(Presión Arterial)">
              </div>
            </div>
            <div class="col-12 col-sm-4 col-md-4">
              <div class="form-group">
                <input type="text" name="fc" class="form-control" value="<?=$frec_cardiaca?>" placeholder="FC(Frecuencia Cardiaca)">
              </div>
            </div>
             <div class="col-12 col-sm-4 col-md-4">
              <div class="form-group">
                <input type="text" name="fr" class="form-control" value="<?=$frec_respiratoria?>" placeholder="FR(Frecuencia Respiratoria)">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-12 col-sm-2 col-md-2">
              <div class="form-group">
                <input type="text" class="form-control" name="peso" value="<?=$peso?>" placeholder="Peso(kg)">
              </div>
            </div>
            <div class="col-12 col-sm-2 col-md-2">
              <div class="form-group">
                <input type="text" name="talla" class="form-control" value="<?=$talla?>" placeholder="Talla(cm)">
              </div>
            </div>
             <div class="col-12 col-sm-8 col-md-8">
              <div class="form-group">
                <input type="text" name="cabeza" class="form-control" value="<?=$estado_cabeza?>" placeholder="Cabeza">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-12 col-sm-12 col-md-12">
              <div class="form-group">
                <input type="text" name="piel_faneras" class="form-control" value="<?=$piel_faneras?>" placeholder="Piel y Faneras">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-12 col-sm-12 col-md-12">
              <div class="form-group">
                <input type="text" name="cv_cr" class="form-control" value="<?=$cv_ruido_cardiaco?>" placeholder="CV:CR(Cardiovascular: Ruidos Cardiacos)">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-12 col-sm-12 col-md-12">
              <div class="form-group">
                <input type="text" name="tp_mv" class="form-control" value="<?=$tp_murmullo_vesicular?>" placeholder="TP:MV(Tóraz y Pulmones: Murmullo Vesicular)">
              </div>
            </div>
          </div>
           <div class="row">
            <div class="col-12 col-sm-6 col-md-6">
              <div class="form-group">
                <input type="text" name="abdomen" class="form-control" value="<?=$estado_abdomen?>" placeholder="Abdomen">
              </div>
            </div>
            <div class="col-12 col-sm-6 col-md-6">
              <div class="form-group">
                <input type="text" name="rha" class="form-control" value="<?=$ruido_hidroaereo?>" placeholder="RHA(Ruidos hidroaéreos)">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-12 col-sm-12 col-md-12">
              <div class="form-group">
                <input type="text" name="neuro" class="form-control" value="<?=$estado_neurologico?>" placeholder="Neuro">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-12 col-sm-12 col-md-12">
              <div class="form-group">
                <input type="text" name="osteomuscular" class="form-control" value="<?=$estado_osteomuscular?>" placeholder="Osteomuscular">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-12 col-sm-6 col-md-6">
              <div class="form-group">
                <input type="text" name="gu_ppl" class="form-control" value="<?=$gu_puno_percusion_lumbar?>" placeholder="GU:PPL (Genito-Urinario: Puño Percusión Lumbar)">
              </div>
            </div>
            <div class="col-12 col-sm-6 col-md-6">
              <div class="form-group">
                <input type="text" name="gu_pru" class="form-control" value="<?=$gu_puntos_reno_uretelares?>" placeholder="GU:PRU (Genito-Urinario: Puntos Renouretelares)">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-12 col-sm-12 col-md-12">
              <div class="form-group" style="text-align: right;">
                <input type="submit" class="btn btn-custom btn-danger" value="Guardar y Continuar">
              </div>
            </div>
          </div>
        </form>
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