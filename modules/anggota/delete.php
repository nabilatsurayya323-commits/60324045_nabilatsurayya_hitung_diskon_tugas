<?php
include "../../koneksi.php";

// cek id
if(!isset($_GET['id'])){
header("Location:index.php");
exit;
}

$id = $_GET['id'];

// ambil data dulu
$data = mysqli_fetch_assoc(
mysqli_query($conn,"
SELECT * FROM anggota
WHERE id_anggota='$id'
")
);

// jika data tidak ditemukan
if(!$data){
header("Location:index.php");
exit;
}


// ================= HAPUS FOTO =================

if($data['foto'] != ""){

$file = "uploads/" . $data['foto'];

if(file_exists($file)){
unlink($file);
}

}


// ================= HAPUS DATABASE =================

mysqli_query($conn,"
DELETE FROM anggota
WHERE id_anggota='$id'
");


// ================= REDIRECT =================

header("Location:index.php");
exit;
?>