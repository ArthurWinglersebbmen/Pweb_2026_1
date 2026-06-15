<?php
session_start();
include '../../db.class.php';

$database = new Database();
$db = $database->getConnection();

// Lógica de Exclusão
if (isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];
    $stmt = $db->prepare("DELETE FROM veiculo WHERE id = :id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    header("Location: VeiculoList.php");
    exit;
}

// Lógica de Busca
$busca = isset($_GET['busca']) ? $_GET['busca'] : '';
$query = "SELECT * FROM veiculo";

if (!empty($busca)) {
    $query .= " WHERE placa LIKE :busca OR modelo LIKE :busca";
}

$stmt = $db->prepare($query);

if (!empty($busca)) {
    $termo = "%$busca%";
    $stmt->bindParam(':busca', $termo);
}

$stmt->execute();
$veiculos = $stmt->fetchAll(PDO::FETCH_ASSOC);

// O professor faz o include do header logo antes do HTML começar
include '../../header.php';
?>

<h3>Lista de Veículos</h3>

<div class="mt-2 mb-4">
    <a href="VeiculoForm.php" class="btn btn-primary">Adicionar Veículo</a>
</div>

<form action="VeiculoList.php" method="GET" class="mb-4">
    <div class="row">
        <div class="col-6">
            <input type="text" name="busca" class="form-control" placeholder="Buscar por placa ou modelo..." value="<?= htmlspecialchars($busca) ?>">
        </div>
        <div class="col-4 mt-1">
            <button type="submit" class="btn btn-secondary">Buscar</button>
            <a href="VeiculoList.php" class="btn btn-light">Limpar</a>
        </div>
    </div>
</form>

<table class="table table-striped">
    <thead>
        <tr>
            <th>ID</th>
            <th>Placa</th>
            <th>Modelo</th>
            <th>Ano</th>
            <th>Passageiros</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
        <?php if (count($veiculos) > 0): ?>
            <?php foreach ($veiculos as $row): ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= htmlspecialchars($row['placa']) ?></td>
                <td><?= htmlspecialchars($row['modelo']) ?></td>
                <td><?= $row['ano_fabricacao'] ?></td>
                <td><?= $row['capacidade_passageiros'] ?></td>
                <td>
                    <a href="VeiculoForm.php?id=<?= $row['id'] ?>" class="btn btn-warning">Editar</a>
                    <a href="VeiculoList.php?delete_id=<?= $row['id'] ?>" class="btn btn-danger" onclick="return confirm('Excluir veículo?');">Excluir</a>
                </td>
            </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="6">Nenhum veículo encontrado.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<?php
include '../../footer.php';
?>