<?php
$host = 'localhost'; // sau adresa serverului MySQL
$dbname = 'seabrook_community';
$username = 'root'; // sau utilizatorul tău
$password = ''; // parola (dacă există)

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Eroare la conectare: " . $e->getMessage());
}
?>
