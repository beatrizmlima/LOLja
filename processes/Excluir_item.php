<?php
session_start();

// Verifica se está logado e é admin
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['tipo'] !== 'Admin') {
    header('Location: ../login.php');
    exit;
}

// Include nas configurações
include_once '../config/config.php';

// Conexão com banco de dados
$conn = dbConnect();

// Verificando se o metodo de requisição é post
if ($_SERVER['REQUEST_METHOD'] == 'POST'){
    $titulo = trim($_POST['titulo']);

    $result = "SELECT * FROM itens WHERE nome = '$titulo'";
    $check = mysqli_query($conn, $result);
    
    if (mysqli_num_rows($check) > 0){
        $sql = "DELETE FROM itens WHERE nome = '$titulo'";
        if (mysqli_query($conn, $sql)) {
            $_SESSION['deletado_sucesso'] = true;
            header('Location: ../itens.php');
            exit;
        } else {
            echo "<script>alert('Erro ao deletar item'); window.history.back();</script>";
        }
    } else {
        echo "<script>alert('Item não encontrado'); window.history.back();</script>";
    }
    $conn->close();
} else {
    header('Location: admin_dashboard.php');
    exit();
}