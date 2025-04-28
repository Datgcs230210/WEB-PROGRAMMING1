<!-- filepath: c:\xampp\htdocs\studentqa\index.php -->
<?php
session_start();

if (isset($_SESSION['login_success'])) {
    $loginMessage = $_SESSION['login_success'];
    unset($_SESSION['login_success']);
}

require_once 'config/db.php';
require_once 'classes/Question.php';

$question = new Question($pdo);
$questions = $question->getAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Q&A</title>
    <link rel="stylesheet" href="/studentqa/assets/style.css">
</head>
<body>
    <header>
        <h1>Student Q&A</h1>

        <!-- User Info Section -->
        <div class="user-info">
            <?php if (isset($_SESSION['user'])): ?>
                <span>Welcome, <?= htmlspecialchars($_SESSION['user']['username']) ?></span>
                <a href="logout.php">Logout</a>
                <?php if (isset($_SESSION['user']['role']) && $_SESSION['user']['role'] === 'admin'): ?>
                    <a href="admin/index.php">Admin</a>
                <?php endif; ?>
            <?php else: ?>
                <a href="login.php">Login</a>
                <a href="register.php">Register</a>
            <?php endif; ?>
        </div>

        <!-- Navigation Menu -->
        <nav>
            <a href="index.php">Home</a>
            <a href="add-question.php">Add a Question</a>
            <a href="contact.php">Contact</a>
        </nav>
    </header>

    <main class="container">
        <!-- Display Login Success Message -->
        <?php if (isset($loginMessage)): ?>
            <p style="color: green;"><?= htmlspecialchars($loginMessage) ?></p>
        <?php endif; ?>

        <!-- Questions List -->
        <ul>
            <?php foreach ($questions as $q): ?>
                <li>
                    <h3 class="post-title"><?= htmlspecialchars($q['title']) ?></h3>
                    <p><?= nl2br(htmlspecialchars($q['body'])) ?></p>
                    <small class="post-meta">
                        Posted by <?= htmlspecialchars($q['username']) ?> in <?= htmlspecialchars($q['module']) ?> on <?= $q['created_at'] ?>
                    </small><br>
                    
                    <?php if ($q['image']): ?>
                        <img src="<?= htmlspecialchars($q['image']) ?>" alt="Screenshot">
                    <?php endif; ?>
                    <br>

                    <?php if (isset($_SESSION['user']) && $_SESSION['user']['id'] === $q['user_id']): ?>
                        <a href="edit-question.php?id=<?= htmlspecialchars($q['id']) ?>">Edit</a> |
                        <a href="delete-question.php?id=<?= htmlspecialchars($q['id']) ?>" onclick="return confirm('Are you sure?')">Delete</a>
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
        </ul>
    </main>

    <footer>
        <p>&copy; <?= date('Y') ?> Student Q&A Platform</p>
    </footer>
</body>
</html>