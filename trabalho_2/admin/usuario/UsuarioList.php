<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
if (!isset($_SESSION['logado'])) {
    header("Location: ../../index.php");
    exit;
}

require_once '../../db.class.php';
$database = new Database();
$db = $database->getConnection();

// Lógica para Excluir Registro
if (isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];
    
    // Proteção básica para não excluir o admin principal (ID 1)
    if ($id != 1) {
        $stmt = $db->prepare("DELETE FROM usuario WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
    } else {
        $erro_exclusao = "Não é permitido excluir o usuário administrador principal.";
    }
    
    // Se não houver erro, recarrega a página
    if (!isset($erro_exclusao)) {
        header("Location: UsuarioList.php");
        exit;
    }
}

// Lógica do Campo de Busca Funcional
$busca = isset($_GET['busca']) ? $_GET['busca'] : '';
$query = "SELECT id, nome, telefone, email, login FROM usuario";
if (!empty($busca)) {
    $query .= " WHERE nome LIKE :busca OR login LIKE :busca OR email LIKE :busca";
}
$query .= " ORDER BY nome ASC";

$stmt = $db->prepare($query);
if (!empty($busca)) {
    $termo = "%$busca%";
    $stmt->bindParam(':busca', $termo);
}
$stmt->execute();
$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

require_once '../../header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4 mt-4">
    <h2>Listagem de Usuários</h2>
    <a href="UsuarioForm.php" class="btn btn-primary">+ Cadastrar Novo</a>
</div>

<?php if(isset($erro_exclusao)): ?>
    <div class="alert alert-danger"><?= $erro_exclusao ?></div>
<?php endif; ?>

<div class="card mb-4 shadow-sm">
    <div class="card-body">
        <form method="GET" action="UsuarioList.php" class="row g-3 align-items-center">
            <div class="col-auto">
                <label class="col-form-label">Pesquisar:</label>
            </div>
            <div class="col-auto">
                <input type="text" name="busca" class="form-control" placeholder="Nome, E-mail ou Login..." value="<?= htmlspecialchars($busca) ?>">
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-secondary">🔍 Buscar</button>
                <a href="UsuarioList.php" class="btn btn-light">Limpar</a>
            </div>
        </form>
    </div>
</div>

<div class="table-responsive shadow-sm">
    <table class="table table-bordered table-striped table-hover align-middle">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Telefone</th>
                <th>E-mail</th>
                <th>Login</th>
                <th class="text-center">Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($usuarios) > 0): ?>
                <?php foreach ($usuarios as $row): ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= htmlspecialchars($row['nome']) ?></td>
                    <td><?= htmlspecialchars($row['telefone']) ?></td>
                    <td><?= htmlspecialchars($row['email']) ?></td>
                    <td><span class="badge bg-secondary"><?= htmlspecialchars($row['login']) ?></span></td>
                    <td class="text-center">
                        <a href="UsuarioForm.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-warning">Editar</a>
                        <?php if ($row['id'] != 1): // Esconde o botão de excluir para o admin ?>
                            <a href="UsuarioList.php?delete_id=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Tem certeza que deseja excluir?');">Excluir</a>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" class="text-center">Nenhum usuário encontrado.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php require_once '../../footer.php'; ?>