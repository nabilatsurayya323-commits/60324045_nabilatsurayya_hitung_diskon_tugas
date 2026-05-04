<?php include "../../koneksi.php"; ?>

<?php

$limit = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($page - 1) * $limit;

$search = isset($_GET['search']) ? $_GET['search'] : '';
$status = isset($_GET['status']) ? $_GET['status'] : '';
$jk = isset($_GET['jk']) ? $_GET['jk'] : '';

$where = "WHERE 1=1";

if($search != ""){
$where .= " AND (
nama LIKE '%$search%' OR
email LIKE '%$search%' OR
telepon LIKE '%$search%'
)";
}

if($status != ""){
$where .= " AND status='$status'";
}

if($jk != ""){
$where .= " AND jenis_kelamin='$jk'";
}

$query = "SELECT * FROM anggota $where ORDER BY id_anggota DESC LIMIT $start,$limit";
$data = mysqli_query($conn,$query);

$totalData = mysqli_num_rows(mysqli_query($conn,"SELECT * FROM anggota $where"));
$totalPage = ceil($totalData / $limit);

// statistik
$total = mysqli_num_rows(mysqli_query($conn,"SELECT * FROM anggota"));
$aktif = mysqli_num_rows(mysqli_query($conn,"SELECT * FROM anggota WHERE status='Aktif'"));
$nonaktif = mysqli_num_rows(mysqli_query($conn,"SELECT * FROM anggota WHERE status='Nonaktif'"));

?>

<!DOCTYPE html>
<html>
<head>
<title>Data Anggota</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

</head>
<body class="bg-light">

<div class="container mt-4">

<h2 class="mb-4 text-center">📚 Sistem Data Anggota Perpustakaan</h2>

<div class="row mb-4">

<div class="col-md-4">
<div class="card text-bg-primary">
<div class="card-body">
<h5>Total Anggota</h5>
<h2><?= $total; ?></h2>
</div>
</div>
</div>

<div class="col-md-4">
<div class="card text-bg-success">
<div class="card-body">
<h5>Aktif</h5>
<h2><?= $aktif; ?></h2>
</div>
</div>
</div>

<div class="col-md-4">
<div class="card text-bg-danger">
<div class="card-body">
<h5>Nonaktif</h5>
<h2><?= $nonaktif; ?></h2>
</div>
</div>
</div>

</div>

<div class="mb-3">
<a href="create.php" class="btn btn-primary">+ Tambah Anggota</a>
<a href="export.php" class="btn btn-success">Export Excel</a>
</div>

<form method="GET" class="row g-2 mb-4">

<div class="col-md-4">
<input type="text" name="search" class="form-control" placeholder="Cari nama/email/telepon" value="<?= $search; ?>">
</div>

<div class="col-md-3">
<select name="status" class="form-control">
<option value="">Semua Status</option>
<option value="Aktif" <?= ($status=="Aktif")?'selected':''; ?>>Aktif</option>
<option value="Nonaktif" <?= ($status=="Nonaktif")?'selected':''; ?>>Nonaktif</option>
</select>
</div>

<div class="col-md-3">
<select name="jk" class="form-control">
<option value="">Semua Gender</option>
<option value="Laki-laki" <?= ($jk=="Laki-laki")?'selected':''; ?>>Laki-laki</option>
<option value="Perempuan" <?= ($jk=="Perempuan")?'selected':''; ?>>Perempuan</option>
</select>
</div>

<div class="col-md-2">
<button class="btn btn-dark w-100">Filter</button>
</div>

</form>

<table class="table table-bordered table-striped table-hover bg-white">

<tr class="table-dark">
<th>No</th>
<th>Foto</th>
<th>Kode</th>
<th>Nama</th>
<th>Email</th>
<th>Telepon</th>
<th>JK</th>
<th>Status</th>
<th>Aksi</th>
</tr>

<?php
$no = $start + 1;
while($row=mysqli_fetch_assoc($data)){
?>

<tr>

<td><?= $no++; ?></td>

<td>
<?php if($row['foto']!=""){ ?>
<img src="uploads/<?= $row['foto']; ?>" width="50" height="50" style="object-fit:cover;border-radius:50%;">
<?php } else { ?>
-
<?php } ?>
</td>

<td><?= $row['kode_anggota']; ?></td>
<td><?= $row['nama']; ?></td>
<td><?= $row['email']; ?></td>
<td><?= $row['telepon']; ?></td>

<td>
<?php if($row['jenis_kelamin']=="Laki-laki"){ ?>
<span class="badge bg-primary">Laki-laki</span>
<?php } else { ?>
<span class="badge bg-warning text-dark">Perempuan</span>
<?php } ?>
</td>

<td>
<?php if($row['status']=="Aktif"){ ?>
<span class="badge bg-success">Aktif</span>
<?php } else { ?>
<span class="badge bg-danger">Nonaktif</span>
<?php } ?>
</td>

<td>
<a href="edit.php?id=<?= $row['id_anggota']; ?>" class="btn btn-sm btn-warning">Edit</a>

<a href="delete.php?id=<?= $row['id_anggota']; ?>"
onclick="return confirm('Yakin hapus data ini?')"
class="btn btn-sm btn-danger">
Hapus
</a>
</td>

</tr>

<?php } ?>

</table>

<nav>
<ul class="pagination">

<?php for($i=1;$i<=$totalPage;$i++){ ?>

<li class="page-item <?= ($page==$i)?'active':''; ?>">
<a class="page-link"
href="?page=<?= $i; ?>&search=<?= $search; ?>&status=<?= $status; ?>&jk=<?= $jk; ?>">
<?= $i; ?>
</a>
</li>

<?php } ?>

</ul>
</nav>

</div>
</body>
</html>