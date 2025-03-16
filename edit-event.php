<?php
session_start();
include 'config/db.php'; // Include fișierul de conectare la baza de date

// Verifică dacă utilizatorul este conectat
if (!isset($_SESSION['user_id'])) {
    echo "Trebuie să te autentifici pentru a edita un eveniment!";
    exit();
}

// Verifică dacă ID-ul evenimentului este furnizat
if (!isset($_GET['id'])) {
    echo "ID-ul evenimentului nu a fost specificat.";
    exit();
}

$event_id = $_GET['id'];

// Verifică dacă utilizatorul este cel care a creat evenimentul
$stmt = $pdo->prepare("SELECT * FROM events WHERE id = :id AND user_id = :user_id");
$stmt->execute(['id' => $event_id, 'user_id' => $_SESSION['user_id']]);
$event = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$event) {
    echo "Evenimentul nu a fost găsit sau nu aveți permisiunea să-l editați.";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Preia datele din formular
    $title = $_POST['title'];
    $description = $_POST['description'];
    $date = $_POST['date'];

    // Actualizează evenimentul în baza de date
    $stmt = $pdo->prepare("UPDATE events SET title = :title, description = :description, date = :date WHERE id = :id AND user_id = :user_id");
    $stmt->execute([
        'title' => $title,
        'description' => $description,
        'date' => $date,
        'id' => $event_id,
        'user_id' => $_SESSION['user_id']
    ]);

    echo "Evenimentul a fost actualizat cu succes!";
    header("Location: events.php"); // După actualizare, redirecționează înapoi la lista de evenimente
    exit();
}
?>

<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editare Eveniment</title>
</head>
<body>
    <h1>Editare Eveniment</h1>
    <form action="edit_event.php?id=<?php echo $event_id; ?>" method="POST">
        <label for="title">Titlu:</label>
        <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($event['title']); ?>" required><br><br>

        <label for="description">Descriere:</label>
        <textarea id="description" name="description" required><?php echo htmlspecialchars($event['description']); ?></textarea><br><br>

        <label for="date">Data și Ora:</label>
        <input type="datetime-local" id="date" name="date" value="<?php echo date('Y-m-d\TH:i', strtotime($event['date'])); ?>" required><br><br>

        <button type="submit">Actualizează Eveniment</button>
    </form>
</body>
</html>
