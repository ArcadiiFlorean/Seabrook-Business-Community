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
    <link rel="stylesheet" href="assets/style.css"> 
</head>
<body>

    <main class="container">
  
        <section class="section-events">
            <div class="message message--error">
            <h1 class="message__title">We're sorry for the inconvenience.</h1>
<p class="message__text">Please sign in to explore upcoming events and stay up to date with the latest news.</p>

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
// Verifică dacă este trimis un comentariu
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_comment'])) {
    $comment = trim($_POST['comment']);
    $event_id = $_POST['event_id'];
    $user_id = $_SESSION['user_id'];  // Sau folosește altă metodă pentru a prelua numele utilizatorului (dacă este necesar)
    $user_name = 'User Name';  // Aici poți adăuga logica pentru a obține numele utilizatorului

    if (empty($comment)) {
        echo "<script>alert('Comentariul nu poate fi gol!');</script>";
    } else {
        try {
            // Salvează comentariul în baza de date
            $stmt = $pdo->prepare("INSERT INTO comments (event_id, user_id, user_name, comment_text, created_at) VALUES (:event_id, :user_id, :user_name, :comment_text, NOW())");
            $stmt->execute([
                'event_id' => $event_id,
                'user_id' => $user_id,
                'user_name' => $user_name,
                'comment_text' => $comment
            ]);
        } catch (PDOException $e) {
            echo "<script>alert('A apărut o eroare: " . $e->getMessage() . "');</script>";
        }
    }
}

// Obține evenimentele
$search = isset($_GET['search']) ? $_GET['search'] : '';
$event_date = isset($_GET['event_date']) ? $_GET['event_date'] : '';

// Filtrare după search și date
$query = "SELECT * FROM events WHERE 1=1"; // Query default
if (!empty($search)) {
    $query .= " AND (title LIKE :search OR description LIKE :search)";
}
if (!empty($event_date)) {
    $query .= " AND DATE(event_date) = :event_date"; // Folosește event_date
}
$query .= " ORDER BY event_date DESC"; // Folosește event_date

$stmt = $pdo->prepare($query);
$params = [];
if (!empty($search)) {
    $params['search'] = "%$search%";
}
if (!empty($event_date)) {
    $params['event_date'] = $event_date;
}

$stmt->execute($params);
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
                    <ul class="benefits-list">
                        <li class="benefit-item"><a href="#" class="benefit-link">Expand Your Network – Connect with local professionals.</a></li>
                        <li class="benefit-item"><a href="#" class="benefit-link">Showcase Your Business – Increase visibility and attract partners.</a></li>
                        <li class="benefit-item"><a href="#" class="benefit-link">Engage Locally – Contribute to Seabrook’s growth.</a></li>
                        <li class="benefit-item"><a href="#" class="benefit-link">Boost Attendance – Reach a targeted audience.</a></li>
                        <li class="benefit-item"><a href="#" class="benefit-link">Access Resources – Leverage local support for success.</a></li>
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

                <img class="img-event" src="./img/group6.png" alt="Group image">
            </div>
        </div>
    </section>

    <section id="events">
        <h1 class="card-title">Upcoming Events</h1>
        <div class="container">
            <section class="search-section">
                <form class="search-form" method="GET" action="events.php">
                    <input type="text" name="search" placeholder="Search Events..." class="search-input">
                    <input type="date" name="event_date" class="search-input">
                    <button type="submit" class="search-btn">Search</button>
                </form>
            </section>

            <div class="container">
                <?php if (!empty($events)) { ?>
                    <div class="events-container">
                        <?php foreach ($events as $row) { ?>
                            <div class="event-card">
                                <div class="event-content">
                                    <h3 class="events-title"><?= htmlspecialchars($row['title'], ENT_QUOTES, 'UTF-8') ?></h3>
                                    <p class="event-description"><?= nl2br(htmlspecialchars(truncateDescription($row['description'], $maxLength), ENT_QUOTES, 'UTF-8')) ?></p>
                                    <p class="date"><em>Date: <?= htmlspecialchars(date('d-m-Y', strtotime($row['event_date'])), ENT_QUOTES, 'UTF-8') ?></em></p>


                                </div>

                                <div class="comments-section" id="comments-<?= $row['event_id'] ?>">
                                    <!-- Comments go here -->
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                <?php } else { ?>
                    <p>No events found.</p>
                <?php } ?>
            </div>
        </div>
    </section>

    <footer class="footer">
        <p>&copy; 2025 Seabrook Community</p>
    </footer>

</body>
</html>
