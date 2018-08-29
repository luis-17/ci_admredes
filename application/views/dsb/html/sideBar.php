
<div id="sidebar" class="sidebar responsive">
				<script type="text/javascript">
					try{ace.settings.check('sidebar' , 'fixed')}catch(e){}
				</script>

				<div class="sidebar-shortcuts" id="sidebar-shortcuts">

					<div class="sidebar-shortcuts-mini" id="sidebar-shortcuts-mini">
						<span class="btn btn-success"></span>

						<span class="btn btn-info"></span>

						<span class="btn btn-warning"></span>

						<span class="btn btn-danger"></span>
					</div>
				</div><!-- /.sidebar-shortcuts -->

				<ul class="nav nav-list">
					<li class="active">
						<a href="<?=base_url();?>">
							<i class="menu-icon fa fa-tachometer"></i>
							<span class="menu-text"> Menú </span>
						</a>

						<b class="arrow"></b>
					</li>

					<?php foreach ($menu1 as $u):
						$idmenu1=$u->Id;
					?>

					<li class="">
						<a href="#" class="dropdown-toggle">
							<i class="menu-icon fa <?=$u->icono;?>"></i>
							<span class="menu-text">
								<?=$u->menu; ?>
							</span>

							<b class="arrow fa fa-angle-down"></b>
						</a>
						<b class="arrow"></b>

						<?php foreach ($menu2 as $uv):
							if ($idmenu1==$uv->idmenu) : ?>

								<ul class="submenu">
									<li class="">

										<a href="<?php echo base_url()?>index.php/<?=$uv->archivo?>">
											<i class="menu-icon fa fa-caret-right"></i>

											<?=$uv->submenu;?>
										</a>

										<!-- <a href="<?php //echo base_url().$uv->archivo; ?>">
											<i class="menu-icon fa fa-caret-right"></i>

											<?=$uv->submenu;?>
											
											<b class="arrow fa fa-angle-down"></b>
										</a> -->
									</li>
								</ul>

						<?php  
						endif;

						endforeach; ?>

					</li>

					<?php endforeach; ?>
				</ul>

				<!-- #section:basics/sidebar.layout.minimize -->
				<div class="sidebar-toggle sidebar-collapse" id="sidebar-collapse">
					<i class="ace-icon fa fa-angle-double-left" data-icon1="ace-icon fa fa-angle-double-left" data-icon2="ace-icon fa fa-angle-double-right"></i>
				</div>

				<!-- /section:basics/sidebar.layout.minimize -->
				<script type="text/javascript">
					try{ace.settings.check('sidebar' , 'collapsed')}catch(e){}
				</script>
			</div>