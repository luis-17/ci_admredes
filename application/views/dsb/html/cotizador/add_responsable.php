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
	<!-- para paginacion -->
		<script src="<?=base_url()?>public/pagination/jquery.dataTables.min.css"></script>
		<script src="<?=base_url()?>public/pagination/jquery-1.12.4.js"></script>
		<script src="<?=base_url()?>public/pagination/jquery.dataTables.min.js"></script>
		<script src="<?=base_url()?>public/pagination/dataTables.bootstrap.min.js"></script>


</head>

<body style="">	
			<!-- /section:basics/sidebar -->
			<div class="page-content">
						<div class="page-header">
							<h1>
								<?=str_replace("%20"," ",$nom);?>: Usuarios Asignados			
							</h1>
						</div>
						<div class="row">
							<div class="col-xs-9">
								<!-- PAGE CONTENT BEGINS -->
								<form class="form-horizontal" role="form" method="post" action="<?=base_url()?>index.php/reg_plan_usuario">
									<div class="form-group">
										<input type="hidden" name="idplan" id="idplan" value="<?=$idplan?>">
										<input type="hidden" name="nom" value="<?=$nom?>">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Usuario: </label>
										<div class="col-sm-5">
											<select name="idusuario" id="idusuario" required="true">
												<option value="">Seleccionar</option>
												<?php foreach ($usuarios as $u) {?>
													<option value="<?=$u->idusuario?>"><?=$u->colaborador?></option>
												<?php } ?>
											</select>
										</div>
									</div>
									<div class="clearfix form-actions">
										<div class="col-md-offset-3 col-md-5">
											<button class="btn btn-info" type="submit" id="guardar" name="guardar" value="eliminar">
												<i class="ace-icon fa fa-check bigger-110"></i>
												Agregar Usuario 
											</button>
										</div>
									</div>
								</form>
							</div><!-- /.col -->

							<div class="col-xs-12">
							<br>
								<table id="example" class="table table-striped table-bordered table-hover"  style="font-size: 12px;">
									<thead>
										<th>ID</th>
										<th>Colaborador</th>
										<th>Tipo</th>
										<th width="5%"></th>
									</thead>
									<tbody>
										<?php foreach ($personal_asignado as $p) { ?>
											<tr>
												<td><?=$p->idplanusuario?></td>
												<td><?=$p->colaborador?></td>
												<td><?php if($p->tipo_responsable=='P'){ echo 'Responsable';}else{ echo 'Apoyo';} ?></td>												
												<td width="5%">
													<?php if($p->tipo_responsable=='A'){ ?><a title="Eliminar BLoqueo" href="<?=base_url()?>index.php/eliminar_responsable/<?=$p->idplanusuario?>/<?=$idplan?>/<?=$nom?>"><i class="ace-icon glyphicon glyphicon-trash blue"></i></a><?php } ?></td>
											</tr>
										<?php } ?>
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
								<br>
							</div>
						</div>

					</div>
		<!-- basic scripts -->
</body>

</html>
