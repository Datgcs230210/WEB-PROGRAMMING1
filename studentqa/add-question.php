
<?php
require_once 'config/db.php';
require_once 'classes/Question.php';

$question = new Question($pdo);

// Fetch users for dropdown
try {
    $usersStmt = $pdo->query("SELECT id, username FROM users");
    $users = $usersStmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("<p style='color:red;'>Database error fetching users: " . htmlspecialchars($e->getMessage()) . "</p>");
}

// Fetch modules for dropdown
try {
    $modulesStmt = $pdo->query("SELECT id, name FROM modules ORDER BY name ASC");
    $modules = $modulesStmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("<p style='color:red;'>Database error fetching modules: " . htmlspecialchars($e->getMessage()) . "</p>");
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'] ?? '';
    $body = $_POST['body'] ?? '';
    $user_id = $_POST['user_id'] ?? '';
    $module_id = $_POST['module_id'] ?? '';
    $image = '';

    // Validate inputs
    if (empty($title) || empty($body) || empty($user_id) || empty($module_id)) {
        echo "<p style='color:red;'>All fields are required.</p>";
    } else {
        // Handle image upload
        if (!empty($_FILES['image']['name'])) {
            $uploadDir = 'assets/images/uploads/';
            $image = $uploadDir . basename($_FILES['image']['name']);
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            if (!move_uploaded_file($_FILES['image']['tmp_name'], $image)) {
                echo "<p style='color:red;'>Error uploading image. Question not added.</p>";
                $image = ''; // Reset image if upload fails
            }
        }

        // Insert question into database
        try {
            $question->create($title, $body, $user_id, $module_id, $image);
            header("Location: index.php");
            exit;
        } catch (PDOException $e) {
            echo "<p style='color:red;'>Database error inserting question: " . htmlspecialchars($e->getMessage()) . "</p>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add a Question</title>
    <link rel="stylesheet" href="/studentqa/assets/style.css">
</head>
<body>
    <header>
        <h1>Add a Question</h1>
    </header>
    <main class="container">
        <form method="POST" enctype="multipart/form-data">
            <label for="title">Title:</label>
            <input type="text" name="title" id="title" placeholder="Enter the title" required>

            <label for="body">Your Question:</label>
            <textarea name="body" id="body" rows="5" placeholder="Write your question here" required></textarea>

            <label for="user_id">Select User:</label>
            <select name="user_id" id="user_id" required>
                <option value="">Select User</option>
                <?php foreach ($users as $user): ?>
                    <option value="<?= htmlspecialchars($user['id']) ?>">
                        <?= htmlspecialchars($user['username']) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label for="module_id">Select Module:</label>
            <select name="module_id" id="module_id" required>
                <option value="">Select Module</option>
                <?php foreach ($modules as $module): ?>
                    <option value="<?= htmlspecialchars($module['id']) ?>">
                        <?= htmlspecialchars($module['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label for="image">Upload Image:</label>
            <input type="file" name="image" id="image">

            <button type="submit">Post Question</button>
        </form>
        <a href="index.php" class="home-link">Back to Home</a>
    </main>
    <?php include 'includes/footer.php'; ?>
</body>
</html>