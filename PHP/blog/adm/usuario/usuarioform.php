
<?php
include './header.php';
include_once "../database/db.class.php";

$db = new db ('usuario');

if(!empty($_POST)){
    $db->store($_POST);
}

?>

<div class="col">
    <form action="resultadoFormAluno.php" method="post">
        <div class="col-6">
            <label for="nome">Nome: </label>
            <input type="text" name="nomeAluno" class="form-control" maxlength="35" required> 
        </div>

        <div class="col-6">
            <label for="email">E-mail: </label>
            <input type="email" name="emailAluno" class="form-control" required> 
        </div>

        <div class="col-6">
            <label for="telefone">Telefone: </label>
            <input type="text" name="telAluno" class="form-control" required> 
        </div>

        <div class="col mt-4">
            <button type="submit" class="btn btn-success">Salvar</button>
        </div>
    </form>
</div>

<?php
include './footer.php';
?>