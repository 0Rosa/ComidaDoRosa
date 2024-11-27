<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

include 'db_connection.php';

// Verificar se o ID do item foi passado via GET
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "ID do item não fornecido.";
    exit();
}

$item_id = $_GET['id'];

// Recuperar detalhes do item
$sql_item = "SELECT i.id, i.nome, i.descricao, i.foto, i.preco, c.nome AS categoria
             FROM tb_itens i
             JOIN tb_categoria c ON i.idCategoria = c.id
             WHERE i.id = ?";
$stmt = $conn->prepare($sql_item);
$stmt->bind_param("i", $item_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $item = $result->fetch_assoc();
} else {
    echo "Item não encontrado.";
    exit();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalhes do Item</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <!-- Header com logo -->
    <header>
        <img src="ComidaDasRosa.png" alt="Logo" class="logo"> <!-- Caminho da logo -->
    </header>

    <!-- Container de Detalhes do Item -->
    <div class="item-details-container">
        <h1><?= htmlspecialchars($item['nome']) ?></h1>
        <div class="item-details">
            <!-- Exibir imagem do item -->
            <img src="<?= htmlspecialchars($item['foto']) ?>" alt="<?= htmlspecialchars($item['nome']) ?>" class="item-details-image">
            <div class="item-info">
                <h2>Categoria: <?= htmlspecialchars($item['categoria']) ?></h2>
                <p><?= htmlspecialchars($item['descricao']) ?></p>
                <p class="price">Preço: R$ <?= number_format($item['preco'], 2, ',', '.') ?></p>
                <form action="add_to_order.php" method="POST">
                    <input type="hidden" name="item_id" value="<?= $item['id'] ?>">
                    <label for="quantity">Quantidade:</label>
                    <input type="number" id="quantity" name="quantity" min="1" value="1" required>
                    <button type="submit" class="add-to-order-button">Adicionar ao Pedido</button>
                </form>
            </div>
        </div>
        <a href="menu.php" class="back-button">Voltar ao Cardápio</a>
    </div>
</body>
</html>
