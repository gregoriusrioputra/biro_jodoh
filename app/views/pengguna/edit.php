<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Edit Pengguna</title>
<link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
<div class="container">
    <h1>Edit Pengguna</h1>
    <form method="post" action="/update">
        <input type="hidden" name="id" value="<?php echo $user['id']; ?>">
        <label>Nama: <input type="text" name="nama" value="<?php echo htmlspecialchars($user['nama']); ?>" required></label><br><br>
        <label>Umur: <input type="number" name="umur" value="<?php echo htmlspecialchars($user['umur']); ?>"></label><br><br>
        <label>Jenis Kelamin:
            <select name="jenis_kelamin">
                <option value="">-</option>
                <option value="L" <?php echo ($user['jenis_kelamin']=='L')?'selected':''; ?>>L</option>
                <option value="P" <?php echo ($user['jenis_kelamin']=='P')?'selected':''; ?>>P</option>
            </select>
        </label><br><br>

        <fieldset>
            <legend>Pilih Hobi (boleh lebih dari satu)</legend>
            <?php $sel = array_column($user['hobi'],'id'); ?>
            <?php foreach($hobi as $h): ?>
                <label><input type="checkbox" name="hobi_ids[]" value="<?php echo $h['id']; ?>" <?php echo in_array($h['id'],$sel)?'checked':''; ?>> <?php echo htmlspecialchars($h['nama']); ?></label><br>
            <?php endforeach; ?>
        </fieldset>

        <br>
        <button type="submit">Simpan</button>
    </form>
    <p><a href="/">Kembali</a></p>
</div>
</body>
</html>
