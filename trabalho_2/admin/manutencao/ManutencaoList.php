<?php
session_start();
include '../../db.class.php';

$database = new Database();
$db = $database->getConnection();

// Lógica de Exclusão
if (isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];
    $stmt = $db->prepare("DELETE FROM manutencao WHERE id = :id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    header("Location: ManutencaoList.php");
    exit;
}

// Lógica de Busca (Busca por descrição ou placa do veículo)
$busca = isset($_GET['busca']) ? $_GET['busca'] : '';
$query = "SELECT m.*, v.placa, v.modelo 
          FROM manutencao m 
          INNER JOIN veiculo v ON m.veiculo_id = v.id";

if (!empty($busca)) {
    $query .= " WHERE m.descricao LIKE :busca OR v.placa LIKE :busca";
}
$query .= " ORDER BY m.data_manutencao DESC";

$stmt = $db->prepare($query);

if (!empty($busca)) {
    $termo = "%$busca%";
    $stmt->bindParam(':busca', $termo);
}

$stmt->execute();
$manutencoes = $stmt->fetchAll(PDO::FETCH_ASSOC);

include '../../header.php';
?>

<h3>Lista de Manutenções</h3>

<div class="mt-2 mb-4">
    <a href="ManutencaoForm.php" class="btn btn-primary">Adicionar Manutenção</a>
</div>

<form action="ManutencaoList.php" method="GET" class="mb-4">
    <div class="row">
        <div class="col-6">
            <input type="text" name="busca" class="form-control" placeholder="Buscar por descrição ou placa..." value="<?= htmlspecialchars($busca) ?>">
        </div>
        <div class="col-4 mt-1">
            <button type="submit" class="btn btn-secondary">Buscar</button>
            <a href="ManutencaoList.php" class="btn btn-light">Limpar</a>
        </div>
    </div>
</form>

<table class="table table-striped">
    <thead>
        <tr>
            <th>ID</th>
            <th>Veículo (Placa)</th>
            <th>Descrição do Serviço</th>
            <th>Data</th>
            <th>Valor</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
        <?php if (count($manutencoes) > 0): ?>
            <?php foreach ($manutencoes as $row): ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= htmlspecialchars($row['modelo']) ?> (<?= htmlspecialchars($row['placa']) ?>)</td>
                <td><?= htmlspecialchars($row['descricao']) ?></td>
                <td><?= date('d/m/Y', strtotime($row['data_manutencao'])) ?></td>
                <td>R$ <?= number_format($row['valor'], 2, ',', '.') ?></td>
                <td>
                    <a href="ManutencaoForm.php?id=<?= $row['id'] ?>" class="btn btn-warning">Editar</a>
                    <a href="ManutencaoList.php?delete_id=<?= $row['id'] ?>" class="btn btn-danger" onclick="return confirm('Excluir manutenção?');">Excluir</a>
                </td>
            </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="6">Nenhuma manutenção encontrada.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<?php
include '../../footer.php';
?>