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
						<?=str_replace("%20"," ",$nom);?>
						<small>
							<i class="ace-icon fa fa-angle-double-right"></i>
						</small>
					</h1>
				</div>
				<p>Estimada, [Nombre Centro Médico]</p>
				<p>Por medio de éste correo electrónico se confirma la reserva de atención médica ambulatoria con los siguientes datos:</p>
				<table align="center" border="1" width="90%">
					<tr>
						<th>DNI Afiliado:</th>
						<td>[N° Documento del afiliado]</td>
						<th>Nombre del Afiliado:</th>
						<td>[Nombres del Afiliado]</td>
					</tr>
					<tr>
						<th>Plan:</th>
						<td>[Nombre del plan]</td>
						<th>Especialidad:</th>
						<td>[Nombre de la especialidad]</td>
					</tr>
					<tr>
						<th>Fecha y Hora de reserva: </th>
						<td>[Fecha y Hora reservada]</td>
						<th>Observaciones:</th>
						<td>[Observaciones digitadas al realizar la reserva]</td>
					</tr>
				</table>
				<form method="post" action="<?=base_url()?>index.php/guardar_email">
					<fieldset>
					<input type="hidden" name="idplan" value="<?=$idplan?>">
						<textarea id="cuerpo_mail" name="cuerpo_mail" class="widgEditor nothing" placeholder="Digitar el contenido adicional del email  según el plan"><?=$p->cuerpo_mail;?></textarea>
					</fieldset>	
					<p>Para dudas &oacute; consultas comun&iacute;quese a nuestra central en Lima: 01-445-3019 &oacute; provincia: 0800-47676</p>				
					<p>Saludos cordiales,</p>
					<p>Atte. <?php echo strtoupper($nombres_col.' '.$ap_paterno_col.' '.$ap_materno_col); ?></p>
					<fieldset class="submit">
						<button class="btn btn-info" type="submit">Guardar</button>
					</fieldset>
				</form>
				<?php } ?>
			</div>
		</div>
	</div>
</body>

</html>
