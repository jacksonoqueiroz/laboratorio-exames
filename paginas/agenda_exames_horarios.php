<?php
session_start();
ob_start();

include_once ('conexao/conexao.php');
include_once ('config/seguranca.php');
include_once './include/head.php';
?>

<title>Agenda Horários do Exame</title>
</head>

<?php include_once './include/menu.php'; ?>

<?php
// ===============================
// RECEBER PARÂMETROS
// ===============================
$exame_id = filter_input(INPUT_GET, 'exame', FILTER_VALIDATE_INT);
$data     = filter_input(INPUT_GET, 'data', FILTER_SANITIZE_SPECIAL_CHARS);

if (!$exame_id || !$data) {
    header("Location: " . URL . "agenda_exames");
    exit;
}

// ===============================
// DIA DA SEMANA
// (1 = segunda | 7 = domingo)
// ===============================
$dia_semana = date('N', strtotime($data));

// ===============================
// BUSCAR EXAME
// ===============================
$stmt = $pdo->prepare("
    SELECT nome 
    FROM tipos_exames 
    WHERE id = :id
");
$stmt->execute([':id' => $exame_id]);
$exame = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$exame) {
    header("Location: " . URL . "agenda_exames");
    exit;
}

// ===============================
// BUSCAR HORÁRIO DO EXAME NO DIA
// ===============================
$stmt = $pdo->prepare("
    SELECT 
        hora_inicio, 
        hora_fim, 
        intervalo_minutos
    FROM agenda_exames
    WHERE tipo_exame_id = :exame
      AND dia_semana = :dia
      AND ativo = 1
");
$stmt->execute([
    ':exame' => $exame_id,
    ':dia'   => $dia_semana
]);

$agenda = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$agenda) {
    echo '<div class="container mt-4">
        <div class="alert alert-warning">
            Este exame não possui atendimento neste dia.
        </div>
        <a href="'.URL.'agenda_exames?exame='.$exame_id.'" class="btn btn-secondary">
            Voltar
        </a>
    </div>';
    exit;
}

// ===============================
// HORÁRIOS JÁ AGENDADOS
// ===============================
$stmt = $pdo->prepare("
    SELECT DATE_FORMAT(horario, '%H:%i')
    FROM agendamentos_exames
    WHERE tipo_exame_id = :exame
      AND data_exame = :data
      AND status = 'Agendado'
");
$stmt->execute([
    ':exame' => $exame_id,
    ':data'  => $data
]);

$ocupados = $stmt->fetchAll(PDO::FETCH_COLUMN);

// ===============================
// CONFIGURAÇÕES
// ===============================
$inicio    = strtotime($agenda['hora_inicio']);
$fim       = strtotime($agenda['hora_fim']);
$intervalo = $agenda['intervalo_minutos'] * 60;
?>

<div class="container mt-4">

<h4>Horários disponíveis</h4>

<p>
    <strong>Exame:</strong> <?= $exame['nome']; ?><br>
    <strong>Data:</strong> <?= date('d/m/Y', strtotime($data)); ?>
</p>

<div class="row">
<?php
for ($h = $inicio; $h < $fim; $h += $intervalo) {

    $hora = date('H:i', $h);

    // 🔴 HORÁRIO OCUPADO
    if (in_array($hora, $ocupados)) {

        echo '
        <div class="col-md-2 mb-2">
            <button class="btn btn-danger w-100" disabled>
                '.$hora.'
            </button>
        </div>';

    } else {

        // 🟢 HORÁRIO DISPONÍVEL
        echo '
        <div class="col-md-2 mb-2">
            <a href="'.URL.'agenda_exames_confirmar?exame='.$exame_id.'&data='.$data.'&hora='.$hora.'" 
               class="btn btn-success w-100">
               '.$hora.'
            </a>
        </div>';
    }
}
?>
</div>

<a href="<?= URL ?>agendar_exames?exame=<?= $exame_id ?>" class="btn btn-secondary mt-3">
    Voltar ao calendário
</a>

</div>

<?php include_once './include/footer.php'; ?>
