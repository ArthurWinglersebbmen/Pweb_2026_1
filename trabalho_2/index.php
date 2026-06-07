<?php
require_once 'header.php';
require_once 'db.class.php'; // <-- 1. Incluímos a classe de banco de dados

// Lógica atualizada para validar o login no Banco de Dados com PDO
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $login = trim($_POST['login'] ?? '');
    $senha = trim($_POST['senha'] ?? '');
    
    // 2. Instancia a conexão com o banco
    $database = new Database();
    $db = $database->getConnection();
    
    // 3. Prepara a consulta para buscar o usuário na tabela
    $stmt = $db->prepare("SELECT id, nome FROM usuario WHERE login = :login AND senha = :senha");
    $stmt->bindParam(':login', $login);
    $stmt->bindParam(':senha', $senha);
    $stmt->execute();
    
    // 4. Se a consulta retornar 1 linha, o login está correto
    if ($stmt->rowCount() > 0) {
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Salva as informações na sessão
        $_SESSION['logado'] = true;
        $_SESSION['nome_usuario'] = $usuario['nome']; // Podemos guardar o nome para usar depois
        
        header("Location: index.php"); // Recarrega a página para abrir o painel
        exit;
    } else {
        $erro = "Login ou senha inválidos!";
    }
}
?>

<?php if(!isset($_SESSION['logado'])): ?>
    <div class="row justify-content-center mt-5">
        <div class="col-md-4">
            <div class="card shadow">
                <div class="card-header bg-dark text-white text-center">
                    <h4>Acesso ao Sistema</h4>
                </div>
                <div class="card-body">
                    <?php if(isset($erro)): ?>
                        <div class="alert alert-danger text-center"><?= $erro ?></div>
                    <?php endif; ?>
                    
                    <form method="POST" action="">
                        <div class="mb-3">
                            <label class="form-label">Usuário</label>
                            <input type="text" name="login" class="form-control" placeholder="Digite seu usuário" required>
                        </div>
                        <div class="mb-4">
                            <label class="form-label">Senha</label>
                            <input type="password" name="senha" class="form-control" placeholder="Digite sua senha" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Entrar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

<?php else: ?>
    <div class="text-center mt-4">
        <h2 class="mb-3">Bem-vindo(a), <?= htmlspecialchars($_SESSION['nome_usuario']) ?>!</h2>
        <p class="text-muted">Selecione uma opção no menu superior ou nos atalhos abaixo.</p>
        <hr class="my-4">
        
        <div class="row mt-4">
            <div class="col-md-3 mb-3">
                <div class="card text-bg-primary shadow-sm h-100">
                    <div class="card-body text-center">
                        <h5 class="card-title">Veículos</h5>
                        <p class="card-text">Gerencie a frota.</p>
                        <a href="admin/veiculo/VeiculoList.php" class="btn btn-light btn-sm">Acessar</a>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card text-bg-success shadow-sm h-100">
                    <div class="card-body text-center">
                        <h5 class="card-title">Motoristas</h5>
                        <p class="card-text">Equipe de condutores.</p>
                        <a href="admin/motorista/MotoristaList.php" class="btn btn-light btn-sm">Acessar</a>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card text-bg-warning shadow-sm h-100">
                    <div class="card-body text-center">
                        <h5 class="card-title">Manutenções</h5>
                        <p class="card-text">Controle de serviços.</p>
                        <a href="admin/manutencao/ManutencaoList.php" class="btn btn-dark btn-sm">Acessar</a>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card text-bg-secondary shadow-sm h-100">
                    <div class="card-body text-center">
                        <h5 class="card-title">Usuários</h5>
                        <p class="card-text">Acessos do sistema.</p>
                        <a href="admin/usuario/UsuarioList.php" class="btn btn-light btn-sm">Acessar</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php require_once 'footer.php'; ?>