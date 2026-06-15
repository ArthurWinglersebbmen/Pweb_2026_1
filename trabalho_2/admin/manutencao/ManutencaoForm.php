<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
if (!isset($_SESSION['logado'])) {
    header("Location: ../../index.php");
    exit;
}

require_once '../../db.class.php';
$database = new Database();
$db = $database->getConnection();

// --- 1. BUSCA TODOS OS VEÍCULOS PARA PREENCHER O SELECT DO FORMULÁRIO ---
$stmtVeiculos = $db->prepare("SELECT id, placa, modelo FROM veiculo ORDER BY modelo ASC");
$stmtVeiculos->execute();
$veiculos = $stmtVeiculos->fetchAll(PDO::FETCH_ASSOC);

$id = isset($_GET['id']) ? $_GET['id'] : '';
$veiculo_id = '';
$descricao = '';
$data_manutencao = '';
$valor = '';

// Se for edição, busca os dados da manutenção
if (!empty($id)) {
    $stmt = $db->prepare("SELECT * FROM manutencao WHERE id = :id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($row) {
        $veiculo_id = $row['veiculo_id'];
        $descricao = $row['descricao'];
        $data_manutencao = $row['data_manutencao'];
        $valor = $row['valor'];
    }
}

// Processa o envio do formulário
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $veiculo_id = $_POST['veiculo_id'];
    $descricao = trim($_POST['descricao']);
    $data_manutencao = $_POST['data_manutencao'];
    $valor = $_POST['valor'];

    // Validação de campos obrigatórios
    if (!empty($veiculo_id) && !empty($descricao) && !empty($data_manutencao) && !empty($valor)) {
        try {
            if (empty($id)) {
                $stmt = $db->prepare("INSERT INTO manutencao (veiculo_id, descricao, data_manutencao, valor) VALUES (:veiculo_id, :descricao, :data_manutencao, :valor)");
            } else {
                $stmt = $db->prepare("UPDATE manutencao SET veiculo_id = :veiculo_id, descricao = :descricao, data_manutencao = :data_manutencao, valor = :valor WHERE id = :id");
                $stmt->bindParam(':id', $id);
            }
            
            $stmt->bindParam(':veiculo_id', $veiculo_id);
            $stmt->bindParam(':descricao', $descricao);
            $stmt->bindParam(':data_manutencao', $data_manutencao);
            $stmt->bindParam(':valor', $valor);
            
            if ($stmt->execute()) {
                header("Location: ManutencaoList.php");
                exit;
            }
        } catch(PDOException $e) {
            $erro = "Erro ao salvar os dados: " . $e->getMessage();
        }
    } else {
        $erro = "Por favor, preencha todos os campos obrigatórios!";
    }
}

require_once '../../header.php';
?>

<div class="row justify-content-center mt-4">
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header bg-dark text-white">
                <h4 class="mb-0"><?= empty($id) ? 'Nova Manutenção' : 'Editar Manutenção' ?></h4>
            </div>
            <div class="card-body">
                
                <?php if(isset($erro)): ?>
                    <div class="alert alert-danger"><?= $erro ?></div>
                <?php endif; ?>

                <form method="POST" action="">
                    
                    <div class="mb-3">
                        <label class="form-label">Selecione o Veículo *</label>
                        <select name="veiculo_id" class="form-select" required>
                            <option value="" disabled <?= empty($veiculo_id) ? 'selected' : '' ?>>Selecione o veículo...</option>
                            
                            <?php foreach($veiculos as $v): ?>
                                <option value="<?= $v['id'] ?>" <?= $veiculo_id == $v['id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($v['modelo']) ?> (Placa: <?= htmlspecialchars($v['placa']) ?>)
                                </option>
                            <?php endforeach; ?>
                            
                        </select>
                        <?php if(count($veiculos) == 0): ?>
                            <small class="text-danger">Atenção: Nenhum veículo cadastrado no sistema. Cadastre um veículo primeiro!</small>
                        <?php endif; ?>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Descrição do Serviço / Peças Trocadas *</label>
                        <input type="text" name="descricao" class="form-control" placeholder="Ex: Troca de óleo e filtro" value="<?= htmlspecialchars($descricao) ?>" required>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label class="form-label">Data da Manutenção *</label>
                            <input type="date" name="data_manutencao" class="form-control" value="<?= htmlspecialchars($data_manutencao) ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Valor do Serviço (R$) *</label>
                            <input type="number" name="valor" step="0.01" class="form-control" placeholder="0.00" value="<?= htmlspecialchars($valor) ?>" required>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between mt-4">
                        <a href="ManutencaoList.php" class="btn btn-secondary">Voltar</a>
                        <button type="submit" class="btn btn-success" <?= count($veiculos) == 0 ? 'disabled' : '' ?>>Salvar Registro</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>

<?php require_once '../../footer.php'; ?>