<?php
ini_set('display_errors', 0);
session_start();
require_once("../../dbconnect.php");
require_once("../../functions/user/operateTable.php");
require_once("../../functions/etc.php");

$error = $_SESSION["message"] ? $_SESSION["message"] : NULL;
$_SESSION["message"] = NULL;

// トークン生成
$_SESSION["token"] = base64_encode(openssl_random_pseudo_bytes(32));
$token = $_SESSION["token"];

// リダイレクト処理
$urltoken = isset($_GET["urltoken"]) ? $_GET["urltoken"] : NULL;
if ($urltoken === NULL) {
  redirect("../home.php", ※不正なアクセスです。);
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
  // バリデーション
  $name = preg_replace('/\A[\p{C}\p{Z}]++|[\p{C}\p{Z}]++\z/u', '', $_POST["name"]);
  $email = preg_replace('/\A[\p{C}\p{Z}]++|[\p{C}\p{Z}]++\z/u', '', $_POST["email"]);
  $password = preg_replace('/\A[\p{C}\p{Z}]++|[\p{C}\p{Z}]++\z/u', '', $_POST["password"]);
  $password_conf = preg_replace('/\A[\p{C}\p{Z}]++|[\p{C}\p{Z}]++\z/u', '', $_POST["password_conf"]);

  if (!isset($name) || empty($name)) {
    redirect("./signup.php?urltoken=".$urltoken, ※利用者名が入力されていません。);
  }

  $provisionalEmail = getProvisionalEmail($urltoken);
  if ($provisionalEmail !== $email) {
    redirect("./signup.php?urltoken=".$urltoken, ※メールアドレスが違います。);
  }

  if (!preg_match("/\A(?=.*?[a-z])(?=.*?[A-Z])(?=.*?\d)[a-zA-Z\d]{8,100}+\z/", $password)) {
    redirect("./signup.php?urltoken=".$urltoken, ※パスワードは８〜１００文字で半角英小文字・大文字・数字をそれぞれ１種類以上含んでいる必要があります。);
  }

  if ($password !== $password_conf) {
    redirect("./signup.php?urltoken=".$urltoken, ※パスワードが一致しません。);
  }

  $_SESSION["name"] = $name;
  $_SESSION["email"] = $email;
  $_SESSION["password"] = $password;

  redirect("./conf_signup.php?urltoken=".$urltoken, NULL);
}

$name = $_SESSION["name"];
$email = $_SESSION["email"];
$password = $_SESSION["password"];
$password_hide = str_repeat("*", strlen($password));
?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>本登録ページ</title>
</head>
<body>
  <h1>内容確認</h1>
  <?php if($error): ?>
    <h2 style="color:red"><?= $error ?></h2>
  <?php endif; ?>
  <div>
    <form action="./done_signup.php?urltoken=<?php print $urltoken; ?>" method="POST">
      <h1>新規登録</h1>
      <div>
        <label for="name">利用者名：<?= h($name) ?></label>
      </div>
      <div>
        <label for="email">メールアドレス：<?= h($email) ?></label>
      </div>
      <div>
        <label for="password">パスワード：<?= h($password_hide) ?></label>
      </div>
      <input type="hidden" name="token" value="<?= $token ?>">
      <input type="submit" value="新規登録">
    </form>
  </div>
</body>
</html>