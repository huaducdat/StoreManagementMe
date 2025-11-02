<?php
if (php_sapi_name() !== 'cli') {
    $remote = $_SERVER['REMOTE_ADDR'] ?? '';
    if (!in_array($remote, ['127.0.0.1', '::1'])) {
        http_response_code(403);
        echo "Forbidden.";
        exit;
    }
}

function generate_hash($pw)
{
    if (defined('PASSWORD_ARGON2ID')) {
        $algo = PASSWORD_ARGON2ID;
        $name = 'Argon2id';
    } else {
        $algo = PASSWORD_BCRYPT;
        $name = 'Bcrypt';
    }
    return [$name, password_hash($pw, $algo)];
}
$result = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['password'])) {
    $password = $_POST['password'];
    [$algoName, $hash] = generate_hash($pw);
    $result = [
        'algo' => $algoName,
        'hash' => $hash,
        'time' => date('Y-m-d:i:s'),
    ];
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>🔐 Password Generator (local dev)</title>
</head>

<body>
    <div>
        <h2>🔑 Create Hash Password</h2>
        <form action="" method="post">
            <label for="">Enter password for hash:</label>
            <input type="password" name="password" placeholder="Enter Password..." required>
            <button type="submit">Create Hash</button>
        </form>

        <?php if ($result): ?>
            <hr>
            <h3>Result: (<?= htmlspecialchars($result['algo']) ?>)</h3>
            <p><b>Time: </b>(<?= htmlspecialchars($result['time']) ?>)</p>
            <pre><?= htmlspecialchars($result['hash']) ?></pre>
            <p>Copy this string to innit.php create Admin user.</p>
        <?php endif; ?>
    </div>
</body>
<footer>
    <hr>
    Only for local dev, do not deploy this page to server public.<br>
    <?php if (php_sapi_name() !== 'cli'): ?>
        <small>Access Address: <?= htmlspecialchars($_SERVER['REMOTE_ADDR'] ?? 'unknown') ?></small>
    <?php endif; ?>
</footer>

</html>