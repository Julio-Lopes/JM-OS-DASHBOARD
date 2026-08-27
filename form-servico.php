<?php
session_start();
$_GET['id'] = $_GET['id'] ?? null;

require_once './includes/conexao.php';
require_once './classes/Servico.php';

if(!isset($_SESSION['id_user']))
{
    header("location: login.php");
    exit;
}

$servico = new Servico($pdo);

if($_GET['id'] !== null){
    $servicoEncontrado = $servico->buscarServicoPorId($_GET['id']);
    
    if(!$servicoEncontrado){
        header("location: dashboard.php");
        exit;
    }
}

$erro = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $descricao = $_POST['descricao'] ?? '';
    $preco = $_POST['preco'] ?? '';
    $id_usuario = $_SESSION['id_user'] ?? null;

    if (empty($descricao) || empty($preco)) {
        $erro = "Todos os campos são obrigatórios";
    } else {
        if($_GET['id'] !== null){
            $resultado = $servico->atualizarServico($_GET['id'], $descricao, $preco);
        } else {
            $resultado = $servico->cadastrarServico($descricao, $preco, $id_usuario);
        }

        if($resultado){
            header("location: dashboard.php");
            exit;
        } else {
            $erro = "Erro ao cadastrar/atualizar serviço";
        }
    }
}

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo isset($servicoEncontrado) ? 'Editar Serviço' : 'Cadastrar Serviço'; ?> | Sistema</title>
</head>
<body>
    <div class="formulario">
        <h1 class="titulo"><?php echo isset($servicoEncontrado) ? 'Editar Serviço' : 'Cadastrar Serviço'; ?></h1>
        <?php if($erro != ""){ ?>
            <p><?= $erro ?></p>
        <?php } ?>
        <form method="POST" action="">
            <label for="descricao">Descrição:</label>
            <input type="text" id="descricao" name="descricao" maxlength="45" value="<?php echo isset($servicoEncontrado['description']) ? $servicoEncontrado['description'] : ''; ?>" required>

            <label for="preco">Preço:</label>
            <input type="text" id="preco" name="preco" value="<?php echo isset($servicoEncontrado['price']) ? $servicoEncontrado['price'] : ''; ?>" required>

            <button type="submit"><?php echo isset($servicoEncontrado) ? 'Atualizar' : 'Cadastrar'; ?></button>
        </form>
        <a href="dashboard.php" class="botao botao--secundario botao--bloco">Voltar</a>
    </div>
</body>
</html>