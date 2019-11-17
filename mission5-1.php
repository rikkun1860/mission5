<?php
    $dsn = 'データベース名';
	$user = 'ユーザー名';
	$password = 'パスワード';
	$pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
	$sql = "CREATE TABLE IF NOT EXISTS mission5"
	." ("
	. "id INT AUTO_INCREMENT PRIMARY KEY,"
	. "name char(32),"
	. "comment TEXT,"
	. "hoge char(32),"
	. "pass char(32)"
	.");";
	$stmt = $pdo->query($sql);
	$error_message = "";
	$newnum = "";
	$newcomment = "";
	$newname = "";
	//編集
	if(isset($_POST['edit'])){
		$sql = 'SELECT * FROM mission5';
		$stmt = $pdo->query($sql);
		$results = $stmt->fetchAll();
		foreach($results as $row){
			if($row['id'] == intval($_POST['number2'])){
				if($row['pass'] == $_POST['pass3']){
					$newnum = $row['id'];
					$newname = $row['name'];
					$newcomment = $row['comment'];
				}
				elseif($row['pass'] != $_POST['pass2']){
					$error_message = "パスワードが違うので編集できません";
				}
			}
		}
	}

?>

<!DOCTYPE html>
<html>
<head>
	<title>mission5-1</title>
	<meta charset = "utf-8">
</head>
<body>
	<form method = "POST" action = "mission5-1.php">
	<input type = "text" name = "name" placeholder = "名前" value = "<?php if(isset($_POST['edit'])){echo $newname;}?>"/><br>
	<input type = "text" name = "comment" placeholder = "コメント" value = "<?php if(isset($_POST['edit'])){echo $newcomment;}?>"/><br>
	<input type = "password" name = "pass1" placeholder = "パスワード"/><br>
	<input type = "hidden" name = "enumber" value = "<?php echo $newnum;?>">
	<input type = "submit" name = 'send' value = "送信"/><br><br>

	<input type = "text" name = "number1" placeholder = "削除対象番号"/><br>
	<input type = "password" name = "pass2" placeholder = "パスワード"/><br>
	<input type = "submit" name = "delete" value = "削除"/><br><br>

    <input type = "text" name = "number2" placeholder = "編集対象番号"/><br>
	<input type = "password" name = "pass3" placeholder = "パスワード"/><br>
    <input type = "submit" name = "edit" value = "編集"/><br>
	</form>
</body>
</html>

<?php
		if(isset($_POST['send'])){//送信
            if($_POST['enumber'] == ""){
				$sql = $pdo->prepare("INSERT INTO mission5 (name, comment, hoge, pass) VALUES(:name, :comment, :hoge, :pass)");
				$sql -> bindParam(':name', $name, PDO::PARAM_STR);
				$sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
				$sql -> bindParam(':hoge', $hoge, PDO::PARAM_STR);
				$sql -> bindParam(':pass', $pass, PDO::PARAM_STR);
				$name = $_POST['name'];
				$comment = $_POST['comment'];
				$hoge = date('Y/ m/ d H: i: s');
				$pass = $_POST['pass1'];
				$sql -> execute();
			}elseif(isset($_POST['enumber'])){//編集実行
				$editid = intval($_POST['enumber']);
				$ename = $_POST['name'];
				$ecomment = $_POST['comment'];
				$ehoge = date('Y/ m/ d H: i: s');
				$epass = $_POST['pass1'];
				$sql = 'update mission5 set name=:name,comment=:comment,hoge=:hoge,pass=:pass where id=:id';
				$estmt = $pdo->prepare($sql);
				$estmt->bindParam(':name', $ename, PDO::PARAM_STR);
				$estmt->bindParam(':comment', $ecomment, PDO::PARAM_STR);
				$estmt->bindParam(':hoge', $ehoge, PDO::PARAM_STR);
				$estmt->bindParam(':pass', $epass, PDO::PARAM_STR);
				$estmt->bindParam(':id', $editid, PDO::PARAM_INT);
				$estmt->execute();
            }
		}elseif(isset($_POST['delete'])){//削除
			$deleteid = intval($_POST['number1']);
			$sql = 'SELECT * FROM mission5';
			$stmt = $pdo->query($sql);
			$results = $stmt->fetchAll();
			foreach($results as $row){
				if($row['id'] == $deleteid){
					if($row['pass'] == $_POST['pass2']){
						$sql = 'delete from mission5 where id=:id';
						$dstmt = $pdo->prepare($sql);
						$dstmt ->bindParam(':id', $deleteid, PDO::PARAM_INT);
						$dstmt ->execute();
					}
					elseif($row['pass'] != $_POST['pass2']){
						$error_message = "パスワードが違うので削除できません";
					}
				}
			}
		}
		else{
			echo "";
		}
		echo $error_message."<br><br>";
		$sql = 'SELECT * FROM mission5';//表示
		$stmt = $pdo->query($sql);
		$results = $stmt->fetchAll();
		foreach($results as $row){
			echo $row['id'].' ';
			echo $row['name'].' ';
			echo $row['comment'].' ';
			echo $row['hoge'].' ';
			echo '<br>';
		}
		echo "<hr>";
	?>