<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pedido Confirmado</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="confirmation-container">
        <h1>Pedido Confirmado com Sucesso!</h1>
        <p>Obrigado por realizar seu pedido. Estamos processando e logo estará pronto!</p>
        <a href="menu.php" class="back-button">Voltar ao Cardápio</a>
    </div>
</body>
</html>
