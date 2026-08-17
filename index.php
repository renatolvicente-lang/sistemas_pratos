<?php 

 session_start(); // inicia uma sessão

    include("infra/db/connect.php"); // connecta com o BD

    if($_SERVER['REQUEST_METHOD'] == "POST"){ // Verifica se o request_method é do tipo "POST"

        $usuario = $_POST["usuario"];//guarda o dado inserido pelo usuário no input de name "usuario"
        $senha = $_POST["senha"];// guarda o dado inserido pelo usuário no input de name "senha"
        
        $sql = "SELECT * FROM usuarios WHERE usuario = '$usuario' AND senha = '$senha'";// cria uma query que seleciona todos os dados da tabela usuario que são iguais aos que usuario digitou

        $resultado = $conn->query($sql);// armazena a variavel $conn que executa a query $sql

        if ($resultado->num_rows > 0){// verifica se o numero de linhas da matriz resultado é maior que 0
            $_SESSION["usuario"] = $usuario;// Nomeia a Sessão
            header("Location: public/home.php");// manda para a pagina home.php
            exit();
        }else{
            $erro = "Usuário ou senha inválidos!";// mensagem caso a operação de erro
        }
    }



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>Sitema de Login Simples</h1>

    <form method="POST">
        <label>Usuário:</label>
        <input type="text" name="usuario">
        <br>
        <label>Senha:</label>
        <input type="password" name="senha">
        <br>
        <?php
            
            if(isset($erro)){
                echo $erro;
            };

            
        ?>
        <br>
        <button type="submit">Entrar</button>
    </form>
</body>
</html>