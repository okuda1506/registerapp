<?php 
session_start();

header('X-FRAME-OPTIONS: DENY');

if(!isset($_SESSION['user'])){
  header('Location: index.php');
  exit();
}

//クリックジャッキング対策
header('X-FRAME-OPTIONS: DENY');

if(!isset($_SESSION['token'])){
  $token = bin2hex(random_bytes(32));   //トークン生成
  $_SESSION["token"] = $token;
}

if(isset($_POST['register'])){
  if($_POST['lastName'] == ''){
    $errorFlg['lastName'] = 'blank'; //入力欄フラグ
  }
  if($_POST['firstName'] == ''){
    $errorFlg['firstName'] = 'blank';
  }
  if($_POST['middleName'] == ''){
    $errorFlg['middleName'] = 'blank';
  }
  if($_POST['email'] == ''){
    $errorFlg['email'] = 'blank';
  }

  if(empty($errorFlg)){
    if(isset($_POST['token']) && $_POST['token'] === $_SESSION['token']){  //csrf対策：tokenの一致判定
      $_SESSION['register'] = $_POST;
      //テーブルにinsertして登録完了通知メール送信
      require_once('insertPeopleTable.php');
      // require_once('sendMail.php');
      header('Location: registerList.php');
      exit();
    } else {
      echo '不正なリクエストです。最初から登録し直して下さい。';
      exit();
    }
  }
}

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
    <h1>登録画面</h1>
    <p>こんにちは、<?php echo $_SESSION['user']; ?>さん</p>
    <a href="logout.php">ログアウト</a>
    <form method="post" action="">
      <input type="hidden" name="token" value="<?php echo $_SESSION["token"]; ?>">
      <table>
        <tr>
          <th>お名前: </th>
          <td>
            姓: <input type="text" name="lastName" value="<?php if(isset($_POST['lastName'])){echo h($_POST['lastName']);} ?>">
            ミドル: <input type="text" name="middleName" value="<?php if(isset($_POST['middleName'])){echo h($_POST['middleName']);} ?>">  
            名: <input type="text" name="firstName" value="<?php if(isset($_POST['firstName'])){echo h($_POST['firstName']);} ?>">
          </td>
        </tr>
        <?php if(isset($errorFlg['lastName'])): ?>
        <tr class="error">
          <th></th>
          <td>* 姓を入力してください。</td>
        </tr>
        <?php endif; ?>
        <?php if(isset($errorFlg['middleName'])): ?>
        <tr class="error">
          <th></th>
          <td>* ミドルネームを入力してください。</td>
        </tr>
        <?php endif; ?>
        <?php if(isset($errorFlg['firstName'])): ?>
        <tr class="error">
          <th></th>
          <td>* 名を入力してください。</td>
        </tr>
        <?php endif; ?>
        <tr>
          <th>メールアドレス: </th>
          <td>
            <input type="email" name="email" value="<?php if(isset($_POST['email'])){echo h($_POST['email']);} ?>">
          </td>
        </tr>
        <?php if(isset($errorFlg['email'])): ?>
        <tr class="error">
          <th></th>
          <td>* 入力が不正です。メールアドレスを正しく入力してください。</td>
        </tr>
        <?php endif; ?>
        <tr><th></th><td><input type="submit" name="register" value="登録" style="cursor:pointer"></td></tr>
      </table>
    </form>
    <p>登録データ一覧は<a href="registerList.php">こちら</a></p>
  </div>
</div>
</body>
</html>