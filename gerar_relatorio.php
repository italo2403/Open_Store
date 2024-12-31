<?php
// Conexão com o banco de dados
$conn = new mysqli('localhost', 'root', '', 'estoque');

if ($conn->connect_error) {
    die("Erro na conexão: " . $conn->connect_error);
}

// Buscar produtos por tipo
$tipo = $_GET['tipo'] ?? '';
$query = "";

if (!empty($tipo)) {
    $query = "SELECT * FROM produtos WHERE categoria = '$tipo'";
} else {
    $query = "SELECT * FROM produtos";
}

$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="img/logo.png" type="image/x-icon">
    <title>Relatório de Produtos</title>
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
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f4f4f4;
        }
        .btn {
    padding: 10px 15px;
    margin-top: 20px;
    margin-right: 10px; /* Espaçamento horizontal */
    background-color: #3498db;
    color: white;
    text-decoration: none;
    border-radius: 4px;
    display: inline-block; /* Garante o comportamento inline */
}

        @media print {
            .btn {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Relatório de Produtos</h2>
        <p><strong>Categoria Selecionada:</strong> <?= htmlspecialchars($tipo ?: 'Todos') ?></p>
        <p><strong>Data de Geração:</strong> <?= date('Y-m-d H:i:s') ?></p>

        <?php if ($result && $result->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Código</th>
                        <th>Nome</th>
                        <th>Categoria</th>
                        <th>Quantidade</th>
                        <th>Quantidade Mínima</th>
                        <th>Descrição</th>
                        <th>Data de Cadastro</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['codigo']) ?></td>
                            <td><?= htmlspecialchars($row['nome']) ?></td>
                            <td><?= htmlspecialchars($row['categoria']) ?></td>
                            <td><?= htmlspecialchars($row['quantidade']) ?></td>
                            <td><?= htmlspecialchars($row['quantidade_minima']) ?></td>
                            <td><?= htmlspecialchars($row['descricao']) ?></td>
                            <td><?= htmlspecialchars($row['data_cadastro']) ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Nenhum produto encontrado para esta categoria.</p>
        <?php endif; ?>

        <a href="javascript:window.print()" class="btn">Imprimir Relatório</a>
        <a href="dash.php" class="btn" style="background-color: #95a5a6;">Voltar</a>
    </div>
</body>
</html>

<?php
$conn->close();
?>
