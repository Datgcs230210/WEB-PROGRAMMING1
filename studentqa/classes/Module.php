<?php
require_once '../config/db.php';
include '../includes/header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $module = trim($_POST['name']); // Important: Remove extra spaces
    if (!empty($module)) { // Important:  Don't add empty modules
        $stmt = $pdo->prepare("INSERT INTO modules (name) VALUES (?)");
        $stmt->execute([$module]);
    }
}

if (isset($_GET['delete'])) {
    $stmt = $pdo->prepare("DELETE FROM modules WHERE id = ?");
    $stmt->execute([$_GET['delete']]);
}

$modules = $pdo->query("SELECT * FROM modules ORDER BY name ASC")->fetchAll(); // Order alphabetically
?>

<h2>Manage Modules</h2>
<form method="POST">
    <input type="text" name="name" placeholder="Module Name" required>
    <button type="submit">Add Module</button>
</form>

<ul>
    <?php foreach ($modules as $module): ?>
        <li>
            <?= htmlspecialchars($module['name']) ?>
            <a href="?delete=<?= $module['id'] ?>" onclick="return confirm('Delete module?')">[Delete]</a>
        </li>
    <?php endforeach; ?>
</ul>

<?php include '../includes/footer.php'; ?>

