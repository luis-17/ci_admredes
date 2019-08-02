<!DOCTYPE html>
<html lang="en" >

<head>
  <meta charset="UTF-8">
  <title>Random Login Form</title>
  <link rel="stylesheet" href="<?=base_url()?>public/assets/css/login/style.css" />
  <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/prefixfree/1.0.7/prefixfree.min.js"></script> -->
  <link rel="shortcut icon" type="image/x-icon" href="public/assets/css/login/icono.ico">

</head>

<body>

  <div class="body"></div>
		<div class="grad"></div>
		<div class="header">
			<!-- <div>Red<span>Salud</span></div> -->
			<img src="<?=base_url()?>public/assets/css/login/logo.png" alt="RedSalud">
		</div>
		<br>
		<div class="login">
			<form  method="post" role="form" action="<?=base_url()?>index.php/start_sesion">
				<input type="text" placeholder="Usuario" name="email"><br>
				<input type="password" placeholder="Contraseña" name="password"><br>
				<input type="submit" value="Ingresar">
			</form>
		</div>

		<?php
			if($this->session->flashdata('error')){
		?>
			<div class="alert alert-danger text-center" style="margin-top:20px;">
				<?php echo $this->session->flashdata('error'); ?>
			</div>
			<?php
			}?>
  <!-- <script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script> -->

  

</body>

</html>
