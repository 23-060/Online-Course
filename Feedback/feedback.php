<?php ob_start(); ?>
<?php
// Mulai sesi untuk menyimpan ulasan
session_start();

// Data ulasan default
if (!isset($_SESSION['reviews'])) {
    $_SESSION['reviews'] = [];
}

// Tambahkan ulasan baru jika form dikirim
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $content = htmlspecialchars($_POST['content']);
    $rating = intval($_POST['rating']);
    $date = date('d F Y', time()); // Tanggal hari ini
    $name = "Rizky"; // Nama default

    // Tambahkan ulasan ke dalam sesi
    $_SESSION['reviews'][] = [
        "name" => $name,
        "date" => $date,
        "content" => $content,
        "rating" => $rating
    ];
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ulasan dan Feedback</title>
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

        .review-form {
            background-color: white;
            border-radius: 10px;
            padding: 2rem;
            width: 50%;
            margin: 2rem auto;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        }

        .review-form h2 {
            margin-bottom: 1rem;
            color: #333;
        }

        .review-form textarea {
            width: 100%;
            padding: 1rem;
            font-size: 1rem;
            border: 1px solid #ddd;
            border-radius: 8px;
            resize: none;
            margin-bottom: 1rem;
            min-height: 150px;
        }

        .review-form .star-rating {
            display: flex;
            gap: 5px;
            margin-bottom: 1rem;
        }

        .review-form .star-rating span {
            font-size: 2rem;
            color: #ddd;
            cursor: pointer;
        }

        .review-form .star-rating span.active {
            color: #f4d03f;
        }

        .review-form button {
            padding: 0.8rem 1.5rem;
            background-color: #0052d4;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 1rem;
            transition: background-color 0.3s;
        }

        .review-form button:hover {
            background-color: #4364f7;
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
    </style>
</head>

<body>

    <!-- Header Section -->
    <header class="header">
        <h1>Ulasan dan Kritik Kursus</h1>
    </header>

    <!-- Review Form -->
    <section class="review-form">
        <h2>Berikan Ulasan Anda</h2>
        <form method="POST" action="">
            <div class="star-rating" id="starRating">
                <span data-rating="1">&#9733;</span>
                <span data-rating="2">&#9733;</span>
                <span data-rating="3">&#9733;</span>
                <span data-rating="4">&#9733;</span>
                <span data-rating="5">&#9733;</span>
            </div>
            <input type="hidden" name="rating" id="ratingInput" value="0">
            <textarea name="content" placeholder="Tulis ulasan atau kritik Anda di sini..." required></textarea>
            <button type="submit">Kirim Ulasan</button>
        </form>
    </section>

    <!-- Reviews List -->
    <section class="reviews-list">
        <h2>Ulasan Pengguna</h2>
        <?php
        // Tampilkan ulasan
        foreach ($_SESSION['reviews'] as $review) {
            echo "
            <div class='review-card'>
                <header>
                    <div class='reviewer-name'>{$review['name']} " . str_repeat("★", $review['rating']) . "</div>
                    <div class='review-date'>{$review['date']}</div>
                </header>
                <p>{$review['content']}</p>
            </div>
            ";
        }
        ?>
    </section>

    <script>
        // Bintang interaktif
        const stars = document.querySelectorAll('#starRating span');
        const ratingInput = document.getElementById('ratingInput');

        stars.forEach(star => {
            star.addEventListener('click', () => {
                const rating = star.getAttribute('data-rating');
                ratingInput.value = rating; // Simpan rating di input tersembunyi
                stars.forEach(s => s.classList.remove('active'));
                for (let i = 0; i < rating; i++) {
                    stars[i].classList.add('active');
                }
            });
        });
    </script>

</body>

</html>

<?php
$content = ob_get_clean(); 
include '../partials/Master.php'; 
?>