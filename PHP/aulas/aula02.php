<?php

$pessoas = [
    ["nome" => "Jackson Five", "idade" => 38],
    ["nome" => "Ana", "idade" => 18],
    ["nome" => "Chaves", "idade" => 16],
];

foreach ($pessoas as $key => $item) {
    $nome = $item['nome'];
    $idade = $item['idade'];
    echo "Indice: $key Nome:$nome Idade: $idade  <br>";
}
