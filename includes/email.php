<?php

function enviarEmailFinalizacao($email, $nome, $servico, $comissao) {
    $assunto = "Finalização de Serviço";
    $comissao = number_format($comissao, 2, ',', '.');
    $mensagem = "Olá, $nome! O serviço '$servico' foi finalizado com sucesso. Sua comissão é de R$ $comissao.";
    if (!mail($email, $assunto, $mensagem, "From: sistema@jminformatica.com.br")) {
        $arquivo = dirname(__DIR__) . '/storage/emails/' . uniqid() . '.txt';
        file_put_contents($arquivo, "Destinatário: $email\nAssunto: $assunto\nMensagem: $mensagem");
    }
    return true;
}