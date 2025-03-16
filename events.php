<?php
session_start();
include 'config/db.php'; // Include fișierul de conectare la baza de date

// Verifică dacă utilizatorul este conectat
if (!isset($_SESSION['user_id'])) {
    echo "
    <html>
        <head>
            <title>Autentificare Necesara</title>
            <link rel='stylesheet' type='text/css' href='style.css'>
        </head>
        <body>
            <div class='container'>
                <div class='message'>
                    <h1>You need to log in to view the events!</h1>
                    <p>Vă rugăm să vă autentificați pentru a accesa secțiunea de evenimente exclusive. Apăsați pe butonul de mai jos pentru a vă conecta.</p>
                    <img src='login_image.jpg' alt='Autentificare' class='login-image'>
                    <a href='login.php' class='login-button'>Autentifică-te</a>
                </div>
            </div>
        </body>
    </html>";
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

    // Debugging: Verifică valoarea datei selectate
    echo "Data selectată: " . htmlspecialchars($selected_date) . "<br>";

    // Preluarea evenimentelor din baza de date pentru ziua selectată
    $stmt = $pdo->prepare("SELECT * FROM events WHERE DATE(date) = :event_date ORDER BY date DESC");
    $stmt->execute(['event_date' => $selected_date]);
} else {
    // Dacă nu s-a selectat o dată, afișează toate evenimentele
    $stmt = $pdo->query("SELECT * FROM events ORDER BY date DESC");
}

$events = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Debugging: Verifică câte evenimente au fost găsite
echo "Număr evenimente găsite: " . count($events) . "<br>";

?>

<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Evenimente</title>
    <link rel="stylesheet" href="./assesrs/events_styles.css">
</head>
<body>
    <h1>Evenimente</h1>

    <!-- Butonul de logout -->
    <a href="logout.php"><button type="button">Logout</button></a>

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
                } else {
                    echo "<p>Nu poți edita sau șterge acest eveniment deoarece nu l-ai creat.</p>";
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
