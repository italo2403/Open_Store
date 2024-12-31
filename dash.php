<?php
// dashboard.php
session_start();
require_once 'db.php';

if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit();
}

$usuario_id = $_SESSION['usuario_id'];
$usuario_tipo = $_SESSION['usuario_tipo'];
$usuario_nome = $_SESSION['usuario_nome'];

// Busca produtos com estoque baixo (para gerentes)
$produtos_baixo_estoque = [];
if ($usuario_tipo == 'gerente') {
    $sql = "SELECT * FROM produtos WHERE quantidade <= quantidade_minima";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $produtos_baixo_estoque = $stmt->fetchAll();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard - Controle de Estoque</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="img/logo.png" type="image/x-icon">
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
        }

        .header {
            background-color: #fff;
            padding: 1rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .user-info {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }

        .datetime {
            color: #666;
            font-size: 0.9rem;
        }

        .container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 1rem;
        }

        .actions-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .action-card {
            background: #fff;
            padding: 1.5rem;
            border-radius: 8px;
            text-align: center;
            transition: transform 0.2s;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .action-card:hover {
            transform: translateY(-5px);
        }

        .action-icon {
            font-size: 2rem;
            margin-bottom: 1rem;
            color: #3498db;
        }

        .alerts {
            background: #fff;
            padding: 1rem;
            border-radius: 8px;
            margin-top: 2rem;
        }

        .alert-item {
            padding: 0.5rem;
            margin: 0.5rem 0;
            border-left: 4px solid #e74c3c;
            background: #fff5f5;
        }

        @media (max-width: 768px) {
            .actions-grid {
                grid-template-columns: 1fr;
            }
        }

        .btn {
            display: inline-block;
            padding: 0.5rem 1rem;
            text-decoration: none;
            color: #fff;
            background-color: #3498db;
            border-radius: 4px;
            margin-top: 1rem;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="user-info">
            <div>
                <h2>Bem-vindo(a), <?php echo htmlspecialchars($usuario_nome); ?></h2>
                <p class="datetime">
                    <?php echo date('d/m/Y H:i'); ?>
                    <?php if ($usuario_tipo == 'gerente') echo " - Perfil: Gerente"; ?>
                </p>
            </div>
            <a href="logout.php" class="btn">Sair</a>
        </div>
    </div>

    <div class="container">
        <div class="actions-grid">
            <!-- Ações comuns -->
            <div class="action-card">
                <i class="fas fa-search action-icon"></i>
                <h3>Consulta de Produtos</h3>
                <p>Visualize todos os produtos cadastrados</p>
                <a href="consulta_produtos.php" class="btn">Acessar</a>
            </div>
<!-- Ações comuns -->
<div class="action-card">
                <i class="fas  fa-file action-icon"></i>
                <h3>Ordem de Serviço</h3>
                <p>Gere sua Vendas</p>
                <a href="os.php" class="btn">Acessar</a>
                <a href="imprimir_os.php" class="btn">Imprimir</a>
            </div>
            <?php if ($usuario_tipo == 'funcionario'): ?>
                <!-- Ações específicas para funcionários -->
                <div class="action-card">
                    <i class="fas fa-plus action-icon"></i>
                    <h3>Registrar Entrada</h3>
                    <p>Adicionar quantidade ao estoque</p>
                    <a href="cadastrar_produto.php" class="btn">Registrar</a>
                </div>

                <div class="action-card">
                    <i class="fas fa-minus action-icon"></i>
                    <h3>Registrar Saída</h3>
                    <p>Reduzir quantidade do estoque</p>
                    <a href="registrar_saida.php" class="btn">Registrar</a>
                </div>

                <div class="action-card">
                    <i class="fas fa-sync action-icon"></i>
                    <h3>Atualizar Quantidade</h3>
                    <p>Correções no estoque</p>
                    <a href="atualizar_quantidade.php" class="btn">Atualizar</a>
                </div>

            <?php else: ?>
                <!-- Ações específicas para gerentes -->
                <div class="action-card">
                    <i class="fas fa-plus-circle action-icon"></i>
                    <h3>Cadastrar Produto</h3>
                    <p>Adicionar novos produtos ao sistema</p>
                    <a href="cadastrar_produto.php" class="btn">Cadastrar</a>
                </div>

                <div class="action-card">
                    <i class="fas fa-chart-bar action-icon"></i>
                    <h3>Gerar Relatórios</h3>
                    <p>Relatórios de movimentação e estoque</p>
                    <a href="gerar_relatorio.php" class="btn">Acessar</a>
                </div>
            <?php endif; ?>
        </div>

        <?php if ($usuario_tipo == 'gerente' && !empty($produtos_baixo_estoque)): ?>
            <div class="alerts">
                <h3><i class="fas fa-exclamation-triangle"></i> Alertas</h3>
                <?php foreach ($produtos_baixo_estoque as $produto): ?>
                    <div class="alert-item">
                        Produto com estoque baixo: <?php echo htmlspecialchars($produto['nome']); ?> 
                        (Quantidade atual: <?php echo $produto['quantidade']; ?>)
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <script>
        // Atualiza a hora a cada minuto
        setInterval(() => {
            const now = new Date();
            const formatted = now.toLocaleDateString() + ' ' + 
                            now.toLocaleTimeString();
            document.querySelector('.datetime').textContent = formatted;
        }, 60000);
    </script>
</body>
</html>