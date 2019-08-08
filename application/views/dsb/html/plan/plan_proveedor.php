<?php
				$user = $this->session->userdata('user');
				extract($user);
			?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "https://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<!--
 * WYMeditor : what you see is What You Mean web-based editor
 * Copyright (c) 2005 - 2009 Jean-Francois Hovinne, https://www.wymeditor.org/
 * Dual licensed under the MIT (MIT-license.txt)
 * and GPL (GPL-license.txt) licenses.
 *
 * For further information visit:
 *        https://www.wymeditor.org/
 *
 * File Name:
 *        01-basic.html
 *        WYMeditor integration example.
 *        See the documentation for more info.
 *
 * File Authors:
 *        Jean-Francois Hovinne - https://www.hovinne.com/
-->
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>WYMeditor</title>
<meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
<meta name="author" content="The Man in Blue" />
<meta name="robots" content="all" />
<meta name="MSSmartTagsPreventParsing" content="true" />
<meta name="description" content="" />
<meta name="keywords" content="" />
<script src="https://code.jquery.com/jquery-1.10.1.min.js"></script>

<style type="text/css" media="all">
	@import "<?=base_url()?>public/assets/css/main.css";
	@import "<?=base_url()?>public/assets/css/widgEditor.css";
</style>

<script type="text/javascript" src="<?=base_url()?>public/assets/scripts/widgEditor.js"></script>

<!-- bootstrap & fontawesome -->
		<link rel="stylesheet" href="<?=  base_url()?>public/assets/css/bootstrap.css" />
		<link rel="stylesheet" href="<?=  base_url()?>public/assets/css/font-awesome.css" />

		<!-- page specific plugin styles -->

		<!-- text fonts -->
		<link rel="stylesheet" href="<?=  base_url()?>public/assets/css/ace-fonts.css" />

		<!-- ace styles -->
		<link rel="stylesheet" href="<?=  base_url()?>public/assets/css/ace.css" class="ace-main-stylesheet" id="main-ace-style" />

		<!-- jQuery library is required, see https://jquery.com/ -->
		<script type="text/javascript" src="<?=base_url()?>public/assets/js/jquery/jquery.js"></script>
		<!-- WYMeditor main JS file, minified version -->
		<script type="text/javascript" src="<?=base_url()?>public/assets/js/wymeditor/jquery.wymeditor.min.js"></script>


</head>

<body>
	<div class="main-container" id="main-container">
		<div class="main-content">
			<div class="main-content-inner">
				<?php foreach($plan as $p){?>
				<div class="page-header">
					<h1>
						<?=str_replace("%20"," ",'Editar proveedores');?>
						<small>
							<i class="ace-icon fa fa-angle-double-right"></i>
						</small>
					</h1>
				</div>
				<div class="row">
					<div class="col-xs-12">
						<form class="form-horizontal" id="formCheck" name="formCheck" role="form" method="post" action="<?=base_url()?>index.php/save_proveedor">	

								<div class="tabbable">
									<!-- #section:pages/faq -->
									<div class="col-xs-12">
									<?php if(!empty($proveedores)){ ?>
													<table id="example" class="table table-striped table-bordered table-hover"  style="font-size: 12px;">
														<thead>
															<tr>
																<th>ID</th>
																<th>Nombre Comercial</th>
																<th>Dirección</th>
															</tr>
														</thead>

														<tbody style="font-size: 12px;">
														<?php foreach ($proveedores as $pr) {?>
															<tr>
																<td align="right"><?=$pr->idproveedor?></td>
																<td><?=$pr->nombre_comercial_pr?></td>
																<td><?=$pr->direccion_pr?></td>
															</tr>
														<?php } ?>
														</tbody>
													</table>
									<?php } ?>
												</div>

												<script>			
													//para paginacion
													$(document).ready(function() {
													$('#example').DataTable( {
													"pagingType": "full_numbers"
													} );
												} );
												</script>	
												<br>
								<!-- end table -->
							</div><!-- /.col -->
							<br>
							<input type="hidden" name="idplan" id="idplan" value="<?=$p->idplan?>">	
							<div class="form-group" id="detalle_proveedor"></div>
							<div class="col-md-offset-3 col-md-9" align="center">
								<button class="btn btn-info" type="submit">
									<i class="ace-icon fa fa-check bigger-110"></i>Guardar
								</button>
							</div>
						</form>

					</div>
				</div>
				<?php } ?>
			</div>
		</div>
	</div>
<!-- basic scripts -->
		<!--[if !IE]> -->
		<script type="text/javascript">
			window.jQuery || document.write("<script src='<?=  base_url()?>public/assets/js/jquery.js'>"+"<"+"/script>");
		</script><script src="<?=  base_url()?>public/assets/js/jquery.js"></script>

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
		<script src="<?=  base_url()?>public/assets/js/dataTables/jquery.dataTables.js"></script>
		<script src="<?=  base_url()?>public/assets/js/dataTables/jquery.dataTables.bootstrap.js"></script>
		<script src="<?=  base_url()?>public/assets/js/dataTables/extensions/TableTools/js/dataTables.tableTools.js"></script>
		<script src="<?=  base_url()?>public/assets/js/dataTables/extensions/ColVis/js/dataTables.colVis.js"></script>

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

		

		<!-- the following scripts are used in demo only for onpage help and you don't need them -->
		<link rel="stylesheet" href="<?=  base_url()?>public/assets/css/ace.onpage-help.css">
		<link rel="stylesheet" href="<?=  base_url()?>public/docs/assets/js/themes/sunburst.css">
		<script src="<?=  base_url()?>public/assets/js/ace/elements.onpage-help.js"></script>
		<script src="<?=  base_url()?>public/assets/js/ace/ace.onpage-help.js"></script>
		<script src="<?=  base_url()?>public/docs/assets/js/rainbow.js"></script>
		<script src="<?=  base_url()?>public/docs/assets/js/language/generic.js"></script>
		<script src="<?=  base_url()?>public/docs/assets/js/language/html.js"></script>
		<script src="<?=  base_url()?>public/docs/assets/js/language/css.js"></script>
		<script src="<?=  base_url()?>public/docs/assets/js/language/javascript.js"></script>

		<script>
			$(document).ready(function(){
	            $.ajax({
	                url: "<?= BASE_URL()?>index.php/Plan_cnt/detalle_proveedor",
	                type: 'POST',
	                dataType: 'json',
	                data: $("#formCheck").serialize(),
	                success: function(data)
	                {   
	                	$("#detalle_proveedor").html(null);
                		$("#detalle_proveedor").html(data);
	                }
	            });
	            return false;
			});

	function marcar(source) 
	{
		checkboxes=document.getElementsByTagName('input'); //obtenemos todos los controles del tipo Input
		for(i=0;i<checkboxes.length;i++) //recoremos todos los controles
		{
			if(checkboxes[i].type == "checkbox") //solo si es un checkbox entramos
			{
				checkboxes[i].checked=source.checked; //si es un checkbox le damos el valor del checkbox que lo llamó (Marcar/Desmarcar Todos)
			}
		}
	}
		</script>
</body>

</html>
