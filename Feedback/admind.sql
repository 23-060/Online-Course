-- Tabel untuk menyimpan balasan admin
CREATE TABLE feedback_reply (
    id_reply INT AUTO_INCREMENT PRIMARY KEY,
    id_feedback INT NOT NULL,
    reply TEXT NOT NULL,
    replied_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_feedback) REFERENCES feedback(id_feedback) ON DELETE CASCADE
);
