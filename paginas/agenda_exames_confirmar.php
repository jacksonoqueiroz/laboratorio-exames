<?php
session_start();
ob_start();

include_once 'conexao/conexao.php';
include_once 'config/seguranca.php';

// ========================
// RECEBER DADOS (GET)
// ========================
$exame_id = filter_input(INPUT_GET, 'exame', FILTER_VALIDATE_INT);
$data     = filter_input(INPUT_GET, 'data');
$hora     = filter_input(INPUT_GET, 'hora');

if (!$exame_id || !$data || !$hora) {
    header("Location: " . URL . "agendar_exames");
    exit;
}

// ========================
// BUSCAR EXAME
// ========================
$stmt = $pdo->prepare("SELECT nome FROM tipos_exames WHERE id = :id");
$stmt->execute([':id' => $exame_id]);
$exame = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$exame) {
    header("Location: " . URL . "agendar_exames");
    exit;
}

// ========================
// SALVAR AGENDAMENTO
// ========================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (empty($_POST['paciente_id'])) {
        $_SESSION['msg'] = [
            'tipo'   => 'error',
            'titulo' => 'Paciente não informado',
            'texto'  => 'Selecione ou cadastre um paciente.'
        ];
        header("Location: " . URL . "agendar_exames");
        exit;
    }

    $paciente_id = $_POST['paciente_id'];
    $usuario_id  = $_SESSION['id'];

    try {

        $sql = "INSERT INTO agendamentos_exames
                (tipo_exame_id, paciente_id, data_exame, horario, usuario_id, status)
                VALUES
                (:exame, :paciente, :data, :hora, :usuario, 'Agendado')";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':exame'    => $exame_id,
            ':paciente' => $paciente_id,
            ':data'     => $data,
            ':hora'     => $hora,
            ':usuario'  => $usuario_id
        ]);

        
        $_SESSION['msg'] = '<div class="alert alert-success" role="alert">
        Agendamento realizado! Exame agendado com sucesso!</div>';

        header("Location: " . URL . "pesquisar-agendamentos");
        exit;

    } catch (PDOException $e) {

        // $_SESSION['msg'] = [
        //     'tipo'   => 'error',
        //     'titulo' => 'Erro',
        //     'texto'  => 'Erro ao agendar o exame.'
        // ];
        $_SESSION['msg'] = '<div class="alert alert-danger" role="alert">
        Erro ao agendar o exame.</div>';

        header("Location: " . URL . "agendar_exames");
        exit;
    }
}

// ========================
// HTML
// ========================
include_once './include/head.php';
?>
<title>Confirmar Exame</title>
</head>
<?php include_once './include/menu.php'; ?>

<div class="container mt-4">

<h4>Confirmar Exame</h4>

<div class="card">
<div class="card-body">

<p><strong>Exame:</strong> <?= $exame['nome'] ?></p>
<p><strong>Data:</strong> <?= date('d/m/Y', strtotime($data)) ?></p>
<p><strong>Horário:</strong> <?= substr($hora, 0, 5) ?></p>

<form method="post">

<div class="mb-3 position-relative">
    <label class="form-label">Paciente</label>

    <input type="text"
           id="paciente_nome"
           class="form-control"
           placeholder="Digite o nome do paciente"
           autocomplete="on"
           required>

    <div id="resultadoPacientes" class="list-group position-absolute w-100" style="z-index:1000;"></div>

    <input type="hidden" name="paciente_id" id="paciente_id">

    <button type="button"
            class="btn btn-outline-primary btn-sm mt-2"
            data-bs-toggle="modal"
            data-bs-target="#modalPaciente">
        + Cadastrar novo paciente
    </button>
</div>

<button class="btn btn-success">Confirmar Exame</button>
<a href="<?= URL ?>agendar_exames" class="btn btn-secondary">Cancelar</a>

</form>

</div>
</div>
</div>

<!-- ================= MODAL PACIENTE ================= -->
<?php include_once 'include/modal_cadastro_paciente.php'; ?>

<script>
// ================= SALVAR PACIENTE =================
function salvarPaciente() {

    const form = document.getElementById('formPaciente');
    const dados = new FormData(form);

    fetch('<?= URL ?>ajax/salvar_paciente.php', {
        method: 'POST',
        body: dados
    })
    .then(res => res.json())
    .then(ret => {

        if (ret.erro) {
            document.getElementById('erroPaciente').innerText = ret.erro;
            return;
        }

        document.getElementById('paciente_nome').value = ret.nome;
        document.getElementById('paciente_id').value = ret.id;

        bootstrap.Modal.getInstance(
            document.getElementById('modalPaciente')
        ).hide();

        form.reset();
        document.getElementById('erroPaciente').innerText = '';
    })
    .catch(() => {
        document.getElementById('erroPaciente').innerText =
            'Falha na comunicação com o servidor.';
    });
}
</script>

<script>
// ================= BUSCAR PACIENTE =================
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
