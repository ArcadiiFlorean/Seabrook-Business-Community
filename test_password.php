<?php
// Parola introdusă la login
$password = 'Florean';

// Parola criptată din baza de date
$hashed_password_from_db = '1ee4d3e72d9f9b3b8bdbc1f7d4bb045'; // Schimbă aceasta cu valoarea reală din baza ta de date

// Verifică dacă parola introdusă corespunde cu parola criptată din baza de date
if (password_verify($password, $hashed_password_from_db)) {
    echo "Parola este corectă!";
} else {
    echo "Parola este incorectă!";
}
?>
