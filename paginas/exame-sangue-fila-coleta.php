<?php
session_start();
ob_start();

include_once 'conexao/conexao.php';
include_once 'config/seguranca.php';
include_once './include/head.php';

// ===============================
// BUSCAR FILA DE COLETA
// ===============================
$sql = "
SELECT 
    es.id,
    es.data_exame,
    es.created_at,
    p.nome,
    p.cpf
FROM exame_sangue es
INNER JOIN pacientes p ON p.id = es.paciente_id
WHERE es.status = 'Coleta'
ORDER BY es.created_at ASC
";

$stmt = $pdo->prepare($sql);
$stmt->execute();
$fila = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<title>Fila de Coleta — Exame de Sangue</title>
</head>

<?php include_once './include/menu.php'; ?>

<div class="container mt-4">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">
            🧪 Fila de Coleta
        </h4>

        <span class="badge bg-primary fs-6">
            <?= count($fila) ?> aguardando
        </span>
    </div>

    <?php if (empty($fila)): ?>
        <div class="alert alert-success text-center">
            Nenhum paciente aguardando coleta 🎉
        </div>
    <?php else: ?>

    <div class="card shadow-sm">
        <div class="card-body p-0">

            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Paciente</th>
                        <th>CPF</th>
                        <th>Chegada</th>
                        <th>Tempo de Espera</th>
                        <th class="text-end">Ação</th>
                    </tr>
                </thead>

                <tbody>
                <?php foreach ($fila as $f): ?>

                    <?php
                        $entrada = new DateTime($f['created_at']);
                        $agora   = new DateTime();
                        $tempo   = $entrada->diff($agora)->format('%H:%I');
                    ?>

                    <tr>
                        <td>
                            <strong><?= $f['nome'] ?></strong>
                        </td>

                        <td><?= $f['cpf'] ?></td>

                        <td>
                            <?= date('d/m/Y H:i', strtotime($f['created_at'])) ?>
                        </td>

                        <td>
                            <span class="badge bg-warning text-dark">
                                ⏱ <?= $tempo ?>
                            </span>
                        </td>

                        <td class="text-end">
                            <a href="<?= URL ?>exame_sangue_coleta?id=<?= $f['id'] ?>"
                               class="btn btn-success btn-sm">
                                <i class="bi bi-droplet-half"></i> Coletar
                            </a>
                        </td>
                    </tr>

                <?php endforeach; ?>
                </tbody>

            </table>

        </div>
    </div>

    <?php endif; ?>

</div>

<?php include_once './include/footer.php'; ?>