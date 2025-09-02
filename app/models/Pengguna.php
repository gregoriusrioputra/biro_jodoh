<?php
// app/models/Pengguna.php
class Pengguna {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Ambil semua pengguna + daftar hobi mereka
    public function all() {
        $stmt = $this->pdo->query("SELECT * FROM pengguna");
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($users as &$u) {
            $stmt2 = $this->pdo->prepare("
                SELECT hobi.id, hobi.nama 
                FROM pengguna_hobi 
                JOIN hobi ON hobi.id = pengguna_hobi.hobi_id
                WHERE pengguna_hobi.pengguna_id = ?
            ");
            $stmt2->execute([$u['id']]);
            $u['hobi'] = $stmt2->fetchAll(PDO::FETCH_ASSOC) ?: [];
        }

        return $users;
    }

    public function find($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM pengguna WHERE id = ?");
        $stmt->execute([$id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            $stmt2 = $this->pdo->prepare("
                SELECT hobi.id, hobi.nama 
                FROM pengguna_hobi 
                JOIN hobi ON hobi.id = pengguna_hobi.hobi_id
                WHERE pengguna_hobi.pengguna_id = ?
            ");
            $stmt2->execute([$user['id']]);
            $user['hobi'] = $stmt2->fetchAll(PDO::FETCH_ASSOC) ?: [];
        }

        return $user;
    }

    public function create($data) {
        $stmt = $this->pdo->prepare("INSERT INTO pengguna (nama, umur, jenis_kelamin) VALUES (?, ?, ?)");
        $stmt->execute([$data['nama'], $data['umur'], $data['jenis_kelamin']]);

        $userId = $this->pdo->lastInsertId();
        foreach ($data['hobi_ids'] as $hobiId) {
            $stmt = $this->pdo->prepare("INSERT INTO pengguna_hobi (pengguna_id, hobi_id) VALUES (?, ?)");
            $stmt->execute([$userId, $hobiId]);
        }
    }

    public function update($id, $data) {
        $stmt = $this->pdo->prepare("UPDATE pengguna SET nama = ?, umur = ?, jenis_kelamin = ? WHERE id = ?");
        $stmt->execute([$data['nama'], $data['umur'], $data['jenis_kelamin'], $id]);

        $stmt = $this->pdo->prepare("DELETE FROM pengguna_hobi WHERE pengguna_id = ?");
        $stmt->execute([$id]);

        foreach ($data['hobi_ids'] as $hobiId) {
            $stmt = $this->pdo->prepare("INSERT INTO pengguna_hobi (pengguna_id, hobi_id) VALUES (?, ?)");
            $stmt->execute([$id, $hobiId]);
        }
    }

    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM pengguna_hobi WHERE pengguna_id = ?");
        $stmt->execute([$id]);

        $stmt = $this->pdo->prepare("DELETE FROM pengguna WHERE id = ?");
        $stmt->execute([$id]);
    }

    public function getMatches($id) {
        $user = $this->find($id);
        $allUsers = $this->all();
        $matches = [];

        foreach ($allUsers as $otherUser) {
            if ($otherUser['id'] == $user['id']) continue;

            // Kalau jenis kelamin sama → langsung 0%
            if ($user['jenis_kelamin'] == $otherUser['jenis_kelamin']) {
                $matchPercent = 0;
            } else {
                $matchPercent = $this->hitungPersentase($user, $otherUser);
            }

            $matches[] = [
                'user' => $otherUser,
                'percent' => $matchPercent
            ];
        }

        return $matches;
    }

    private function hitungPersentase($user1, $user2) {
        $hobi1 = array_column($user1['hobi'], 'id');
        $hobi2 = array_column($user2['hobi'], 'id');

        $common = count(array_intersect($hobi1, $hobi2));
        $total = count(array_unique(array_merge($hobi1, $hobi2)));

        if ($total == 0) return 0;

        return round(($common / $total) * 100);
    }
}
