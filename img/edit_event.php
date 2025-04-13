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
$stmt = $pdo->prepare("SELECT * FROM events WHERE id = :id");
$stmt->execute(['id' => $event_id]);
$event = $stmt->fetch(PDO::FETCH_ASSOC);

// Verifică dacă utilizatorul este proprietarul evenimentului
$is_owner = $event && $event['user_id'] == $_SESSION['user_id'];

if (!$event) {
    echo "Evenimentul nu a fost găsit!";
    exit();
}

// Dacă formularul a fost trimis pentru a actualiza evenimentul și utilizatorul este proprietarul
if ($_SERVER['REQUEST_METHOD'] == 'POST' && $is_owner) {
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

    // Redirecționează utilizatorul cu un parametru de succes în URL
    header('Location: edit_event.php?id=' . $event_id . '&success=1');
    exit();
}
?>

<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Event</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>

<section class="section-edit-body">
<header class="header">
    <div class="container">
        <div class="nav__bar">
            <img src="./img/logo__img.svg" alt="Logo Comunitatea Seabrook" class="header__logo" width="150" height="auto">
            <h1 class="header__title">Creating Opportunities, Together.</h1>
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

<div class="container">
<div class="edit-events-content">
    <h1 class="edit-events-title">Edit Event</h1>

    <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
        <p class="success-message">The event has been successfully updated!</p>
    <?php elseif (isset($_GET['error'])): ?>
        <p class="error-message"><?= htmlspecialchars($_GET['error']) ?></p>
    <?php endif; ?>

    <?php if (!$is_owner): ?>
        <p class="edit-form-error">You do not have permission to edit this event!</p>
    <?php endif; ?>

    <?php if (isset($event['id'])): ?>
        <form class="edit-form edit-form-container" action="edit_event.php?id=<?= $event['id'] ?>" method="POST">
            
            <div class="form-group">
                <label for="title" class="form-label">Title:</label>
                <input type="text" name="title" id="title" class="form-input" value="<?= htmlspecialchars($event['title']) ?>" required <?= !$is_owner ? 'disabled' : '' ?>>
            </div>

            <div class="form-group">
                <label for="description" class="form-label">Description</label>
                <textarea name="description" id="description" class="form-input" required <?= !$is_owner ? 'disabled' : '' ?>><?= htmlspecialchars($event['description']) ?></textarea>
            </div>

            <div class="form-group">
                <label for="date" class="form-label">Date and time:</label>
                <input type="datetime-local" name="date" id="date" class="form-input" value="<?= date('Y-m-d\TH:i', strtotime($event['date'])) ?>" required <?= !$is_owner ? 'disabled' : '' ?>>
            </div>

            <button type="submit" class="form-button" <?= !$is_owner ? 'disabled' : '' ?>>Update the event!</button>
        </form>

        <a href="events.php" class="back-button">Back to events</a>

    <?php else: ?>
        <p class="edit-form-error">Error: Event not found!</p>
    <?php endif; ?>
</div>

</div>
</section>

</body>
</html>
