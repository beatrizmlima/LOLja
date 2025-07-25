<?php
session_start();

// Verifica se está logado e é admin
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['tipo'] !== 'Admin') {
    header('Location: ../login.php');
    exit;
}

include_once '../config/config.php';
$conn = dbConnect();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $titulo = trim($_POST['titulo']);

    // Busca os dados atuais do item
    $stmt = $conn->prepare("SELECT nome, descricao, imagem, preco FROM itens WHERE nome = ?");
    $stmt->bind_param("s", $titulo);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result && $result->num_rows > 0) {
        $item = $result->fetch_assoc();

        // Só atualiza se o campo foi preenchido, senão mantém o antigo
        $tituloNovo = trim($_POST['tituloNovo']);
        if ($tituloNovo === '') $tituloNovo = $item['nome'];

        $descricao = trim($_POST['descricao']);
        if ($descricao === '') $descricao = $item['descricao'];

        $preco = trim($_POST['preco']);
        if ($preco === '' || !is_numeric(str_replace(',', '.', $preco))) {
            $preco = $item['preco'];
        }

        // Atualiza imagem se enviada, senão mantém a antiga
        if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] == 0) {
            $imagem_nome = uniqid() . "_" . basename($_FILES['imagem']['name']);
            $destino = $_SERVER['DOCUMENT_ROOT'] . "/LOLjaV1/assets/img_BD/" . $imagem_nome;
            if (move_uploaded_file($_FILES['imagem']['tmp_name'], $destino)) {
                $imagem = $imagem_nome;
            } else {
                echo "<script>alert('Erro ao mover imagem!'); window.history.back();</script>";
                exit;
            }
        } else {
            $imagem = $item['imagem'];
        }

        // Atualiza o item
        $update = $conn->prepare("UPDATE itens SET nome = ?, descricao = ?, imagem = ?, preco = ? WHERE nome = ?");
        $update->bind_param("sssss", $tituloNovo, $descricao, $imagem, $preco, $titulo);
        $update->execute();

        $_SESSION['editado_sucesso'] = true;
        header("Location: ../itens.php");
        exit;
    } else {
        echo "<script>alert('Item não encontrado'); window.history.back();</script>";
    }
    $stmt->close();
    $conn->close();
} else {
    header('Location: admin_dashboard.php');
    exit();
}