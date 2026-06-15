<?php
session_start();
include '../../db.class.php';

$database = new Database();
$db = $database->getConnection();

// Lógica de Exclusão
if (isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];
    $stmt = $db->prepare("DELETE FROM usuario WHERE id = :id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    header("Location: UsuarioList.php");
    exit;
}

// Lógica de Busca
$busca = isset($_GET['busca']) ? $_GET['busca'] : '';
$query = "SELECT * FROM usuario";

if (!empty($busca)) {
    $query .= " WHERE nome LIKE :busca OR login LIKE :busca";
}

$stmt = $db->prepare($query);

if (!empty($busca)) {
    $termo = "%$busca%";
    $stmt->bindParam(':busca', $termo);
}

$stmt->execute();
$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

include '../../header.php';
?>

<h3>Lista de Usuários</h3>

<div class="mt-2 mb-4">
    <a href="UsuarioForm.php" class="btn btn-primary">Adicionar Usuário</a>
</div>

<form action="UsuarioList.php" method="GET" class="mb-4">
    <div class="row">
        <div class="col-6">
            <input type="text" name="busca" class="form-control" placeholder="Buscar por nome ou login..." value="<?= htmlspecialchars($busca) ?>">
        </div>
        <div class="col-4 mt-1">
            <button type="submit" class="btn btn-secondary">Buscar</button>
            <a href="UsuarioList.php" class="btn btn-light">Limpar</a>
        </div>
    </div>
</form>

<table class="table table-striped">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nome</th>
            <th>Telefone</th>
            <th>Email</th>
            <th>Login</th>
            <th>Ações</th>
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
                <td><?= htmlspecialchars($row['login']) ?></td>
                <td>
                    <a href="UsuarioForm.php?id=<?= $row['id'] ?>" class="btn btn-warning">Editar</a>
                    <a href="UsuarioList.php?delete_id=<?= $row['id'] ?>" class="btn btn-danger" onclick="return confirm('Excluir usuário?');">Excluir</a>
                </td>
            </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="6">Nenhum usuário encontrado.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<?php
include '../../footer.php';
?>