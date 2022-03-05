<?php 
session_start();

header('X-FRAME-OPTIONS: DENY');

function h($str) { 
  return htmlspecialchars($str, ENT_QUOTES);
}

if(!isset($_SESSION['token'])){
  $token = bin2hex(random_bytes(32)); 
  $_SESSION["token"] = $token;
}

if(isset($_POST['login'])){
  if($_POST['email'] == ''){
    $errorFlg['email'] = 1;
    $errorMsg['email'] = '* メールアドレスを入力してください';
  }
  if($_POST['password'] == ''){
    $errorFlg['password'] = 1;
    $errorMsg['password'] = '* パスワードを入力してください';
  }

  if(empty($errorFlg)){
    $email = h($_POST['email']);
    $password = $_POST['password'];
    require_once('database/dbConnect.php'); 
    $select = $db->prepare('SELECT * FROM users where email=?');
    $select->execute(array($email));
    $record = $select->fetch();
    if($record){
      $db = null; 
      $hashedPassword = $record['password'];
      if(password_verify($password, $hashedPassword)){
        if(isset($_POST['token']) && $_POST['token'] === $_SESSION['token']){
          $_SESSION['user'] = $record['userName']; 
          header('Location: register.php');
          exit();
        } else {
          echo '不正なリクエストです。最初からやり直してください。';
          exit();
        }
      } else {
        $errorFlg['login'] = 1;
        $errorMsg['login'] = 'メールアドレス・パスワードが一致しません。';
      }
    } else {
      $errorFlg['login'] = 1;
      $errorMsg['login'] = 'メールアドレス・パスワードが一致しません。';
    }
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>アカウントログイン画面</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <div class="wrapper">
    <div class="overall">
      <h1>ログイン</h1>
      <p>ログイン情報を入力してください。</p>
      <?php if(isset($errorFlg['login'])): ?>
      <p class="error"><?php echo $errorMsg['login']; ?></p>
      <?php endif; ?>
      <form method="post">
        <input type="hidden" name="token" value="<?php echo $_SESSION["token"]; ?>">
        <div class="form-group">
          <input type="email" class="formInfo" name="email" placeholder="メールアドレス" value="<?php if(isset($_POST['email'])){echo $_POST['email'];} ?>">
          <?php if(isset($errorFlg['email'])): ?>
          <p class="error"><?php echo $errorMsg['email']; ?></p>
          <?php endif; ?>
        </div>
        <div class="form-group">
          <input type="password" class="formInfo" name="password" placeholder="パスワード"  value="<?php if(isset($_POST['password'])){echo $_POST['password'];} ?>">
          <?php if(isset($errorFlg['password'])): ?>
          <p class="error"><?php echo $errorMsg['password']; ?></p>
          <?php endif; ?>
        </div>
        <button type="submit" name="login" style="cursor:pointer">ログイン</button>
        <a href="signup.php">会員登録はこちら</a>
      </form>
    </div>
  </div>
</body>
</html>