<?php
require_once 'config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $message = $_POST['message'];

    try {
        $stmt = $pdo->prepare("INSERT INTO contact_messages (name, email, message, created_at) VALUES (?, ?, ?, NOW())");
        $stmt->execute([$name, $email, $message]);

        echo "<p style='color:green;'>Message sent. Thank you!</p>";
    } catch (PDOException $e) {
        echo "<p style='color:red;'>Error sending message: " . htmlspecialchars($e->getMessage()) . "</p>";
        error_log("Contact form error: " . $e->getMessage());
    }
}
?>

<form method="POST">
    <input type="text" name="name" placeholder="Your Name" required><br>
    <input type="email" name="email" placeholder="Your Email" required><br>
    <textarea name="message" placeholder="Message" required></textarea><br>
    <button type="submit">Send</button>
</form>
