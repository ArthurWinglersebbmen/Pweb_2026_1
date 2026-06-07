<?php
// Inicia a sessão e verifica se o usuário está logado
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
    $stmt = $db->prepare("DELETE FROM manutencao WHERE id = :id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    header("Location: ManutencaoList.php");
    exit;
}

// Lógica do Campo de Busca Funcional[cite: 1]
$busca = isset($_GET['busca']) ? $_GET['busca'] : '';
$query = "SELECT * FROM manutencao";
if (!empty($busca)) {
    $query .= " WHERE descricao_pecas LIKE :busca OR data_servico LIKE :busca";
}
$query .= " ORDER BY data_servico DESC";

$stmt = $db->prepare($query);
if (!empty($busca)) {
    $termo = "%$busca%";
    $stmt->bindParam(':busca', $termo);
}
$stmt->execute();
$manutencoes = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Inclui o topo do layout
require_once '../../header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4 mt-4">
    <h2>Listagem de Manutenções</h2>
    <a href="ManutencaoForm.php" class="btn btn-primary">+ Cadastrar Nova</a>
</div>

<!-- Formulário de Pesquisa[cite: 1] -->
<div class="card mb-4 shadow-sm">
    <div class="card-body">
        <form method="GET" action="ManutencaoList.php" class="row g-3 align-items-center">
            <div class="col-auto">
                <label class="col-form-label">Pesquisar:</label>
            </div>
            <div class="col-auto">
                <input type="text" name="busca" class="form-control" placeholder="Data ou Peça..." value="<?= htmlspecialchars($busca) ?>">
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-secondary">🔍 Buscar</button>
                <a href="ManutencaoList.php" class="btn btn-light">Limpar</a>
            </div>
        </form>
    </div>
</div>

<div class="table-responsive shadow-sm">
    <table class="table table-bordered table-striped table-hover align-middle">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Data do Serviço</th>
                <th>KM Atual</th>
                <th>Peças/Descrição</th>
                <th>Custo Total (R$)</th>
                <th class="text-center">Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($manutencoes) > 0): ?>
                <?php foreach ($manutencoes as $row): ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= date('d/m/Y', strtotime($row['data_servico'])) ?></td>
                    <td><?= number_format($row['quilometragem_atual'], 0, ',', '.') ?> km</td>
                    <td><?= htmlspecialchars($row['descricao_pecas']) ?></td>
                    <td><?= number_format($row['custo_total'], 2, ',', '.') ?></td>
                    <td class="text-center">
                        <a href="ManutencaoForm.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-warning">Editar</a>
                        <a href="ManutencaoList.php?delete_id=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Tem certeza que deseja excluir?');">Excluir</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" class="text-center">Nenhuma manutenção encontrada.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php require_once '../../footer.php'; ?>