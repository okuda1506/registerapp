<?php 
session_start();
//クリックジャッキング対策
header('X-FRAME-OPTIONS: DENY');

if(!isset($_SESSION['token'])){
  $token = bin2hex(random_bytes(32));   //トークン生成
  $_SESSION["token"] = $token;
}

if(isset($_POST['signup'])){
  //バリデーション
  if($_POST['userName'] == ''){
    $errorFlg['userName'] = 1;
    $errorMsg['userName'] = '* ユーザー名を入力してください';
  }
  if($_POST['email'] == ''){
    $errorFlg['email'] = 1;
    $errorMsg['email'] = '* メールアドレスを入力してください';
  } elseif(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
    $errorFlg['email'] = 1;
    $errorMsg['email'] = '* 正しいメールアドレスを入力してください';
  }
  if($_POST['password'] == ''){
    $errorFlg['password'] = 1;
    $errorMsg['password'] = '* パスワードを入力してください';
  } elseif(!preg_match('/\A(?=.*?[a-z])(?=.*?\d)[a-z\d]{8,50}+\z/i', $_POST['password'])){ //パスワード正規表現判定
    $errorFlg['password'] = 1;
    $errorMsg['password'] = '* パスワードは半角英数字8文字以上で入力してください';
  }

  if(empty($errorFlg)){
    if(isset($_POST['token']) && $_POST['token'] === $_SESSION['token']){ //csrf対策
      //DBにinsert
      $_SESSION['signup'] = $_POST;
      require_once('insertUsersTable.php');
    } else {
      echo '不正なリクエストです。最初からやり直してください。';
      exit();
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
  <title>アカウント登録画面</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <div class="wrapper">
    <div class="overall">
      <h1>アカウント登録</h1>
      <?php if(empty($signupFlg)):?>
      <p>アカウント情報を入力してください。</p>
      <form method="post">
        <input type="hidden" name="token" value="<?php echo $_SESSION["token"]; ?>"> 
        <div class="form-group">
          <input type="text" class="formInfo" name="userName" placeholder="ユーザー名" value="<?php if(isset($_POST['userName'])){echo $_POST['userName'];} ?>">
          <?php if(isset($errorFlg['userName'])): ?>
          <p class="error"><?php echo $errorMsg['userName']; ?></p>
          <?php endif; ?>
        </div>
        <div class="form-group">
          <input type="email" class="formInfo" name="email" placeholder="メールアドレス" value="<?php if(isset($_POST['email'])){echo $_POST['email'];} ?>">
          <?php if(isset($errorFlg['email'])): ?>
          <p class="error"><?php echo $errorMsg['email']; ?></p>
          <?php endif; ?>
        </div>
        <div class="form-group">
          <input type="password" class="formInfo" name="password" placeholder="パスワード" value="<?php if(isset($_POST['password'])){echo $_POST['password'];} ?>">
          <?php if(isset($errorFlg['password'])): ?>
          <p class="error"><?php echo $errorMsg['password']; ?></p>
          <?php endif; ?>
        </div>
        <button type="submit" name="signup" style="cursor:pointer">会員登録する</button>
        <a href="index.php">ログインはこちら</a>
      </form>
      <?php endif;?>
      <?php if(isset($signupFlg)): ?>
      <p>会員登録が完了しました。</p>
      <a href="index.php">ログインはこちら</a>
      <?php endif; ?>
    </div>
  </div>
</body>
</html>