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
    $stmt = $db->prepare("DELETE FROM veiculo WHERE id = :id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    header("Location: VeiculoList.php");
    exit;
}

// Lógica do Campo de Busca Funcional
$busca = isset($_GET['busca']) ? $_GET['busca'] : '';
$query = "SELECT * FROM veiculo";
if (!empty($busca)) {
    $query .= " WHERE placa LIKE :busca OR modelo LIKE :busca";
}
$query .= " ORDER BY modelo ASC";

$stmt = $db->prepare($query);
if (!empty($busca)) {
    $termo = "%$busca%";
    $stmt->bindParam(':busca', $termo);
}
$stmt->execute();
$veiculos = $stmt->fetchAll(PDO::FETCH_ASSOC);

require_once '../../header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4 mt-4">
    <h2>Listagem de Veículos</h2>
    <a href="VeiculoForm.php" class="btn btn-primary">+ Cadastrar Novo</a>
</div>

<div class="card mb-4 shadow-sm">
    <div class="card-body">
        <form method="GET" action="VeiculoList.php" class="row g-3 align-items-center">
            <div class="col-auto">
                <label class="col-form-label">Pesquisar:</label>
            </div>
            <div class="col-auto">
                <input type="text" name="busca" class="form-control" placeholder="Placa ou Modelo..." value="<?= htmlspecialchars($busca) ?>">
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-secondary">🔍 Buscar</button>
                <a href="VeiculoList.php" class="btn btn-light">Limpar</a>
            </div>
        </form>
    </div>
</div>

<div class="table-responsive shadow-sm">
    <table class="table table-bordered table-striped table-hover align-middle">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Placa</th>
                <th>Modelo</th>
                <th>Ano de Fabricação</th>
                <th>Capacidade (Passageiros)</th>
                <th class="text-center">Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($veiculos) > 0): ?>
                <?php foreach ($veiculos as $row): ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><span class="badge bg-secondary fs-6"><?= htmlspecialchars($row['placa']) ?></span></td>
                    <td><?= htmlspecialchars($row['modelo']) ?></td>
                    <td><?= htmlspecialchars($row['ano_fabricacao']) ?></td>
                    <td><?= htmlspecialchars($row['capacidade_passageiros']) ?></td>
                    <td class="text-center">
                        <a href="VeiculoForm.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-warning">Editar</a>
                        <a href="VeiculoList.php?delete_id=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Tem certeza que deseja excluir?');">Excluir</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" class="text-center">Nenhum veículo encontrado.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php require_once '../../footer.php'; ?>