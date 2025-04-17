
# 🛍️ Open Store - Sistema de Controle de Estoque para Pequenos Negócios

Este é um sistema de controle de estoque leve e funcional, ideal para pequenas lojas, bijuterias, negócios locais, artesãos e MEIs. Desenvolvido em PHP com MySQL, o Open Store oferece um painel simples com gerenciamento de produtos, relatórios e controle de acesso.

---

## ✅ Funcionalidades

- Cadastro, edição e exclusão de produtos
- Atualização de quantidade com motivo
- Geração de relatórios por categoria
- Controle de acesso (funcionário e gerente)
- Painéis separados por tipo de usuário
- Dashboard com alertas de estoque mínimo
- Impressão de ordens de serviço
- Login seguro com sessões
- Interface responsiva com Bootstrap

---

## 🛠️ Tecnologias Utilizadas

- PHP 8+
- MySQL
- Bootstrap 5
- HTML5 / CSS3 / JS
- Laragon para execução local

---

## 🚀 Como Instalar com Laragon

### 1. Clone ou baixe o projeto

Descompacte a pasta `Open_Store-main` e mova para a pasta www (Laragon):

### 2. Importe o banco de dados

- Abra o **phpMyAdmin**
- Crie um banco chamado `estoque`
- Importe o arquivo `Banco.sql` que está na raiz do projeto

### 3. Inicie o servidor

- Execute o **Apache** e o **MySQL** no LARAGON
- Acesse no navegador:

```
http://localhost/Open_Store-main/login.php
```

## 👤 Usuários e Permissões

| Tipo        | Permissões                             |
|-------------|----------------------------------------|
| Gerente     | Total acesso: editar, excluir, relatórios |
| Funcionário | Cadastro e visualização de produtos     |

## 📦 Estrutura de Pastas

📁 Open_Store-main
├── Banco.sql
├── db.php
├── login.php
├── logout.php
├── cadastrar_produto.php
├── editar_produto.php
├── excluir_produto.php
├── consulta_produtos.php
├── painel_gerente.php
├── painel_funcionario.php
├── gerar_relatorio.php
├── os.php
├── imprimir_os.php
├── atualizar_quantidade.php
├── img/
└── index.html

---

## 💡 Sugestões de Uso

- Venda do sistema como produto digital
- Implementação em pequenos comércios locais
- Versão personalizada para clientes
- Base para cursos de PHP e MySQL

---

## 🧑‍💻 Autor

Desenvolvido por: Italo Nunes  
Contato: [italonunespereira@gmail.com]

---
