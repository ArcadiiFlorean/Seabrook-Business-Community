<?php
session_start();
include 'config/db.php'; // Conectează-te la baza de date

// Verificăm dacă utilizatorul este autentificat
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Verificăm dacă formularul a fost trimis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verificăm dacă variabilele există și sunt valide
    if (!isset($_POST['event_id'], $_POST['comment'])) {
        echo "Datele sunt incomplete!";
        exit();
    }

    $event_id = filter_var($_POST['event_id'], FILTER_VALIDATE_INT);
    $comment = trim($_POST['comment']);
    $user_id = $_SESSION['user_id'];

    // Verificăm dacă datele sunt valide
    if (!$event_id) {
        echo "ID-ul evenimentului este invalid!";
        exit();
    }
    if (empty($comment)) {
        echo "Comentariul nu poate fi gol!";
        exit();
    }

    try {
        // Pregătim interogarea SQL pentru a adăuga comentariul
        $sql = "INSERT INTO comments (event_id, user_id, comment) VALUES (:event_id, :user_id, :comment)";
        $stmt = $pdo->prepare($sql);

        // Legăm parametrii
        $stmt->bindParam(':event_id', $event_id, PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);

        // Executăm interogarea
        if ($stmt->execute()) {
            // Redirectăm utilizatorul înapoi la pagina de detalii a evenimentului
            header("Location: event_details.php?id=$event_id");
            exit();
        } else {
            echo "Eroare la adăugarea comentariului!";
        }
    } catch (PDOException $e) {
        echo "Eroare la interogare: " . $e->getMessage();
    }
}
?>
