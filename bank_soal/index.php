<?php ob_start(); 
session_start();
?>
<head>
    <style>

        .timer {
            position: fixed;
            top: 20px;
            right: 20px;
            background: rgba(255, 255, 255, 0.95);
            padding: 1rem 1.5rem;
            border-radius: 10px;
            font-size: 1.2rem;
            font-weight: bold;
            color: #0052d4;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            z-index: 1000;
        }

        .timer.warning {
            background: #ff9800;
            color: white;
            animation: pulse 1s infinite;
        }

        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }

        .welcome-section {
            text-align: center;
            padding: 3rem 1rem;
            color: white;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }

        .grid-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 2rem;
            padding: 2rem;
            max-width: 1200px;
            margin: 0 auto;
        }

        .grid-item {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            padding: 2rem;
            text-align: center;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .grid-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.15);
        }

        .soal-container {
            max-width: 800px;
            margin: 2rem auto;
            padding: 2rem;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        }

        .question-navigation {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 0.5rem;
            margin: 1rem 0;
        }

        .question-number {
            padding: 0.5rem;
            border-radius: 5px;
            background: #f0f0f0;
            text-align: center;
            cursor: pointer;
            transition: all 0.2s;
        }

        .question-number.answered {
            background: #4CAF50;
            color: white;
        }

        .question-number.current {
            background: #2196F3;
            color: white;
        }

        .option-container {
            margin: 1rem 0;
        }

        .option-label {
            display: block;
            padding: 1rem;
            margin: 0.5rem 0;
            background: #f8f9fa;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.2s;
        }

        .option-label:hover {
            background: #e9ecef;
        }

        input[type="radio"]:checked + .option-label {
            background: #2196F3;
            color: white;
        }

        .button-container {
            display: flex;
            justify-content: space-between;
            margin-top: 2rem;
        }

        .result-container {
            max-width: 800px;
            margin: 2rem auto;
            padding: 2rem;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        }

        .result-summary {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
            margin: 2rem 0;
        }

        .result-card {
            background: #f8f9fa;
            padding: 1.5rem;
            border-radius: 10px;
            text-align: center;
        }

        .progress-bar {
            height: 20px;
            background: #e9ecef;
            border-radius: 10px;
            overflow: hidden;
            margin: 1rem 0;
        }

        .progress {
            height: 100%;
            background: #4CAF50;
            transition: width 0.5s ease;
        }

        .answer-review {
            margin-top: 2rem;
        }

        .review-item {
            padding: 1rem;
            margin: 1rem 0;
            border-radius: 8px;
            background: #f8f9fa;
        }

        .review-item.correct {
            border-left: 4px solid #4CAF50;
        }

        .review-item.incorrect {
            border-left: 4px solid #f44336;
        }
    </style>
