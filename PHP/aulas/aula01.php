<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <?php
    echo "Olá Mundo PHP! <br>";

    $nome = "Jackson Five";
    $idade = 16;

    echo "Nome: $nome - Idade: $idade";

    echo "<br>";
    if ($idade >= 18) {
        echo "De maior";
    } else {
        echo "De menor";
    }

    $notas = [5, 7, 10, 9];

    //print_r($notas);
    echo "<br>";
    for ($i = 0; $i < count($notas); $i++) {
        echo $notas[$i] . "<br>";
    }
    echo "<br>";
    foreach ($notas as $item) {
        echo $item . "<br>";
    }

    echo "<br>";

    $nomes = ["Jackson Five", "Maria", "Ellen", "Arthur"];
    echo "<br>";
    for ($i = 0; $i < count($nomes); $i++) {
        echo $nomes[$i] . "<br>";
    }
    echo "<br>";
    foreach ($nomes as $item) {
        echo $item . "<br>";
    }

    echo "<br>";
    //matriz
    $carros = [
        ['modelo' => "Mustang", 'cor' => "Branco", "ano" => 2026],
        ['modelo' => "Fusca", 'cor' => "Azul", "ano" => 1973],
        ['modelo' => "Brasilia", 'cor' => "Amarela", "ano" => 1969],
    ];

    echo $carros[0]['modelo'] . " - " . $carros[0]['cor'];
    echo "<br>";

    foreach ($carros as $indice => $carro) {
        echo $indice + 1;
        echo " Modelo: " . $carro['modelo'] . " Ano:" . $carro['ano'];

        echo "<br>";
    }


    ?>

    <p> Meu site <?= $carros[0]['modelo'] . " - Ano:" . $carros[0]['ano'];  ?> </p>

    <?php
    include "./aula02.php";
    ?>

</body>

</html>