<?php 
session_start();

// Verifica se está logado e é admin
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['tipo'] !== 'Admin') {
    header('Location: ../login.php');
    exit;
}
include '../includes/navbar.php'; 
?>

<div class="container mt-5" style="max-width: 700px;">
  <h2 class="mb-4">Publicar novo item</h2>
  <form method="POST" action="Adicionar_item_process.php" enctype="multipart/form-data">
    <div class="mb-3">
      <label for="titulo" class="form-label">Nome do Item</label>
      <input type="text" class="form-control" id="titulo" name="titulo" required>
    </div>

    <div class="mb-3">
      <label for="classe" class="form-label">Classe do item</label>
      <select class="form-select" id="classe" name="classe" required style="background-color: #0D1A1C; border: 1px solid #AE914B; color: #A6F6D9;">
        <option disabled selected>Escolha a Classe do item</option>
        <option>Assassino</option>
        <option>Mago</option>
        <option>Lutador</option>
        <option>Atirador</option>
        <option>Suporte</option>
        <option>Tanque</option>
      </select>
    </div>

    <div class="mb-3">
      <label for="descricao" class="form-label">Descrição</label>
      <textarea class="form-control" id="descricao" name="descricao" rows="4" required></textarea>
    </div>

    <div class="mb-3">
      <label for="imagem" class="form-label">Imagem do item</label>
      <input class="form-control" type="file" id="imagem" name="imagem" required>
    </div>

     <div class="mb-3">
      <label for="preco" class="form-label">Preço</label>
      <input class="form-control" type="text" id="preco" name="preco" required>
    </div>

    <div class="d-flex gap-2">
      <button type="submit" class="btn btn-success flex-fill">Confirmar item</button>
      <a href="admin_dashboard.php" class="btn btn-secondary flex-fill">Cancelar</a>
    </div>
  </form>
</div>

<?php include '../includes/footer.php'; ?>