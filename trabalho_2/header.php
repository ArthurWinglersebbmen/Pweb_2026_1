<?php
session_start();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sis Frota</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <?php if(isset($_SESSION['logado']) && $_SESSION['logado'] === true): ?>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4 shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="index.php">Sis Frota</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="admin/veiculo/VeiculoList.php">Veículos</a></li>
                    <li class="nav-item"><a class="nav-link" href="admin/motorista/MotoristaList.php">Motoristas</a></li>
                    <li class="nav-item"><a class="nav-link" href="admin/manutencao/ManutencaoList.php">Manutenções</a></li>
                    <li class="nav-item"><a class="nav-link" href="admin/usuario/UsuarioList.php">Usuários</a></li>
                    <li class="nav-item"><a class="nav-link text-danger" href="logout.php">Sair</a></li>
                </ul>
            </div>
        </div>
    </nav>
    <?php endif; ?>

    <div class="container">