<?php
session_start();
ob_start();

include_once 'conexao/conexao.php';
include_once 'config/seguranca.php';
include_once './include/head.php';

// Data atual
$dataHoje = date('Y-m-d');

// Buscar exames do dia
$sql = "
SELECT 
    a.id,
    a.horario,
    a.status,
    p.nome AS paciente,
    e.nome AS exame,
    u.nome AS usuario
FROM agendamentos_exames a
INNER JOIN tipos_exames e ON e.id = a.tipo_exame_id
INNER JOIN usuarios u ON u.id = a.usuario_id
INNER JOIN pacientes p ON p.id = a.paciente_id
WHERE a.data_exame = :data
AND a.status IN ('Agendado','Confirmado')
ORDER BY a.horario
";

$stmt = $pdo->prepare($sql);
$stmt->bindParam(':data', $dataHoje);
$stmt->execute();
$agendamentos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<title>Recepção — Exames</title>
</head>

<?php include_once './include/menu.php'; ?>

<div class="container mt-4">

    <h4>Recepção — Exames de Hoje</h4>
    <p class="text-muted"><?= date('d/m/Y') ?></p>

    <?php if (empty($agendamentos)): ?>
        <div class="alert alert-info">
            Nenhum exame agendado para hoje.
        </div>
    <?php else: ?>

    <table class="table table-hover align-middle">
        <thead class="table-light">
            <tr>
                <th>Horário</th>
                <th>Paciente</th>
                <th>Exame</th>
                <th>Status</th>
                <th class="text-end">Ação</th>
            </tr>
        </thead>
        <tbody>

        <?php foreach ($agendamentos as $a): ?>
            <tr>
                <td><?= substr($a['horario'],0,5) ?></td>
                <td><?= htmlspecialchars($a['paciente']) ?></td>
                <td><?= $a['exame'] ?></td>
                <td>
                    <span class="badge bg-secondary">
                        <?= $a['status'] ?>
                    </span>
                </td>
                <td class="text-end">

                    <a href="<?= URL ?>recepcao_exames_checkin?id=<?= $a['id'] ?>" 
                       class="btn btn-success btn-sm"
                       onclick="return confirm('Confirmar chegada do paciente?')">
                        Check-in
                    </a>

                    <a href="<?= URL ?>exame-cancelar?id=<?= $a['id'] ?>" 
                       class="btn btn-danger btn-sm"
                       onclick="return confirm('Cancelar este agendamento?')">
                        Cancelar
                    </a>

                </td>
            </tr>
        <?php endforeach; ?>

        </tbody>
    </table>

    <?php endif; ?>

</div>

<?php include_once './include/footer.php'; ?>
