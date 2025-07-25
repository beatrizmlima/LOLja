<?php
session_start();
include 'config/config.php';

// Verifica se está logado e não é admin
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['tipo'] === 'Admin') {
    header('Location: login.php');
    exit;
}

// Inicializa carrinho se ainda não existir
if (!isset($_SESSION['carrinho'])) {
    $_SESSION['carrinho'] = [];
}

// Limpa itens corrompidos do carrinho
if (!empty($_SESSION['carrinho'])) {
    foreach ($_SESSION['carrinho'] as $key => $item) {
        // Remove itens com dados inválidos
        if (!isset($item['id']) || !isset($item['nome']) || !isset($item['preco']) || !isset($item['quantidade'])) {
            unset($_SESSION['carrinho'][$key]);
        }
    }
}

$conn = dbConnect();

// Adiciona item ao carrinho via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['produto_id'], $_POST['quantidade'])) {
    $id = (int) $_POST['produto_id'];
    $quantidade = (int) $_POST['quantidade'];

    if ($id > 0 && $quantidade > 0) {
        // Busca o produto no banco
        $stmt = $conn->prepare("SELECT id, nome, preco, imagem FROM itens WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $produto = $result->fetch_assoc();
        
        if ($produto) {
            // Se item já estiver no carrinho, soma quantidade
            if (isset($_SESSION['carrinho'][$id])) {
                $_SESSION['carrinho'][$id]['quantidade'] += $quantidade;
            } else {
                $_SESSION['carrinho'][$id] = [
                    'id' => $produto['id'],
                    'nome' => $produto['nome'],
                    'preco' => (float)$produto['preco'],
                    'imagem' => $produto['imagem'],
                    'quantidade' => $quantidade
                ];
            }
            // Define mensagem de sucesso na sessão e redireciona
            $_SESSION['carrinho_msg'] = 'success';
            header('Location: carrinho.php');
            exit;
        } else {
            $_SESSION['carrinho_msg'] = 'error_product';
        }
        $stmt->close();
    } else {
        $_SESSION['carrinho_msg'] = 'error_data';
    }
}

// Processamento de mensagens da sessão
$mensagem = '';
if (isset($_SESSION['carrinho_msg'])) {
    switch ($_SESSION['carrinho_msg']) {
        case 'success':
            $mensagem = '<div class="alert alert-success text-center">Item adicionado ao carrinho com sucesso!</div>';
            break;
        case 'updated':
            $mensagem = '<div class="alert alert-success text-center">Quantidade atualizada com sucesso!</div>';
            break;
        case 'removed':
            $mensagem = '<div class="alert alert-success text-center">Item removido do carrinho!</div>';
            break;
        case 'error_product':
            $mensagem = '<div class="alert alert-danger text-center">Produto não encontrado!</div>';
            break;
        case 'error_data':
            $mensagem = '<div class="alert alert-danger text-center">Dados inválidos!</div>';
            break;
        case 'error_notfound':
            $mensagem = '<div class="alert alert-danger text-center">Item não encontrado no carrinho!</div>';
            break;
    }
    unset($_SESSION['carrinho_msg']);
}

include 'includes/navbar.php';

// Calcula subtotal
$subtotal = 0;
if (!empty($_SESSION['carrinho'])) {
    foreach ($_SESSION['carrinho'] as $item) {
        $preco = (float)($item['preco'] ?? 0);
        $quantidade = (int)($item['quantidade'] ?? 0);
        $subtotal += $preco * $quantidade;
    }
}
?>

<?= $mensagem ?>

<h2 class="mb-4 text-center" style="color:#F5DF95;">Seu Carrinho</h2>

<?php if (empty($_SESSION['carrinho'])): ?>
  <p class="text-center text-light">Seu carrinho está vazio.</p>
  <div class="text-center mt-4">
    <a href="itens.php" class="btn btn-login">Continuar Comprando</a>
  </div>
