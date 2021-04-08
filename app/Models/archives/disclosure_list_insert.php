<?php
    require 'bdd.php';
    session_start();
    //セッション開始
    // ログイン状態チェック
    if (!isset($_SESSION["user_id"])) {
        header("Location: index.php");
        exit;
    }

    if( isset($_POST["list_name"], $_POST["is_publish"] )==true ){
        
        if(idCheck()==true && mailCheck()==true && passwordCheck()==true){
            //id_generator($_POST["id"]);
             insert();
        }
    }
    function insert(){
        require 'bdd.php';
        $list_name = $_POST["list_name"];
        $list_icon = $_POST["list_icon"];
        $is_publish = $_POST["is_publish"];
        $list_users_id_array = $_POST["list_user_id_array"];
		$list_id ="";

        // エラー処理
        try {
			$stmt = $bdd->prepare("INSERT INTO disclosure_lists (name, owner_user_id, is_published, is_hidden) VALUES (?, ?, ?, ?)");
			$stmt->execute(array($list_name, $_SESSION["user_id"], $is_publish)); 
			$stmt = $bdd->prepare("SELECT id FROM users WHERE created_at =(select max(created_at) from users)");
			$stmt->execute();  
			foreach($stmt as $row){ //データの表示
				$list_id=$row['id'];
			}
			for($i=0;$i < count($list_users_id_array.length);$i++){
				if(is_have($list_users_id_array.length[i]) = false){
					$stmt = $bdd->prepare("INSERT INTO disclosure_lists_users (list_id, id) VALUES (?, ?)");
					$stmt->execute(array($list_id, $list_users_id_array.length[i] ));  	
				}
			} 
        } catch (PDOException $e) {
            echo 'データベースエラー';
        }
    }
    function is_have($user_id){
        require 'bdd.php';
        $stmt = $bdd->prepare('SELECT * FROM users WHERE id = ?');
        $stmt->execute(array($user_id));

        $cntent =$stmt->rowCount();

        if($cntent != '0'){
            return true;
        }else{
            return false;
        }
    }
