<?php
session_start();
include 'config/db.php'; // Dacă db.php este într-un subfolder "config"

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Obține evenimentele din baza de date
$sql = "SELECT * FROM events ORDER BY date DESC";
$result = mysqli_query($conn, $sql);

echo "<h1>Evenimente disponibile</h1>";

while ($row = mysqli_fetch_assoc($result)) {
    echo "<div>";
    echo "<h3>" . $row['title'] . "</h3>";
    echo "<p>" . $row['description'] . "</p>";
    echo "<p><strong>Data:</strong> " . $row['date'] . "</p>";

    // Afișează comentariile pentru fiecare eveniment
    $event_id = $row['id'];
    $comment_sql = "SELECT * FROM comments WHERE event_id = $event_id";
    $comment_result = mysqli_query($conn, $comment_sql);

    echo "<h4>Comentarii:</h4>";
    while ($comment = mysqli_fetch_assoc($comment_result)) {
        echo "<p><strong>" . $comment['comment'] . "</strong> - de " . $comment['user_id'] . "</p>";
    }

    echo "<form method='post' action='comment.php'>
            <textarea name='comment' placeholder='Adaugă un comentariu'></textarea>
            <input type='hidden' name='event_id' value='" . $row['id'] . "'>
            <button type='submit'>Adaugă comentariu</button>
          </form>";
    echo "</div>";
}

mysqli_close($conn);
?>
