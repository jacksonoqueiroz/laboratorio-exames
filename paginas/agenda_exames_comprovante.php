<?php
session_start();
ob_start();

include_once 'conexao/conexao.php';
include_once 'config/seguranca.php';
include_once './include/head.php';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id) {
    header("Location: " . URL . "pesquisar-agendamentos");
    exit;
}

// ===============================
// BUSCAR AGENDAMENTO
// ===============================
$stmt = $pdo->prepare("
    SELECT 
        a.id,
        a.data_exame,
        a.horario,
        a.status,
        a.created_at,
        p.nome AS paciente,
        e.nome AS exame
    FROM agendamentos_exames a
    INNER JOIN pacientes p ON p.id = a.paciente_id
    INNER JOIN tipos_exames e ON e.id = a.tipo_exame_id
    WHERE a.id = :id
");
$stmt->execute([':id' => $id]);
$ag = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$ag) {
    echo "
    <div class='container mt-4 alert alert-danger'>
        Agendamento não encontrado.
    </div>";
    exit;
}
?>

<title>Comprovante de Exame</title>
</head>

<body>

<div class="container mt-4 print-area">

<div class="card print-clean">
<div class="card-body">

<div class="row align-items-center mb-4">
    <div class="col-2">
        <img src="<?= URL ?>assets/images/logo2.png" height="90">
    </div>
    <div class="col-10 text-muted">
        <strong>Laboratório de Análises Clínicas</strong>
    </div>
</div>

<hr>

<p><strong>Paciente:</strong> <?= htmlspecialchars($ag['paciente']) ?></p>
<p><strong>Exame:</strong> <?= htmlspecialchars($ag['exame']) ?></p>
<p><strong>Data:</strong> <?= date('d/m/Y', strtotime($ag['data_exame'])) ?></p>
<p><strong>Horário:</strong> <?= substr($ag['horario'], 0, 5) ?></p>
<p><strong>Status:</strong> <?= $ag['status'] ?></p>

<hr>

<p class="text-muted small">
    Código do agendamento:
    <?= str_pad($ag['id'], 6, '0', STR_PAD_LEFT) ?><br>

    Agendado por:
    <?= $_SESSION['nome'] ?><br>

    Gerado em:
    <?= date('d/m/Y H:i', strtotime($ag['created_at'])) ?>
</p>

<div class="text-center mt-4 no-print">
    <button onclick="window.print()" class="btn btn-primary">
        <i class="bi bi-printer"></i> Imprimir
    </button>

    <a href="<?= URL ?>pesquisar-agendamentos" class="btn btn-secondary">
        Voltar
    </a>
</div>

</div>
</div>
</div>

</body>
