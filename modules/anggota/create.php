<?php include "../../koneksi.php"; ?>

<?php

if(isset($_POST['simpan'])){

$kode = trim($_POST['kode']);
$nama = trim($_POST['nama']);
$email = trim($_POST['email']);
$telepon = trim($_POST['telepon']);
$alamat = trim($_POST['alamat']);
$tanggal_lahir = $_POST['tanggal_lahir'];
$jk = $_POST['jk'];
$pekerjaan = trim($_POST['pekerjaan']);


// ================= VALIDASI =================

if(
$kode=="" || $nama=="" || $email=="" ||
$telepon=="" || $alamat=="" ||
$tanggal_lahir=="" || $jk==""
){
die("Semua field wajib diisi!");
}

// email
if(!filter_var($email,FILTER_VALIDATE_EMAIL)){
die("Format email tidak valid!");
}

// telepon
if(!preg_match('/^08[0-9]{8,13}$/',$telepon)){
die("Nomor telepon harus diawali 08");
}

// umur
$umur = date('Y') - date('Y', strtotime($tanggal_lahir));

if($umur < 10){
die("Umur minimal 10 tahun");
}

// cek kode unik
$cekKode = mysqli_query($conn,"
SELECT * FROM anggota
WHERE kode_anggota='$kode'
");

if(mysqli_num_rows($cekKode)>0){
die("Kode anggota sudah digunakan");
}

// cek email unik
$cekEmail = mysqli_query($conn,"
SELECT * FROM anggota
WHERE email='$email'
");

if(mysqli_num_rows($cekEmail)>0){
die("Email sudah digunakan");
}


// ================= FOTO =================

$foto = "";

if($_FILES['foto']['name'] != ""){

$foto = time()."_".$_FILES['foto']['name'];

move_uploaded_file(
$_FILES['foto']['tmp_name'],
"uploads/".$foto
);

}


// ================= SIMPAN =================

mysqli_query($conn,"INSERT INTO anggota(

kode_anggota,
nama,
email,
telepon,
alamat,
tanggal_lahir,
jenis_kelamin,
pekerjaan,
tanggal_daftar,
status,
foto

) VALUES (

'$kode',
'$nama',
'$email',
'$telepon',
'$alamat',
'$tanggal_lahir',
'$jk',
'$pekerjaan',
CURDATE(),
'Aktif',
'$foto'

)");

header("Location:index.php");
exit;

}
?>

<!DOCTYPE html>
<html>
<head>
<title>Tambah Anggota</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

</head>
<body class="bg-light">

<div class="container mt-5">

<div class="card shadow">

<div class="card-header bg-primary text-white">
<h4 class="mb-0">Tambah Anggota Baru</h4>
</div>

<div class="card-body">

<form method="POST" enctype="multipart/form-data">

<div class="row">

<div class="col-md-6 mb-3">
<label>Kode Anggota</label>
<input type="text" name="kode" class="form-control" required>
</div>

<div class="col-md-6 mb-3">
<label>Nama</label>
<input type="text" name="nama" class="form-control" required>
</div>

<div class="col-md-6 mb-3">
<label>Email</label>
<input type="email" name="email" class="form-control" required>
</div>

<div class="col-md-6 mb-3">
<label>Telepon</label>
<input type="text" name="telepon" class="form-control" required>
</div>

<div class="col-md-6 mb-3">
<label>Tanggal Lahir</label>
<input type="date" name="tanggal_lahir" class="form-control" required>
</div>

<div class="col-md-6 mb-3">
<label>Jenis Kelamin</label>
<select name="jk" class="form-control" required>
<option value="">-- Pilih --</option>
<option>Laki-laki</option>
<option>Perempuan</option>
</select>
</div>

<div class="col-md-12 mb-3">
<label>Alamat</label>
<textarea name="alamat" class="form-control" required></textarea>
</div>

<div class="col-md-6 mb-3">
<label>Pekerjaan</label>
<input type="text" name="pekerjaan" class="form-control">
</div>

<div class="col-md-6 mb-3">
<label>Foto</label>
<input type="file" name="foto" class="form-control">
</div>

</div>

<button type="submit" name="simpan" class="btn btn-primary">
Simpan
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