<?php
$page_title = "Tambah Buku Baru";
require_once '../../config/database.php';
require_once '../../includes/header.php';

/* =====================================
   Inisialisasi Variabel
===================================== */
$errors = [];

$judul       = '';
$penulis     = '';
$tahun       = '';
$harga       = '';
$stok        = '';
$id_kategori = '';
$id_penerbit = '';
$id_rak      = '';

/* =====================================
   Ambil Data Dropdown
===================================== */
$kategori_result = $conn->query("
    SELECT id_kategori, nama_kategori
    FROM kategori_buku
    ORDER BY nama_kategori ASC
");

$penerbit_result = $conn->query("
    SELECT id_penerbit, nama_penerbit
    FROM penerbit
    ORDER BY nama_penerbit ASC
");

$rak_result = $conn->query("
    SELECT id_rak, nama_rak
    FROM rak
    ORDER BY nama_rak ASC
");

/* =====================================
   Proses Submit
===================================== */
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $judul       = trim($_POST['judul'] ?? '');
    $penulis     = trim($_POST['penulis'] ?? '');
    $tahun       = (int)($_POST['tahun'] ?? 0);
    $harga       = (float)($_POST['harga'] ?? 0);
    $stok        = (int)($_POST['stok'] ?? 0);
    $id_kategori = (int)($_POST['id_kategori'] ?? 0);
    $id_penerbit = (int)($_POST['id_penerbit'] ?? 0);
    $id_rak      = (int)($_POST['id_rak'] ?? 0);

    /* =========================
       Validasi
    ========================= */
    if ($judul == '') {
        $errors[] = "Judul buku wajib diisi.";
    }

    if ($penulis == '') {
        $errors[] = "Penulis wajib diisi.";
    }

    if ($tahun < 1900 || $tahun > date('Y')) {
        $errors[] = "Tahun terbit tidak valid.";
    }

    if ($harga < 0) {
        $errors[] = "Harga tidak boleh negatif.";
    }

    if ($stok < 0) {
        $errors[] = "Stok tidak boleh negatif.";
    }

    if ($id_kategori <= 0) {
        $errors[] = "Kategori wajib dipilih.";
    }

    if ($id_penerbit <= 0) {
        $errors[] = "Penerbit wajib dipilih.";
    }

    if ($id_rak <= 0) {
        $errors[] = "Rak wajib dipilih.";
    }

    /* =========================
       Simpan Data
    ========================= */
    if (count($errors) == 0) {

        $query = "INSERT INTO buku
                 (judul, penulis, tahun_terbit, harga, stok, id_kategori, id_penerbit, id_rak)
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($query);

        if (!$stmt) {
            die("SQL Error: " . $conn->error);
        }

        $stmt->bind_param(
            "ssidiiii",
            $judul,
            $penulis,
            $tahun,
            $harga,
            $stok,
            $id_kategori,
            $id_penerbit,
            $id_rak
        );

        if ($stmt->execute()) {
            $stmt->close();
            closeConnection();

            header("Location: index.php?success=" . urlencode("Buku berhasil ditambahkan"));
            exit();
        } else {
            $errors[] = "Gagal menyimpan data: " . $stmt->error;
        }

        $stmt->close();
    }
}
?>

<div class="container mt-4">
<div class="row">
<div class="col-md-8 offset-md-2">

<div class="card shadow">
<div class="card-header bg-primary text-white">
    <h4 class="mb-0">
        <i class="bi bi-plus-circle"></i> Tambah Buku Baru
    </h4>
</div>

<div class="card-body">

<!-- Error -->
<?php if (count($errors) > 0): ?>
<div class="alert alert-danger">
    <ul class="mb-0">
        <?php foreach ($errors as $error): ?>
            <li><?php echo $error; ?></li>
        <?php endforeach; ?>
    </ul>
</div>
<?php endif; ?>

<form method="POST">

<!-- Judul -->
<div class="mb-3">
    <label class="form-label">Judul Buku</label>
    <input type="text"
           name="judul"
           class="form-control"
           value="<?php echo htmlspecialchars($judul); ?>"
           required>
</div>

<!-- Penulis -->
<div class="mb-3">
    <label class="form-label">Penulis</label>
    <input type="text"
           name="penulis"
           class="form-control"
           value="<?php echo htmlspecialchars($penulis); ?>"
           required>
</div>

<!-- Tahun -->
<div class="mb-3">
    <label class="form-label">Tahun Terbit</label>
    <input type="number"
           name="tahun"
           class="form-control"
           min="1900"
           max="<?php echo date('Y'); ?>"
           value="<?php echo htmlspecialchars($tahun); ?>"
           required>
</div>

<!-- Harga -->
<div class="mb-3">
    <label class="form-label">Harga</label>
    <input type="number"
           name="harga"
           class="form-control"
           min="0"
           step="1000"
           value="<?php echo htmlspecialchars($harga); ?>"
           required>
</div>

<!-- Stok -->
<div class="mb-3">
    <label class="form-label">Stok</label>
    <input type="number"
           name="stok"
           class="form-control"
           min="0"
           value="<?php echo htmlspecialchars($stok); ?>"
           required>
</div>

<!-- Kategori -->
<div class="mb-3">
    <label class="form-label">Kategori</label>
    <select name="id_kategori" class="form-select" required>
        <option value="">-- Pilih Kategori --</option>

        <?php while($row = $kategori_result->fetch_assoc()): ?>
        <option value="<?php echo $row['id_kategori']; ?>"
            <?php echo ($id_kategori == $row['id_kategori']) ? 'selected' : ''; ?>>
            <?php echo htmlspecialchars($row['nama_kategori']); ?>
        </option>
        <?php endwhile; ?>
    </select>
</div>

<!-- Penerbit -->
<div class="mb-3">
    <label class="form-label">Penerbit</label>
    <select name="id_penerbit" class="form-select" required>
        <option value="">-- Pilih Penerbit --</option>

        <?php while($row = $penerbit_result->fetch_assoc()): ?>
        <option value="<?php echo $row['id_penerbit']; ?>"
            <?php echo ($id_penerbit == $row['id_penerbit']) ? 'selected' : ''; ?>>
            <?php echo htmlspecialchars($row['nama_penerbit']); ?>
        </option>
        <?php endwhile; ?>
    </select>
</div>

<!-- Rak -->
<div class="mb-3">
    <label class="form-label">Rak</label>
    <select name="id_rak" class="form-select" required>
        <option value="">-- Pilih Rak --</option>

        <?php while($row = $rak_result->fetch_assoc()): ?>
        <option value="<?php echo $row['id_rak']; ?>"
            <?php echo ($id_rak == $row['id_rak']) ? 'selected' : ''; ?>>
            <?php echo htmlspecialchars($row['nama_rak']); ?>
        </option>
        <?php endwhile; ?>
    </select>
</div>

<hr>

<div class="d-grid gap-2">
    <button type="submit" class="btn btn-primary">
        <i class="bi bi-save"></i> Simpan Buku
    </button>

    <a href="index.php" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
</div>

</form>

</div>
</div>

</div>
</div>
</div>

<?php
closeConnection();
require_once '../../includes/footer.php';
?>