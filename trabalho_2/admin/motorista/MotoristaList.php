<?php
// Verifica se o usuário está logado
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
    $stmt = $db->prepare("DELETE FROM motorista WHERE id = :id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    header("Location: MotoristaList.php");
    exit;
}

// Lógica do Campo de Busca Funcional
$busca = isset($_GET['busca']) ? $_GET['busca'] : '';
$query = "SELECT * FROM motorista";
if (!empty($busca)) {
    $query .= " WHERE nome_motorista LIKE :busca OR numero_cnh LIKE :busca";
}
$query .= " ORDER BY nome_motorista ASC";

$stmt = $db->prepare($query);
if (!empty($busca)) {
    $termo = "%$busca%";
    $stmt->bindParam(':busca', $termo);
}
$stmt->execute();
$motoristas = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Inclui o topo do layout
require_once '../../header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4 mt-4">
    <h2>Listagem de Motoristas</h2>
    <a href="MotoristaForm.php" class="btn btn-primary">+ Cadastrar Novo</a>
</div>

<!-- Formulário de Pesquisa[cite: 1] -->
<div class="card mb-4 shadow-sm">
    <div class="card-body">
        <form method="GET" action="MotoristaList.php" class="row g-3 align-items-center">
            <div class="col-auto">
                <label class="col-form-label">Pesquisar:</label>
            </div>
            <div class="col-auto">
                <input type="text" name="busca" class="form-control" placeholder="Nome ou CNH..." value="<?= htmlspecialchars($busca) ?>">
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-secondary">🔍 Buscar</button>
                <a href="MotoristaList.php" class="btn btn-light">Limpar</a>
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
                <th>Número CNH</th>
                <th>Categoria</th>
                <th>Validade Exame</th>
                <th class="text-center">Ações</th>
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
                    <td class="text-center">
                        <a href="MotoristaForm.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-warning">Editar</a>
                        <a href="MotoristaList.php?delete_id=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Tem certeza que deseja excluir?');">Excluir</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" class="text-center">Nenhum motorista encontrado.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php require_once '../../footer.php'; ?>