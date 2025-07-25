<?php
session_start();
require 'config/config.php';
include 'includes/navbar.php';

// Verificar se já está logado como admin (opcional - para proteger a página)
// Remova este bloco se quiser permitir cadastro livre de admin
/*
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['tipo'] !== 'Admin') {
    header('Location: login.php');
    exit;
}
*/
?>

<?php if (isset($_SESSION['cadastro_erro'])): ?>
    <div class="alert alert-danger text-center"><?= htmlspecialchars($_SESSION['cadastro_erro']) ?></div>
    <?php unset($_SESSION['cadastro_erro']); ?>
<?php endif; ?>

<div class="container">
    <div class="form-container">
        <h2 class="text-center mb-4" style="color: #F5DF95;">Cadastro de Administrador</h2>
        
        <form method="POST" action="processes/cadastrar_admin.php" class="mb-3" style="max-width: 500px;">
            <div class="mb-3">
                <label for="nome_admin" class="form-label">Nome Completo *</label>
                <input type="text" name="nome_admin" id="nome_admin" required class="form-control" 
                       value="<?= isset($_SESSION['form_data']['nome_admin']) ? htmlspecialchars($_SESSION['form_data']['nome_admin']) : '' ?>" />
            </div>
            
            <div class="mb-3">
                <label for="email_admin" class="form-label">Email *</label>
                <input type="email" name="email_admin" id="email_admin" required class="form-control" 
                       value="<?= isset($_SESSION['form_data']['email_admin']) ? htmlspecialchars($_SESSION['form_data']['email_admin']) : '' ?>" />
            </div>
            
            <div class="mb-3">
                <label for="senha_admin" class="form-label">Senha *</label>
                <input type="password" name="senha_admin" id="senha_admin" required class="form-control" 
                       minlength="6" />
                <div class="form-text text-muted">Mínimo 6 caracteres</div>
            </div>
            
            <div class="mb-3">
                <label for="confirmar_senha" class="form-label">Confirmar Senha *</label>
                <input type="password" name="confirmar_senha" id="confirmar_senha" required class="form-control" 
                       minlength="6" />
            </div>
           
            <button type="submit" class="btn btn-login">Cadastrar Administrador</button>
        </form>
        
        <div class="text-center">
            <p><a href="login.php" class="text-decoration-none text-light">Já tem conta? Faça login</a></p>
            <p><a href="cadastro.php" class="text-decoration-none text-light">Cadastrar como cliente</a></p>
        </div>
    </div>
</div>

<?php 
// Limpar dados do formulário da sessão
unset($_SESSION['form_data']);
include 'includes/footer.php'; 
?>