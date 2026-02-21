<?php
session_start();
ob_start();

include_once 'conexao/conexao.php';
include_once 'config/seguranca.php';
include_once './include/head.php';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id) {
    die('Exame inválido.');
}

// ===============================
// BUSCAR EXAME DE SANGUE
// ===============================
$sql = "
SELECT 
    es.data_exame,
    p.id AS paciente_id,
    p.nome,
    p.cpf,
    p.data_nascimento
FROM exame_sangue es
INNER JOIN pacientes p ON p.id = es.paciente_id
WHERE es.id = :id
";

$stmt = $pdo->prepare($sql);
$stmt->execute([':id' => $id]);
$exame = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$exame) {
    die('Exame não encontrado.');
}

// ===============================
// BUSCAR RESULTADO DO EXAME (SANGUE)
// ===============================
$sqlResultado = "
SELECT 
    re.resultado,
    re.laudo
FROM resultado_exame re
INNER JOIN tipos_exames te ON te.id = re.tipo_exame_id
WHERE 
    re.paciente_id = :paciente_id
    AND te.nome = 'Sangue'
ORDER BY re.id DESC
LIMIT 1
";

$stmtResultado = $pdo->prepare($sqlResultado);
$stmtResultado->execute([
    ':paciente_id' => $exame['paciente_id']
]);

$resultado = $stmtResultado->fetch(PDO::FETCH_ASSOC);

include_once './include/menu.php';
?>

<title>Resultado – Exame de Sangue</title>

<style>
body {
    font-family: Arial;
}

.container {
    max-width: 800px;
    margin: auto;
}

.header {
    text-align: left;
    margin-bottom: 30px;
}

.header img {
    height: 90px;
}
.titulo {
    margin-left: 80px;
    margin-top: -50px;
}
.bloco {
    border: 1px solid #6c757d;
    padding: 15px;
    min-height: 120px;
}

/* =========================
   IMPRESSÃO
========================= */
@media print {

    .no-print,
    nav,
    header,
    footer,
    .btn {
        display: none !important;
    }

    body {
        margin: 0;
    }

    .container {
        max-width: 100%;
    }
}
</style>
</style>

</head>
<body>

<div class="container">

    <div class="header">
        <img src="<?= URL ?>assets/images/logo2.png">
        <h3 class="titulo">Laboratório de Análises Clínicas</h3>
    </div>

    <hr>

    <p><strong>Paciente:</strong> <?= $exame['nome'] ?></p>
    <p><strong>CPF:</strong> <?= $exame['cpf'] ?></p>
    <p><strong>Data de Nascimento:</strong> <?= date('d/m/Y', strtotime($exame['data_nascimento'])) ?></p>
    <p><strong>Data do Exame:</strong> <?= date('d/m/Y', strtotime($exame['data_exame'])) ?></p>

    <hr>

    <h4>Resultado do Exame de Sangue</h4>
    <div class="form-control bloco">
        <?= nl2br($resultado['resultado'] ?? 'Resultado não informado.') ?>
    </div>
    <br>
    <h4>Laudo Médico</h4>
    <div class="form-control bloco">
        <?= nl2br($resultado['laudo'] ?? 'Laudo não informado.') ?>
    </div>
    <br>
    <div class="no-print">
        <button class="btn btn-primary" onclick="window.print()">🖨️ Imprimir</button>
        <a href="<?= URL ?>exame-sangue-analise" class="btn btn-secondary">
    Voltar
</a>
    </div>

</div>

</body>
</html>