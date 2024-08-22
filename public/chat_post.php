<?php
$dbh = new PDO('mysql:host=mysql;dbname=kyototech', 'root', '');

$name = $_POST['name'];
$content = $_POST['content'];
$topic_id = intval($_POST['topic_id']);

// レスナンバーの最大値を取得して、新しいレスナンバーを決定する
$sth = $dbh->prepare('SELECT MAX(res_number) FROM chat WHERE topic_id = :topic_id');
$sth->bindParam(':topic_id', $topic_id, PDO::PARAM_INT);
$sth->execute();
$max_res_number = $sth->fetchColumn();
$res_number = $max_res_number + 1;

//name_idを設定(IPアドレスを取得して、それをmd5でハッシュ化)
$str = $_SERVER["REMOTE_ADDR"];
$name_id = substr(md5($str),0,30);

// 新しい投稿を挿入する
$sth = $dbh->prepare('INSERT INTO chat (name_id, name, res_number, content, topic_id) VALUES (:name_id, :name, :res_number, :content, :topic_id)');
$sth->bindParam(':name_id', $name_id, PDO::PARAM_STR);
$sth->bindParam(':name', $name, PDO::PARAM_STR);
$sth->bindParam(':res_number', $res_number, PDO::PARAM_INT);
$sth->bindParam(':content', $content, PDO::PARAM_STR);
$sth->bindParam(':topic_id', $topic_id, PDO::PARAM_INT);
$sth->execute();

// 投稿後、元の掲示板ページにリダイレクトする
header("Location: chat.php?topic_id=$topic_id&page=1");
exit();
?>
