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
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f8f9fa;
            color: #333;
        }

        h1 {
            font-size: 1.8em;
            color: #343a40;
            margin-bottom: 1.5em;
        }

        a {
            color: #007bff;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        .container {
            max-width: 1000px;
            margin: 0 auto;
            padding: 1em;
            background-color: #fff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        .topic-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .topic-list li {
            margin-bottom: 1em;
            border-bottom: 1px solid #dee2e6;
            padding-bottom: 0.5em;
        }

        .topic-list li:last-child {
            border-bottom: none;
        }

        .topic-link {
            font-size: 1.2em;
            color: #007bff;
            text-decoration: none;
            display: block;
            padding: 0.75em;
            border-radius: 4px;
            background-color: #f1f3f5;
            transition: background-color 0.2s ease;
        }

        .topic-link:hover {
            background-color: #e9ecef;
        }

        .new-topic-link {
            display: inline-block;
            margin-bottom: 1.5em;
            padding: 0.75em 1.5em;
            border-radius: 4px;
            background-color: #007bff;
            color: white;
            font-size: 1em;
            text-decoration: none;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            transition: background-color 0.2s ease;
        }

        .new-topic-link:hover {
            background-color: #0056b3;
        }

        /* モバイル対応 */
        @media screen and (max-width: 600px) {
            h1 {
                font-size: 1.4em;
            }

            .topic-link {
                font-size: 1em;
                padding: 0.5em;
            }

            .new-topic-link {
                width: 100%; /* 幅を100%に変更 */
                font-size: 1em;
                padding: 0.75em;
                box-sizing: border-box; /* paddingを含めた全体の幅を調整 */
            }
        }
    </style>
</head>
<body>

<div class="container">
    <div style="text-align: center; margin-bottom: 1.5em;">
        <h1>掲示板 トピック一覧</h1>
    </div>

    <div style="text-align: center; margin-bottom: 1.5em;">
        <a href="create_topic.php" class="new-topic-link">新しいトピックを作成する</a>
    </div>

    <div style="text-align: center;">
        <ul class="topic-list">
            <?php foreach($topics as $topic): ?>
                <li>
                    <a href="chat.php?topic_id=<?= $topic['topic_id'] ?>&page=1" class="topic-link">
                        <?= htmlspecialchars($topic['topic_name'], ENT_QUOTES, 'UTF-8') ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</div>

</body>
</html>
