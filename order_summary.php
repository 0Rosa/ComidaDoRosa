<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

include 'db_connection.php';

$user_id = $_SESSION['user_id'];

// Recuperar itens do pedido
$sql_order = "
    SELECT ip.id, i.nome, ip.quantidade, ip.preco, (ip.quantidade * ip.preco) AS total_item
    FROM tb_itens_pedido ip
    JOIN tb_itens i ON ip.idItem = i.id
    WHERE ip.idUsuario = ? AND ip.finalizado = FALSE";
$stmt_order = $conn->prepare($sql_order);
$stmt_order->bind_param("i", $user_id);
$stmt_order->execute();
$result_order = $stmt_order->get_result();

$order_items = [];
$total_price = 0;

if ($result_order->num_rows > 0) {
    while ($row = $result_order->fetch_assoc()) {
        $order_items[] = $row;
        $total_price += $row['total_item'];
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resumo do Pedido</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <!-- Header com logo -->
    <header>
        <img src="ComidaDasRosa.png" alt="Logo" class="logo"> <!-- Caminho da logo -->
    </header>

    <!-- Resumo do Pedido -->
    <div class="order-summary-container">
        <h1>Resumo do Pedido</h1>
        <table class="order-summary-table">
            <thead>
                <tr>
                    <th>Item</th>
                    <th>Quantidade</th>
                    <th>Preço Unitário</th>
                    <th>Total</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($order_items) > 0): ?>
                    <?php foreach ($order_items as $item): ?>
                        <tr>
                            <td><?= htmlspecialchars($item['nome']) ?></td>
                            <td>
                                <form action="update_quantity.php" method="POST" class="update-quantity-form">
                                    <input type="hidden" name="item_id" value="<?= $item['id'] ?>">
                                    <input type="number" name="quantity" value="<?= $item['quantidade'] ?>" min="1">
                                    <button type="submit">Atualizar</button>
                                </form>
                            </td>
                            <td>R$ <?= number_format($item['preco'], 2, ',', '.') ?></td>
                            <td>R$ <?= number_format($item['total_item'], 2, ',', '.') ?></td>
                            <td>
                                <form action="remove_item.php" method="POST">
                                    <input type="hidden" name="item_id" value="<?= $item['id'] ?>">
                                    <button type="submit" class="remove-button">Remover</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5">Seu pedido está vazio.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
        <div class="total-price">
            <h2>Total Geral: R$ <?= number_format($total_price, 2, ',', '.') ?></h2>
        </div>
        <form action="finalize_order.php" method="POST">
            <button type="submit" class="finalize-order-button">Confirmar Pedido</button>
        </form>
        <a href="menu.php" class="back-button">Voltar ao Cardápio</a>
    </div>
</body>
</html>
