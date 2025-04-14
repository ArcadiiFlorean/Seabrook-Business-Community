<?php
session_start();
include 'config/db.php'; // Include fișierul de conectare la baza de date

// Verifică dacă formularul a fost trimis
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Elimină spațiile de la începutul și sfârșitul valorilor introduse
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Protejează datele de intrare
    $username = htmlspecialchars($username);
    $password = htmlspecialchars($password);

    // Verifică dacă utilizatorul există în baza de date
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username LIMIT 1");
    $stmt->execute(['username' => $username]);

    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Verifică dacă utilizatorul a fost găsit
    if ($user) {
        // Verifică dacă parola introdusă este corectă
        if (password_verify($password, $user['password'])) {
            // Parola este corectă, autentificare reușită
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            header('Location: events.php');
            exit();
        } else {
            echo "Parola este incorectă!<br>";
        }
    } else {
        echo "Utilizatorul nu există.<br>";
    }
} // ← aceasta era acolada lipsă
?>

<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Autentificare</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body class="login-body">
<header class="header">
    <div class="container">
        <div class="nav__bar--login">
            <!-- Logo cu dimensiuni controlate -->
            <img src="./img/logo__img.svg" alt="Logo Comunitatea Seabrook" class="header__logo" width="150" height="auto">
            
            <!-- Titlul secțiunii -->
            <h1 class="header__title">Your Seabrook Business Network Awaits</h1>

            <!-- Navigare -->
            <ul class="nav__list">
                <li class="nav__item">
                    <a class="nav__link" href="index.php">Home</a>
                </li>
                <li class="nav__item">
                    <a class="nav__link" href="events.php">Events</a>
                </li>
                <li class="nav__item">
                    <a class="nav__link" href="contact.php">Contact</a>
                </li>
                <li class="nav__item">
                    <button class="nav__link--btn" onclick="window.location.href='login.php';">Login</button>
                </li>
                <li class="nav__item">
                    <button class="nav__link--btn" onclick="window.location.href='register.php';">Sign Up</button>
                </li>
            </ul>
        </div>
    </div>
</header>
<section class="login-section">
    <div class="container">
        <div class="login-content">
            <div class="login-info">
                <h1 class="login-main-text">Login to <span class="span-login">Seabrook</span> Community</h1>
                <p class="login-main-paragrath">Log in to access events, connect with neighbors, and stay updated on local news. Join discussions, explore volunteer opportunities, and be part of exciting community projects. Let’s make Seabrook stronger together!</p>
                <div class="login-social">
                    <ul class="social-login-items">
                        <li class="social-list">
                            <a href="#" class="social-link-items">
                                <!-- Facebook SVG -->
                            </a>
                        </li>
                        <li class="social-list">
                            <a href="#" class="social-link">
                                <!-- WhatsApp SVG -->
                            </a>
                        </li>
                        <li class="social-list">
                            <a href="#" class="social-link">
                                <!-- Twitter SVG -->
                            </a>
                        </li>
                        <li class="social-list">
                            <a href="#" class="social-link">
                                <!-- Instagram SVG -->
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            
            <div class="login-form">
            <form method="POST" action="login.php">
    <label for="username">Username:</label>
    <input type="text" name="username" id="username" required>
    
    <label for="password">Parola:</label>
    <input type="password" name="password" id="password" required>
    
    <button type="submit">Logare</button>
</form>

            </div>
        </div>
    </div>
</section>
</body>
</html>
