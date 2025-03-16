<?php
session_start();
include 'config/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = htmlspecialchars($_POST['username']);
    $password = $_POST['password'];

    // Verifică dacă utilizatorul există
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
    $stmt->execute(['username' => $username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];
        header('Location: index.php');  // Redirecționează la pagina principală
        exit();
    } else {
        echo "Date de autentificare invalide.";
    }
}
?>

<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Autentificare</title>
</head>
<body>
    <h1>Autentificare</h1>
    <form action="auth.php" method="POST">
        <label for="username">Nume utilizator:</label>
        <input type="text" id="username" name="username" required><br><br>

        <label for="password">Parolă:</label>
        <input type="password" id="password" name="password" required><br><br>

        <button type="submit">Autentifică-te</button>
    </form>
</body>
</html>
