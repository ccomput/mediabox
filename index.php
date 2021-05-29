<?php
require "inc/security.php";
require "inc/conect.php";
require "inc/verifica.php";
require "inc/functions.php";
?>
<!DOCTYPE html>
<!--[if IE 8]> <html lang="pt-br" class="ie8"> <![endif]-->
<!--[if IE 9]> <html lang="pt-br" class="ie9"> <![endif]-->
<!--[if !IE]><!--> <html lang="pt-br"> <!--<![endif]-->
<!-- BEGIN HEAD -->
<head>
   <?php include "head.php"; ?>
</head>
<!-- END HEAD -->
<!-- BEGIN BODY -->
<body class="fixed-top">
	<?php include "preloader.php"; ?>
   <!-- BEGIN HEADER -->
   <div id="header" class="navbar navbar-inverse navbar-fixed-top">
       <!-- BEGIN TOP NAVIGATION BAR -->
       <?php include "topnavigationbar.php"; ?>
       <!-- END TOP NAVIGATION BAR -->
   </div>
   <!-- END HEADER -->
   <!-- BEGIN CONTAINER -->
   <div id="container" class="row-fluid">
      <!-- BEGIN SIDEBAR -->
      <div class="sidebar-scroll">
        <div id="sidebar" class="nav-collapse collapse">

         <!-- BEGIN RESPONSIVE QUICK SEARCH FORM -->
         <div class="navbar-inverse">
            <form class="navbar-search visible-phone">
               <input type="text" class="search-query" placeholder="Search" />
            </form>
         </div>
         <!-- END RESPONSIVE QUICK SEARCH FORM -->
         <!-- BEGIN SIDEBAR MENU -->
         <?php include "sidebar.php"; ?>
         <!-- END SIDEBAR MENU -->
      </div>
      </div>
      <!-- END SIDEBAR -->
      <!-- BEGIN PAGE -->  
      <div id="main-content">
         <!-- BEGIN PAGE CONTAINER-->
         <div class="container-fluid">
            <!-- BEGIN PAGE HEADER-->   
            <div class="row-fluid">
               <div class="span12">
                  <!-- BEGIN PAGE TITLE & BREADCRUMB-->
                   <h3 class="page-title">
                     Painel
                   </h3>
                   <ul class="breadcrumb">
                       <li>
                           <a href="#">Home</a>
                           <span class="divider">/</span>
                       </li>
                       <li class="active">
                           Painel
                       </li>
                       <li class="pull-right search-wrap">
                           <!--<form action="" class="hidden-phone">
                               <div class="input-append search-input-area">
                                   <input class="" id="appendedInputButton" type="text">
                                   <button class="btn" type="button"><i class="icon-search"></i> </button>
                               </div>
                           </form>-->
                       </li>
                   </ul>
                   <!-- END PAGE TITLE & BREADCRUMB-->
               </div>
            </div>
            <!-- END PAGE HEADER-->
            <!-- BEGIN PAGE CONTENT-->

            <!--BEGIN METRO STATES-->
			<?php 
			if($_SESSION["mod_comercial"]== '1'){
			?>
			<div class="row-fluid">
				<div class="metro-nav">
					<div class="metro-nav-block nav-block-orange">
						<a data-original-title="Propostas" href="propostas.php?viewer=comercial">
							<i class="icon-shopping-cart"></i>
							<div class="info"></div>
							<div class="status">Propostas</div>
						</a>
					</div>
					<div class="metro-nav-block nav-block-yellow">
						<a data-original-title="Pedidos" href="pedidos.php?viewer=comercial">
							<i class="icon-shopping-cart"></i>
							<div class="info"></div>
							<div class="status">Pedidos</div>
						</a>
					</div>

				</div>
			</div>
			<?php
			}

			if($_SESSION["mod_plantel"]== '1'){
			?>
			<div class="row-fluid">
				<div class="metro-nav">
					<div class="metro-nav-block nav-block-blue">
						<a data-original-title="Status Faturamento" href="status_pedido.php?viewer=financeiro">
							<i class="icon-usd"></i>
							<div class="info"></div>
							<div class="status">Status Faturamento</div>
						</a>
					</div>
					<?php 
					if($_SESSION["mod_cadastro"]== '1'){
					?>
					<div class="metro-nav-block nav-block-green ">
						<a data-original-title="Clientes" href="clientes.php?viewer=cadastros">
							<i class="icon-user"></i>
							<div class="info"></div>
							<div class="status">Clientes</div>
						</a>
					</div>
					<div class="metro-nav-block nav-block-orange">
						<a data-original-title="Agências" href="agencias.php?viewer=cadastros">
							<i class="icon-coffee"></i>
							<div class="info"></div>
							<div class="status">Agências</div>
						</a>
					</div>
					<div class="metro-nav-block nav-block-purple">
						<a data-original-title="Veículos" href="veiculos.php?viewer=cadastros">
							<i class="icon-bullhorn"></i>
							<div class="info"></div>
							<div class="status">Veículos</div>
						</a>
					</div>
					<div class="metro-nav-block nav-block-grey ">
						<a data-original-title="Vendedores" href="vendedores.php?viewer=cadastros">
							<i class="icon-tags"></i>
							<div class="info"></div>
							<div class="status">Vendedores</div>
						</a>
					</div>
					<?php } ?>
				</div>
			 </div>
			<?php
			}
			 ?>
			
				<!-- END PAGE CONTENT-->         
         </div>
         <!-- END PAGE CONTAINER-->
      </div>
      <!-- END PAGE -->  
   </div>
   <!-- END CONTAINER -->

   <!-- BEGIN FOOTER -->
   <?php include "footer.php"; ?>
   <!-- END FOOTER -->

   <!-- BEGIN JAVASCRIPTS -->
   <!-- Load javascripts at bottom, this will reduce page load time -->
   <script src="js/jquery-1.8.3.min.js"></script>
   <script src="js/jquery.nicescroll.js" type="text/javascript"></script>
   <script type="text/javascript" src="assets/jquery-slimscroll/jquery-ui-1.9.2.custom.min.js"></script>
   <script type="text/javascript" src="assets/jquery-slimscroll/jquery.slimscroll.min.js"></script>
   <script src="assets/fullcalendar/fullcalendar/fullcalendar.min.js"></script>
   <script src="assets/bootstrap/js/bootstrap.min.js"></script>

   <!-- ie8 fixes -->
   <!--[if lt IE 9]>
   <script src="js/excanvas.js"></script>
   <script src="js/respond.js"></script>
   <![endif]-->

   <script src="assets/jquery-easy-pie-chart/jquery.easy-pie-chart.js" type="text/javascript"></script>
   <script src="js/jquery.sparkline.js" type="text/javascript"></script>
   <script src="assets/chart-master/Chart.js"></script>

   <!--common script for all pages-->
   <script src="js/common-scripts.js"></script>

   <!--script for this page only-->

   <script src="js/easy-pie-chart.js"></script>
   <script src="js/sparkline-chart.js"></script>
   <script src="js/home-page-calender.js"></script>
   <script src="js/chartjs.js"></script>

   <!-- END JAVASCRIPTS -->   
</body>
<!-- END BODY -->
</html>