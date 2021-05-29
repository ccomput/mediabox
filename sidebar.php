			<ul class="sidebar-menu">
				<li class="sub-menu"><a class="" href="index.php"><i class="icon-dashboard"></i> <span>Painel</span></a></li>
                
				<?php
				if($_SESSION["mod_cadastro"] == 1){
				?>
                <li class="sub-menu <?php echo @$sub_cadastros; ?>">
					<a href="javascript:;" class=""><i class="icon-book"></i> <span>Cadastros</span><span class="arrow"></span></a>
					<ul class="sub">
						<li class="<?php echo @$clientes; ?>"><a class="" href="/clientes.php?viewer=cadastros">Clientes</a></li>
						<li class="<?php echo @$agencias; ?>"><a class="" href="/agencias.php?viewer=cadastros">Agências</a></li>
						<li class="<?php echo @$veiculos; ?>"><a class="" href="/veiculos.php?viewer=cadastros">Veículos</a></li>
						<li class="<?php echo @$vendedores; ?>"><a class="" href="/vendedores.php?viewer=cadastros">Vendedores</a></li>
						<li class="<?php echo @$status; ?>"><a class="" href="/status.php?viewer=cadastros">Status</a></li>
					</ul>
				</li>
                <?php
				}
				?>
				
				<?php
				if($_SESSION["mod_comercial"] == 1){
				?>
                <li class="sub-menu <?php echo @$sub_comercial; ?>">
					<a href="javascript:;" class=""><i class="icon-shopping-cart"></i> <span>Comercial</span><span class="arrow"></span></a>
					<ul class="sub">
						<li class="<?php echo @$propostas; ?>"><a class="" href="/propostas.php?viewer=comercial">Propostas</a></li>
						<li class="<?php echo @$pedidos; ?>"><a class="" href="/pedidos.php?viewer=comercial">Pedidos</a></li>
					</ul>
				</li>
                <?php
				}
				?>
                
				<?php
				if($_SESSION["mod_plantel"] == 1){
				?>
				<li class="sub-menu <?php echo @$sub_faturamento; ?>">
					<a href="javascript:;" class=""><i class="icon-usd"></i> <span>Faturamento</span><span class="arrow"></span></a>
					<ul class="sub">
						<?php if($_SESSION["mod_add_plantel"] == 1){ ?>
						
						<?php } ?>
						<li class="<?php echo @$status_pedido; ?>"><a class="" href="/status_pedido.php?viewer=faturamento">Status Faturamento</a></li>
						<li class="<?php echo @$cobrancas; ?>"><a class="" href="/app/faturamento/cobranca/?viewer=faturamento">Cobranças</a></li>
					</ul>
				</li>
				<?php
				}
				?>
				
				<?php
				if($_SESSION["mod_configura"] == 1){
				?>
				<li class="sub-menu <?php echo @$sub_financeiro; ?>">
					<a href="javascript:;" class=""><i class="icon-usd"></i> <span>Financeiro</span><span class="arrow"></span></a>
					<ul class="sub">
						<?php if($_SESSION["mod_add_plantel"] == 1){ ?>
						
						<?php } ?>
						<li class="<?php echo @$plano_contas; ?>"><a class="" href="plano_contas.php?viewer=financeiro">Plano de Contas</a></li>
					</ul>
				</li>
				<?php
				}
				?>
                
                <?php
				if($_SESSION["mod_certificado"] == 1){
				?>
				<li class="sub-menu <?php echo @$sub_relatorios; ?>">
					<a href="javascript:;" class=""><i class="icon-file-text"></i> <span>Relatórios</span><span class="arrow"></span></a>
					<ul class="sub">
						<li class="<?php echo @$relatorios; ?>"><a class="" href="relatorios.php?viewer=relatorios">Relatórios Gerenciais</a></li>
					</ul>
				</li>
                <?php
				}
				?>
                
				<?php
				if($_SESSION["mod_configura"] == 1){
				echo '
              	<li class="sub-menu '.@$sub_config.'">
					<a href="javascript:;" class="">
						<i class="icon-cogs"></i>
						<span>Configurações</span>
						<span class="arrow"></span>
					</a>
					<ul class="sub">
						<li'.@$usuarios.'><a class="" href="usuarios.php">Usuários</a></li>
						<li'.@$grupos.'><a class="" href="grupos.php">Grupos</a></li>
						<li'.@$unidades.'><a class="" href="unidades.php">Unidades</a></li>
					</ul>
				</li>';
				}
				?>
			</ul>