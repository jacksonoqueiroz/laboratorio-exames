<?php

ob_start();
include_once ('conexao/conexao.php');

include './include/head-login.php';


?>


<body class="text-center">
	<?php

	?>
	 <form method="POST" class="form-signin" action="">
	 	<?php
		$dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

		//Exemplo criptografar senha
		//	echo password_hash(1234, PASSWORD_DEFAULT);
		
		if (!empty($dados['SendLogin'])) {
			// echo "<pre>";
			// var_dump($dados);
			// echo "</pre>";
			$query_usuario = "SELECT id, usuario, nome, senha, imagem, perfil
					FROM usuario_lab 
					WHERE usuario =:usuario
					LIMIT 1";
			$result_usuario = $pdo->prepare($query_usuario);
			$result_usuario->bindParam(':usuario', $dados['usuario'], PDO::PARAM_STR);
			$result_usuario->execute();

			if (($result_usuario) AND ($result_usuario->rowCount() != 0)) {
				$row_usuario = $result_usuario->fetch(PDO::FETCH_ASSOC);
				// echo "<pre>";
				// var_dump($row_usuario);
				// echo "</pre>";
				if (password_verify($dados['senha'], $row_usuario['senha'])) {
					$_SESSION['id'] = $row_usuario['id'];
					$_SESSION['nome'] = $row_usuario['nome'];
					$_SESSION['imagem'] = $row_usuario['imagem'];
					$_SESSION['perfil'] = $row_usuario['perfil'];
					header("Location: " . URL . "home");
				}else{
					$_SESSION['msg'] = '<div class="alert alert-danger" role="alert">Usuário ou senha inválidos!</div>';
				}
			}else{
				$_SESSION['msg'] = '<div class="alert alert-danger" role="alert">Usuário ou senha inválidos!</div>';
			}
			
			
		}

		
	?>
	 	<img class="mb-4" src="assets/images/logo2.png" alt="" width="72" height="72">
  			<h1 class="h3 mb-3 font-weight-normal">Área restrita</h1>
			 	<?php
			 	if (isset($_SESSION['msg'])) {
					echo $_SESSION['msg'];
					unset($_SESSION['msg']);
				}
				?>
		  <label for="usuario" class="sr-only">Usuário</label>
			  <input name="usuario" type="text" id="usuario" class="form-control mb-4" placeholder="Digite o Usuário" value="<?php if (isset($dados['usuario'])) {
			  	echo $dados['usuario'];
			  } ?>" required autofocus>
			  <label for="senha" class="sr-only">Senha</label>
			  <div class="senha">
			  	<input name="senha" type="password" id="senha" class="form-control mb-4" placeholder="Digite a Senha" required><!--<i class="bi bi-eye-fill" id="btn-senha" onclick="mostrarSenha()"></i>-->
			  </div>
			  <input name="SendLogin" type="submit" value="Acessar" class="btn btn-lg btn-primary btn-block">
			  <p><a href="#">Esqueci a senha</a></p>
	</form>


	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

	<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>

	<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.min.js" integrity="sha384-w1Q4orYjBQndcko6MimVbzY0tgp4pWB4lZ7lr30WKz0vr/aWKhXdBNmNb5D92v7s" crossorigin="anonymous"></script>
</body>
</html>

