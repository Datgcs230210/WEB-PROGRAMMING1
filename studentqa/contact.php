<!-- filepath: c:\xampp\htdocs\studentqa\contact.php -->
<?php
session_start();
require_once 'config/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $message = htmlspecialchars($_POST['message']);

    try {
        $stmt = $pdo->prepare("INSERT INTO contact_messages (name, email, message, created_at) VALUES (?, ?, ?, NOW())");
        $stmt->execute([$name, $email, $message]);

        // Set success message and redirect
        $_SESSION['contact_success'] = "Message sent successfully!";
        header('Location: contact.php');
        exit();
    } catch (PDOException $e) {
        // Log error and set error message
        error_log("Contact form database error: " . $e->getMessage());
        $_SESSION['contact_error'] = "There was an error sending your message. Please try again later.";
        header('Location: contact.php');
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us</title>
    <link rel="stylesheet" href="/studentqa/assets/style.css">
</head>
<body>
    <header>
        <h1>Contact Us</h1>
    </header>
    <main class="contact-container">
        <?php if (isset($_SESSION['contact_success'])): ?>
            <p style="color: green;"><?= htmlspecialchars($_SESSION['contact_success']) ?></p>
            <?php unset($_SESSION['contact_success']); ?>
        <?php elseif (isset($_SESSION['contact_error'])): ?>
            <p style="color: red;"><?= htmlspecialchars($_SESSION['contact_error']) ?></p>
            <?php unset($_SESSION['contact_error']); ?>
        <?php endif; ?>

        <form method="post" action="">
            <label for="name">Your Name:</label>
            <input type="text" name="name" id="name" placeholder="Enter your name" required>

            <label for="email">Your Email:</label>
            <input type="email" name="email" id="email" placeholder="Enter your email" required>

            <label for="message">Your Message:</label>
            <textarea name="message" id="message" placeholder="Write your message here" required></textarea>

            <button type="submit">Send</button>
        </form>
        <a href="index.php" class="home-link">Back to Home</a>
    </main>
    <?php include 'includes/footer.php'; ?>
</body>
</html>