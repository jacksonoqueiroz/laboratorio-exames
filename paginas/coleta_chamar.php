<?php
session_start();
include_once 'conexao/conexao.php';
include_once 'config/seguranca.php';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if ($id) {

    // Buscar o exame relacionado
    $sql = "
        SELECT tipo_exame_id 
        FROM agendamentos_exames 
        WHERE id = :id
    ";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $dados = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($dados) {

        // Atualiza status do agendamento
        $sql = "
            UPDATE agendamentos_exames 
            SET status = 'Em Analise' 
            WHERE id = :id
        ";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        // Atualiza status do exame
        $sql = "
            UPDATE exames 
            SET status = 'Em Análise'
            WHERE id = :exame
        ";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':exame', $dados['tipo_exame_id']);
        $stmt->execute();

        $_SESSION['msg'] = [
            'tipo'   => 'success',
            'titulo'=> 'Paciente chamado',
            'texto' => 'Coleta iniciada com sucesso.'
        ];
    }
}

header("Location: " . URL . "coleta-exames");
exit;
