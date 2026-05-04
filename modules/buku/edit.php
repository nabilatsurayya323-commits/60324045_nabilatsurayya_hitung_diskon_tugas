<?php
$page_title = "Edit Data Buku";
require_once '../../config/database.php';
require_once '../../includes/header.php';

if (!isset($_GET['id'])) {
    header("Location:index.php");
    exit();
}

$id_buku = (int)$_GET['id'];
$errors = [];

/* ======================
AMBIL DATA
====================== */
$stmt = $conn->prepare("SELECT * FROM buku WHERE id_buku = ?");

if (!$stmt) {
    die("Prepare gagal: " . $conn->error);
}

$stmt->bind_param("i", $id_buku);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows == 0) {
    header("Location:index.php");
    exit();
}

$data = $result->fetch_assoc();
$stmt->close();

/* isi variabel */
$judul = $data['judul'] ?? '';
$tahun = $data['tahun_terbit'] ?? '';
$harga = $data['harga'] ?? '';
$stok  = $data['stok'] ?? '';

/* ======================
UPDATE
====================== */
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $judul = $_POST['judul'];
    $tahun = (int)$_POST['tahun'];
    $harga = (float)$_POST['harga'];
    $stok  = (int)$_POST['stok'];

    if ($judul == '') $errors[] = "Judul wajib diisi";
    if ($tahun < 1900 || $tahun > date('Y')) $errors[] = "Tahun salah";
    if ($harga < 0) $errors[] = "Harga salah";
    if ($stok < 0) $errors[] = "Stok salah";

    if (!$errors) {

        $stmt = $conn->prepare("UPDATE buku SET
            judul = ?,
            tahun_terbit = ?,
            harga = ?,
            stok = ?
            WHERE id_buku = ?
        ");

        if (!$stmt) {
            die("Prepare gagal: " . $conn->error);
        }

        $stmt->bind_param(
            "sidii",
            $judul,
            $tahun,
            $harga,
            $stok,
            $id_buku
        );

        if ($stmt->execute()) {
            header("Location:index.php?success=Update berhasil");
            exit();
        } else {
            $errors[] = "Gagal update";
        }

        $stmt->close();
    }
}
?>

<div class="container mt-4">
<div class="card">
<div class="card-header bg-warning">
<h4>Edit Buku</h4>
</div>

<div class="card-body">

<?php if ($errors): ?>
<div class="alert alert-danger">
<ul>
<?php foreach($errors as $e): ?>
<li><?= $e ?></li>
<?php endforeach; ?>
</ul>
</div>
<?php endif; ?>

<form method="POST">

<div class="mb-3">
<label>Judul Buku</label>
<input type="text" name="judul" class="form-control"
value="<?= htmlspecialchars($judul) ?>" required>
</div>

<div class="mb-3">
<label>Tahun Terbit</label>
<input type="number" name="tahun" class="form-control"
value="<?= htmlspecialchars($tahun) ?>" required>
</div>

<div class="mb-3">
<label>Harga</label>
<input type="number" name="harga" class="form-control"
value="<?= htmlspecialchars($harga) ?>" required>
</div>

<div class="mb-3">
<label>Stok</label>
<input type="number" name="stok" class="form-control"
value="<?= htmlspecialchars($stok) ?>" required>
</div>

<button type="submit" class="btn btn-warning">
Update
</button>

<a href="index.php" class="btn btn-secondary">
Kembali
</a>

</form>

</div>
</div>
</div>

<?php require_once '../../includes/footer.php'; ?>