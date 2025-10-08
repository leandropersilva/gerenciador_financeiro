-- Tabela USUARIO
CREATE TABLE USUARIO (
    id_usuario INT NOT NULL,
    login VARCHAR(50) NOT NULL,
    senha VARCHAR(50) NOT NULL,
    telefone VARCHAR(15),
    nome VARCHAR(60) NOT NULL,
    saldo DECIMAL(15,2) NOT NULL,
    idade INT,
    sexo CHAR(1),
    PRIMARY KEY (id_usuario),
    UNIQUE (login)
);

-- Tabela MOVIMENTACAO_USUARIO
CREATE TABLE MOVIMENTACAO_USUARIO (
    id_usuario INT NOT NULL,
    id_movimentacao INT NOT NULL,
    valor DECIMAL(15,2) NOT NULL,
    moeda CHAR(3) NOT NULL,
    status VARCHAR(15) NOT NULL,
    tipo VARCHAR(30) NOT NULL,
    categoria VARCHAR(15) NOT NULL,
    origem VARCHAR(15) NOT NULL,
    data_hora TIMESTAMP NOT NULL,
    observacoes BLOB SUB_TYPE TEXT,
    anexos VARCHAR(2048),
    PRIMARY KEY (id_usuario, id_movimentacao),
    FOREIGN KEY (id_usuario) REFERENCES USUARIO(id_usuario)
);

-- Inserir dados de teste na tabela USUARIO
INSERT INTO USUARIO (id_usuario, login, senha, telefone, nome, saldo, idade, sexo)
VALUES (1, 'usuario.teste', 'senha123', '11987654321', 'João Silva', 1500.50, 25, 'M');

-- Inserir dados de teste na tabela MOVIMENTACAO_USUARIO
INSERT INTO MOVIMENTACAO_USUARIO (id_usuario, id_movimentacao, valor, moeda, status, tipo, categoria, origem, data_hora, observacoes, anexos)
VALUES (1, 1, 250.00, 'BRL', 'concluido', 'deposito', 'salario', 'pix', CURRENT_TIMESTAMP, 'Pagamento mensal', NULL);