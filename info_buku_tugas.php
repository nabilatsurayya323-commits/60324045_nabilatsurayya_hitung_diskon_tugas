<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Info Buku - Perpustakaan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <h1 class="mb-4">Informasi Buku</h1>

<?php
// Buku 1
$judul1 = "Pemrograman PHP Modern";
$pengarang1 = "Budi Raharjo";
$penerbit1 = "Informatika";
$tahun1 = 2023;
$isbn1 = "978-602-1234-56-7";
$harga1 = 85000;
$stok1 = 15;
$kategori1 = "Programming";
$bahasa1 = "Indonesia";
$halaman1 = 450;
$berat1 = 700;

// Buku 2
$judul2 = "MySQL Database Administration";
$pengarang2 = "Andi Saputra";
$penerbit2 = "Elex Media";
$tahun2 = 2022;
$isbn2 = "978-602-9988-11-3";
$harga2 = 95000;
$stok2 = 10;
$kategori2 = "Database";
$bahasa2 = "Inggris";
$halaman2 = 520;
$berat2 = 800;

// Buku 3
$judul3 = "Belajar HTML & CSS";
$pengarang3 = "Rina Kurnia";
$penerbit3 = "Gramedia";
$tahun3 = 2021;
$isbn3 = "978-602-7712-45-1";
$harga3 = 78000;
$stok3 = 8;
$kategori3 = "Web Design";
$bahasa3 = "Indonesia";
$halaman3 = 380;
$berat3 = 600;

// Buku 4
$judul4 = "Advanced JavaScript";
$pengarang4 = "Michael Smith";
$penerbit4 = "TechPress";
$tahun4 = 2024;
$isbn4 = "978-602-4567-89-0";
$harga4 = 120000;
$stok4 = 12;
$kategori4 = "Programming";
$bahasa4 = "Inggris";
$halaman4 = 600;
$berat4 = 900;

// fungsi warna badge kategori
function badgeKategori($kategori){
    if($kategori == "Programming") return "bg-primary";
    if($kategori == "Database") return "bg-success";
    if($kategori == "Web Design") return "bg-warning text-dark";
}
?>

<div class="row">

<?php
$buku = [
[$judul1,$pengarang1,$penerbit1,$tahun1,$isbn1,$harga1,$stok1,$kategori1,$bahasa1,$halaman1,$berat1],
[$judul2,$pengarang2,$penerbit2,$tahun2,$isbn2,$harga2,$stok2,$kategori2,$bahasa2,$halaman2,$berat2],
[$judul3,$pengarang3,$penerbit3,$tahun3,$isbn3,$harga3,$stok3,$kategori3,$bahasa3,$halaman3,$berat3],
[$judul4,$pengarang4,$penerbit4,$tahun4,$isbn4,$harga4,$stok4,$kategori4,$bahasa4,$halaman4,$berat4]
];

foreach($buku as $b){
?>

<div class="col-md-6 mb-4">
    <div class="card h-100">
        <div class="card-header bg-dark text-white">
            <h5 class="mb-0"><?php echo $b[0]; ?></h5>
        </div>

        <div class="card-body">

            <span class="badge <?php echo badgeKategori($b[7]); ?>">
                <?php echo $b[7]; ?>
            </span>

            <table class="table table-borderless mt-3">
                <tr>
                    <th>Pengarang</th>
                    <td>: <?php echo $b[1]; ?></td>
                </tr>
                <tr>
                    <th>Penerbit</th>
                    <td>: <?php echo $b[2]; ?></td>
                </tr>
                <tr>
                    <th>Tahun Terbit</th>
                    <td>: <?php echo $b[3]; ?></td>
                </tr>
                <tr>
                    <th>ISBN</th>
                    <td>: <?php echo $b[4]; ?></td>
                </tr>
                <tr>
                    <th>Bahasa</th>
                    <td>: <?php echo $b[8]; ?></td>
                </tr>
                <tr>
                    <th>Jumlah Halaman</th>
                    <td>: <?php echo $b[9]; ?> halaman</td>
                </tr>
                <tr>
                    <th>Berat</th>
                    <td>: <?php echo $b[10]; ?> gram</td>
                </tr>
                <tr>
                    <th>Harga</th>
                    <td>: Rp <?php echo number_format($b[5],0,',','.'); ?></td>
                </tr>
                <tr>
                    <th>Stok</th>
                    <td>: <?php echo $b[6]; ?> buku</td>
                </tr>
            </table>

        </div>
    </div>
</div>

<?php } ?>

</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>