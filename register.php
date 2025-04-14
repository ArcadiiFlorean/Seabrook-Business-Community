<?php
session_start();
include 'config/db.php'; // Include fișierul de conectare la baza de date

// Verifică dacă formularul a fost trimis
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obține datele din formular
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $role = 'user'; // Poți seta rolul implicit ca 'user'

    // Protejează datele de intrare
    $username = htmlspecialchars($username);
    $email = htmlspecialchars($email);
    $password = htmlspecialchars($password);

    // Verifică dacă utilizatorul există deja
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username OR email = :email LIMIT 1");
    $stmt->execute(['username' => $username, 'email' => $email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // Dacă utilizatorul există deja, arată un mesaj de eroare
        echo "Utilizatorul sau email-ul există deja!";
    } else {
        // ✅ Criptează parola corect
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // ✅ Inserare utilizator în baza de date
        $sql = "INSERT INTO users (username, email, password) VALUES (:username, :email, :password)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'username' => $username,
            'email' => $email,
            'password' => $hashedPassword
        ]);

        echo "Înregistrare reușită! Poți acum să te loghezi.";
    }
}
?>

<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Înregistrare</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<section class="register-section">
    <div class="container">
        <div class="register-content">
            <h1>Înregistrează-te</h1>
            <form class="form-register-content" method="POST">
                <label for="username">Username:</label>
                <input type="text" name="username" id="username" required>
                
                <label for="email">Email:</label>
                <input type="email" name="email" id="email" required>
                
                <label for="password">Parola:</label>
                <input type="password" name="password" id="password" required>
                
                <button type="submit">Înregistrare</button>
            </form>
        </div>
    </div>
</section>
</body>
</html>
