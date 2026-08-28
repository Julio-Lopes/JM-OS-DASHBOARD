<?php
session_start();
$_GET['id'] = $_GET['id'] ?? null;

require_once './includes/conexao.php';
require_once './includes/email.php';
require_once './classes/Servico.php';
require_once './classes/Usuario.php';

if(!isset($_SESSION['id_user']))
{
    header("location: login.php");
    exit;
}

$servico = new Servico($pdo);

if($_GET['id'] === null)
{
    header("location: dashboard.php");
    exit;
}

$finalizou = $servico->finalizarServico($_GET['id']);
if ($finalizou) {
    $servicoFinalizado = $servico->buscarServicoPorId($_GET['id']);
    $usuario = new Usuario($pdo);
    $usuarioComissao = $usuario->buscaUsuarioPorId($servicoFinalizado['user_id_user']);

    if (!empty($usuarioComissao)) {
        enviarEmailFinalizacao($usuarioComissao['email'], $usuarioComissao['name'], $servicoFinalizado['description'], $servicoFinalizado['commission_user']);
    }

    header("location: dashboard.php");
    exit;
} else {
    header("location: dashboard.php");
    exit;
}