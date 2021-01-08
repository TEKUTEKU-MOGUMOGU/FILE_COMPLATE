<?php
ini_set('display_errors', 0);
session_start();
require_once("../../dbconnect.php");
require_once("../../functions/company/operateTable.php");
require_once("../../functions/etc.php");


// $_SESSION["message"] = NULL;
$error = $_SESSION["message"] ? $_SESSION["message"] : NULL;
$_SESSION["message"] = NULL;

// トークン生成
$_SESSION["token"] = base64_encode(openssl_random_pseudo_bytes(32));
$token = $_SESSION["token"];

// リダイレクト処理
if (empty($_GET)) {
  redirect("../home.php", ※不正なアクセスです。);
}

$urltoken = isset($_GET["urltoken"]) ? $_GET["urltoken"] : NULL;
if ($urltoken === NULL) {
  redirect("../home.php", ※トークンがありません。不正なアクセスです。);
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
  <h1>新規登録</h1>
  <?php if($error): ?>
    <h2 style="color:red"><?= $error ?></h2>
  <?php endif; ?>
  <div>
    <form action="./conf_signup.php?urltoken=<?php print $urltoken; ?>" method="POST">
      <h1>本登録フォーム</h1>
      <div>
        <label for="name">企業名：</label>
        <input type="text" name="name" placeholder="Name">
      </div>
      <div>
        <label for="email">メールアドレス：</label>
        <input type="email" name="email" placeholder="Email">
      </div>
      <div>
        <label for="password">パスワード：</label>
        <input type="password" name="password" placeholder="Password">
      </div>
      <div>
        <label for="password_conf">パスワード確認：</label>
        <input type="password" name="password_conf" placeholder="Confirm">
      </div>
      <input type="hidden" name="token" value="<?php echo $token ?>">
      <input type="submit" value="新規登録">
    </form>
  </div>
</body>
</html>