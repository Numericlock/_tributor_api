<?php
    require 'bdd.php';
    require 'password_hash.php';

    if( isset( $_POST["id"] , $_POST["email"] , $_POST["password"] )==true ){
        
        if(idCheck()==true && mailCheck()==true && passwordCheck()==true){
            //id_generator($_POST["id"]);
             insert();
        }
    }
    function id_generator($str){   //一意のIDを生成します。
        $str_cal=$str;  //文字数カウント用
        if(mb_strlen(preg_replace("/^[a-zA-Z0-9]+$/", "", $str_cal ), "UTF-8" )==0){    //文字列が半角英数字のみの場合
            $str_length = mb_strlen($str, "UTF-8" );
            if($str_length<=16){
                while(true){
                    require 'bdd.php';
                    $id = $str."_".random(25-$str_length);
                    $stmt = $bdd->prepare('SELECT * FROM users WHERE id = ?');
                    $stmt->execute(array($id));

                    $cntent =$stmt->rowCount();
                    echo $id;
                    echo 26-$str_length."文字";
                   // echo +random(26-$str_length);
                    if($cntent != '0'){
                        continue;
                    }else{
                        insert($id);
                        break;
                    }
                }
            }
        }else{
            while(true){
                require 'bdd.php';
                $id = random(26);
                $stmt = $bdd->prepare('SELECT * FROM users WHERE id = ?');
                $stmt->execute(array($id));

                $cntent =$stmt->rowCount();
                echo $id;
               // echo +random(26-$str_length);
                if($cntent != '0'){
                    continue;
                }else{
                    insert($id);
                    break;
                }
            }
        }
    }
    function random($length){
        return substr(str_shuffle('1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, $length);
    }
	function is_blank($str){
        echo "blank";
		// チェックのために、タブ(\t)、スペース(\s)、全角スペース（ ）を削除
		$check_str  = preg_replace("/( |　)/", "", $str );
       // echo $check_str;
        if($str === ""){
			return true;
			// 名前未入力
		}else if(mb_strlen( $check_str, "UTF-8" )== 0){
			// チェックの文字が長さ0なので、スペース系のみだったと判断。
			return true;
		}
	}
   function idCheck(){
       echo "idcheck";
        $str=$_POST["id"];
       // $str="hishida";
		if(is_blank($str) == true || mb_strlen( $str, "UTF-8" )>24){
            return false;
		}else if(!preg_match('/^[a-zA-Z0-9]+$/', $str)){
            return false;
        }else{
            require 'bdd.php';
            $id = $str;
			$stmt = $bdd->prepare('SELECT * FROM users WHERE id = ?');
            $stmt->execute(array($id));
			
			$cntent =$stmt->rowCount();
            
            if($cntent != '0'){
                return false;
            }else{
                return true;
            }
		}
    }
    function mailCheck(){
        echo "mailCheck";
       $str=$_POST["email"];
        //$str="tatsuki1@live.jp";
		if(is_blank($str) == true){
            return false;
		}else if(!preg_match('/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/iD', $str)){
            return false;
        }else{
            require 'bdd.php';
            $mailaddress = $str;
			$stmt = $bdd->prepare('SELECT * FROM users WHERE e_mail = ?');
            $stmt->execute(array($mailaddress));
			
			$cntent =$stmt->rowCount();
            
            if($cntent != '0'){
                return false;
            }else{
                return true;
            }
        }
    }
    function passwordCheck(){
        echo "passwordCheck";
        $str=$_POST["password"];
       // $str="hishida1";
		if(is_blank($str) == true){
            return false;
		}else if(mb_strlen( $str, "UTF-8" ) <= 5 ||mb_strlen( $str, "UTF-8" ) >= 255){
            return false;
        }else if(!preg_match('/^[a-zA-Z0-9]+$/', $str)){
            return false;
        }else{
            return true;
		}
    }
    function insert(){
        require 'bdd.php';
        // 入力したユーザIDとパスワードの格納
        //$user_id = $str;
        $id = $_POST["id"];
        $password = $_POST["password"];
        $mailaddress = $_POST["email"];
       // $user_id = "hishida";
     //   $password = "hishida1";
     //   $mailaddress = "tatsuki1@live.jp";

        // エラー処理
        try {
            $id_result = "SELECT * FROM users WHERE id ='$id'";
                $id_stmt = $bdd->prepare($id_result);
                $id_stmt->execute();
                $id_count=$id_stmt->rowCount();
            $mail_result = "SELECT * FROM users WHERE e_mail ='$mailaddress'";
                $mail_stmt = $bdd->prepare($mail_result);
                $mail_stmt->execute();
                $mail_count=$mail_stmt->rowCount();
            if($id_count>=1 && $mail_count>1){
               echo 'IDもしくはメールアドレスが重複しています。';
            }else{
                //データベースに挿入
                $stmt = $bdd->prepare("INSERT INTO users (id, password, e_mail) VALUES (?, ?, ?)");
                $stmt->execute(array($id, $password,$mailaddress));  // パスワードのハッシュ化
             //   header('Location:login.php');  // ログイン画面へ遷移
                exit();  // 処理終了
            }
        } catch (PDOException $e) {
            echo 'データベースエラー';
        }
    }
    
?>