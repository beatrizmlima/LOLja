<?php
session_start();
require 'config/config.php';

$erro = '';
$sucesso = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $novaSenha = $_POST['nova_senha'] ?? '';
    $confirmaSenha = $_POST['confirma_senha'] ?? '';

    if (!$email || !$novaSenha || !$confirmaSenha) {
        $erro = 'Preencha todos os campos.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erro = 'Email inválido.';
    } elseif ($novaSenha !== $confirmaSenha) {
        $erro = 'As senhas não coincidem.';
    } else {
        // Verifica se usuário existe
        $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE email = ?");
        $stmt->execute([$email]);
        $usuario = $stmt->fetch();

        if (!$usuario) {
            $erro = 'Email não encontrado.';
        } else {
            $hashSenha = password_hash($novaSenha, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("UPDATE usuarios SET senha = ? WHERE id = ?");
            $atualizado = $stmt->execute([$hashSenha, $usuario['id']]);
            if ($atualizado) {
                $sucesso = true;
            } else {
                $erro = 'Erro ao alterar senha. Tente novamente.';
            }
        }
    }
}

include 'includes/navbar.php';
?>

<?php if ($sucesso): ?>
    <script>
        alert('Senha alterada com sucesso');
        window.location.href = 'login.php';
    </script>
<?php else: ?>

    <?php if ($erro): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($erro) ?></div>
    <?php endif; ?>
    <div class="container">
        <div class="form-container">
            <h2 class="text-center mb-4" style="color: #F5DF95;">Redefinir Senha</h2>

            <?php if (isset($erro))
                echo "<div class='alert alert-danger text-center'>$erro</div>"; ?>
            <form method="POST" action="esqueci_senha.php" style="max-width: 400px;">
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" name="email" id="email" required class="form-control"
                        value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" />
                </div>
                <div class="mb-3">
                    <label for="nova_senha" class="form-label">Nova Senha</label>
                    <input type="password" name="nova_senha" id="nova_senha" required class="form-control" />
                </div>
                <div class="mb-3">
                    <label for="confirma_senha" class="form-label">Confirmar Nova Senha</label>
                    <input type="password" name="confirma_senha" id="confirma_senha" required class="form-control" />
                </div>
                <button type="submit" class="btn btn-login">Redefinir Senha</button>
            </form>
        </div>
    </div>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>