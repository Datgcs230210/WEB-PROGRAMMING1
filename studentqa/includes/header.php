<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Student Q&A</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<header>
    <h1>Student Q&A Platform</h1>
    <nav>
        <a href="/student_qa/index.php">Home</a>
        <a href="/student_qa/add-question.php">Add Question</a>
        <a href="/student_qa/contact.php">Contact</a>
        <?php if (isset($_SESSION['user'])): ?>
            <span>Welcome, <?= htmlspecialchars($_SESSION['user']['username']) ?></span>
            <a href="/student_qa/logout.php">Logout</a>
        <?php else: ?>
            <a href="/student_qa/login.php">Login</a>
            <a href="/student_qa/register.php">Register</a>
        <?php endif; ?>
    </nav>
</header>
<main>
