<?php
session_start();

require_once './includes/conexao.php';
require_once './classes/Servico.php';
require_once './classes/Usuario.php';

if(!isset($_SESSION['id_user']))
{
    header("location: login.php");
    exit;
}

$nome = new Usuario($pdo);
$dados_usuario = $nome->buscaUsuarioPorId($_SESSION['id_user']);
$nome = $dados_usuario['name'];

$os = new Servico($pdo);
$servicos = $os->listarServicosComNomeUsuario();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard | Sistema</title>
</head>
<body>
    <div class="formulario">
        <h1 class="titulo">DASHBOARD</h1>
        <p>Bem-vindo, <?= $nome ?></p>
        <a href="sair.php" class="botao botao--secundario botao--bloco">Sair</a>
    </div>
    <div>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Descrição</th>
                    <th>Preço</th>
                    <th>Status</th>
                    <th>Usuário</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($servicos as $servico){ ?>
                <tr>
                    <td><?= $servico['id_service'] ?></td>
                    <td><?= $servico['description'] ?></td>
                    <td><?= $servico['price'] ?></td>
                    <td><?= $servico['status'] ?></td>
                    <td><?= $servico['nome_usuario'] ?></td>
                    <td>
                        <a href="form-servico.php?id=<?= $servico['id_service'] ?>" class="botao botao--secundario">alterar</a>
                        <a href="excluir-servico.php?id=<?= $servico['id_service'] ?>" class="botao botao--perigo" onclick="return confirm('Tem certeza que deseja excluir este serviço?')">excluir</a>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</body>
</html>