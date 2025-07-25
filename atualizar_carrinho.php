<?php
session_start();

// Verifica se está logado
if (!isset($_SESSION['usuario'])) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['produto_id'], $_POST['quantidade'])) {
    $id = (int) $_POST['produto_id'];
    $quantidade = (int) $_POST['quantidade'];
    
    // Verifica se a quantidade é válida e se o item existe no carrinho
    if ($quantidade > 0 && isset($_SESSION['carrinho'][$id])) {
        $_SESSION['carrinho'][$id]['quantidade'] = $quantidade;
        $_SESSION['carrinho_msg'] = 'updated';
    } else {
        $_SESSION['carrinho_msg'] = 'error_data';
    }
} else {
    $_SESSION['carrinho_msg'] = 'error_data';
}

header('Location: carrinho.php');
exit;
?>