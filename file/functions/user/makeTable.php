<?php
ini_set('display_errors', 0);

// 仮登録 テーブル
function makePreRegistrationTable() {
  $pdo = dbconnect();

  $sql = "CREATE TABLE IF NOT EXISTS pre_user"
  ."("
  ."id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,"
  ."urltoken VARCHAR(128) NOT NULL,"
  ."email VARCHAR(128) NOT NULL,"
  ."date DATETIME NOT NULL"
  .");";
  $stmt = $pdo->query($sql);
}

// 本登録 テーブル
function makeRegistrationTable() {
  $pdo = dbconnect();

  $sql = "CREATE TABLE IF NOT EXISTS user"
  ."("
  ."id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,"
  ."name VARCHAR(128) NOT NULL,"
  ."password VARCHAR(128) NOT NULL,"
  ."email VARCHAR(128) NOT NULL,"
  ."status INT(1) NOT NULL DEFAULT 2,"
  ."created_at DATETIME,"
  ."updated_at DATETIME"
  .");";
  $stmt = $pdo->query($sql);
}
?>