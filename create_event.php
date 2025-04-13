<?php
session_start();
include('includes/header.php');
include 'config/db.php'; // Acest fișier definește variabila $pdo

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $date = $_POST['date'];
    $user_id = $_SESSION['user_id'];

    try {
        $query = "INSERT INTO events (title, description, date, user_id) VALUES (:title, :description, :date, :user_id)";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':date', $date);
        $stmt->bindParam(':user_id', $user_id);

        if ($stmt->execute()) {
            echo "Evenimentul a fost creat cu succes!";
            header("Location: index.php");
            exit();
        } else {
            echo "Eroare la crearea evenimentului.";
        }
    } catch (PDOException $e) {
        echo "Eroare SQL: " . $e->getMessage();
    }
}
?>

<main>
    <h1>Crează un Eveniment Nou</h1>
    <form action="create-event.php" method="post">
        <input type="text" name="title" placeholder="Titlu Eveniment" required><br>
        <textarea name="description" placeholder="Descriere" required></textarea><br>
        <input type="date" name="date" required><br>
        <button type="submit">Crează</button>
    </form>
</main>

<?php include('includes/footer.php'); ?>
