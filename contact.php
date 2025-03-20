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
    <title>Seabrook Community</title>
    <link rel="stylesheet" href="assets/style.css">
    <style>
        /* General styles */
    
      .contact-content {
        width: 80%;
            margin: 0 auto;
            padding: 20px;
      
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        h1, h2 {
            color: #333;
            text-align: center;
        }

        .contact-info {
            margin-top: 20px;
            font-size: 18px;
            color: #555;
        }

        .contact-info a {
            color: #007BFF;
            text-decoration: none;
        }

        .contact-info a:hover {
            text-decoration: underline;
        }

        /* Form Styles */
        .contact-form {
            display: flex;
            flex-direction: column;
            gap: 15px;
            margin-top: 20px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        .form-group label {
            font-size: 16px;
            margin-bottom: 5px;
            color: #333;
        }

        .form-group input,
        .form-group textarea {
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .form-group input:focus,
        .form-group textarea:focus {
            border-color: #007BFF;
            outline: none;
        }

        .form-group textarea {
            resize: vertical;
            min-height: 120px;
        }

        .submit-btn {
            padding: 12px 20px;
            background-color: #007BFF;
            color: white;
            font-size: 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .submit-btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
<header class="header">
    <div class="container">
        <div class="nav__bar">
            <img src="./img/logo__img.svg" alt="Seabrook Community Logo" class="header__logo" width="150" height="auto">
            <h1 class="header__title">Connecting Ideas, Empowering Growth!</h1>
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
        <h1>Contact Us</h1>
        <div class="contact-info">
            <p>You can contact us at the following phone number:</p>
            <p><strong><a href="tel:<?php echo $telefon; ?>"><?php echo $telefon; ?></a></strong></p>
            <p>You can also send us an email at <a href="mailto:<?php echo $email; ?>"><?php echo $email; ?></a>.</p>
            <p>Our address is: <strong><?php echo $adresa; ?></strong></p>
        </div>

        <h2>Send us a message</h2>
        <!-- Contact Form -->
        <form action="send_message.php" method="POST" class="contact-form">
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

</body>
</html>
