<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel ="stylesheet" href="./design/design.css">
    <title>Confirm</title>
</head>
<body>
<?php
    ini_set('display_errors', 0);
    session_start();
    require_once("../dbconnect.php");
    $pdo = dbconnect();
    if(!empty($_SESSION["login_user"])) {
      $user = $_SESSION["login_user"];
      $usermail = current(array_slice($user, 3, 1));
    }
    if(empty($_POST["email"])==false){
        $email = $_POST["email"];
    }
?>    
    <h1>ご注文商品</h1>
    <h2>これでお間違いないですか？</h2>
    <div class="centerhome">
        <div class="child1">
            <form method="post" action="bought.php">
                <input type="hidden" name="email" value="<?php echo "$email"; ?>"> 
                <a href="bought.php" style="text-decoration: none;">  
                    <input type="submit" value="はい" class="btn-flat-simple">
                </a>
            </form>
        </div>
        <div class="child2">
            <form method="post" action="mypage.php">
                <input type="hidden" name="email" value="<?php echo "$email"; ?>"> 
                <a href="mypage.php" style="text-decoration: none;">  
                    <input type="submit" value="いいえ" class="btn-flat-simple">
                </a>
            </form>
        </div>
    </div>
    <br>           
<?php
        $sql = 'SELECT * FROM shoppingcart where email=:email ORDER BY buysirialno ASC';
	    $stmt=$pdo->prepare($sql);
        $stmt->bindParam(':email',$usermail,PDO::PARAM_STR);
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        date_default_timezone_set("Asia/Tokyo");
        if(empty($results)){
            ?>
                <script>
                    alert('カートに商品が入っておりません。');
                    location.href = 'mypage.php';
                </script>
            <?php
        }else{
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
                    $sql = 'delete from shoppingcart where buysirialno=:buysirialno';
	                $stmt = $pdo->prepare($sql);
	                $stmt->bindParam(':buysirialno', $delnum, PDO::PARAM_STR);
                    $stmt->execute();
                }else{
?>
    <div class="productinfo">
        <table border="1" align="center">
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
        } 
?>   
</body>
</html>