<?php
$dsn='データベース名';//接続
$user='ユーザー名';
$password='パスワード';
$pdo=new PDO($dsn,$user,$password,array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_WARNING));
$sql = "CREATE TABLE IF NOT EXISTS mission"//テーブル作成
." ("
. "id INT AUTO_INCREMENT PRIMARY KEY,"
. "name char(32),"
. "comment TEXT,"
. "registry_datetime DATETIME,"
. "password char(30)"
.");";
$stmt = $pdo->query($sql);


if(!empty($_POST["comment"])and($_POST["namae"])and($_POST["passtoukou"])){//名前とコメントの送信
  if(empty($_POST["hensyu"])){
    $hyouji=$_POST["comment"];
    $namae=$_POST["namae"];//スペルミスは厳禁
    $passtoukou=$_POST["passtoukou"];
    $jikan=date("Y-m-d H:i:s");

    $sql = $pdo -> prepare("INSERT INTO mission (name, comment, registry_datetime, password) VALUES (:name, :comment, :registry_datetime, :password)");//書き込み
    $sql -> bindParam(':name', $name, PDO::PARAM_STR);
    $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
    $sql -> bindParam(':registry_datetime', $registry_datetime, PDO::PARAM_STR);
    $sql -> bindParam(':password', $password, PDO::PARAM_STR);
    $name ="$namae";
    $comment ="$hyouji"; //好きな名前、好きな言葉は自分で決めること
    $registry_datetime ="$jikan";
    $password ="$passtoukou";
    $sql -> execute();
  }else{
  $hensyubangou=$_POST["hensyu"];

  $newid = $_POST["hensyu"]; //変更する投稿番号
  $newname = $_POST["namae"];
  $newcomment = $_POST["comment"]; //変更したい名前、変更したいコメントは自分で決めること
  $newregistry_datetime = date("Y-m-d H:i:s");
  $newpassword = $_POST["passtoukou"];
  $sql = 'update mission set name=:name,comment=:comment,registry_datetime=:registry_datetime,password=:password where id=:id';
  $stmt = $pdo->prepare($sql);
  $stmt->bindParam(':name', $newname, PDO::PARAM_STR);
  $stmt->bindParam(':comment', $newcomment, PDO::PARAM_STR);
  $stmt->bindParam(':id', $newid, PDO::PARAM_INT);
  $stmt->bindParam(':password', $newpassword, PDO::PARAM_STR);
  $stmt->bindParam(':registry_datetime', $newregistry_datetime, PDO::PARAM_STR);
  $stmt->execute();
}

}

if(!empty($_POST["delete"])and($_POST["passdelete"])){//削除の送信
  $delete=$_POST["delete"];
  $passdelete=$_POST["passdelete"];

    $id = "$delete";//削除
    $sql = 'delete from mission where id=:id and password=:password';
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->bindParam(':password', $passdelete, PDO::PARAM_INT);
    $stmt->execute();
}


if(!empty($_POST["hensyu"])and !empty($_POST["passhensyu"])){//編集の送信
  $hensyu=$_POST["hensyu"];
  $passhensyu=$_POST["passhensyu"];
  

  $sql = 'SELECT * FROM mission';
  $stmt = $pdo->query($sql);
  $results = $stmt->fetchAll();
  foreach ($results as $row){

          if($row['id']==$hensyu and $row['password']==$passhensyu){
             $hensyu=$row['id'];
             $hensyunamae=$row['name'];
             $hensyucomment=$row['comment'];
          }
  }

}


?>

<html>
<head>
<title>githubyou</title>
</head>
<body>
<br>
【 投稿フォーム 】<br>
<form method="post"action="githubyou.php">
名前：　　　<input type='text' value="<?php if(!empty($hensyunamae)){echo $hensyunamae;} ?>" name='namae'placeholder="名前"><br>
コメント：　<input type='text' value="<?php if(!empty($hensyucomment)){echo $hensyucomment;} ?>" name='comment'placeholder="コメント"><br>
パスワード：<input type='password' name='passtoukou' placeholder="パスワード"><br>
<input type="submit"value="送信"><br>
<input type='hidden' value="<?php if(!empty($hensyu)){echo $hensyu;} ?>" name='hensyu'><br>
</form>
【 削除フォーム 】<br>
<form method="post"action="githubyou.php">
投稿番号：　<input type="text" name="delete"placeholder="削除対象番号"><br>
パスワード：<input type="password" name="passdelete" placeholder="パスワード"><br>
<input type="submit"value="削除"><br>
</form>
<br>
【 編集フォーム 】<br>
<form method="post"action="githubyou.php">
投稿番号：　<input type="text" name="hensyu"placeholder="編集対象番号"><br>
パスワード：<input type="password" name="passhensyu" placeholder="パスワード"><br>
<input type="submit"value="編集"><br>
</form>


<br>
------------------------------------<br>
【 投稿一覧 】<br>

<?php
$sql = 'SELECT * FROM mission';
$stmt = $pdo->query($sql);
$results = $stmt->fetchAll();
foreach ($results as $row){
        //$rowの中にはテーブルのカラム名が入る
        echo $row['id'].' ';
        echo $row['name'].' ';
        echo $row['comment'].' ';
        echo $row['registry_datetime'].'<br>';
echo "<hr>";
}
?>
</body>
</html>