<?php
session_start();
ob_start();

include_once ('conexao/conexao.php');
include_once ('config/seguranca.php');
include_once './include/head.php';

// ========================
// BUSCAR EXAMES
// ========================
$stmt = $pdo->prepare("
    SELECT id, nome 
    FROM tipos_exames 
    WHERE ativo = 1 
    ORDER BY nome
");
$stmt->execute();
$exames = $stmt->fetchAll(PDO::FETCH_ASSOC);

// ========================
// RECEBER EXAME
// ========================
$exame_id = filter_input(INPUT_GET, 'exame', FILTER_VALIDATE_INT);

// ========================
// MÊS / ANO
// ========================
$mes = filter_input(INPUT_GET, 'mes', FILTER_VALIDATE_INT) ?? date('m');
$ano = filter_input(INPUT_GET, 'ano', FILTER_VALIDATE_INT) ?? date('Y');

// ========================
// DATAS BASE
// ========================
$primeiroDia = strtotime("$ano-$mes-01");
$totalDias   = date('t', $primeiroDia);
$hoje        = date('Y-m-d');
$dataLimite  = date('Y-m-d', strtotime('+6 months'));

// ========================
// DIAS QUE O EXAME ATENDE
// ========================
$diasAtendimento = [];
if ($exame_id) {
    $stmt = $pdo->prepare("
        SELECT dia_semana 
        FROM agenda_exames 
        WHERE tipo_exame_id = :id
    ");
    $stmt->execute([':id' => $exame_id]);
    $diasAtendimento = $stmt->fetchAll(PDO::FETCH_COLUMN);
}

$diasSemana = ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb'];

// ========================
// NAVEGAÇÃO
// ========================
$mesAnterior = $mes - 1;
$anoAnterior = $ano;
$mesProximo  = $mes + 1;
$anoProximo  = $ano;

if ($mesAnterior < 1) {
    $mesAnterior = 12;
    $anoAnterior--;
}
if ($mesProximo > 12) {
    $mesProximo = 1;
    $anoProximo++;
}
?>
<title>Agenda de Exames</title>
</head>

<?php include_once './include/menu.php'; ?>

<div class="container mt-4">

<div class="d-flex justify-content-between align-items-center mb-3">
    <h4>Agenda de Exames</h4>

    <a href="<?= URL ?>pesquisar-agendamentos" class="btn btn-primary">
        🔍 Pesquisar
    </a>
</div>

<form method="get" class="row mb-4">
    <div class="col-md-4">
        <label class="form-label">Selecione o exame</label>
        <select name="exame" class="form-select" onchange="this.form.submit()">
            <option value="">Selecione</option>
            <?php foreach ($exames as $e): ?>
                <option value="<?= $e['id'] ?>" <?= ($exame_id == $e['id']) ? 'selected' : '' ?>>
                    <?= $e['nome'] ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    <?php if ($exame_id): ?>
        <input type="hidden" name="mes" value="<?= $mes ?>">
        <input type="hidden" name="ano" value="<?= $ano ?>">
    <?php endif; ?>
</form>

<?php if ($exame_id): ?>

<div class="d-flex justify-content-between mb-2">
    <a href="<?= URL ?>agendar_exames?exame=<?= $exame_id ?>&mes=<?= $mesAnterior ?>&ano=<?= $anoAnterior ?>"
       class="btn btn-outline-secondary btn-sm">◀ Mês anterior</a>

    <strong><?= date('m/Y', $primeiroDia) ?></strong>

    <?php if (date('Y-m-d', strtotime("$anoProximo-$mesProximo-01")) <= $dataLimite): ?>
        <a href="<?= URL ?>agendar_exames?exame=<?= $exame_id ?>&mes=<?= $mesProximo ?>&ano=<?= $anoProximo ?>"
           class="btn btn-outline-secondary btn-sm">Próximo mês ▶</a>
    <?php endif; ?>
</div>

<div class="card">
<div class="card-body">

<div class="row text-center fw-bold">
<?php foreach ($diasSemana as $d): ?>
    <div class="col border"><?= $d ?></div>
<?php endforeach; ?>
</div>

<div class="row text-center">
<?php
$inicioSemana = date('w', $primeiroDia);
for ($i = 0; $i < $inicioSemana; $i++) {
    echo '<div class="col border p-3"></div>';
}

for ($dia = 1; $dia <= $totalDias; $dia++) {

    $dataAtual = date('Y-m-d', strtotime("$ano-$mes-$dia"));
    $diaSemanaNumero = date('N', strtotime($dataAtual)); // 1 a 7

    $passado = $dataAtual < $hoje;
    $atende  = in_array($diaSemanaNumero, $diasAtendimento);

    if ($atende && !$passado && $dataAtual <= $dataLimite) {
        echo "
        <div class='col border p-3'>
            <a href='".URL."agenda_exames_horarios?exame=$exame_id&data=$dataAtual'
               class='btn btn-outline-success btn-sm'>$dia</a>
        </div>";
    } else {
        echo "<div class='col border p-3 text-muted bg-light'>$dia</div>";
    }

    if (date('w', strtotime($dataAtual)) == 6) {
        echo '</div><div class="row text-center">';
    }
}
?>
</div>

</div>
</div>

<?php endif; ?>
</div>

<?php include_once './include/footer.php'; ?>
