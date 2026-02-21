<?php
session_start();
ob_start();

include_once 'conexao/conexao.php';
include_once 'config/seguranca.php';

$exame_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$exame_id) {
    header("Location: " . URL . "home");
    exit;
}

$stmt = $pdo->prepare("
    SELECT 
        es.id,
        es.status,
        p.nome,
        p.cpf,
        p.data_nascimento
    FROM exame_sangue es
    JOIN pacientes p ON p.id = es.paciente_id
    WHERE es.id = :id
");
$stmt->execute([':id' => $exame_id]);
$exame = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$exame || $exame['status'] !== 'Check-in') {
    header("Location: " . URL . "home");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $stmt = $pdo->prepare("
        UPDATE exame_sangue
        SET status = 'Coleta'
        WHERE id = :id
    ");
    $stmt->execute([':id' => $exame_id]);

    header("Location: " . URL . "exame_sangue_coleta?id=$exame_id");
    exit;
}

include_once './include/head.php';
?>
<title>Check-in — Exame de Sangue</title>
</head>

<?php include_once './include/menu.php'; ?>

<div class="container mt-4">

<h4>Check-in — Exame de Sangue</h4>

<div class="card">
<div class="card-body">

<p><strong>Paciente:</strong> <?= $exame['nome'] ?></p>
<p><strong>CPF:</strong> <?= $exame['cpf'] ?></p>
<p><strong>Data de nascimento:</strong>
    <?= date('d/m/Y', strtotime($exame['data_nascimento'])) ?>
</p>

<form method="post">
    <button class="btn btn-primary">Confirmar Check-in</button>
    <a href="<?= URL ?>home" class="btn btn-secondary">Cancelar</a>
</form>

</div>
</div>
</div>

<?php include_once './include/footer.php'; ?>