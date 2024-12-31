<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit();
}

$usuario_tipo = $_SESSION['usuario_tipo'];

// Processamento do filtro de busca
$busca = isset($_GET['busca']) ? $_GET['busca'] : '';
$categoria = isset($_GET['categoria']) ? $_GET['categoria'] : '';

// Construção da query base
$sql = "SELECT * FROM produtos WHERE 1=1";
$params = [];

if (!empty($busca)) {
    $sql .= " AND (nome LIKE :busca OR codigo LIKE :busca)";
    $params[':busca'] = "%{$busca}%";
}

if (!empty($categoria)) {
    $sql .= " AND categoria = :categoria";
    $params[':categoria'] = $categoria;
}

// Executar a query
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$produtos = $stmt->fetchAll();

// Buscar categorias para o filtro
$stmt_cat = $pdo->query("SELECT DISTINCT categoria FROM produtos ORDER BY categoria");
$categorias = $stmt_cat->fetchAll(PDO::FETCH_COLUMN);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Consulta de Produtos</title>
    <meta charset="utf-8">
    <link rel="icon" href="img/logo.png" type="image/x-icon">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f4f6f9;
            padding: 20px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .search-section {
            background: white;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .search-form {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .form-group {
            flex: 1;
            min-width: 200px;
        }

        input, select {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .btn {
            padding: 8px 16px;
            border: none;
            border-radius: 4px;
            background-color: #3498db;
            color: white;
            cursor: pointer;
            text-decoration: none;
        }

        .btn:hover {
            background-color: #2980b9;
        }

        .btn-voltar {
            background-color: #95a5a6;
        }

        .table-container {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f8f9fa;
            font-weight: bold;
        }

        tr:hover {
            background-color: #f5f5f5;
        }

        .status {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 0.9em;
        }

        .status-normal {
            background-color: #2ecc71;
            color: white;
        }

        .status-baixo {
            background-color: #e74c3c;
            color: white;
        }

        .status-medio {
            background-color: #f1c40f;
            color: black;
        }

        @media (max-width: 768px) {
            .search-form {
                flex-direction: column;
            }
            
            .form-group {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Consulta de Produtos</h1>
            <a href="dash.php" class="btn btn-voltar">
                <i class="fas fa-arrow-left"></i> Voltar
            </a>
        </div>

        <div class="search-section">
            <form class="search-form" method="GET">
                <div class="form-group">
                    <input type="text" name="busca" placeholder="Buscar por nome ou código" 
                           value="<?php echo htmlspecialchars($busca); ?>">
                </div>
                <div class="form-group">
                    <select name="categoria">
                        <option value="">Todas as categorias</option>
                        <?php foreach ($categorias as $cat): ?>
                            <option value="<?php echo htmlspecialchars($cat); ?>"
                                    <?php echo $categoria === $cat ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($cat); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button type="submit" class="btn">
                    <i class="fas fa-search"></i> Buscar
                </button>
            </form>
        </div>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Código</th>
                        <th>Nome</th>
                        <th>Categoria</th>
                        <th>Quantidade</th>
                        <th>Status</th>
                        <?php if ($usuario_tipo == 'gerente'): ?>
                            <th>Ações</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($produtos as $produto): ?>
                        <?php
                        // Define o status do produto
                        $status_class = 'status-normal';
                        $status_text = 'Normal';
                        
                        if ($produto['quantidade'] <= $produto['quantidade_minima']) {
                            $status_class = 'status-baixo';
                            $status_text = 'Baixo';
                        } elseif ($produto['quantidade'] <= $produto['quantidade_minima'] * 1.5) {
                            $status_class = 'status-medio';
                            $status_text = 'Médio';
                        }
                        ?>
                        <tr>
                            <td><?php echo htmlspecialchars($produto['codigo']); ?></td>
                            <td><?php echo htmlspecialchars($produto['nome']); ?></td>
                            <td><?php echo htmlspecialchars($produto['categoria']); ?></td>
                            <td><?php echo htmlspecialchars($produto['quantidade']); ?></td>
                            <td>
                                <span class="status <?php echo $status_class; ?>">
                                    <?php echo $status_text; ?>
                                </span>
                            </td>
                            <?php if ($usuario_tipo == 'gerente'): ?>
                                <td>
                                    <a href="editar_produto.php?id_prod=<?php echo $produto['id_prod']; ?>" class="btn">
                                        <i class="fas fa-edit"></i> Editar
                                    </a>
                                </td>
                            <?php endif; ?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>