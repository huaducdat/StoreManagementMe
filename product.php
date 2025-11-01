<?php
session_start();
if(!isset($_SESSION['user'])){
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