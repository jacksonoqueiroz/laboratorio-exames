<?php
session_start();
ob_start();

include_once ('conexao/conexao.php');
include_once ('config/seguranca.php');
include_once './include/head.php';

$mostrar_apenas_ultimo = true;

// Se clicar em "Ver todos"
if (filter_input(INPUT_GET, 'todos', FILTER_VALIDATE_INT)) {
    $mostrar_apenas_ultimo = false;
    unset($_SESSION['ultimo_agendamento_exame']);
}


$sql = "
    SELECT 
        a.id,
        a.nome_paciente,
        a.data_exame,
        a.horario,
        a.status,
        e.nome AS exame,
        u.nome AS usuario
    FROM agendamentos_exames a
    INNER JOIN tipos_exames e ON e.id = a.tipo_exame_id
    INNER JOIN usuarios u ON u.id = a.usuario_id
";

$params = [];

if ($mostrar_apenas_ultimo && isset($_SESSION['ultimo_agendamento_exame'])) {
    $sql .= " WHERE a.id = :id ";
    $params[':id'] = $_SESSION['ultimo_agendamento_exame'];
}

$sql .= " ORDER BY a.data_exame DESC, a.horario ASC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$agendamentos = $stmt->fetchAll(PDO::FETCH_ASSOC);


?>

<title>Agendamentos de Exames</title>
</head>

<?php include_once './include/menu.php'; ?>

<div class="container mt-4">

    <?php
        if (isset($_SESSION['msg'])) {
            echo $_SESSION['msg'];
            unset($_SESSION['msg']);
        }
    ?>


<h4>Agendamentos de Exames</h4>

<div class="d-flex gap-2 mb-3">

    <a href="<?= URL ?>agendar_exames" class="btn btn-primary">
        + Agendar novo exame
    </a>

    <?php if (!empty($mostrar_apenas_ultimo)) { ?>
        <a href="<?= URL ?>agenda_exames_lista?todos=1" class="btn btn-outline-secondary">
            Ver todos os agendamentos
        </a>
    <?php } ?>

</div>


<table class="table table-bordered table-striped">
    <thead class="table-dark">
        <tr>
            <th>Paciente</th>
            <th>Exame</th>
            <th>Data</th>
            <th>Horário</th>
            <th>Status</th>
            <th>Usuário</th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($agendamentos as $a): ?>
        <tr>
            <td><?= $a['nome_paciente'] ?></td>
            <td><?= $a['exame'] ?></td>
            <td><?= date('d/m/Y', strtotime($a['data_exame'])) ?></td>
            <td><?= $a['horario'] ?></td>
            <td>
                <span class="badge bg-success"><?= $a['status'] ?></span>
            </td>
            <td><?= $a['usuario'] ?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

</div>

<?php include_once './include/footer.php'; ?>
