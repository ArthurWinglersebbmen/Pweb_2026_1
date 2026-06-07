<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
if (!isset($_SESSION['logado'])) {
    header("Location: ../../index.php");
    exit;
}

require_once '../../db.class.php';
$database = new Database();
$db = $database->getConnection();

$id = isset($_GET['id']) ? $_GET['id'] : '';
$nome = '';
$telefone = '';
$email = '';
$login = '';

// Se possui ID na URL, busca os dados para edição
if (!empty($id)) {
    $stmt = $db->prepare("SELECT * FROM usuario WHERE id = :id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($row) {
        $nome = $row['nome'];
        $telefone = $row['telefone'];
        $email = $row['email'];
        $login = $row['login'];
    }
}

// Processa o envio do formulário
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome'];
    $telefone = $_POST['telefone'];
    $email = $_POST['email'];
    $login = $_POST['login'];
    $senha = $_POST['senha'];

    // Validação de campos obrigatórios (senha só é obrigatória no cadastro novo)
    if (!empty($nome) && !empty($telefone) && !empty($email) && !empty($login)) {
        
        try {
            if (empty($id)) {
                // Valida se a senha foi preenchida no novo cadastro
                if(empty($senha)) {
                    $erro = "A senha é obrigatória para novos usuários.";
                } else {
                    $stmt = $db->prepare("INSERT INTO usuario (nome, telefone, email, login, senha) VALUES (:nome, :telefone, :email, :login, :senha)");
                    // Por simplicidade no trabalho vamos manter sem criptografia, já que o script do professor pediu login admin / senha 123
                    $stmt->bindParam(':senha', $senha);
                }
            } else {
                // Modo Edição
                if (!empty($senha)) {
                    // Atualiza COM a senha nova
                    $stmt = $db->prepare("UPDATE usuario SET nome = :nome, telefone = :telefone, email = :email, login = :login, senha = :senha WHERE id = :id");
                    $stmt->bindParam(':senha', $senha);
                } else {
                    // Atualiza SEM mexer na senha
                    $stmt = $db->prepare("UPDATE usuario SET nome = :nome, telefone = :telefone, email = :email, login = :login WHERE id = :id");
                }
                $stmt->bindParam(':id', $id);
            }
            
            if (!isset($erro)) {
                $stmt->bindParam(':nome', $nome);
                $stmt->bindParam(':telefone', $telefone);
                $stmt->bindParam(':email', $email);
                $stmt->bindParam(':login', $login);
                
                if ($stmt->execute()) {
                    header("Location: UsuarioList.php");
                    exit;
                }
            }
        } catch(PDOException $e) {
            if ($e->getCode() == 23000) {
                $erro = "Erro: Este login já está sendo utilizado por outro usuário.";
            } else {
                $erro = "Erro ao salvar os dados: " . $e->getMessage();
            }
        }
    } else {
        $erro = "Preencha todos os campos obrigatórios!";
    }
}

require_once '../../header.php';
?>

<div class="row justify-content-center mt-4">
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header bg-dark text-white">
                <h4 class="mb-0"><?= empty($id) ? 'Novo Usuário' : 'Editar Usuário' ?></h4>
            </div>
            <div class="card-body">
                
                <?php if(isset($erro)): ?>
                    <div class="alert alert-danger"><?= $erro ?></div>
                <?php endif; ?>

                <form method="POST" action="">
                    <div class="row mb-3">
                        <div class="col-md-7">
                            <label class="form-label">Nome Completo *</label>
                            <input type="text" name="nome" class="form-control" value="<?= htmlspecialchars($nome) ?>" required>
                        </div>
                        <div class="col-md-5">
                            <label class="form-label">Telefone *</label>
                            <input type="text" name="telefone" class="form-control" placeholder="(00) 00000-0000" value="<?= htmlspecialchars($telefone) ?>" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">E-mail *</label>
                        <input type="email" name="email" class="form-control" placeholder="exemplo@email.com" value="<?= htmlspecialchars($email) ?>" required>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label class="form-label">Login de Acesso *</label>
                            <input type="text" name="login" class="form-control" value="<?= htmlspecialchars($login) ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Senha <?= empty($id) ? '*' : '(Deixe em branco para não alterar)' ?></label>
                            <input type="password" name="senha" class="form-control" <?= empty($id) ? 'required' : '' ?>>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between mt-4">
                        <a href="UsuarioList.php" class="btn btn-secondary">Voltar</a>
                        <button type="submit" class="btn btn-success">Salvar Usuário</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>

<?php require_once '../../footer.php'; ?>