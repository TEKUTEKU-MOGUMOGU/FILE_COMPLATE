<?php
ini_set('display_errors', 0);
session_start();
require 'src/Exception.php';
require 'src/PHPMailer.php';
require 'src/SMTP.php';
require 'setting.php';
require_once("../../dbconnect.php");
require_once("../../functions/company/operateTable.php");
require_once("../../functions/etc.php");

// 二重投稿対策
if($_SERVER['REQUEST_METHOD'] === 'POST'){

    // バリデーション
    $email = preg_replace('/\A[\p{C}\p{Z}]++|[\p{C}\p{Z}]++\z/u', '', $_POST["email"]);

    if (empty($email)) {
        redirect("../SignUp/pre_signup.php", ※メールアドレスを入力して下さい。);
    }

    if (!preg_match("/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/", $email)) {
        redirect("../SignUp/pre_signup.php", ※メールアドレスの形式が正しくありません。);
    } 

    $result = checkEmail($email);
    if ($result) {
        redirect("../SignUp/pre_signup.php", ※入力されたメールアドレスは既に登録されております。);
    }

    // URLトークン生成
    $urltoken = hash("sha256", uniqid(rand(), 1));
    $url = "https://tb-220376.tech-base.net/file/Company/SignUp/signup.php?urltoken=".$urltoken;
    try {
        preRegistration($urltoken, $email);
    } catch (PDOException $e) {
        $err = $e->getMessage();
        redirect("../SignUp/pre_signup.php", "エラー内容"."<br>".$err);
    }


    // PHPMailerのインスタンス生成
        $mail = new PHPMailer\PHPMailer\PHPMailer();

        $mail->isSMTP(); // SMTPを使うようにメーラーを設定する
        $mail->SMTPAuth = true;
        $mail->Host = MAIL_HOST; // メインのSMTPサーバー（メールホスト名）を指定
        $mail->Username = MAIL_USERNAME; // SMTPユーザー名（メールユーザー名）
        $mail->Password = MAIL_PASSWORD; // SMTPパスワード（メールパスワード）
        $mail->SMTPSecure = MAIL_ENCRPT; // TLS暗号化を有効にし、「SSL」も受け入れます
        $mail->Port = SMTP_PORT; // 接続するTCPポート

        // メール内容設定
        $mail->CharSet = "UTF-8";
        $mail->Encoding = "base64";
        $mail->setFrom(MAIL_FROM,MAIL_FROM_NAME);
        $mail->addAddress($email, "名前"); //受信者（送信先）を追加する
    //    $mail->addReplyTo('xxxxxxxxxx@xxxxxxxxxx','返信先');
    //    $mail->addCC('xxxxxxxxxx@xxxxxxxxxx'); // CCで追加
    //    $mail->addBcc('xxxxxxxxxx@xxxxxxxxxx'); // BCCで追加
        $mail->Subject = MAIL_SUBJECT; // メールタイトル
        $mail->isHTML(true);    // HTMLフォーマットの場合はコチラを設定します
        $body = $url;

        $mail->Body  = "この度は会員登録にお申込みいただきありがとうございます。"
                        ."<br>"
                        ."<br>"
                        ."２４時間以内に下記リンクから、本登録を完了させて下さい。"
                        ."<br>"
                        ."<br>"
                        .$body
                        ."<br>"
                        ."<br>"
                        ."本登録が完了すると、本サービスをご利用いただけます。"
                        ."<br>"
                        ."<br>"
                        ."※２４時間を過ぎた場合は、再度、新規会員登録にお申込みください。"
                        ."<br>"
                        ."<br>"
                        ."※このメールの内容にお心当たりがない場合は、お手数ですがこのメールを破棄してください。"
                        ."<br>"
                        ."<br>"
                        ."※本メールは送信専用です。このメールに返信いただいてもお答えできませんのでご了承ください。";
        // メール送信の実行
        if(!$mail->send()) {
            redirect(
                "./send_test.php",
                "エラーが発生しました。下記エラー内容をご確認ください。"
                ."<br>"
                ."<br>"
                ."エラー内容：${error}"
            );
        } else {
            redirect(
                "./send_test.php",
                "入力されたメールアドレス宛にメールを送信しました。"
                ."<br>"
                ."メールに記載された手順で手続きを行って下さい。"
                ."<br>"
                ."※メールが届かない方は入力間違いがないか、迷惑メールフォルダに届いていないかをご確認下さい。"
            );
        }
}

$message = $_SESSION["message"];
// セッション破棄
$_SESSION = [];
session_destroy();
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
  <h1>新規登録</h1>
  <h2><?= $message ?></h2>
  </div>
</body>
</html>