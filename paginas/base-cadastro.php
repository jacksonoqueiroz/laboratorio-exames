<?php

session_start();

include_once ('conexao/conexao.php');

if ((!isset($_SESSION['id'])) AND (!isset($_SESSION['nome']))) {
	$_SESSION['msg'] = '<div class="alert alert-danger" role="alert">É necessário realizar o Login, para acessar o sistema!</div>';	
	header("Location: login.php");
}
//Inclui o head
include_once './include/head.php';
?>
<title>Cadastro</title>
</head>
<?php

//Inclui o menu
include_once './include/menu.php';
 //echo "Página cadastro.";
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Cadastro - Tela Moderna</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body {
      /*background: linear-gradient(to right, #74ebd5, #acb6e5);
      height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;*/
    }
    .card {
      margin-top: 20px;
      border: none;
      border-radius: 20px;
      box-shadow: 0 10px 25px rgba(0,0,0,0.1);

    }
    .form-control {
      border-radius: 10px;
    }
    .input-group-text {
      background-color: #f1f1f1;
      border: none;
      border-radius: 10px 0 0 10px;
    }
    .btn-primary {
      border-radius: 10px;
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-6 col-lg-5">
        <div class="card p-4">
          <h2 class="text-center mb-4">Cadastrar Aluno</h2>

          <?php
          	$dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

          	if (!empty($dados['cad_aluno'])) {
          			// echo "<pre>";
          			// var_dump($dados);
          			// echo "</pre>";
          			$query_aluno = "INSERT INTO alunos (nome, telefone, instrumento, ficha) VALUES (:nome, :telefone, :instrumento, :ficha)";
          			$cad_aluno = $conn->prepare($query_aluno);
          			$cad_aluno->bindParam(':nome', $dados['nome'], PDO::PARAM_STR);
          			$cad_aluno->bindParam(':telefone', $dados['telefone'], PDO::PARAM_STR);
          			$cad_aluno->bindParam(':instrumento', $dados['instrumento'], PDO::PARAM_STR);
          			$cad_aluno->bindParam(':ficha', $dados['ficha'], PDO::PARAM_STR);
          			$cad_aluno->execute();
          			if ($cad_aluno->rowCount()) {
          				$_SESSION['msg'] = "<div class='alert alert-success' role='alert'>Registro Cadastrado!</div>";

          			}
          	}
          	if (isset($_SESSION['msg'])) {
        echo $_SESSION['msg'];
        unset($_SESSION['msg']);
    }

          ?>


          <form name="cad-aluno" method="POST" action="">
            <div class="mb-3 input-group">
              <span class="input-group-text"><i class="bi bi-person-fill"></i></span>
              <input type="text" name="nome" id="nome" class="form-control" placeholder="Nome completo" required>
            </div>
            <div class="mb-3 input-group">
              <span class="input-group-text"><i class="bi bi-whatsapp"></i></span>
              <input type="text" name="telefone" id="telefone" class="form-control" placeholder="Telefone" required>
            </div>
            <!-- <div class="mb-3 input-group">
              <span class="input-group-text"><i class="bi bi-music-note-beamed"></i></span>
              <input type="text" class="form-control" placeholder="Instrumento" required>
            </div> -->
            <div class="mb-3 input-group">
  				<span class="input-group-text"><i class="bi bi-music-note-beamed"></i></span>
  					<select class="form-select" name="instrumento" required>
    					<option value="">Selecione um instrumento</option>
    					<option value="clarinete">Clarinete</option>
    					<option value="eufonio">Eufônio</option>
    					<option value="flauta">Flauta</option>
    					<option value="flughellhorn">Flughellhorn</option>    					
    					<option value="sax_alto">Sax Alto</option>
    					<option value="sax_soprano">Sax Soprano</option>
    					<option value="sax_tenor">Sax Tenor</option>
    					<option value="tuba">Tuba</option>
    					<option value="trompa">Trompa</option>
    					<option value="trompete">Trompete</option>
    					<option value="trombone">Trombone</option>
    					<option value="violino">Violino</option>
    					<option value="viola">Viola</option>
    					<option value="oboe">Oboé</option>
    					<option value="outro">Outro</option>
  					</select>
			</div>
            <div class="mb-3 input-group">
              <span class="input-group-text"><i class="bi bi-list-check"></i></span>
              <input type="number" name="ficha" id="ficha" class="form-control" placeholder="Ficha" required>
            </div>
            <div class="d-grid">
              <input type="submit" name="cad_aluno" value="Cadastrar" class="btn btn-primary">
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</body>
</html>


<?php
//Inclui o footer
include_once './include/footer.php';
?>