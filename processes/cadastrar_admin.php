<?php
session_start();
include_once '../config/config.php';
$conn = dbConnect();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Receber dados do formulário
    $nome_admin = trim($_POST['nome_admin'] ?? '');
    $email_admin = trim($_POST['email_admin'] ?? '');
    $senha_admin = $_POST['senha_admin'] ?? '';
    $confirmar_senha = $_POST['confirmar_senha'] ?? '';
    
    // Salvar dados do formulário para reexibir em caso de erro
    $_SESSION['form_data'] = [
        'nome_admin' => $nome_admin,
        'email_admin' => $email_admin
    ];
    
    // Validações
    if (!$nome_admin || !$email_admin || !$senha_admin || !$confirmar_senha) {
        $_SESSION['cadastro_erro'] = 'Preencha todos os campos obrigatórios.';
        header('Location: ../cadastro_admin.php');
        exit;
    }
    
    if (!filter_var($email_admin, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['cadastro_erro'] = 'Email inválido.';
        header('Location: ../cadastro_admin.php');
        exit;
    }
    
    if (strlen($senha_admin) < 6) {
        $_SESSION['cadastro_erro'] = 'A senha deve ter pelo menos 6 caracteres.';
        header('Location: ../cadastro_admin.php');
        exit;
    }
    
    if ($senha_admin !== $confirmar_senha) {
        $_SESSION['cadastro_erro'] = 'As senhas não coincidem.';
        header('Location: ../cadastro_admin.php');
        exit;
    }
    
    // Verificar se email já existe na tabela admins
    $stmt = $conn->prepare("SELECT id_admin FROM admins WHERE email_admin = ?");
    $stmt->bind_param("s", $email_admin);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        $_SESSION['cadastro_erro'] = 'Email já cadastrado como administrador.';
        header('Location: ../cadastro_admin.php');
        exit;
    }
    $stmt->close();
    
    // Verificar se email já existe na tabela clientes
    $stmt = $conn->prepare("SELECT id FROM clientes WHERE email = ?");
    $stmt->bind_param("s", $email_admin);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        $_SESSION['cadastro_erro'] = 'Email já cadastrado como cliente.';
        header('Location: ../cadastro_admin.php');
        exit;
    }
    $stmt->close();
    
    // Inserir admin no banco
    $senha_hash = password_hash($senha_admin, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("INSERT INTO admins (nome_admin, email_admin, senha_admin) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $nome_admin, $email_admin, $senha_hash);
    
    if ($stmt->execute()) {
        // Limpar dados do formulário
        unset($_SESSION['form_data']);
        $_SESSION['cadastro_sucesso'] = 'Administrador cadastrado com sucesso! Faça seu login.';
        header('Location: ../login.php');
        exit;
    } else {
        $_SESSION['cadastro_erro'] = 'Erro ao cadastrar administrador. Tente novamente.';
        header('Location: ../cadastro_admin.php');
        exit;
    }
    
    $stmt->close();
    $conn->close();
} else {
    header('Location: ../cadastro_admin.php');
    exit;
}