# これはAWSの授業の2024年前期課題です。

## 概要
最低限の機能はあります。投稿機能、投稿した時間が自動で保存されます。
レスアンカー機能はうまくできませんでした。ですが、代わりにユーザーIDをIPアドレスをMD5でキャッシュ化して自動で作成するものを作成しました。
スマホで見やすいようには掲示板を使ったことがなかったのでイメージがわかなくて諦めました。。
他にもトピックを作成できたり、自動でそれぞれ表示できるようにしました。

## SQL
1. トピック管理
```sql
CREATE TABLE topics (
    topic_id INT AUTO_INCREMENT PRIMARY KEY,
    topic_name VARCHAR(255) NOT NULL
);
```
2. チャット管理
```sql
CREATE TABLE chat (
    name_id TEXT NOT NULL,
    name VARCHAR(255) NOT NULL,
    res_number INT NOT NULL,
    post_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    content TEXT NOT NULL,
    topic_id INT NOT NULL,
    FOREIGN KEY (topic_id) REFERENCES topics(topic_id)
);
```
