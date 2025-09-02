<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Daftar Pengguna</title>
<link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
<div class="container">
    <h1>Daftar Pengguna Biro Jodoh Berbasis Hobi</h1>
    <p><a href="/create">Tambah Pengguna</a> | <a href="/hobi">Kelola Hobi</a></p>

    <?php if(empty($users)): ?>
        <p>Tidak ada pengguna yang ditemukan.</p>
    <?php else: ?>
        <table>
            <thead><tr><th>Nama</th><th>Umur</th><th>Jenis Kelamin</th><th>Hobi</th><th>Aksi</th></tr></thead>
            <tbody>
            <?php foreach($users as $u): ?>
                <tr>
                    <td><?php echo htmlspecialchars($u['nama']); ?></td>
                    <td><?php echo htmlspecialchars($u['umur']); ?></td>
                    <td><?php echo htmlspecialchars($u['jenis_kelamin']); ?></td>
                    <td><?php echo htmlspecialchars(implode(', ', array_column($u['hobi'],'nama'))); ?></td>
                    <td>
                        <a href="/edit?id=<?php echo $u['id']; ?>">Edit</a> |
                        <a href="/delete?id=<?php echo $u['id']; ?>" onclick="return confirm('Hapus?')">Hapus</a> |
                        <a href="/matches?id=<?php echo $u['id']; ?>">Lihat Kecocokan</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>
</body>
</html>
