<?php
$dbh = new PDO('mysql:host=mysql;dbname=kyototech', 'root', '');

// トップページから`topic_id`を取得する
$topic_id = isset($_GET['topic_id']) ? intval($_GET['topic_id']) : 1;

// トピックの存在を確認
$topic_sth = $dbh->prepare('SELECT COUNT(*) FROM topics WHERE topic_id = :topic_id;');
$topic_sth->bindParam(':topic_id', $topic_id, PDO::PARAM_INT);
$topic_sth->execute();
$topic_exists = $topic_sth->fetchColumn();

if (!$topic_exists) {
    print('指定されたトピックは存在しません!');
    exit;
}

// トピックの題名を取得
$topic_sth = $dbh->prepare('SELECT topic_name FROM topics WHERE topic_id = :topic_id;');
$topic_sth->bindParam(':topic_id', $topic_id, PDO::PARAM_INT);
$topic_sth->execute();
$topic = $topic_sth->fetch(PDO::FETCH_ASSOC);

$topic_name = htmlspecialchars($topic['topic_name'], ENT_QUOTES, 'UTF-8');

// ページ数をURLクエリパラメータから取得。無い場合は1ページ目とみなす
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;

// 1ページあたりの行数を決める
$count_per_page = 10;

// ページ数に応じてスキップする行数を計算
$skip_count = $count_per_page * ($page - 1);

// 全行数取得
$count_sth = $dbh->prepare('SELECT COUNT(*) FROM chat WHERE topic_id = :topic_id;');
$count_sth->bindParam(':topic_id', $topic_id, PDO::PARAM_INT);
$count_sth->execute();
$count_all = $count_sth->fetchColumn();

// ページが存在するかどうかを確認
if ($skip_count >= $count_all && $count_all > 0) {
    print('このページは存在しません!');
    exit;
}

// 掲示板の投稿を取得
$select_sth = $dbh->prepare('SELECT * FROM chat WHERE topic_id = :topic_id ORDER BY res_number ASC LIMIT :count_per_page OFFSET :skip_count');
$select_sth->bindParam(':topic_id', $topic_id, PDO::PARAM_INT);
$select_sth->bindParam(':count_per_page', $count_per_page, PDO::PARAM_INT);
$select_sth->bindParam(':skip_count', $skip_count, PDO::PARAM_INT);
$select_sth->execute();

?>

<?php
$dbh = new PDO('mysql:host=mysql;dbname=kyototech', 'root', '');

// トップページから`topic_id`を取得する
$topic_id = isset($_GET['topic_id']) ? intval($_GET['topic_id']) : 1;
$sth = $dbh->prepare('SELECT topic_id, topic_name FROM topics ORDER BY topic_name ASC');
// トピックの存在を確認
$topic_sth = $dbh->prepare('SELECT COUNT(*) FROM topics WHERE topic_id = :topic_id;');
$topic_sth->bindParam(':topic_id', $topic_id, PDO::PARAM_INT);
$topic_sth->execute();
$topic_exists = $topic_sth->fetchColumn();

if (!$topic_exists) {
    print('指定されたトピックは存在しません!');
    exit;
}

// トピックの題名を取得
$topic_sth = $dbh->prepare('SELECT topic_name FROM topics WHERE topic_id = :topic_id;');
$topic_sth->bindParam(':topic_id', $topic_id, PDO::PARAM_INT);
$topic_sth->execute();
$topic = $topic_sth->fetch(PDO::FETCH_ASSOC);

$topic_name = htmlspecialchars($topic['topic_name'], ENT_QUOTES, 'UTF-8');

// ページ数をURLクエリパラメータから取得。無い場合は1ページ目とみなす
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;

// 1ページあたりの行数を決める
$count_per_page = 10;

// ページ数に応じてスキップする行数を計算
$skip_count = $count_per_page * ($page - 1);

// 全行数取得
$count_sth = $dbh->prepare('SELECT COUNT(*) FROM chat WHERE topic_id = :topic_id;');
$count_sth->bindParam(':topic_id', $topic_id, PDO::PARAM_INT);
$count_sth->execute();
$count_all = $count_sth->fetchColumn();

// ページが存在するかどうかを確認
if ($skip_count >= $count_all && $count_all > 0) {
    print('このページは存在しません!');
    exit;
}

// 掲示板の投稿を取得
$select_sth = $dbh->prepare('SELECT * FROM chat WHERE topic_id = :topic_id ORDER BY res_number ASC LIMIT :count_per_page OFFSET :skip_count');
$select_sth->bindParam(':topic_id', $topic_id, PDO::PARAM_INT);
$select_sth->bindParam(':count_per_page', $count_per_page, PDO::PARAM_INT);
$select_sth->bindParam(':skip_count', $skip_count, PDO::PARAM_INT);
$select_sth->execute();

?>


