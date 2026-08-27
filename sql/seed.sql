-- =============================================================================
--  Massa de dados para avaliação. Senha de todos os usuários: 123456
-- =============================================================================

USE `jm_ordem_servico`;

SET FOREIGN_KEY_CHECKS = 0;
TRUNCATE TABLE `service`;
TRUNCATE TABLE `user`;
SET FOREIGN_KEY_CHECKS = 1;

INSERT INTO `user` (`id_user`, `name`, `email`, `password`, `created_at`, `ativo`) VALUES
(1, 'Julio Pereira',   'julio@jminformatica.com.br',   '$2y$10$.Xsk.m38dSwhYDKK0l6rCuTHO/dDGgh6jp1UxGA1b6xYhi.kWKJj6', DATE_SUB(NOW(), INTERVAL 120 DAY), 1),
(2, 'Marina Castro',   'marina@jminformatica.com.br',  '$2y$10$DQwC4lNxAoLIVle2DunB4uSOvGb3VHvnwy9cG1gTvZWrLWGFSoYKO', DATE_SUB(NOW(), INTERVAL 90 DAY),  1),
(3, 'Rogério Antunes', 'rogerio@jminformatica.com.br', '$2y$10$OryJI52il2iZI.GG3y.3du99YO.3O49sbpFrProXugOwwnHEofPyC', DATE_SUB(NOW(), INTERVAL 60 DAY),  0);

INSERT INTO `service` (`description`, `price`, `created_at`, `finished_at`, `commission_user`, `user_id_user`) VALUES
('Formatação e backup de notebook',   450.000,   DATE_SUB(NOW(), INTERVAL 45 DAY), DATE_SUB(NOW(), INTERVAL 43 DAY), 22.500,   1),
('Troca de fonte e limpeza interna',  1000.000,  DATE_SUB(NOW(), INTERVAL 38 DAY), DATE_SUB(NOW(), INTERVAL 36 DAY), 50.000,   1),
('Instalação de rede em escritório',  2800.000,  DATE_SUB(NOW(), INTERVAL 30 DAY), DATE_SUB(NOW(), INTERVAL 25 DAY), 280.000,  1),
('Servidor de arquivos com RAID',     15400.000, DATE_SUB(NOW(), INTERVAL 22 DAY), DATE_SUB(NOW(), INTERVAL 15 DAY), 3080.000, 1),
('Manutenção preventiva mensal',      680.000,   DATE_SUB(NOW(), INTERVAL 12 DAY), NULL, NULL, 1),
('Recuperação de HD danificado',      1250.000,  DATE_SUB(NOW(), INTERVAL 6 DAY),  NULL, NULL, 1),
('Configuração de firewall pfSense',  3400.000,  DATE_SUB(NOW(), INTERVAL 2 DAY),  NULL, NULL, 1),
('Suporte remoto ao sistema fiscal',  320.000,   DATE_SUB(NOW(), INTERVAL 40 DAY), DATE_SUB(NOW(), INTERVAL 39 DAY), 16.000,   2),
('Migração de e-mails para Google',   4700.000,  DATE_SUB(NOW(), INTERVAL 28 DAY), DATE_SUB(NOW(), INTERVAL 20 DAY), 470.000,  2),
('Projeto de cabeamento estruturado', 10000.000, DATE_SUB(NOW(), INTERVAL 18 DAY), DATE_SUB(NOW(), INTERVAL 10 DAY), 1000.000, 2),
('Implantação de câmeras IP',         12300.000, DATE_SUB(NOW(), INTERVAL 9 DAY),  NULL, NULL, 2),
('Instalação de impressora fiscal',   540.000,   DATE_SUB(NOW(), INTERVAL 4 DAY),  NULL, NULL, 2),
('Consultoria de licenciamento',      2100.000,  DATE_SUB(NOW(), INTERVAL 35 DAY), DATE_SUB(NOW(), INTERVAL 33 DAY), 210.000,  3),
('Troca de switch core',              8900.000,  DATE_SUB(NOW(), INTERVAL 7 DAY),  NULL, NULL, 3);