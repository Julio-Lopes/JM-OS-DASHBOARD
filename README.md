# Sistema de Ordem de Serviço

Sistema de ordem de serviço com login, dashboard, cadastro de serviços,
filtros e finalização com cálculo de comissão. Cards no dashboard para visualização
de valores referentes ao usuário logado.

Desenvolvido em PHP, MySQL e JavaScript puros, sem framework e sem Composer.

## Requisitos
PHP 8, MySQL, extensão pdo_mysql.

## Como rodar

1. Crie o banco rodando os arquivos da pasta sql (schema.sql e depois seed.sql).
2. Se necessário, ajuste usuário e senha do banco em includes/conexao.php.
3. Rode: php -S localhost:8000
4. Acesse http://localhost:8000

## Login de teste
julio@jminformatica.com.br / 123456

## Cadastro de usuário
O cadastro também exige o campo nome, pois ele é obrigatório para o usuário e
é exibido como responsável por cada serviço no dashboard.

## Comissão
Até R$ 1.000 = 5%, acima de R$ 1.000 = 10%, acima de R$ 10.000 = 20%.
As faixas se sobrepõem no enunciado, então a maior sempre prevalece. Como a regra
usa "acima de", R$ 1.000 exato fica em 5% e R$ 10.000 exato fica em 10%.

## Observação
O envio de e-mail usa a função mail() do PHP. Como ela não funciona sem
servidor SMTP configurado, uma cópia do e-mail é salva em storage/emails em ".txt".