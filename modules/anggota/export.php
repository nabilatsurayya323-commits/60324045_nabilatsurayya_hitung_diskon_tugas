<?php
include "../../koneksi.php";

// header excel
header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=data_anggota.xls");
header("Pragma: no-cache");
header("Expires: 0");

// ambil data
$data = mysqli_query($conn,"
SELECT * FROM anggota
ORDER BY id_anggota DESC
");
?>

<table border="1">

<tr style="font-weight:bold; background:#ddd;">
<th>No</th>
<th>Kode</th>
<th>Nama</th>
<th>Email</th>
<th>Telepon</th>
<th>Alamat</th>
<th>Tanggal Lahir</th>
<th>Jenis Kelamin</th>
<th>Pekerjaan</th>
<th>Tanggal Daftar</th>
<th>Status</th>
</tr>

<?php
$no = 1;

while($row = mysqli_fetch_assoc($data)){
?>

<tr>
<td><?= $no++; ?></td>
<td><?= $row['kode_anggota']; ?></td>
<td><?= $row['nama']; ?></td>
<td><?= $row['email']; ?></td>
<td><?= $row['telepon']; ?></td>
<td><?= $row['alamat']; ?></td>
<td><?= $row['tanggal_lahir']; ?></td>
<td><?= $row['jenis_kelamin']; ?></td>
<td><?= $row['pekerjaan']; ?></td>
<td><?= $row['tanggal_daftar']; ?></td>
<td><?= $row['status']; ?></td>
</tr>

<?php } ?>

</table>