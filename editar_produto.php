<?php
// editar_produto.php
session_start();
require_once 'db.php';

if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] !== 'gerente') {
    header('Location: login.php');
    exit();
}

$mensagem = '';
$produto = null;

if (isset($_GET['id_prod'])) {
    $stmt = $pdo->prepare("SELECT * FROM produtos WHERE id_prod = ?");
    $stmt->execute([$_GET['id_prod']]);
    $produto = $stmt->fetch();

    if (!$produto) {
        header('Location: consulta_produtos.php');
        exit();
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['atualizar'])) {
    try {
        $sql = "UPDATE produtos SET 
                codigo = :codigo,
                nome = :nome,
                categoria = :categoria,
                quantidade = :quantidade,
                quantidade_minima = :quantidade_minima,
                descricao = :descricao
                WHERE id_prod = :id_prod";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':codigo' => $_POST['codigo'],
            ':nome' => $_POST['nome'],
            ':categoria' => $_POST['categoria'],
            ':quantidade' => $_POST['quantidade'],
            ':quantidade_minima' => $_POST['quantidade_minima'],
            ':descricao' => $_POST['descricao'],
            ':id_prod' => $_GET['id_prod']
        ]);

        $mensagem = "Produto atualizado com sucesso!";
        
        // Recarrega os dados do produto
        $stmt = $pdo->prepare("SELECT * FROM produtos WHERE id_prod = ?");
        $stmt->execute([$_GET['id_prod']]);
        $produto = $stmt->fetch();
    } catch (PDOException $e) {
        $mensagem = "Erro ao atualizar produto: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Editar Produto</title>
    <meta charset="utf-8">
    <link rel="icon" href="img/logo.png" type="image/x-icon">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        /* Estilos básicos */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f6f9;
            padding: 20px;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        input[type="text"],
        input[type="number"],
        textarea {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            color: white;
            text-decoration: none;
            display: inline-block;
            margin-right: 10px;
        }
        .btn-primary { background-color: #3498db; }
        .btn-danger { background-color: #e74c3c; }
        .btn-secondary { background-color: #95a5a6; }
        .mensagem {
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 4px;
        }
        .sucesso { background-color: #d4edda; color: #155724; }
        .erro { background-color: #f8d7da; color: #721c24; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Editar Produto</h1>

        <?php if ($mensagem): ?>
            <div class="mensagem <?php echo strpos($mensagem, 'sucesso') !== false ? 'sucesso' : 'erro'; ?>">
                <?php echo $mensagem; ?>
            </div>
        <?php endif; ?>

        <?php if ($produto): ?>
            <form method="POST">
                <div class="form-group">
                    <label>Código:</label>
                    <input type="text" name="codigo" value="<?php echo htmlspecialchars($produto['codigo']); ?>" required>
                </div>

                <div class="form-group">
                    <label>Nome:</label>
                    <input type="text" name="nome" value="<?php echo htmlspecialchars($produto['nome']); ?>" required>
                </div>

                <div class="form-group">
                    <label>Categoria:</label>
                    <input type="text" name="categoria" value="<?php echo htmlspecialchars($produto['categoria']); ?>" required>
                </div>

                <div class="form-group">
                    <label>Quantidade:</label>
                    <input type="number" name="quantidade" value="<?php echo htmlspecialchars($produto['quantidade']); ?>" required>
                </div>

                <div class="form-group">
                    <label>Quantidade Mínima:</label>
                    <input type="number" name="quantidade_minima" value="<?php echo htmlspecialchars($produto['quantidade_minima']); ?>" required>
                </div>

                <div class="form-group">
                    <label>Descrição:</label>
                    <textarea name="descricao" rows="4"><?php echo htmlspecialchars($produto['descricao']); ?></textarea>
                </div>

                <button type="submit" name="atualizar" class="btn btn-primary">Atualizar</button>
                <a href="excluir_produto.php?id_prod=<?php echo $produto['id_prod']; ?>" class="btn btn-danger" 
                   onclick="return confirm('Tem certeza que deseja excluir este produto?')">Excluir</a>
                <a href="consulta_produtos.php" class="btn btn-secondary">Voltar</a>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>
