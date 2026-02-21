<?php
session_start();

include_once ('conexao/conexao.php');
include_once ('config/seguranca.php');


// CADASTRAR TIPO DE EXAME
if(isset($_POST['salvar'])){
    $nome = trim($_POST['nome']);
    $precisa = $_POST['precisa_agendamento'];

    if(!empty($nome)){
        $stmt = $pdo->prepare("
            INSERT INTO tipos_exames (nome, precisa_agendamento)
            VALUES (:nome, :precisa)
        ");
        $stmt->execute([
            ':nome' => $nome,
            ':precisa' => $precisa
        ]);
    }
}

// LISTAGEM
$tipos = $pdo->query("
    SELECT * FROM tipos_exames 
    ORDER BY nome
")->fetchAll(PDO::FETCH_ASSOC);

// Inclui o head
include_once './include/head.php';
?>
<title>Tipos de Exames</title>
</head>

<?php
// Inclui o menu
include_once './include/menu.php';
?>

<div class="container-fluid mt-4">

    <div class="row">

        <!-- FORMULÁRIO -->
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <strong>Cadastrar Tipo de Exame</strong>
                </div>

                <div class="card-body">
                    <form method="post">

                        <div class="form-group">
                            <label>Nome do Exame</label>
                            <input type="text" name="nome" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label>Precisa Agendamento?</label>
                            <select name="precisa_agendamento" class="form-control">
                                <option value="1">Sim</option>
                                <option value="0">Não</option>
                            </select>
                        </div>

                        <button type="submit" name="salvar" class="btn btn-success btn-block">
                            <i class="bi bi-check-circle"></i> Salvar
                        </button>

                    </form>
                </div>
            </div>
        </div>

        <!-- LISTAGEM -->
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-dark text-white">
                    <strong>Tipos de Exames Cadastrados</strong>
                </div>

                <div class="card-body p-0">
                    <table class="table table-striped mb-0">
                        <thead class="thead-light">
                            <tr>
                                <th>Exame</th>
                                <th>Agendamento</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>

                        <?php if(count($tipos) > 0): ?>
                            <?php foreach($tipos as $t): ?>
                                <tr>
                                    <td><?= htmlspecialchars($t['nome']) ?></td>

                                    <td>
                                        <?php if($t['precisa_agendamento']): ?>
                                            <span class="badge badge-info">Obrigatório</span>
                                        <?php else: ?>
                                            <span class="badge badge-secondary">Não precisa</span>
                                        <?php endif; ?>
                                    </td>

                                    <td>
                                        <?php if($t['ativo']): ?>
                                            <span class="badge badge-success">Ativo</span>
                                        <?php else: ?>
                                            <span class="badge badge-danger">Inativo</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="3" class="text-center text-muted">
                                    Nenhum tipo de exame cadastrado
                                </td>
                            </tr>
                        <?php endif; ?>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>

</div>

<?php
// Inclui o footer
include_once './include/footer.php';
?>
