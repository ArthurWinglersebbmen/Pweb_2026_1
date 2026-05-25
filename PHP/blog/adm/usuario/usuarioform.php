<?php
include '../header.php';
include_once "../database/db.class.php";

$db = new db('usuario');
$success = '';
$actionError = '';
$errors = [];

if (!empty($_POST)) {
    // var_dump($_POST);
    //exit;
    try {

        if (empty($_POST['nome'])) {
            $errors[] = "<li>O nome é obrigatório</li>";
        }

        if (empty($_POST['email'])) {
            $errors[] = "<li>O email é obrigatório</li>";
        }

        if (empty($errors)) {
            $db->store($_POST);
            $success = "Registro Salvo com sucesso!";

            redirect('./UsuarioList.php');
        }
    } catch (PDOException $e) {
        $actionError = $e->getMessage();
    } catch (Exception $e) {
        $actionError = $e->getMessage();
    }
}

?>

<div class="row">
    <?php actionMessage($success, $actionError) ?>
    <?php showValidationError($errors) ?>

    <form action="UsuarioForm.php" method="post">
        <h3>Formulário Usuário</h3>
        <div class="col-6">
            <label for="nome">Nome</label>
            <input type="text" name="nome" class="form-control" value="<?php echo getFormValue('nome'); ?>">
        </div>
        <div class="col-6">
            <label for="email">Email</label>
            <input type="email" name="email" class="form-control" value="<?php echo getFormValue('email'); ?>">
        </div>
        <div class="col-6">
            <label for="telefone">Telefone</label>
            <input type="text" name="telefone" class="form-control" value="<?php echo getFormValue('telefone'); ?>">
        </div>
        <div class="mt-2">
            <button type="submit" class="btn btn-success">Salvar</button>
            <a href="./UsuarioList.php" class="btn btn-primary"> Voltar</a>
        </div>


    </form>

</div>

<?php
include '../footer.php';
?>