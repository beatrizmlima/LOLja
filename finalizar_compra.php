<?php
session_start();
include 'includes/navbar.php';

// Verifica se o carrinho existe
if (!isset($_SESSION['carrinho']) || empty($_SESSION['carrinho'])) {
    echo "<div class='text-center text-light mt-5'><h3>Seu carrinho está vazio.</h3></div>";
    include 'includes/footer.php';
    exit;
}

// Salvar no banco 


// Limpa o carrinho
unset($_SESSION['carrinho']);
?>

<!-- Pop-up visual -->
<div class="modal fade show d-block" tabindex="-1" style="background: rgba(0,0,0,0.8);">
  <div class="modal-dialog">
    <div class="modal-content" style="background-color: #0D1A1C; color: #A6F6D9; border: 2px solid #AE914B;">
      <div class="modal-header">
        <h5 class="modal-title">Compra Finalizada</h5>
      </div>
      <div class="modal-body text-center">
        <p class="fs-4">🎉 Compra realizada com sucesso!</p>
        <p class="text-muted">Você receberá a confirmação por e-mail.</p>
        <a href="itens.php" class="btn btn-login">Voltar para lolja</a>
      </div>
    </div>
  </div>
</div>

<?php include 'includes/footer.php'; ?>
