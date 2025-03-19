<?php
session_start();
include 'config/db.php'; // Include fișierul de conectare la baza de date

// Verifică dacă utilizatorul este conectat și dacă ID-ul evenimentului este setat
if (!isset($_SESSION['user_id']) || !isset($_GET['id'])) {
    echo "Acces interzis!";
    exit();
}

// Preia ID-ul evenimentului din GET
$event_id = $_GET['id'];

// Obține evenimentul din baza de date
$stmt = $pdo->prepare("SELECT * FROM events WHERE id = :id AND user_id = :user_id");
$stmt->execute(['id' => $event_id, 'user_id' => $_SESSION['user_id']]);
$event = $stmt->fetch(PDO::FETCH_ASSOC);

// Dacă evenimentul nu există sau utilizatorul nu este cel care l-a creat
if (!$event) {
    echo "Evenimentul nu a fost găsit sau nu ai permisiunea de a-l edita!";
    exit();
}

// Dacă formularul a fost trimis pentru a actualiza evenimentul
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $date = $_POST['date'];

    // Actualizează evenimentul în baza de date
    $stmt = $pdo->prepare("UPDATE events SET title = :title, description = :description, date = :date WHERE id = :id");
    $stmt->execute([
        'title' => $title,
        'description' => $description,
        'date' => $date,
        'id' => $event_id
    ]);

    echo "Evenimentul a fost actualizat cu succes!";
}

?>

<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editare Eveniment</title>
    <link rel="stylesheet" href="./assets/index.css">
</head>
<body>
    <h1>Editare Eveniment</h1>
    
    <form action="edit_event.php?id=<?= $event['id'] ?>" method="POST">
        <label for="title">Titlu:</label>
        <input type="text" name="title" id="title" value="<?= htmlspecialchars($event['title']) ?>" required><br><br>
        
        <label for="description">Descriere:</label>
        <textarea name="description" id="description" required><?= htmlspecialchars($event['description']) ?></textarea><br><br>
        
        <label for="date">Data și Ora:</label>
        <input type="datetime-local" name="date" id="date" value="<?= date('Y-m-d\TH:i', strtotime($event['date'])) ?>" required><br><br>
        
        <button type="submit">Actualizează Evenimentul</button>
    </form>

</body>
</html>
