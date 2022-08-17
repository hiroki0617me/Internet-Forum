<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<title><mission_5-1></title>
</head>
<body>
<?php
    //データベース構築
    $dsn = 'ʼデータベース名';
    $user = 'ユーザー名';
    $password = 'パスワード';
    $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));

    //テーブル創造
    $sql = "CREATE TABLE IF NOT EXISTS newtab"
        ." ("
        . "id INT AUTO_INCREMENT PRIMARY KEY,"
        . "name char(32),"
        . "comment TEXT,"
        . "date TEXT,"
        . "password TEXT"
        .");";
    $stmt = $pdo-> query($sql);

    //通常投稿・データ入力
    if(!empty($_POST["name"]) && !empty($_POST["comment"]) && empty($_POST['edtxt']) && !empty($_POST["password"])){
        $sql = $pdo -> prepare("INSERT INTO newtab (name, comment, date, password) VALUES (:name, :comment, :date, :password)");
        $sql -> bindParam(':name', $name, PDO::PARAM_STR);
        $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
        $sql -> bindParam(':date', $date, PDO::PARAM_STR);
        $sql -> bindParam(':password', $password, PDO::PARAM_STR);
        $name = $_POST["name"];
        $comment = $_POST["comment"]; 
        $date = date("Y/n/j G:i:s");
        $password = $_POST["password"];
        $sql -> execute();
        }

    //削除開始
        if(!empty($_POST["deleteNo"])&& !empty($_POST["delepassword"])){
            $delete = $_POST["deleteNo"];
            $id = $delete;
            $delepassword = $_POST["delepassword"];
            $password = $delepassword;
            $sql = 'delete from newtab where id=:id AND password=:password';
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':password', $password, PDO::PARAM_STR);
            $stmt->execute();
        }

    //編集開始
    if(isset($_POST['edit'])){
        if(!empty($_POST['editNo']) && !empty($_POST["edipassword"])){
            $id = $_POST['editNo'];
            $edipassword = $_POST["edipassword"];
            $sql = 'SELECT * FROM newtab where id=:id AND password=:edipassword';
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':edipassword', $edipassword, PDO::PARAM_STR);
            $stmt->execute();
            $results = $stmt->fetchAll();
            foreach ($results as $row){
                //$rowの中にはテーブルのカラム名が入る
                $EN1 = $row['id'];
                $EN2 = $row['name'];
                $EN3 = $row['comment'];
                $EN4 = $row['password'];
            }
        }
    }

    if(isset($_POST["submit"])){
        if(!empty($_POST["name"]) && !empty($_POST["comment"]) && !empty ($_POST['edtxt']) && !empty($_POST["password"])){
            $name = $_POST["name"];
            $comment = $_POST["comment"];
            $edtext = $_POST['edtxt'];
            $password = $_POST["password"];
            $id = $edtext;
            $sql = 'UPDATE newtab SET name=:name,comment=:comment WHERE id=:id AND password=:password';
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':name', $name, PDO::PARAM_STR);
            $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt-> bindParam(':password', $password, PDO::PARAM_STR);
            $stmt->execute();
        }
    }

    $sql = 'SELECT * FROM newtab';
    $stmt = $pdo->query($sql);
    $results = $stmt->fetchAll();
    foreach ($results as $row){
        //$rowの中にはテーブルのカラム名が入る
        echo $row['id'].',';
        echo $row['name'].',';
        echo $row['comment'].',';
        echo $row['date'].'<br>';
        echo "<hr>";//hrは下線
    }

?>
<form action="" method="post">
<body background="background002.gif">
    <p><center><b><font color"=#bc42f4# size=+4">INTERNET FORUM / 掲示板</font></b></center></p><br><br>
    <p><center><input type="text" name="name"placeholder="NAME / 名前" value="<?php echo $EN2 ?? "";?>"></p>
    <input type="text" name="comment"placeholder="COMMENT / コメント" value="<?php echo $EN3 ?? "";?>">
    <input type="hidden" name="edtxt" placeholder="" value="<?php echo $EN1 ?? "";?>">
    <input type="text" name="password" placeholder="PASSWORD / パスワード" value = "<?php echo $EN4 ?? "";?>">
    <input type="submit" name="submit" value="   POST   / 投稿"><br><br>
    <input type="number" name = "deleteNo" placeholder="DELETE # / 削除対象番号">
    <input type="text" name="delepassword" placeholder="PASSWORD / パスワード">
    <input type="submit" name="delete" value = "DELETE / 削除"><br><br>
    <input type="number" name = "editNo" placeholder="EDIT # / 編集対象番号">
    <input type="text" name=" edipassword" placeholder="PASSWORD / パスワード">
    <input type="submit" name="edit" value = "   EDIT   / 編集">
</form>
</body>
</html>