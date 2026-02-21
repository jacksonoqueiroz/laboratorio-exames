<?php

include_once ('conexao/conexao.php');

//ABRIR ESSA PÁGINA QUANDO LOGADO
if ((!isset($_SESSION['id'])) AND (!isset($_SESSION['nome']))) {
	$_SESSION['msg'] = '<div class="alert alert-danger" role="alert">É necessário realizar o Login, para acessar o sistema!</div>';	
	header("Location: login.php");
}

//Inclui o head
include_once './include/head.php';
?>
<title>Home | Laboratório</title>
</head>
<?php

$paginaAtual = basename($_SERVER['REQUEST_URI']);

$stmt = $pdo->prepare("
    SELECT 
        a.id,
        p.nome AS paciente,
        a.horario,
        t.nome AS exame
    FROM agendamentos_exames a
    INNER JOIN tipos_exames t ON t.id = a.tipo_exame_id
    INNER JOIN pacientes p ON p.id = a.paciente_id
    WHERE a.data_exame = CURDATE()
    AND a.status = 'Agendado'
    ORDER BY a.horario
");
$stmt->execute();
$examesHoje = $stmt->fetchAll(PDO::FETCH_ASSOC);


//Inclui o menu
include_once './include/menu.php';
 //echo "Página cadastro.";
?>
<div class="container">
	<div class="row">

<?php
$solicitado = $pdo->query("SELECT COUNT(*) FROM exames WHERE status = 'Solicitado'")->fetchColumn();
$analise = $pdo->query("SELECT COUNT(*) FROM exames WHERE status = 'Em analise'")->fetchColumn();
$realizado = $pdo->query("SELECT COUNT(*) FROM exames WHERE status = 'Realizado'")->fetchColumn();
?>

<div class="col-md-4">
    <div class="card card-status card-warning">
        <div class="card-body">
            <h6>Solicitados</h6>
            <h2><?= $solicitado ?></h2>
        </div>
    </div>
</div>

<div class="col-md-4">
    <div class="card card-status card-primary">
        <div class="card-body">
            <h6>Em Análise</h6>
            <h2><?= $analise ?></h2>
        </div>
    </div>
</div>

<div class="col-md-4">
    <div class="card card-status card-success">
        <div class="card-body">
            <h6>Realizados</h6>
            <h2><?= $realizado ?></h2>
        </div>
    </div>
</div>

</div>
<br><br>
<section>
    <div class="card shadow-sm">
    <div class="card-header bg-primary text-white">
        <i class="bi bi-calendar-check"></i> Exames de Hoje
    </div>

    <div class="card-body">

        <?php if (count($examesHoje) == 0): ?>
            <p class="text-muted mb-0">Nenhum exame agendado para hoje.</p>
        <?php else: ?>

            <div class="table-responsive">
                <table class="table table-sm align-middle">
                    <thead>
                        <tr>
                            <th>Hora</th>
                            <th>Paciente</th>
                            <th>Exame</th>
                            <th class="text-center">Ação</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($examesHoje as $e): ?>
                            <tr>
                                <td><?= $e['horario'] ?></td>
                                <td><?= $e['paciente'] ?></td>
                                <td><?= $e['exame'] ?></td>
                                <td class="text-center">
                                    <a href="<?= URL ?>recepcao_exames?id=<?= $e['id'] ?>"
                                       class="btn btn-success btn-sm">
                                        <i class="bi bi-check2-circle"></i> Check-in
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

        <?php endif; ?>

    </div>
</div>


</section>

<?php

//Inclui o footer
include_once './include/footer.php';
?>