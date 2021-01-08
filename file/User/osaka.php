<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel ="stylesheet" href="./design/storeinfo.css">
    <title>FOOD</title>
</head>
<body onload="document.form3.submit();">
    <h6>Rescue store</h6>        
    <h1>レスキュー店舗一覧</h1> 
<?php
    ini_set('display_errors', 0);
    session_start();
    require_once("../dbconnect.php");
    $pdo = dbconnect();
    if(!empty($_SESSION["login_user"])) {
        $user = $_SESSION["login_user"];
        $usermail = current(array_slice($user, 3, 1));
    }
    $sql = 'SELECT * FROM storedata WHERE adress like "%大阪%"';
    $stmt = $pdo->query($sql);
    $results = $stmt->fetchAll();
    foreach ($results as $row){
        $stnum = substr($row['phone'],0,3);
        $midnum = substr($row['phone'],3,4);
        $finnum = substr($row['phone'],7,4);
        $email = $row["email"];
        $sql = 'SELECT * FROM storeimage WHERE email=:email';
        $stmt=$pdo->prepare($sql);
        $stmt->bindParam(':email',$email,PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($result as $value){
            $img_name = $value['img'];
?>               
        <div class="storeinfo" id="storeinfo">
            <div class="child1">
                <?php echo "<img src=\"../Company/$img_name\">";?>
            </div>
            <div class="child2">
                <table border="1">
                    <tbody>
                        <tr>
                            <td style="background-color:rgb(223,97,72);" width="150" align="center">
                                <font color="#ffffff"><strong>店舗名</strong></td>
                            <td style="background-color:rgb(248,248,248);" width="250" height="70"align="center">
                                <?php echo $row['name'];?></td>        
                        </tr>
                        <tr>
                            <td style="background-color:rgb(223,97,72);" width="150" align="center">
                                <font color="#ffffff"><strong>住所</strong></td>
                            <td style="background-color:rgb(248,248,248);" width="250" align="center">
                                <?php echo $row['adress'];?></td>        
                        </tr>
                        <tr>
                            <td style="background-color:rgb(223,97,72);" width="150" align="center">
                                <font color="#ffffff"><strong>最寄り駅</strong></td>
                            <td style="background-color:rgb(248,248,248);" width="250" align="center">
                                <?php echo $row['station'];?></td>        
                        </tr>
                        <tr>
                            <td style="background-color:rgb(223,97,72);" width="150" align="center">
                                <font color="#ffffff"><strong>営業時間</strong></td>
                            <td style="background-color:rgb(248,248,248);" width="250" align="center">
                                <?php echo $row['time'];?></td>        
                        </tr>
                        <tr>
                            <td style="background-color:rgb(223,97,72);" width="150" align="center">
                                <font color="#ffffff"><strong>電話番号</strong></td>
                            <td style="background-color:rgb(248,248,248);" width="250" align="center">
                                <?php echo $stnum."-".$midnum."-".$finnum; ?></td>        
                        </tr>
                    </tbody>
                </table> 
            </div>  
            <?php
                $email = $row['email'];
            ?>
            <form method="post" action="mypage.php">
                <input type="hidden" name="email" value="<?php echo "$email"; ?>">
                <a href="mypage.php" style="text-decoration: none;">  
                    <input type="submit" value="見てみる" class="toshop">
                </a>
            </form> 
        </div> 
<?php
    //1つ目のforeach閉じる
        }
    //2つ目のforeach閉じる
    }   
?>      
</body>
</html>