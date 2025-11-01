<?php
class Product
{
    private $pdo;
    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }
    public function getAllByUser($user_id, $keyword = '', $sort = 'created_at DESC', $limit = 5, $offset = 0)
    {
        $sql = "SELECT * FROM products WHERE user_id = :user_id";
        if ($keyword) $sql .= " AND name LIKE :kw";
        $sql .= " ORDER BY $sort LIMIT :offset, :limit";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':user_id', (int)$user_id, PDO::PARAM_INT);
        if ($keyword) $stmt->bindValue(':kw', "%$keyword%", PDO::PARAM_STR);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function countByUser($user_id, $keyword = '')
    {
        $sql = "SELECT COUNT(*) FROM products WHERE user_id = :user_id";
        if ($keyword) $sql .= " AND name LIKE :kw";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        if ($keyword) $stmt->bindValue(':kw', "%$keyword%", PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchColumn();
    }
    public function create($user_id, $name, $desc, $price)
    {
        $stmt = $this->pdo->prepare("INSERT INTO products (user_id, name, description, price, created_at, updated_at)
        VALUES( ?, ?, ?, ?, NOW(), NOW())");
        return $stmt->execute([$user_id, $name, $desc, $price]);
    }
    public function delete($id, $user_id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM products WHERE id=? AND user_id=?");
        return $stmt->execute([$id, $user_id]);
    }
}
