<!DOCTYPE html>
<html lang="pt-BR">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="./css/style.css">
	<title><?= $tituloPagina ?? 'Sistema' ?></title>
</head>
<body>
	<header>
		<div class="header-conteudo">
			<div class="marca">
				<strong>JM INFORMÁTICA</strong>
				<span>Ordem de Serviço</span>
			</div>
			<div class="conta-usuario">
				<div>
					<strong><?= htmlspecialchars($nomeUsuario ?? 'Usuário', ENT_QUOTES, 'UTF-8') ?></strong>
					<?php if (!empty($dados_usuario['email'])) { ?>
						<small><?= htmlspecialchars($dados_usuario['email'], ENT_QUOTES, 'UTF-8') ?></small>
					<?php } ?>
				</div>
				<a class="botao-sair" href="sair.php">Sair</a>
			</div>
		</div>
	</header>