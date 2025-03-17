<?php
include 'config/db.php'; // Include fișierul de configurare al bazei de date

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Preia datele din formular
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);  // Criptare parolă folosind password_hash()
    $email = $_POST['email'];

    try {
        // Prepara interogarea SQL pentru a preveni atacurile SQL Injection
        $stmt = $pdo->prepare("INSERT INTO users (username, password, email) VALUES (:username, :password, :email)");

        // Leagă valorile parametrilor
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $password);
        $stmt->bindParam(':email', $email);

        // Execută interogarea
        if ($stmt->execute()) {
            echo '<p class="success">Înregistrare cu succes!</p>';
        } else {
            echo '<p class="error">Eroare la înregistrare! Vă rugăm să încercați din nou.</p>';
        }
    } catch (PDOException $e) {
        echo '<p class="error">Eroare: ' . $e->getMessage() . '</p>';
    }
}
?>

<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sing Up</title>
    <!-- Link către fișierul CSS -->
    <link rel="stylesheet" href="./assets/index.css"><!-- Dacă fișierul CSS se află în folderul 'css' -->
</head>
<body>
<header class="header">
    <div class="container">
        <div class=" nav__bar--login">
            <!-- Logo cu dimensiuni controlate -->
            <img src="./img/logo__img.svg" alt="Logo Comunitatea Seabrook" class="header__logo" width="150" height="auto">
            
            <!-- Titlul secțiunii -->
            <h1 class="header__title">Start Your Journey – Sign Up Today</h1>

            <!-- Navigare -->
            <ul class="nav__list">
                <li class="nav__item">
                    <a class="nav__link" href="index.php">Home</a>
                </li>
                <li class="nav__item">
                    <a class="nav__link" href="events.php">Events</a>
                </li>
                <li class="nav__item">
                    <a class="nav__link" href="contact.php">Contact</a> <!-- Aș schimba linkul pentru Contact -->
                </li>
                <li class="nav__item">
                    <button class="nav__link--btn" onclick="window.location.href='login.php';">Login</button>
                </li>
                <li class="nav__item">
                    <button class="nav__link--btn" onclick="window.location.href='register.php';">Sing Up</button>
                </li>
            </ul>
        </div>
    </div>
</header>



<div class="register-section">
  <div class="register-content">
    <div class="register-info">
      <h2 class="register-main-text">Register an Account</h2>
      <p class="register-main-paragrath">Sign up now and start using our platform!</p>
      <form method="post" action="register.php">
        <input type="text" name="username" placeholder="User name" required>
        <input type="password" name="password" placeholder="Password" required>
        <input type="email" name="email" placeholder="Email" required>
        <button type="submit">Sign Up</button>
      </form>
    </div>
  </div>
</div>
<footer class="footer">
        <p class="footer__text">&copy; 2025 Comunitatea Seabrook</p>
    </footer>

</body>
</html>
