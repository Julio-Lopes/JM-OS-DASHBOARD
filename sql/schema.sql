-- =============================================================================
--  1. user.password usa VARCHAR(255): o hash bcrypt do password_hash() ocupa
--     60 caracteres e seria truncado em VARCHAR(45).
--  2. Não existe coluna de status. Serviço com finished_at preenchido está
--     finalizado; sem finished_at está pendente.
-- =============================================================================

CREATE DATABASE IF NOT EXISTS `jm_ordem_servico`
    DEFAULT CHARACTER SET utf8mb4
    DEFAULT COLLATE utf8mb4_unicode_ci;

USE `jm_ordem_servico`;

DROP TABLE IF EXISTS `service`;
DROP TABLE IF EXISTS `user`;

CREATE TABLE `user` (
    `id_user`    BIGINT(20)   NOT NULL AUTO_INCREMENT,
    `name`       VARCHAR(150) NOT NULL,
    `email`      VARCHAR(100) NOT NULL,
    `password`   VARCHAR(255) NOT NULL,
    `created_at` DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `update_at`  DATETIME     NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    `ativo`      TINYINT(1)   NOT NULL DEFAULT 1,

    PRIMARY KEY (`id_user`),
    UNIQUE KEY `uq_user_email` (`email`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_unicode_ci;

CREATE TABLE `service` (
    `id_service`      BIGINT(20)     NOT NULL AUTO_INCREMENT,
    `description`     VARCHAR(45)    NOT NULL,
    `price`           DECIMAL(11, 3) NOT NULL,
    `created_at`      DATETIME       NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `update_at`       DATETIME       NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    `finished_at`     DATETIME       NULL DEFAULT NULL,
    `commission_user` DECIMAL(11, 3) NULL DEFAULT NULL,
    `user_id_user`    BIGINT(20)     NOT NULL,

    PRIMARY KEY (`id_service`),
    KEY `idx_service_user` (`user_id_user`),
    KEY `idx_service_finished_at` (`finished_at`),
    KEY `idx_service_created_at` (`created_at`),

    CONSTRAINT `fk_service_user`
        FOREIGN KEY (`user_id_user`)
        REFERENCES `user` (`id_user`)
        ON DELETE RESTRICT
        ON UPDATE CASCADE
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_unicode_ci;