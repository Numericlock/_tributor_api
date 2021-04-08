<?php
	require_once('bdd.php');
	// セッション開始
	//session_start();

	//user_id or user_name データ呼び出し
	if(isset($_GET['list-id'])){
		$id=$_GET['list-id'];
		$stmt = $bdd->prepare('SELECT * FROM disclosure_lists_users WHERE id = ?');
		$stmt->execute(array($id));
		foreach($stmt as $row){
			print '<div class="list-modal-addUsers-searchArea-result-user">
						<div class="list-modal-addUsers-searchArea-result-user-icon">
							<img src="public/img/2.jpg">
						</div>
						<div class="list-modal-addUsers-searchArea-result-user-name">
							<span>'.$row['nickname'].'</span>
						</div>
						<div class="list-modal-addUsers-searchArea-result-user-checkbox">
							<div class="checkbox">
								<div>
									<input type="checkbox" class="list-modal-addUsers-searchArea-result-user-checkbox-input" id="'.$row['id'].'" name="'.$row['id'].'" value="'.$row['nickname'].'" />
									<label class="checkbox-label" for="'.$row['id'].'">
										<span class="checkbox-span"><!-- This span is needed to create the "checkbox" element --></span>
									</label>
								</div>
							</div>
						</div>
					</div> ';
		}
		print '<script src="public/js/list_users_checkbox.js"></script>';
	}else{
		
	}
