<?php
session_start();

require_once './includes/conexao.php';
require_once './classes/Servico.php';

if(!isset($_SESSION['id_user']))
{
    header("location: login.php");
    exit;
}

$servico = new Servico($pdo);
$erro = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $descricao = $_POST['descricao'] ?? '';
    $preco = $_POST['preco'] ?? '';
    $id_usuario = $_SESSION['id_user'] ?? null;

    if (!empty($descricao) && !empty($preco) && !empty($id_usuario)) {
        $cadastrado = $servico->cadastrarServico($descricao, $preco, $id_usuario);
        if ($cadastrado) {
            header("Location: dashboard.php");
            exit;
        } else {
            $erro = "Erro ao cadastrar serviço.";
        }
    } else {
        $erro = "Todos os campos são obrigatórios.";
    }
}

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Cadastrar Serviço | Sistema</title>
</head>
<body>
    <div class="formulario">
        <h1 class="titulo">CADASTRAR SERVIÇO</h1>
        <?php if($erro != ""){ ?>
            <p><?= $erro ?></p>
        <?php } ?>
        <form method="POST" action="">
            <label for="descricao">Descrição:</label>
            <input type="text" id="descricao" name="descricao" maxlength="45" required>

            <label for="preco">Preço:</label>
            <input type="text" id="preco" name="preco" required>

            <button type="submit">Cadastrar</button>
        </form>
        <a href="dashboard.php" class="botao botao--secundario botao--bloco">Voltar</a>
    </div>
</body>
</html>