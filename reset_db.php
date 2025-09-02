<?php
// reset_db.php (put at project root)
$dbFile = __DIR__ . '/database/database.db';
if (file_exists($dbFile)) {
    unlink($dbFile);
}
$pdo = require __DIR__ . '/config/database.php';
require __DIR__ . '/config/init_db.php';
echo "Database reset - empty schema created.\n";
