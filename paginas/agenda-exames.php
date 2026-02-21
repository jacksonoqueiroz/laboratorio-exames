<?php
session_start();

include_once('conexao/conexao.php');
include_once('config/seguranca.php');

// SALVAR AGENDA
if(isset($_POST['salvar'])){

    $tipo_exame = $_POST['tipo_exame'];
    $dias = $_POST['dias']; // array
    $hora_inicio = $_POST['hora_inicio'];
    $hora_fim = $_POST['hora_fim'];
    $intervalo = $_POST['intervalo'];

    foreach($dias as $dia){
        $stmt = $pdo->prepare("
            INSERT INTO agenda_exames 
            (tipo_exame_id, dia_semana, hora_inicio, hora_fim, intervalo_minutos)
            VALUES (:tipo, :dia, :inicio, :fim, :intervalo)
        ");
        $stmt->execute([
            ':tipo' => $tipo_exame,
            ':dia' => $dia,
            ':inicio' => $hora_inicio,
            ':fim' => $hora_fim,
            ':intervalo' => $intervalo
        ]);
    }
}

// BUSCA TIPOS DE EXAMES (somente os que precisam agenda)
$tipos = $pdo->query("
    SELECT * FROM tipos_exames 
    WHERE precisa_agendamento = 1 AND ativo = 1
    ORDER BY nome
")->fetchAll(PDO::FETCH_ASSOC);

// LISTAGEM AGENDA
$agenda = $pdo->query("
    SELECT a.*, t.nome 
    FROM agenda_exames a
    JOIN tipos_exames t ON t.id = a.tipo_exame_id
    ORDER BY t.nome, a.dia_semana
")->fetchAll(PDO::FETCH_ASSOC);

// HEAD
include_once('./include/head.php');
?>
<title>Agenda de Exames</title>
</head>

<?php include_once('./include/menu.php'); ?>

<div class="container-fluid mt-4">

<div class="row">

<!-- FORMULÁRIO -->
<div class="col-md-4">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <strong>Configurar Agenda</strong>
        </div>

        <div class="card-body">
            <form method="post">

                <div class="form-group">
                    <label>Tipo de Exame</label>
                    <select name="tipo_exame" class="form-control" required>
                        <option value="">Selecione</option>
                        <?php foreach($tipos as $t): ?>
                            <option value="<?= $t['id'] ?>">
                                <?= htmlspecialchars($t['nome']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <label>Dias da Semana</label>

                <?php
                $diasSemana = [
                    '1' => 'Segunda',
                    '2' => 'Terça',
                    '3' => 'Quarta',
                    '4' => 'Quinta',
                    '5' => 'Sexta',
                    '6' => 'Sábado'
                ];
                ?>

                <?php foreach($diasSemana as $valor => $label): ?>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="dias[]" value="<?= $valor ?>">
                        <label class="form-check-label"><?= $label ?></label>
                    </div>
                <?php endforeach; ?>

                <div class="form-group mt-3">
                    <label>Hora Inicial</label>
                    <input type="time" name="hora_inicio" class="form-control" required>
                </div>

                <div class="form-group">
                    <label>Hora Final</label>
                    <input type="time" name="hora_fim" class="form-control" required>
                </div>

                <div class="form-group">
                    <label>Intervalo (minutos)</label>
                    <input type="number" name="intervalo" class="form-control" value="15" required>
                </div>

                <button class="btn btn-success btn-block" name="salvar">
                    <i class="bi bi-calendar-check"></i> Salvar Agenda
                </button>

            </form>
        </div>
    </div>
</div>

<!-- LISTAGEM -->
<div class="col-md-8">
    <div class="card shadow-sm">
        <div class="card-header bg-dark text-white">
            <strong>Agendas Cadastradas</strong>
        </div>

        <div class="card-body p-0">
            <table class="table table-striped mb-0">
                <thead class="thead-light">
                    <tr>
                        <th>Exame</th>
                        <th>Dia</th>
                        <th>Horário</th>
                        <th>Intervalo</th>
                    </tr>
                </thead>
                <tbody>

                <?php if(count($agenda)): ?>
                    <?php foreach($agenda as $a): ?>
                        <tr>
                            <td><?= htmlspecialchars($a['nome']) ?></td>
                            <td><?= ucfirst($a['dia_semana']) ?></td>
                            <td><?= substr($a['hora_inicio'],0,5) ?> - <?= substr($a['hora_fim'],0,5) ?></td>
                            <td><?= $a['intervalo_minutos'] ?> min</td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" class="text-center text-muted">
                            Nenhuma agenda cadastrada
                        </td>
                    </tr>
                <?php endif; ?>

                </tbody>
            </table>
        </div>
    </div>
</div>

</div>
</div>

<?php include_once('include/footer.php'); ?>
