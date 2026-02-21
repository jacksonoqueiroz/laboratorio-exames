<?php
session_start();
ob_start();

include_once 'conexao/conexao.php';
include_once 'config/seguranca.php';
include_once './include/head.php';

// ===============================
// BUSCAR EXAMES (FILTRO)
// ===============================
$stmt = $pdo->prepare("
    SELECT id, nome
    FROM tipos_exames
    WHERE ativo = 1
    ORDER BY nome
");
$stmt->execute();
$exames = $stmt->fetchAll(PDO::FETCH_ASSOC);

// ===============================
// FILTROS
// ===============================
$paciente_id = $_GET['paciente_id'] ?? '';
$data        = $_GET['data'] ?? '';
$exame       = $_GET['exame'] ?? '';

$mostrarLista = false;
$agendamentos = [];

if (isset($_GET['pesquisar']) && $paciente_id) {

    $mostrarLista = true;

    $sql = "
        SELECT
            a.id,
            a.data_exame,
            a.horario,
            a.status,
            p.nome AS paciente,
            e.nome AS exame
        FROM agendamentos_exames a
        INNER JOIN pacientes p ON p.id = a.paciente_id
        INNER JOIN tipos_exames e ON e.id = a.tipo_exame_id
        WHERE a.paciente_id = :paciente AND a.status = 'Agendado'
    ";

    $params = [
        ':paciente' => $paciente_id
    ];

    if ($data) {
        $sql .= " AND a.data_exame = :data ";
        $params[':data'] = $data;
    }

    if ($exame) {
        $sql .= " AND a.tipo_exame_id = :exame ";
        $params[':exame'] = $exame;
    }

    $sql .= " ORDER BY a.data_exame DESC, a.horario ASC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $agendamentos = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<title>Pesquisar Agendamentos</title>
</head>

<?php include_once './include/menu.php'; ?>

<div class="container mt-4">

<div class="d-flex justify-content-between align-items-center mb-3">
    <h4>Pesquisar Agendamentos</h4>

    <a href="<?= URL ?>agendar_exames" class="btn btn-success">
        ➕ Novo Agendamento
    </a>
</div>

<?php if (!empty($_SESSION['msg'])): ?>
    <?= $_SESSION['msg']; unset($_SESSION['msg']); ?>
<?php endif; ?>

<!-- ===============================
FILTROS
=============================== -->
<form method="GET" class="row g-2 mb-4">

    <div class="col-4 position-relative">
        <input type="text"
               id="paciente_nome"
               class="form-control"
               placeholder="Digite o nome do paciente"
               autocomplete="off"
               required>

        <div id="resultadoPacientes"
             class="list-group position-absolute w-100"
             style="z-index:1000;"></div>

        <input type="hidden" name="paciente_id" id="paciente_id">
    </div>

    <div class="col-3">
        <input type="date" name="data" class="form-control"
               value="<?= $data ?>">
    </div>

    <div class="col-3">
        <button name="pesquisar" class="btn btn-primary w-100">
            🔍 Pesquisar
        </button>
    </div>

</form>

<!-- ===============================
RESULTADOS
=============================== -->
<?php if ($mostrarLista): ?>

    <?php if (empty($agendamentos)): ?>
        <div class="alert alert-warning">
            Nenhum agendamento encontrado.
        </div>
    <?php else: ?>

<table class="table table-hover align-middle">
<thead>
<tr>
    <th>Paciente</th>
    <th>Exame</th>
    <th>Data</th>
    <th>Hora</th>
    <th>Status</th>
    <th class="text-center">Ações</th>
</tr>
</thead>
<tbody>

<?php foreach ($agendamentos as $a): ?>
<tr>
    <td><?= $a['paciente'] ?></td>
    <td><?= $a['exame'] ?></td>
    <td><?= date('d/m/Y', strtotime($a['data_exame'])) ?></td>
    <td><?= substr($a['horario'],0,5) ?></td>
    <td>
        <span class="badge bg-success"><?= $a['status'] ?></span>
    </td>
    <td class="text-center">

        <a href="<?= URL ?>agenda_exames_editar?id=<?= $a['id'] ?>"
           class="btn btn-sm btn-outline-primary">
            <i class="bi bi-pencil"></i>
        </a>

        <a href="<?= URL ?>agenda_exames_cancelar?id=<?= $a['id'] ?>"
           class="btn btn-sm btn-outline-danger"
           onclick="return confirm('Deseja cancelar este agendamento?')">
            <i class="bi bi-trash"></i>
        </a>

        <a href="<?= URL ?>agenda_exames_comprovante?id=<?= $a['id'] ?>"
           class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-printer"></i>
        </a>

    </td>
</tr>
<?php endforeach; ?>

</tbody>
</table>

    <?php endif; ?>
<?php endif; ?>

</div>

<!-- ================= BUSCA PACIENTE (AJAX) ================= -->
<script>
const input = document.getElementById('paciente_nome');
const resultado = document.getElementById('resultadoPacientes');
const pacienteId = document.getElementById('paciente_id');

input.addEventListener('keyup', function () {

    if (this.value.length < 3) {
        resultado.innerHTML = '';
        return;
    }

    fetch('<?= URL ?>ajax/buscar_paciente.php?q=' + this.value)
        .then(res => res.json())
        .then(dados => {

            resultado.innerHTML = '';

            dados.forEach(p => {
                const item = document.createElement('a');
                item.href = '#';
                item.className = 'list-group-item list-group-item-action';
                item.textContent = `${p.nome} (${p.cpf})`;

                item.onclick = e => {
                    e.preventDefault();
                    input.value = p.nome;
                    pacienteId.value = p.id;
                    resultado.innerHTML = '';
                };

                resultado.appendChild(item);
            });
        });
});
</script>

<?php include_once './include/footer.php'; ?>
