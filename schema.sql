-- schema.sql
-- Estrutura do banco. Nenhum dado real aqui.
-- Uso: mysql -u root < schema.sql   (ou importe pelo phpMyAdmin)

CREATE DATABASE IF NOT EXISTS sistema_ordens
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE sistema_ordens;

CREATE TABLE IF NOT EXISTS usuarios (
    id          INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    usuario     VARCHAR(50)  NOT NULL UNIQUE,
    senha       VARCHAR(255) NOT NULL,              -- hash gerado por password_hash()
    created_at  TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS ordens (
    id            INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    cliente       VARCHAR(120) NOT NULL,
    descricao     VARCHAR(255) NOT NULL,
    material      VARCHAR(60)  NOT NULL,
    espessura     SMALLINT UNSIGNED NOT NULL,       -- em milímetros
    status        ENUM('recebido','aguardando','produzindo','pronto','retirado')
                  NOT NULL DEFAULT 'recebido',
    arquivo_nome  VARCHAR(255) NULL,                -- nome original do plano de corte
    arquivo_path  VARCHAR(80)  NULL,                -- nome gerado no disco (storage/anexos)
    data_envio    DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at    TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_status (status)
) ENGINE=InnoDB;
