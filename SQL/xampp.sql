-- Criação da base de dados
CREATE DATABASE trabalho_php;
USE trabalho_php;

-- Tabela: tipos_utilizador
CREATE TABLE tipos_utilizador (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(50) UNIQUE NOT NULL
);

-- Inserir tipos de utilizador
INSERT INTO tipos_utilizador (id, nome) VALUES
  (1, 'cliente'),
  (2, 'funcionario'),
  (3, 'administrador'),
  (4, 'visitante');

-- Tabela: utilizadores
CREATE TABLE utilizadores (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(150) NOT NULL,
  palavra_passe VARCHAR(255) NOT NULL,
  tipo_utilizador_id INT NOT NULL,
  data_nascimento DATE NULL,
  telefone VARCHAR(15) NULL,
  endereco VARCHAR(255) NULL,
  estado ENUM('ativo', 'inativo') DEFAULT 'ativo',
  FOREIGN KEY (tipo_utilizador_id) REFERENCES tipos_utilizador(id)
);

-- Inserir alguns utilizadores
INSERT INTO utilizadores (id, nome, palavra_passe, tipo_utilizador_id, estado) VALUES
  (7, 'cliente', '$2y$10$xhx10ddIFLutxtaUOQYYNuNRJyv5Nc7oWLhIhi3BHh8uQLV31XBna', 1, 'ativo'),
  (8, 'funcionario', '$2y$10$.XqeSXjPoKTyD0/XAkZz7edTx42MkHGiY4RzmkfYQ3kr2ZiP7WF4G', 2, 'ativo'),
  (9, 'admin', '$2y$10$ex7LzCmjOZdL.3x8UOBo.O2IyAflxxR8dHgXMqPZE/9Mxgdx8hFTq', 3, 'ativo');

-- Tabela: rotas
CREATE TABLE rotas (
  id INT AUTO_INCREMENT PRIMARY KEY,
  origem VARCHAR(100) NOT NULL,
  destino VARCHAR(100) NOT NULL,
  data DATE NOT NULL,
  hora TIME NOT NULL,
  capacidade INT NOT NULL
);

-- Inserir algumas rotas
INSERT INTO rotas (origem, destino, data, hora, capacidade) VALUES
  ('Braga', 'Lisboa (Oriente)', '2023-11-24', '05:00', 50),
  ('Porto', 'Faro', '2023-11-25', '07:25', 50),
  ('Coimbra', 'Viseu', '2023-11-26', '08:40', 50);

-- Tabela: carteira
CREATE TABLE carteira (
  id INT AUTO_INCREMENT PRIMARY KEY,
  utilizador_id INT NOT NULL,
  saldo DECIMAL(10,2) DEFAULT 0.00, 
  FOREIGN KEY (utilizador_id) REFERENCES utilizadores(id) ON DELETE CASCADE ON UPDATE CASCADE
);

-- Inserir dados na tabela carteira
INSERT INTO carteira (utilizador_id, saldo) 
VALUES
  (7, 200),
  (8, 50),
  (9, 100);

-- Tabela: transacoes
CREATE TABLE transacoes (
  id INT AUTO_INCREMENT PRIMARY KEY,
  utilizador_id INT NOT NULL,
  rota_id INT NOT NULL,
  valor DECIMAL(10,2) NOT NULL,
  tipo ENUM('carregamento', 'compra', 'levantamento') NOT NULL,
  data_transacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
  FOREIGN KEY (utilizador_id) REFERENCES utilizadores(id) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (rota_id) REFERENCES rotas(id) ON DELETE CASCADE ON UPDATE CASCADE
);

-- Tabela: bilhetes
CREATE TABLE bilhetes (
  id INT AUTO_INCREMENT PRIMARY KEY,
  utilizador_id INT NOT NULL,
  rota_id INT NOT NULL,
  codigo_validacao VARCHAR(50) UNIQUE NOT NULL,
  estado ENUM('comprado', 'usado', 'cancelado') DEFAULT 'comprado',
  FOREIGN KEY (utilizador_id) REFERENCES utilizadores(id) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (rota_id) REFERENCES rotas(id) ON DELETE CASCADE ON UPDATE CASCADE
);

-- Inserir alguns bilhetes
INSERT INTO bilhetes (utilizador_id, rota_id, codigo_validacao, estado) VALUES
  (7, 1, 'ABC123', 'comprado'),
  (8, 2, 'DEF456', 'comprado'),
  (9, 3, 'GHI789', 'comprado');