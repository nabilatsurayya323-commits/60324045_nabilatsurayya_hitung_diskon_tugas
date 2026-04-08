<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Sistem Anggota</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<?php
require_once 'functions_anggota.php';

// Data anggota
$anggota_list = [
    ["id"=>"AGT-001","nama"=>"Budi Santoso","email"=>"budi@email.com","telepon"=>"0812","alamat"=>"Jakarta","tanggal_daftar"=>"2024-01-15","status"=>"Aktif","total_pinjaman"=>5],
    ["id"=>"AGT-002","nama"=>"Anis Nadhirotul Mustafida","email"=>"anis@email.com","telepon"=>"0822","alamat"=>"Semarang","tanggal_daftar"=>"2024-02-10","status"=>"Aktif","total_pinjaman"=>7],
    ["id"=>"AGT-003","nama"=>"Nabila Tsurayya Ahmad","email"=>"nabila@email.com","telepon"=>"0833","alamat"=>"Solo","tanggal_daftar"=>"2024-03-05","status"=>"Non-Aktif","total_pinjaman"=>4],
    ["id"=>"AGT-004","nama"=>"Nailah Dhina Amelia","email"=>"nailah@email.com","telepon"=>"0844","alamat"=>"Malang","tanggal_daftar"=>"2024-03-12","status"=>"Aktif","total_pinjaman"=>6],
    ["id"=>"AGT-005","nama"=>"Davin Aurelio","email"=>"davin@email.com","telepon"=>"0855","alamat"=>"Jakarta","tanggal_daftar"=>"2024-03-20","status"=>"Non-Aktif","total_pinjaman"=>3],
];

// Search & Sort
if (isset($_GET['search'])) {
    $anggota_list = search_nama($anggota_list, $_GET['search']);
}
$anggota_list = sort_nama($anggota_list);

// Statistik
$total = hitung_total_anggota($anggota_list);
$aktif = hitung_anggota_aktif($anggota_list);
$nonaktif = $total - $aktif;
$rata = hitung_rata_rata_pinjaman($anggota_list);
$teraktif = cari_anggota_teraktif($anggota_list);
?>

<div class="container mt-5">
    <h2>📚 Sistem Anggota</h2>

    <!-- Search -->
    <form class="mb-3">
        <input type="text" name="search" class="form-control" placeholder="Cari nama...">
    </form>

    <!-- Statistik -->
    <div class="row mb-4">
        <div class="col-md-3"><div class="card p-3 bg-primary text-white">Total: <?= $total ?></div></div>
        <div class="col-md-3"><div class="card p-3 bg-success text-white">Aktif: <?= $aktif ?></div></div>
        <div class="col-md-3"><div class="card p-3 bg-danger text-white">Non-Aktif: <?= $nonaktif ?></div></div>
        <div class="col-md-3"><div class="card p-3 bg-warning">Rata: <?= number_format($rata,1) ?></div></div>
    </div>

    <!-- Tabel -->
    <table class="table table-bordered">
        <tr>
            <th>Nama</th><th>Status</th><th>Pinjaman</th>
        </tr>
        <?php foreach ($anggota_list as $a): ?>
        <tr>
            <td><?= $a['nama'] ?></td>
            <td><?= $a['status'] ?></td>
            <td><?= $a['total_pinjaman'] ?></td>
        </tr>
        <?php endforeach; ?>
    </table>

    <!-- Teraktif -->
    <div class="card bg-success text-white p-3">
        Anggota Teraktif: <b><?= $teraktif['nama'] ?></b> (<?= $teraktif['total_pinjaman'] ?>)
    </div>

    <!-- Aktif & Nonaktif -->
    <div class="row mt-4">
        <div class="col-md-6">
            <h5>Aktif</h5>
            <ul>
                <?php foreach (filter_by_status($anggota_list,"Aktif") as $a): ?>
                    <li><?= $a['nama'] ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <div class="col-md-6">
            <h5>Non-Aktif</h5>
            <ul>
                <?php foreach (filter_by_status($anggota_list,"Non-Aktif") as $a): ?>
                    <li><?= $a['nama'] ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
</div>

</body>
</html>