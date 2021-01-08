<?php 
ini_set('display_errors', 0);
session_start();
require_once("../dbconnect.php");
require_once("../functions/company/makeTable.php");

$error = $_SESSION["message"] ? $_SESSION["message"] : NULL;
$_SESSION["message"] = NULL;

// トークン生成
$_SESSION["token"] = base64_encode(openssl_random_pseudo_bytes(32));
$token = $_SESSION["token"];

// テーブル作成
makePreRegistrationTable();
makeRegistrationTable();

?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link rel ="stylesheet" href="./design/kigyoudesign.css">
  <title>企業用紹介ページ</title>
</head>
<body>
  <h1>企業用紹介ページ</h1>
  <?php if($error): ?>
    <h2 style="color:red"><?= $error ?></h2>
  <?php endif; ?>
  <?php if(!$_SESSION["login_company"]): ?>
    <div class="centerhomelogin">
      <a href="./SignUp/pre_signup.php" class="btn-border">新規登録ページへ</a>
      <a href="./LogIn/login.php" class="btn-border">ログインページへ</a>
    </div>
      <?php else: ?>
    <div class="centerhome">
      <a href="./LogIn/logout.php" class="btn-border">ログアウト</a>
      <a href="./mypage.php" class="btn-border">商品一覧ページへ</a>
    </div>
  <?php endif; ?>
</body>
</html>
