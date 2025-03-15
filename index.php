<?php
include 'config/db.php'; // Verifică dacă calea către db.php este corectă

// Verifică dacă s-a selectat o dată din formularul GET
if (isset($_GET['event_date'])) {
    $selected_date = $_GET['event_date'];

    // Preluarea evenimentelor din baza de date pentru ziua selectată
    $stmt = $pdo->prepare("SELECT * FROM events WHERE DATE(date) = :event_date ORDER BY date DESC");
    $stmt->execute(['event_date' => $selected_date]);
} else {
    // Dacă nu s-a selectat o dată, afișează toate evenimentele
    $stmt = $pdo->query("SELECT * FROM events ORDER BY date DESC");
}

$events = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comunitatea Seabrook</title>
    <link rel="stylesheet" href="./assesrs/styles.css">

</head>
<body>
    <header>
        <h1>Comunitatea Seabrook</h1>
        <nav>
            <ul>
                <li><a href="index.php">Acasă</a></li>
                <li><a href="events.php">Evenimente</a></li>
                <li><a href="login.php">Autentificare</a></li>
                <li><a href="register.php">Înregistrare</a></li>
            </ul>
        </nav>
    </header>

    <main>
       
<H1>Main-menu</H1>
       
    </main>

    <footer>
        <p>&copy; 2025 Comunitatea Seabrook</p>
    </footer>
</body>
</html>
