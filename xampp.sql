--Tabela para os cargos dos Funcionarios 
-- Inserir isto no XAMPP
CREATE TABLE IF NOT EXISTS Funcionarios (
    IdFuncionario INT AUTO_INCREMENT PRIMARY KEY, -- ira ficar logo como chave primaria 
    NomeCompleto VARCHAR(250) NOT NULL,
    Cargo VARCHAR(250) NOT NULL,
    DataContratacao DATE NOT NULL,
    Salario DECIMAL(10, 2)
);

-- Resto das tabelas serao inseridas aqui

