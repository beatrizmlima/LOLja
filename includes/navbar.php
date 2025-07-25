<?php
//session_start();

function isLogged()
{
    return isset($_SESSION['usuario']);
}
function isAdmin()
{
    return isLogged() && $_SESSION['usuario']['tipo'] === 'Admin';
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>LoLJA - Loja de Itens Colecionáveis</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Cinzel&display=swap" rel="stylesheet" />
    <style>

        body {
            background-color: #0D1A1C;
            color: #A6F6D9;
            font-family: 'Cinzel', serif;
            overflow-x: hidden;
            margin: 10px;
        }

        * {
            cursor: url('../assets/img/cursorzinho.png'), auto !important;
        }

        .navbar-custom {
            background-color: #132226;
        }

        .navbar-custom .navbar-brand,
        .navbar-custom .nav-link,
        .navbar-custom .navbar-text,
        .navbar-custom button {
            color: #A6F6D9;
            font-weight: 600;
            transition: color 0.3s;
        }

        .navbar-custom .nav-link:hover {
            color: #F5DF95;
            text-decoration: underline;
        }

        .btn-login {
            border: 2px solid #A6F6D9;
            background: transparent;
            color: #A6F6D9;
            font-weight: 700;
        }

        .btn-login:hover {
            background-color: #A6F6D9;
            color: #132226;
        }

        .form-container {
            max-width: 500px;
            margin: 50px auto;
            padding: 30px;
            background-color: #132226;
            border: 2px solid #AE914B;
            border-radius: 8px;
            color: #A6F6D9;
            box-shadow: 0 0 15px rgba(174, 145, 75, 0.5);
        }

        .card {
            background-color: #132226;
            border: 2px solid #AE914B;
            color: #A6F6D9;
        }

        .card-title {
            color: #F5DF95;
        }

        .form-control {
            background-color: #0D1A1C;
            border: 1px solid #AE914B;
            color: #A6F6D9;
        }

        .form-control:focus {
            background-color: #0D1A1C;
            border-color: #F5DF95;
            color: #A6F6D9;
            box-shadow: 0 0 0 0.2rem rgba(245, 223, 149, 0.25);
        }

        .form-label {
            color: #A6F6D9;
        }

        .btn-primary {
            background-color: #AE914B;
            border-color: #AE914B;
        }

        .btn-primary:hover {
            background-color: #F5DF95;
            border-color: #F5DF95;
            color: #132226;
        }

        .btn-success {
            background-color: #3B7775;
            border-color: #3B7775;
        }

        .btn-success:hover {
            background-color: #14555B;
            border-color: #14555B;
        }

        h2, h3, h4, h5 {
            color: #F5DF95;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-custom">
        <div class="container">
            <a class="navbar-brand" href="<?= isAdmin() ? '/LOLjaV1/admin/admin_dashboard.php' : '/LOLjaV1/index.php' ?>" style="font-size: 1.8rem;">LoLJA</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu"
                aria-controls="navMenu" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon" style="color:#A6F6D9;"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-center" id="navMenu">
                <ul class="navbar-nav mb-2 mb-lg-0 gap-4">
                    <?php if (isAdmin()): ?>
                        <!-- Menu simplificado para Admin -->
                        <li class="nav-item"><a class="nav-link" href="/LOLjaV1/admin/admin_dashboard.php">Dashboard</a></li>
                        <li class="nav-item"><a class="nav-link" href="/LOLjaV1/itens.php">Ver Itens</a></li>
                    <?php else: ?>
                        <!-- Menu completo para clientes -->
                        <li class="nav-item"><a class="nav-link" href="/LOLjaV1/index.php">Home</a></li>
                        <li class="nav-item"><a class="nav-link" href="/LOLjaV1/itens.php?filtro=recomendados">Recomendados</a></li>
                        <li class="nav-item"><a class="nav-link" href="/LOLjaV1/itens.php">Todos os Itens</a></li>
                        <li class="nav-item"><a class="nav-link" href="/LOLjaV1/itens.php?filtro=arsenal">Arsenal</a></li>
                        <?php if (isLogged()): ?>
                            <li class="nav-item"><a class="nav-link" href="/LOLjaV1/carrinho.php">Carrinho</a></li>
                            <li class="nav-item"><a class="nav-link" href="/LOLjaV1/perfil.php">Perfil</a></li>
                        <?php endif; ?>
                    <?php endif; ?>
                </ul>
            </div>

            <div class="d-flex align-items-center gap-3">
                <?php if (!isLogged()): ?>
                    <a href="/LOLjaV1/cadastro_cliente.php" class="navbar-text">Cadastro</a>
                    <a href="/LOLjaV1/login.php"><button class="btn btn-login">Login</button></a>
                <?php else: ?>
                    <span class="navbar-text">Olá, <?= htmlspecialchars($_SESSION['usuario']['nome']) ?></span>
                    <a href="/LOLjaV1/admin/logout.php" class="btn btn-login">Logout</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <div class="container my-4">
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>