<?php else: ?>
  <div class="table-responsive">
    <table class="table table-dark table-striped text-center align-middle">
      <thead>
        <tr>
          <th>Imagem</th>
          <th>Produto</th>
          <th>Quantidade</th>
          <th>Preço</th>
          <th>Total</th>
          <th>Ações</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($_SESSION['carrinho'] as $key => $item): ?>
          <?php 
          // Verifica se o item tem dados válidos
          if (!isset($item['id']) || !isset($item['nome']) || !isset($item['preco']) || !isset($item['quantidade'])) {
              continue; // Pula itens corrompidos
          }
          ?>
          <tr>
            <td>
              <?php if (!empty($item['imagem'])): ?>
                <img src="assets/img_BD/<?= htmlspecialchars($item['imagem'] ?? '') ?>" 
                     style="height: 60px; object-fit: contain;" 
                     alt="<?= htmlspecialchars($item['nome'] ?? 'Produto') ?>"
                     onerror="this.src='assets/img_BD/default.png'">
              <?php else: ?>
                <div style="height: 60px; width: 60px; background: #333; display: flex; align-items: center; justify-content: center; margin: 0 auto;">
                  <span style="font-size: 12px; color: #999;">Sem imagem</span>
                </div>
              <?php endif; ?>
            </td>
            <td><?= htmlspecialchars($item['nome'] ?? 'Produto sem nome') ?></td>
            <td>
              <form method="POST" action="atualizar_carrinho.php" class="d-inline">
                <input type="hidden" name="produto_id" value="<?= (int)$item['id'] ?>">
                <input type="number" name="quantidade" value="<?= (int)$item['quantidade'] ?>" 
                       min="1" max="99" style="width: 60px;" class="form-control d-inline">
                <button type="submit" class="btn btn-sm btn-outline-warning">Atualizar</button>
              </form>
            </td>
            <td>R$ <?= number_format((float)($item['preco'] ?? 0), 2, ',', '.') ?></td>
            <td>R$ <?= number_format((float)($item['preco'] ?? 0) * (int)($item['quantidade'] ?? 0), 2, ',', '.') ?></td>
            <td>
              <a href="remover_carrinho.php?id=<?= (int)$item['id'] ?>" 
                 class="btn btn-sm btn-outline-danger"
                 onclick="return confirm('Remover este item do carrinho?')">Remover</a>
            </td>
          </tr>
        <?php endforeach; ?>
        <tr>
          <td colspan="4" class="text-end fw-bold">Subtotal:</td>
          <td class="fw-bold text-warning">R$ <?= number_format($subtotal, 2, ',', '.') ?></td>
          <td></td>
        </tr>
      </tbody>
    </table>
  </div>

  <div class="text-center mt-4">
    <a href="itens.php" class="btn btn-secondary me-2">Continuar Comprando</a>
    <button class="btn btn-login" data-bs-toggle="modal" data-bs-target="#finalizarModal">Finalizar Compra</button>
  </div>
<?php endif; ?>

<!-- Modal de Finalização -->
<div class="modal fade" id="finalizarModal" tabindex="-1" aria-labelledby="finalizarModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content" style="background-color: #132226; color: #A6F6D9; border: 2px solid #AE914B;">
      <div class="modal-header">
        <h5 class="modal-title" id="finalizarModalLabel">Finalizar Compra</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fechar"></button>
      </div>
      <div class="modal-body">
        <ul class="list-group mb-3">
          <?php foreach ($_SESSION['carrinho'] as $item): ?>
            <li class="list-group-item d-flex justify-content-between align-items-center bg-dark text-light">
              <?= (int)($item['quantidade'] ?? 0) ?>x <?= htmlspecialchars($item['nome'] ?? 'Produto') ?>
              <span>R$ <?= number_format((float)($item['preco'] ?? 0) * (int)($item['quantidade'] ?? 0), 2, ',', '.') ?></span>
            </li>
          <?php endforeach; ?>
          <li class="list-group-item d-flex justify-content-between bg-dark text-warning fw-bold">
            Total: <span>R$ <?= number_format($subtotal, 2, ',', '.') ?></span>
          </li>
        </ul>

        <form method="POST" action="finalizar_compra.php" id="formPagamento">
          <div class="mb-3">
            <label for="pagamento" class="form-label">Forma de Pagamento</label>
            <select name="pagamento" id="pagamento" class="form-select" required onchange="mostrarCamposPagamento()">
              <option value="">Selecione...</option>
              <option value="cartao">Cartão</option>
              <option value="boleto">Boleto</option>
              <option value="pix">Pix</option>
            </select>
          </div>

          <div id="campoCartao" style="display: none;" class="mb-3">
            <label for="numero_cartao" class="form-label">Número do Cartão</label>
            <input type="text" name="numero_cartao" id="numero_cartao" class="form-control" />
          </div>

          <div id="campoBoleto" style="display: none;" class="mb-3">
            <p>Protocolo: <strong><?= rand(100000000, 999999999) ?></strong></p>
            <p>Vencimento: <strong><?= date('d/m/Y', strtotime('+30 days')) ?></strong></p>
          </div>

          <div id="campoPix" style="display: none;" class="mb-3">
            <p>Escaneie o QR Code:</p>
            <img src="assets/qrcode_pix_exemplo.png" alt="QR Code Pix" style="width: 200px;" />
          </div>

          <button type="submit" class="btn btn-login w-100 mt-3">Finalizar Compra</button>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
function mostrarCamposPagamento() {
  const tipo = document.getElementById('pagamento').value;
  document.getElementById('campoCartao').style.display = tipo === 'cartao' ? 'block' : 'none';
  document.getElementById('campoBoleto').style.display = tipo === 'boleto' ? 'block' : 'none';
  document.getElementById('campoPix').style.display = tipo === 'pix' ? 'block' : 'none';
}
</script>

<?php include 'includes/footer.php'; ?>