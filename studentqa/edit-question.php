<?php
require_once 'config/db.php';
require_once 'classes/Question.php';

$question = new Question($pdo);

$id = $_GET['id'] ?? null;
if (!$id) {
    die("Question ID is required.");
}

$data = $question->getById($id);
$users = $pdo->query("SELECT * FROM users")->fetchAll();
$modules = $pdo->query("SELECT * FROM modules")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $body = $_POST['body'];
    $user_id = $_POST['user_id'];
    $module_id = $_POST['module_id'];

    $image = $data['image']; // keep existing
    if (!empty($_FILES['image']['name'])) {
        $image = 'assets/images/uploads/' . basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], $image);
    }

    $question->update($id, $title, $body, $user_id, $module_id, $image);
    header("Location: index.php");
    exit;
}
?>

<form method="POST" enctype="multipart/form-data">
    <input type="text" name="title" value="<?= $data['title'] ?>" required><br>
    <textarea name="body" required><?= $data['body'] ?></textarea><br>

    <select name="user_id">
        <?php foreach ($users as $user): ?>
            <option value="<?= $user['id'] ?>" <?= $user['id'] == $data['user_id'] ? 'selected' : '' ?>>
                <?= $user['username'] ?>
            </option>
        <?php endforeach; ?>
    </select><br>

    <select name="module_id">
        <?php foreach ($modules as $module): ?>
            <option value="<?= $module['id'] ?>" <?= $module['id'] == $data['module_id'] ? 'selected' : '' ?>>
                <?= $module['name'] ?>
            </option>
        <?php endforeach; ?>
    </select><br>

    <input type="file" name="image"><br>
    <button type="submit">Update Question</button>
</form>
