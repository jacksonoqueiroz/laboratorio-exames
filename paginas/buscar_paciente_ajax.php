<?php
include_once('conexao/conexao.php');

$termo = $_GET['termo'] ?? '';

$stmt = $pdo->prepare("
    SELECT *
    FROM pacientes
    WHERE cpf = :termo
       OR nome LIKE :nome
    LIMIT 1
");
$stmt->execute([
    ':termo' => $termo,
    ':nome'  => "%$termo%"
]);

echo json_encode($stmt->fetch(PDO::FETCH_ASSOC));
