<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="img/logo.png" type="image/x-icon">
    <title>Cadastrar Produto</title>
    <style>
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
        select {
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
        <h2>Cadastrar Produto</h2>
        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $codigo = $_POST['codigo'] ?? '';
            $nome = $_POST['nome'] ?? '';
            $categoria = $_POST['categoria'] ?? '';
            $quantidade = $_POST['quantidade'] ?? 0;
            $quantidade_minima = $_POST['quantidade_minima'] ?? 0;
            $descricao = $_POST['descricao'] ?? '';

            // Conexão com o banco de dados
            $conn = new mysqli('localhost', 'root', '', 'estoque');

            if ($conn->connect_error) {
                echo "<div class='mensagem erro'>Erro na conexão: " . $conn->connect_error . "</div>";
            } else {
                $stmt = $conn->prepare("INSERT INTO produtos (codigo, nome, categoria, quantidade, quantidade_minima, descricao) VALUES (?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("sssdds", $codigo, $nome, $categoria, $quantidade, $quantidade_minima, $descricao);
               
                $categorias_validas = ['Anel', 'Brinco', 'Colar', 'Pulseira', 'Corrente','Corrente Masculina', 'Pircing', 'Relógios'];
                if (!in_array($categoria, $categorias_validas)) {
                    echo "<div class='mensagem erro'>Categoria inválida.</div>";
                    exit;
                }
                
                if ($stmt->execute()) {
                    echo "<div class='mensagem sucesso'>Produto cadastrado com sucesso!</div>";
                } else {
                    echo "<div class='mensagem erro'>Erro ao cadastrar o produto: " . $stmt->error . "</div>";
                }

                $stmt->close();
                $conn->close();
            }
        }
        ?>

        <form method="POST" action="">
            <div class="form-group">
                <label for="codigo">Código</label>
                <input type="text" id="codigo" name="codigo" required>
            </div>

            <div class="form-group">
                <label for="nome">Nome</label>
                <input type="text" id="nome" name="nome" required>
            </div>

            <div class="form-group">
                <label for="categoria">Categoria</label>
                <select id="categoria" name="categoria" required>
                    <option value="">Selecione...</option>
                    <option value="Anel">Anel</option>
                    <option value="Brinco">Brinco</option>
                    <option value="Colar">Colar</option>
                    <option value="Pulseira">Pulseira</option>
                    <option value="Corrente Masculina">Corrente Masculina</option>
                    <option value="Pircing">Pircing</option>
                    <option value="Relógios">Relógios</option>
                </select>
            </div>
           
            <div class="form-group">
                <label for="quantidade">Quantidade</label>
                <input type="number" id="quantidade" name="quantidade" min="0" required>
            </div>

            <div class="form-group">
                <label for="quantidade_minima">Quantidade Mínima</label>
                <input type="number" id="quantidade_minima" name="quantidade_minima" min="0" required>
            </div>

            <div class="form-group">
                <label for="descricao">Descrição</label>
                <textarea id="descricao" name="descricao" rows="4" required></textarea>
            </div>

            <button type="submit" class="btn btn-primary">Cadastrar</button>
            <a href="dash.php" class="btn btn-secondary">Voltar</a>
        </form>
    </div>
</body>
</html>
