<?php
include_once('../conexao/conexao.php');

$termo = filter_input(INPUT_GET, 'q', FILTER_SANITIZE_SPECIAL_CHARS);

if (!$termo || strlen($termo) < 3) {
    echo json_encode([]);
    exit;
}

$sql = "SELECT id, nome, cpf, data_nascimento 
        FROM pacientes 
        WHERE status = 'Ativo'
        AND nome LIKE :nome
        ORDER BY nome
        LIMIT 10";

$stmt = $pdo->prepare($sql);
$like = "%$termo%";
$stmt->bindParam(':nome', $like);
$stmt->execute();

echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
