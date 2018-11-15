<!DOCTYPE html>
<html lang="en">
	<head>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
		<meta charset="utf-8" />
		<title>Sistema para la Gestión de Planes de Salud</title>

		<meta name="description" content="with draggable and editable events" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />

		<!-- bootstrap & fontawesome -->
		<link rel="stylesheet" href="<?=base_url()?>public/assets/css/bootstrap.css" />
		<link rel="stylesheet" href="<?=base_url()?>public/assets/css/font-awesome.css" />

		<!-- page specific plugin styles -->
		<link rel="stylesheet" href="<?=base_url()?>public/assets/css/jquery-ui.custom.css" />
		<link rel="stylesheet" href="<?=base_url()?>public/assets/css/fullcalendar.css" />

		<!-- text fonts -->
		<link rel="stylesheet" href="<?=base_url()?>public/assets/css/ace-fonts.css" />

		<!-- ace styles -->
		<link rel="stylesheet" href="<?=base_url()?>public/assets/css/ace.css" class="ace-main-stylesheet" id="main-ace-style" />

		<!--[if lte IE 9]>
			<link rel="stylesheet" href="../assets/css/ace-part2.css" class="ace-main-stylesheet" />
		<![endif]-->

		<!--[if lte IE 9]>
		  <link rel="stylesheet" href="../assets/css/ace-ie.css" />
		<![endif]-->

		<!-- inline styles related to this page -->

		<!-- ace settings handler -->
		<script src="<?=base_url()?>public/assets/js/ace-extra.js"></script>

		<!-- HTML5shiv and Respond.js for IE8 to support HTML5 elements and media queries -->

		<!--[if lte IE 8]>
		<script src="../assets/js/html5shiv.js"></script>
		<script src="../assets/js/respond.js"></script>
		<![endif]-->

		<!-- Include Date Range Picker -->
		<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/js/bootstrap-datepicker.min.js"></script>
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/css/bootstrap-datepicker3.css"/>

		<!-- para paginacion -->
		<script src="<?=base_url()?>public/pagination/jquery.dataTables.min.css"></script>
		<script src="<?=base_url()?>public/pagination/jquery-1.12.4.js"></script>
		<script src="<?=base_url()?>public/pagination/jquery.dataTables.min.js"></script>
		<script src="<?=base_url()?>public/pagination/dataTables.bootstrap.min.js"></script>

		
		<!-- pass var to model from foreach -->
		<script type="text/javascript">
			
			$(document).on("click", ".open-registerPay", function () {
		     var liqdetalleid = $(this).data('id');
		     $(".modal-body #liqdetalleid").val( liqdetalleid );
		     // As pointed out in comments, 
		     // it is superfluous to have to manually call the modal.
		     // $('#addBookDialog').modal('show');
		});

		</script>



		<script>		
			$(document).ready(function(){
		    $('#aseg_numDoc').on('change',function(){
		        var dniAseg = $('#aseg_numDoc').val();
		        $.ajax({
		            type:'POST',
		            url:'<?=base_url()?>public/population/getEmployeeId.php',
		            dataType: "json",
		            data:{dniAseg:dniAseg},
		            success:function(data){
		                if(data.status == 'ok'){
		                    $('#aseg_nom1').val(data.result.aseg_nom1);
		                    $('#aseg_ape1').val(data.result.aseg_ape1);
		                    $('#aseg_ape2').val(data.result.aseg_ape2);
		                    //$('#aseg_fechNac').val(data.result.aseg_fechNac);
		                    $('#aseg_id').val(data.result.aseg_id);
		                    //var aseg_id = 	data.result.aseg_id                    
		                    $('.user-content').slideDown();
		                    

		                }else{
		                    $('.user-content').slideUp();
		                    alert("User not found...");
		                } 
		            }
		        });

		        $.ajax({		            

		            url:"<?=base_url()?>public/population/getPlan.php",
			        type:'POST',
			        data:{dniAseg:dniAseg},
			        success:function(response) {
			          //var resp = $.trim(response);
			          if(response != '') {
			            $("#plan").removeAttr('disabled','disabled').html(response);
			            
			          } else {
			            $("#plan").attr('disabled','disabled').html("<option value=''>------- Select --------</option>");
			          }
			        }

		        });		        
		        
			});
		});
		</script>

		<script>
		   $(document).ready(function() {
		   $(window).keydown(function(event){
		     if(event.keyCode == 13) {
		       event.preventDefault();
		       return false;
		     }
		   });
		 });
		</script>


	</head>

	<body class="no-skin">
		<!-- #section:basics/navbar.layout -->
		<?php include ("/../headBar.php");?>

		<!-- /section:basics/navbar.layout -->
		<div class="main-container" id="main-container">
			<script type="text/javascript">
				try{ace.settings.check('main-container' , 'fixed')}catch(e){}
			</script>

			<!-- #section:basics/sidebar -->
			<?php include ("/../sideBar.php");?>
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

							<li class="active">
								Liquidaciones
							</li>
						</ul><!-- /.breadcrumb -->

						<!-- /section:basics/content.searchbox -->
					</div>

					<!-- /section:basics/content.breadcrumbs -->
					<div class="page-content">
						<!-- /section:settings.box -->
						<div class="page-header">
							<h1>
								Liquidaciones
								<small>
									<i class="ace-icon fa fa-angle-double-right"></i>
								</small>
							</h1>
						</div><!-- /.page-header -->

						<div class="row">
							<div class="col-xs-12">
								<!-- PAGE CONTENT BEGINS -->
								<div class="tabbable">
									<!-- #section:pages/faq -->
									<ul class="nav nav-tabs padding-18 tab-size-bigger" id="myTab">
										<li class="active">
											<a data-toggle="tab" href="#faq-tab-1">
												Por Liquidar
											</a>
										</li>
										<li>
											<a data-toggle="tab" href="#faq-tab-2">
												Comp. Liquidados
											</a>
										</li>
																				
									</ul>

									<!-- /section:pages/faq -->
									<div class="tab-content no-border padding-24">
										<div id="faq-tab-1" class="tab-pane fade in active">								

											<!-- star table -->		
												<div class="col-xs-12">
													<table id="example" class="table table-striped table-bordered table-hover">
														<thead>
															<tr>
																<th>OA</th>
																<th>Proveedor</th>			
																<th>Detalle Servicio</th>
																<th>Nº Factura</th>
																<th>Monto</th>
																<th>Estado</th>
																<th>Registrar Pago</th>
															</tr>
														</thead>

														<tbody>
															<?php foreach($liquidaciones as $o):
																
																$fecha=$o->fecha_atencion;
																$fecha=date("d/m/Y", strtotime($fecha));
																?>

															<tr>										<td>OA<?=$o->num_orden_atencion;?></td>
																				
																<td><?=$o->nombre_comercial_pr?></td>
																<td>
																	
																</td>
																<td><?=$o->liqdetalle_numfact?></td>
																<td><?=$fecha;?></td>
																<td>
																	<?php switch ($o->liqdetalle_aprovpago) {
																	    case "0":
																	        echo "<span class='label label-default'>No Establecido</span>";
																	        break;
																	    case "1":
																	        echo "<span class='label label-primary'>Aprobado Pago</span>";
																	        break;
																	    case "2":
																	        echo "<span class='label label-success'>Pagado</span>";
																	        break;					    
																	}
																	?>		
																</td>						
																<td>
	<div class="hidden-sm hidden-xs btn-group">&nbsp;
		<button type="button" data-id="<?=$o->liqdetalleid?>" class="open-registerPay btn btn-danger btn-sm" data-toggle="modal" data-target=".bs-example-modal-lg">Registrar Pago</button>
																	</div>	
																</td>
															</tr>
														<?php endforeach; ?>
														</tbody>
													</table>
												</div>
												<!-- end table -->									

									<div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
									  <div class="modal-dialog modal-lg" role="document">
									    <div class="modal-content">
									      <div class="modal-header">
									        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
									        <h4 class="modal-title" id="myModalLabel">Registro de Pago a Proveedor</h4>
									      </div>
									      <div class="modal-body">
									       <form id="creaSin" action="<?=base_url()?>index.php/registraPago" method="post">
											<div class="row">
											  <div class="col-sm-4">
											  	<div class="form-group">
													<b class="text-primary">Fecha de Pago:</b>
									                <input class="form-control" id="input-date" name="pagoFecha" type="date">
												</div>
											  	
											  </div>
											  <div class="col-sm-4">
											  	<div class="form-group">
													<b class="text-primary">Forma de Pago:</b>
													<select name="pagoForma" class="form-control" id="pagoForma">
														<option>------- Select --------</option>
														<option value="Transferencia Bancaria">Transferencia Bancaria</option>
														<option value="Cheque">Cheque</option>
														<option value="Depósito en Efectivo">Depósito en Efectivo</option>
													</select>
												</div>

											  </div>
											  <div class="col-sm-4">
											  	<input type="hidden" name="liqdetalleid" id="liqdetalleid"/>
											  </div>
											</div>
											<div class="row">
											  <div class="col-sm-4">
											  	<div class="form-group">
													<b class="text-primary">Banco:</b>
													<select name="pagoBanco" class="form-control" id="pagoBanco">
														<option>------- Select --------</option>
														<option value="Banco de Comercio">Banco de Comercio</option>
														<option value="Banco de Crédito del Perú">Banco de Crédito del Perú</option>
														<option value="Banco Interamericano de Finanzas (BANBIF)">Banco Interamericano de Finanzas (BANBIF)</option>
														<option value="Banco Financiero">Banco Financiero</option>
														<option value="BBVA Continental">BBVA Continental</option>
														<option value="Interbank">Interbank</option>
														<option value="MiBanco">MiBanco</option>
														<option value="Scotiabank Perú">Scotiabank Perú</option>
														<option value="Banco GNB">Banco GNB</option>
														<option value="Banco Falabella">Banco Falabella</option>
														<option value="Banco Ripley">Banco Ripley</option>
														<option value="Banco Santander Perú">Banco Santander Perú</option>
														<option value="Banco Azteca">Banco Azteca</option>
														<option value="ICBC PERU BANK">ICBC PERU BANK</option>
													</select>
												</div>
											  </div>
											  <div class="col-sm-4">
											  	<div class="form-group">
													<b class="text-primary">Nº Operación:</b>
													<input class="form-control" type="text" name="pagoNoperacion" id="pagoNoperacion"/>            
												</div>	
											  </div>
											  <div class="col-sm-4">
											  	<div class="form-group">
													<b class="text-primary">Nº Factura de Pago:</b>
													<input class="form-control" type="text" name="pagoNfactura" id="pagoNfactura"/>            
												</div>
											  </div>
											</div>

											<div class="modal-footer">
									        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
									        <button type="submit" class="btn btn-primary">Guardar</button>
									      </div>
										</form>
									      </div>
									      
									    </div>
									  </div>
									</div>


												<script>			
													//para paginacion
													$(document).ready(function() {
													    $('#example').DataTable( {
													        "pagingType": "full_numbers"
													    } );
													} );
												</script>


										</div>

										
								</div>

								<!-- PAGE CONTENT ENDS -->
							</div><!-- /.col -->
						</div><!-- /.row -->
					</div><!-- /.page-content -->
				</div>
			</div><!-- /.main-content -->

			<div class="footer">
				<div class="footer-inner">
					<!-- #section:basics/footer -->
					<div class="footer-content">
						<span class="bigger-120">
							<span class="blue bolder">Red Salud Admin</span>
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

		<!--[if !IE]> -->
		<script type="text/javascript">
			window.jQuery || document.write("<script src='<?=base_url()?>public/assets/js/jquery.js'>"+"<"+"/script>");
		</script>

		<!-- <![endif]-->

		<!--[if IE]>
