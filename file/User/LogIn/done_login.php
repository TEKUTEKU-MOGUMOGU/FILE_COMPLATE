<?php
ini_set('display_errors', 0);
session_start();
require_once("../../dbconnect.php");
require_once("../../functions/user/operateTable.php");
require_once("../../functions/etc.php");

// 既にログインしていたら、ログイン画面に遷移しない
if(!empty($_SESSION["login_user"])) {
  redirect("../search.php", NULL);
}

// 二重投稿対策
if($_SERVER['REQUEST_METHOD'] === 'POST'){
  // 変数
  $email = $_POST["email"];
  $password = $_POST["password"];

  // メールアドレスからユーザーデータ取得
  $user = getUserByEmail($email, $password);

  // 該当するデータが無ければ、ログイン画面に戻す
  if(!$user) {
    redirect("./login.php", "※入力されたメールアドレスは登録されていません。");
  }

  // 該当するデータのパスワードと一致しなければ、ログイン画面に戻す
  if(!password_verify($password, $user["password"])) {
    redirect("./login.php", "※パスワードが違います。");
  }

  // ログイン成功時
  session_regenerate_id(true);
  $_SESSION["login_user"] = $user;
  
  redirect("./done_login.php", NULL);
}
?>