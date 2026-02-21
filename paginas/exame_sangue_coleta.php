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
        p.nome
    FROM exame_sangue es
    JOIN pacientes p ON p.id = es.paciente_id
    WHERE es.id = :id
");
$stmt->execute([':id' => $exame_id]);
$exame = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$exame || $exame['status'] !== 'Coleta') {
    header("Location: " . URL . "home");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirmar_coleta'])) {

    $stmt = $pdo->prepare("
        UPDATE exame_sangue
        SET status = 'Em Analise'
        WHERE id = :id
    ");
    $stmt->execute([':id' => $exame_id]);

    $_SESSION['msg'] = '<div class="alert alert-success">
        Coleta confirmada com sucesso.
    </div>';

    header("Location: " . URL . "exame-sangue-analise");
    exit;
}

include_once './include/head.php';
?>
<title>Coleta — Exame de Sangue</title>
</head>

<?php include_once './include/menu.php'; ?>

<div class="container mt-4">

<h4>Coleta — Exame de Sangue</h4>

<div class="card">
<div class="card-body">

<p><strong>Paciente:</strong> <?= $exame['nome'] ?></p>
<p><strong>Data:</strong> <?= date('d/m/Y') ?></p>
<p><strong>Status:</strong>
    <span class="badge bg-warning">Coleta</span>
</p>

<form method="post">
    <input type="hidden" name="confirmar_coleta" value="1">

    <button class="btn btn-success">
        Confirmar Coleta
    </button>

    <a href="<?= URL ?>exame_sangue_coleta_imprimir?id=<?= $exame['id'] ?>"
       target="_blank"
       class="btn btn-outline-primary">
        <i class="bi bi-printer"></i> Imprimir Etiquetas
    </a>
</form>

</div>
</div>
</div>

<?php include_once './include/footer.php'; ?>