<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

include 'db_connection.php';

function get_image($img_url) {
    // Se a URL for uma página de galeria, converta para o link direto da imagem
    if (strpos($img_url, 'imgur.com/gallery/') !== false) {
        $image_id = substr($img_url, strrpos($img_url, '/') + 1);
        return 'https://i.imgur.com/' . $image_id . '.jpg'; // ou outro formato, dependendo da imagem
    }
    return $img_url;
}

// Recuperar categorias
$sql_categories = "SELECT * FROM tb_categoria";
$result_categories = $conn->query($sql_categories);

// Verificar se há categorias
if ($result_categories->num_rows > 0) {
    $categories = $result_categories->fetch_all(MYSQLI_ASSOC);
} else {
    $categories = [];
}

// Recuperar itens do cardápio
$sql_items = "SELECT i.id, i.nome, i.descricao, i.foto, i.preco, c.nome AS categoria
              FROM tb_itens i
              JOIN tb_categoria c ON i.idCategoria = c.id";
$result_items = $conn->query($sql_items);

$items_by_category = []; // Array para armazenar itens agrupados por categoria

if ($result_items->num_rows > 0) {
    // Agrupar os itens por categoria
    while ($item = $result_items->fetch_assoc()) {
        // Garantir que o item seja adicionado à categoria correta
        $items_by_category[$item['categoria']][] = $item;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cardápio</title>
    <link rel="stylesheet" href="styles.css">
</head>
<header>
    <img src="ComidaDasRosa.png" alt="Logo Comida da Rosa" class="logo">
</header>

<body>
    <div class="menu-container">
        <h1>Bem-vindo ao Cardápio</h1> <!-- Isso deve aparecer corretamente agora -->
        <a href="logout.php" class="logout">Sair</a>
        
        <?php if (count($categories) > 0): ?>
            <?php foreach ($categories as $category): ?>
                <div class="category">
                    <h2><?= htmlspecialchars($category['nome']) ?></h2>
                    <div class="items">
                        <?php
                        // Verificar se há itens para essa categoria e exibi-los
                        if (isset($items_by_category[$category['nome']])) {
                            foreach ($items_by_category[$category['nome']] as $item):
                        ?>
                                <div class="item">
                                    <img src="<?= get_image($item['foto']) ?>" alt="<?= htmlspecialchars($item['nome']) ?>" class="item-image">
                                    <h3><?= htmlspecialchars($item['nome']) ?></h3>
                                    <p><?= htmlspecialchars($item['descricao']) ?></p>
                                    <p class="price">R$ <?= number_format($item['preco'], 2, ',', '.') ?></p>
                                    <a href="item_details.php?id=<?= $item['id'] ?>" class="details-button">Ver Detalhes</a>
                                </div>
                        <?php 
                            endforeach;
                        } else {
                            echo "<p>Não há itens para esta categoria.</p>";
                        }
                        ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Nenhuma categoria encontrada.</p>
        <?php endif; ?>
    </div>
</body>
</html>
