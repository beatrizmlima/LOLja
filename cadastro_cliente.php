<?php
session_start();
include 'includes/navbar.php';
?>

<div class="container">
    <div class="form-container">
        <h2 class="text-center mb-4" style="color: #F5DF95;">Cadastre-se</h2>
        <?php if (isset($_SESSION['cadastro_erro'])): ?>
            <div class="alert alert-danger text-center"><?= htmlspecialchars($_SESSION['cadastro_erro']) ?></div>
            <?php unset($_SESSION['cadastro_erro']); ?>
        <?php endif; ?>
        
        <!-- ALTERAR O ACTION AQUI -->
        <form id="formCadastro" method="POST" action="processes/cadastrar_cliente.php" style="max-width: 600px;">
            <div class="mb-3">
                <label for="nome" class="form-label">Nome *</label>
                <input type="text" id="nome" name="nome" class="form-control" required
                    value="<?= htmlspecialchars($_SESSION['form_data']['nome'] ?? '') ?>" />
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email *</label>
                <input type="email" id="email" name="email" class="form-control" required
                    value="<?= htmlspecialchars($_SESSION['form_data']['email'] ?? '') ?>" />
            </div>
            <div class="mb-3">
                <label for="classe" class="form-label">Classe de jogador *</label>
                <select id="classe" name="classe" class="form-select" required>
                    <option value="">Selecione sua classe</option>
                    <?php
                    $classes = ['Assassino', 'Mago', 'Lutador', 'Atirador', 'Suporte', 'Tanque'];
                    $classeSel = $_SESSION['form_data']['classe'] ?? '';
                    foreach ($classes as $c): ?>
                        <option value="<?= $c ?>" <?= $classeSel === $c ? 'selected' : '' ?>><?= $c ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="senha" class="form-label">Senha *</label>
                <input type="password" id="senha" name="senha" class="form-control" required minlength="6" />
                <div class="form-text text-muted">Mínimo 6 caracteres</div>
            </div>
            <div class="mb-3">
                <label for="confirmar_senha" class="form-label">Confirmar Senha *</label>
                <input type="password" id="confirmar_senha" name="confirmar_senha" class="form-control" required minlength="6" />
            </div>
            
            <h5 class="text-center mb-4" style="color: #F5DF95;">Informações geográficas</h5>
            <div class="mb-3">
                <label for="cep" class="form-label">CEP *</label>
                <input type="text" id="cep" name="cep" class="form-control" maxlength="9" required
                    value="<?= htmlspecialchars($_SESSION['form_data']['cep'] ?? '') ?>" />
            </div>
            <div class="mb-3">
                <label for="logradouro" class="form-label">Logradouro</label>
                <input type="text" id="logradouro" name="logradouro" class="form-control"
                    value="<?= htmlspecialchars($_SESSION['form_data']['logradouro'] ?? '') ?>" />
            </div>
            <div class="mb-3">
                <label for="numero" class="form-label">Número *</label>
                <input type="text" id="numero" name="numero" class="form-control" required
                    value="<?= htmlspecialchars($_SESSION['form_data']['numero'] ?? '') ?>" />
            </div>
            <div class="mb-3">
                <label for="bairro" class="form-label">Bairro</label>
                <input type="text" id="bairro" name="bairro" class="form-control"
                    value="<?= htmlspecialchars($_SESSION['form_data']['bairro'] ?? '') ?>" />
            </div>
            <div class="mb-3">
                <label for="cidade" class="form-label">Cidade</label>
                <input type="text" id="cidade" name="cidade" class="form-control"
                    value="<?= htmlspecialchars($_SESSION['form_data']['cidade'] ?? '') ?>" />
            </div>
            <div class="mb-3">
                <label for="uf" class="form-label">UF</label>
                <input type="text" id="uf" name="uf" class="form-control" maxlength="2"
                    value="<?= htmlspecialchars($_SESSION['form_data']['uf'] ?? '') ?>" />
            </div>
            <button type="submit" class="btn btn-login">Cadastrar Cliente</button>
        </form>
        
        <div class="text-center mt-3">
            <p><a href="login.php" class="text-decoration-none text-light">Já tem conta? Faça login</a></p>
            <p><a href="cadastro_admin.php" class="text-decoration-none text-light">Cadastrar como administrador</a></p>
        </div>
    </div>
</div>

<script>
    // Função para limpar campos de endereço
    function limpaCamposEndereco() {
        document.getElementById('logradouro').value = '';
        document.getElementById('bairro').value = '';
        document.getElementById('cidade').value = '';
        document.getElementById('uf').value = '';
    }

    // Função para preencher campos com dados da API ViaCEP
    function preencherEndereco(dados) {
        if (!('erro' in dados)) {
            document.getElementById('logradouro').value = dados.logradouro;
            document.getElementById('bairro').value = dados.bairro;
            document.getElementById('cidade').value = dados.localidade;
            document.getElementById('uf').value = dados.uf;
        } else {
            limpaCamposEndereco();
            alert('CEP não encontrado.');
        }
    }

    document.getElementById('cep').addEventListener('blur', function () {
        let cep = this.value.replace(/\D/g, '');
        if (cep.length === 8) {
            fetch(`https://viacep.com.br/ws/${cep}/json/`)
                .then(response => response.json())
                .then(data => preencherEndereco(data))
                .catch(() => {
                    limpaCamposEndereco();
                    alert('Erro ao buscar CEP.');
                });
        } else {
            limpaCamposEndereco();
        }
    });
</script>

<?php 
// Limpar dados do formulário da sessão
unset($_SESSION['form_data']);
include 'includes/footer.php'; 
?>