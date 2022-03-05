<?php
if(!isset($_SESSION['signup'])){
  header('Location: signup.php');
  exit();
}

function h($str) { 
  return htmlspecialchars($str, ENT_QUOTES);
}

$userName = h($_SESSION['signup']['userName']);
$email = h($_SESSION['signup']['email']);
$password = $_SESSION['signup']['password'];
require_once('database/dbConnect.php'); 

$sql = $db->prepare('SELECT COUNT(*) AS cnt FROM users WHERE email=?'); //重複登録チェック
$sql->execute(array($email));
$duplicate = $sql->fetch();

if($duplicate['cnt'] > 0) {
  $errorFlg['email'] = 1; 
  $errorMsg['email'] = 'このメールアドレスは既に他のアカウントで使用されています。';
} 

if(empty($errorFlg)) {
  $insert = $db->prepare('INSERT INTO users SET userName=?, email=?, password=?');
  $insert->execute(array($userName, $email, password_hash($password, PASSWORD_DEFAULT))); 
  unset($_SESSION['signup']);
  $db = null;
  $signupFlg = 1;
}
?>