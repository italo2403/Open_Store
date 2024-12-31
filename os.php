<?php
session_start();
require_once 'db.php';

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit();
}

$mensagem = '';

// Processamento do formulário
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        $pdo->beginTransaction();

        // Valida os campos obrigatórios
        $cliente_nome = $_POST['cliente_nome'] ?? '';
        $cliente_telefone = $_POST['cliente_telefone'] ?? '';
        $endereco = $_POST['endereco'] ?? '';
        $descricao_servico = $_POST['descricao_servico'] ?? '';
        $quantidade = $_POST['quantidade'] ?? 0;
        $valor_total = $_POST['valor_total'] ?? '0.00';
        $forma_pagamento = $_POST['forma_pagamento'] ?? 'PIX';
        $observacoes = $_POST['observacoes'] ?? '';

        if (empty($cliente_nome) || empty($cliente_telefone) || empty($descricao_servico) || empty($endereco)) {
            throw new Exception("Preencha todos os campos obrigatórios.");
        }

        // Gera o número da OS (ANO + MÊS + SEQUENCIAL)
        $numero_os = date('Ym') . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);

        // Insere a ordem de serviço
        $sql = "INSERT INTO ordem_servico (
                    numero_os,
                    cliente_nome,
                    cliente_telefone,
                    endereco,
                    descricao_servico,
                    quantidade,
                    valor_total,
                    forma_pagamento,
                    observacoes,
                    usuario_id
                ) VALUES (
                    :numero_os,
                    :cliente_nome,
                    :cliente_telefone,
                    :endereco,
                    :descricao_servico,
                    :quantidade,
                    :valor_total,
                    :forma_pagamento,
                    :observacoes,
                    :usuario_id
                )";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':numero_os' => $numero_os,
            ':cliente_nome' => $cliente_nome,
            ':cliente_telefone' => $cliente_telefone,
            ':endereco' => $endereco,
            ':descricao_servico' => $descricao_servico,
            ':quantidade' => $quantidade,
            ':valor_total' => $valor_total,
            ':forma_pagamento' => $forma_pagamento,
            ':observacoes' => $observacoes,
            ':usuario_id' => $_SESSION['usuario_id']
        ]);

        $pdo->commit();
        $mensagem = "Ordem de Serviço criada com sucesso! Número: " . $numero_os;
    } catch (PDOException $e) {
        $pdo->rollBack();
        $mensagem = "Erro ao criar Ordem de Serviço: " . $e->getMessage();
    } catch (Exception $e) {
        $pdo->rollBack();
        $mensagem = $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Nova Ordem de Serviço</title>
    <meta charset="utf-8">
    <link rel="icon" href="img/logo.png" type="image/x-icon">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body { font-family: Arial; padding: 20px; }
        .container { max-width: 800px; margin: 0 auto; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; }
        input, select, textarea { width: 100%; padding: 8px; margin-bottom: 10px; }
        button { padding: 10px 20px; background: #3498db; color: white; border: none; cursor: pointer; }
        .mensagem { padding: 10px; margin-bottom: 20px; }
        .sucesso { background: #DFF0D8; border: 1px solid #3C763D; }
        .erro { background: #F2DEDE; border: 1px solid #A94442; }
        .btn-secondary{ padding: 10px 20px; background: #3498db; color: white; border: none; cursor: pointer; }
        a{
            text-decoration: none;
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

<img src="img/logo.png" alt="">
       


    <div class="container">
        <h1>Nova Ordem de Serviço</h1>

        <?php if ($mensagem): ?>
            <div class="mensagem <?php echo strpos($mensagem, 'sucesso') !== false ? 'sucesso' : 'erro'; ?>">
                <?php echo $mensagem; ?>
            </div>
        <?php endif; ?>

        <form method="POST">
    <div class="form-group">
        <label>Nome do Cliente:</label>
        <input type="text" name="cliente_nome" required value="<?php echo htmlspecialchars($_POST['cliente_nome'] ?? ''); ?>">
    </div>

    <div class="form-group">
        <label>Telefone:</label>
        <input type="text" name="cliente_telefone" required value="<?php echo htmlspecialchars($_POST['cliente_telefone'] ?? ''); ?>">
    </div>

    <div class="form-group">
        <label>Endereço:</label>
        <input type="text" name="endereco" required value="<?php echo htmlspecialchars($_POST['endereco'] ?? ''); ?>">
    </div>

    <div class="form-group">
        <label>Descrição do Serviço:</label>
        <textarea name="descricao_servico" required rows="4"><?php echo htmlspecialchars($_POST['descricao_servico'] ?? ''); ?></textarea>
    </div>

    <div class="form-group">
        <label>Quantidade:</label>
        <input type="number" name="quantidade" required min="1" value="<?php echo htmlspecialchars($_POST['quantidade'] ?? ''); ?>">
    </div>

    <div class="form-group">
        <label>Valor Total:</label>
        <input type="number" name="valor_total" step="0.01" required value="<?php echo htmlspecialchars($_POST['valor_total'] ?? ''); ?>">
    </div>

    <div class="form-group">
        <label>Forma de Pagamento:</label>
        <select name="forma_pagamento" required>
            <option value="PIX" <?php echo (isset($_POST['forma_pagamento']) && $_POST['forma_pagamento'] == 'PIX') ? 'selected' : ''; ?>>PIX</option>
            <option value="Dinheiro" <?php echo (isset($_POST['forma_pagamento']) && $_POST['forma_pagamento'] == 'Dinheiro') ? 'selected' : ''; ?>>Dinheiro</option>
            <option value="Cartão Débito" <?php echo (isset($_POST['forma_pagamento']) && $_POST['forma_pagamento'] == 'Cartão Débito') ? 'selected' : ''; ?>>Cartão Débito</option>
            <option value="Cartão Crédito" <?php echo (isset($_POST['forma_pagamento']) && $_POST['forma_pagamento'] == 'Cartão Crédito') ? 'selected' : ''; ?>>Cartão Crédito</option>
            <option value="Credito Parcelado" <?php echo (isset($_POST['forma_pagamento']) && $_POST['forma_pagamento'] == 'Credito Parcelado') ? 'selected' : ''; ?>>Crédito Parcelado</option>
        </select>
    </div>

    <div class="form-group">
        <label>Observações:</label>
        <textarea name="observacoes" rows="4"><?php echo htmlspecialchars($_POST['observacoes'] ?? ''); ?></textarea>
    </div>

    <button type="submit">Criar Ordem de Serviço</button>
    <button type="button" onclick="window.print()">Imprimir</button>
    <a href="dash.php" class="btn-secondary">Voltar</a>
</form>

    </div>
</body>
</html>
