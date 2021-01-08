<?php
ini_set('display_errors', 0);
session_start();
require_once("../../dbconnect.php");
require_once("../../functions/company/operateTable.php");
require_once("../../functions/etc.php");

// リダイレクト処理
$urltoken = isset($_GET["urltoken"]) ? $_GET["urltoken"] : NULL;
if ($urltoken === NULL) {
  redirect("../home.php", ※トークンがありません。);
} else {
  try {
    $result = checkUrltoken($urltoken);
    if (!$result) {
      redirect("../home.php", ※リンクの有効期限が切れたため、このURLはご利用できません。);
    }
    $_SESSION["email"] = $result;
  } catch (Exception $e) {
    $err = $e->getMessage();
    redirect("../home.php", "エラー内容"."<br>".$err);
  }
}

// 二重投稿対策
if($_SERVER['REQUEST_METHOD'] === 'POST'){

  try {
    registration($_SESSION["name"], $_SESSION["email"], $_SESSION["password"]);
    // セッション破棄
    $_SESSION = [];
    if (isset($_COOKIE["PHPSESSID"])) {
      setcookie("PHPSESSID", '', time() - 1800, '/');
    }
    session_destroy();

  } catch (Exception $e) {
    $error = $e->getMessage();
    redirect("../home.php", "エラー内容"."<br>".$err);
  }
  redirect("./done_signup.php?urltoken=".$urltoken, NULL);
}

try {
  deleteProvisionalEmail($urltoken);
  redirect("../LogIn/login.php", "登録が完了しました。引き続きサービスをご利用になる場合は、ログインして下さい。");
} catch (Exception $e) {
  $error = $e->getMessage();
  redirect("./conf_signup.php?urltoken=".$urltoken, "エラー内容"."<br>".$err);
}
?>