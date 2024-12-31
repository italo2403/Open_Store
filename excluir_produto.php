<?php
// excluir_produto.php
session_start();
require_once 'db.php';

if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] !== 'gerente') {
    header('Location: login.php');
    exit();
}

if (isset($_GET['id_prod'])) {
    try {
        // Primeiro verifica se o produto existe
        $stmt = $pdo->prepare("SELECT id_prod FROM produtos WHERE id_prod = ?");
        $stmt->execute([$_GET['id_prod']]);
        if (!$stmt->fetch()) {
            header('Location: consulta_produtos.php');
            exit();
        }

        // Exclui o produto
        $stmt = $pdo->prepare("DELETE FROM produtos WHERE id_prod = ?");
        $stmt->execute([$_GET['id_prod']]);

        $_SESSION['mensagem'] = "Produto excluído com sucesso!";
    } catch (PDOException $e) {
        $_SESSION['mensagem'] = "Erro ao excluir produto: " . $e->getMessage();
    }
}

header('Location: consulta_produtos.php');
exit();

