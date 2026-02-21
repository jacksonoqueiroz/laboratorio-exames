<?php
session_start();
ob_start();

include_once 'conexao/conexao.php';
include_once 'config/seguranca.php';
include_once './include/head.php';

// ===============================
// BUSCAR EXAMES EM ANÁLISE
// ===============================
$sql = "
SELECT 
    a.id,
    a.paciente_id,
    p.nome AS paciente,
    a.data_exame
FROM exame_sangue a
INNER JOIN pacientes p ON p.id = a.paciente_id
WHERE a.status = 'Em Analise'
ORDER BY a.data_exame ASC
";

$stmt = $pdo->prepare($sql);
$stmt->execute();
$exames = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<title>Exame Sangue Análise</title>
</head>

<?php include_once './include/menu.php'; ?>

<div class="container mt-4">

    <h4>Exames Sangue em Análise</h4>

    <?php if (empty($exames)): ?>
        <div class="alert alert-info">
            Nenhum exame aguardando análise.
        </div>
    <?php else: ?>

        <table class="table table-hover align-middle mt-3">
            <thead class="table-light">
                <tr>
                    <th>Id</th>
                    <th>Paciente</th>
                    <th>Data</th>
                    <th class="text-end">Ação</th>
                </tr>
            </thead>
            <tbody>

            <?php foreach ($exames as $e): ?>
                <tr>
                    <td><?= $e['id'] ?></td>
                    <td><?= $e['paciente'] ?></td>
                    <td><?= date('d/m/Y', strtotime($e['data_exame'])) ?></td>
                    <td class="text-end">

                        <a href="<?= URL ?>exame_sangue_resultado?id=<?= $e['id'] ?>"
                           class="btn btn-primary btn-sm">
                            Analisar
                        </a>

                    </td>
                </tr>
            <?php endforeach; ?>

            </tbody>
        </table>

    <?php endif; ?>

</div>

<?php include_once './include/footer.php'; ?>
