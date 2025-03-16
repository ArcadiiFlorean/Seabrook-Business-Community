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
            echo "Înregistrare cu succes!";
        } else {
            echo "Eroare la înregistrare!";
        }
    } catch (PDOException $e) {
        echo "Eroare: " . $e->getMessage();
    }
}
?>

<form method="post" action="register.php">
    <input type="text" name="username" placeholder="Nume utilizator" required>
    <input type="password" name="password" placeholder="Parola" required>
    <input type="email" name="email" placeholder="Email" required>
    <button type="submit">Înregistrează-te</button>
</form>
