<?php
ini_set('display_errors', 0);

// DB内のメールアドレスと照合
function checkEmail($email) {
  $pdo = dbconnect();

  $sql = "SELECT id FROM company WHERE email = ?";
  $stmt = $pdo->prepare($sql);
  $stmt->bindValue(1, $email, PDO::PARAM_STR);
  $stmt->execute();
  $result = $stmt->fetch();
  return $result;
}

// 仮登録
function preRegistration($urltoken, $email) {
  $pdo = dbconnect();

  $sql = "INSERT INTO pre_company (urltoken, email, date) VALUES (?, ?, now())";
  $stmt = $pdo->prepare($sql);
  $stmt->bindValue(1, $urltoken, PDO::PARAM_STR);
  $stmt->bindValue(2, $email, PDO::PARAM_STR);
  $stmt->execute();
}

// トークンの有効期限チェック
function checkUrltoken($urltoken) {
  $pdo = dbconnect();

  $sql = "SELECT email FROM pre_company WHERE urltoken = ? AND date > now() - interval 24 hour";
  $stmt = $pdo->prepare($sql);
  $stmt->bindValue(1, $urltoken, PDO::PARAM_STR);
  $stmt->execute();
  $row_count = $stmt->rowCount();

  if ($row_count == 1) {
    $email_array = $stmt->fetch();
    $email = $email_array["email"];
    return $email;
  } else {
    return NULL;
  }
}

// 仮登録したメールアドレス取得
function getProvisionalEmail($urltoken) {
  $pdo = dbconnect();

  $sql = "SELECT email FROM pre_company WHERE urltoken = ?";
  $stmt = $pdo->prepare($sql);
  $stmt->bindValue(1, $urltoken, PDO::PARAM_STR);
  $stmt->execute();
  $result = $stmt->fetch();
  return $result["email"];
}

// 企業 本登録
function registration($name, $email, $password) {
  $pdo = dbconnect();

  $sql = "INSERT INTO company (name, email, password, status, created_at, updated_at) VALUES (?, ?, ?, 1, now(), now())";
  $stmt = $pdo->prepare($sql);
  $stmt->bindValue(1, $name, PDO::PARAM_STR);
  $stmt->bindValue(2, $email, PDO::PARAM_STR);
  $stmt->bindValue(3, password_hash($password, PASSWORD_DEFAULT), PDO::PARAM_STR);
  $stmt->execute();
}

// 仮登録したメールアドレスを削除
function deleteProvisionalEmail($urltoken) {
  $pdo = dbconnect();

  $sql = "DELETE FROM pre_company WHERE urltoken = ?";
  $stmt = $pdo->prepare($sql);
  $stmt->bindValue(1, $urltoken, PDO::PARAM_STR);
  $stmt->execute();
}

// メールアドレスから企業データ取得
function getCompanyByEmail($email, $password) {
  $pdo = dbconnect();

  $sql = "SELECT * FROM company WHERE email = ?";
  $stmt = $pdo->prepare($sql);
  $stmt->bindValue(1, $email, PDO::PARAM_STR);
  $stmt->execute();
  $result = $stmt->fetch();
  return $result;
}

