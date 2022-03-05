<?php 
session_start();

header('X-FRAME-OPTIONS: DENY');

if(!isset($_SESSION['user'])){
  header('Location: index.php');
  exit();
}

require_once('database/dbConnect.php'); 
// ↓gmailのレコードだけ取得
// $records = $db->query("SELECT * FROM people WHERE email LIKE '%gmail.com'");
$records = $db->query('SELECT * FROM people ORDER BY id DESC'); //全レコードをidの降順で取得
$db = null; // DB切断

function h($str) {
  return htmlspecialchars($str, ENT_QUOTES);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>個人情報登録アプリ</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="wrapper">
  <div class="overall">
    <h1>登録データ一覧</h1>
    <p>こんにちは、<?php echo $_SESSION['user']; ?>さん</p>
    <a href="logout.php">ログアウト</a>
    <table class="registerList">
      <tr><th class="column">#</th><th class="column">姓</th><th class="column">ミドル</th><th class="column">名</th><th class="column">メールアドレス</th></tr>
      <?php foreach($records as $record): ?>
        <tr>
          <td class="data"><?php echo h($record['id']); ?></td>
          <td class="data"><?php echo h($record['lastName']); ?></td>
          <td class="data"><?php echo h($record['middleName']); ?></td>
          <td class="data"><?php echo h($record['firstName']); ?></td>
          <td class="data"><?php echo h($record['email']); ?></td>
        </tr>
      <?php endforeach; ?>
    </table>
    <div class="btn">
        <button onClick="location.href='register.php'" style="cursor:pointer">入力へ</button>
        <button onClick="location.href='outputCsv.php'" style="cursor:pointer">CSVダウンロード</button>
    </div>
  </div>
</div>
</body>
</html>