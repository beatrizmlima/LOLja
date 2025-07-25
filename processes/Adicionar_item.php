<?php
session_start();

// Verifica se está logado e é admin
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['tipo'] !== 'Admin') {
    header('Location: ../login.php');
    exit;
}

include_once '../config/config.php';

$conn = dbConnect();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = $_POST['titulo'];
    $classe = $_POST['classe'];
    $descricao = $_POST['descricao'];
    $preco = $_POST['preco'];
    
    // Cria o nome da foto e o lugar onde sera salva
    if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] == 0) {
        $imagem_nome = uniqid() . "_" . basename($_FILES['imagem']['name']);
        $destino = $_SERVER['DOCUMENT_ROOT'] . "/LOLjaV1/assets/img_BD/" . $imagem_nome;

        // Move a foto do arq temporario para o arq final(imgs_BD)
        if (move_uploaded_file($_FILES['imagem']['tmp_name'], $destino)){
            $sql = "INSERT INTO itens(nome, classe, descricao, imagem, preco) VALUES ('$titulo', '$classe','$descricao', '$imagem_nome', '$preco')";
            if (mysqli_query($conn, $sql)) {
                $_SESSION['subido_sucesso'] = true;
                header("Location: ../itens.php");
                exit;
            } else {
                echo "Erro ao publicar: " . mysqli_error($conn);
            }
        } else {
            echo "Erro ao mover arquivo de imagem.";
        }
    } else {
        echo "Erro no upload: " . $_FILES['imagem']['error'];
    }
    $conn->close();
} else {
    header('Location: admin_dashboard.php');
    exit;
}