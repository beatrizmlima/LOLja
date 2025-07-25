<?php
session_start();
require '../config/config.php';

$conn = dbConnect();

$email = trim($_POST['email'] ?? '');
$senha = $_POST['senha'] ?? '';

if (!$email || !$senha) {
    $_SESSION['erro'] = 'Preencha todos os campos.';
    header('Location: ../login.php');
    exit;
}

$usuario_encontrado = false;
$dados_usuario = null;

// Primeiro, tentar encontrar na tabela admins
$stmt = $conn->prepare("SELECT id_admin as id, nome_admin as nome, 'Admin' as tipo, senha_admin as senha FROM admins WHERE email_admin = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows === 1) {
    $stmt->bind_result($id, $nome, $tipo, $senha_hash);
    $stmt->fetch();
    $dados_usuario = [
        'id' => $id,
        'nome' => $nome,
        'tipo' => $tipo,
        'senha_hash' => $senha_hash,
    ];
    $usuario_encontrado = true;
}
$stmt->close();

// Se não encontrou admin, tentar na tabela clientes
if (!$usuario_encontrado) {
    $stmt = $conn->prepare("SELECT id as id, nome as nome, classe as classe, 'Cliente' as tipo, senha as senha FROM clientes WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    
    if ($stmt->num_rows === 1) {
        $stmt->bind_result($id, $nome, $classe, $tipo, $senha_hash);
        $stmt->fetch();
        $dados_usuario = [
            'id' => $id,
            'nome' => $nome,
            'tipo' => $tipo,
            'senha_hash' => $senha_hash,
            'classe' => $classe
        ];
        $usuario_encontrado = true;
    }
    $stmt->close();
}

if ($usuario_encontrado && password_verify($senha, $dados_usuario['senha_hash'])) {
    // Login bem-sucedido
    $_SESSION['logado'] = true;
    $_SESSION['usuario'] = [
        'id' => $dados_usuario['id'],
        'nome' => $dados_usuario['nome'],
        'tipo' => $dados_usuario['tipo'],
        'classe' => $dados_usuario['classe']
    ];

    // Redirecionamento baseado no tipo de usuário
    if ($dados_usuario['tipo'] === 'Admin') {
        $_SESSION['login_sucesso'] = 'Bem-vindo, Administrador(a) ' . $dados_usuario['nome'] . '!';
        header('Location: ../admin/admin_dashboard.php');
    } else {
        $_SESSION['login_sucesso'] = 'Login realizado com sucesso, ' . $dados_usuario['nome'] . '!';
        header('Location: ../perfil.php');
    }
    exit;
} else {
    // Login falhou
    $_SESSION['erro'] = 'Email ou senha incorretos.';
    header('Location: ../login.php');
    exit;
}

$conn->close();