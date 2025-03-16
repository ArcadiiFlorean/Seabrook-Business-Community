<?php
session_start();
include 'config/db.php';

// Verifică dacă utilizatorul este conectat
if (!isset($_SESSION['user_id'])) {
    echo "Trebuie să te autentifici pentru a șterge un eveniment!";
    exit();
}

if (!isset($_GET['id'])) {
    echo "ID-ul evenimentului nu a fost specificat.";
    exit();
}

$event_id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM events WHERE id = :id AND user_id = :user_id");
$stmt->execute(['id' => $event_id, 'user_id' => $_SESSION['user_id']]);
$event = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$event) {
    echo "Evenimentul nu a fost găsit sau nu aveți permisiunea să-l ștergeți.";
    exit();
}

// Ștergem evenimentul
$stmt = $pdo->prepare("DELETE FROM events WHERE id = :id AND user_id = :user_id");
$stmt->execute(['id' => $event_id, 'user_id' => $_SESSION['user_id']]);

echo "Evenimentul a fost șters cu succes!";
?>
