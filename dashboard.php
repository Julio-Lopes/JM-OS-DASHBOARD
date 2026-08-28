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
$tituloPagina = 'Dashboard | Sistema';
?>

<?php require_once './includes/header.php'; ?>
    <div class="formulario">
        <h1 class="titulo">DASHBOARD</h1>
        <p>Bem-vindo, <?= htmlspecialchars($nomeUsuario, ENT_QUOTES, 'UTF-8') ?> | Data: <?= date('d/m/Y') ?></p>
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
                    <li><?= htmlspecialchars($servico['description'], ENT_QUOTES, 'UTF-8') ?> - <?= number_format($servico['price'], 2, ',', '.') ?> - <?= date('d/m/Y', strtotime($servico['created_at'])) ?></li>
                <?php } ?>
            </ul>
        <?php } ?>
    </div>
    <div>
        <form method="get">
            <label for="inicio">Início:</label>
            <input type="date" name="inicio" id="inicio" value="<?= htmlspecialchars($_GET['inicio'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
            <label for="fim">Fim:</label>
            <input type="date" name="fim" id="fim" value="<?= htmlspecialchars($_GET['fim'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
            <label for="nome">Nome:</label>
            <input type="text" name="nome" id="nome" value="<?= htmlspecialchars($_GET['nome'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
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
                    <option value="<?= $u['id_user'] ?>" <?= (isset($_GET['usuario']) && $_GET['usuario'] == $u['id_user']) ? 'selected' : '' ?>><?= htmlspecialchars($u['name'], ENT_QUOTES, 'UTF-8') ?></option>
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
                    <td><?= htmlspecialchars($servico['description'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= number_format($servico['price'], 2, ',', '.') ?></td>
                    <td><?= htmlspecialchars($servico['status'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($servico['nome_usuario'], ENT_QUOTES, 'UTF-8') ?></td>
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
<?php require_once './includes/footer.php'; ?>