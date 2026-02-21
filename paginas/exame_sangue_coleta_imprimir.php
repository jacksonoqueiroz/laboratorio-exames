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
// BUSCAR DADOS DO EXAME
// ===============================
$sql = "
SELECT 
    es.data_exame,
    p.nome,
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

$dataAtual = date('d/m/Y H:i');
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<title>Etiquetas — Coleta Exame de Sangue</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

<style>
body {
    font-family: Arial, sans-serif;
}

/* =========================
   ETIQUETAS
========================= */
.etiqueta {
    width: 7cm;
    height: 4cm;
    border: 1px solid #000;
    padding: 6px;
    margin-bottom: 8px;
    font-size: 12px;
}

.etiqueta strong {
    font-size: 13px;
}

.container {
    width: 8cm;
    margin: auto;
}

/* =========================
   BOTÕES
========================= */
.no-print {
    text-align: center;
    margin-bottom: 20px;
}

/* =========================
   IMPRESSÃO
========================= */
@media print {
    .no-print {
        display: none;
    }

    body {
        margin: 0;
    }

    .etiqueta {
        page-break-inside: avoid;
    }
}
</style>
</head>

<body>

<div class="no-print">
    <button onclick="window.print()" class="btn btn-primary">
        <i class="bi bi-printer"></i> Imprimir Etiquetas
    </button>

    <button onclick="window.close()" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Fechar
    </button>
</div>

<div class="container">

<?php for ($i = 1; $i <= 4; $i++): ?>
    <div class="etiqueta">
        <strong><?= htmlspecialchars($exame['nome']) ?></strong><br>

        Nasc.: <?= date('d/m/Y', strtotime($exame['data_nascimento'])) ?><br>
        Exame: <?= date('d/m/Y', strtotime($exame['data_exame'])) ?><br>
        Coleta: <?= $dataAtual ?>
    </div>
<?php endfor; ?>

</div>

</body>
</html>