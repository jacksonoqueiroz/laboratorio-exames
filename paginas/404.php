<?php
include_once './include/head.php';
?>

<title>Página não encontrada</title>
</head>

<?php include_once './include/menu.php'; ?>

<div class="container mt-5 text-center">

    <h1 class="display-4 text-danger">404</h1>
    <h3>Página não encontrada</h3>

    <p class="text-muted mt-3">
        A página que você tentou acessar não existe ou foi removida.
    </p>

    <a href="<?= URL ?>home" class="btn btn-primary mt-3">
        <i class="bi bi-house"></i> Ir para Home
    </a>

    <button onclick="history.back()" class="btn btn-secondary mt-3">
        <i class="bi bi-arrow-left"></i> Voltar
    </button>

</div>

<?php include_once './include/footer.php'; ?><?php

echo "Erro 404: Págia não encontrada!";

