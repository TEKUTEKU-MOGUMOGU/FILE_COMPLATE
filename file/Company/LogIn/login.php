<?php
ini_set('display_errors', 0);
session_start();
require_once("../../functions/etc.php");

// 既にログインしていたら、ログイン画面に遷移しない
if(!empty($_SESSION["login_company"])) {
  redirect("../mypage.php", NULL);
}

// リロード後にエラーを非表示にする
$error = $_SESSION["message"];
$_SESSION["message"] = NULL;
// $_SESSION = [];
// session_destroy();
?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link rel ="stylesheet" href="../design/kigyoudesign.css">
  <title>ログインページ</title>
</head>
<body>
  <div>
  <h1>ログイン</h1>
    <form action="done_login.php" method="POST" class="centerlogin">
      <?php if($error): ?>
        <p style="color:red"><?= $error ?></p>
      <?php endif; ?>
      <div>
        <label for="email">メールアドレス：</label>
        <input type="email" name="email" placeholder="Email">
      </div>
      <div>
        <label for="password">パスワード          ：</label>
        <input type="password" name="password" placeholder="Password">
      </div>
      <div style="padding-left:80px;">
        <input type="submit" value="ログイン" class="btn-flat-simple">
      </div>
    </form>
  </div>
</body>
</html>