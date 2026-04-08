<?php
// ================= DATA ANGGOTA =================
$anggota_list = [
    [
        "id" => "AGT-001",
        "nama" => "Budi Santoso",
        "email" => "budi@email.com",
        "telepon" => "081234567890",
        "alamat" => "Jakarta",
        "tanggal_daftar" => "2024-01-15",
        "status" => "Aktif",
        "total_pinjaman" => 5
    ],
    [
        "id" => "AGT-002",
        "nama" => "Anis Nadhirotul Mustafida",
        "email" => "anis@email.com",
        "telepon" => "086111111111",
        "alamat" => "Semarang",
        "tanggal_daftar" => "2024-02-10",
        "status" => "Aktif",
        "total_pinjaman" => 7
    ],
    [
        "id" => "AGT-003",
        "nama" => "Nabila Tsurayya Ahmad",
        "email" => "nabila@email.com",
        "telepon" => "086222222222",
        "alamat" => "Solo",
        "tanggal_daftar" => "2024-03-05",
        "status" => "Non-Aktif",
        "total_pinjaman" => 4
    ],
    [
        "id" => "AGT-004",
        "nama" => "Nailah Dhina Amelia",
        "email" => "nailah@email.com",
        "telepon" => "086333333333",
        "alamat" => "Malang",
        "tanggal_daftar" => "2024-03-12",
        "status" => "Aktif",
        "total_pinjaman" => 6
    ],
    [
        "id" => "AGT-005",
        "nama" => "Davin Aurelio",
        "email" => "davin@email.com",
        "telepon" => "086444444444",
        "alamat" => "Jakarta",
        "tanggal_daftar" => "2024-03-20",
        "status" => "Non-Aktif",
        "total_pinjaman" => 3
    ]
];

// ================= STATISTIK =================
$total_anggota = count($anggota_list);

$aktif = 0;
$nonaktif = 0;
$total_pinjaman = 0;
$anggota_teraktif = $anggota_list[0];

foreach ($anggota_list as $a) {
    if ($a['status'] == "Aktif") {
        $aktif++;
    } else {
        $nonaktif++;
    }

    $total_pinjaman += $a['total_pinjaman'];

    if ($a['total_pinjaman'] > $anggota_teraktif['total_pinjaman']) {
        $anggota_teraktif = $a;
    }
}

$persen_aktif = ($aktif / $total_anggota) * 100;
$persen_nonaktif = ($nonaktif / $total_anggota) * 100;
$rata_pinjaman = $total_pinjaman / $total_anggota;

// ================= FILTER =================
$filter = isset($_GET['status']) ? $_GET['status'] : "Semua";

$data_tampil = array_filter($anggota_list, function($a) use ($filter) {
    if ($filter == "Semua") return true;
    return $a['status'] == $filter;
});
?>

<!DOCTYPE html>
<html>
<head>
    <title>Data Anggota</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-4">

<h2 class="mb-4">📚 Data Anggota Perpustakaan</h2>

<!-- ================= CARDS ================= -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card bg-primary text-white p-3">
            <h5>Total Anggota</h5>
            <h3><?= $total_anggota ?></h3>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white p-3">
            <h5>Aktif</h5>
            <h3><?= number_format($persen_aktif,1) ?>%</h3>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-danger text-white p-3">
            <h5>Non-Aktif</h5>
            <h3><?= number_format($persen_nonaktif,1) ?>%</h3>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning text-dark p-3">
            <h5>Rata Pinjaman</h5>
            <h3><?= number_format($rata_pinjaman,1) ?></h3>
        </div>
    </div>
</div>

<!-- ================= ANGGOTA TERAKTIF ================= -->
<div class="alert alert-info">
    <strong>Anggota Teraktif:</strong> <?= $anggota_teraktif['nama'] ?> 
    (<?= $anggota_teraktif['total_pinjaman'] ?> pinjaman)
</div>

<!-- ================= FILTER ================= -->
<form method="GET" class="mb-3">
    <select name="status" class="form-select w-25 d-inline">
        <option <?= $filter=="Semua"?"selected":"" ?>>Semua</option>
        <option <?= $filter=="Aktif"?"selected":"" ?>>Aktif</option>
        <option <?= $filter=="Non-Aktif"?"selected":"" ?>>Non-Aktif</option>
    </select>
    <button class="btn btn-primary">Filter</button>
</form>

<!-- ================= TABEL ================= -->
<table class="table table-bordered table-striped">
    <thead class="table-dark">
        <tr>
            <th>ID</th>
            <th>Nama</th>
            <th>Email</th>
            <th>Telepon</th>
            <th>Alamat</th>
            <th>Tanggal Daftar</th>
            <th>Status</th>
            <th>Total Pinjaman</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($data_tampil as $a): ?>
        <tr>
            <td><?= $a['id'] ?></td>
            <td><?= $a['nama'] ?></td>
            <td><?= $a['email'] ?></td>
            <td><?= $a['telepon'] ?></td>
            <td><?= $a['alamat'] ?></td>
            <td><?= $a['tanggal_daftar'] ?></td>
            <td><?= $a['status'] ?></td>
            <td><?= $a['total_pinjaman'] ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

</body>
</html>