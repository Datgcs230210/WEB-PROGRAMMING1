<!-- filepath: c:\xampp\htdocs\studentqa\register.php -->
<?php
session_start();
require_once 'config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];

    // Check if the email already exists
    $stmtCheck = $pdo->prepare("SELECT email FROM users WHERE email = ?");
    $stmtCheck->execute([$email]);
    $existingUser = $stmtCheck->fetch();

    if ($existingUser) {
        $error = "This email account already exists!";
    } else {
        $stmtInsert = $pdo->prepare("INSERT INTO users (username, email) VALUES (?, ?)");
        if ($stmtInsert->execute([$username, $email])) {
            $_SESSION['register_success'] = "REGISTER SUCCESSFULLY. PLEASE LOGIN.";
            header("Location: login.php");
            exit;
        } else {
            $error = "An error occurred during the registration process.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="/studentqa/assets/style.css">
</head>
<body>
    <main class="register-container">
        <h1>Register Your Account</h1>
        <?php if (isset($error)): ?>
            <p style="color: red;"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>
        <form method="post" action="">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" placeholder="Enter your username" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" placeholder="Enter your email" required>

            <button type="submit">Register</button>
        </form>
        <a href="login.php" class="login-link">Already have an account? Login here</a>
    </main>
</body>
</html>