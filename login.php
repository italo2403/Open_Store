<?php
session_start();
require_once 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST['nome'] ?? '';
    $senha = $_POST['senha'] ?? '';
    
    if (empty($nome) || empty($senha)) {
        $erro = "Por favor, preencha todos os campos.";
    } else {
        $sql = "SELECT * FROM usuarios WHERE nome = :nome AND senha = :senha";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['nome' => $nome, 'senha' => $senha]);
        
        if ($usuario = $stmt->fetch()) {
            $_SESSION['usuario_id'] = $usuario['id_usu'];
            $_SESSION['usuario_nome'] = $usuario['nome'];
            $_SESSION['usuario_tipo'] = $usuario['tipo'];
            
            // Redireciona baseado no tipo de usuário
            if ($usuario['tipo'] == 'gerente') {
                header("Location: dash.php");
            } else {
                header("Location: painel_funcionario.php");
            }
            exit();
        } else {
            $erro = "Usuário ou senha inválidos.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <meta charset="utf-8">
    <link rel="icon" href="img/logo.png" type="image/x-icon">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f6f9;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            max-width: 400px;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            width: 100%;
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #2c3e50;
        }
        form div {
            margin-bottom: 15px;
        }
        label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
            color: #34495e;
        }
        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        button {
            width: 100%;
            padding: 10px;
            background-color: #3498db;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
        }
        button:hover {
            background-color: #2980b9;
        }
        .error {
            color: #e74c3c;
            margin-bottom: 15px;
            text-align: center;
        }

        img {
        display: block;
        margin: 0 auto;
        max-width: 100%;
        width: 150px;
        height: 150px;
        height: auto;
    } 
    
        @media (max-width: 768px) {
            .container {
                padding: 15px;
            }
            button {
                font-size: 14px;
            }
        }
    </style>
</head>
<body>

    <div class="container">
    <img src="img/logo.png" alt=""><h2>Login</h2>
        <?php if (isset($erro)): ?>
            <p class="error"><?php echo $erro; ?></p>
        <?php endif; ?>
        
        <form method="POST">
            <div>
                <label for="nome">Nome:</label>
                <input type="text" id="nome" name="nome" required>
            </div>
            <div>
                <label for="senha">Senha:</label>
                <input type="password" id="senha" name="senha" required>
            </div>
            <button type="submit">Entrar</button>
        </form>
    </div>
</body>
</html>
