<?php
session_start();
include 'config/db.php'; // Verifică dacă calea către db.php este corectă

// Verificăm dacă utilizatorul este autentificat
if (!$pdo) {
    echo json_encode(['status' => 'error', 'message' => 'Database connection failed']);
    exit();
}

// Verificăm dacă formularul a fost trimis prin POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_POST['event_id'], $_POST['comment'])) {
        $_SESSION['message'] = 'Date incomplete!';
        header('Location: events.php'); // Redirecționează utilizatorul la pagina de evenimente
        exit();
    }

    $event_id = filter_var($_POST['event_id'], FILTER_VALIDATE_INT);
    $comment = trim($_POST['comment']);
    $user_id = $_SESSION['user_id'];

    if (!$event_id) {
        $_SESSION['message'] = 'ID-ul evenimentului este invalid!';
        header('Location: events.php'); // Redirecționează utilizatorul la pagina de evenimente
        exit();
    }
    if (empty($comment)) {
        $_SESSION['message'] = 'Comentariul nu poate fi gol!';
        header('Location: events.php'); // Redirecționează utilizatorul la pagina de evenimente
        exit();
    }

    try {
        // Obține numele utilizatorului
        $user_stmt = $pdo->prepare("SELECT username FROM users WHERE id = :user_id");
        $user_stmt->execute(['user_id' => $user_id]);
        $user = $user_stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            $_SESSION['message'] = 'Utilizatorul nu a fost găsit!';
            header('Location: events.php'); // Redirecționează utilizatorul la pagina de evenimente
            exit();
        }

        // Pregătim interogarea SQL pentru a adăuga comentariul
        $sql = "INSERT INTO comments (event_id, user_id, comment) VALUES (:event_id, :user_id, :comment)";
        $stmt = $pdo->prepare($sql);

        // Bind params and check for errors
        $stmt->bindParam(':event_id', $event_id, PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);

        // Execute and check result
        if ($stmt->execute()) {
            $_SESSION['message'] = 'Comentariul a fost adăugat cu succes!';
            header('Location: events.php'); // Redirecționează utilizatorul la pagina de evenimente
            exit(); // Oprește execuția scriptului după redirecționare
        } else {
            // Logging for debugging
            $errorInfo = $stmt->errorInfo();
            $_SESSION['message'] = 'Eroare la adăugarea comentariului: ' . implode(', ', $errorInfo);
            header('Location: events.php'); // Redirecționează utilizatorul la pagina de evenimente
            exit();
        }
    } catch (PDOException $e) {
        $_SESSION['message'] = 'Eroare la interogare: ' . $e->getMessage();
        header('Location: events.php'); // Redirecționează utilizatorul la pagina de evenimente
        exit();
    }
}
?>
