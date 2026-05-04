<?php include "../../koneksi.php"; ?>

<?php

$id = $_GET['id'];

$data = mysqli_fetch_assoc(
mysqli_query($conn,"SELECT * FROM anggota WHERE id_anggota='$id'")
);

if(!$data){
die("Data tidak ditemukan");
}


// ================= PROSES UPDATE =================

if(isset($_POST['update'])){

$kode = trim($_POST['kode']);
$nama = trim($_POST['nama']);
$email = trim($_POST['email']);
$telepon = trim($_POST['telepon']);
$alamat = trim($_POST['alamat']);
$tanggal_lahir = $_POST['tanggal_lahir'];
$jk = $_POST['jk'];
$pekerjaan = trim($_POST['pekerjaan']);
$status = $_POST['status'];


// VALIDASI REQUIRED
if(
$kode=="" || $nama=="" || $email=="" ||
$telepon=="" || $alamat=="" ||
$tanggal_lahir=="" || $jk==""
){
die("Semua field wajib diisi!");
}

// VALIDASI EMAIL
if(!filter_var($email,FILTER_VALIDATE_EMAIL)){
die("Format email tidak valid!");
}

// VALIDASI TELEPON
if(!preg_match('/^08[0-9]{8,13}$/',$telepon)){
die("Nomor telepon tidak valid!");
}

// VALIDASI UMUR
$umur = date('Y') - date('Y', strtotime($tanggal_lahir));

if($umur < 10){
die("Umur minimal 10 tahun");
}


// ================= UNIQUE =================

// cek kode
$cekKode = mysqli_query($conn,"
SELECT * FROM anggota
WHERE kode_anggota='$kode'
AND id_anggota != '$id'
");

if(mysqli_num_rows($cekKode)>0){
die("Kode anggota sudah dipakai!");
}

// cek email
$cekEmail = mysqli_query($conn,"
SELECT * FROM anggota
WHERE email='$email'
AND id_anggota != '$id'
");

if(mysqli_num_rows($cekEmail)>0){
die("Email sudah dipakai!");
}


// ================= FOTO =================

$foto = $data['foto'];

if($_FILES['foto']['name'] != ""){

$fotoBaru = time()."_".$_FILES['foto']['name'];

move_uploaded_file(
$_FILES['foto']['tmp_name'],
"uploads/".$fotoBaru
);

// hapus foto lama
if($foto != "" && file_exists("uploads/".$foto)){
unlink("uploads/".$foto);
}

$foto = $fotoBaru;
}


// ================= UPDATE =================

mysqli_query($conn,"UPDATE anggota SET

kode_anggota='$kode',
nama='$nama',
email='$email',
telepon='$telepon',
alamat='$alamat',
tanggal_lahir='$tanggal_lahir',
jenis_kelamin='$jk',
pekerjaan='$pekerjaan',
status='$status',
foto='$foto'

WHERE id_anggota='$id'
");

header("Location:index.php");
exit;

}
?>

<!DOCTYPE html>
<html>
<head>
<title>Edit Anggota</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

</head>
<body class="bg-light">

<div class="container mt-5">

<div class="card shadow">

<div class="card-header bg-warning">
<h4 class="mb-0">Edit Data Anggota</h4>
</div>

<div class="card-body">

<form method="POST" enctype="multipart/form-data">

<div class="row">

<div class="col-md-6 mb-3">
<label>Kode Anggota</label>
<input type="text" name="kode" class="form-control"
value="<?= $data['kode_anggota']; ?>" required>
</div>

<div class="col-md-6 mb-3">
<label>Nama</label>
<input type="text" name="nama" class="form-control"
value="<?= $data['nama']; ?>" required>
</div>

<div class="col-md-6 mb-3">
<label>Email</label>
<input type="email" name="email" class="form-control"
value="<?= $data['email']; ?>" required>
</div>

<div class="col-md-6 mb-3">
<label>Telepon</label>
<input type="text" name="telepon" class="form-control"
value="<?= $data['telepon']; ?>" required>
</div>

<div class="col-md-6 mb-3">
<label>Tanggal Lahir</label>
<input type="date" name="tanggal_lahir" class="form-control"
value="<?= $data['tanggal_lahir']; ?>" required>
</div>

<div class="col-md-6 mb-3">
<label>Jenis Kelamin</label>
<select name="jk" class="form-control">

<option <?= ($data['jenis_kelamin']=="Laki-laki")?'selected':''; ?>>
Laki-laki
</option>

<option <?= ($data['jenis_kelamin']=="Perempuan")?'selected':''; ?>>
Perempuan
</option>

</select>
</div>

<div class="col-md-12 mb-3">
<label>Alamat</label>
<textarea name="alamat" class="form-control" required><?= $data['alamat']; ?></textarea>
</div>

<div class="col-md-6 mb-3">
<label>Pekerjaan</label>
<input type="text" name="pekerjaan" class="form-control"
value="<?= $data['pekerjaan']; ?>">
</div>

<div class="col-md-6 mb-3">
<label>Status</label>
<select name="status" class="form-control">

<option <?= ($data['status']=="Aktif")?'selected':''; ?>>
Aktif
</option>

<option <?= ($data['status']=="Nonaktif")?'selected':''; ?>>
Nonaktif
</option>

</select>
</div>

<div class="col-md-6 mb-3">
<label>Foto Lama</label><br>

<?php if($data['foto']!=""){ ?>
<img src="uploads/<?= $data['foto']; ?>" width="100">
<?php } else { ?>
Tidak ada foto
<?php } ?>

</div>

<div class="col-md-6 mb-3">
<label>Upload Foto Baru</label>
<input type="file" name="foto" class="form-control">
</div>

</div>

<button type="submit" name="update" class="btn btn-warning">
Update
</button>

<a href="index.php" class="btn btn-secondary">
Kembali
</a>

</form>

</div>
</div>

</div>

</body>
</html>