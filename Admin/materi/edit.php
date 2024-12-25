<?php
ob_start();
session_start();
require "./../../model/materi.php";
require "./../../model/kelas.php";
require_once "./../../autoload/autoload.php";

$role = $_SESSION["user"]["peran"];
$id_pengajar = $_SESSION["user"]["id_pengguna"];

$id_kelas = $_GET['id_kelas'];
$id_materi = $_GET["id_materi"];

$data["materi"] = getMateriById($id_materi);
$data["kelas"] = getKelasById($id_kelas);

if ($role != 2 || $data["kelas"]["id_pengajar"] != $id_pengajar) {
    showAlertAndRedirect("kamu Tidak Punya Akses Ke Halaman Ini.", "../../");
}

$filePath = $_SERVER['DOCUMENT_ROOT'] . $data["materi"]['path_file'];
if (file_exists($filePath)) {
    $data["materi"]["konten_materi"] = file_get_contents($filePath);
} else {
    $data["materi"]["konten_materi"] = '';
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $judulMateri = $_POST["judul_materi"];
    $deskripsiMateri = $_POST["deskripsi"];
    $kontenHtml = $_POST["konten_materi"];

    $materi = [
        'id_materi' => $data["materi"]["id_materi"],
        'judul_materi' => $judulMateri,
        'deskripsi' => $deskripsiMateri,
        'path_file' => $data["materi"]["path_file"],
    ];
    if (updateMateri($materi)) {
        $filePath = $data["materi"]["path_file"];

        file_put_contents($_SERVER['DOCUMENT_ROOT'] . $filePath, $kontenHtml);

        showAlertAndRedirect("Materi berhasil diubah!", "./index.php?id_kelas=$id_kelas");
    } else {
        showAlert("Gagal menyimpan materi.");
    }
}
?>

<script src="https://cdn.tiny.cloud/1/2xz0gmpxasi720t55esl1kctym7watf53gccp3a1nbocdbnl/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>


<div class="container mx-auto p-6">
    <div class="mb-4">
        <nav class="text-sm text-gray-600">
            <a href="/" class="hover:text-blue-500">Home</a> &gt;
            <a href="/admin/kelas/" class="hover:text-blue-500">Kelola Kelas</a> &gt;
            <a href="/admin/materi/index.php?id_kelas=<?= $id_kelas ?>" class="hover:text-blue-500">Kelola Materi</a> &gt;
            <span class="text-gray-500">Edit</span>
        </nav>
    </div>
    <h1 class="text-2xl font-bold text-gray-800 mb-6">Ubah Materi - <?= htmlspecialchars($data['kelas']['nama_kelas']) ?></h1>

    <form action="" method="POST">
        <div class="rounded-lg shadow-md bg-white p-8">
            <div class="mb-6">
                <label for="judul_materi" class="block text-gray-600">Judul Materi</label>
                <input type="text"
                    id="judul_materi"
                    name="judul_materi"
                    class="w-full p-3 mt-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="Masukkan judul materi"
                    value="<?= htmlspecialchars($_POST['judul_materi'] ?? $data['materi']['judul_materi'] ?? '') ?>"
                    required>
            </div>

            <div class="mb-6">
                <label for="deskripsi" class="block text-gray-600">Deskripsi Materi</label>
                <textarea id="deskripsi"
                    name="deskripsi"
                    class="w-full p-3 mt-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    rows="4"
                    placeholder="Masukkan deskripsi materi"
                    required><?= htmlspecialchars($_POST['deskripsi'] ?? $data['materi']['deskripsi'] ?? '') ?></textarea>
            </div>

            <div class="mb-6">
                <label for="konten_materi" class="block text-gray-600">Konten Materi</label>
                <textarea id="konten_materi"
                    name="konten_materi"
                    class="w-full p-3 mt-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    rows="6"><?= htmlspecialchars($_POST['konten_materi'] ?? $data['materi']['konten_materi'] ?? '') ?></textarea>
            </div>

            <div class="mb-6 text-center">
                <button type="submit" class="px-6 py-3 bg-blue-500 text-white text-xl rounded-lg hover:bg-blue-600 transition duration-200">Ubah Materi</button>
            </div>
        </div>
    </form>
</div>


<script>
    tinymce.init({
        selector: '#konten_materi',
        plugins: ['anchor', 'autolink', 'charmap', 'codesample', 'emoticons', 'image', 'link', 'lists', 'media', 'searchreplace', 'table', 'visualblocks', 'wordcount'],
        toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table mergetags | addcomment showcomments | spellcheckdialog a11ycheck typography | align lineheight | checklist numlist bullist indent outdent | emoticons charmap | removeformat',
        tinycomments_mode: 'embedded',
        tinycomments_author: 'Author name',
        setup: function(editor) {
            editor.on('change', function() {
                editor.save();
            });
        },
        mergetags_list: [{
                value: 'First.Name',
                title: 'First Name'
            },
            {
                value: 'Email',
                title: 'Email'
            },
        ],
    });
</script>
<?php
$content = ob_get_clean();
require "./../MasterAdmin.php";
