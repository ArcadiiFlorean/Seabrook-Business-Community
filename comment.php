<?php
session_start();
include 'config/db.php'; // Dacă db.php este într-un subfolder "config"

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $comment = mysqli_real_escape_string($conn, $_POST['comment']);
    $event_id = $_POST['event_id'];
    $user_id = $_SESSION['user_id']; // Asigură-te că folosești user_id corect

    $sql = "INSERT INTO comments (event_id, user_id, comment) VALUES ('$event_id', '$user_id', '$comment')";

    if (mysqli_query($conn, $sql)) {
        header("Location: event-details.php?id=$event_id");  // Redirect after adding comment
    } else {
        echo "Eroare la adăugarea comentariului: " . mysqli_error($conn);
    }
}

mysqli_close($conn);
?>
