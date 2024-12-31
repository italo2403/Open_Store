<?php
session_start();
require_once 'db.php';

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit();
}

$ordem_servicos = [];
$mensagem = '';

// Verifica se o nome foi enviado
if (isset($_GET['cliente_nome']) && !empty($_GET['cliente_nome'])) {
    $cliente_nome = trim($_GET['cliente_nome']); // Sanitiza o nome recebido

    // Busca as ordens de serviço pelo nome do cliente
    try {
        $stmt = $pdo->prepare("SELECT * FROM ordem_servico WHERE cliente_nome LIKE :cliente_nome");
        $stmt->execute([':cliente_nome' => '%' . $cliente_nome . '%']);
        $ordem_servicos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (empty($ordem_servicos)) {
            $mensagem = "Nenhuma Ordem de Serviço encontrada para o cliente: " . htmlspecialchars($cliente_nome);
        }
    } catch (PDOException $e) {
        $mensagem = "Erro ao buscar Ordens de Serviço: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Buscar Ordens de Serviço</title>
    <meta charset="utf-8">
    <link rel="icon" href="img/logo.png" type="image/x-icon">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        .container { max-width: 800px; margin: 0 auto; }
        .mensagem { padding: 10px; margin-bottom: 20px; border: 1px solid #ccc; background: #f9f9f9; }
        .btn { padding: 10px 20px; margin-top: 20px; background-color: #3498db; color: white; border: none; cursor: pointer; text-decoration: none; display: inline-block; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        table, th, td { border: 1px solid #ddd; }
        th, td { padding: 10px; text-align: left; }
        th { background-color: #f4f4f4; }
        @media print {
            .btn { display: none; }
        }


        .container2 {
            max-width: 1200px;
            margin: 0 auto;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        img {
        display: block;
        margin: 0 auto;
        max-width: 100%;
        width: 150px;
        height: 150px;
        height: auto;
    }
    </style>
</head>
<body>



<div class="container2">
        <!-- <div class="header">
            <h1>Consulta de Produtos</h1> -->
       
<img src="img/logo.png" alt="">

    <div class="container">
        <h1>Buscar Ordens de Serviço</h1>

        <form method="GET">
            <label>Nome do Cliente:</label>
            <input type="text" name="cliente_nome" placeholder="Digite o nome do cliente" required>
            <button type="submit" class="btn">Buscar</button>
            <a href="dash.php" class="btn btn-voltar">
                <i class="fas fa-arrow-left"></i> Voltar
            </a>
        </form>

        <?php if ($mensagem): ?>
            <div class="mensagem"><?php echo $mensagem; ?></div>
        <?php elseif (!empty($ordem_servicos)): ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Número OS</th>
                        <th>Nome do Cliente</th>
                        <th>Telefone</th>
                        <th>Endereço</th>
                        <th>Descrição</th>
                        <th>Valor Total</th>
                        <th>Data</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($ordem_servicos as $os): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($os['id']); ?></td>
                            <td><?php echo htmlspecialchars($os['numero_os']); ?></td>
                            <td><?php echo htmlspecialchars($os['cliente_nome']); ?></td>
                            <td><?php echo htmlspecialchars($os['cliente_telefone']); ?></td>
                            <td><?php echo htmlspecialchars($os['endereco']); ?></td>
                            <td><?php echo htmlspecialchars($os['descricao_servico']); ?></td>
                            <td><?php echo number_format($os['valor_total'], 2, ',', '.'); ?></td>
                            <td><?php echo htmlspecialchars($os['data_criacao']); ?></td>
                            <td>
                                <button class="btn" onclick="window.print()">Imprimir</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</body>
</html>
