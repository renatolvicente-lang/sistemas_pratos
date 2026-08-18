# Sistema de pratos do restaurante

Sistema CRUD simples em PHP e MySQL para cadastrar usuários, cadastrar pratos, listar pratos, editar, excluir e consultar os pratos por usuário.

## Requisitos

- PHP
- MySQL
- XAMPP ou ambiente semelhante

## Estrutura

- `index.php` - cadastro e listagem de usuários
- `public/usuarios.php` - listagem de usuários cadastrados
- `public/cadastro_pratos.php` - cadastro, listagem, edição e exclusão de pratos
- `public/editar_prato.php` - edição de um prato específico
- `public/excluir_prato.php` - exclusão de um prato específico
- `public/pratos_por_usuario.php` - consulta dos pratos por usuário
- `database/script.sql` - script para criar o banco de dados

## Como executar no XAMPP

1. Copie a pasta do projeto para `htdocs`.
2. Abra o XAMPP e inicie `Apache` e `MySQL`.
3. Abra o `phpMyAdmin`.
4. Importe o arquivo `database/script.sql`.
5. Confira o arquivo `infra/conexao.php` e ajuste usuário, senha e nome do banco se for necessário.
6. Acesse o projeto no navegador em `http://localhost/sistemas_pratos`.

## Observações

- O projeto usa `Prepared Statements` nas operações que recebem dados do usuário.
- Antes de cadastrar um prato, é necessário cadastrar pelo menos um usuário.
- Se o seu MySQL do XAMPP estiver sem senha, deixe a variável `$senha` vazia em `infra/conexao.php`.
