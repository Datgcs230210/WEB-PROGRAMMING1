<?php
class Question {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getAll() {
        $stmt = $this->pdo->query("SELECT q.*, u.username, m.name AS module 
                                   FROM questions q 
                                   JOIN users u ON q.user_id = u.id 
                                   JOIN modules m ON q.module_id = m.id 
                                   ORDER BY q.created_at DESC");
        return $stmt->fetchAll();
    }

    public function create($title, $body, $user_id, $module_id, $image) {
        $stmt = $this->pdo->prepare("INSERT INTO questions (title, body, user_id, module_id, image) 
                                     VALUES (?, ?, ?, ?, ?)");
        return $stmt->execute([$title, $body, $user_id, $module_id, $image]);
    }

    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM questions WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function getById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM questions WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function update($id, $title, $body, $user_id, $module_id, $image) {
        $stmt = $this->pdo->prepare("UPDATE questions SET title=?, body=?, user_id=?, module_id=?, image=? WHERE id=?");
        return $stmt->execute([$title, $body, $user_id, $module_id, $image, $id]);
    }
}
?>

