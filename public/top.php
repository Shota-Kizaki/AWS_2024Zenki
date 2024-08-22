<?php
$dbh = new PDO('mysql:host=mysql;dbname=kyototech', 'root', '');

// トピック一覧を取得する
$sth = $dbh->prepare('SELECT topic_id, topic_name FROM topics ORDER BY topic_name ASC');
$sth->execute();
$topics = $sth->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>掲示板 トップページ</title>
</head>
<body>

<div style="margin-bottom: 1em;">
  <h1 style="text-align: center;">掲示板 トピック一覧</h1>
</div>

<div style="width: 100%; text-align: center; margin-bottom: 1.5em;">
    <a href="create_topic.php">新しいトピックを作成する</a>
</div>

<div style="width: 100%; text-align: center;">
    <ul style="list-style: none; padding: 0;">
        <?php foreach($topics as $topic): ?>
            <li style="margin-bottom: 1em;">
                <a href="chat.php?topic_id=<?= $topic['topic_id'] ?>&page=1" style="text-decoration: none; font-size: 1.2em;">
                    <?= htmlspecialchars($topic['topic_name'], ENT_QUOTES, 'UTF-8') ?>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
</div>

</body>
</html>
