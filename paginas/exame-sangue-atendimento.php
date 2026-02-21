<?php
session_start();
ob_start();

include_once 'conexao/conexao.php';
include_once 'config/seguranca.php';

// =======================
// CRIAR EXAME (CHECK-IN)
// =======================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $paciente_id = filter_input(INPUT_POST, 'paciente_id', FILTER_VALIDATE_INT);

    if (!$paciente_id) {
        $_SESSION['msg'] = '<div class="alert alert-danger">Paciente inválido.</div>';
        header("Location: " . URL . "exame-sangue-atendimento");
        exit;
    }

    $stmt = $pdo->prepare("
        INSERT INTO exame_sangue
        (paciente_id, data_exame, status, created_at)
        VALUES
        (:paciente, CURDATE(), 'Check-in', NOW())
    ");
    $stmt->execute([
        ':paciente' => $paciente_id
    ]);

    $exame_id = $pdo->lastInsertId();

    header("Location: " . URL . "exame_sangue_checkin?id=$exame_id");
    exit;
}

include_once './include/head.php';
?>
<title>Atendimento — Exame de Sangue</title>
</head>

<?php include_once './include/menu.php'; ?>

<div class="container mt-4">

<h4>Atendimento — Exame de Sangue</h4>
<p class="text-muted">Atendimento sem agendamento</p>

<div class="card">
<div class="card-body">

<form method="post">

<div class="mb-3 position-relative">
    <label class="form-label">Paciente</label>

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

    <button type="button"
            class="btn btn-outline-primary btn-sm mt-2"
            data-bs-toggle="modal"
            data-bs-target="#modalPaciente">
        + Cadastrar novo paciente
    </button>
</div>

<div id="dadosPaciente" style="display:none;">
    <p><strong>CPF:</strong> <span id="cpf"></span></p>
    <p><strong>Data de nascimento:</strong> <span id="data_nascimento"></span></p>
</div>

<button type="submit"
        class="btn btn-success mt-3"
        id="btnAtender"
        disabled>
    Iniciar Atendimento
</button>

<a href="<?= URL ?>home" class="btn btn-secondary mt-3">Cancelar</a>

</form>

</div>
</div>
</div>

<?php include_once './include/modal_cadastro_paciente.php'; ?>

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

                    document.getElementById('cpf').innerText = p.cpf;
                    document.getElementById('data_nascimento').innerText = p.data_nascimento;
                    document.getElementById('dadosPaciente').style.display = 'block';

                    document.getElementById('btnAtender').disabled = false;
                    resultado.innerHTML = '';
                };

                resultado.appendChild(item);
            });
        });
});
</script>

<?php include_once './include/footer.php'; ?>