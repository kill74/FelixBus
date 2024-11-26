--Tabela para os cargos dos Funcionarios 
-- Inserir isto no XAMPP
CREATE TABLE 'cargos' (
    IdFuncionario INT AUTO_INCREMENT PRIMARY KEY, -- ira ficar logo como chave primaria 
    Nome VARCHAR(250) NOT NULL,
    Cargo VARCHAR(250) NOT NULL,
);

-- inserir dados na tab

INSERT INTO 'cargos' ('IdFuncionario','Nome', 'Cargo') VALUES 
(1, 'Guilherme', 'admin'),

-- tabela para o login
CREATE TABLE `login` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL
)

--Inserir dados na tabela de login
INSERT INTO `login` (`id`, `username`, `email`, `password`) VALUES
(1, 'Guilherme Infante Sales', 'gnailpo895@gmail.com', '$2y$10$sSDISOaZMMn1vXloxq7n5Omli72AofkhhKnaRCqLZCX9oOZSelKaq'),
(2, 'Eduardo Pualino', 'eduardopaulino@gmail.com', '$2y$10$UVrypu3dCfvGSUwo/jZrueHIrh/sXM23Z4W4jQCx9hhiYs9/F10M.');


-- Resto das tabelas serao inseridas aqui

