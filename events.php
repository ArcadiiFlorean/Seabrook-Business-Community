<?php
session_start();
include 'config/db.php'; // Include fișierul de conectare la baza de date

// Verifică dacă utilizatorul este conectat
if (!isset($_SESSION['user_id'])) {
    echo "Trebuie să te autentifici pentru a vizualiza evenimentele!";
    exit();
}

// Adăugare eveniment
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_event'])) {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $date = $_POST['date'];
    $user_id = $_SESSION['user_id'];

    // Inserare eveniment în baza de date
    $stmt = $pdo->prepare("INSERT INTO events (title, description, date, user_id) VALUES (:title, :description, :date, :user_id)");
    $stmt->execute([
        'title' => $title,
        'description' => $description,
        'date' => $date,
        'user_id' => $user_id
    ]);

    echo "Evenimentul a fost adăugat cu succes!";
}

// Verifică dacă s-a trimis o dată prin GET
if (isset($_GET['event_date'])) {
    $selected_date = $_GET['event_date'];

    // Preluarea evenimentelor din baza de date pentru ziua selectată
    $stmt = $pdo->prepare("SELECT * FROM events WHERE DATE(date) = :event_date ORDER BY date DESC");
    $stmt->execute(['event_date' => $selected_date]);
} else {
    // Dacă nu s-a selectat o dată, afișează toate evenimentele
    $stmt = $pdo->query("SELECT * FROM events ORDER BY date DESC");
}

$events = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Evenimente</title>
</head>
<body>
    <h1>Evenimente</h1>

    <!-- Formular pentru adăugarea unui eveniment -->
    <form action="events.php" method="POST">
        <h2>Adaugă Eveniment</h2>
        <label for="title">Titlu:</label>
        <input type="text" id="title" name="title" required><br><br>

        <label for="description">Descriere:</label>
        <textarea id="description" name="description" required></textarea><br><br>

        <label for="date">Data și Ora:</label>
        <input type="datetime-local" id="date" name="date" required><br><br>

        <button type="submit" name="add_event">Adaugă Eveniment</button>
    </form>

    <!-- Formularul pentru a selecta o dată -->
    <form action="events.php" method="GET">
        <label for="event_date">Alege o dată:</label>
        <input type="date" id="event_date" name="event_date" required>
        <button type="submit">Căutare Evenimente</button>
    </form>

    <section id="events">
        <h2>Evenimente Recente</h2>
        <?php
        if (count($events) > 0) {
            foreach ($events as $row) {
                echo "<div class='event'>";
                echo "<h3>" . htmlspecialchars($row['title']) . "</h3>";
                echo "<p>" . nl2br(htmlspecialchars($row['description'])) . "</p>";
                echo "<p><em>Data: " . htmlspecialchars($row['date']) . "</em></p>";

                // Permite editarea și ștergerea doar pentru utilizatorul care a creat evenimentul
                if ($_SESSION['user_id'] == $row['user_id']) {
                    echo "<a href='edit_event.php?id=" . $row['id'] . "'>Editează</a> | ";
                    echo "<a href='delete_event.php?id=" . $row['id'] . "'>Șterge</a>";
                }

                echo "</div>";
            }
        } else {
            echo "<p>Nu sunt evenimente pentru data selectată.</p>";
        }
        ?>
    </section>
</body>
</html>
