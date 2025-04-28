<!-- filepath: c:\xampp\htdocs\studentqa\admin\messages.php -->
<?php
require_once '../config/db.php';

$messages = $pdo->query("SELECT * FROM contact_messages ORDER BY created_at DESC")->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Messages</title>
    <link rel="stylesheet" href="/studentqa/assets/style.css">
</head>
<body>
    <main class="messages-container">
        <h1>Contact Messages</h1>

        <!-- Display Messages -->
        <?php if (empty($messages)): ?>
            <p class="no-messages">No messages yet.</p>
        <?php else: ?>
            <table class="messages-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Message</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($messages as $msg): ?>
                        <tr>
                            <td><?= htmlspecialchars($msg['name']) ?></td>
                            <td><?= htmlspecialchars($msg['email']) ?></td>
                            <td><?= nl2br(htmlspecialchars($msg['message'])) ?></td>
                            <td><?= htmlspecialchars($msg['created_at']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>

        <a href="../index.php" class="home-link">Back to Home</a>
    </main>
</body>
</html>