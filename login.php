<?php
session_start();
include 'config/db.php'; // Include fișierul de conectare la baza de date

// Verifică dacă formularul a fost trimis
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Protejează datele de intrare
    $username = htmlspecialchars($username);
    $password = htmlspecialchars($password);

    // Verifică dacă utilizatorul există în baza de date
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username LIMIT 1");
    $stmt->execute(['username' => $username]);

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        // Autentificare reușită
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        header('Location: events.php');
        exit();
    } else {
        $error_message = "Autentificare eșuată! Utilizator sau parolă incorectă.";
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

    <?php
    if (isset($error_message)) {
        echo "<p style='color: red;'>$error_message</p>";
    }
    ?>

    <form action="login.php" method="POST">
        <label for="username">Nume utilizator:</label>
        <input type="text" id="username" name="username" required><br>

        <label for="password">Parolă:</label>
        <input type="password" id="password" name="password" required><br>

        <button type="submit">Autentificare</button>
    </form>

</body>
</html>