<body>
    <header>
        <h1>Bank Soal Interaktif</h1>
    </header>

    <div id="timer" class="timer mt-[100px]">Waktu: 30:00</div>

    <main>
        <section id="welcome-section" class="welcome-section">
            <h2>Selamat Datang di Bank Soal</h2>
            <p>Pilih kategori soal untuk memulai latihan. Setiap kategori memiliki 20 soal dengan waktu 30 menit.</p>
        </section>

        <section id="grid-container" class="grid-container">
            <div class="grid-item" onclick="startQuiz('Penalaran Umum')">
                <h3>Kemampuan Penalaran Umum</h3>
                <p>20 Soal • 30 Menit</p>
            </div>
            <div class="grid-item" onclick="startQuiz('Pengetahuan Umum')">
                <h3>Pengetahuan dan Pemahaman Umum</h3>
                <p>20 Soal • 30 Menit</p>
            </div>
            <div class="grid-item" onclick="startQuiz('Pemahaman Bacaan')">
                <h3>Pemahaman Bacaan dan Menulis</h3>
                <p>20 Soal • 30 Menit</p>
            </div>
            <div class="grid-item" onclick="startQuiz('Bahasa Inggris')">
                <h3>Bahasa Inggris</h3>
                <p>20 Soal • 30 Menit</p>
            </div>
            <div class="grid-item" onclick="startQuiz('Literasi Bahasa Indonesia')">
                <h3>Literasi Bahasa Indonesia</h3>
                <p>20 Soal • 30 Menit</p>
            </div>
        </section>

        <section id="soal-container" class="soal-container" style="display: none;">
            <h3 id="soal-title"></h3>
            <div class="question-navigation" id="question-navigation"></div>
            <div id="soal-content"></div>
            <div class="button-container">
                <button onclick="prevQuestion()" id="prev-button">Sebelumnya</button>
                <button onclick="nextQuestion()" id="next-button">Selanjutnya</button>
                <button onclick="submitQuiz()" id="submit-button" style="display: none;">Kumpulkan</button>
            </div>
        </section>

        <section id="result-container" class="result-container" style="display: none;">
            <h3>Hasil Latihan</h3>
            <div class="result-summary">
                <div class="result-card">
                    <h4>Skor Total</h4>
                    <div class="progress-bar">
                        <div id="score-progress" class="progress"></div>
                    </div>
                    <p><span id="result">0</span>/20 Benar</p>
                </div>
                <div class="result-card">
                    <h4>Waktu Pengerjaan</h4>
                    <p id="completion-time">0m 0s</p>
                </div>
                <div class="result-card">
                    <h4>Akurasi</h4>
                    <p id="accuracy">0%</p>
                </div>
            </div>
            <div id="answer-review" class="answer-review"></div>
            <div class="button-container">
                <button onclick="backToMenu()">Kembali ke Menu</button>
                <button onclick="toggleAnswerReview()">Lihat Review Jawaban</button>
            </div>
        </section>
    </main>

    <script>
        const questions = {
            "Penalaran Umum": [
                {
                    question: "Pola bilangan berikut adalah 2, 4, 8, 16, ... Berapakah angka selanjutnya?",
                    options: ["20", "24", "32", "40"],
                    answer: "C",
                },
                {
                    question: "Jika 5, 10, 20, 40, ..., maka bilangan ke-6 adalah?",
                    options: ["80", "120", "160", "200"],
                    answer: "C",
                },
                {
                    question: "Pola berikut: 1, 1, 2, 3, 5, 8, ... adalah deret Fibonacci. Berapakah angka ke-8?",
                    options: ["13", "21", "34", "55"],
                    answer: "B",
                },
                {
                    question: "Pola bilangan berikut adalah 81, 27, 9, 3, ... Berapakah angka selanjutnya?",
                    options: ["2", "1", "0.5", "0.3"],
                    answer: "B",
                },
                {
                    question: "Semua guru adalah pendidik. Sebagian pendidik adalah penulis. Maka:",
                    options: ["Semua guru adalah penulis", "Sebagian penulis adalah guru", "Sebagian guru adalah penulis", "Tidak dapat disimpulkan"],
                    answer: "D",
                },
                {
                    question: "Semua burung dapat terbang. Semua elang adalah burung. Maka:",
                    options: ["Semua burung adalah elang", "Semua elang dapat terbang", "Semua yang dapat terbang adalah burung", "Sebagian burung bukan elang"],
                    answer: "B",
                },
                {
                    question: "Sebagian dokter adalah ahli bedah. Semua ahli bedah adalah orang yang terampil. Maka:",
                    options: ["Semua dokter adalah terampil", "Sebagian dokter terampil", "Semua yang terampil adalah dokter", "Sebagian dokter bukan ahli bedah"],
                    answer: "B",
                },
                {
                    question: "Jika semua A adalah B, dan semua B adalah C, maka:",
                    options: ["Semua A adalah C", "Sebagian A adalah C", "Semua C adalah A", "Tidak dapat disimpulkan"],
                    answer: "A",
                },
                {
                    question: "Jika hujan, maka jalan basah. Hari ini tidak hujan. Maka:",
                    options: ["Jalan tidak basah", "Jalan pasti kering", "Tidak dapat disimpulkan", "Jalan pasti basah"],
                    answer: "C",
                },
                {
                    question: "Jika lampu merah menyala, maka kendaraan berhenti. Kendaraan tidak berhenti. Maka:",
                    options: ["Lampu merah tidak menyala", "Kendaraan tetap berjalan", "Lampu hijau pasti menyala", "Tidak dapat disimpulkan"],
                    answer: "A",
                },
                {
                    question: "Kucing : Meong :: Anjing : ...",
                    options: ["Melenguh", "Menggonggong", "Mendesis", "Mengaum"],
                    answer: "B",
                },
                {
                    question: "Pisau : Memotong :: Pensil : ...",
                    options: ["Menghapus", "Menulis", "Mengasah", "Mencoret"],
                    answer: "B",
                },
                {
                    question: "Dokter : Pasien :: Guru : ...",
                    options: ["Murid", "Buku", "Pelajaran", "Sekolah"],
                    answer: "A",
                },
                {
                    question: "Jika sebuah buku berisi 240 halaman dan setiap hari dibaca 40 halaman, maka berapa hari yang diperlukan untuk menyelesaikan buku tersebut?",
                    options: ["5", "6", "7", "8"],
                    answer: "B",
                },
                {
                    question: "Ali memiliki 24 apel. Ia membagikan apel tersebut kepada 4 temannya secara merata. Berapa apel yang diterima setiap temannya?",
                    options: ["5", "6", "7", "8"],
                    answer: "B",
                },
                {
                    question: "Jika Dina tidur pukul 10 malam dan bangun pukul 6 pagi, berapa lama waktu tidurnya?",
                    options: ["6 jam", "7 jam", "8 jam", "9 jam"],
                    answer: "C",
                },
                {
                    question: "Sebuah kereta berjalan dengan kecepatan 60 km/jam. Jika menempuh perjalanan 180 km, berapa lama waktu yang diperlukan?",
                    options: ["2 jam", "3 jam", "4 jam", "5 jam"],
                    answer: "B",
                },
                {
                    question: "Kepala : Tubuh :: Roda : ...",
                    options: ["Mobil", "Mesin", "Ban", "Sepeda"],
                    answer: "A",
                },
                {
                    question: "Matahari : Siang :: Bulan : ...",
                    options: ["Malam", "Gelap", "Bintang", "Sinar"],
                    answer: "A",
                },
                {
                    question: "Hujan : Basah :: Panas : ...",
                    options: ["Kering", "Terang", "Gerah", "Gelap"],
                    answer: "C",
                }
            ],
            "Pengetahuan Umum": [
                // ... (questions remain unchanged)
            ],
            
        };

        let currentCategory = "";
        let currentQuestionIndex = 0;
        let userAnswers = [];
        let timeRemaining = 1800; // 30 menit dalam detik
        let timerInterval;

        function startQuiz(category) {
            currentCategory = category;
            currentQuestionIndex = 0;
            userAnswers = Array(20).fill(null);
            timeRemaining = 1800;
            
            document.getElementById("welcome-section").style.display = "none";
            document.getElementById("grid-container").style.display = "none";
            document.getElementById("soal-container").style.display = "block";
            
            updateQuestionNavigation();
            showQuestion();
            startTimer();
        }

        function updateQuestionNavigation() {
            const nav = document.getElementById("question-navigation");
            nav.innerHTML = userAnswers.map((answer, index) => `
                <div class="question-number ${index === currentQuestionIndex ? 'current' : ''} ${answer ? 'answered' : ''}"
                     onclick="jumpToQuestion(${index})">
                    ${index + 1}
                </div>
            `).join("");
        }

        function showQuestion() {
            const question = questions[currentCategory][currentQuestionIndex];
            document.getElementById("soal-title").textContent = `${currentCategory} - Soal ${currentQuestionIndex + 1}`;
            
            document.getElementById("soal-content").innerHTML = `
                <div class="question-text">
                    <p>${question.question}</p>
                </div>
                <div class="option-container">
                    ${question.options.map((option, index) => `
                        <div>
                            <input type="radio" 
                                   id="option${index}" 
                                   name="answer" 
                                   value="${String.fromCharCode(65 + index)}"
                                   ${userAnswers[currentQuestionIndex] === String.fromCharCode(65 + index) ? 'checked' : ''}>
                            <label class="option-label" for="option${index}">
                                ${option}
                            </label>
                        </div>
                    `).join("")}
                </div>
            `;
        }

        function prevQuestion() {
            if (currentQuestionIndex > 0) {
                currentQuestionIndex--;
                updateQuestionNavigation();
                showQuestion();
            }
        }

        function nextQuestion() {
            if (currentQuestionIndex < questions[currentCategory].length - 1) {
                currentQuestionIndex++;
                updateQuestionNavigation();
                showQuestion();
            } else {
                document.getElementById("submit-button").style.display = "block"; // Show submit button on last question
            }
        }

        function submitQuiz() {
            document.getElementById("soal-container").style.display = "none";
            document.getElementById("result-container").style.display = "block";
            calculateResults();
        }

        function calculateResults() {
            let correctAnswers = 0;
            questions[currentCategory].forEach((question, index) => {
                if (userAnswers[index] === question.answer) {
                    correctAnswers++;
                }
            });
            const totalQuestions = questions[currentCategory].length;
            const accuracy = (correctAnswers / totalQuestions) * 100;

            document.getElementById("result").textContent = correctAnswers;
            document.getElementById("accuracy").textContent = accuracy.toFixed(2) + "%";
            document.getElementById("completion-time").textContent = formatTime(1800 - timeRemaining);
            document.getElementById("score-progress").style.width = `${(correctAnswers / totalQuestions) * 100}%`;
        }

        function formatTime(seconds) {
            const minutes = Math.floor(seconds / 60);
            const secs = seconds % 60;
            return `${minutes}m ${secs}s`;
        }

        function backToMenu() {
            // Logic to reset the quiz and go back to the main menu
            location.reload(); // Reload the page to reset
        }

        function toggleAnswerReview() {
            const reviewContainer = document.getElementById("answer-review");
            reviewContainer.innerHTML = userAnswers.map((answer, index) => {
                const question = questions[currentCategory][index];
                const isCorrect = answer === question.answer;
                return `
                    <div class="review-item ${isCorrect ? 'correct' : 'incorrect'}">
                        <p>${question.question}</p>
                        <p>Jawaban Anda: ${answer || 'Belum dijawab'}</p>
                        <p>${isCorrect ? 'Benar' : 'Salah'}</p>
                    </div>
                `;
            }).join("");
            reviewContainer.style.display = reviewContainer.style.display === "none" ? "block" : "none";
        }

        function startTimer() {
            timerInterval = setInterval(() => {
                if (timeRemaining <= 0) {
                    clearInterval(timerInterval);
                    submitQuiz(); // Automatically submit when time is up
                } else {
                    timeRemaining--;
                    const minutes = Math.floor(timeRemaining / 60);
                    const seconds = timeRemaining % 60;
                    document.getElementById("timer").textContent = `Waktu: ${minutes}:${seconds < 10 ? '0' + seconds : seconds}`;
                    
                    if (timeRemaining <= 60) {
                        document.getElementById("timer").classList.add("warning");
                    }
                }
            }, 1000);
        }

        function jumpToQuestion(index) {
            currentQuestionIndex = index;
            updateQuestionNavigation();
            showQuestion();
        }

        document.getElementById("soal-container").addEventListener("change", (e) => {
            if (e.target.name === "answer") {
                userAnswers[currentQuestionIndex] = e.target.value;
                updateQuestionNavigation();
            }
        });
        function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        sidebar.classList.toggle('hidden');
    }

    </script>

<div>


</div>
    </HTML>
    <?php
$content = ob_get_clean(); 
include '../partials/Master.php'; 
?>