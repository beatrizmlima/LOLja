<?php
session_start();

// Verifica se está logado
if (!isset($_SESSION['usuario'])) {
    header('Location: login.php');
    exit;
}

if (isset($_GET['id'])) {
    $id = (int) $_GET['id'];
    
    // Remove o item do carrinho se ele existir
    if (isset($_SESSION['carrinho'][$id])) {
        unset($_SESSION['carrinho'][$id]);
        $_SESSION['carrinho_msg'] = 'removed';
    } else {
        $_SESSION['carrinho_msg'] = 'error_notfound';
    }
} else {
    $_SESSION['carrinho_msg'] = 'error_data';
}

header('Location: carrinho.php');
exit;
?>