<?php
// Sample data ulasan pengguna
$reviews = [
    [
        'name' => 'Anton',
        'date' => '18 Desember 2024',
        'content' => 'Kursusnya sangat membantu!',
        'likes' => 2,
        'dislikes' => 0,
        'rating' => 5,
        'adminResponse' => 'Terima kasih atas ulasannya!'
    ],
    [
        'name' => 'Dewi',
        'date' => '17 Desember 2024',
        'content' => 'Materi cukup baik, tapi bisa lebih mendalam lagi.',
        'likes' => 1,
        'dislikes' => 1,
        'rating' => 4,
        'adminResponse' => 'Terima kasih atas masukannya, Dewi. Kami akan meningkatkan kualitas materi.'
    ],
    [
        'name' => 'Rizky',
        'date' => date('d F Y'),
        'content' => 'aplikasi nya bagus',
        'likes' => 0,
        'dislikes' => 0,
        'rating' => 5,
        'adminResponse' => ''
    ]
];
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Ulasan Pengguna</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
            color: #333;
        }

        .header {
            background-color: #0052d4;
            color: white;
            padding: 1.5rem 2rem;
            text-align: center;
        }

        .reviews-list {
            margin: 3rem 0;
            padding: 0 2rem;
        }

        .review-card {
            background-color: white;
            border-radius: 10px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .review-card header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .review-card .reviewer-name {
            font-weight: bold;
            font-size: 1.1rem;
        }

        .review-card .review-date {
            font-size: 0.9rem;
            color: #666;
        }

        .review-card p {
            margin-top: 1rem;
            font-size: 1rem;
        }

        .review-card .rating {
            margin-top: 0.5rem;
            font-size: 1rem;
            font-weight: bold;
            color: #ffaa00;
        }

        .like-dislike {
            margin-top: 1rem;
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .like-dislike button {
            background-color: #fff;
            border: 1px solid #ddd;
            padding: 0.5rem 1rem;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .like-dislike button:hover {
            background-color: #f0f0f0;
        }

        .admin-response {
            margin-top: 1rem;
            padding-left: 1rem;
            border-left: 3px solid #0052d4;
            font-style: italic;
            color: #555;
        }

        .admin-form textarea {
            width: 100%;
            padding: 0.5rem;
            font-size: 1rem;
            border: 1px solid #ddd;
            border-radius: 8px;
            resize: none;
            margin-bottom: 0.5rem;
            min-height: 50px;
        }

        .admin-form button {
            padding: 0.5rem 1rem;
            background-color: #0052d4;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 0.9rem;
        }
    </style>
</head>

<body>

    <!-- Header Section -->
    <header class="header">
        <h1>Admin - Ulasan Pengguna</h1>
    </header>

    <!-- Reviews List -->
    <section class="reviews-list">
        <h2>Ulasan Pengguna</h2>
        <?php foreach ($reviews as $review): ?>
            <div class="review-card">
                <header>
                    <div class="reviewer-name"><?= htmlspecialchars($review['name']) ?></div>
                    <div class="review-date"><?= htmlspecialchars($review['date']) ?></div>
                </header>
                <p><?= htmlspecialchars($review['content']) ?></p>
                <div class="rating">Rating: <?= $review['rating'] ?> / 5</div>
                <div class="like-dislike">
                    <button onclick="likeReview(this)">👍 <?= $review['likes'] ?></button>
                    <button onclick="dislikeReview(this)">👎 <?= $review['dislikes'] ?></button>
                </div>
                <div class="admin-response">
                    Admin: <?= htmlspecialchars($review['adminResponse']) ?: 'Belum ada balasan.' ?>
                </div>
                <div class="admin-form">
                    <textarea placeholder="Balas ulasan..."></textarea>
                    <button onclick="replyReview(this)">Balas</button>
                </div>
            </div>
        <?php endforeach; ?>
    </section>

    <script>
        function likeReview(button) {
            const count = parseInt(button.innerText.split(' ')[1]) + 1;
            button.innerText = `👍 ${count}`;
        }

        function dislikeReview(button) {
            const count = parseInt(button.innerText.split(' ')[1]) + 1;
            button.innerText = `👎 ${count}`;
        }

        function replyReview(button) {
            const textarea = button.previousElementSibling;
            const response = textarea.value;

            if (response.trim().length > 0) {
                const responseDiv = button.closest('.review-card').querySelector('.admin-response');
                responseDiv.innerText = `Admin: ${response}`;
                textarea.value = "";
            } else {
                alert("Harap tulis balasan terlebih dahulu.");
            }
        }
    </script>

</body>

</html>