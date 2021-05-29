		<div class="navbar-inner">
			<div class="container-fluid">
				<!--BEGIN SIDEBAR TOGGLE-->
				<div class="sidebar-toggle-box hidden-phone">
					<div class="icon-reorder"></div>
				</div>
				<!--END SIDEBAR TOGGLE-->
				<!-- BEGIN LOGO -->
				<a class="brand" href="index.php">
					<img src="/img/duemidia_white.png" alt="DueMidia" />
				</a>
				<!-- END LOGO -->
				<!-- BEGIN RESPONSIVE MENU TOGGLER -->
				<a class="btn btn-navbar collapsed" id="main_menu_trigger" data-toggle="collapse" data-target=".nav-collapse">
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="arrow"></span>
				</a>
				<!-- END RESPONSIVE MENU TOGGLER -->
				<div id="top_menu" class="nav notify-row">
					<!-- BEGIN NOTIFICATION -->
					<ul class="nav top-menu" id="count-carrinho">
						<!-- BEGIN NOTIFICATION CONSULTA PCP-->
					<?php
					if($_SESSION["mod_plantel"] == 1){
						
						$busca_cobranca_dia	= "
						SELECT 
							COUNT(*) AS Total
						FROM (
							SELECT
								ukey,
								(SELECT ukey_status FROM mp_status_pedido WHERE ukey_pedidos = mp_pedidos.ukey ORDER BY ukey DESC LIMIT 0,1) ukey_status,
								(SELECT cobranca FROM mp_status_pedido WHERE ukey_pedidos = mp_pedidos.ukey ORDER BY ukey DESC LIMIT 0,1) cobranca
							FROM mp_pedidos 
						) DADOS
						WHERE (ukey_status <> '7' OR ukey_status IS NULL) AND cobranca = '".$date."'";
						$sql_cobranca_dia = mysqli_query($con, $busca_cobranca_dia) or die("ERROR COBRANCA NOTIFICATION 001");
						$result_cobranca_dia = mysqli_fetch_array($sql_cobranca_dia);
						$total_cobranca_dia	= $result_cobranca_dia["Total"];
						
						$busca_cobranca_mes	= "
						SELECT 
							COUNT(*) AS Total
						FROM (
							SELECT
								ukey,
								(SELECT ukey_status FROM mp_status_pedido WHERE ukey_pedidos = mp_pedidos.ukey ORDER BY ukey DESC LIMIT 0,1) ukey_status,
								(SELECT cobranca FROM mp_status_pedido WHERE ukey_pedidos = mp_pedidos.ukey ORDER BY ukey DESC LIMIT 0,1) cobranca
							FROM mp_pedidos 
						) DADOS
						WHERE (ukey_status <> '7' OR ukey_status IS NULL) AND MONTH(cobranca) = '".$month."' AND YEAR(cobranca) = '".$year."'";
						$sql_cobranca_mes = mysqli_query($con, $busca_cobranca_mes) or die("ERROR COBRANCA NOTIFICATION 001");
						$result_cobranca_mes = mysqli_fetch_array($sql_cobranca_mes);
						$total_cobranca_mes	= $result_cobranca_mes["Total"];
					?>
						
						<li class="dropdown" id="header_notification_bar">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown">
								<i class="icon-bell-alt"></i>
								<span class="badge badge-warning"><?php echo $total_cobranca_dia; ?></span>
							</a>
							<ul class="dropdown-menu extended notification">
								<li>
									<p>Você possui <?php echo $total_cobranca_dia; ?> cobranças para hoje.</p>
								</li>
							
							<?php
							$busca_cobranca_not	= "
							SELECT 
								*
							FROM (
								SELECT 
									ukey, 
									pi, 
									(SELECT fantasia FROM mp_client WHERE ukey = mp_pedidos.ukey_client) cliente, 
									(SELECT fantasia FROM mp_sellers WHERE ukey = mp_pedidos.ukey_sellers) vendedor, 
									emissao,
									(SELECT ukey_status FROM mp_status_pedido WHERE ukey_pedidos = mp_pedidos.ukey ORDER BY ukey DESC LIMIT 0,1) ukey_status,
									(SELECT cobranca FROM mp_status_pedido WHERE ukey_pedidos = mp_pedidos.ukey ORDER BY ukey DESC LIMIT 0,1) cobranca
								FROM mp_pedidos
							) DADOS
							WHERE (ukey_status <> '7' OR ukey_status IS NULL) AND cobranca = '".$date_today."'";
							$sql_cobranca_not	= mysqli_query($con, $busca_cobranca_not) or die("ERROR COBRANCA NOTIFICATION 002");

							while ($cobranca_not = mysqli_fetch_array($sql_cobranca_not)){
								$ukey_cobranca_not		= $cobranca_not["ukey"];
								$pi_cobranca_not		= $cobranca_not["pi"];
								$cliente_cobranca_not	= $cobranca_not["cliente"];
								$vendedor_cobranca_not	= $cobranca_not["vendedor"];
								$emissao_cobranca_not	= $cobranca_not["emissao"];
							?>
								
								<li>
									<a href="#">
										<span class="label label-warning"><i class="icon-bell"></i></span>
										<?php echo $ukey_cobranca_not; ?> - <?php echo $cliente_cobranca_not; ?>
										<span class="small italic"><?php echo $emissao_cobranca_not; ?></span>
									</a>
								</li>
							<?php	
							}
							?>
								<li>
									<p>Há <?php echo $total_cobranca_mes; ?> cobranças para este mês.</p>
								</li>
								<li>
									<a href="/app/faturamento/cobranca/?viewer=faturamento">Ver todas as cobranças</a>
								</li>
							</ul>
						</li>
					<?php
					}
					?>
                       <!-- END NOTIFICATION CONSULTA PCP -->
                   </ul>
               </div>
               <!-- END  NOTIFICATION -->
               
               
               <div class="top-nav ">
                   <ul class="nav pull-right top-menu" >
                       <!-- BEGIN SUPPORT -->
                       <!--<li class="dropdown mtop5">

                           <a class="dropdown-toggle element" data-placement="bottom" data-toggle="tooltip" href="#" data-original-title="Chat">
                               <i class="icon-comments-alt"></i>
                           </a>
                       </li>
                       <li class="dropdown mtop5">
                           <a class="dropdown-toggle element" data-placement="bottom" data-toggle="tooltip" href="#" data-original-title="Help">
                               <i class="icon-headphones"></i>
                           </a>
                       </li>-->
                       <!-- END SUPPORT -->
                       <!-- BEGIN USER LOGIN DROPDOWN -->
                       <li class="dropdown">
                           <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <img src="/img/avatar-user-mini.png" alt=""> 
                               <span class="username"><?php echo $_SESSION["nomes"]; ?></span>
                               <b class="caret"></b>
                           </a>
                           <ul class="dropdown-menu extended logout">
                               <!--<li><a href="#"><i class="icon-user"></i> Meu Perfil</a></li>
                               <li><a href="#"><i class="icon-cog"></i> Configutações</a></li>-->
                               <li><a href="/destroy.php"><i class="icon-key"></i> Sair</a></li>
                           </ul>
                       </li>
                       <!-- END USER LOGIN DROPDOWN -->
                   </ul>
                   <!-- END TOP NAVIGATION MENU -->
               </div>
           </div>
       </div>