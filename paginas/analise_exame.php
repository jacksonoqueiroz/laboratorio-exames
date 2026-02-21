<?php
session_start();
ob_start();

include_once 'conexao/conexao.php';
include_once 'config/seguranca.php';
include_once './include/head.php';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id) {
    header("Location: " . URL . "home");
    exit;
}

// ===============================
// BUSCAR DADOS DO EXAME
// ===============================
$sql = "
SELECT 
    a.id,
    p.nome AS paciente,
    a.data_exame,
    a.paciente_id,
    t.id AS tipo_exame_id,
    t.nome AS exame
FROM agendamentos_exames a
INNER JOIN tipos_exames t ON t.id = a.tipo_exame_id
INNER JOIN pacientes p ON p.id = a.paciente_id

WHERE a.id = :id
AND a.status = 'Em Analise'
";

$stmt = $pdo->prepare($sql);
$stmt->bindParam(':id', $id);
$stmt->execute();
$dados = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$dados) {
    $_SESSION['msg'] = [
        'tipo' => 'warning',
        'titulo' => 'Atenção',
        'texto' => 'Exame não disponível para análise.'
    ];
    header("Location: " . URL . "home");
    exit;
}
?>

<title>Análise Clínica</title>
</head>

<?php include_once './include/menu.php'; ?>

<div class="container mt-4">

    <h4>Análise Clínica</h4>

    <div class="card shadow-sm mb-4">
        <div class="card-body">

            <p><strong>Paciente:</strong> <?= $dados['paciente'] ?></p>
            <p><strong>Exame:</strong> <?= $dados['exame'] ?></p>
            <p><strong>Data do Exame:</strong> <?= date('d/m/Y', strtotime($dados['data_exame'])) ?></p>

        </div>
    </div>

    <form method="post" action="<?= URL ?>analise_exame_salvar">

        <input type="hidden" name="agendamento_id" value="<?= $dados['id'] ?>">
        <input type="hidden" name="tipo_exame_id" value="<?= $dados['tipo_exame_id'] ?>">
        <input type="hidden" name="paciente_id" value="<?= $dados['paciente_id'] ?>">
        <input type="hidden" name="paciente" value="<?= $dados['paciente'] ?>">
        <input type="hidden" name="data_exame" value="<?= $dados['data_exame'] ?>">

        <div class="mb-3">
            <label class="form-label">Resultado</label>
            <textarea name="resultado" class="form-control" rows="4" required></textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Laudo</label>
            <textarea name="laudo" class="form-control" rows="6" required></textarea>
        </div>

        <button type="submit" class="btn btn-success">
            Salvar Resultado
        </button>

        <a href="<?= URL ?>dashboard" class="btn btn-secondary">
            Voltar
        </a>

    </form>

</div>

<?php include_once './include/footer.php'; ?>
