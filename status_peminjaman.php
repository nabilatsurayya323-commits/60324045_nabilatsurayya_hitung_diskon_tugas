<?php
// Data Anggota
$nama_anggota = "Budi Santoso";
$total_pinjaman = 2;
$buku_terlambat = 1;
$hari_keterlambatan = 5; // hari

// Konstanta
$maks_pinjaman = 3;
$denda_per_hari = 1000;
$maks_denda = 50000;

// ======================
// Hitung Denda
// ======================
$total_denda = 0;

if ($buku_terlambat > 0) {
    $total_denda = $buku_terlambat * $hari_keterlambatan * $denda_per_hari;

    // Batasi maksimal denda
    if ($total_denda > $maks_denda) {
        $total_denda = $maks_denda;
    }
}

// ======================
// Cek Status Peminjaman (IF-ELSEIF-ELSE)
// ======================
if ($buku_terlambat > 0) {
    $status = "Tidak bisa meminjam (ada buku terlambat)";
} elseif ($total_pinjaman >= $maks_pinjaman) {
    $status = "Tidak bisa meminjam (sudah mencapai batas maksimal)";
} else {
    $status = "Boleh meminjam buku";
}

// ======================
// Tentukan Level Member (SWITCH)
// ======================
switch (true) {
    case ($total_pinjaman >= 0 && $total_pinjaman <= 5):
        $level = "Bronze";
        break;
    case ($total_pinjaman >= 6 && $total_pinjaman <= 15):
        $level = "Silver";
        break;
    case ($total_pinjaman > 15):
        $level = "Gold";
        break;
    default:
        $level = "Tidak diketahui";
}

// ======================
// Output
// ======================
echo "<h2>Status Peminjaman Anggota</h2>";
echo "Nama Anggota: $nama_anggota <br>";
echo "Total Pinjaman: $total_pinjaman <br>";
echo "Level Member: $level <br><br>";

echo "<strong>Status:</strong> $status <br><br>";

if ($buku_terlambat > 0) {
    echo "<strong>Peringatan!</strong><br>";
    echo "Anda memiliki $buku_terlambat buku terlambat selama $hari_keterlambatan hari.<br>";
    echo "Total Denda: Rp " . number_format($total_denda, 0, ',', '.') . "<br>";
} else {
    echo "Tidak ada keterlambatan.<br>";
}
?>