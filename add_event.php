<?php
session_start();
include 'config/db.php';

// Verificăm dacă utilizatorul este autentificat
if (!isset($_SESSION['user_id'])) {
    echo "Trebuie să fiți autentificat pentru a adăuga un eveniment.";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = htmlspecialchars($_POST['title']);
    $description = htmlspecialchars($_POST['description']);
    $date = $_POST['date'];
    $user_id = $_SESSION['user_id']; // Asociem evenimentul cu utilizatorul

    // Salvăm evenimentul în baza de date
    $stmt = $pdo->prepare("INSERT INTO events (title, description, date, user_id) VALUES (:title, :description, :date, :user_id)");
    $stmt->execute([
        'title' => $title,
        'description' => $description,
        'date' => $date,
        'user_id' => $user_id
    ]);

    echo "Evenimentul a fost adăugat cu succes!";
}
?>

<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adăugare Eveniment</title>
</head>
<body>
    <h1>Adăugare Eveniment</h1>
    <form action="add_event.php" method="POST">
        <label for="title">Titlu Eveniment:</label>
        <input type="text" id="title" name="title" required><br><br>

        <label for="description">Descriere Eveniment:</label>
        <textarea id="description" name="description" required></textarea><br><br>

        <label for="date">Data și Ora:</label>
        <input type="datetime-local" id="date" name="date" required><br><br>

        <button type="submit">Adaugă Eveniment</button>
    </form>
</body>
</html>
