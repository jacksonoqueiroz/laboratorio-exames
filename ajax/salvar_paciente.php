<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

include_once '../conexao/conexao.php';

header('Content-Type: application/json; charset=utf-8');

// ===============================
// RECEBER DADOS
// ===============================
$nome             = trim($_POST['nome'] ?? '');
$cpf              = trim($_POST['cpf'] ?? '');
$data_nascimento  = $_POST['data_nascimento'] ?? '';
$tipo_atendimento = $_POST['tipo_atendimento'] ?? '';

if (!$nome || !$cpf || !$data_nascimento || !$tipo_atendimento) {
    echo json_encode(['erro' => 'Preencha todos os campos obrigatórios.']);
    exit;
}

// ===============================
// VERIFICAR CPF DUPLICADO
// ===============================
$verifica = $pdo->prepare("
    SELECT id 
    FROM pacientes 
    WHERE cpf = :cpf
");
$verifica->execute([':cpf' => $cpf]);

if ($verifica->rowCount() > 0) {
    echo json_encode(['erro' => 'CPF já cadastrado.']);
    exit;
}

// ===============================
// INSERIR PACIENTE
// ===============================
$stmt = $pdo->prepare("
    INSERT INTO pacientes
        (nome, cpf, data_nascimento, tipo_atendimento, status)
    VALUES
        (:nome, :cpf, :data_nascimento, :tipo_atendimento, 'Ativo')
");

$stmt->execute([
    ':nome'             => $nome,
    ':cpf'              => $cpf,
    ':data_nascimento'  => $data_nascimento,
    ':tipo_atendimento' => $tipo_atendimento
]);

$id = $pdo->lastInsertId();

// ===============================
// RETORNO PARA O MODAL
// ===============================
echo json_encode([
    'id'              => $id,
    'nome'            => $nome,
    'cpf'             => $cpf,
    'data_nascimento' => date('d/m/Y', strtotime($data_nascimento)),
    'tipo_atendimento'=> $tipo_atendimento
]);