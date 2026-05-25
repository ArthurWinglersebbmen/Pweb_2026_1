<?php
include '../header.php';
?>

<div class="col-6">
    <?php
    /*  
        echo "<p>" . $_GET['nome'] . "</p>";
        echo "<p>" . $_GET['email'] . "</p>";
    */
    echo "<p>" . $_POST['nome'] . "</p>";
    echo "<p>" . $_POST['email'] . "</p>";
    echo "<p>" . $_POST['telefone'] . "</p>";
    echo "<p>" . $_POST['senha'] . "</p>";
    ?>
</div>


<?php
include '../footer.php';
?>