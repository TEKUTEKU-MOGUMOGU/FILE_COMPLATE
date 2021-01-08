<?php 
ini_set('display_errors', 0);
session_start();

$error = $_SESSION["message"] ? $_SESSION["message"] : NULL;
$_SESSION["message"] = NULL;

// トークン生成
$_SESSION["token"] = base64_encode(openssl_random_pseudo_bytes(32));
$token = $_SESSION["token"];
?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link rel ="stylesheet" href="../kigyoudesign.css">
  <title>仮登録ページ</title>
</head>
<body>
  <?php if($error): ?>
    <h2 style="color:red"><?= $error ?></h2>
  <?php endif; ?>
  <div>
    <h1>仮登録フォーム</h1>
    <h2>仮登録メールを送信します。<br>メールに記載された手順で本登録を行って下さい。</h2>
    <form action="../phpmailer/send_test.php" method="POST" align="center">
      <div id="email_input">
        <label for="email">メールアドレス：</label>
        <input type="email" name="email" id="email" placeholder="Email">
      </div>
      <input type="hidden" name="token" value="<?php echo $token ?>">
      <input type="submit" value="メール送信" id="signup" class="btn-flat-simple">
    </form>
  </div>
</body>
</html>