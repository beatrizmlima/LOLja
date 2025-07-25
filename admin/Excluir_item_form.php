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
  <div class="alert alert-warning" style="background-color: #574625; border-color: #AE914B; color: #F5DF95;">
    <strong>⚠️ Atenção!</strong> Esta ação não pode ser desfeita. O item será removido permanentemente.
  </div>
  
  <h2 class="mb-4">Deletar Item</h2>
  <form method="POST" action="Excluir_item_process.php" onsubmit="return confirm('Tem certeza que deseja deletar este item? Esta ação não pode ser desfeita.')">
    <div class="mb-4">
      <label for="titulo" class="form-label">Nome do Item a ser deletado</label>
      <input type="text" class="form-control" id="titulo" name="titulo" required>
      <div class="form-text" style="color: #A6F6D9;">Digite o nome exato do item que deseja deletar</div>
    </div>
    
    <div class="d-flex gap-2">
      <button type="submit" class="btn flex-fill" style="background-color: #dc3545; border-color: #dc3545; color: white;">
        🗑️ Deletar Item
      </button>
      <a href="admin_dashboard.php" class="btn btn-secondary flex-fill">Cancelar</a>
    </div>
  </form>
</div>

<?php include '../includes/footer.php'; ?>