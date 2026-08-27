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

if($_GET['id'] === null)
{
    header("location: dashboard.php");
    exit;
}

$servico->removerServico($_GET['id']);
header("location: dashboard.php");
exit;