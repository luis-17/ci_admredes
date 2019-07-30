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
		<script type="text/javascript">
	    /*funcion ajax que llena el combo dependiendo de la categoria seleccionada*/
	    $(document).ready(function() {  
		 	$("#item").change(function(){  
			   var url = '<?php echo base_url()?>index.php/detalle_producto'; 
			   var id = $('#item').val();
		  	 	$.ajax({  
			    url: url,  
			    data: { id : id },  
			    type: "POST",  
			    success:function(data){                
			     $("#detalle_producto").html(data);  
			    }  
		  		});  
		 	});
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

	<body class="no-skin">
		<!-- #section:basics/navbar.layout -->
		<?php include (APPPATH."views/dsb/html/headBar.php");?>

		<!-- /section:basics/navbar.layout -->
		<div class="main-container" id="main-container">
			<script type="text/javascript">
				try{ace.settings.check('main-container' , 'fixed')}catch(e){}
			</script>

			<!-- #section:basics/sidebar -->
			<?php include (APPPATH."views/dsb/html/sideBar.php");?>
			<!-- end nav. -->

			<!-- /section:basics/sidebar -->
			<div class="main-content">
				<div class="main-content-inner">
					<!-- #section:basics/content.breadcrumbs -->
					<div class="breadcrumbs" id="breadcrumbs">
						<script type="text/javascript">
							try{ace.settings.check('breadcrumbs' , 'fixed')}catch(e){}
						</script>

						<ul class="breadcrumb">
							<li>
								<i class="ace-icon fa fa-home home-icon"></i>
								<a href="<?=base_url()?>">Inicio</a>
							</li>
							<li><a href="<?=base_url()?>index.php/plan">Planes</a></li>
							<li class="active">Coberturas</li>
						</ul><!-- /.breadcrumb -->
						<!-- /section:basics/content.searchbox -->
					</div>

					<!-- /section:basics/content.breadcrumbs -->
					<div class="page-content">
						<!-- #section:settings.box -->
						<div class="page-header">
							<h1>	
							Plan: <?=str_replace("%20"," ",$nom);?>						
							</h1>
						</div>
						<div class="row">
							<div class="col-xs-12">
								<!-- PAGE CONTENT BEGINS -->
								<form class="form-horizontal" id="form" name="form" role="form" method="post" action="<?=base_url()?>index.php/guardar_cobertura">
									<input type="hidden" id="nom" name="nom" value="<?=$nom;?>">
									<input type="hidden" name="idplan" id="idplan" value="<?=$id?>">
									<input type="hidden" id="idplandetalle" name="idplandetalle" value="<?=$iddet?>" />
									<?php 
									if($iddet==0):
										$item=0;
										$desc="";
										$visible=2;
										$style='disabled';
										$chk='';
										$flg='';
										$op='';
										$val='';
										$tiempo="";
										$num_eventos="";
										$inicio="";
										$fin="";
										$num1="";
										$tiempo1="";
										$num2="";
										$tiempo2="";
										else:
											foreach ($detalle as $det) :
												$item=$det->idvariableplan;
												$desc=$det->texto_web;
												$visible=$det->visible;
												$flg=$det->flg_liquidacion;
												$op=$det->simbolo_detalle;
												$val=$det->valor_detalle;
												$tiempo= $det->tiempo;
												$num_eventos=$det->num_eventos;
												$inicio=$det->iniVig;
												$fin=$det->finVig;
												$num1=$det->num1;
												$tiempo1=$det->tiempo1;
												$num2=$det->num2;
												$tiempo2=$det->tiempo2;
											endforeach;
									endif;
									?>
									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-1">Cobertura: </label>

										<div class="col-sm-9">
											<select id="item" name="item" value="" class="col-xs-10 col-sm-5">
												<option value="">Seleccionar</option>
												<?php foreach($items as $i): 
													if($i->idvariableplan==$item):
														$esti="selected";
														else:
															$esti="";
													endif;?>
												<option value="<?=$i->idvariableplan;?>" <?=$esti;?> ><?=$i->nombre_var;?></option>
												<?php endforeach; ?>
											</select>
										</div>
									</div>

									<div style="font-size: 9px;" class="form-group" id="detalle_producto"><?=$cadena;?></div>

									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Descripción: </label>

										<div class="col-sm-9">
											<textarea  class="col-xs-10 col-sm-5" id="descripcion" name="descripcion" cols="36" rows="4" placeholder="Escribe aquí una descripción"><?=$desc;?></textarea>
										</div>
									</div>

									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-1">Visible en redes: </label>
										<div class="col-sm-9">
											<input type="radio" name="visible" value="1" <?php if($visible==1): echo "checked"; endif; ?>><label>Sí</label>&nbsp;&nbsp;&nbsp;
											<input type="radio" name="visible" value="0" <?php if($visible==0): echo "checked"; endif; ?>><label>No</label>
										</div>
									</div>

									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-1">Inicio de Vigencia: </label>
										<div class="col-sm-9">
											<input onclick="ocultar_inicio();" type="radio" name="inicio" value="0" <?php if($inicio==0){ echo "checked";}?>><label>Con el Certificado</label>&nbsp;&nbsp;&nbsp;
											<input onclick="mostrar_inicio();" type="radio" name="inicio" value="1"<?php if($inicio<>0){ echo "checked";}?>><label>Otro</label>
										</div>
									</div>

									<div class="form-group" id="inicio_vig"  <?php if($inicio==0 || $inicio=""){ echo 'style="display:none"'; } ?>>
										<label class="col-sm-3 control-label no-padding-right" for="form-field-1"></label>

										<div class="col-sm-9">
											<input style="width: 5%" type="number" id="num" name="num" value="<?=$num1?>" />&nbsp;&nbsp;&nbsp;
											<select name="tiempo" id="tiempo">
												<option>Seleccionar</option>
												<option <?php if($tiempo1=="day"){echo "selected";} ?> value="day">día(s)</option>
												<option <?php if($tiempo1=="month"){echo "selected";} ?> value="month">mes(es)</option>
												<option <?php if($tiempo1=="year"){echo "selected";} ?> value="year">año(s)</option>
											</select>
										</div>
									</div>

									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-1">Fin de Vigencia: </label>

										<div class="col-sm-9">
											<input onclick="ocultar_fin();" type="radio" name="fin" value="0" <?php if($fin==0){ echo "checked";}?>><label>Con el Certificado</label>&nbsp;&nbsp;&nbsp;
											<input onclick="mostrar_fin();" type="radio" name="fin" value="1"<?php if($fin<>0){ echo "checked";}?>><label>Otro</label>
										</div>
									</div>
									<div class="form-group" id="fin_vig" <?php if($fin==0 || $fin=""){ echo 'style="display:none"'; } ?>>
										<label class="col-sm-3 control-label no-padding-right" for="form-field-1"></label>

										<div class="col-sm-9">
											<input style="width: 5%" type="number" id="num2" name="num2" value="<?=$num2?>" />&nbsp;&nbsp;&nbsp;
											<select name="tiempo2" id="tiempo2">
												<option>Seleccionar</option>
												<option <?php if($tiempo2=="day"){echo "selected";} ?> value="day">día(s)</option>
												<option <?php if($tiempo2=="month"){echo "selected";} ?> value="month">mes(es)</option>
												<option <?php if($tiempo2=="year"){echo "selected";} ?> value="year">año(s)</option>
											</select>
										</div>
									</div>
										<div class="col-md-offset-3 col-md-7" align="center">
											<button class="btn btn-success" type="submit" id="guardar" name="guardar" value="guardar">
												<i class="ce-icon fa fa-check bigger-110"></i>
												Guardar
											</button>
											<button class="btn btn-info" type="submit" name="guardar" value="cancelar">
												<i class="ace-icon glyphicon glyphicon-remove abigger-110"></i>
												Cancelar
											</button>
										</div>
								</form>
								<div class="tabbable">
										<!-- #section:pages/faq -->
										<div class="col-xs-12">
											<br>
														<table id="example" class="table table-bordered table-hover"  style="font-size: 12px;">
															<thead>
																<tr>
																	<th>Cobertura</th>
																	<th width="30%">Descripción</th>
																	<th>Visible</th>																	
																	<th>Inicio de Vigencia</th>
																	<th>Fin de Vigencia</th>
																	<th>Coaseguro</th>
																	<th>Eventos</th>
																	<th>Estado</th>
																	<th></th>
																</tr>
															</thead>
															<tbody style="font-size: 12px;">
																<?php $cont=0;
																foreach($cobertura as $c):
																	if($c->visible==1):
																		$visible='Sí';
																		else:
																			$visible='No';
																	endif;
																	if($c->estado_pd==1):
																		$estado="Activo";
																		$titulo="Anular Cobertura";
																		$boton='ace-icon glyphicon glyphicon-trash';
																		$funcion="cobertura_anular";
																		else:
																			$estado="Inactivo";
																			$titulo="Activar Cobertura";
																			$boton='ace-icon glyphicon glyphicon-ok';
																			$funcion="cobertura_activar";
																	endif;
																	$cont=$cont+1;

																			$num=$c->num_eventos;
																			switch ($c->tiempo) {
																				case '1 month':
																					$tiempo= '<a class="boton fancybox"  data-fancybox-width="600" data-fancybox-height="400" title="Editar Evento" href="'.base_url().'index.php/eventos/'.$c->idplandetalle.'">'.$num." Menual(es) </a>";
																					break;															
																				case '2 month':
																					$tiempo= '<a class="boton fancybox"  data-fancybox-width="600" data-fancybox-height="400" title="Editar Evento" href="'.base_url().'index.php/eventos/'.$c->idplandetalle.'">'.$num." Bimestral(es)</a>";
																					break;
																				case '3 month':
																					$tiempo= '<a class="boton fancybox"  data-fancybox-width="600" data-fancybox-height="400" title="Editar Evento" href="'.base_url().'index.php/eventos/'.$c->idplandetalle.'">'.$num." Trimestral(es)</a>";
																					break;
																				case '6 month':
																					$tiempo= '<a class="boton fancybox"  data-fancybox-width="600" data-fancybox-height="400" title="Editar Evento" href="'.base_url().'index.php/eventos/'.$c->idplandetalle.'">'.$num." Semestral(es)</a>";
																					break;
																				case '1 year':
																						$tiempo= '<a class="boton fancybox"  data-fancybox-width="600" data-fancybox-height="400" title="Editar Evento" href="'.base_url().'index.php/eventos/'.$c->idplandetalle.'">'.$num." Anual(es)</a>";
																					break;
																				case 'ilimitados':
																						$tiempo= "Ilimitados";
																					break;
																				default:
																					$num="";
																					$tiempo='<a class="boton fancybox"  data-fancybox-width="600" data-fancybox-height="400" title="Agregar Evento" href="'.base_url().'index.php/eventos/'.$c->idplandetalle.'"><i class="ace-icon glyphicon glyphicon-plus red"></i></a>';
																					break;
																			}

																			$tiempo11=$c->tiempo11;
																			$tiempo22=$c->tiempo22;

																			switch ($tiempo11) {
																				case 'day':
																					$tiempo11 = "día(s)";
																					break;
																				case 'month':
																					$tiempo11 = "mes(es)";
																					break;
																				case 'year':
																					$tiempo11 = "año(s)";
																					break;	
																				default:
																					$tiempo11 = $c->tiempo11;
																					break;
																			}

																			switch ($tiempo22) {
																				case 'day':
																					$tiempo22 = "día(s)";
																					break;
																				case 'month':
																					$tiempo22 = "mes(es)";
																					break;
																				case 'year':
																					$tiempo22 = "año(s)";
																					break;		
																				default:
																					$tiempo22 = $c->tiempo22;
																					break;
																			}
																			$num11 = $c->num11;
																			$num22 = $c->num22;
																	?>
																	<tr>
																		<td><?=$c->nombre_var;?></td>
																		<td  width="30%"><?=$c->texto_web;?></td>
																		<td><?=$visible?></td>
																		<td><?php if($num11==0 && $tiempo11==''){ echo "Con el Certificado";} else { echo $num11.' '.$tiempo11;}?></td>
																		<td><?php if($num22==0 && $tiempo22==''){ echo "Con el Certificado";} else { echo $num22.' '.$tiempo22;}?></td>
																		<td><a class="boton fancybox"  data-fancybox-width="800" data-fancybox-height="700" href="<?=base_url()?>index.php/coaseguro/<?=$c->idplandetalle;?>" title="Agregar Coaseguro" href=""><i class="ace-icon glyphicon glyphicon-plus red"></i></a> <?=$c->cobertura?></td>
																		<td><?=$tiempo?></td>
																		<td><?=$estado;?></td>
																		<td>
																			<div class="hidden-sm hidden-xs btn-group">
																				<div title="Editar Cobertura" style="float:left;cursor:pointer;" class="ui-pg-div ui-inline-edit" id="jEditButton_12" onclick="" data-original-title="Edit selected row">
																					<a href="<?=base_url()?>index.php/seleccionar_cobertura/<?=$id?>/<?=$nom?>/<?=$c->idplandetalle;?>">
																						<i class="ace-icon fa fa-pencil bigger-120 blue"></i>
																					</a>
																				</div>
																				<div title="Bloquear Otras Coberturas" style="float:left;cursor:pointer;" class="ui-pg-div ui-inline-edit" id="jEditButton_12" onclick="" data-original-title="Edit selected row">
																					&nbsp;<a class="boton fancybox"  data-fancybox-width="800" data-fancybox-height="700" href="<?=base_url()?>index.php/bloqueo/<?=$c->idplandetalle;?>">
																						<i class="ace-icon fa fa-ban blue"></i>
																					</a>
																				</div>
																				<div title="<?=$titulo?>" style="float:left;cursor:pointer;" class="ui-pg-div ui-inline-edit" id="jEditButton_12" onclick="" data-original-title="Edit selected row">
																					&nbsp;<a href="<?=base_url()?>index.php/<?=$funcion?>/<?=$id?>/<?=$nom?>/<?=$c->idplandetalle;?>">
																						<i class="<?=$boton?> blue"></i>
																					</a>
																				</div>
																			</div>
																			<div class="hidden-md hidden-lg">
																				<div class="inline pos-rel">
																					<button class="btn btn-minier btn-info dropdown-toggle" data-toggle="dropdown" data-position="auto">
																						<i class="ace-icon fa fa-cog icon-only bigger-110"></i>
																					</button>

																					<ul class="dropdown-menu dropdown-only-icon dropdown-yellow dropdown-menu-right dropdown-caret dropdown-close">
																						<li>
																							<div title="Editar Cobertura" style="float:left;cursor:pointer;" class="ui-pg-div ui-inline-edit" id="jEditButton_12" onclick="" data-original-title="Edit selected row">
																								<a href="<?=base_url()?>index.php/seleccionar_cobertura/<?=$id?>/<?=$nom?>/<?=$c->idplandetalle;?>">
																									<i class="ace-icon fa fa-pencil bigger-120 blue"></i>
																								</a>
																							</div>
																						</li>
																						<li>
																							<div title="Bloquear Otras Coberturas" style="float:left;cursor:pointer;" class="ui-pg-div ui-inline-edit" id="jEditButton_12" onclick="" data-original-title="Edit selected row">
																								&nbsp;<a class="boton fancybox"  data-fancybox-width="800" data-fancybox-height="700" href="<?=base_url()?>index.php/bloqueo/<?=$c->idplandetalle;?>">
																									<i class="ace-icon fa fa-ban blue"></i>
																								</a>
																							</div>
																						</li>
																						<li>
																							<div title="<?=$titulo?>" style="float:left;cursor:pointer;" class="ui-pg-div ui-inline-edit" id="jEditButton_12" onclick="" data-original-title="Edit selected row">
																								&nbsp;<a href="<?=base_url()?>index.php/<?=$funcion?>/<?=$id?>/<?=$nom?>/<?=$c->idplandetalle;?>">
																								<i class="<?=$boton?> blue"></i>
																								</a>
																							</div>
																						</li>																						
																					</ul>
																				</div>
																			</div>
																		</td>
																	</tr>
																<?php endforeach; ?>
															</tbody>
														</table>
														<script>			
										//para paginacion
										$(document).ready(function() {
										$('#example').DataTable( {
										"pagingType": "full_numbers"
										} );
									} );
									</script>
										</div>
									<!-- end table -->
								</div><!-- /.col -->
							</div>
						</div>
					</div><!-- /.page-content -->
				</div>
			</div><!-- /.main-content -->

			<div class="footer">
				<div class="footer-inner">
					<!-- #section:basics/footer -->
					<div class="footer-content">
						<span class="bigger-120">
							<span class="blue bolder">Red Salud</span>
							Application &copy; 2018
						</span>

						&nbsp; &nbsp;
					</div>

					<!-- /section:basics/footer -->
				</div>
			</div>

			<a href="#" id="btn-scroll-up" class="btn-scroll-up btn btn-sm btn-inverse">
				<i class="ace-icon fa fa-angle-double-up icon-only bigger-110"></i>
			</a>
		</div><!-- /.main-container -->

		<!-- basic scripts -->

		<!-- fin scripts paginacion -->

		<!--[if !IE]> -->
		<script>
			function ocultar_inicio(){
				document.getElementById('inicio_vig').style.display='none';
			}

			function mostrar_inicio(){
				document.getElementById('inicio_vig').style.display='block';
			}

			function ocultar_fin(){
				document.getElementById('fin_vig').style.display='none';
			}

			function mostrar_fin(){
				document.getElementById('fin_vig').style.display='block';
			}


			function habilitar(value)
			{
				if(value==true)
				{
					// habilitamos
					document.form.valor.disabled=false;
					document.form.operador.disabled=false;
					document.form.flg_liqui.value='Sí';
				}else{
					// deshabilitamos
					document.form.operador.disabled=true;
					document.form.operador.value='';
					document.form.valor.disabled=true;
					document.form.valor.value='';
					document.form.flg_liqui.value='No';
				}
			}

			function tiempo(value)
			{
				if(value==''){
					document.form.num_eventos.disabled=true;
				}else if(value=='Ilimitados'){
					document.form.num_eventos.value=1000;
					document.form.num_eventos.disabled=false;
				}
				else{
					document.form.num_eventos.disabled=false;
				}
			}
		</script>
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
	</body>
</html>
