<?php
include '../header.php';
include_once "../database/db.class.php";

$db = new db('usuario');

if (!empty($_POST)) {
    // var_dump($_POST);
    //exit;
    // $db->store($_POST);
} else {
    $dados = $db->all();
}

?>
<div class="row">

    <div class="row">
        <div class="col">
            <a href="./UsuarioForm.php" class="btn btn-success"> Novo</a>
        </div>
    </div>
</div>


<div class="row">
    <table class="table table-striped table-hover">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Nome</th>
                <th scope="col">Telefone</th>
                <th scope="col">Email</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($dados as $item) {
                echo "<tr>
                <th scope='row'>$item->id</th>
                <td>$item->nome</td>
                <td>$item->telefone</td>
                <td>$item->email</td>
            </tr>";
            }
            ?>
        </tbody>
    </table>
</div>



<?php
include '../footer.php';
?>