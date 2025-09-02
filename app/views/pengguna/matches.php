<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Kecocokan</title>
<link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
<div class="container">
    <h1>Kecocokan</h1>
    <p><a href="/">Kembali</a></p>

    <?php if(empty($matches)): ?>
        <p>Tidak ada pengguna lain untuk dibandingkan.</p>
    <?php else: ?>
        <table>
            <thead><tr><th>Nama</th><th>Umur</th><th>Hobi</th><th>Kecocokan (%)</th></tr></thead>
            <tbody>
            <?php foreach($matches as $m): ?>
                <tr>
                    <td><?php echo htmlspecialchars($m['user']['nama']); ?></td>
                    <td><?php echo htmlspecialchars($m['user']['umur']); ?></td>
                    <td><?php echo htmlspecialchars(implode(', ', array_column($m['user']['hobi'],'nama'))); ?></td>
                    <td><?php echo $m['percent']; ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

</div>
</body>
</html>
