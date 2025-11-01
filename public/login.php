<?php
session_start();
require_once __DIR__ . '/../config/db_config.php';
require_once __DIR__ . '/../database/Database.php';
require_once __DIR__ . '/../modules/User.php';

$config = require __DIR__ . '/../config/db_config.php';
$db = new Database($config, 'db_products');
$pdo = $db->connect();
$userModel = new User($pdo);

if($_SERVER['REQUEST_METHOD']==='POST'){
    $user = $userModel->login($_POST['email'], $_POST['password']);
    if($user)
    {
        $_SESSION['user'] = $user;
        header('Location: product.php');
        exit;
    } else{
        $error = "Password incorrect!";
    }
}
?>
<form action="" method="post">
    <h2>Login</h2>
    <?= isset($error) ? "<p style='color:red'>$error</p>" : '' ?><br><br>
    <input type="email" name="email" placeholder="Email" required><br><br>
    <input type="password" name="password" placeholder="Password" required><br><br><hr>
    <button type="submit">Login</button>
</form>
<p>Do not have an account? <a href="signin.php">Sign up now</a></p>