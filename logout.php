<?php 
session_start();

header('X-FRAME-OPTIONS: DENY');

if(!isset($_SESSION['user'])){
  header('Location: index.php');
  exit();
}

$_SESSION = array();
session_destroy();

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ログアウト画面</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <div class="wrapper">
    <div class="overall">
      <h1>ログアウト</h1>
      <p>ログアウトが完了しました。</p>
      <a href="index.php">ホームへ戻る</a>
    </div>
  </div>
</body>
</html>