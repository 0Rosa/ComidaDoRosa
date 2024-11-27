<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

include 'db_connection.php';

$user_id = $_SESSION['user_id'];

// Atualizar o status dos itens do pedido para finalizado
$sql_finalize = "UPDATE tb_itens_pedido SET finalizado = TRUE WHERE idUsuario = ? AND finalizado = FALSE";
$stmt_finalize = $conn->prepare($sql_finalize);
$stmt_finalize->bind_param("i", $user_id);

if ($stmt_finalize->execute()) {
    header("Location: confirmation.php");
} else {
    echo "Erro ao finalizar o pedido: " . $conn->error;
}

$conn->close();
?>
