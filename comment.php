<?php
session_start();
include 'config/db.php'; // Conectează-te la baza de date

// Verificăm dacă utilizatorul este autentificat
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Not logged in']);
    exit();
}

// Verificăm dacă formularul a fost trimis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verificăm dacă variabilele există și sunt valide
    if (!isset($_POST['event_id'], $_POST['comment'])) {
        echo json_encode(['status' => 'error', 'message' => 'Incomplete data']);
        exit();
    }

    $event_id = filter_var($_POST['event_id'], FILTER_VALIDATE_INT);
    $comment = trim($_POST['comment']);
    $user_id = $_SESSION['user_id'];

    // Verificăm dacă datele sunt valide
    if (!$event_id) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid event ID']);
        exit();
    }
    if (empty($comment)) {
        echo json_encode(['status' => 'error', 'message' => 'Comment cannot be empty']);
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
            // Răspundem cu succes și returnăm comentariul adăugat
            echo json_encode([
                'status' => 'success',
                'comment' => htmlspecialchars($comment),
                'user_id' => $user_id
            ]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Error adding comment']);
        }
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => 'Query error: ' . $e->getMessage()]);
    }
}
?>
