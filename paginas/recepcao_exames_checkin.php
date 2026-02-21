<?php
session_start();
include_once 'conexao/conexao.php';
include_once 'config/seguranca.php';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if ($id) {

    $sql = "UPDATE agendamentos_exames 
            SET status = 'Em Atendimento' 
            WHERE id = :id";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    $_SESSION['msg'] = [
        'tipo'   => 'success',
        'titulo'=> 'Check-in realizado',
        'texto' => 'Paciente encaminhado para coleta.'
    ];
}

header("Location: " . URL . "recepcao_exames");
exit;
