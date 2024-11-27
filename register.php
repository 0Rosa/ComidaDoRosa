<?php
include 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $birthdate = $_POST['birthdate'];
    $phone = $_POST['phone'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $cep = $_POST['cep'];
    $street = $_POST['street'];
    $number = $_POST['number'];
    $neighborhood = $_POST['neighborhood'];
    $complement = $_POST['complement'] ?? null;
    $city = $_POST['city'];
    $state = $_POST['state'];

    // Verificar se o e-mail já está cadastrado
    $sql_check = "SELECT email FROM tb_usuario WHERE email = ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param("s", $email);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();

    if ($result_check->num_rows > 0) {
        echo "E-mail já cadastrado.";
        exit;
    }

    // Inserir novo usuário
    $sql_insert = "INSERT INTO tb_usuario (nome, email, data_nascimento, telefone, senha, cep, rua, numero, bairro, complemento, cidade, estado) 
                   VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql_insert);
    $stmt->bind_param("ssssssssssss", $name, $email, $birthdate, $phone, $password, $cep, $street, $number, $neighborhood, $complement, $city, $state);

    if ($stmt->execute()) {
        echo "Cadastro realizado com sucesso!";
        header("Location: login.html");
    } else {
        echo "Erro ao cadastrar usuário: " . $conn->error;
    }

    $stmt->close();
}
$conn->close();
?>
