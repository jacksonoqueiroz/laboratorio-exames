<?php
session_start();
include_once('conexao/conexao.php');
include_once('config/seguranca.php');

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id) {
    header("Location: " . URL . "pesquisar_exames");
    exit;
}

$stmt = $pdo->prepare("
    UPDATE agendamentos_exames
    SET status = 'Cancelado'
    WHERE id = :id
");
$stmt->execute([':id' => $id]);

$_SESSION['msg'] = '<div class="alert alert-success">
    Agendamento cancelado com sucesso!
</div>';

header("Location: " . URL . "pesquisar-agendamentos");
exit;
