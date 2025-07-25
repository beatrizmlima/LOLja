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
  <h2 class="mb-4">Editar Item</h2>
  <form method="POST" action="Atualizar_item_process.php" enctype="multipart/form-data">
    <div class="mb-3">
      <label for="titulo" class="form-label">Nome do Item a mudar</label>
      <input type="text" class="form-control" id="titulo" name="titulo" required>
      <div class="form-text" style="color: #A6F6D9;">Digite o nome exato do item que deseja alterar</div>
    </div>
    
    <div class="mb-3">
      <label for="tituloNovo" class="form-label">Novo nome</label>
      <input type="text" class="form-control" id="tituloNovo" name="tituloNovo">
      <div class="form-text" style="color: #A6F6D9;">Deixe em branco para manter o nome atual</div>
    </div>

    <div class="mb-3">
      <label for="descricao" class="form-label">Nova descrição</label>
      <textarea class="form-control" id="descricao" name="descricao" rows="4"></textarea>
      <div class="form-text" style="color: #A6F6D9;">Deixe em branco para manter a descrição atual</div>
    </div>

    <div class="mb-3">
      <label for="imagem" class="form-label">Nova imagem</label>
      <input class="form-control" type="file" id="imagem" name="imagem">
      <div class="form-text" style="color: #A6F6D9;">Deixe em branco para manter a imagem atual</div>
    </div>

    <div class="mb-3">
      <label for="preco" class="form-label">Novo preço</label>
      <input class="form-control" type="text" id="preco" name="preco">
      <div class="form-text" style="color: #A6F6D9;">Deixe em branco para manter o preço atual</div>
    </div>

    <div class="d-flex gap-2">
      <button type="submit" class="btn btn-primary flex-fill">Atualizar Item</button>
      <a href="admin_dashboard.php" class="btn btn-secondary flex-fill">Cancelar</a>
    </div>
  </form>
</div>

<?php include '../includes/footer.php'; ?>