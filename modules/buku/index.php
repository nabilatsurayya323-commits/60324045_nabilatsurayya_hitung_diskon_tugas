<?php
$page_title = "Data Buku";
require_once '../../config/database.php';
require_once '../../includes/header.php';

/* =========================
   Pagination
========================= */
$limit = 10;
$page  = isset($_GET['page']) ? (int) $_GET['page'] : 1;
if ($page < 1) $page = 1;

$offset = ($page - 1) * $limit;

/* =========================
   Search
========================= */
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

/* =========================
   Query
========================= */
if (!empty($search)) {

    $keyword = "%$search%";

    $query = "SELECT buku.*,
                     kategori_buku.nama_kategori,
                     penerbit.nama_penerbit
              FROM buku
              LEFT JOIN kategori_buku
                     ON buku.id_kategori = kategori_buku.id_kategori
              LEFT JOIN penerbit
                     ON buku.id_penerbit = penerbit.id_penerbit
              WHERE buku.judul LIKE ?
                 OR buku.penulis LIKE ?
                 OR kategori_buku.nama_kategori LIKE ?
                 OR penerbit.nama_penerbit LIKE ?
              ORDER BY buku.created_at DESC
              LIMIT ? OFFSET ?";

    $stmt = $conn->prepare($query);

    if (!$stmt) {
        die("SQL Error: " . $conn->error);
    }

    $stmt->bind_param(
        "ssssii",
        $keyword,
        $keyword,
        $keyword,
        $keyword,
        $limit,
        $offset
    );

    $stmt->execute();
    $result = $stmt->get_result();

    /* Count total rows */
    $count_query = "SELECT COUNT(*) AS total
                    FROM buku
                    LEFT JOIN kategori_buku
                           ON buku.id_kategori = kategori_buku.id_kategori
                    LEFT JOIN penerbit
                           ON buku.id_penerbit = penerbit.id_penerbit
                    WHERE buku.judul LIKE ?
                       OR buku.penulis LIKE ?
                       OR kategori_buku.nama_kategori LIKE ?
                       OR penerbit.nama_penerbit LIKE ?";

    $stmt_count = $conn->prepare($count_query);

    if (!$stmt_count) {
        die("SQL Error: " . $conn->error);
    }

    $stmt_count->bind_param(
        "ssss",
        $keyword,
        $keyword,
        $keyword,
        $keyword
    );

    $stmt_count->execute();
    $total_rows = $stmt_count->get_result()->fetch_assoc()['total'];

} else {

    $query = "SELECT buku.*,
                     kategori_buku.nama_kategori,
                     penerbit.nama_penerbit
              FROM buku
              LEFT JOIN kategori_buku
                     ON buku.id_kategori = kategori_buku.id_kategori
              LEFT JOIN penerbit
                     ON buku.id_penerbit = penerbit.id_penerbit
              ORDER BY buku.created_at DESC
              LIMIT ? OFFSET ?";

    $stmt = $conn->prepare($query);

    if (!$stmt) {
        die("SQL Error: " . $conn->error);
    }

    $stmt->bind_param("ii", $limit, $offset);
    $stmt->execute();
    $result = $stmt->get_result();

    $total_rows = $conn->query(
        "SELECT COUNT(*) AS total FROM buku"
    )->fetch_assoc()['total'];
}

/* =========================
   Total Pages
========================= */
$total_pages = ceil($total_rows / $limit);
?>

