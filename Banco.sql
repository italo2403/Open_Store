CREATE DATABASE estoque;
USE estoque;

CREATE TABLE produtos (
  id_prod INT PRIMARY KEY AUTO_INCREMENT,
  codigo VARCHAR(50) ,
  nome VARCHAR(255) ,
  categoria ENUM('Anel', 'Brinco', 'Colar', 'Pulseira', 'Corrente', 'Pircing', 'Relógios') ,
  quantidade INT ,
  quantidade_minima INT ,
  descricao TEXT,
  data_cadastro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
ALTER TABLE produtos MODIFY categoria ENUM('Anel', 'Brinco', 'Colar', 'Pulseira', 'Corrente Masculina', 'Pircing', 'Relógios');

ALTER TABLE produtos MODIFY categoria ENUM('Anel', 'Brinco', 'Colar', 'Pulseira', 'Corrente', 'Corrente Masculina', 'Pircing', 'Relógios');


CREATE TABLE usuarios (
  id_usu INT PRIMARY KEY AUTO_INCREMENT,
  nome VARCHAR(255) ,
  senha VARCHAR(30) ,
  tipo ENUM('gerente', 'funcionario') 
);
insert into usuarios(nome, senha,tipo)values('italo','123','gerente');
insert into usuarios(nome, senha,tipo)values('maria','321','gerente');

CREATE TABLE relatorios (
  id_relatorio INT PRIMARY KEY AUTO_INCREMENT,
  tipo ENUM('estoque_atual', 'baixo_estoque', 'por_categoria', 'por_periodo') ,
  filtros TEXT,
  data_geracao TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE ordem_servico (
    id INT PRIMARY KEY AUTO_INCREMENT,
    numero_os VARCHAR(20) NOT NULL,
    cliente_nome VARCHAR(255) NOT NULL,
    cliente_telefone VARCHAR(15) NOT NULL,
    endereco VARCHAR(255) NOT NULL,
    descricao_servico TEXT NOT NULL,
    quantidade INT NOT NULL,
    valor_total DECIMAL(10, 2) NOT NULL,
    forma_pagamento ENUM('PIX', 'Dinheiro', 'Cartão Débito', 'Cartão Crédito', 'Credito Parcelado') NOT NULL,
    observacoes TEXT,
    usuario_id INT NOT NULL,
    data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id_usu) ON DELETE CASCADE
);


drop database estoque;