<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>掲示板</title>
  <style>
    /* 全体的なデザイン */
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
      margin-bottom: 1em;
    }

    a {
      color: #007bff;
      text-decoration: none;
    }

    a:hover {
      text-decoration: underline;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 1em;
      background-color: #fff;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    th, td {
      padding: 0.75em;
      text-align: left;
      border-bottom: 1px solid #dee2e6;
      word-wrap: break-word;
      overflow-wrap: break-word;
    }

    th {
      background-color: #f1f3f5;
      color: #495057;
    }

    td {
      background-color: #ffffff;
    }

    /* 幅の調整 */
    th:nth-child(1), td:nth-child(1) {
      width: 5%; /* レス番号 */
    }

    th:nth-child(2), td:nth-child(2) {
      width: 15%; /* 名前 */
    }

    th:nth-child(3), td:nth-child(3) {
      width: 20%; /* ID */
    }

    th:nth-child(4), td:nth-child(4) {
      width: 20%; /* 日時 */
    }

    th:nth-child(5), td:nth-child(5) {
      width: 40%; /* 内容 */
    }

    /* 投稿フォームのデザイン */
    input[type="text"], textarea {
      width: 100%;
      padding: 0.75em;
      margin-bottom: 1em;
      border: 1px solid #ced4da;
      border-radius: 4px;
      box-sizing: border-box;
      font-size: 1em;
      color: #495057;
    }

    button {
      padding: 0.75em 1.5em;
      border: none;
      background-color: #007bff;
      color: white;
      border-radius: 4px;
      font-size: 1em;
      cursor: pointer;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    button:hover {
      background-color: #0056b3;
    }

    /* モバイルデバイス向けのスタイル調整 */
    @media screen and (max-width: 600px) {
      h1 {
        font-size: 1.4em;
        margin-bottom: 0.75em;
      }

      table, thead, tbody, th, td, tr {
        display: block;
        width: 100%;
      }

      th {
        display: none; /* ヘッダーを非表示に */
      }

      td {
        display: flex;
        justify-content: space-between;
        border: none;
        border-bottom: 1px solid #dee2e6;
        padding: 0.75em 0;
        font-size: 0.9em;
      }

      td::before {
        content: attr(data-label);
        flex-basis: 40%;
        text-align: left;
        font-weight: bold;
        color: #495057;
      }

      input[type="text"], textarea {
        font-size: 0.9em;
        padding: 0.75em;
      }

      button {
        width: 100%;
        font-size: 1em;
        padding: 0.75em;
        margin-top: 1em;
      }
    }

    /* レスポンシブ対応のためのフォームラッパー */
    .form-wrapper {
      margin-top: 2em;
      padding: 1.5em;
      background-color: #fff;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
      border-radius: 8px;
    }
  </style>
</head>
<body>

<div style="text-align: center; margin-top: 2em;">
  <h1>掲示板: <?= $topic_name ?></h1>
  <a href="top.php">トップページに戻る</a>
</div>

<div style="width: 100%; text-align: center; padding-top: 1em; border-top: 1px solid #ccc; margin-bottom: 1.5em;">
  <?= $page ?>ページ目 (全 <?= floor($count_all / $count_per_page) + 1 ?>ページ中)
</div>

<div style="display: flex; justify-content: center; margin-bottom: 2em;">
  <div style="width: 100%; max-width: 1000px;">
    <table>
      <thead>
        <tr>
          <th></th>
          <th>名前</th>
          <th>ID</th>
          <th>日時</th>
          <th>内容</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($select_sth as $post): ?>
          <tr id="post-<?= $post['res_number'] ?>" style="border-top: 1px solid #ccc;">
            <td data-label=" "><a href="#post-<?= $post['res_number'] ?>" style="text-decoration: none;"><?= htmlspecialchars($post['res_number'], ENT_QUOTES, 'UTF-8') ?></a></td>
            <td data-label="名前"><?= htmlspecialchars($post['name'], ENT_QUOTES, 'UTF-8') ?></td>
            <td data-label="ID"><?= htmlspecialchars($post['name_id'], ENT_QUOTES, 'UTF-8') ?></td>
            <td data-label="日時"><?= htmlspecialchars($post['post_date'], ENT_QUOTES, 'UTF-8') ?></td>
            <?php 
            $content_with_anchors = preg_replace('/>>(\d+)/', '<a href="#post-$1">>>$1</a>', htmlspecialchars($post['content'], ENT_QUOTES, 'UTF-8'));
            ?>
            <td data-label="内容"><?= $content_with_anchors ?></td>
          </tr>
        <?php endforeach ?>
      </tbody>
      <div style="display: flex; justify-content: space-between; margin-bottom: 2em;">
        <div>
          <?php if($page > 1): ?>
            <a href="chat.php?topic_id=<?= $topic_id ?>&page=<?= $page - 1 ?>">前のページ</a>
          <?php endif; ?>
        </div>
        <div>
          <?php if($count_all > $page * $count_per_page): ?>
            <a href="chat.php?topic_id=<?= $topic_id ?>&page=<?= $page + 1 ?>">次のページ</a>
          <?php endif; ?>
        </div>
      </div>
    </table>
    
    <div style="text-align: center; margin-top: 2em;">
      <button id="showFormButton">投稿する</button>
    </div>

    <div id="postForm" class="form-wrapper" style="display: none;">
      <form action="chat_post.php" method="POST">
        <input type="hidden" name="topic_id" value="<?= htmlspecialchars($topic_id, ENT_QUOTES, 'UTF-8') ?>">
        <div>
          <label for="name">名前:</label>
          <input type="text" id="name" name="name" required>
        </div>
        <div>
          <label for="content">内容:</label>
          <textarea id="content" name="content" required></textarea>
        </div>
        <div style="text-align: center;">
          <button type="submit">投稿する</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
  document.getElementById('showFormButton').addEventListener('click', function() {
    document.getElementById('postForm').style.display = 'block';
    this.style.display = 'none';
  });

  // レスアンカー機能のイベントリスナーをここで設定します
  document.querySelectorAll('a[href^="#post-"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
      e.preventDefault();
      const resNumber = this.getAttribute('href').substring(6);
      const contentField = document.getElementById('content');
      contentField.value += `>>${resNumber}\n`;
      contentField.focus();
    });
  });
</script>
</body>
</html>