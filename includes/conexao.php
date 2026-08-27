<?php
    require_once __DIR__ . '/../classes/Conexao.php';

    $conexao = new Conexao();
    $conexao->conectar("jm_ordem_servico", "localhost", "root", "");
    $pdo = $conexao->getPdo();
?>