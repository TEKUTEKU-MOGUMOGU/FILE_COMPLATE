<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel ="stylesheet" href="./design/kigyoudesign.css">
    <title>Document</title>
</head>
<body>
<h1>お店情報登録</h1>
<form enctype="multipart/form-data" action="./mypage.php" method="POST" class="center">
      <div class="file-up">
        <input type="hidden" name="MAX_FILE_SIZE" value="1048576" />
        <input name="img" type="file" accept="image/*" />
      </div>
      <div>
        <textarea
          name="caption"
          placeholder="キャプション（140文字以下）"
          id="caption"
        ></textarea>
      </div>
      <div class="submit">
        <input type="submit" value="送信" class="btn-flat-simple" style="width: 75%;"/>
      </div>
</form>
<div class="store">
    <form method ="post" action="">
            <table border="1">
                <tbody>
                    <tr>
                        <td style="background-color: rgb(223,97,72); border-style:none;" width="150" align="center">
                            <font color="#ffffff"><strong>店舗名</strong></td>
                        <td style="background-color:rgb(248,248,248); border-style:none;" width="200">
                            <input type="text" name="name" size="70"></td>
                    </tr>
                    <tr>
                        <td style="background-color: rgb(223,97,72); border-style:none;" width="150" align="center">
                            <font color="#ffffff"><strong>住所</strong></td>
                        <td style="background-color:rgb(248,248,248); border-style:none;" width="200">
                            <input type="text" name="adress" size="70"></td>
                    </tr>
                    <tr>
                        <td style="background-color: rgb(223,97,72); border-style:none;" width="150" align="center">
                            <font color="#ffffff"><strong>最寄り駅</strong></td>
                        <td style="background-color:rgb(248,248,248); border-style:none;" width="200">
                            <input type="text" name="station" size="70"></td>
                    </tr>
                    <tr>
                        <td style="background-color: rgb(223,97,72); border-style:none;" width="150" align="center">
                            <font color="#ffffff"><strong>営業時間</strong></td>
                        <td style="background-color:rgb(248,248,248); border-style:none;" width="200">
                            <input type="text" name="time" size="70"></td>
                    </tr>
                    <tr>
                        <td style="background-color: rgb(223,97,72); border-style:none;" width="150" align="center">
                            <font color="#ffffff"><strong>電話番号</strong></td>
                        <td style="background-color:rgb(248,248,248); border-style:none;" width="200">
                            <input type="tel" name="phone" size="70"></td>
                    </tr>
                </tbody>
            </table>
            <input type="submit" name="submit" value="お店情報登録" class="btn-flat-simple" style="display: block; margin-left: auto; margin-right: auto; margin-bottom: 20px; width: 30%;"/>
    </form>
</div>     
<?php
    ini_set('display_errors', 0);
    session_start();
    require_once("../dbconnect.php");
    $pdo = dbconnect();
    if($_SESSION["login_company"] === NULL) {
      redirect("./home.php", "不正なアクセスです。");
    }
    if(!empty($_SESSION["login_company"])) {
        $company = $_SESSION["login_company"];
        $companymail = current(array_slice($company, 3, 1));
    }
    $sql = "CREATE TABLE IF NOT EXISTS storedata"
	    ." ("
        . "id INT AUTO_INCREMENT PRIMARY KEY,"
        . "name char(32),"
	    . "adress char(255),"
        . "station char(255),"
        . "time char(255),"
        . "email VARCHAR( 35 ) NOT NULL,"
	    . "phone varchar(255)"
	    .");";
	$stmt = $pdo->query($sql);
	$sql = $pdo -> prepare("INSERT INTO storedata (name, adress, station, time, email, phone) VALUES (:name, :adress, :station, :time, :email, :phone)");
    $sql -> bindParam(':name', $name, PDO::PARAM_STR);
    $sql -> bindParam(':adress', $adress, PDO::PARAM_STR);
    $sql -> bindParam(':station', $station, PDO::PARAM_STR);
    $sql -> bindParam(':time', $time, PDO::PARAM_STR);
    $sql -> bindParam(':email', $companymail, PDO::PARAM_STR);
	$sql -> bindParam(':phone', $phone, PDO::PARAM_STR);
    if(empty($_POST["name"])==false && empty($_POST["adress"])==false &&
    empty($_POST["station"])==false && empty($_POST["time"])==false && empty($_POST["phone"])==false){
          $name = $_POST["name"];
          $adress = $_POST["adress"];
          $station = $_POST["station"];
          $time = $_POST["time"];
	      $phone = $_POST["phone"];
          $sql -> execute();
?>
        <script>
                alert('お店情報を登録しました！');
        </script>
<?php    
    }  
?>
    <div class="centerfile">
        <a href="./mypage.php" class="btn-border">商品一覧ページへ戻る</a>
    </div>
</body>
</html>