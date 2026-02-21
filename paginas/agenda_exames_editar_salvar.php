<?php
session_start();
include_once('conexao/conexao.php');
include_once('config/seguranca.php');

$id      = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
$data    = filter_input(INPUT_POST, 'data');
$horario = filter_input(INPUT_POST, 'horario');

$stmt = $pdo->prepare("
    UPDATE agendamentos_exames
    SET data_exame = :data,
        horario = :hora
    WHERE id = :id
");
$stmt->execute([
    ':data' => $data,
    ':hora' => $horario,
    ':id'   => $id
]);

$_SESSION['msg'] = '<div class="alert alert-success">
    Agendamento atualizado com sucesso!
</div>';

header("Location: " . URL . "pesquisar-agendamentos");
exit;
