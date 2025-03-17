<!-- includes/header.php -->
<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seabrook Community</title>
 

    <link rel="stylesheet" href="./assets/index.css">
</head>
<header class="header">
    <div class="container">
        <div class="nav__bar">
            <!-- Logo cu dimensiuni controlate -->
            <img src="./img/logo__img.svg" alt="Logo Comunitatea Seabrook" class="header__logo" width="150" height="auto">
            
            <!-- Titlul secțiunii -->
            <h1 class="header__title">Events that Inspire, Connections that Last</h1>

            <!-- Navigare -->
            <ul class="nav__list">
                <li class="nav__item">
                    <a class="nav__link" href="index.php">Home</a>
                </li>
                <li class="nav__item">
                    <a class="nav__link" href="events.php">Events</a>
                </li>
                <li class="nav__item">
                    <a class="nav__link" href="contact.php">Contact</a> <!-- Aș schimba linkul pentru Contact -->
                </li>
                <li class="nav__item">
                    <button class="nav__link--btn" onclick="window.location.href='login.php';">Login</button>
                </li>
                <li class="nav__item">
                    <button class="nav__link--btn" onclick="window.location.href='register.php';">Sign Up</button>
                </li>
            </ul>
        </div>
   
    </div>
   
</header>
