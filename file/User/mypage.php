<?php
ini_set('display_errors', 0);
session_start();
require_once("../dbconnect.php");
$pdo = dbconnect();

// 非ログイン時に遷移しない
if($_SESSION["login_user"] === NULL) {
  redirect("./home.php", "不正なアクセスです。");
}
if(empty($_POST["email"])==false){
    $email = $_POST["email"];
}
if(!empty($_SESSION["login_user"])) {
    $user = $_SESSION["login_user"];
    $usermail = current(array_slice($user, 3, 1));
}

?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link rel ="stylesheet" href="./design/design.css">
  <title>マイページ</title>
</head>
<body>
    <h6>Rescue food</h6>
    <h1>レスキューフード一覧</h1>
<?php       
    $sql = 'SELECT * FROM storeimage WHERE email=:email';
    $stmt=$pdo->prepare($sql);
    $stmt->bindParam(':email',$email,PDO::PARAM_STR);
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach ($results as $row){
    //最新の登録画像を変数に格納
        $img_name = $row['img'];   
    
        //同時に店舗情報を登録しているDBにアクセスし自社のメールアドレスと一致するデータを取得
        $sql = 'SELECT * FROM storedata where email=:email';
        $stmt=$pdo->prepare($sql);
        $stmt->bindParam(':email',$email,PDO::PARAM_STR);
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
    $dsn = 'mysql:dbname=tb220376db;host=localhost';
    $user = 'tb-220376';
    $password = '7A3JpFgm5s';
    $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
    $sql = 'SELECT * FROM fooddata where email=:email ORDER BY sirialno ASC';
    $stmt=$pdo->prepare($sql);
    $stmt->bindParam(':email',$email,PDO::PARAM_STR);
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
<!-- カートに入れる --><br>
<div class="flex">
    <form method ="post" action="mypage.php" name="form1" class="child1">
        <table border="1">
            <tbody>
                <tr>
                    <td width="150" style="border-style:none; padding-left:1em;">
                        <font color="#696969"><strong>商品番号</strong></td>
                </tr>
                <tr>
                    <td style="background-color:rgb(248,248,248); border-style:none; text-align: center;" width="300">
                        <input type="number" name="buysirialno" class="number"></td>
                </tr>    
                <tr>
                    <td width="150" style="border-style:none; padding-left:1em;">
                        <font color="#696969"><strong>数量</strong></td>
                </tr>
                <tr>
                    <td style="background-color:rgb(248,248,248); border-style:none; text-align: center;" width="300">
                        <input type="number" name="buynumber" class="number"></td>
                </tr>
            </tbody>
        </table>
        <input type="hidden" name="email" value="<?php echo "$email"; ?>">
        <input type="submit" class="btn-flat-simple" name="submit" value="カートに入れる" onClick="return checkcart()" size="60">
    </form>
    <!-- カートから戻す -->
    <form action="mypage.php" method="post" name="form1" class="child2">
        <table border="1" style="margin-top:5px;">
            <tbody>
                <tr>
                    <br>
                </tr>
                <tr>
                    <br>
                </tr>  
                <tr>
                    <td style= "border-style:none; padding-left:1em;" width="100">
                        <font color="#696969"><strong>商品番号</strong></td>
                </tr>
                <tr>
                    <td style="background-color:rgb(248,248,248); border-style:none; text-align: center;" width="300">
                        <input type="number" name="returnsirialno" size="50" class="number"></td>
                </tr>
            </tbody>
        </table>
        <input type="hidden" name="email" value="<?php echo "$email"; ?>">
        <input type="submit" class="btn-flat-simple" name="submit" value="カートから戻す"> 
    </form>    
</div>
<br>
<?php
    //カートから戻す
    if(empty($_POST["returnsirialno"])==false){
        $return = $_POST["returnsirialno"];
        if($return>=0){
            $sql = 'SELECT * FROM shoppingcart where email=:email';
            $stmt=$pdo->prepare($sql);
            $stmt->bindParam(':email',$usermail,PDO::PARAM_STR);
            $stmt->execute();
            $count1=$stmt->rowCount();
            $sql = 'delete from shoppingcart where buysirialno=:buysirialno';
	        $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':buysirialno', $return, PDO::PARAM_STR);
            $stmt->execute();
            $sql = 'SELECT * FROM shoppingcart where email=:email';
            $stmt=$pdo->prepare($sql);
            $stmt->bindParam(':email',$usermail,PDO::PARAM_STR);
            $stmt->execute();
            $count2=$stmt->rowCount();
            if($count1>$count2){
                ?>
                <script>
                    alert('商品を戻しました。');
                </script>
        <?php                 
            }else{
            ?>
                <script>
                    alert('入力された商品番号が見つかりません。');
                </script>
            <?php
            }
        }else{
            ?>
                <script>
                    alert('商品番号は０以上の数値を入力して下さい。');
                </script>
            <?php
        }
    }   
    //カートに登録
    if(empty($_POST["buysirialno"])==false && empty($_POST["buynumber"])==false){
        $buysirialno = $_POST["buysirialno"];
        $buynumber = $_POST["buynumber"];
        if($buysirialno>=0 && $buynumber>=0){
            $sql = 'SELECT * FROM fooddata where sirialno=:sirialno AND email=:email';
            $stmt = $pdo->prepare($sql);
            $stmt -> bindParam(':sirialno', $buysirialno, PDO::PARAM_STR);
            $stmt -> bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->execute();
            $results = $stmt->fetchAll();
            if(empty($results)){
                ?>
                    <script>
                        alert('該当の商品がありません。');
                    </script>
                <?php
            }else{
                foreach ($results as $row){
                    if($buynumber<=$row['number']){
                        $buyname = $row['name'];
                        $buyprice = $row['price']*$buynumber;
                        $buydate = $row['date'];
                        $sql = "CREATE TABLE IF NOT EXISTS shoppingcart"
                        ." ("
                        . "id INT AUTO_INCREMENT PRIMARY KEY,"
                        . "buysirialno INT(5),"
                        . "name char(32),"
                        . "buynumber INT(3),"
                        . "price INT(5),"
                        . "email VARCHAR( 35 ) NOT NULL,"
                        . "date datetime"
                        .");";
                        $stmt = $pdo->query($sql);
                        $sql = $pdo -> prepare("INSERT INTO shoppingcart (buysirialno, name, buynumber, price, email, date) VALUES (:buysirialno, :name, :buynumber, :price, :email, :date)");
                        $sql -> bindParam(':buysirialno', $buysirialno, PDO::PARAM_STR);
                        $sql -> bindParam(':name', $buyname, PDO::PARAM_STR);
                        $sql -> bindParam(':buynumber', $buynumber, PDO::PARAM_STR);
                        $sql -> bindParam(':price', $buyprice, PDO::PARAM_STR);
                        $sql -> bindParam(':email', $usermail, PDO::PARAM_STR);
                        $sql -> bindParam(':date', $buydate, PDO::PARAM_STR);
                        $sql -> execute();
                    }else{
                        ?>
                        <script>
                            alert('在庫の量を上回る数の購入はできません。');
                        </script>
                        <?php
                    }
                }    
            }
        }else{
            ?>
                    <script>
                        alert('商品番号と数量は０以上の数値を入力して下さい。');
                    </script>
            <?php
        }
        
    }
    //カート情報の表示    
        $sql = 'SELECT * FROM shoppingcart where email=:email ORDER BY buysirialno ASC';
	    $stmt=$pdo->prepare($sql);
        $stmt->bindParam(':email',$usermail,PDO::PARAM_STR);
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
            $buynumber= $row['buynumber'];
            if($result<=0){
                $delnum = $row['buysirialno'];
                $sql = 'delete from shoppingcart where email=:email buysirialno=:buysirialno';
	            $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':buysirialno', $delnum, PDO::PARAM_STR);
                $stmt->bindParam(':email',$usermail,PDO::PARAM_STR);
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
                        <?php echo $row['buysirialno'];?></td>
                    <td style="background-color:#ffffff; border-style:none; text-align: center;" width="150">
                        <?php echo $row['name'];?></td>
                    <td style="background-color:#ffffff; border-style:none; text-align: center;" width="150">
                        <?php echo $row['buynumber'];?></td>
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
            <form method="post" action="buyconfirm.php">
                <input type="hidden" name="email" value="<?php echo "$email"; ?>"> 
                <a href="buyconfirm.php" style="text-decoration: none;">  
                    <input type="submit" value="購入確認ページに進む" class="btn-flat-simple">
                </a>
            </form>
            <br>
  <div class="centerhome">
    <a href="./LogIn/logout.php" class="btn-border">ログアウト</a>
    <a href="./search.php" class="btn-border">地域検索ページへ</a>
  </div>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
        <script type="text/javascript" src="../Company/javascript/picture.js"></script>
        <script type="text/javascript" src="./javascript/usercart.js"></script>
</body>
</html>