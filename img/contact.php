<?php
// Contact information variables
$telefon = "+447454185152";
$email = "arcadiiflorean789@gmail.com";
$adresa = "4 Burton Avenue, DN4 8BB, Doncaster, United Kingdom";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seabrook Community - Contact</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>

<header class="header">
    <div class="container">
        <div class="nav__bar">
            <img src="./img/logo__img.svg" alt="Seabrook Community Logo" class="header__logo" width="150">
            <h1 class="header__title">Together for a Better Tomorrow!</h1>
            <ul class="nav__list">
                <li class="nav__item"><a class="nav__link" href="index.php">Home</a></li>
                <li class="nav__item"><a class="nav__link" href="events.php">Events</a></li>
                <li class="nav__item"><a class="nav__link" href="contact.php">Contact</a></li>
                <li class="nav__item"><button class="nav__link--btn" onclick="window.location.href='login.php';">Login</button></li>
                <li class="nav__item"><button class="nav__link--btn" onclick="window.location.href='register.php';">Sign Up</button></li>
            </ul>
        </div>
    </div>
</header>

<section class="section-contact">
    <div class="container">
        <div class="contact-content">
            <div class="contact-details">
            <h1>Contact Us</h1>
            <p class="contact-welcome" >We’re here to help! If you have any questions, feel free to reach out using the details below or send us a message.</p>

           
                <p><strong>📞 Phone:</strong> <a href="tel:<?php echo $telefon; ?>"><?php echo $telefon; ?></a></p>
                <p><strong>📧 Email:</strong> <a href="mailto:<?php echo $email; ?>"><?php echo $email; ?></a></p>
                <p><strong>📍 Address:</strong> <?php echo $adresa; ?></p>
                <div class="map-container">
   
    <section class="contact-location">
    <h2>Our Location</h2>
    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d13623.867847647598!2d-1.1349863748231142!3d53.52110755410088!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x48790e85c178ab89%3A0xa0357f24c5532956!2sSouth%20Yorkshire%20Aircraft%20Museum!5e0!3m2!1sro!2suk!4v1742506444736!5m2!1sro!2suk"
        width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
</section>


</div>

            
</div>
            <!-- Contact Form -->
            <form action="send_message.php" method="POST" class="contact-form">
                <h2>Send us a message</h2>
                <div class="form-group">
                    <label for="name">Your Name</label>
                    <input type="text" id="name" name="name" required>
                </div>
                <div class="form-group">
                    <label for="email">Your Email</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="message">Your Message</label>
                    <textarea id="message" name="message" rows="4" required></textarea>
                </div>
                <button type="submit" class="submit-btn">Send Message</button>
            </form>
        </div>
    </div>
</section>

<footer class="footer">
    <p class="footer__text">&copy; 2025 Seabrook Community. All rights reserved.</p>
</footer>

</body>
</html>
