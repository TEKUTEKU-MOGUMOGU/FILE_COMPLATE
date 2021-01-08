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
?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link rel ="stylesheet" href="./design/kigyoudesign.css">
  <title>マイページ</title>
</head>
<body>
    <h6>Rescue food</h6>
    <h1>レスキューフード一覧</h1>
<?php         
    //ファイルの受け取り
    if(empty($_POST["caption"])==false){
        $comment = $_POST["caption"];
        $file = $_FILES['img'];
        $filename = basename($file['name']);
        $tmp_path = $file['tmp_name'];
        $file_err = $file['error'];
        $filesize = $file['size'];
        $upload_dir = 'img';
        $save_filename = date('YmdHis').$filename;
        //保存される名前を作成
        $name ='img'.$save_filename;
        $sql = "CREATE TABLE IF NOT EXISTS storeimage"
	    ." ("
	    . "id INT AUTO_INCREMENT PRIMARY KEY,"
	    . "img BLOB NOT NULL,"
        . "comment TEXT,"
        . "email VARCHAR( 35 ) NOT NULL"
	    .");";
	    $stmt = $pdo->query($sql);
        $sql = $pdo -> prepare("INSERT INTO storeimage (img, comment, email) VALUES (:img, :comment, :email)");
        $sql -> bindParam(':img', $name, PDO::PARAM_STR);
        $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
        $sql -> bindParam(':email', $companymail, PDO::PARAM_STR);
        $sql -> execute();
?>
        <script>
                alert('写真を登録しました！次は店舗情報を登録して下さい！');
        </script>
<?php
        //キャプションを取得
        $caption = filter_input(INPUT_POST, 'caption',
            FILTER_SANITIZE_SPECIAL_CHARS);
    
        //キャプションのバリデーション
        //未入力
        if(empty($caption)){
            echo 'キャプションを入力して下さい。';
            echo '<br>';
        }
        //140文字以内か
        if(strlen($caption) > 140){
            echo 'キャプションを140文字以内で入力して下さい。';
            echo '<br>';
        }
        //ファイルのバリデーション
        //ファイルサイズが1MB未満か
        if($filesize > 1048576 || $file_err == 2){
            echo 'ファイルサイズは1MB未満にして下さい。';
            echo '<br>';
        }
        //拡張は画像形式か
        $allow_ext = array('jpg','jpeg','png');
        $file_ext = pathinfo($filename, PATHINFO_EXTENSION);
        if(!in_array(strtolower($file_ext),$allow_ext)){
            echo '画像ファイルを添付して下さい。';
            echo '<br>';
        }
        //ファイルはあるか？
        if(is_uploaded_file($tmp_path)){
            if(move_uploaded_file($tmp_path,$upload_dir.$save_filename)){
                echo '<br>';
            }else{
                echo 'ファイルが保存できませんでした。';
                echo '<br>';
            }
        }else{
            echo 'ファイルが選択されていません。';
            echo '<br>';
        }
    }
    //店舗画像を登録しているDBにアクセスし最新のデータを取得
    $sql = 'SELECT * FROM storeimage WHERE email=:email';
    $stmt=$pdo->prepare($sql);
    $stmt->bindParam(':email',$companymail,PDO::PARAM_STR);
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach ($results as $row){
        //最新の登録画像を変数に格納
        $img_name = $row['img'];   
        
        //同時に店舗情報を登録しているDBにアクセスし自社のメールアドレスと一致するデータを取得
        $sql = 'SELECT * FROM storedata where email=:email';
        $stmt=$pdo->prepare($sql);
        $stmt->bindParam(':email',$companymail,PDO::PARAM_STR);
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($results as $row){
            $stnum = substr($row['phone'],0,3);
            $midnum = substr($row['phone'],3,4);
            $finnum = substr($row['phone'],7,4);
            ?> 
            <div class="storeinfo">
            <table border="1">
            <tbody>
                <tr>
                    <td style="background-color:rgb(223,97,72); border-style:none; text-align: center; letter-spacing: 5px;" width="150" align="center">
                        <font color="#ffffff"><strong>店舗写真</strong></td>
                    <td style="background-color:rgb(247,234,233); border-style:none; text-align: center;" width="180" align="center">
                        <?php echo "<img src=\"../Company/$img_name\">";?></td>        
                </tr>
                <tr>
                    <td style="background-color:rgb(223,97,72); border-style:none; text-align: center; letter-spacing: 10px;" width="150" align="center">
                        <font color="#ffffff"><strong>店舗名</strong></td>
                    <td style="background-color:rgb(247,234,233); border-style:none; text-align: center;" width="180" align="center">
                        <?php echo $row['name'];?></td>        
                </tr>
                <tr>
                    <td style="background-color:rgb(223,97,72); border-style:none; text-align: center; letter-spacing: 15px;" width="150" align="center">
                        <font color="#ffffff"><strong>住所</strong></td>
                    <td style="background-color:rgb(247,234,233); border-style:none; text-align: center;" width="180" align="center">
                        <?php echo $row['adress'];?></td>        
                </tr>
                <tr>
                    <td style="background-color:rgb(223,97,72); border-style:none; text-align: center; letter-spacing: 5px;" width="150" align="center">
                        <font color="#ffffff"><strong>最寄り駅</strong></td>
                    <td style="background-color:rgb(247,234,233); border-style:none; text-align: center;" width="180" align="center">
                        <?php echo $row['station'];?></td>        
                </tr>
                <tr>
                    <td style="background-color:rgb(223,97,72); border-style:none; text-align: center; letter-spacing: 5px;" width="150" align="center">
                        <font color="#ffffff"><strong>営業時間</strong></td>
                    <td style="background-color:rgb(247,234,233); border-style:none; text-align: center;" width="180" align="center">
                        <?php echo $row['time'];?></td>        
                </tr>               
                <tr>
                    <td style="background-color:rgb(223,97,72); border-style:none; text-align: center; letter-spacing: 5px;" width="150" align="center">
                        <font color="#ffffff"><strong>電話番号</strong></td>
                    <td style="background-color:rgb(247,234,233); border-style:none; text-align: center;" width="180" align="center">
                        <?php echo $stnum."-".$midnum."-".$finnum; ?></td>        
                </tr>
            </tbody>
        </table> 
        </div>     
<?php
    //1つ目のforeach閉じる
            }
    //2つ目のforeach閉じる
        }     
