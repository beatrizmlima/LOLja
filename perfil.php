<?php
session_start();
include 'config/config.php';
include 'includes/navbar.php';

// Verifica se está logado
if (!isset($_SESSION['usuario'])) {
    header('Location: login.php');
    exit;
}

$id = $_SESSION['usuario']['id'];
$tipo = $_SESSION['usuario']['tipo'];
$conn = dbConnect();

$usuario = null;

// Buscar dados dependendo do tipo de usuário
if ($tipo === 'Admin') {
    // Buscar na tabela admins
    $stmt = $conn->prepare("SELECT nome_admin as nome, email_admin as email, 'Admin' as tipo FROM admins WHERE id_admin = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $usuario = $result->fetch_assoc();
    // if ($usuario) {
    //     $usuario['classe'] = null; // Admins não têm classe
    // }
} else {
    // Buscar na tabela clientes
    $stmt = $conn->prepare("SELECT Nome as nome, Email as email, CEP, Classe as classe, Logradouro, numero, Bairro, Cidade, UF, 'Cliente' as tipo FROM clientes WHERE Id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $usuario = $result->fetch_assoc();
}

if (!$usuario) {
    echo "<p class='text-light'>Usuário não encontrado.</p>";
    include 'includes/footer.php';
    exit;
}

// Mensagem de sucesso do login
if (isset($_SESSION['login_sucesso'])) {
    echo "<div class='alert alert-success text-center'>" . htmlspecialchars($_SESSION['login_sucesso']) . "</div>";
    unset($_SESSION['login_sucesso']);
}
?>

<div class="container mt-5">
  <h2 class="text-center mb-4" style="color:#F5DF95;">
    <?= $usuario['tipo'] === 'Admin' ? 'Perfil do Administrador' : 'Seu Perfil' ?>
  </h2>
  
  <div class="row justify-content-center">
    <div class="col-md-8">
      <div class="card" style="background-color:#132226; color:#A6F6D9; border: 2px solid #AE914B;">
        <div class="card-header text-center">
          <h4><?= htmlspecialchars($usuario['nome']) ?></h4>
          <div>
            <span class="badge me-2" style="background-color: #AE914B;">
              <?= htmlspecialchars($usuario['tipo']) ?>
            </span>
            <?php if ($usuario['classe']): ?>
              <span class="badge" style="background-color: #6c757d;">
                Classe: <?= htmlspecialchars($usuario['classe']) ?>
              </span>
            <?php endif; ?>
          </div>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-md-6">
              <h5 class="text-warning">Informações Pessoais</h5>
              <p><strong>Nome:</strong> <?= htmlspecialchars($usuario['nome']) ?></p>
              <p><strong>Email:</strong> <?= htmlspecialchars($usuario['email']) ?></p>
              <?php if ($usuario['classe']): ?>
                <p><strong>Classe do Jogo:</strong> <?= htmlspecialchars($usuario['classe']) ?></p>
              <?php endif; ?>
            </div>
            <div class="col-md-6">
              <?php if ($usuario['tipo'] === 'Cliente'): ?>
                <h5 class="text-warning">Endereço</h5>
                <?php if (!empty($usuario['CEP'])): ?>
                  <p><strong>CEP:</strong> <?= htmlspecialchars($usuario['CEP']) ?></p>
                <?php endif; ?>
                <?php if (!empty($usuario['Logradouro'])): ?>
                  <p><strong>Logradouro:</strong> <?= htmlspecialchars($usuario['Logradouro']) ?></p>
                <?php endif; ?>
                <?php if (!empty($usuario['numero'])): ?>
                  <p><strong>Número:</strong> <?= htmlspecialchars($usuario['numero']) ?></p>
                <?php endif; ?>
                <?php if (!empty($usuario['Bairro'])): ?>
                  <p><strong>Bairro:</strong> <?= htmlspecialchars($usuario['Bairro']) ?></p>
                <?php endif; ?>
                <?php if (!empty($usuario['Cidade'])): ?>
                  <p><strong>Cidade:</strong> <?= htmlspecialchars($usuario['Cidade']) ?></p>
                <?php endif; ?>
                <?php if (!empty($usuario['UF'])): ?>
                  <p><strong>UF:</strong> <?= htmlspecialchars($usuario['UF']) ?></p>
                <?php endif; ?>
              <?php else: ?>
                <h5 class="text-warning">Privilégios de Admin</h5>
                <p class="text-muted">
                  Como administrador, você tem acesso completo ao sistema de gerenciamento da loja.
                </p>
              <?php endif; ?>
            </div>
          </div>
        </div>
        <div class="card-footer text-center">
          <?php if ($usuario['tipo'] === 'Admin'): ?>
            <a href="admin/admin_dashboard.php" class="btn btn-login me-2">Painel Admin</a>
            <a href="cadastro_admin.php" class="btn btn-secondary">Cadastrar Outro Admin</a>
          <?php else: ?>
            <a href="carrinho.php" class="btn btn-login me-2">Ver Carrinho</a>
            <a href="itens.php" class="btn btn-secondary">Continuar Comprando</a>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include 'includes/footer.php'; ?>