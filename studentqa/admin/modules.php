<!-- filepath: c:\xampp\htdocs\studentqa\admin\modules.php -->
<?php
require_once '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $module = $_POST['name'];
    $stmt = $pdo->prepare("INSERT INTO modules (name) VALUES (?)");
    $stmt->execute([$module]);
    $success = "Module added successfully.";
}

if (isset($_GET['delete'])) {
    $stmt = $pdo->prepare("DELETE FROM modules WHERE id = ?");
    $stmt->execute([$_GET['delete']]);
    $success = "Module deleted successfully.";
}

$modules = $pdo->query("SELECT * FROM modules")->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Modules</title>
    <link rel="stylesheet" href="/studentqa/assets/style.css">
</head>
<body>
    <main class="modules-container">
        <h1>Manage Modules</h1>

        <!-- Display Success Messages -->
        <?php if (isset($success)): ?>
            <p style="color: green;"><?= htmlspecialchars($success) ?></p>
        <?php endif; ?>

        <!-- Add Module Form -->
        <form method="POST">
            <label for="module-name">Module Name:</label>
            <input type="text" id="module-name" name="name" placeholder="Enter module name" required>
            <button type="submit">Add Module</button>
        </form>

        <!-- Modules List -->
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Module Name</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($modules as $module): ?>
                    <tr>
                        <td><?= htmlspecialchars($module['id']) ?></td>
                        <td><?= htmlspecialchars($module['name']) ?></td>
                        <td class="action-buttons">
                            <a href="?delete=<?= htmlspecialchars($module['id']) ?>" onclick="return confirm('Are you sure you want to delete this module?')">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <a href="../index.php" class="home-link">Back to Home</a>
    </main>
</body>
</html>