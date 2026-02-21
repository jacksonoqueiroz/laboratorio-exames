<?php

$host = "localhost";
$user = "root";
$pass = "";
$dbname = "db_consulta";
$port = 3306;

try{
    //Conexão com a porta
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=" . $dbname, $user, $pass);

// try{
// 	$conn = new PDO("mysql:host=$host;dbname=" . $dbname, $user, $pass);
	//echo "Conectado com o banco!";
}catch(PDOException $err){
	echo "Erro: Não conectado! Erro: " . $err->getMessage();
}