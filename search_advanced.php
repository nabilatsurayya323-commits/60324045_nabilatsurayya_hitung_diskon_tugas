<?php
session_start();

/* ================= DATA BUKU ================= */
$buku_list = [
    ["kode"=>"B001","judul"=>"Algoritma Dasar","kategori"=>"Informatika","pengarang"=>"Andi","penerbit"=>"Elex","tahun"=>2020,"harga"=>75000,"stok"=>5],
    ["kode"=>"B002","judul"=>"Pemrograman PHP","kategori"=>"Informatika","pengarang"=>"Budi","penerbit"=>"Informatika","tahun"=>2021,"harga"=>85000,"stok"=>0],
    ["kode"=>"B003","judul"=>"Basis Data","kategori"=>"Informatika","pengarang"=>"Citra","penerbit"=>"Andi","tahun"=>2019,"harga"=>90000,"stok"=>3],
    ["kode"=>"B004","judul"=>"Matematika Diskrit","kategori"=>"Matematika","pengarang"=>"Dedi","penerbit"=>"Erlangga","tahun"=>2018,"harga"=>95000,"stok"=>7],
    ["kode"=>"B005","judul"=>"Statistika","kategori"=>"Matematika","pengarang"=>"Eka","penerbit"=>"Graha","tahun"=>2017,"harga"=>65000,"stok"=>2],
    ["kode"=>"B006","judul"=>"Machine Learning","kategori"=>"AI","pengarang"=>"Fajar","penerbit"=>"Deepublish","tahun"=>2022,"harga"=>120000,"stok"=>6],
    ["kode"=>"B007","judul"=>"Jaringan Komputer","kategori"=>"Informatika","pengarang"=>"Gilang","penerbit"=>"Informatika","tahun"=>2016,"harga"=>70000,"stok"=>0],
    ["kode"=>"B008","judul"=>"Kalkulus","kategori"=>"Matematika","pengarang"=>"Hani","penerbit"=>"Erlangga","tahun"=>2015,"harga"=>80000,"stok"=>4],
    ["kode"=>"B009","judul"=>"Python Dasar","kategori"=>"Informatika","pengarang"=>"Indra","penerbit"=>"Andi","tahun"=>2023,"harga"=>99000,"stok"=>9],
    ["kode"=>"B010","judul"=>"Data Science","kategori"=>"AI","pengarang"=>"Joko","penerbit"=>"Deepublish","tahun"=>2021,"harga"=>110000,"stok"=>1],
];

/* ================= GET PARAM ================= */
$keyword   = $_GET['keyword'] ?? '';
$kategori  = $_GET['kategori'] ?? '';
$min_harga = $_GET['min_harga'] ?? '';
$max_harga = $_GET['max_harga'] ?? '';
$tahun     = $_GET['tahun'] ?? '';
$status    = $_GET['status'] ?? 'semua';
$sort      = $_GET['sort'] ?? 'judul';
$page      = $_GET['page'] ?? 1;

/* ================= VALIDASI ================= */
$errors = [];

if ($min_harga !== '' && $max_harga !== '' && $min_harga > $max_harga) {
    $errors[] = "Harga minimum tidak boleh lebih besar dari harga maksimum";
}

if ($tahun !== '' && ($tahun < 1900 || $tahun > date('Y'))) {
    $errors[] = "Tahun tidak valid";
}

/* ================= FILTER ================= */
$hasil = array_filter($buku_list, function($b) use ($keyword,$kategori,$min_harga,$max_harga,$tahun,$status){

    if ($keyword && !stripos($b['judul'].$b['pengarang'],$keyword)) return false;
    if ($kategori && $b['kategori'] != $kategori) return false;
    if ($min_harga !== '' && $b['harga'] < $min_harga) return false;
    if ($max_harga !== '' && $b['harga'] > $max_harga) return false;
    if ($tahun && $b['tahun'] != $tahun) return false;

    if ($status == 'tersedia' && $b['stok'] <= 0) return false;
    if ($status == 'habis' && $b['stok'] > 0) return false;

    return true;
});

/* ================= SORTING ================= */
usort($hasil, function($a,$b) use ($sort){
    return $a[$sort] <=> $b[$sort];
});

/* ================= PAGINATION ================= */
$perPage = 10;
$total = count($hasil);
$start = ($page-1)*$perPage;
$hasil = array_slice($hasil,$start,$perPage);

/* ================= HIGHLIGHT ================= */
function highlight($text,$keyword){
    if (!$keyword) return $text;
    return preg_replace("/($keyword)/i","<mark>$1</mark>",$text);
}

/* ================= EXPORT CSV ================= */
if(isset($_GET['export'])){
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="hasil.csv"');
    $out = fopen('php://output','w');
    fputcsv($out, array_keys($buku_list[0]));
    foreach($hasil as $row) fputcsv($out,$row);
    fclose($out);
    exit;
}

/* ================= SAVE RECENT ================= */
$_SESSION['recent'][] = $_GET;
?>
<!DOCTYPE html>
<html>
<head>
<title>Pencarian Buku</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-4">

<h3>Pencarian Buku</h3>

<?php if($errors): ?>
<div class="alert alert-danger"><?= implode("<br>",$errors) ?></div>
<?php endif; ?>

<form method="GET" class="row g-2 mb-3">
<input type="text" name="keyword" class="form-control col" placeholder="Keyword" value="<?= $keyword ?>">

<select name="kategori" class="form-select col">
<option value="">Semua Kategori</option>
<option <?= $kategori=='Informatika'?'selected':'' ?>>Informatika</option>
<option <?= $kategori=='Matematika'?'selected':'' ?>>Matematika</option>
<option <?= $kategori=='AI'?'selected':'' ?>>AI</option>
</select>

<input type="number" name="min_harga" placeholder="Min" value="<?= $min_harga ?>">
<input type="number" name="max_harga" placeholder="Max" value="<?= $max_harga ?>">
<input type="number" name="tahun" placeholder="Tahun" value="<?= $tahun ?>">

<select name="sort">
<option value="judul">Judul</option>
<option value="harga">Harga</option>
<option value="tahun">Tahun</option>
</select>

<div>
<label><input type="radio" name="status" value="semua" <?= $status=='semua'?'checked':'' ?>> Semua</label>
<label><input type="radio" name="status" value="tersedia" <?= $status=='tersedia'?'checked':'' ?>> Tersedia</label>
<label><input type="radio" name="status" value="habis" <?= $status=='habis'?'checked':'' ?>> Habis</label>
</div>

<button class="btn btn-primary">Cari</button>
<a href="?export=1" class="btn btn-success">Export CSV</a>
</form>

<p><strong><?= $total ?></strong> hasil ditemukan</p>

<table class="table table-bordered table-striped">
<tr>
<th>Kode</th><th>Judul</th><th>Kategori</th><th>Pengarang</th>
<th>Tahun</th><th>Harga</th><th>Stok</th>
</tr>

<?php foreach($hasil as $b): ?>
<tr>
<td><?= $b['kode'] ?></td>
<td><?= highlight($b['judul'],$keyword) ?></td>
<td><?= $b['kategori'] ?></td>
<td><?= highlight($b['pengarang'],$keyword) ?></td>
<td><?= $b['tahun'] ?></td>
<td><?= $b['harga'] ?></td>
<td><?= $b['stok'] ?></td>
</tr>
<?php endforeach; ?>
</table>

</div>
</body>
</html>