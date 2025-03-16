<?php
session_start();
include 'config/db.php';

if (!isset($_GET['id'])) {
    echo "ID-ul evenimentului nu a fost specificat.";
    exit;
}

$event_id = $_GET['id'];

// Preia datele evenimentului din baza de date
$query = "SELECT * FROM events WHERE id = :event_id";
$stmt = $pdo->prepare($query);
$stmt->execute(['event_id' => $event_id]);
$event = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$event) {
    echo "Evenimentul nu a fost găsit.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalii Eveniment</title>
    <link rel="stylesheet" href="./assesrs/events_styles.css">
</head>
<body>
    <header>
        <h1>Detalii Eveniment</h1>
    </header>
    <main>
        <h2><?php echo htmlspecialchars($event['title']); ?></h2>
        <p><?php echo nl2br(htmlspecialchars($event['description'])); ?></p>
        <p><em>Data: <?php echo htmlspecialchars($event['date']); ?></em></p>

        <h3>Comentarii:</h3>
        <div>
            <?php
            // Afișează comentariile
            $comments_query = "SELECT * FROM comments WHERE event_id = :event_id ORDER BY created_at DESC";
            $comments_stmt = $pdo->prepare($comments_query);
            $comments_stmt->execute(['event_id' => $event_id]);
            while ($comment = $comments_stmt->fetch(PDO::FETCH_ASSOC)) {
                echo "<p><strong>User " . $comment['user_id'] . ":</strong> " . $comment['comment'] . "</p>";
            }
            ?>
        </div>

        <?php if (isset($_SESSION['user_id'])): ?>
            <h3>Adaugă un Comentariu</h3>
            <form action="comment.php" method="post">
                <textarea name="comment" placeholder="Lasă un comentariu..." required></textarea><br>
                <input type="hidden" name="event_id" value="<?php echo $event_id; ?>">
                <button type="submit">Adaugă Comentariu</button>
            </form>
        <?php endif; ?>
    </main>

    <footer>
        <p>&copy; 2025 Comunitatea Seabrook</p>
    </footer>
</body>
</html>
