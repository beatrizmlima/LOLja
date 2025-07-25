<?php
session_start();
require 'config/config.php';
include 'includes/navbar.php';
?>

<?php if (isset($_SESSION['erro'])): ?>
    <div class="alert alert-danger text-center"><?= htmlspecialchars($_SESSION['erro']) ?></div>
    <?php unset($_SESSION['erro']); ?>
<?php endif; ?>

<?php if (isset($_SESSION['cadastro_sucesso'])): ?>
    <div class="alert alert-success text-center">Cadastro realizado com sucesso! Faça seu login.</div>
    <?php unset($_SESSION['cadastro_sucesso']); ?>
<?php endif; ?>

<div class="container">
    <div class="form-container">
        <h2 class="text-center mb-4" style="color: #F5DF95;">Login</h2>

        <form method="POST" action="processes/login_process.php" class="mb-3" style="max-width: 400px;">
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" name="email" id="email" required class="form-control" />
            </div>
            <div class="mb-3">
                <label for="senha" class="form-label">Senha</label>
                <input type="password" name="senha" id="senha" required class="form-control" />
            </div>
            <button type="submit" class="btn btn-login">Entrar</button>
        </form>
        <p><a href="esqueci_senha.php" class="text-decoration-none text-light">Esqueci minha senha</a></p>
    </div>
</div>

<?php include 'includes/footer.php'; ?>