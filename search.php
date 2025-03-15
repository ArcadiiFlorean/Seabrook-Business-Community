<?php
session_start();
include 'config/db.php'; // Dacă db.php este într-un subfolder "config"
if (isset($_GET['query'])) {
    $query = mysqli_real_escape_string($conn, $_GET['query']);
    $search_query = "SELECT * FROM events WHERE title LIKE '%$query%'";
    $result = mysqli_query($conn, $search_query);
}

?>

<main>
    <h1>Rezultatele Căutării</h1>
    <div>
        <?php
        if (isset($result) && mysqli_num_rows($result) > 0) {
            while ($event = mysqli_fetch_assoc($result)) {
                echo "<h2><a href='event-details.php?id={$event['id']}'>{$event['title']}</a></h2>";
                echo "<p>{$event['description']}</p>";
            }
        } else {
            echo "Nu au fost găsite evenimente.";
        }
        ?>
    </div>
</main>

<?php include('includes/footer.php'); ?>
