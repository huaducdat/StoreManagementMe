<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}
require_once __DIR__ . '/../config/db_config.php';
require_once __DIR__ . '/../database/Database.php';
require_once __DIR__ . '/../modules/Product.php';

$config = require __DIR__ . '/../config/db_config.php';
$db = new Database($config, 'db_products');
$pdo = $db->connect();
$productModel = new Product($pdo);
$user = $_SESSION['user'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($_POST['action'] === 'add') {
        $productModel->create($user['id'], $_POST['name'], $_POST['descrition'], $_POST['price']);
    } elseif ($_POST['action'] === 'delete') {
        $productModel->delete($_POST['id'], $user['id']);
    }
}
$keyword = $_GET['search'] ?? '';
$page = max(1, intval($_GET['page'] ?? 1));
$limit = 5;
$offset = ($page - 1) * $limit;
$total = $productModel->countByUser($user['id'], $keyword);
$products = $productModel->getAllByUser($user['id'], $keyword, 'created_at DESC', $limit, $offset);
$total_pages = ceil($total / $limit);
?>

<h2>Hello, <?= htmlspecialchars($user['fullname']) ?>!</h2>
<a href="logout.php">Logout</a>
<form action="" method="get">
    <input type="text" name="search" placeholder="Search Product" value="<?= htmlspecialchars($keyword) ?>">
    <button type="submit">Search</button>
</form>
<hr>
<h3>Add Product</h3>
<form action="" method="post">
    <input type="hidden" name="action" value="add">
    <input type="text" name="name" placeholder="Product name" required>
    <input type="number" name="price" step="0.01" placeholder="Price" required>
    <textarea name="description" placeholder="Description"></textarea>
    <button type="submit">Add</button>
</form>
<hr>
<h3>Product List</h3>
<table border="1" cellpaddind="6">
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Price</th>
        <th>Description</th>
        <th>Action</th>
    </tr>
    <?php foreach ($products as $p): ?>
        <tr>
            <td><?= $p['id'] ?></td>
            <td><?= htmlspecialchars($p['name']) ?></td>
            <td><?= number_format($p['price'], 2) ?></td>
            <td><?= htmlspecialchars($p['description']) ?></td>
            <td>
                <form action="" method="post" style="display: inline;">
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="id" value="<?= $p['id'] ?>">
                    <button onclick="return confirm('Delete this Product?')">Delete</button>
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
</table>

<?php for ($i = 1; $i <= $total_pages; $i++): ?>
    <a href="?page=<?= $i ?>$search=<?= urlencode($keyword) ?>"><?= $i ?></a>
<?php endfor; ?>