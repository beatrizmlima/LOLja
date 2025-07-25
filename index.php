<?php
include 'includes/navbar.php';

if (isset($_SESSION['login_sucesso'])): ?>
    <div class="alert alert-success text-center">
        <?= htmlspecialchars($_SESSION['login_sucesso']) ?>
    </div>
    <?php unset($_SESSION['login_sucesso']);
endif;

$produtosDestaque = [
    ['nome' => 'Espada do Assassino', 'imagem' => 'assets/img/espadaAzakana.jpg', 'descricao' => 'Espada lendária para assassinos.', 'preco' => 150.00],
    ['nome' => 'Cajado do Mago', 'imagem' => 'assets/img/cajadoDivino.jpeg', 'descricao' => 'Cajado com poderes arcanos.', 'preco' => 200.00],
    ['nome' => 'Escudo do Tanque', 'imagem' => 'assets/img/escudoIndestrutivel.jpg', 'descricao' => 'Escudo para proteção máxima.', 'preco' => 180.00],
];

$tipos = [
    ['nome' => 'Assassino', 'img' => 'assets/img/kayn.jpg'],
    ['nome' => 'Mago', 'img' => 'assets/img/veigar.jpg'],
    ['nome' => 'Lutador', 'img' => 'assets/img/riven.jpg'],
    ['nome' => 'Atirador', 'img' => 'assets/img/twitch.jpg'],
    ['nome' => 'Suporte', 'img' => 'assets/img/blitzcrank.jpg'],
    ['nome' => 'Tanque', 'img' => 'assets/img/rammus.jpg'],
];
?>

<h1 class="text-center mb-4" style="font-weight: 700; color: #AE914B;">Bem-vindo à LoLJA - Loja de Itens Colecionáveis</h1>

<!-- Carrossel de produtos em destaque -->
<div id="carouselDestaque" class="carousel slide mb-5" data-bs-ride="carousel" style="max-width: 700px; margin: 0 auto;">
  <div class="carousel-inner rounded-3" style="border: 3px solid #AE914B; background-color: #132226;">
    <?php foreach ($produtosDestaque as $index => $produto): ?>
      <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
        <img src="<?= htmlspecialchars($produto['imagem']) ?>" class="d-block w-100" alt="<?= htmlspecialchars($produto['nome']) ?>" style="height: 350px; object-fit: contain; padding: 20px;" />
        <div class="carousel-caption d-none d-md-block" style="background: rgba(0,0,0,0.5); border-radius: 8px;">
          <h5><?= htmlspecialchars($produto['nome']) ?></h5>
          <p><?= htmlspecialchars($produto['descricao']) ?></p>
          <p style="font-weight: bold;">R$ <?= number_format($produto['preco'], 2, ',', '.') ?></p>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
  <button class="carousel-control-prev" type="button" data-bs-target="#carouselDestaque" data-bs-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Anterior</span>
  </button>
  <button class="carousel-control-next" type="button" data-bs-target="#carouselDestaque" data-bs-slide="next">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Próximo</span>
  </button>
</div>

<!-- Cards dos tipos de jogadores -->
<div class="row justify-content-center g-4">
  <?php foreach ($tipos as $tipo): ?>
    <div class="col-6 col-md-4 col-lg-2 text-center">
      <a href="itens.php?classe=<?= urlencode($tipo['nome']) ?>" style="text-decoration: none; color: #A6F6D9;">
        <div class="card" style="background-color: #14555B; border: 2px solid #AE914B;">
          <img src="<?= htmlspecialchars($tipo['img']) ?>" class="card-img-top" alt="<?= htmlspecialchars($tipo['nome']) ?>" style="padding: 20px; height: 140px; object-fit: contain;" />
          <div class="card-body p-2">
            <h5 class="card-title" style="font-weight: 700;"><?= htmlspecialchars($tipo['nome']) ?></h5>
          </div>
        </div>
      </a>
    </div>
  <?php endforeach; ?>
</div>

<?php include 'includes/footer.php'; ?>
