<?php
session_start();
include 'config/db.php'; // Include fișierul de conectare la baza de date

// Verifică dacă utilizatorul este conectat
if (!isset($_SESSION['user_id'])) {
    echo '
    <div class="message message--error">
        <h1 class="message__title">Ne pare rău!</h1>
        <p class="message__text">Vă rugăm să vă autentificați pentru a accesa secțiunea de evenimente exclusive.</p>
        <a href="login.php" class="message__link">
            <button class="message__button">Autentifică-te</button>
        </a>
    </div>';
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

?>

<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Events</title>
    <link rel="stylesheet" href="./assesrs/events_styles.css">
    <!-- Test link CSS pentru verificare -->

</head>

<body>

<div class="events">
    <h1 class="events__title">Events</h1>

    <!-- Secțiune de logout -->
    <div class="events__logout">
        <a href="logout.php" class="events__logout-link">
            <button type="button" class="events__logout-button">Logout</button>
        </a>
    </div>

    <!-- Secțiune pentru adăugarea unui eveniment -->

    <section class="events__image-gallery">
        <h2 class="events__image-title">Galerie Evenimente</h2>
        <div class="events__image-item">
            <img src="image1.jpg" alt="Eveniment 1" class="events__image">
        </div>
        <div class="events__image-item">
            <img src="image2.jpg" alt="Eveniment 2" class="events__image">
        </div>
        <!-- Adăugați mai multe imagini, după necesitate -->
    </section>
    <section class="events__form-section">
        <h2 class="events__form-title">Adaugă Eveniment</h2>
        <form action="events.php" method="POST" class="events__form">
            <div class="events__form-group">
                <label for="title" class="events__label">Titlu:</label>
                <input type="text" id="title" name="title" class="events__input" required>
            </div>

            <div class="events__form-group">
                <label for="description" class="events__label">Descriere:</label>
                <textarea id="description" name="description" class="events__textarea" required></textarea>
            </div>

            <div class="events__form-group">
                <label for="date" class="events__label">Data și Ora:</label>
                <input type="datetime-local" id="date" name="date" class="events__input" required>
            </div>

            <button type="submit" name="add_event" class="events__submit-button">Adaugă Eveniment</button>
        </form>
    </section>

    <!-- Secțiune pentru selectarea unei date -->
    <section class="events__search-section">
        <h2 class="events__search-title">Căutare Evenimente</h2>
        <form action="events.php" method="GET" class="events__search-form">
            <div class="events__form-group">
                <label for="event_date" class="events__label">Alege o dată:</label>
                <input type="date" id="event_date" name="event_date" class="events__input" required>
            </div>

            <button type="submit" class="events__search-button">Căutare Evenimente</button>
        </form>
    </section>

    <!-- Secțiune pentru imagini -->
   
</div>

    
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

            // Formular pentru comentarii
            echo "<h4>Comentarii:</h4>";

            // Afișează comentariile existente
            $comments_query = "SELECT * FROM comments WHERE event_id = :event_id ORDER BY created_at DESC";
            $comments_stmt = $pdo->prepare($comments_query);
            $comments_stmt->execute(['event_id' => $row['id']]);

            while ($comment = $comments_stmt->fetch(PDO::FETCH_ASSOC)) {
                echo "<div class='comment'>";
                echo "<p><strong>User " . htmlspecialchars($comment['user_id']) . ":</strong> " . htmlspecialchars($comment['comment']) . "</p>";
                echo "</div>";
            }

            // Permite adăugarea unui comentariu dacă utilizatorul este autentificat
            if (isset($_SESSION['user_id'])) {
                echo "<form action='comment.php' method='post'>";
                echo "<textarea name='comment' placeholder='Lasă un comentariu...' required></textarea><br>";
                echo "<input type='hidden' name='event_id' value='" . $row['id'] . "'>";  // ID-ul evenimentului
                echo "<button type='submit'>Adaugă Comentariu</button>";
                echo "</form>";
            } else {
                echo "<p>Trebuie să te autentifici pentru a adăuga un comentariu.</p>";
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