<div class="container mt-4">

    <div class="row mb-3">
        <div class="col-md-6">
            <h2><i class="bi bi-book"></i> Data Buku</h2>
        </div>

        <div class="col-md-6 text-end">
            <a href="create.php" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Tambah Buku
            </a>
        </div>
    </div>

    <!-- Search -->
    <div class="card mb-3">
        <div class="card-body">
            <form method="GET">
                <div class="input-group">
                    <input type="text"
                           name="search"
                           class="form-control"
                           placeholder="Cari judul / penulis / kategori / penerbit..."
                           value="<?php echo htmlspecialchars($search); ?>">

                    <button class="btn btn-primary">
                        <i class="bi bi-search"></i> Cari
                    </button>

                    <?php if (!empty($search)): ?>
                        <a href="index.php" class="btn btn-secondary">
                            Reset
                        </a>
                    <?php endif; ?>
                </div>
            </form>
        </div>
    </div>

    <!-- Table -->
    <div class="card">
        <div class="card-header bg-primary text-white">
            <strong>Daftar Buku</strong>
        </div>

        <div class="card-body">

        <?php if ($result->num_rows > 0): ?>

            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Judul</th>
                            <th>Penulis</th>
                            <th>Kategori</th>
                            <th>Penerbit</th>
                            <th>Tahun</th>
                            <th>Harga</th>
                            <th>Stok</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                    <?php
                    $no = $offset + 1;
                    while ($row = $result->fetch_assoc()):
                    ?>
                        <tr>
                            <td><?php echo $no++; ?></td>

                            <td>
                                <?php echo htmlspecialchars($row['judul']); ?>
                            </td>

                            <td>
                                <?php echo htmlspecialchars($row['penulis']); ?>
                            </td>

                            <td>
                                <span class="badge bg-primary">
                                    <?php echo htmlspecialchars($row['nama_kategori']); ?>
                                </span>
                            </td>

                            <td>
                                <?php echo htmlspecialchars($row['nama_penerbit']); ?>
                            </td>

                            <td>
                                <?php echo $row['tahun_terbit']; ?>
                            </td>

                            <td>
                                Rp <?php echo number_format($row['harga'], 0, ',', '.'); ?>
                            </td>

                            <td class="text-center">
                                <?php if ($row['stok'] > 0): ?>
                                    <span class="badge bg-success">
                                        <?php echo $row['stok']; ?>
                                    </span>
                                <?php else: ?>
                                    <span class="badge bg-danger">
                                        Habis
                                    </span>
                                <?php endif; ?>
                            </td>

                            <td>
                                <a href="edit.php?id=<?php echo $row['id_buku']; ?>"
                                   class="btn btn-sm btn-warning">
                                    Edit
                                </a>

                                <a href="delete.php?id=<?php echo $row['id_buku']; ?>"
                                   class="btn btn-sm btn-danger"
                                   onclick="return confirm('Yakin hapus data ini?')">
                                    Hapus
                                </a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <?php if ($total_pages > 1): ?>
            <nav>
                <ul class="pagination justify-content-center">

                    <li class="page-item <?php echo ($page <= 1) ? 'disabled' : ''; ?>">
                        <a class="page-link"
                           href="?page=<?php echo $page - 1; ?>&search=<?php echo urlencode($search); ?>">
                            Previous
                        </a>
                    </li>

                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                        <li class="page-item <?php echo ($page == $i) ? 'active' : ''; ?>">
                            <a class="page-link"
                               href="?page=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>">
                                <?php echo $i; ?>
                            </a>
                        </li>
                    <?php endfor; ?>

                    <li class="page-item <?php echo ($page >= $total_pages) ? 'disabled' : ''; ?>">
                        <a class="page-link"
                           href="?page=<?php echo $page + 1; ?>&search=<?php echo urlencode($search); ?>">
                            Next
                        </a>
                    </li>

                </ul>
            </nav>
            <?php endif; ?>

            <div class="alert alert-info mt-3 mb-0">
                Total Buku: <strong><?php echo $total_rows; ?></strong>
                |
                Halaman <strong><?php echo $page; ?></strong>
                dari <strong><?php echo $total_pages; ?></strong>
            </div>

        <?php else: ?>

            <div class="alert alert-warning mb-0">
                Data tidak ditemukan.
            </div>

        <?php endif; ?>

        </div>
    </div>

</div>

<?php
$stmt->close();
if (isset($stmt_count)) $stmt_count->close();

closeConnection();
require_once '../../includes/footer.php';
?>