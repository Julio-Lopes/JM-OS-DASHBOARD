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
		<span>Olá, <?= htmlspecialchars($nomeUsuario ?? 'Usuário', ENT_QUOTES, 'UTF-8') ?></span>
		<a href="sair.php">Sair</a>
	</header>
