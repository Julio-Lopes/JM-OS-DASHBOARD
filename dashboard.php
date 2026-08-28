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

$usuario_obj = new Usuario($pdo);
$dados_usuario = $usuario_obj->buscaUsuarioPorId($_SESSION['id_user']);
$nomeUsuario = $dados_usuario['name'];

$usuarios = $usuario_obj->buscaTodosUsuarios();

$os = new Servico($pdo);
$servicos = $os->listarServicosComNomeUsuario(
    $_GET['inicio'] ?? null,
    $_GET['fim'] ?? null,
    $_GET['nome'] ?? null,
    $_GET['status'] ?? null,
    $_GET['usuario'] ?? null
);

$servicosRecentes = $os->ultimosServicosPendentes($_SESSION['id_user'],5);
$totalServicos = $os->totalServicosPorUsuario($_SESSION['id_user']);
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
        <p>Bem-vindo, <?= $nomeUsuario ?> | Data: <?= date('d/m/Y') ?></p>
        <a href="sair.php" class="botao botao--secundario botao--bloco">Sair</a>
    </div>
    <div>
        <p>Total de Serviços: <?= number_format($totalServicos, 2, ',', '.') ?></p>
        <a href="form-servico.php" class="botao botao--primario">+ Novo Serviço</a>
    </div>
    <div>
        <h2>Últimos Serviços Pendentes</h2>
        <?php if(empty($servicosRecentes)){ ?>
            <p>Nenhum serviço pendente.</p>
        <?php } else { ?>
            <ul>
                <?php foreach($servicosRecentes as $servico){ ?>
                    <li><?= $servico['description'] ?> - <?= number_format($servico['price'], 2, ',', '.') ?> - <?= date('d/m/Y', strtotime($servico['created_at'])) ?></li>
                <?php } ?>
            </ul>
        <?php } ?>
    </div>
    <div>
        <form method="get">
            <label for="inicio">Início:</label>
            <input type="date" name="inicio" id="inicio" value="<?= $_GET['inicio'] ?? '' ?>">
            <label for="fim">Fim:</label>
            <input type="date" name="fim" id="fim" value="<?= $_GET['fim'] ?? '' ?>">
            <label for="nome">Nome:</label>
            <input type="text" name="nome" id="nome" value="<?= $_GET['nome'] ?? '' ?>">
            <label for="status">Status:</label>
            <select name="status" id="status">
                <option value="">Todos</option>
                <option value="pendente" <?= (isset($_GET['status']) && $_GET['status'] === 'pendente') ? 'selected' : '' ?>>Pendente</option>
                <option value="finalizado" <?= (isset($_GET['status']) && $_GET['status'] === 'finalizado') ? 'selected' : '' ?>>Finalizado</option>
            </select>
            <label for="usuario">Usuário:</label>
            <select name="usuario" id="usuario">
                <option value="">Todos</option>
                <?php foreach($usuarios as $u){ ?>
                    <option value="<?= $u['id_user'] ?>" <?= (isset($_GET['usuario']) && $_GET['usuario'] == $u['id_user']) ? 'selected' : '' ?>><?= $u['name'] ?></option>
                <?php } ?>
            </select>
            <button type="submit">Filtrar</button>
        </form>
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
                    <td><?= number_format($servico['price'], 2, ',', '.') ?></td>
                    <td><?= $servico['status'] ?></td>
                    <td><?= $servico['nome_usuario'] ?></td>
                    <td>
                        <?php if($servico['status'] == 'Pendente'){ ?>
                            <a href="finalizar-servico.php?id=<?= $servico['id_service'] ?>">finalizar</a>
                        <?php } ?>                        
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