<?php
ini_set('display_errors', 0);
// メール情報
// メールホスト名・gmailでは smtp.gmail.com
define('MAIL_HOST','smtp.gmail.com');

// メールユーザー名・アカウント名・メールアドレスを@込でフル記述
define('MAIL_USERNAME','printaisa@gmail.com');

// メールパスワード・上で記述したメールアドレスに即したパスワード
define('MAIL_PASSWORD','masa0610BAIKINN');

// SMTPプロトコル(sslまたはtls)
define('MAIL_ENCRPT','ssl');

// 送信ポート(ssl:465, tls:587)
define('SMTP_PORT', 465);

// メールアドレス・ここではメールユーザー名と同じでOK
define('MAIL_FROM','printaisa@gmail.com');

// 表示名
define('MAIL_FROM_NAME','サポート');

// メールタイトル
define('MAIL_SUBJECT','新規会員登録のご案内【TEKUTEKU MOGUMOGU】');

