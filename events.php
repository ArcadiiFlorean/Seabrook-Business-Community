<?php
session_start();
include 'config/db.php'; // Include fișierul de conectare la baza de date

// Verifică dacă utilizatorul este autentificat
if (!isset($_SESSION['user_id'])) {
    include 'header.php'; // Include header-ul dacă ai unul
    ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Access Restricted</title>
    <link rel="stylesheet" href="styles.css"> <!-- Ensure you have this CSS file -->
</head>
<body>

    <main class="container">
        
        <section class="section-events">
            <div class="message message--error">
                <h1 class="message__title">We apologize for the inconvenience!</h1>
                <p class="message__text">Please sign in to explore upcoming events and stay updated with the latest news</p>
                <a href="login.php" class="message__button" role="button">Login</a>
            </div>

            <div class="img-grid">
                <div class="container-grid">
                    <img class="img-grid-list" src="./img/group1.png" alt="Seabrook cityscape">
                    <img class="img-grid-list" src="./img/group2.png" alt="Community gathering in Seabrook">
                    <img class="img-grid-list" src="./img/group3.png" alt="Cultural event in Seabrook">
                    <img class="img-grid-list" src="./img/group3.png" alt="Seabrook beach at sunset">
                    <img class="img-grid-list" src="./img/group2.png" alt="Music festival in Seabrook">
                    <img class="img-grid-list" src="./img/group1.png" alt="Volunteers working in Seabrook">
                </div>
            </div>
        </section>

    </main>

    <footer class="footer-events">
        <p class="footer__text">&copy; 2025 Seabrook Community</p>
    </footer>

</body>
</html>

    
  

    <?php
    exit();}
// Inițializare variabilă pentru evenimente
$events = [];

// Adăugare eveniment
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_event'])) {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $date = $_POST['date'];
    $user_id = $_SESSION['user_id'];

    if (empty($title) || empty($description) || empty($date)) {
        echo "<script>alert('Toate câmpurile sunt obligatorii!');</script>";
    } else {
        $stmt = $pdo->prepare("INSERT INTO events (title, description, date, user_id) VALUES (:title, :description, :date, :user_id)");
        $stmt->execute([
            'title' => $title,
            'description' => $description,
            'date' => $date,
            'user_id' => $user_id
        ]);
        echo "<script>window.location.href = 'events.php';</script>";
        exit();
    }
}

// Selectare evenimente
if (isset($_GET['event_date'])) {
    $selected_date = $_GET['event_date'];
    $stmt = $pdo->prepare("SELECT * FROM events WHERE DATE(date) = :event_date ORDER BY date DESC");
    $stmt->execute(['event_date' => $selected_date]);
} else {
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
    <link rel="stylesheet" href="./assets/events_style.css">
</head>
<body>
    <div class="events">
        <h1 class="events__title">Evenimente</h1>
        <div class="events__logout">
            <a href="logout.php" class="events__logout-link">
                <button type="button" class="events__logout-button">Deconectare</button>
            </a>
        </div>
        
        <section class="events__form-section">
            <h2 class="events__form-title">Adaugă Eveniment</h2>
            <form action="events.php" method="POST" class="events__form">
                <div class="events__form-group">
                    <label for="title">Titlu:</label>
                    <input type="text" id="title" name="title" required>
                </div>
                <div class="events__form-group">
                    <label for="description">Descriere:</label>
                    <textarea id="description" name="description" required></textarea>
                </div>
                <div class="events__form-group">
                    <label for="date">Data și Ora:</label>
                    <input type="datetime-local" id="date" name="date" required>
                </div>
                <button type="submit" name="add_event">Adaugă Eveniment</button>
            </form>
        </section>

        <section id="events">
            <h2>Evenimente Recente</h2>
            <?php if (!empty($events)) {
                foreach ($events as $row) { ?>
                    <div class='event'>
                        <h3><?= htmlspecialchars($row['title'], ENT_QUOTES, 'UTF-8') ?></h3>
                        <p><?= nl2br(htmlspecialchars($row['description'], ENT_QUOTES, 'UTF-8')) ?></p>
                        <p><em>Data: <?= htmlspecialchars($row['date'], ENT_QUOTES, 'UTF-8') ?></em></p>
                        <?php if ($_SESSION['user_id'] == $row['user_id']) { ?>
                            <a href='edit_event.php?id=<?= $row['id'] ?>'>Editează</a> | 
                            <a href='delete_event.php?id=<?= $row['id'] ?>'>Șterge</a>
                        <?php } ?>
                        <h4>Comentarii:</h4>
                        <?php 
                        $comments_stmt = $pdo->prepare("SELECT * FROM comments WHERE event_id = :event_id ORDER BY created_at DESC");
                        $comments_stmt->execute(['event_id' => $row['id']]);
                        while ($comment = $comments_stmt->fetch(PDO::FETCH_ASSOC)) { ?>
                            <div class='comment'>
                                <p><strong>User <?= htmlspecialchars($comment['user_id'], ENT_QUOTES, 'UTF-8') ?>:</strong> <?= htmlspecialchars($comment['comment'], ENT_QUOTES, 'UTF-8') ?></p>
                            </div>
                        <?php } ?>
                        <form action='comment.php' method='post'>
                            <textarea name='comment' placeholder='Lasă un comentariu...' required></textarea><br>
                            <input type='hidden' name='event_id' value='<?= $row['id'] ?>'>
                            <button type='submit'>Adaugă Comentariu</button>
                        </form>
                    </div>
                <?php } 
            } else {
                echo "<p>Nu sunt evenimente pentru data selectată.</p>";
            } ?>
        </section>
    </div>
</body>
</html>
