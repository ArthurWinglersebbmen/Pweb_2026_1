<?php

include_once './database/db.class.php';

$conn = new db("aluno");

$dados = [
    'nome' => "arthur",
    'telefone' => "999887892",
    'email' => "merdaliquida@gmail",

];

$conn->store($dados);
echo "Inserido com sucesso";
