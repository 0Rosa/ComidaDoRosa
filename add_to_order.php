<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

include 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $item_id = $_POST['item_id'];
    $quantity = intval($_POST['quantity']);

    // Recuperar preço do item
    $sql_price = "SELECT preco FROM tb_itens WHERE id = ?";
    $stmt_price = $conn->prepare($sql_price);
    $stmt_price->bind_param("i", $item_id);
    $stmt_price->execute();
    $result_price = $stmt_price->get_result();

    if ($result_price->num_rows > 0) {
        $item = $result_price->fetch_assoc();
        $price = $item['preco'];
    } else {
        echo "Item não encontrado.";
        exit();
    }

    // Inserir no pedido
    $sql_order = "INSERT INTO tb_itens_pedido (idUsuario, idItem, quantidade, preco) VALUES (?, ?, ?, ?)";
    $stmt_order = $conn->prepare($sql_order);
    $stmt_order->bind_param("iiid", $user_id, $item_id, $quantity, $price);

    if ($stmt_order->execute()) {
        header("Location: order_summary.php");
    } else {
        echo "Erro ao adicionar item ao pedido: " . $conn->error;
    }

    $stmt_order->close();
}

$conn->close();
?>
