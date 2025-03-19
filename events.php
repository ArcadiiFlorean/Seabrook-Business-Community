<?php 
session_start();
include 'config/db.php'; // Include fișierul de conectare la baza de date

// Limita de caractere pentru descriere
$maxLength = 350; // Modifică această valoare după necesități

// Funcție pentru tăierea textului și adăugarea elipsei
function truncateDescription($description, $maxLength) {
    if (strlen($description) > $maxLength) {
        return substr($description, 0, $maxLength) . '...';
    }
    return $description;
}

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
    <link rel="stylesheet" href="assets/index.css"> 
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
    exit();
}

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
        try {
            $stmt = $pdo->prepare("INSERT INTO events (title, description, date, user_id) VALUES (:title, :description, :date, :user_id)");
            $stmt->execute([
                'title' => $title,
                'description' => $description,
                'date' => $date,
                'user_id' => $user_id
            ]);
            // Elimină linia cu alertul de succes
        } catch (PDOException $e) {
            echo "<script>alert('A apărut o eroare: " . $e->getMessage() . "');</script>";
        }
    }
}


// Obține evenimentele
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
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>

    <header class="header">
        <div class="container">
            <div class="nav__bar">
                <img src="./img/logo__img.svg" alt="Logo Comunitatea Seabrook" class="header__logo" width="150" height="auto">
                <h1 class="header__title">From Startups to Success Stories.</h1>
                <ul class="nav__list">
                    <li class="nav__item"><a class="nav__link" href="index.php">Home</a></li>
                    <li class="nav__item"><a class="nav__link" href="events.php">Events</a></li>
                    <li class="nav__item"><a class="nav__link" href="contact.php">Contact</a></li>
                    <li class="nav__item"><button class="nav__link--btn" onclick="window.location.href='login.php';">Login</button></li>
                    <li class="nav__item"><button class="nav__link--btn" onclick="window.location.href='register.php';">Sign Up</button></li>
                    <li class="nav__item"><a href="logout.php" class="events__logout-link"><button type="button" class="events__logout-button">Log Out</button></a></li>
                </ul>
            </div>
        </div>
    </header>

    <section class="events-section">
        <div class="container">
            <div class="events-main">
                <div class="events-main__header">
                    <h1 class="events-main__title">Submit Your Event to Our Platform</h1>
                    <p class="events-main__paragraph">This is where you can add and manage your events with ease.</p>
                    <ul class="events-main__benefits">
                        <li>Expand your audience by reaching a wider community.</li>
                        <li>Increase visibility of your events and attract more participants.</li>
                        <li>Network with professionals and organizations supporting your business.</li>
                        <li>Easy management of events, keeping everything in one place.</li>
                    </ul>
                </div>

                <div class="events-info">
                    <h2 class="events-info__main-text">Post Your Event</h2>
                    <form action="events.php" method="POST" class="events-form">
                        <div class="events-form__group">
                            <label for="title" class="events-form__label">Title:</label>
                            <input type="text" id="title" name="title" class="events-form__input" required>
                        </div>
                        <div class="events-form__group">
                            <label for="description" class="events-form__label">Description:</label>
                            <textarea id="description" name="description" class="events-form__textarea" required></textarea>
                        </div>
                        <div class="events-form__group">
                            <label for="date" class="events-form__label">Date & Time:</label>
                            <input type="datetime-local" id="date" name="date" class="events-form__input" required>
                        </div>
                        <button type="submit" name="add_event" class="events-form__button">Add Event</button>
                    </form>
                </div>

                <img class="img-event" src="./img/eventsimg.png" alt="Group image">
            </div>
        </div>

        
    </section>
 
    <section id="events">
  <h1 class="card-title">Upcoming Events</h1>
        <div class="container">
            <?php if (!empty($events)) { ?>
                <div class="events-container">
                    <?php foreach ($events as $row) { ?>
                        <div class="event-card">
                            <div class="event-content">
                                <h3 class="events-title"><?= htmlspecialchars($row['title'], ENT_QUOTES, 'UTF-8') ?></h3>
                                <p class="event-description"><?= nl2br(htmlspecialchars(truncateDescription($row['description'], $maxLength), ENT_QUOTES, 'UTF-8')) ?></p>
                                <p class="date"><em>Date: <?= htmlspecialchars($row['date'], ENT_QUOTES, 'UTF-8') ?></em></p>
                            </div>

                      
                      <div class="comments-section" id="comments-<?= $row['id'] ?>">
                                <?php 
                                $comments_stmt = $pdo->prepare("SELECT comments.*, users.username FROM comments JOIN users ON comments.user_id = users.id WHERE comments.event_id = :event_id ORDER BY comments.created_at DESC");
                                $comments_stmt->execute(['event_id' => $row['id']]);

                                while ($comment = $comments_stmt->fetch(PDO::FETCH_ASSOC)) { ?>
                                    <div class='comment'>
                                        <p><strong><?= htmlspecialchars($comment['username'], ENT_QUOTES, 'UTF-8') ?>:</strong> <?= htmlspecialchars($comment['comment'], ENT_QUOTES, 'UTF-8') ?></p>
                                    </div>
                                <?php } ?>
                            </div>
                           <div class="coments-action">
                            <div class="comment-form">
                                <form action='comment.php' method='post'>
                                    <textarea name='comment' placeholder='Leave a comment...' required></textarea>
                                    <input type='hidden' name='event_id' value='<?= $row['id'] ?>'>
                                    <button  type='submit'>Add Comment</button>
                                </form>
                            </div>

                            <div class="event-actions">
                                <a href='edit_event.php?id=<?= $row['id'] ?>'>Edit</a>
                                <a href='delete_event.php?id=<?= $row['id'] ?>'>Delete</a>
                            </div>

                           </div>
                            



                        </div>

                      







                    <?php } ?>
                </div>
            <?php } else { ?>
                <p class="no-events">No events found.</p>
            <?php } ?>
        </div>
    </section>
    
    <footer class="footer-events">
        <p class="footer__text">&copy; 2025 Seabrook Community</p>
    </footer>

</body>
</html>
