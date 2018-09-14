<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<!--
 * WYMeditor : what you see is What You Mean web-based editor
 * Copyright (c) 2005 - 2009 Jean-Francois Hovinne, http://www.wymeditor.org/
 * Dual licensed under the MIT (MIT-license.txt)
 * and GPL (GPL-license.txt) licenses.
 *
 * For further information visit:
 *        http://www.wymeditor.org/
 *
 * File Name:
 *        01-basic.html
 *        WYMeditor integration example.
 *        See the documentation for more info.
 *
 * File Authors:
 *        Jean-Francois Hovinne - http://www.hovinne.com/
-->
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>WYMeditor</title>
<!-- jQuery library is required, see http://jquery.com/ -->
<script type="text/javascript" src="<?=base_url()?>public/assets/js/jquery/jquery.js"></script>
<!-- WYMeditor main JS file, minified version -->
<script type="text/javascript" src="<?=base_url()?>public/assets/js/wymeditor/jquery.wymeditor.min.js"></script>

<script type="text/javascript">

/* Here we replace each element with class 'wymeditor'
 * (typically textareas) by a WYMeditor instance.
 * 
 * We could use the 'html' option, to initialize the editor's content.
 * If this option isn't set, the content is retrieved from
 * the element being replaced.
 */

jQuery(function() {
    jQuery('.wymeditor').wymeditor();
});

</script>

<!-- bootstrap & fontawesome -->
		<link rel="stylesheet" href="<?=  base_url()?>public/assets/css/bootstrap.css" />
		<link rel="stylesheet" href="<?=  base_url()?>public/assets/css/font-awesome.css" />

		<!-- page specific plugin styles -->

		<!-- text fonts -->
		<link rel="stylesheet" href="<?=  base_url()?>public/assets/css/ace-fonts.css" />

		<!-- ace styles -->
		<link rel="stylesheet" href="<?=  base_url()?>public/assets/css/ace.css" class="ace-main-stylesheet" id="main-ace-style" />

		<!-- jQuery library is required, see http://jquery.com/ -->
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
				<p>Digita el contenido del email de confirmación de reserva para Proveedores.</p>
				<form method="post" action="<?=base_url()?>index.php/guardar_email">
					<input type="hidden" name="idplan" value="<?=$idplan?>">
					<textarea class="wymeditor" name="cuerpo_mail"><?=$p->cuerpo_mail?></textarea>
					<br>
					<button class="btn btn-info" type="submit">
						<i class="ace-icon fa fa-check bigger-110"></i>
						Guardar
					</button>
				</form>
				<?php } ?>
			</div>
		</div>
	</div>
</body>

</html>
