<?php
session_start();
include_once '../config/config.php';
$conn = dbConnect();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Receber dados do formulário
    $nome = trim($_POST['nome'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $cep = trim($_POST['cep'] ?? '');
    $logradouro = trim($_POST['logradouro'] ?? '');
    $numero = trim($_POST['numero'] ?? '');
    $bairro = trim($_POST['bairro'] ?? '');
    $cidade = trim($_POST['cidade'] ?? '');
    $uf = trim($_POST['uf'] ?? '');
    $classe = $_POST['classe'] ?? '';
    $senha = $_POST['senha'] ?? '';
    $confirmar_senha = $_POST['confirmar_senha'] ?? '';
    
    // Salvar dados do formulário para reexibir em caso de erro
    $_SESSION['form_data'] = [
        'nome' => $nome,
        'email' => $email,
        'cep' => $cep,
        'logradouro' => $logradouro,
        'numero' => $numero,
        'bairro' => $bairro,
        'cidade' => $cidade,
        'uf' => $uf,
        'classe' => $classe
    ];
    
    // Validações
    if (!$nome || !$email || !$senha || !$confirmar_senha || !$numero || !$classe || !$cep) {
        $_SESSION['cadastro_erro'] = 'Preencha todos os campos obrigatórios.';
        header('Location: ../cadastro.php');
        exit;
    }
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['cadastro_erro'] = 'Email inválido.';
        header('Location: ../cadastro.php');
        exit;
    }
    
    if (strlen($senha) < 6) {
        $_SESSION['cadastro_erro'] = 'A senha deve ter pelo menos 6 caracteres.';
        header('Location: ../cadastro.php');
        exit;
    }
    
    if ($senha !== $confirmar_senha) {
        $_SESSION['cadastro_erro'] = 'As senhas não coincidem.';
        header('Location: ../cadastro.php');
        exit;
    }
    
    // Verificar se a classe é válida
    $classes_validas = ['Assassino', 'Mago', 'Lutador', 'Atirador', 'Suporte', 'Tanque'];
    if (!in_array($classe, $classes_validas)) {
        $_SESSION['cadastro_erro'] = 'Classe inválida selecionada.';
        header('Location: ../cadastro.php');
        exit;
    }
    
    // Verificar se email já existe na tabela clientes
    $stmt = $conn->prepare("SELECT id FROM clientes WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        $_SESSION['cadastro_erro'] = 'Email já cadastrado como cliente.';
        header('Location: ../cadastro.php');
        exit;
    }
    $stmt->close();
    
    // Verificar se email já existe na tabela admins
    $stmt = $conn->prepare("SELECT id_admin FROM admins WHERE email_admin = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        $_SESSION['cadastro_erro'] = 'Email já cadastrado como administrador.';
        header('Location: ../cadastro.php');
        exit;
    }
    $stmt->close();
    
    // Inserir cliente no banco
    $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("INSERT INTO clientes (nome, email, cep, logradouro, numero, bairro, cidade, uf, classe, senha) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssssss", $nome, $email, $cep, $logradouro, $numero, $bairro, $cidade, $uf, $classe, $senha_hash);
    
    if ($stmt->execute()) {
        // Limpar dados do formulário
        unset($_SESSION['form_data']);
        $_SESSION['cadastro_sucesso'] = 'Cliente cadastrado com sucesso! Faça seu login.';
        header('Location: ../login.php');
        exit;
    } else {
        $_SESSION['cadastro_erro'] = 'Erro ao cadastrar cliente. Tente novamente.';
        header('Location: ../cadastro.php');
        exit;
    }
    
    $stmt->close();
    $conn->close();
} else {
    header('Location: ../cadastro.php');
    exit;
}