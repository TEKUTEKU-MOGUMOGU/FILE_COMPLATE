<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./design/resistdesign.css">
    <title>FOOD</title>
</head>
<body>
    <h1>商品情報登録フォーム</h1>
    <form method ="post" action="" name="form0">
        <table border="1">
            <tbody>
                <tr>
                    <td style="background-color:rgb(223,97,72);" width="150" align="center">
                        <font color="#ffffff"><strong>商品番号</strong></td>
                    <td style="background-color:rgb(248,248,248);" width="170">
                        <input type="number" name="sirialno"></td>
                </tr>
                <tr>
                    <td style="background-color:rgb(223,97,72);" width="150" align="center">
                        <font color="#ffffff"><strong>商品名</strong></td>
                    <td style="background-color:rgb(248,248,248);" width="170">
                        <input type="text" name="goods"></td>
                </tr>
                <tr>
                    <td style="background-color:rgb(223,97,72);" width="150" align="center">
                        <font color="#ffffff"><strong>数量</strong></td>
                    <td style="background-color:rgb(248,248,248);" width="170">
                        <input type="number" name="number"></td>
                </tr>
                <tr>
                    <td style="background-color:rgb(223,97,72);" width="150" align="center">
                        <font color="#ffffff"><strong>料金</strong></td>
                    <td style="background-color:rgb(248,248,248);" width="170">
                        <input type="number" name="price"></td>
                </tr>
                <tr>
                    <td style="background-color:rgb(223,97,72);" width="150" align="center">
                        <font color="#ffffff"><strong>期限</strong></td>
                    <td style="background-color:rgb(248,248,248);" width="150">
                    <input type="datetime-local" name="date"></td>
                </tr>
            </tbody>
        </table>
        <input type="submit" name="submit" value="登録" onClick="return check()" class="btn-flat-simple">
    </form>
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
    $sql = "CREATE TABLE IF NOT EXISTS fooddata"
	    ." ("
        . "id INT AUTO_INCREMENT PRIMARY KEY,"
        . "sirialno INT(5),"
	    . "name char(32),"
        . "number INT(3),"
        . "price INT(5),"
        . "email VARCHAR( 35 ) NOT NULL,"
	    . "date datetime"
	    .");";
	$stmt = $pdo->query($sql);
    $sql = $pdo -> prepare("INSERT INTO fooddata (sirialno, name, number, price, email, date) VALUES (:sirialno, :name, :number, :price, :email, :date)");
    $sql -> bindParam(':sirialno', $sirialno, PDO::PARAM_STR);
    $sql -> bindParam(':name', $name, PDO::PARAM_STR);
    $sql -> bindParam(':number', $number, PDO::PARAM_STR);
    $sql -> bindParam(':price', $price, PDO::PARAM_STR);
    $sql -> bindParam(':email', $companymail, PDO::PARAM_STR);
    $sql -> bindParam(':date', $date, PDO::PARAM_STR);
        if(empty($_POST["sirialno"])==false && empty($_POST["goods"])==false && empty($_POST["number"])==false && empty($_POST["price"])==false && empty($_POST["date"])==false){
            $sirialno = $_POST["sirialno"];
            $name = $_POST["goods"];
            $number = $_POST["number"];
            $price = $_POST["price"];
            $time = strtotime($_POST["date"]);
            $time2 = strtotime('now');
            $result = ($time-$time2)/(60*60*24);
            if($result>0 && $number>0 && $sirialno>0 && $price>0){
                $date = $_POST["date"];
                $sql -> execute();
?>
                <script>
                    alert('登録完了');
                </script>
<?php      
            }else if($result<=0){
                ?>
                <script>
                    alert('現在時刻より先の時刻を入力して下さい。');
                </script>
                <?php
            }else if($sirialno<=0){
                ?>
                <script>
                    alert('商品番号は正の整数を入力して下さい。');
                </script>
                <?php
            }else if($price<=0){
                ?>
                <script>
                    alert('価格設定は正の整数を入力して下さい。');
                </script>
                <?php
            }else{
                ?>
                <script>
                    alert('数量は正の整数を入力して下さい。');
                </script>
                <?php
            }    
        }
