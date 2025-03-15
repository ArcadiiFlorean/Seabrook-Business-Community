<?php
$host = 'localhost';
$dbname = 'seabrook_community';
$username = 'root'; // Folosește utilizatorul tău de bază de date
$password = ''; // Folosește parola ta pentru baza de date, dacă este cazul

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'Conectare eșuată: ' . $e->getMessage();
    exit();
}
?>
