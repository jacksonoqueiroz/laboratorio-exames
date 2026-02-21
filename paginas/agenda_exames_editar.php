<?php
session_start();
include_once('conexao/conexao.php');
include_once('config/seguranca.php');

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

$stmt = $pdo->prepare("
    SELECT a.*, t.nome AS exame
    FROM agendamentos_exames a
    JOIN tipos_exames t ON t.id = a.tipo_exame_id
    WHERE a.id = :id
    AND a.status = 'Agendado'
");
$stmt->execute([':id' => $id]);
$ag = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$ag) {
    header("Location: " . URL . "pesquisar-agendamentos ");
    exit;
}
//Inclui o head
include_once './include/head.php';

//Inclui o menu
include_once './include/menu.php';
 //echo "Página cadastro.";
?>

<div class="container mt-4">
<h4>Editar Agendamento</h4>

<form method="post" action="<?= URL ?>agenda_exames_editar_salvar">

<input type="hidden" name="id" value="<?= $ag['id'] ?>">

<div class="mb-3">
    <label class="form-label">Paciente</label>
    <input type="text" class="form-control" value="<?= $ag['nome_paciente'] ?>" readonly>
</div>

<div class="mb-3">
    <label class="form-label">Exame</label>
    <input type="text" class="form-control" value="<?= $ag['exame'] ?>" readonly>
</div>

<div class="mb-3">
    <label class="form-label">Nova Data</label>
    <input type="date" name="data" class="form-control" required>
</div>

<div class="mb-3">
    <label class="form-label">Novo Horário</label>
    <input type="time" name="horario" class="form-control" required>
</div>

<button class="btn btn-success">Salvar Alterações</button>
<a href="<?= URL ?>pesquisar-agendamentos" class="btn btn-secondary">Cancelar</a>

</form>
</div>

