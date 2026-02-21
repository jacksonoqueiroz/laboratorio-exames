<?php
session_start();
include_once 'conexao/conexao.php';
// include_once '../config/seguranca.php';
include_once './include/head.php';

// BUSCAR PEDIDOS
$sql = "
SELECT 
    p.id,
    p.data_pedido,
    p.status_geral,
    pac.nome AS paciente,
    m.nome AS medico
FROM pedidos_exames p
JOIN pacientes pac ON pac.id = p.paciente_id
JOIN medicos m ON m.id = p.medico_id
ORDER BY p.data_pedido DESC
";

$stmt = $pdo->prepare($sql);
$stmt->execute();
$pedidos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<title>Laboratório | Pedidos de Exames</title>
</head>

<?php include_once './include/menu.php'; ?>

<div class="container mt-4">

<h4>Pedidos de Exames</h4>

<table class="table table-hover table-bordered align-middle">
<thead class="table-light">
<tr>
    <th>Id</th>
    <th>Paciente</th>
    <th>Médico</th>
    <th>Data</th>
    <th>Status</th>
    <th width="120">Ação</th>
</tr>
</thead>
<tbody>

<?php if (count($pedidos) == 0): ?>
<tr>
    <td colspan="6" class="text-center text-muted">
        Nenhum pedido encontrado
    </td>
</tr>
<?php endif; ?>

<?php foreach ($pedidos as $p): ?>
<tr>
    <td><?= $p['id'] ?></td>
    <td><?= $p['paciente'] ?></td>
    <td><?= $p['medico'] ?></td>
    <td><?= date('d/m/Y H:i', strtotime($p['data_pedido'])) ?></td>
    <td>
        <?php
        $badge = match ($p['status_geral']) {
            'Aberto'    => 'warning',
            'Concluido' => 'success',
            'Cancelado' => 'danger',
            default     => 'secondary'
        };
        ?>
        <span class="badge bg-<?= $badge ?>">
            <?= $p['status_geral'] ?>
        </span>
    </td>
    <td class="text-center">
        <a href="ver_pedido.php?id=<?= $p['id'] ?>" 
           class="btn btn-outline-primary btn-sm">
            Abrir
        </a>
    </td>
</tr>
<?php endforeach; ?>

</tbody>
</table>

</div>

<?php include_once './include/footer.php'; ?>
