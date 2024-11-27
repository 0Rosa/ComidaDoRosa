<?php
$servername = "localhost"; // ou o endereço do seu servidor MySQL
$username = "root"; // ou o nome de usuário configurado
$password = ""; // senha configurada para o MySQL
$database = "restaurante"; // nome do banco de dados

// Criar conexão
$conn = new mysqli($servername, $username, $password, $database);

// Verificar conexão
if ($conn->connect_error) {
    die("Erro na conexão com o banco de dados: " . $conn->connect_error);
}
?>
