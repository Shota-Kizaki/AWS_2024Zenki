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

<div style="margin-bottom: 1em;">
  <h1 style="text-align: center;">掲示板: <?= $topic_name ?></h1>
</div>
<div style="width: 100%; text-align: center; margin-bottom: 1em;">
  <a href="top.php">トップページに戻る</a>
</div>
<div style="width: 100%; text-align: center; padding-top: 1em; border-top: 1px solid #ccc; margin-bottom: 0.5em">
  <?= $page ?>ページ目
  (全 <?= floor($count_all / $count_per_page) + 1 ?>ページ中)
</div>

<div style="display: flex; justify-content: center;">
  <div style="width: 100%; max-width: 1000px;">
    <table style="border: none; table-layout: fixed; border-collapse: collapse;">
      <thead>
        <tr>
          <th style="width: 10%; padding: 0.5em; border-right: 1px solid #ccc;">レス番号</th>
          <th style="width: 10%; padding: 0.5em; border-right: 1px solid #ccc;">名前</th>
          <th style='width: 10%; padding: 0.5em;'>ID</th>
          <th style="width: 20%; padding: 0.5em;">日時</th>
          <th style="width: 50%; padding: 0.5em;">内容</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($select_sth as $post): ?>
          <tr style="border-top: 1px solid #ccc;">
            <td style="padding: 0.5em; border-right: 1px solid #ccc;"><?= htmlspecialchars($post['res_number'], ENT_QUOTES, 'UTF-8') ?></td>
            <td style="padding: 0.5em; border-right: 1px solid #ccc;"><?= htmlspecialchars($post['name'], ENT_QUOTES, 'UTF-8') ?></td>
            <td style="padding: 0.5em;"><?= htmlspecialchars($post['name_id'], ENT_QUOTES, 'UTF-8') ?></td>
            <td style="padding: 0.5em;"><?= htmlspecialchars($post['post_date'], ENT_QUOTES, 'UTF-8') ?></td>
            <td style="padding: 0.5em;"><?= htmlspecialchars($post['content'], ENT_QUOTES, 'UTF-8') ?></td>
          </tr>
        <?php endforeach ?>
      </tbody>
    </table>
    
    <div style="text-align: center; margin-top: 1em;">
      <button id="showFormButton">投稿する</button>
    </div>

    <form id="postForm" action="chat_post.php" method="POST" style="display: none; margin-top: 1em;">
      <input type="hidden" name="topic_id" value="<?= htmlspecialchars($topic_id, ENT_QUOTES, 'UTF-8') ?>">
      <div style="margin-bottom: 1em;">
        <label for="name">名前:</label>
        <input type="text" id="name" name="name" required>
      </div>
      <div style="margin-bottom: 1em;">
        <label for="content">内容:</label>
        <textarea id="content" name="content" required></textarea>
      </div>
      <div style="text-align: center;">
        <button type="submit">投稿する</button>
      </div>
    </form>
  </div>
</div>

<script>
  document.getElementById('showFormButton').addEventListener('click', function() {
    document.getElementById('postForm').style.display = 'block';
    this.style.display = 'none';
  });
</script>
