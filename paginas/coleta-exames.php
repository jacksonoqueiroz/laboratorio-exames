<?php
session_start();
ob_start();

include_once 'conexao/conexao.php';
include_once 'config/seguranca.php';
include_once './include/head.php';

// Buscar exames em atendimento
$sql = "
SELECT 
    a.id,
    p.nome AS paciente,
    a.horario,
    t.nome AS exame
FROM agendamentos_exames a
INNER JOIN tipos_exames t ON t.id = a.tipo_exame_id
INNER JOIN pacientes p ON p.id = a.paciente_id
WHERE a.status = 'Em Atendimento'
ORDER BY a.horario
";

$stmt = $pdo->prepare($sql);
$stmt->execute();
$exames = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<title>Coleta de Exames</title>
</head>

<?php include_once './include/menu.php'; ?>

<div class="container mt-4">

    <h4>Coleta — Exames em Atendimento</h4>

    <?php if (empty($exames)): ?>
        <div class="alert alert-info mt-3">
            Nenhum paciente aguardando coleta.
        </div>
    <?php else: ?>

        <div class="row">

        <?php foreach ($exames as $e): ?>
            <div class="col-md-4">
                <div class="card shadow-sm mb-3">

                    <div class="card-body text-center">
                        <h5 class="card-title">
                            <?= htmlspecialchars($e['paciente']) ?>
                        </h5>

                        <p class="mb-1">
                            <strong>Exame:</strong> <?= $e['exame'] ?>
                        </p>

                        <p class="text-muted mb-3">
                            Horário: <?= substr($e['horario'],0,5) ?>
                        </p>

                        <!-- BOTÃO X (CHAMAR) -->
                        <a href="<?= URL ?>coleta_chamar?id=<?= $e['id'] ?>"
                           class="btn btn-danger btn-lg"
                           onclick="return confirm('Chamar paciente para coleta?')">
                            ❌ Chamar
                        </a>
                    </div>

                </div>
            </div>
        <?php endforeach; ?>

        </div>

    <?php endif; ?>

</div>

<?php include_once './include/footer.php'; ?>
