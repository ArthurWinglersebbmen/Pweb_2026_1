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
$nome_motorista = '';
$numero_cnh = '';
$categoria_cnh = '';
$validade_exame_medico = '';

// Se possui ID na URL, busca os dados para edição
if (!empty($id)) {
    $stmt = $db->prepare("SELECT * FROM motorista WHERE id = :id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($row) {
        $nome_motorista = $row['nome_motorista'];
        $numero_cnh = $row['numero_cnh'];
        $categoria_cnh = $row['categoria_cnh'];
        $validade_exame_medico = $row['validade_exame_medico'];
    }
}

// Processa o envio do formulário
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome_motorista = $_POST['nome_motorista'];
    $numero_cnh = $_POST['numero_cnh'];
    $categoria_cnh = $_POST['categoria_cnh'];
    $validade_exame_medico = $_POST['validade_exame_medico'];

    // Validação de campos obrigatórios[cite: 1]
    if (!empty($nome_motorista) && !empty($numero_cnh) && !empty($categoria_cnh) && !empty($validade_exame_medico)) {
        
        try {
            if (empty($id)) {
                $stmt = $db->prepare("INSERT INTO motorista (nome_motorista, numero_cnh, categoria_cnh, validade_exame_medico) VALUES (:nome, :cnh, :categoria, :validade)");
            } else {
                $stmt = $db->prepare("UPDATE motorista SET nome_motorista = :nome, numero_cnh = :cnh, categoria_cnh = :categoria, validade_exame_medico = :validade WHERE id = :id");
                $stmt->bindParam(':id', $id);
            }
            
            $stmt->bindParam(':nome', $nome_motorista);
            $stmt->bindParam(':cnh', $numero_cnh);
            $stmt->bindParam(':categoria', $categoria_cnh);
            $stmt->bindParam(':validade', $validade_exame_medico);
            
            if ($stmt->execute()) {
                header("Location: MotoristaList.php");
                exit;
            }
        } catch(PDOException $e) {
            // Trata possível erro de CNH duplicada (UNIQUE constraint definida no banco)
            if ($e->getCode() == 23000) {
                $erro = "Erro: Este número de CNH já está cadastrado no sistema.";
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
                <h4 class="mb-0"><?= empty($id) ? 'Novo Motorista' : 'Editar Motorista' ?></h4>
            </div>
            <div class="card-body">
                
                <?php if(isset($erro)): ?>
                    <div class="alert alert-danger"><?= $erro ?></div>
                <?php endif; ?>

                <form method="POST" action="">
                    <div class="mb-3">
                        <label class="form-label">Nome Completo *</label>
                        <!-- Validação front-end com required[cite: 1] -->
                        <input type="text" name="nome_motorista" class="form-control" placeholder="Ex: João da Silva" value="<?= htmlspecialchars($nome_motorista) ?>" required>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label">Número da CNH *</label>
                            <input type="text" name="numero_cnh" class="form-control" placeholder="Apenas números" value="<?= htmlspecialchars($numero_cnh) ?>" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Categoria *</label>
                            <select name="categoria_cnh" class="form-select" required>
                                <option value="" disabled <?= empty($categoria_cnh) ? 'selected' : '' ?>>Selecione...</option>
                                <option value="A" <?= $categoria_cnh == 'A' ? 'selected' : '' ?>>A</option>
                                <option value="B" <?= $categoria_cnh == 'B' ? 'selected' : '' ?>>B</option>
                                <option value="AB" <?= $categoria_cnh == 'AB' ? 'selected' : '' ?>>AB</option>
                                <option value="C" <?= $categoria_cnh == 'C' ? 'selected' : '' ?>>C</option>
                                <option value="D" <?= $categoria_cnh == 'D' ? 'selected' : '' ?>>D</option>
                                <option value="E" <?= $categoria_cnh == 'E' ? 'selected' : '' ?>>E</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Validade Exame *</label>
                            <input type="date" name="validade_exame_medico" class="form-control" value="<?= htmlspecialchars($validade_exame_medico) ?>" required>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between mt-4">
                        <a href="MotoristaList.php" class="btn btn-secondary">Voltar</a>
                        <button type="submit" class="btn btn-success">Salvar Motorista</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>

<?php require_once '../../footer.php'; ?>