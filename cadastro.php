<?php
session_start();

require_once './includes/conexao.php';
require_once './classes/Usuario.php';

$u = new Usuario($pdo);
$erro = "";
$nome = "";
$email = "";

if(isset($_SESSION['id_user']))
{
	header("location: dashboard.php");
	exit;
}

if($_SERVER['REQUEST_METHOD'] === 'POST')
{
	$nome = trim($_POST['name'] ?? '');
	$email = trim($_POST['email'] ?? '');
	$senha = $_POST['senha'] ?? '';

	if(empty($nome) || empty($email) || empty($senha))
	{
		$erro = "Todos os campos são obrigatórios";
	}else{
		$novoId = $u->cadastrarUsuario($nome, $email, $senha);

		if($novoId)
		{
			$_SESSION['id_user'] = $novoId;
			header("location: dashboard.php");
			exit;
		}else{
			$erro = "Ops, este e-mail já está cadastrado";
		}
	}
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Cadastro | Sistema</title>
</head>
<body>
<div class="formulario">
	<h1 class="titulo">CADASTRO</h1>
	<?php if($erro != ""){ ?>
		<p class="erro"><?= $erro ?></p>
	<?php } ?>
	<form method="post" action="" novalidate>
		<div class="campo">
			<label for="name">Nome</label>
			<input type="text" id="name" name="name" value="<?= ($nome) ?>"
				autocomplete="name" required autofocus>
		</div>

		<div class="campo">
			<label for="email">E-mail</label>
			<input type="email" id="email" name="email" value="<?= ($email) ?>"
				autocomplete="username" required>
		</div>

		<div class="campo">
			<label for="senha">Senha</label>
			<input type="password" id="senha" name="senha"
				autocomplete="new-password" required>
		</div>

		<button type="submit" class="botao botao--primario botao--bloco">Cadastrar</button>
	</form>
</div>
</body>
</html>