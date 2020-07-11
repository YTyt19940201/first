<?php
    // MySQLサーバ接続に必要な値を変数に代入
    $host = 'localhost';
    $username = 'root';
    $password = '';
    $db_name = 'bookshelf_mini';

    // 変数を設定して、MySQLサーバに接続
    $database = mysqli_connect($host, $username, $password, $db_name);

    // 接続を確認し、接続できていない場合にはエラーを出力して終了する
    if ($database == false) {
        die('Connect Error (' . mysqli_connect_errno() . ') ' . mysqli_connect_error());
    }

    // MySQL に utf8 で接続するための設定をする
    $charset = 'utf8';
    mysqli_set_charset($database, $charset);

    // ここにMySQLを使ったなんらかの処理を書く
    // フォームから書籍タイトルが送信されていればデータベースに保存する
    if (isset($_POST['book_title'])) {
        // 実行するSQLを作成
        $sql = 'INSERT INTO books (book_title) VALUES(?)';
        // ユーザ入力に依存するSQLを実行するので、セキュリティ対策をする
        $statement = mysqli_prepare($database, $sql);
        // ユーザ入力データ($_POST['book_title'])をVALUES(?)の?の部分に代入する
        mysqli_stmt_bind_param($statement, 's', $_POST['book_title']);
        // SQL文を実行する
        mysqli_stmt_execute($statement);
        // SQL文を破棄する
        mysqli_stmt_close($statement);
    }
    
    // 
    $sql = 'SELECT * FROM books ORDER BY created_at DESC';
    $result = mysqli_query($database, $sql);

    // MySQLを使った処理が終わると、接続は不要なので切断する
    mysqli_close($database);
?>
<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="utf-8">
        <title>Bookshelf | カンタン！あなたのオンライン本棚</title>
        <link rel="stylesheet" href="bookshelf.css">
    </head>
    <body>
<?php
    // フォームデータ送受信確認用コード（コメントアウトで表示されない）
    // print '<div style="background-color: skyblue;">';
    // print '<p>動作確認用:</p>';
    // var_dump($_POST['book_title']);
    // print '</div>';
?>
        <a href="bookshelf_mini.php"><h1>Bookshelf</h1></a>
        <h2>書籍の登録フォーム</h2>
        <form action="bookshelf_mini.php" method="post">
            <input type="text" name="book_title" placeholder="書籍タイトルを入力" required>
            <input type="submit" name="submit_add_book" value="登録">
        </form>
        <h2>登録された書籍一覧</h2>
        <ul>
<?php
            if ($result) {
                while ($record = mysqli_fetch_assoc($result)) {
                    $book_title = $record['book_title'];
?>
                    <li><?php print htmlspecialchars($book_title, ENT_QUOTES, 'UTF-8'); ?></li>

<?php
                }
                mysqli_free_result($result);
            }
?>
        </ul>
    </body>
</html>