?>   
<?php      
    $sql = 'SELECT * FROM fooddata where email=:email ORDER BY sirialno ASC';
	$stmt=$pdo->prepare($sql);
    $stmt->bindParam(':email',$companymail,PDO::PARAM_STR);
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    date_default_timezone_set("Asia/Tokyo");
    foreach ($results as $row){
        //$rowの中にはテーブルのカラム名が入る
        $time = strtotime($row['date']);
        $time2 = strtotime('now');
        $result = ($time-$time2)/(60*60*24);
        $datetime = new DateTime($row['date']);
        $current  = new DateTime();
        $diff     = $current->diff($datetime);
        if($result<=0){
            $delnum = $row['sirialno'];
            $sql = 'delete from fooddata where sirialno=:sirialno';
	        $stmt = $pdo->prepare($sql);
	        $stmt->bindParam(':sirialno', $delnum, PDO::PARAM_STR);
            $stmt->execute();
        }else{
?>
    <div class="productinfo">
        <table border="1">
            <tbody>
                <tr>
                    <td style="background-color: rgb(217,217,217); letter-spacing: 5px; border-style:none; text-align: center;" width="150" align="center">
                        <font color="#404040"><strong>No.</strong></td>
                    <td style="background-color: rgb(217,217,217);  letter-spacing: 10px; border-style:none; text-align: center;" width="150">
                        <font color="#404040"><strong>商品名</strong></td>
                    <td style="background-color: rgb(217,217,217);  letter-spacing: 10px; border-style:none; text-align: center;" width="150">
                        <font color="#404040"><strong>数量</strong></td>
                    <td style="background-color: rgb(217,217,217);  letter-spacing: 10px; border-style:none; text-align: center;" width="150">
                        <font color="#404040"><strong>料金</strong></td>    
                    <td style="background-color: rgb(217,217,217);  letter-spacing: 10px; border-style:none; text-align: center;" width="250">
                        <font color="#404040"><strong>期限</strong></td>        
                </tr>
                <tr>
                    <td style="background-color:#ffffff; border-style:none; text-align: center;" width="150" align="center">
                        <?php echo $row['sirialno'];?></td>
                    <td style="background-color:#ffffff; border-style:none; text-align: center;" width="150">
                        <?php echo $row['name'];?></td>
                    <td style="background-color:#ffffff; border-style:none; text-align: center;" width="150">
                        <?php echo $row['number'];?></td>
                    <td style="background-color:#ffffff; border-style:none; text-align: center;" width="150">
                        <?php echo $row['price'];?>円</td>
                    <td style="background-color:#ffffff; border-style:none; text-align: center;" width="150">
                        <?php printf('残り %d日 %d時間%d分',
                            $diff->days, $diff->h,$diff->i);?></td>    
                </tr>
            </tbody>
        </table> 
    </div>      
<?php
        }
    }
?>   
    <div class="centerrink">
        <a href="./LogIn/logout.php" class="btn-border">ログアウト</a>
        <a href="./home.php" class="btn-border">企業用紹介ページへ</a>
        <a href="./kigyouresist.php" class="btn-border">商品登録ページへ</a>
        <a href="./file_upload.php" class="btn-border">店舗情報登録ページへ</a>
    </div>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
        <script type="text/javascript" src="./javascript/picture.js"></script>
</body>
</html>