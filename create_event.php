<?php
session_start();
include('includes/header.php');
include 'config/db.php'; // Dacă db.php este într-un subfolder "config"

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $date = $_POST['date'];
    $user_id = $_SESSION['user_id'];  // ID-ul utilizatorului logat

    $query = "INSERT INTO events (title, description, date, user_id) VALUES ('$title', '$description', '$date', '$user_id')";
    if (mysqli_query($conn, $query)) {
        echo "Evenimentul a fost creat cu succes!";
        header("Location: index.php");  // Redirecționează utilizatorul către pagina principală
    } else {
        echo "Eroare la crearea evenimentului: " . mysqli_error($conn);
    }
}
?>

<main>
    <h1>Crează un Eveniment Nou</h1>
    <form action="create-event.php" method="post">
        <input type="text" name="title" placeholder="Titlu Eveniment" required><br>
        <textarea name="description" placeholder="Descriere" required></textarea><br>
        <input type="date" name="date" required><br>
        <button type="submit">Crează </button>
    </form>
</main>

<?php include('includes/footer.php'); ?>
