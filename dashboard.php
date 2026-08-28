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
$resumo = $os->resumoServicosPorUsuario($_SESSION['id_user']);
$tituloPagina = 'Dashboard | Sistema';
?>

<?php require_once './includes/header.php'; ?>
    <div class="dashboard-cabecalho">
        <div>
            <p class="saudacao">Olá, <?= htmlspecialchars($nomeUsuario, ENT_QUOTES, 'UTF-8') ?></p>
            <p class="data-atual">Data: <?= date('d/m/Y') ?></p>
        </div>
        <a href="form-servico.php" class="botao botao--primario">Adicionar serviço</a>
    </div>
    <section class="cards-resumo" aria-label="Resumo dos serviços">
        <article class="card-valor card-valor--destaque">
            <p class="card-rotulo">Valor total dos seus serviços</p>
            <strong>R$ <?= number_format($resumo['total'], 2, ',', '.') ?></strong>
            <small><?= (int) $resumo['quantidade'] ?> serviço(s) registrado(s)</small>
        </article>
        <article class="card-valor">
            <p class="card-rotulo">Finalizados</p>
            <strong>R$ <?= number_format($resumo['finalizados'], 2, ',', '.') ?></strong>
            <small>Comissão acumulada R$ <?= number_format($resumo['comissao'], 2, ',', '.') ?></small>
        </article>
        <article class="card-valor">
            <p class="card-rotulo">Pendentes</p>
            <strong>R$ <?= number_format($resumo['pendentes'], 2, ',', '.') ?></strong>
            <small><?= (int) $resumo['quantidade_pendentes'] ?> aguardando finalização</small>
        </article>
    </section>
    <section class="bloco-dashboard">
        <h2>Seus últimos serviços pendentes</h2>
        <?php if(empty($servicosRecentes)){ ?>
            <p>Nenhum serviço pendente.</p>
        <?php } else { ?>
            <ul>
                <?php foreach($servicosRecentes as $servico){ ?>
                    <li>
                        <span><?= htmlspecialchars($servico['description'], ENT_QUOTES, 'UTF-8') ?></span>
                        <small>aberto em <?= date('d/m/Y', strtotime($servico['created_at'])) ?></small>
                        <strong>R$ <?= number_format($servico['price'], 2, ',', '.') ?></strong>
                    </li>
                <?php } ?>
            </ul>
        <?php } ?>
    </section>
    <section class="bloco-dashboard filtro-servicos">
        <h2>Filtrar serviços</h2>
        <form method="get">
            <div class="campo-filtro">
                <label for="inicio">Início:</label>
                <input type="date" name="inicio" id="inicio" value="<?= htmlspecialchars($_GET['inicio'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
            </div>
            <div class="campo-filtro">
                <label for="fim">Fim:</label>
                <input type="date" name="fim" id="fim" value="<?= htmlspecialchars($_GET['fim'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
            </div>
            <div class="campo-filtro">
                <label for="nome">Nome:</label>
                <input type="text" name="nome" id="nome" value="<?= htmlspecialchars($_GET['nome'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
            </div>
            <div class="campo-filtro">
                <label for="status">Status:</label>
                <select name="status" id="status">
                <option value="">Todos</option>
                <option value="pendente" <?= (isset($_GET['status']) && $_GET['status'] === 'pendente') ? 'selected' : '' ?>>Pendente</option>
                <option value="finalizado" <?= (isset($_GET['status']) && $_GET['status'] === 'finalizado') ? 'selected' : '' ?>>Finalizado</option>
                </select>
            </div>
            <div class="campo-filtro">
                <label for="usuario">Usuário:</label>
                <select name="usuario" id="usuario">
                <option value="">Todos</option>
                <?php foreach($usuarios as $u){ ?>
                    <option value="<?= $u['id_user'] ?>" <?= (isset($_GET['usuario']) && $_GET['usuario'] == $u['id_user']) ? 'selected' : '' ?>><?= htmlspecialchars($u['name'], ENT_QUOTES, 'UTF-8') ?></option>
                <?php } ?>
                </select>
            </div>
            <button type="submit">Filtrar</button>
        </form>
    </div>
    </section>
    <section class="bloco-dashboard tabela-servicos">
        <h2>Serviços prestados</h2>
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
                            <a href="finalizar-servico.php?id=<?= $servico['id_service'] ?>" class="botao botao--primario">finalizar</a>
                        <?php } ?>                        
                        <a href="form-servico.php?id=<?= $servico['id_service'] ?>" class="botao botao--secundario">alterar</a>
                        <a href="excluir-servico.php?id=<?= $servico['id_service'] ?>" class="botao botao--perigo" onclick="return confirm('Tem certeza que deseja excluir este serviço?')">excluir</a>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </section>
<?php require_once './includes/footer.php'; ?>