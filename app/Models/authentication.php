<!DOCTYPE html>
<html lang="ja">
<head>
	<script>
		window.location.href = 'signUp.php'; 
	</script>
	<meta charset="utf-8">
	<title>新規登録</title>
	<script src="public/js/jquery-2.1.3.js"></script>
	<script src="public/js/jquery.pjax.js"></script>
	<script src="public/js/barba.js"></script>
    <link rel="icon" href="public/favicon.ico">
	<link rel="stylesheet" href="/css/fonts.css">
	<link rel="stylesheet" href="/css/opening-common.css">
	<link rel="stylesheet" href="/css/wave.css">
</head>
<body>
	<div id="barba-wrapper">
		<div class='wave -one'></div>
		<div class='wave -two'></div>
		<div class='wave -three'></div>
		<div class="barba-container">
			<link rel="stylesheet" href="/css/authentication.css">
			<div class="title-wrapper">
				<span class="form-title">認証コードを送信しました</span>
				<span class="form-comment">メールアドレスを認証するため、以下にコードを入力してください</span>
				<span class="form-mailaddress">tatsuki1@live.jp</span>
			</div>	
			<div>
				<div class="authentication_box box">
					<div class="authentication_inner inner">
						<input id="text3" class="text" type="text">
						<div class="authentication_string string">認証キー</div>
					</div>
					<i class="fas fa-eye-slash"></i>
				</div>
			</div>
			<div class="control-button">
				<a href="password.php"><button type="button">次へ</button></a>
			</div>
			<script>
				window.onload = function () {
					$('#text3').val("");
				};
				$('#text3').focus(function(){
					$('.authentication_box').animate({borderTopColor: '#3be5ae', borderLeftColor: '#3be5ae', borderRightColor: '#3be5ae', borderBottomColor: '#3be5ae'}, 200);
				}).blur(function(){
					$('.authentication_box').animate({borderTopColor: '#d3d3d3', borderLeftColor: '#d3d3d3', borderRightColor: '#d3d3d3', borderBottomColor: '#d3d3d3'}, 200);
				});
				
				$('#text3').change(function() {
					const str = $('#text3').val();
					if(str===""){
						const result = $('.authentication_string').removeClass('keepfocus');
					}else{ 
						const result = $('.authentication_string').addClass('keepfocus')
					}
				});
			</script>
		</div>
	</div>
</body>

</html>
