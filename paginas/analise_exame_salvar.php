<?php
session_start();
include_once 'conexao/conexao.php';
include_once 'config/seguranca.php';

$agendamento_id = $_POST['agendamento_id'] ?? null;

if ($agendamento_id) {

    // Inserir resultado
    $sql = "
        INSERT INTO resultado_exame (
            tipo_exame_id,
            paciente_id,
            data_exame,
            usuario_id,
            resultado,
            laudo
        ) VALUES (
            :tipo_exame,
            :paciente,
            :data,
            :usuario,
            :resultado,
            :laudo
        )
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':tipo_exame' => $_POST['tipo_exame_id'],
        ':paciente'   => $_POST['paciente_id'],
        ':data'       => $_POST['data_exame'],
        ':usuario'    => $_SESSION['id'],
        ':resultado'  => $_POST['resultado'],
        ':laudo'      => $_POST['laudo']
    ]);

    // Atualiza status do exame
    $sql = "
        UPDATE exames
        SET status = 'Realizado', resultado = :resultado
        WHERE paciente_id = :paciente
        AND pedido_exame_id = :exame

        ";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':paciente' => $_POST['paciente_id'],
            ':resultado'   => $_POST['resultado'],
            ':exame' => $agendamento_id
        ]);

    // Atualiza status do agendamento
    $sql = "UPDATE agendamentos_exames SET status = 'Realizado' WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $agendamento_id);
    $stmt->execute();

    $_SESSION['msg'] = [
        'tipo' => 'success',
        'titulo' => 'Exame finalizado',
        'texto' => 'Resultado e laudo salvos com sucesso.'
    ];
}

header("Location: " . URL . "exames-em-analise");
exit;
