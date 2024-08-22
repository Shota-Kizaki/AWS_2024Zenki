<?php
$dbh = new PDO('mysql:host=mysql;dbname=kyototech', 'root', '');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $topic_name = $_POST['topic_name'];

    if (!empty($topic_name)) {
        // トピックをデータベースに挿入
        $sth = $dbh->prepare('INSERT INTO topics (topic_name) VALUES (:topic_name)');
        $sth->bindParam(':topic_name', $topic_name, PDO::PARAM_STR);
        $sth->execute();

        // トピック作成後、トップページにリダイレクト
        header('Location: top.php');
        exit;
    } else {
        $error = "トピック名を入力してください。";
    }
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>トピック作成</title>
</head>
<body>

<div style="margin-bottom: 1em;">
  <h1 style="text-align: center;">トピック作成</h1>
</div>

<div style="width: 100%; text-align: center;">
    <?php if (isset($error)): ?>
        <p style="color: red;"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></p>
    <?php endif; ?>
    <form action="create_topic.php" method="POST">
        <input type="text" name="topic_name" placeholder="トピック名を入力" required>
        <button type="submit">作成</button>
    </form>
</div>

</body>
</html>
