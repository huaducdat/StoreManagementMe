<?php
require_once __DIR__ . '/../config/db_config.php';
require_once __DIR__ . '/../database/Database.php';
require_once __DIR__ . '/../modules/User.php';

$config = require __DIR__ . '/../config/db_config.php';
$db = new Database($config, 'db_products');
$pdo = $db->connect();
$userModel = new User($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ok = $userModel->register($_POST['fullname'], $_POST['email'], $_POST['password'], $_POST['address'], $_POST['birthday']);
    if ($ok) {
        header('Location: login.php');
        exit;
    } else {
        $error = "Email was exists!";
    }
}
?>

<form action="" method="post">
    <h2>Register an account</h2>
    <?= isset($error) ? "<p style='color:red;'>$error</p>" : "" ?><br><br>
    <input type="text" name="fullname" placeholder="Fullname" required><br><br>
    <input type="email" name="email" placeholder="Email" required><br><br>
    <input type="password" name="password" placeholder="Password" required><br><br>
    <input type="text" placeholder="Address" name="address"><br><br>
    <input type="date" name="birthday" placeholder="Birthday"><br><br>
    <hr>
    <button type="submit">Register</button><br><br>

</form>
<p>Have an account? <a href="login.php">Login now.</a></p>