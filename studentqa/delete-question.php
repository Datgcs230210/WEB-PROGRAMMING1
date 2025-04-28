<?php
session_start();
require_once 'config/db.php';
require_once 'classes/Question.php';

$question = new Question($pdo);
$id = $_GET['id'] ?? null;

if ($id && filter_var($id, FILTER_VALIDATE_INT)) {
    try {
        // Check if the question exists and belongs to the logged-in user
        $stmt = $pdo->prepare("SELECT * FROM questions WHERE id = ?");
        $stmt->execute([$id]);
        $questionData = $stmt->fetch();

        if ($questionData) {
            // Delete the question
            $question->delete($id);
            $_SESSION['success_message'] = "Question deleted successfully.";
        } else {
            $_SESSION['error_message'] = "Question not found or you are not authorized to delete it.";
        }
    } catch (PDOException $e) {
        error_log("Error deleting question: " . $e->getMessage());
        $_SESSION['error_message'] = "An error occurred while trying to delete the question.";
    }
} else {
    $_SESSION['error_message'] = "Invalid question ID.";
}

header("Location: index.php");
exit;