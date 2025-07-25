<?php
session_start();
include 'includes/navbar.php';
include 'config/config.php'; 

$db = dbConnect();

// Obtendo os filtros da URL
$filtroNome = isset($_GET['nome']) ? trim($_GET['nome']) : '';
$filtroOrdenar = isset($_GET['ordenar']) ? $_GET['ordenar'] : '';
$filtroClasse = isset($_GET['classe']) ? $_GET['classe'] : '';

// Montando a consulta SQL dinamicamente
$sql = "SELECT id, nome, preco, descricao, imagem, classe FROM itens WHERE 1";
$params = [];
$types = "";

// Filtro por nome
if ($filtroNome) {
    $sql .= " AND nome LIKE ?";
    $params[] = "%$filtroNome%";
    $types .= "s";
}

// Filtro por classe
if ($filtroClasse) {
    $sql .= " AND classe = ?";
    $params[] = $filtroClasse;
    $types .= "s";
}

// Ordenação
$ordenacoesPermitidas = ['nome', 'preco', 'id'];
if ($filtroOrdenar && in_array($filtroOrdenar, $ordenacoesPermitidas)) {
    $sql .= " ORDER BY $filtroOrdenar";
} else {
    $sql .= " ORDER BY id DESC";
}

// Preparando e executando a consulta
$stmt = $db->prepare($sql);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();

$itens = [];
while ($row = $result->fetch_assoc()) {
    $itens[] = $row;
}

// Verifica se é admin
if (isset($_SESSION['subido_sucesso']) && $_SESSION['subido_sucesso']) {
    echo '<div class="alert alert-success text-center">Item adicionado com sucesso!</div>';
    unset($_SESSION['subido_sucesso']);
}

if (isset($_SESSION['editado_sucesso']) && $_SESSION['editado_sucesso']) {
    echo '<div class="alert alert-success text-center">Item atualizado com sucesso!</div>';
    unset($_SESSION['editado_sucesso']);
}

if (isset($_SESSION['deletado_sucesso']) && $_SESSION['deletado_sucesso']) {
    echo '<div class="alert alert-success text-center">Item deletado com sucesso!</div>';
    unset($_SESSION['deletado_sucesso']);
}
?>

<h2 class="mb-4 text-center" style="color:#F5DF95;">
  <?php 
  // Título diferente para admin
  if (isset($_SESSION['usuario']) && $_SESSION['usuario']['tipo'] === 'Admin') {
    echo 'Gerenciar Itens';
  } else {
    echo 'Todos os Itens';
  }
  ?>
</h2>

<!-- Filtros -->
<form method="GET" class="row g-3 mb-4">
  <div class="col-md-4">
    <input type="text" name="nome" class="form-control" placeholder="Buscar por nome..." value="<?= htmlspecialchars($filtroNome) ?>">
  </div>
  <div class="col-md-3">
    <select name="ordenar" class="form-select" style="background-color: #0D1A1C; border: 1px solid #AE914B; color: #A6F6D9;">
      <option value="">Ordenar por...</option>
      <option value="nome" <?= $filtroOrdenar === 'nome' ? 'selected' : '' ?>>Nome</option>
      <option value="preco" <?= $filtroOrdenar === 'preco' ? 'selected' : '' ?>>Preço</option>
    </select>
  </div>
  <div class="col-md-3">
    <select name="classe" class="form-select" style="background-color: #0D1A1C; border: 1px solid #AE914B; color: #A6F6D9;">
      <option value="">Filtrar por classe</option>
      <?php
      $classes = ['Assassino', 'Mago', 'Lutador', 'Atirador', 'Suporte', 'Tanque'];
      foreach ($classes as $classe) {
        $selected = ($filtroClasse === $classe) ? 'selected' : '';
        echo "<option value='$classe' $selected>$classe</option>";
      }
      ?>
    </select>
  </div>
  <div class="col-md-2">
    <button class="btn btn-login w-100">Filtrar</button>
  </div>
</form>

<!-- Cards dos produtos -->
<div class="row g-4">
  <?php foreach ($itens as $item): ?>
    <div class="col-md-4">
      <div class="card h-100" style="background-color: #132226; border: 2px solid #AE914B;">
        <img src="assets/img_BD/<?= htmlspecialchars($item['imagem']) ?>" class="card-img-top" alt="<?= htmlspecialchars($item['nome']) ?>" style="height: 200px; object-fit: contain; padding: 10px;" />
        <div class="card-body">
          <h5 class="card-title text-light"><?= htmlspecialchars($item['nome']) ?></h5>
          <p class="text-muted"><?= htmlspecialchars($item['classe']) ?></p>
          <p class="text-warning fw-bold">R$ <?= number_format($item['preco'], 2, ',', '.') ?></p>
          <button class="btn btn-login" data-bs-toggle="modal" data-bs-target="#itemModal<?= $item['id'] ?>">Ver detalhes</button>
        </div>
      </div>
    </div>

    <!-- Modal (pop-up) para o item -->
    <div class="modal fade" id="itemModal<?= $item['id'] ?>" tabindex="-1" aria-labelledby="itemModalLabel<?= $item['id'] ?>" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content" style="background-color: #0D1A1C; color: #A6F6D9; border: 2px solid #AE914B;">
          <div class="modal-header">
            <h5 class="modal-title" id="itemModalLabel<?= $item['id'] ?>"><?= htmlspecialchars($item['nome']) ?></h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fechar"></button>
          </div>
          <div class="modal-body">
            <p><?= htmlspecialchars($item['descricao']) ?></p>
            
            <?php if (isset($_SESSION['usuario']) && $_SESSION['usuario']['tipo'] === 'Admin'): ?>
              <div class="d-flex gap-2 mb-3">
                <a href="itens.php" class="btn btn-secondary flex-fill">← Voltar</a>
              </div>
            <?php else: ?>
              <!-- Formulário para adicionar ao carrinho (apenas para clientes) -->
              <?php if (isset($_SESSION['usuario']) && $_SESSION['usuario']['tipo'] !== 'Admin'): ?>
                <form method="POST" action="carrinho.php">
                  <input type="hidden" name="produto_id" value="<?= $item['id'] ?>">
                  <label for="quantidade<?= $item['id'] ?>">Quantidade:</label>
                  <input type="number" id="quantidade<?= $item['id'] ?>" name="quantidade" min="1" value="1" class="form-control mb-3" style="background-color: #0D1A1C; border: 1px solid #AE914B; color: #A6F6D9;" />
                  <button type="submit" class="btn btn-login w-100">Adicionar ao Carrinho</button>
                </form>
              <?php else: ?>
                <p class="text-center text-muted">Faça login para adicionar itens ao carrinho</p>
                <a href="login.php" class="btn btn-login w-100">Fazer Login</a>
              <?php endif; ?>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>

  <?php endforeach; ?>
</div>

<?php include 'includes/footer.php'; ?>