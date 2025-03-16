<?php
include 'config/db.php'; // Include fișierul de conectare la baza de date

// Nume utilizator pe care vrei să-l verifici
$username_to_check = 'numele_utilizatorului'; // Înlocuiește cu numele utilizatorului pe care vrei să-l verifici

try {
    // Interoghează baza de date pentru utilizatorul respectiv
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
    $stmt->execute(['username' => $username_to_check]);

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        echo "Utilizator găsit: <br>";
        print_r($user); // Afișează informațiile despre utilizator (parola criptată inclusiv)
    } else {
        echo "Nu a fost găsit niciun utilizator cu acest nume.";
    }
} catch (PDOException $e) {
    echo 'Eroare: ' . $e->getMessage();
}
?>
