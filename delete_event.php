<?php
session_start();
include 'config/db.php';

// Verifică dacă utilizatorul este conectat
if (!isset($_SESSION['user_id'])) {
    header('Location: events.php?error=not_logged_in');
    exit();
}

if (!isset($_GET['id'])) {
    header('Location: events.php?error=no_event_id');
    exit();
}

$event_id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM events WHERE id = :id AND user_id = :user_id");
$stmt->execute(['id' => $event_id, 'user_id' => $_SESSION['user_id']]);
$event = $stmt->fetch(PDO::FETCH_ASSOC);

// Verifică dacă evenimentul există și dacă utilizatorul are permisiunea de a-l șterge
if (!$event) {
    header('Location: events.php?error=not_found_or_no_permission');
    exit();
}

// Încearcă să ștergi evenimentul doar dacă utilizatorul este autorul evenimentului
if ($event['user_id'] != $_SESSION['user_id']) {
    header('Location: events.php?error=no_permission_to_delete');
    exit();
}

// Șterge evenimentul
$stmt = $pdo->prepare("DELETE FROM events WHERE id = :id AND user_id = :user_id");
$stmt->execute(['id' => $event_id, 'user_id' => $_SESSION['user_id']]);

// Redirecționează utilizatorul la lista de evenimente cu un mesaj de succes
header('Location: events.php?success=1');
exit();
?>