?>
    <form method ="post" action="" name="form1">
        <table border="1">
            <tbody>
                <tr>
                    <td style="background-color:rgb(223,97,72); font-size:15px;" width="150" align="center">
                        <font color="#ffffff"><strong>削除したい商品番号</strong></td>
                    <td style="background-color:rgb(248,248,248);" width="150">
                        <input type="number" name="delnum"></td>
                </tr>
            </tbody>
        </table>
        <input type="submit" name="submit" value="削除" class="btn-flat-simple">
    </from>
<?php
    if(empty($_POST["delnum"])==false){
        $delnum = $_POST["delnum"];
        $sql = 'SELECT * FROM fooddata';
        $stmt = $pdo->query($sql);
        $stmt->execute();
        $count1=$stmt->rowCount();
        $sql = 'delete from fooddata where sirialno=:sirialno';
	    $stmt = $pdo->prepare($sql);
	    $stmt->bindParam(':sirialno', $delnum, PDO::PARAM_STR);
        $stmt->execute();
        $sql = 'SELECT * FROM fooddata';
        $stmt = $pdo->query($sql);
        $stmt->execute();
        $count2=$stmt->rowCount();
        if($count1>$count2){
            ?>
                <script>
                    alert('削除しました！');
                </script>
            <?php
        }else{
            ?>
                <script>
                    alert('入力された商品番号が見つかりません。');
                </script>
            <?php
        }
    }   
?>
<form method ="post" action="" name="form2">
        <table border="1">
            <tbody>
                <tr>
                    <td style="background-color:rgb(223,97,72); font-size:15px;" width="150" align="center">
                        <font color="#ffffff"><strong>変更したい商品番号</strong></td>
                    <td style="background-color:rgb(248,248,248);" width="150">
                    <input type="number" name="edit"></td>
                </tr>
                <tr>
                    <td style="background-color:rgb(223,97,72);" width="150" align="center">
                        <font color="#ffffff"><strong>変更後の数量</strong></td>
                    <td style="background-color:rgb(248,248,248);" width="150">
                    <input type="number" name="editnum"></td>
                </tr>
                <tr>
                    <td style="background-color:rgb(223,97,72);" width="150" align="center">
                        <font color="#ffffff"><strong>変更後の価格</strong></td>
                    <td style="background-color:rgb(248,248,248);" width="150">
                    <input type="number" name="editprice"></td>
                </tr>
            </tbody>
        </table>
        <input type="submit" name="submit" value="編集"  onClick="return checkedit()" class="btn-flat-simple">
</form>
<?php    
    if(empty($_POST["edit"])==false && empty($_POST["editnum"])==false && empty($_POST["editprice"])==false){
            $id = $_POST["edit"]; //変更する投稿番号
            $edit = $_POST["editnum"];
            $editprice = $_POST["editprice"];
            $sql = 'SELECT * FROM eeee';
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            $results = $stmt->fetchAll();
            $num1 = 0;
            $price1 = 0;
            //編集前の数量の合計
            foreach ($results as $row){
                $num1 = $num1 + $row['number'];
                $price1 = $price1 + $row['price'];   
            }
            //編集開始
            $sql = 'UPDATE fooddata SET number=:number,price=:price WHERE sirialno=:sirialno';
	        $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':number', $edit, PDO::PARAM_STR);
            $stmt->bindParam(':price', $editprice, PDO::PARAM_STR);
	        $stmt->bindParam(':sirialno', $id, PDO::PARAM_STR);
            $stmt->execute();
            //編集終了
            $sql = 'SELECT * FROM fooddata';
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            $results = $stmt->fetchAll();
            $num2 = 0;
            $price2 =0;
            //編集後の数量の合計
            foreach ($results as $row){
                $num2 = $num2 + $row['number']; 
                $price2 = $price2 + $row['price'];  
            }
            if($num1==$num2 && $price1==$price2){
                ?>
                <script>
                    alert('入力された商品番号がないかデータに変更はありませんでした。');
                </script>
            <?php
            }else if($price1==$price2 && $num1!==$num2){
                ?>
                <script>
                    alert('数量のみを変更しました！');
                </script>
            <?php
            }else if($price1!==$price2 && $num1==$num2){
                ?>
                <script>
                    alert('料金のみを変更しました！');
                </script>
            <?php
            }else{
                ?>
                <script>
                    alert('変更完了！');
                </script>
            <?php
            }
    }    
        
?>    
    <div class="centerrink">
        <a href="./mypage.php" class="btn-border">商品一覧へ戻る</a>
    </div>
    <script type="text/javascript" src="./javascript/kigyouresist.js"></script>
</body>
</html>