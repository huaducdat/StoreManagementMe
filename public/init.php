<?php
require_once __DIR__ . '/../config/db_config.php';
require_once __DIR__ . '/../database/Database.php';

$config = require __DIR__ . '/../config/db_config.php';
$db = new Database($config);
$pdo = $db->connect();

function showMessage($text, $color = 'black'){
    echo "<p style='color:$color;'>$text</p>";
}
echo "<h2>🚀 Products Management System</h2>";
echo "<p>Select below Action:</p>";

if(isset($_POST['created_server'])){
    try{
        $pdo->exec("DROP DATABASE IF EXISTS db_products");
        $pdo->exec("CREATE DATABASE db_products CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        showMessage("✅ Created <b>db_products</b> successfully!", 'green');
    } catch(PDOException $e)
    {
        showMessage("❌ Created Server error: " . $e->getMessage());
    } 
}

if(isset($_POST['init_data'])){
    try{
        $sqlFile = __DIR__ . '/../database/init.sql';
        if(!file_exists($sqlFile)){
            showMessage("⚠️ Found no file database/init.sql", 'red');
        } else{
            $sql = file_get_contents($sqlFile);
            $pdo->exec('USE db_products');
            $pdo->exec($sql);
            showMessage("✅ Create table and data successfully!", 'green');
        }
    } catch(PDOException $e){
        showMessage("❌ Error when create data: " . $e->getMessage(), 'red');
    }
}
?>

<form action="" method="post" style="margin-top: 20px;">
    <button type="submit" name="created_server">🖥️ Create Server</button>
    <button type="submit" name="init_data">💽 Create Data</button>
</form>
<p style="color: gray;">Can play by Terminal:</p>
<pre>php public/init.php</pre>