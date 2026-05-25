<?php

include_once './database/db.class.php';


//instanciar um objeto da classe DB
$conn =  new db("usuario");

$dados = [
    'nome' => "Jackson Five 2",
    'telefone' => "84 9888-55522",
    'email' => "lordjackson@gmail.com",
];

$conn->store($dados);
echo "Inserido com sucesso!";
