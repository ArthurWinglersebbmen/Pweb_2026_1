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
$placa = '';
$modelo = '';
$ano_fabricacao = '';
$capacidade_passageiros = '';

// Se possui ID na URL, busca os dados para edição
if (!empty($id)) {
    $stmt = $db->prepare("SELECT * FROM veiculo WHERE id = :id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($row) {
        $placa = $row['placa'];
        $modelo = $row['modelo'];
        $ano_fabricacao = $row['ano_fabricacao'];
        $capacidade_passageiros = $row['capacidade_passageiros'];
    }
}

// Processa o envio do formulário
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $placa = strtoupper(trim($_POST['placa'])); // Converte placa para maiúsculo
    $modelo = trim($_POST['modelo']);
    $ano_fabricacao = $_POST['ano_fabricacao'];
    $capacidade_passageiros = $_POST['capacidade_passageiros'];

    // Validação de campos obrigatórios
    if (!empty($placa) && !empty($modelo) && !empty($ano_fabricacao) && !empty($capacidade_passageiros)) {
        
        try {
            if (empty($id)) {
                $stmt = $db->prepare("INSERT INTO veiculo (placa, modelo, ano_fabricacao, capacidade_passageiros) VALUES (:placa, :modelo, :ano_fabricacao, :capacidade_passageiros)");
            } else {
                $stmt = $db->prepare("UPDATE veiculo SET placa = :placa, modelo = :modelo, ano_fabricacao = :ano_fabricacao, capacidade_passageiros = :capacidade_passageiros WHERE id = :id");
                $stmt->bindParam(':id', $id);
            }
            
            $stmt->bindParam(':placa', $placa);
            $stmt->bindParam(':modelo', $modelo);
            $stmt->bindParam(':ano_fabricacao', $ano_fabricacao);
            $stmt->bindParam(':capacidade_passageiros', $capacidade_passageiros);
            
            if ($stmt->execute()) {
                header("Location: VeiculoList.php");
                exit;
            }
        } catch(PDOException $e) {
            // Trata possível erro de placa duplicada
            if ($e->getCode() == 23000) {
                $erro = "Erro: Esta placa já está cadastrada no sistema.";
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
                <h4 class="mb-0"><?= empty($id) ? 'Novo Veículo' : 'Editar Veículo' ?></h4>
            </div>
            <div class="card-body">
                
                <?php if(isset($erro)): ?>
                    <div class="alert alert-danger"><?= $erro ?></div>
                <?php endif; ?>

                <form method="POST" action="">
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label">Placa *</label>
                            <input type="text" name="placa" class="form-control" placeholder="Ex: ABC1D23" value="<?= htmlspecialchars($placa) ?>" required maxlength="10">
                        </div>
                        <div class="col-md-8">
                            <label class="form-label">Modelo do Veículo *</label>
                            <input type="text" name="modelo" class="form-control" placeholder="Ex: Mercedes-Benz Sprinter" value="<?= htmlspecialchars($modelo) ?>" required>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label class="form-label">Ano de Fabricação *</label>
                            <input type="number" name="ano_fabricacao" class="form-control" placeholder="Ex: 2020" value="<?= htmlspecialchars($ano_fabricacao) ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Capacidade de Passageiros *</label>
                            <input type="number" name="capacidade_passageiros" class="form-control" placeholder="Ex: 15" value="<?= htmlspecialchars($capacidade_passageiros) ?>" required>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between mt-4">
                        <a href="VeiculoList.php" class="btn btn-secondary">Voltar</a>
                        <button type="submit" class="btn btn-success">Salvar Veículo</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>

<?php require_once '../../footer.php'; ?>