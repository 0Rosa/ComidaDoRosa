<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

include 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $item_id = $_POST['item_id'];
    $quantity = intval($_POST['quantity']);

    if ($quantity > 0) {
        $sql_update = "UPDATE tb_itens_pedido SET quantidade = ? WHERE id = ?";
        $stmt_update = $conn->prepare($sql_update);
        $stmt_update->bind_param("ii", $quantity, $item_id);

        if ($stmt_update->execute()) {
            header("Location: order_summary.php");
        } else {
            echo "Erro ao atualizar quantidade: " . $conn->error;
        }
    }
}

$conn->close();
?>
