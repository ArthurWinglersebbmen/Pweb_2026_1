<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>

    <?php
    echo "Olá Mundo";
    $nome = "Arthur Winckler";
    $idade = 19;

           if($idade < 18){
        echo "Menor";
        } else {
        echo "Maior";
        }

    $notas = [5 ,7 ,10, 9];

    echo"<br>";
    for ($i = 0; $i < count($notas); $i++ ) {
        echo $notas($i) . "<br>";
    }
    echo"<br>";
    foreach ($notas as $item ) {
        echo $item . "<br>";
    }
        
    $nomes = ["jack", "gudurus", "draken", "mamacp" ];

        
    echo"<br>"; 
    for ($i = 0; $i < count($nomes); $i++ ) {
        echo $nomes($i) . "<br>";
    }
    echo"<br>";
    foreach ($nomes as $item ) {
        echo $item . "<br>";
    }
    echo"<br>";
    $carro = ['modelo' => "Mustang", "cor" => "Branco", 'ano' => 2026];
    echo $carro = ['modelo'] . " - " . $carro['cor'];
        
    ?>

</body>
</html>