<script type="text/javascript">
 window.jQuery || document.write("<script src='../assets/js/jquery1x.js'>"+"<"+"/script>");
</script>
<![endif]-->
		<script type="text/javascript">
			if('ontouchstart' in document.documentElement) document.write("<<?=base_url()?>script src='public/assets/js/jquery.mobile.custom.js'>"+"<"+"/script>");
		</script>
		<script src="<?=base_url()?>public/assets/js/bootstrap.js"></script>

		
		<!-- page specific plugin scripts -->

		<!-- ace scripts -->
		<!-- <script src="<?=base_url()?>public/assets/js/ace/elements.scroller.js"></script> -->
		<!-- <script src="<?=base_url()?>public/assets/js/ace/elements.colorpicker.js"></script> -->
		<!-- <script src="<?=base_url()?>public/assets/js/ace/elements.fileinput.js"></script> -->
		<!-- <script src="<?=base_url()?>public/assets/js/ace/elements.typeahead.js"></script> -->
		<!-- <script src="<?=base_url()?>public/assets/js/ace/elements.wysiwyg.js"></script> -->
		<!-- <script src="<?=base_url()?>public/assets/js/ace/elements.spinner.js"></script> -->
		<!-- <script src="<?=base_url()?>public/assets/js/ace/elements.treeview.js"></script> -->
		<!-- <script src="<?=base_url()?>public/assets/js/ace/elements.wizard.js"></script> -->
		<!-- <script src="<?=base_url()?>public/assets/js/ace/elements.aside.js"></script> -->
		<script src="<?=base_url()?>public/assets/js/ace/ace.js"></script>
		<!-- <script src="<?=base_url()?>public/assets/js/ace/ace.ajax-content.js"></script> -->
		<!-- <script src="<?=base_url()?>public/assets/js/ace/ace.touch-drag.js"></script> -->
		<script src="<?=base_url()?>public/assets/js/ace/ace.sidebar.js"></script>
		<!-- <script src="<?=base_url()?>public/assets/js/ace/ace.sidebar-scroll-1.js"></script> -->
		<!-- <script src="<?=base_url()?>public/assets/js/ace/ace.submenu-hover.js"></script> -->
		<!-- <script src="<?=base_url()?>public/assets/js/ace/ace.widget-box.js"></script>
		<script src="<?=base_url()?>public/assets/js/ace/ace.settings.js"></script>
		<script src="<?=base_url()?>public/assets/js/ace/ace.settings-rtl.js"></script>
		<script src="<?=base_url()?>public/assets/js/ace/ace.settings-skin.js"></script>
		<script src="<?=base_url()?>public/assets/js/ace/ace.widget-on-reload.js"></script>
		<script src="<?=base_url()?>public/assets/js/ace/ace.searchbox-autocomplete.js"></script> -->

		<!-- inline scripts related to this page -->

		<!-- the following scripts are used in demo only for onpage help and you don't need them -->
		<!-- <link rel="stylesheet" href="public/assets/css/ace.onpage-help.css" />
		<link rel="stylesheet" href="public/docs/assets/js/themes/sunburst.css" /> -->

		<!-- <script type="text/javascript"> ace.vars['base'] = '..'; </script>
		<script src="<?=base_url()?>public/assets/js/ace/elements.onpage-help.js"></script>
		<script src="<?=base_url()?>public/assets/js/ace/ace.onpage-help.js"></script>
		<script src="<?=base_url()?>public/docs/assets/js/rainbow.js"></script>
		<script src="<?=base_url()?>public/docs/assets/js/language/generic.js"></script>
		<script src="<?=base_url()?>public/docs/assets/js/language/html.js"></script>
		<script src="<?=base_url()?>public/docs/assets/js/language/css.js"></script>
		<script src="<?=base_url()?>public/docs/assets/js/language/javascript.js"></script> -->		

	</body>
</html>
