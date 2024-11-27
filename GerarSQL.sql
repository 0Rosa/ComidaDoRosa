-- Criação do banco de dados
CREATE DATABASE restaurante;

-- Selecionar o banco de dados
USE restaurante;

-- Tabela de usuários
CREATE TABLE tb_usuario (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    data_nascimento DATE,
    telefone VARCHAR(20),
    senha VARCHAR(255) NOT NULL,
    cep VARCHAR(10),
    rua VARCHAR(255),
    numero VARCHAR(10),
    bairro VARCHAR(100),
    complemento VARCHAR(100),
    cidade VARCHAR(100),
    estado CHAR(2)
);

-- Tabela de categorias do cardápio
CREATE TABLE tb_categoria (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255) NOT NULL
);

-- Tabela de itens do cardápio
CREATE TABLE tb_itens (
    id INT AUTO_INCREMENT PRIMARY KEY,
    idCategoria INT,
    nome VARCHAR(255) NOT NULL,
    descricao TEXT,
    foto BLOB,
    preco DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (idCategoria) REFERENCES tb_categoria(id)
);

-- Tabela de itens do pedido
CREATE TABLE tb_itens_pedido (
    id INT AUTO_INCREMENT PRIMARY KEY,
    idUsuario INT,
    idItem INT,
    quantidade INT NOT NULL,
    preco DECIMAL(10, 2) NOT NULL,
    finalizado BOOLEAN NOT NULL DEFAULT FALSE,
    FOREIGN KEY (idUsuario) REFERENCES tb_usuario(id),
    FOREIGN KEY (idItem) REFERENCES tb_itens(id)
);

INSERT INTO tb_categoria (nome) VALUES ('Entradas'), ('Pratos Principais'), ('Sobremesas'), ('Bebidas');

INSERT INTO tb_itens (idCategoria, nome, descricao, preco, foto) 
VALUES 
(1, 'Bruschetta', 'Pão italiano com tomate fresco e manjericão.', 15.00, 'https://i.imgur.com/dIu0cfm.jpg'), 
(1, 'Camarões ao Alho', 'Camarões grelhados com molho de alho e limão.', 18.00, 'https://i.imgur.com/A7tXUvh.jpg'),
(2, 'Filé Mignon', 'Filé ao ponto com molho madeira e purê de batatas.', 65.00, 'https://i.imgur.com/YvZT9H5.jpg'), 
(2, 'Frango Grelhado', 'Frango grelhado com ervas finas e legumes assados.', 40.00, 'https://i.imgur.com/84qjNfP.jpg'),
(3, 'Torta de Limão', 'Torta cremosa de limão com merengue.', 12.00, 'https://i.imgur.com/28o1qbm.jpg'), 
(3, 'Mousse de Maracujá', 'Mousse leve e cremosa de maracujá.', 10.00, 'https://i.imgur.com/KMhPr05.jpg'),
(4, 'Suco Natural', 'Suco de laranja natural.', 8.00, 'https://i.imgur.com/cc4ZGzX.jpg'), 
(4, 'Café Preto', 'Café expresso forte e aromático.', 6.00, 'https://i.imgur.com/T6DfgdI.jpg');
