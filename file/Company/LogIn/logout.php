<?php
ini_set('display_errors', 0);
session_start();
require_once("../../functions/etc.php");

// セッションの有効期限をチェック
if(!$_SESSION["login_company"]) {
  redirect("./login.php", "※セッションの有効期限が切れました。再度ログインしてください。");
}

// ログアウト成功時
$_SESSION = [];
session_destroy();

// ログアウト完了確認
if(!$_SESSION["login_company"]) {
  redirect("../home.php", NULL);
}
?>