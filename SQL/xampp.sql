CREATE TABLE tipos_utilizador (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(50) UNIQUE NOT NULL
);

CREATE TABLE utilizadores (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(150) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    palavra_passe VARCHAR(255) NOT NULL,
    tipo_utilizador_id INT NOT NULL,
    estado ENUM('ativo', 'inativo') DEFAULT 'ativo',
    FOREIGN KEY (tipo_utilizador_id) REFERENCES tipos_utilizador(id)
);

CREATE TABLE carteira (
    id INT AUTO_INCREMENT PRIMARY KEY,
    utilizador_id INT NOT NULL,
    saldo DECIMAL(10,2) DEFAULT 0.00,
    FOREIGN KEY (utilizador_id) REFERENCES utilizadores(id)
);

CREATE TABLE rotas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    origem VARCHAR(100) NOT NULL,
    destino VARCHAR(100) NOT NULL
);

CREATE TABLE viagens (
    id INT AUTO_INCREMENT PRIMARY KEY,
    rota_id INT NOT NULL,
    capacidade INT NOT NULL,
    data_viagem DATE NOT NULL,
    hora_partida TIME NOT NULL,
    hora_chegada TIME NOT NULL,
    preco DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (rota_id) REFERENCES rotas(id)
);

CREATE TABLE bilhetes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    utilizador_id INT NOT NULL,
    viagem_id INT NOT NULL,
    codigo_validacao VARCHAR(50) UNIQUE NOT NULL,
    estado ENUM('comprado', 'usado', 'cancelado') DEFAULT 'comprado',
    FOREIGN KEY (utilizador_id) REFERENCES utilizadores(id),
    FOREIGN KEY (viagem_id) REFERENCES viagens(id)
);

CREATE TABLE transacoes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    utilizador_id INT NOT NULL,
    valor DECIMAL(10,2) NOT NULL,
    tipo ENUM('carregamento', 'compra', 'levantamento') NOT NULL,
    data_transacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (utilizador_id) REFERENCES utilizadores(id)
);

CREATE TABLE alertas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(200) NOT NULL,
    descricao TEXT,
    data_inicio DATETIME NOT NULL,
    data_fim DATETIME NOT NULL
);

INSERT INTO tipos_utilizador (nome) VALUES 
('cliente'), 
('funcionario'), 
('administrador'),
('visitante');
