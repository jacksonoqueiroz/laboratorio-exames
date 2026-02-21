<?php
session_start();
ob_start();

include_once 'conexao/conexao.php';
include_once 'config/seguranca.php';
include_once './include/head.php';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id) {
    header("Location: " . URL . "exame-sangue-analise");
    exit;
}

// ===============================
// BUSCAR EXAME DE SANGUE
// ===============================
$sql = "
SELECT 
    es.id,
    es.paciente_id,
    es.data_exame,
    es.status,
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
    echo "<div class='alert alert-danger m-4'>Exame não encontrado.</div>";
    exit;
}

// ===============================
// BUSCAR ID DO EXAME SANGUE
// ===============================
$stmt = $pdo->prepare("
    SELECT id FROM tipos_exames 
    WHERE nome = 'Sangue' 
    LIMIT 1
");
$stmt->execute();
$tipo_exame_id = $stmt->fetchColumn();

// ===============================
// SALVAR RESULTADO
// ===============================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $resultado = trim($_POST['resultado'] ?? '');
    $laudo     = trim($_POST['laudo'] ?? '');

    try {

        // Inserir resultado na tabela resultado_exame
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
                :data_exame,
                :usuario,
                :resultado,
                :laudo
            )
        ";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':tipo_exame' => $tipo_exame_id,
            ':paciente'   => $exame['paciente_id'],
            ':data_exame' => $exame['data_exame'],
            ':usuario'    => $_SESSION['id'],
            ':resultado'  => $resultado,
            ':laudo'      => $laudo
        ]);

        // Atualiza status do exame de sangue
        $stmt = $pdo->prepare("
            UPDATE exame_sangue 
            SET status = 'Realizado' 
            WHERE id = :id
        ");
        $stmt->execute([':id' => $id]);

        // Atualiza tabela exames (registro geral)
        $stmt = $pdo->prepare("
            UPDATE exames
            SET status = 'Realizado',
                resultado = :resultado
            WHERE paciente_id = :paciente
            AND tipo_exame = 'Sangue'
        ");
        $stmt->execute([
            ':resultado' => $resultado,
            ':paciente'  => $exame['paciente_id']
        ]);

        $_SESSION['msg'] = [
            'tipo'   => 'success',
            'titulo' => 'Exame finalizado',
            'texto'  => 'Resultado e laudo salvos com sucesso.'
        ];

        header("Location: " . URL . "exame_sangue_resultado_imprimir?id=" . $id);
        
        exit;

    } catch (PDOException $e) {

        $_SESSION['msg'] = [
            'tipo'   => 'error',
            'titulo' => 'Erro',
            'texto'  => 'Erro ao salvar o resultado.'
        ];
    }
}
?>

<title>Resultado — Exame de Sangue</title>
</head>

<?php include_once './include/menu.php'; ?>

<div class="container mt-4">

<h4>Resultado — Exame de Sangue</h4>

<div class="card mb-3">
<div class="card-body">

<p><strong>Paciente:</strong> <?= $exame['nome'] ?></p>
<p><strong>CPF:</strong> <?= $exame['cpf'] ?></p>
<p><strong>Data de Nascimento:</strong>
    <?= date('d/m/Y', strtotime($exame['data_nascimento'])) ?>
</p>
<p><strong>Data do Exame:</strong>
    <?= date('d/m/Y', strtotime($exame['data_exame'])) ?>
</p>

</div>
</div>

<form method="post">

<div class="mb-3">
    <label class="form-label">Resultado</label>
    <textarea name="resultado"
              class="form-control"
              rows="4"
              required></textarea>
</div>

<div class="mb-3">
    <label class="form-label">Laudo</label>
    <textarea name="laudo"
              class="form-control"
              rows="6"
              required></textarea>
</div>

<button class="btn btn-success">
    Salvar Resultado
</button>

<a href="<?= URL ?>exame_sangue_resultado_imprimir?id=<?= $exame['id'] ?>"
   target="_blank"
   class="btn btn-outline-primary">
    <i class="bi bi-printer"></i> Imprimir Resultado
</a>

<a href="<?= URL ?>exame-sangue-analise" class="btn btn-secondary">
    Voltar
</a>

</form>

</div>

<?php include_once './include/footer.php'; ?>