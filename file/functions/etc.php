<?php
ini_set('display_errors', 0);

// XSS対策
function h($str) {
  return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

// リダイレクト
function redirect($path, $message) {
  $_SESSION["message"] = $message;
  header("Location: ".$path);
  exit();
}
?>