
<?php require_once 'Config.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Course</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/flowbite@2.5.1/dist/flowbite.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script>
        // Konfigurasi Tailwind untuk menambahkan keyframes dan animasi
        tailwind.config = {
            theme: {
                extend: {
                    keyframes: {
                        bellShake: {
                            '0%, 100%': { transform: 'rotate(0deg)' },
                            '25%': { transform: 'rotate(30deg)' },
                            '75%': { transform: 'rotate(-30deg)' },
                        },
                    },
                    animation: {
                        bellShake: 'bellShake 0.5s ease-in-out',
                    },
                },
            },
        }
    </script>
</head>
<body>
    <?php include __DIR__ . '/nav.php' ?>

        <?php 
            if (isset($content)) {
                echo $content;
            } else {
                echo "<p>Konten tidak tersedia.</p>";
            }
        ?>
        <?php if(isset($_SESSION['user']))
        {
             include __DIR__ . '/cs.php';
        } ?>
        
        <?php include __DIR__ . '/footer.php' ?>
    <script src="https://cdn.jsdelivr.net/npm/flowbite@2.5.1/dist/flowbite.min.js"></script>


</body>
</html>