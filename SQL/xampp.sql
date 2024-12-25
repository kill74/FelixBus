-- Criar e selecionar o banco de dados
CREATE DATABASE IF NOT EXISTS trabalho_php;
USE trabalho_php;

-- Tabela `alertas`
CREATE TABLE `alertas` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `titulo` VARCHAR(200) NOT NULL,
  `descricao` TEXT DEFAULT NULL,
  `data_inicio` DATETIME NOT NULL,
  `data_fim` DATETIME NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabela `bilhetes`
CREATE TABLE `bilhetes` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `utilizador_id` INT NOT NULL,
  `viagem_id` INT NOT NULL,
  `codigo_validacao` VARCHAR(50) NOT NULL UNIQUE,
  `estado` ENUM('comprado','usado','cancelado') DEFAULT 'comprado',
  PRIMARY KEY (`id`),
  FOREIGN KEY (`utilizador_id`) REFERENCES `utilizadores` (`id`),
  FOREIGN KEY (`viagem_id`) REFERENCES `viagens` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabela `carteira`
CREATE TABLE `carteira` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `utilizador_id` INT NOT NULL,
  `saldo` DECIMAL(10,2) DEFAULT 0.00,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`utilizador_id`) REFERENCES `utilizadores` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabela `rotas`
CREATE TABLE `rotas` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `origem` VARCHAR(100) NOT NULL,
  `destino` VARCHAR(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabela `tipos_utilizador`
CREATE TABLE `tipos_utilizador` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `nome` VARCHAR(50) NOT NULL UNIQUE,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Inserir dados na tabela `tipos_utilizador`
INSERT INTO `tipos_utilizador` (`id`, `nome`) VALUES
(1, 'cliente'),
(2, 'funcionario'),
(3, 'administrador'),
(4, 'visitante');

-- Tabela `transacoes`
CREATE TABLE `transacoes` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `utilizador_id` INT NOT NULL,
  `valor` DECIMAL(10,2) NOT NULL,
  `tipo` ENUM('carregamento','compra','levantamento') NOT NULL,
  `data_transacao` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`utilizador_id`) REFERENCES `utilizadores` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabela `utilizadores`
CREATE TABLE `utilizadores` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `nome` VARCHAR(150) NOT NULL,
  `palavra_passe` VARCHAR(255) NOT NULL,
  `tipo_utilizador_id` INT NOT NULL,
  `estado` ENUM('ativo','inativo') DEFAULT 'ativo',
  PRIMARY KEY (`id`),
  FOREIGN KEY (`tipo_utilizador_id`) REFERENCES `tipos_utilizador` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Inserir dados na tabela `utilizadores`
INSERT INTO `utilizadores` (`id`, `nome`, `palavra_passe`, `tipo_utilizador_id`, `estado`) VALUES
(7, 'cliente', '$2y$10$7QJ8Q8Q8Q8Q8Q8Q8Q8Q8Q8Q8Q8Q8Q8Q8Q8Q8Q8Q8Q8Q8Q8Q8Q8Q8Q8Q8', 1, 'ativo'),
(8, 'funcionario', '$2y$10$7QJ8Q8Q8Q8Q8Q8Q8Q8Q8Q8Q8Q8Q8Q8Q8Q8Q8Q8Q8Q8Q8Q8Q8Q8Q8Q8Q8', 2, 'ativo'),
(9, 'admin', '$2y$10$7QJ8Q8Q8Q8Q8Q8Q8Q8Q8Q8Q8Q8Q8Q8Q8Q8Q8Q8Q8Q8Q8Q8Q8Q8Q8Q8Q8', 3, 'ativo');

-- Tabela `viagens`
CREATE TABLE `viagens` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `rota_id` INT NOT NULL,
  `capacidade` INT NOT NULL,
  `data_viagem` DATE NOT NULL,
  `hora_partida` TIME NOT NULL,
  `hora_chegada` TIME NOT NULL,
  `preco` DECIMAL(10,2) NOT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`rota_id`) REFERENCES `rotas` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
