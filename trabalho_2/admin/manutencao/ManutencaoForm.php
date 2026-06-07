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
$data_servico = '';
$quilometragem_atual = '';
$descricao_pecas = '';
$custo_total = '';

// Se possui ID na URL, busca os dados para preencher o formulário (Modo Edição)
if (!empty($id)) {
    $stmt = $db->prepare("SELECT * FROM manutencao WHERE id = :id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($row) {
        $data_servico = $row['data_servico'];
        $quilometragem_atual = $row['quilometragem_atual'];
        $descricao_pecas = $row['descricao_pecas'];
        $custo_total = $row['custo_total'];
    }
}

// Processa o envio do formulário (INSERT ou UPDATE)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data_servico = $_POST['data_servico'];
    $quilometragem_atual = $_POST['quilometragem_atual'];
    $descricao_pecas = $_POST['descricao_pecas'];
    $custo_total = str_replace(',', '.', $_POST['custo_total']); // Troca vírgula por ponto para o MySQL

    // Validação de campos obrigatórios no back-end[cite: 1]
    if (!empty($data_servico) && !empty($quilometragem_atual) && !empty($descricao_pecas) && !empty($custo_total)) {
        
        if (empty($id)) {
            // Nova Manutenção
            $stmt = $db->prepare("INSERT INTO manutencao (data_servico, quilometragem_atual, descricao_pecas, custo_total) VALUES (:data_servico, :quilometragem_atual, :descricao_pecas, :custo_total)");
        } else {
            // Atualizar Manutenção Existente
            $stmt = $db->prepare("UPDATE manutencao SET data_servico = :data_servico, quilometragem_atual = :quilometragem_atual, descricao_pecas = :descricao_pecas, custo_total = :custo_total WHERE id = :id");
            $stmt->bindParam(':id', $id);
        }
        
        $stmt->bindParam(':data_servico', $data_servico);
        $stmt->bindParam(':quilometragem_atual', $quilometragem_atual);
        $stmt->bindParam(':descricao_pecas', $descricao_pecas);
        $stmt->bindParam(':custo_total', $custo_total);
        
        if ($stmt->execute()) {
            header("Location: ManutencaoList.php");
            exit;
        } else {
            $erro = "Erro ao salvar os dados.";
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
                <h4 class="mb-0"><?= empty($id) ? 'Nova Manutenção' : 'Editar Manutenção' ?></h4>
            </div>
            <div class="card-body">
                
                <?php if(isset($erro)): ?>
                    <div class="alert alert-danger"><?= $erro ?></div>
                <?php endif; ?>

                <form method="POST" action="">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Data do Serviço *</label>
                            <!-- Validação front-end com required[cite: 1] -->
                            <input type="date" name="data_servico" class="form-control" value="<?= htmlspecialchars($data_servico) ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Quilometragem Atual *</label>
                            <input type="number" name="quilometragem_atual" class="form-control" placeholder="Ex: 45000" value="<?= htmlspecialchars($quilometragem_atual) ?>" required>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Peças e Descrição do Serviço *</label>
                        <textarea name="descricao_pecas" class="form-control" rows="3" placeholder="Descreva os serviços feitos e as peças trocadas..." required><?= htmlspecialchars($descricao_pecas) ?></textarea>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-4">
                            <label class="form-label">Custo Total (R$) *</label>
                            <input type="text" name="custo_total" class="form-control" placeholder="0.00" value="<?= htmlspecialchars($custo_total) ?>" required>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="ManutencaoList.php" class="btn btn-secondary">Voltar</a>
                        <button type="submit" class="btn btn-success">Salvar Manutenção</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>

<?php require_once '../../footer.php'; ?>