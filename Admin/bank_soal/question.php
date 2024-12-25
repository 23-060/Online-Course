<?php
// classes/Question.php
class Question {
    private $pdo;
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    
    public function getQuestions($category) {
        $stmt = $this->pdo->prepare("SELECT * FROM questions WHERE category = ? ORDER BY id DESC");
        $stmt->execute([$category]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addQuestion($data) {
        $query = "INSERT INTO questions (category, question, options, correct_answer, media_type, media_url) 
                  VALUES (:category, :question, :options, :correct_answer, :media_type, :media_url)";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute([
            ':category' => $data['category'],
            ':question' => $data['question'],
            ':options' => $data['options'],
            ':correct_answer' => $data['correct_answer'],
            ':media_type' => $data['media_type'],
            ':media_url' => $data['media_url']
        ]);
    }
    
    
    public function updateQuestion($id, $data) {
        $stmt = $this->pdo->prepare("UPDATE questions SET 
                                    question = ?, 
                                    options = ?, 
                                    correct_answer = ?, 
                                    media_type = ?, 
                                    media_url = ? 
                                    WHERE id = ?");
        return $stmt->execute([
            $data['question'],
            json_encode($data['options']),
            $data['correct_answer'],
            $data['media_type'],
            $data['media_url'],
            $id
        ]);
    }
    
    public function deleteQuestion($id) {
        $stmt = $this->pdo->prepare("DELETE FROM questions WHERE id = ?");
        return $stmt->execute([$id]);
    }
}