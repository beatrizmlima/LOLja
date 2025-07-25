<?php
session_start();
include '../includes/navbar.php';

// Verifica se está logado e é admin
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['tipo'] !== 'Admin') {
    header('Location: ../login.php');
    exit;
}
?>

<div class="container mt-5">
    <h2 class="text-center mb-4" style="color:#F5DF95;">Painel do Administrador</h2>
    
    <?php if (isset($_SESSION['login_sucesso'])): ?>
        <div class="alert alert-success text-center">
            <?= htmlspecialchars($_SESSION['login_sucesso']) ?>
        </div>
        <?php unset($_SESSION['login_sucesso']); ?>
    <?php endif; ?>

    <div class="row g-4">
        <div class="col-md-4">
            <div class="card h-100" style="background-color: #132226; border: 2px solid #AE914B;">
                <div class="card-body text-center">
                    <h5 class="card-title text-light">Adicionar Item</h5>
                    <p class="text-muted">Cadastre novos itens na loja</p>
                    <a href="Adicionar_item_form.php" class="btn btn-login">Acessar</a>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card h-100" style="background-color: #132226; border: 2px solid #AE914B;">
                <div class="card-body text-center">
                    <h5 class="card-title text-light">Atualizar Item</h5>
                    <p class="text-muted">Edite itens existentes</p>
                    <a href="Atualizar_item_form.php" class="btn btn-login">Acessar</a>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card h-100" style="background-color: #132226; border: 2px solid #AE914B;">
                <div class="card-body text-center">
                    <h5 class="card-title text-light">Excluir Item</h5>
                    <p class="text-muted">Remova itens da loja</p>
                    <a href="Excluir_item_form.php" class="btn btn-login">Acessar</a>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-12">
            <div class="card" style="background-color: #132226; border: 2px solid #AE914B;">
                <div class="card-body">
                    <h5 class="card-title text-light">Estatísticas</h5>
                    <div class="row text-center">
                        <div class="col-md-3">
                            <h3 class="text-warning">--</h3>
                            <p class="text-muted">Total de Itens</p>
                        </div>
                        <div class="col-md-3">
                            <h3 class="text-warning">--</h3>
                            <p class="text-muted">Usuários Cadastrados</p>
                        </div>
                        <div class="col-md-3">
                            <h3 class="text-warning">--</h3>
                            <p class="text-muted">Vendas do Mês</p>
                        </div>
                        <div class="col-md-3">
                            <h3 class="text-warning">--</h3>
                            <p class="text-muted">Faturamento</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>