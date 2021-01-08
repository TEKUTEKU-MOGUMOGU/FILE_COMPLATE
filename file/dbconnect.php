<?php
ini_set('display_errors', 0);
function dbconnect() {
  $dsn = 'mysql:dbname=tb220376db;host=localhost';
  $user = 'tb-220376';
  $password = '7A3JpFgm5s';

  try {
    $pdo = new PDO(
      $dsn,
      $user,
      $password,
      [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
      ],
    );
    return $pdo;
  } catch(PDOException $e) {
    $err = $e->getMessage();
    echo "接続に失敗しました。<br>エラー内容：{$err}";
    exit();
  }
}
?>