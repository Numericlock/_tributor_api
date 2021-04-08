<?php
require_once('bdd.php');
// セッション開始
//session_start();

	//usernameデータ呼び出し
	if(isset($_GET['username'])){
			$username = $_GET["nickname"];
			$stmt = $bdd->prepare('SELECT * FROM users WHERE username = ?');
            $stmt->execute(array($username));
			
			$cntent =$stmt->rowCount();
			echo "$cntent";
			
	}
	
	//user_idデータ呼び出し
	if(isset($_GET['user_id'])){
			$user_id = $_GET["user_id"];
			$stmt = $bdd->prepare('SELECT * FROM users WHERE id = ?');
            $stmt->execute(array($user_id));
			
			$cntent =$stmt->rowCount();
			echo "$cntent";
				
	}
	
	//mailaddressデータ呼び出し
	if(isset($_GET['mailaddress'])){
			$mailaddress = $_GET["mailaddress"];
			$stmt = $bdd->prepare('SELECT * FROM users WHERE e_mail = ?');
            $stmt->execute(array($mailaddress));
			
			$cntent =$stmt->rowCount();
			echo "$cntent";
			
	}
