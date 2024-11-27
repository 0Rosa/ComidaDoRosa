<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

include 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $item_id = $_POST['item_id'];

    $sql_remove = "DELETE FROM tb_itens_pedido WHERE id = ?";
    $stmt_remove = $conn->prepare($sql_remove);
    $stmt_remove->bind_param("i", $item_id);

    if ($stmt_remove->execute()) {
        header("Location: order_summary.php");
    } else {
        echo "Erro ao remover item: " . $conn->error;
    }
}

$conn->close();
?>
