<?php
session_start();
include '../../db.class.php';

$database = new Database();
$db = $database->getConnection();

// Lógica de Exclusão
if (isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];
    $stmt = $db->prepare("DELETE FROM motorista WHERE id = :id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    header("Location: MotoristaList.php");
    exit;
}

// Lógica de Busca
$busca = isset($_GET['busca']) ? $_GET['busca'] : '';
$query = "SELECT * FROM motorista";

if (!empty($busca)) {
    $query .= " WHERE nome_motorista LIKE :busca OR numero_cnh LIKE :busca";
}

$stmt = $db->prepare($query);

if (!empty($busca)) {
    $termo = "%$busca%";
    $stmt->bindParam(':busca', $termo);
}

$stmt->execute();
$motoristas = $stmt->fetchAll(PDO::FETCH_ASSOC);

include '../../header.php';
?>

<h3>Lista de Motoristas</h3>

<div class="mt-2 mb-4">
    <a href="MotoristaForm.php" class="btn btn-primary">Adicionar Motorista</a>
</div>

<form action="MotoristaList.php" method="GET" class="mb-4">
    <div class="row">
        <div class="col-6">
            <input type="text" name="busca" class="form-control" placeholder="Buscar por nome ou CNH..." value="<?= htmlspecialchars($busca) ?>">
        </div>
        <div class="col-4 mt-1">
            <button type="submit" class="btn btn-secondary">Buscar</button>
            <a href="MotoristaList.php" class="btn btn-light">Limpar</a>
        </div>
    </div>
</form>

<table class="table table-striped">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nome</th>
            <th>Nº CNH</th>
            <th>Categoria</th>
            <th>Validade Exame</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
        <?php if (count($motoristas) > 0): ?>
            <?php foreach ($motoristas as $row): ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= htmlspecialchars($row['nome_motorista']) ?></td>
                <td><?= htmlspecialchars($row['numero_cnh']) ?></td>
                <td><?= htmlspecialchars($row['categoria_cnh']) ?></td>
                <td><?= date('d/m/Y', strtotime($row['validade_exame_medico'])) ?></td>
                <td>
                    <a href="MotoristaForm.php?id=<?= $row['id'] ?>" class="btn btn-warning">Editar</a>
                    <a href="MotoristaList.php?delete_id=<?= $row['id'] ?>" class="btn btn-danger" onclick="return confirm('Excluir motorista?');">Excluir</a>
                </td>
            </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="6">Nenhum motorista encontrado.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<?php
include '../../footer.php';
?>