<?php
session_start();

require_once './includes/conexao.php';
require_once './classes/Usuario.php';

$u = new Usuario($pdo);
$erro = "";
$email = "";

if(isset($_SESSION['id_user']))
{
    header("location: dashboard.php");
    exit;
}

if(isset($_POST['email']))
{
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    if($u->logar($email, $senha))
    {
        header("location: dashboard.php");
        exit;
    }else{
        $erro = "Ops, Email ou Senha inválido";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Entrar | Sistema</title>
</head>
<body>
<div classe="formulario">
    <h1 class="titulo">LOGIN</h1>
    <?php if($erro != ""){ ?>
        <p class="erro"><?= $erro ?></p>
    <?php } ?>
    <form method="post" action="" novalidate>

        <div class="campo">
            <label for="email">E-mail</label>
            <input type="email" id="email" name="email" value="<?= ($email) ?>"
                autocomplete="username" required autofocus>
        </div>

        <div class="campo">
            <label for="senha">Senha</label>
            <input type="password" id="senha" name="senha"
                autocomplete="current-password" required>
        </div>

        <button type="submit" class="botao botao--primario botao--bloco">Entrar</button>
    </form>
    <p class="cartao-login__rodape">
        Não tem conta? <a href="cadastro.php">Cadastrar usuário</a>
    </p>
</div>