<?php
// atualizar_quantidade.php
session_start();
require_once 'db.php';

if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit();
}

$mensagem = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        $pdo->beginTransaction();

        $produto_id = $_POST['produto_id'];
        $nova_quantidade = $_POST['nova_quantidade'];
        $motivo = $_POST['motivo'];

        // Atualiza a quantidade do produto
        $sql = "UPDATE produtos SET quantidade = :quantidade WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':quantidade' => $nova_quantidade,
            ':id' => $produto_id
        ]);

        // Registra a atualização no histórico
        $sql = "INSERT INTO historico_atualizacoes (produto_id, usuario_id, quantidade_anterior, 
                quantidade_nova, motivo, data_atualizacao) 
                VALUES (:produto_id, :usuario_id, :quantidade_anterior, :quantidade_nova, :motivo, NOW())";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':produto_id' => $produto_id,
            ':usuario_id' => $_SESSION['usuario_id'],
            ':quantidade_anterior' => $_POST['quantidade_atual'],
            ':quantidade_nova' => $nova_quantidade,
            ':motivo' => $motivo
        ]);

        $pdo->commit();
        $mensagem = "Quantidade atualizada com sucesso!";
    } catch (PDOException $e) {
        $pdo->rollBack();
        $mensagem = "Erro ao atualizar quantidade: " . $e->getMessage();
    }
}

// Busca produtos para o select
$stmt = $pdo->query("SELECT id, codigo, nome, quantidade FROM produtos ORDER BY nome");
$produtos = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Atualizar Quantidade</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="img/logo.png" type="image/x-icon">
    <style>
        /* Mesmos estilos do editar_produto.php */
    </style>
</head>
<body>
    <div class="container">
        <h1>Atualizar Quantidade</h1>

        <?php if ($mensagem): ?>
            <div class="mensagem <?php echo strpos($mensagem, 'sucesso') !== false ? 'sucesso' : 'erro'; ?>">
                <?php echo $mensagem; ?>
            </div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label>Produto:</label>
                <select name="produto_id" required onchange="atualizarQuantidadeAtual(this.value)">
                    <option value="">Selecione um produto</option>
                    <?php foreach ($produtos as $produto): ?>
                        <option value="<?php echo $produto['id']; ?>" 
                                data-quantidade="<?php echo $produto['quantidade']; ?>">
                            <?php echo htmlspecialchars($produto['codigo'] . ' - ' . $produto['nome']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label>Quantidade Atual:</label>
                <input type="number" name="quantidade_atual" id="quantidade_atual" readonly>
            </div>

            <div class="form-group">
                <label>Nova Quantidade:</label>
                <input type="number" name="nova_quantidade" required>
            </div>

            <div class="form-group">
                <label>Motivo da Atualização:</label>
                <textarea name="motivo" rows="4" required></textarea>
            </div>

            <button type="submit" class="btn btn-primary">Atualizar</button>
            <a href="dashboard.php" class="btn btn-secondary">Voltar</a>
        </form>
    </div>

    <script>
        function atualizarQuantidadeAtual(produtoId) {
            const select = document.querySelector('select[name="produto_id"]');
            const option = select.options[select.selectedIndex];
            const quantidade = option.getAttribute('data-quantidade');
            document.getElementById('quantidade_atual').value = quantidade || '';
        }
    </script>
</body>
</html>