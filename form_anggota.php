<?php
$errors = [];
$old = [];
$success = false;

function clean($data) {
    return htmlspecialchars(trim($data));
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Ambil data
    $old['nama'] = clean($_POST['nama'] ?? '');
    $old['email'] = clean($_POST['email'] ?? '');
    $old['telepon'] = clean($_POST['telepon'] ?? '');
    $old['alamat'] = clean($_POST['alamat'] ?? '');
    $old['jenis_kelamin'] = clean($_POST['jenis_kelamin'] ?? '');
    $old['tanggal_lahir'] = clean($_POST['tanggal_lahir'] ?? '');
    $old['pekerjaan'] = clean($_POST['pekerjaan'] ?? '');

    // VALIDASI

    // Nama
    if ($old['nama'] == '') {
        $errors['nama'] = "Nama wajib diisi";
    } elseif (strlen($old['nama']) < 3) {
        $errors['nama'] = "Minimal 3 karakter";
    }

    // Email
    if ($old['email'] == '') {
        $errors['email'] = "Email wajib diisi";
    } elseif (!filter_var($old['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Format email tidak valid";
    }

    // Telepon
    if ($old['telepon'] == '') {
        $errors['telepon'] = "Telepon wajib diisi";
    } elseif (!preg_match('/^08[0-9]{8,11}$/', $old['telepon'])) {
        $errors['telepon'] = "Format harus 08xxxxxxxxxx (10-13 digit)";
    }

    // Alamat
    if ($old['alamat'] == '') {
        $errors['alamat'] = "Alamat wajib diisi";
    } elseif (strlen($old['alamat']) < 10) {
        $errors['alamat'] = "Minimal 10 karakter";
    }

    // Jenis kelamin
    if (!in_array($old['jenis_kelamin'], ['Laki-laki','Perempuan'])) {
        $errors['jenis_kelamin'] = "Pilih jenis kelamin";
    }

    // Tanggal lahir
    if ($old['tanggal_lahir'] == '') {
        $errors['tanggal_lahir'] = "Tanggal lahir wajib diisi";
    } else {
        $lahir = new DateTime($old['tanggal_lahir']);
        $today = new DateTime();
        $umur = $today->diff($lahir)->y;

        if ($umur < 10) {
            $errors['tanggal_lahir'] = "Minimal umur 10 tahun";
        }
    }

    // Pekerjaan
    $valid = ['Pelajar','Mahasiswa','Pegawai','Lainnya'];
    if (!in_array($old['pekerjaan'], $valid)) {
        $errors['pekerjaan'] = "Pilih pekerjaan";
    }

    if (empty($errors)) {
        $success = true;
    }
}

function old($name, $old) {
    return $old[$name] ?? '';
}

function error($name, $errors) {
    return isset($errors[$name]) ? "<div class='invalid-feedback'>{$errors[$name]}</div>" : "";
}

function isInvalid($name, $errors) {
    return isset($errors[$name]) ? "is-invalid" : "";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Registrasi Anggota</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5" style="max-width:700px;">

<?php if ($success): ?>
    <!-- SUCCESS -->
    <div class="card shadow">
        <div class="card-header bg-success text-white">
            <h4>Registrasi Berhasil</h4>
        </div>
        <div class="card-body">
            <p><strong>Nama:</strong> <?= $old['nama'] ?></p>
            <p><strong>Email:</strong> <?= $old['email'] ?></p>
            <p><strong>Telepon:</strong> <?= $old['telepon'] ?></p>
            <p><strong>Jenis Kelamin:</strong> <?= $old['jenis_kelamin'] ?></p>
            <p><strong>Tanggal Lahir:</strong> <?= $old['tanggal_lahir'] ?></p>
            <p><strong>Pekerjaan:</strong> <?= $old['pekerjaan'] ?></p>
            <p><strong>Alamat:</strong> <?= nl2br($old['alamat']) ?></p>

            <a href="" class="btn btn-primary mt-3">Input Lagi</a>
        </div>
    </div>

<?php else: ?>

    <div class="card shadow">
        <div class="card-header">
            <h4>Form Registrasi Anggota</h4>
        </div>
        <div class="card-body">

            <form method="POST">

                <!-- Nama -->
                <div class="mb-3">
                    <label>Nama Lengkap</label>
                    <input type="text" name="nama" class="form-control <?= isInvalid('nama',$errors) ?>" value="<?= old('nama',$old) ?>">
                    <?= error('nama',$errors) ?>
                </div>

                <!-- Email -->
                <div class="mb-3">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control <?= isInvalid('email',$errors) ?>" value="<?= old('email',$old) ?>">
                    <?= error('email',$errors) ?>
                </div>

                <!-- Telepon -->
                <div class="mb-3">
                    <label>Telepon</label>
                    <input type="text" name="telepon" class="form-control <?= isInvalid('telepon',$errors) ?>" value="<?= old('telepon',$old) ?>">
                    <?= error('telepon',$errors) ?>
                </div>

                <!-- Alamat -->
                <div class="mb-3">
                    <label>Alamat</label>
                    <textarea name="alamat" class="form-control <?= isInvalid('alamat',$errors) ?>"><?= old('alamat',$old) ?></textarea>
                    <?= error('alamat',$errors) ?>
                </div>

                <!-- Jenis Kelamin -->
                <div class="mb-3">
                    <label>Jenis Kelamin</label><br>
                    <input type="radio" name="jenis_kelamin" value="Laki-laki" <?= old('jenis_kelamin',$old)=='Laki-laki'?'checked':'' ?>> Laki-laki
                    <input type="radio" name="jenis_kelamin" value="Perempuan" <?= old('jenis_kelamin',$old)=='Perempuan'?'checked':'' ?>> Perempuan
                    <?php if(isset($errors['jenis_kelamin'])): ?>
                        <div class="text-danger"><?= $errors['jenis_kelamin'] ?></div>
                    <?php endif; ?>
                </div>

                <!-- Tanggal Lahir -->
                <div class="mb-3">
                    <label>Tanggal Lahir</label>
                    <input type="date" name="tanggal_lahir" class="form-control <?= isInvalid('tanggal_lahir',$errors) ?>" value="<?= old('tanggal_lahir',$old) ?>">
                    <?= error('tanggal_lahir',$errors) ?>
                </div>

                <!-- Pekerjaan -->
                <div class="mb-3">
                    <label>Pekerjaan</label>
                    <select name="pekerjaan" class="form-select <?= isInvalid('pekerjaan',$errors) ?>">
                        <option value="">Pilih</option>
                        <?php foreach(['Pelajar','Mahasiswa','Pegawai','Lainnya'] as $p): ?>
                            <option value="<?= $p ?>" <?= old('pekerjaan',$old)==$p?'selected':'' ?>><?= $p ?></option>
                        <?php endforeach; ?>
                    </select>
                    <?= error('pekerjaan',$errors) ?>
                </div>

                <button class="btn btn-success">Daftar</button>

            </form>
        </div>
    </div>

<?php endif; ?>

</div>
</body>
</html>