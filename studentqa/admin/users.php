<!-- filepath: c:\xampp\htdocs\studentqa\admin\users.php -->
<?php
session_start();
require_once '../config/db.php';

// Authorization: Only admin can access this page
if (!isset($_SESSION['user']) || !isset($_SESSION['user']['role']) || $_SESSION['user']['role'] != 'admin') {
    echo "<p style='color:red;'>You do not have permission to access this page.</p>";
    exit;
}

// Handle deleting a question
if (isset($_GET['delete_question'])) {
    try {
        $stmt = $pdo->prepare("DELETE FROM questions WHERE id = ?");
        $stmt->execute([$_GET['delete_question']]);
        $success = "Question deleted successfully.";
    } catch (PDOException $e) {
        $error = "Error deleting question: " . $e->getMessage();
    }
}

// Fetch all users
$users = $pdo->query("SELECT * FROM users")->fetchAll();

// Fetch all questions
$questions = $pdo->query("SELECT q.id, q.title, u.username, q.created_at 
                          FROM questions q 
                          JOIN users u ON q.user_id = u.id 
                          ORDER BY q.created_at DESC")->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users and Questions</title>
    <link rel="stylesheet" href="/studentqa/assets/style.css">
</head>
<body>
    <main class="admin-container">
        <h1>Manage Users</h1>

        <!-- Display Success or Error Messages -->
        <?php if (isset($success)): ?>
            <p style="color: green;"><?= htmlspecialchars($success) ?></p>
        <?php endif; ?>
        <?php if (isset($error)): ?>
            <p style="color: red;"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>

        <!-- Add User Form -->
        <form method="POST" class="admin-form">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" placeholder="Enter username" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" placeholder="Enter email" required>

            <button type="submit" name="add">Add User</button>
        </form>

        <!-- Users List -->
        <h2>Users List</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?= htmlspecialchars($user['id']) ?></td>
                        <td><?= htmlspecialchars($user['username']) ?></td>
                        <td><?= htmlspecialchars($user['email']) ?></td>
                        <td class="action-buttons">
                            <a href="?delete=<?= htmlspecialchars($user['id']) ?>" onclick="return confirm('Are you sure you want to delete this user?')">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Questions List -->
        <h2>Questions List</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Author</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($questions as $question): ?>
                    <tr>
                        <td><?= htmlspecialchars($question['id']) ?></td>
                        <td><?= htmlspecialchars($question['title']) ?></td>
                        <td><?= htmlspecialchars($question['username']) ?></td>
                        <td><?= htmlspecialchars($question['created_at']) ?></td>
                        <td class="action-buttons">
                            <a href="?delete_question=<?= htmlspecialchars($question['id']) ?>" onclick="return confirm('Are you sure you want to delete this question?')">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <a href="../index.php" class="home-link">Back to Home</a>
    </main>
</body>
</html>