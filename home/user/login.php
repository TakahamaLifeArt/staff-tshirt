<?php
require_once dirname(__FILE__).'/php_libs/funcs.php';

// ログインしている場合は注文履歴へ
$me = checkLogin();
if($me){
	jump('history.php');
}

if(isset($_REQUEST['login']) && empty($_SESSION['me'])){
	
	$args = array($_REQUEST['email']);
	$conndb = new Conndb(_API_U);
	
	// エラーチェック
	if(empty($_REQUEST['email'])) {
		$err = 'メールアドレスを入力して下さい。';
	}else if(!$conndb->checkExistEmail($args)) {
		$err = "このメールアドレスは登録されていません。";
		//$err .= "<br><br>【TLAメンバーズとは】<br>タカハマライフアートでご注文いただいたお客様専用の会員ページです。<br>";
	
	}else if(empty($_REQUEST['pass'])) {
		$err = 'パスワードを入力して下さい。';
	}else{
		$args = array('email'=>$_REQUEST['email'], 'pass'=>$_REQUEST['pass']);
		$me = $conndb->getUser($args);
		if(!$me){
			$err = "メールアドレスかパスワードが認識できません。ご確認下さい。";
			//$err .= "<br><br>【TLAメンバーズとは】<br>タカハマライフアートでご注文いただいたお客様専用の会員ページです。<br>";
		}
	}
	
	if(empty($err)){
		// セッションハイジャック対策
		session_regenerate_id(true);
		
		// ログイン状態を保持
		if($_REQUEST['save']) {
			//setcoocie(session_name(), sesion_id(), time()+60*60*24*7);
		}
		
		$_SESSION['me'] = $me;
		jump('history.php');
	}
}

?>
<!DOCTYPE html>
<html lang="ja">
	<head>
	<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-PLQMXT');</script>
<!-- End Google Tag Manager -->
		<meta charset="utf-8" />
		<meta name="description" content="スタッフTシャツお客様マイページはこちらから。ログインをしてお客様情報をご確認できます！スタッフTシャツ、イベントTシャツの作成は短納期で早いスタッフTシャツ屋タカハマライフアートへ。">
		<meta name="keywords" content="スタッフTシャツ,作成,即日,ログイン,お客様情報">
		<meta name="viewport" content="width=device-width,user-scalable=no,maximum-scale=1" />
		<title>ログイン ｜スタッフTシャツ屋</title>
		<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
		<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css">
		<link rel="stylesheet" type="text/css" href="../css/reset.css" media="all">
		<link rel="stylesheet" type="text/css" href="../css/common - test.css" media="all">
		<link rel="stylesheet" type="text/css" href="../css/flexnav1.css" media="all">
		<link rel="stylesheet" type="text/css" href="../css/media-style.css" media="all">
		<link rel="stylesheet" type="text/css" media="screen" href="./css/style.css" />
		<link rel="stylesheet" type="text/css" media="screen" href="./css/login.css" />
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
		<script type="text/javascript" src="/common2/js/tlalib.js"></script>
		<script type="text/javascript" src="./js/login.js"></script>
		<script src="../js/jquery.flexnav.js"></script>
		<script type="text/javascript">
			_LOGIN_STATE = '<?php echo $err; ?>';
			jQuery(function($) {
				var nav    = $('.fixed'),
					offset = nav.offset();
				$(window).scroll(function () {
					if($(window).scrollTop() > offset.top) {
						nav.addClass('fixed');
					} else {
						nav.removeClass('fixed');
					}
				});
				$(".flexnav").flexNav();
			});
		</script>
	<script>
	  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
	  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
	  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
	  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');
	  ga('create', 'UA-11155922-7', 'auto');
	  ga('send', 'pageview');
	</script>
	</head>
	<body>
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-PLQMXT"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
		<div id="head1">
			<h1>ログイン！スタッフTシャツならタカハマライフアート</h1>
		</div>
		<?php include '../common/global_nav - test.html'; ?>
		<div id="mainwrap">
			<section id="main">
				<div class="contents">
					<div class="toolbar">
						<div class="toolbar_inner clearfix">
							<h2 class="heading2">ログイン</h2>
						</div>
					</div>
					<div id="loginform_wrapper" class="section">
						<form class="form_m" name="loginform" action="<?php echo $_SERVER['SCRIPT_NAME'];?>" method="post" onsubmit="return false;">
							<div class="close_form"></div>
							<label>メールアドレス</label>
							<input type="text" value="" name="email" autofocus />
							<label>パスワード</label>
							<input type="password" value="" name="pass" />
							<div class="btn_wrap">
								<div id="login_button"></div>
								<a href="resend_pass.php">パスワードを忘れた方はこちらへ</a>
							</div>
							<input type="hidden" name="login" value="1">
							<input type="hidden" name="reg_site" value="6">

						</form>
					</div>
				</div>
				<p class="totop">
					<a href="#head1">ログイン　ページトップへ</a>
				</p>
			</section>
			<?php include '../common/side_nav - test.html'; ?>
		</div>
		<?php include '../common/footer - test.html'; ?>
		<div id="msgbox" class="modal fade" tabindex="-1" role="dialog">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title">メッセージ</h4>
			 		</div>
			 		<div class="modal-body">
						<p></p>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-primary is-ok" data-dismiss="modal">OK</button>
						<button type="button" class="btn btn-default is-cancel" data-dismiss="modal">Cancel</button>
					</div>
				</div>
			</div>
		</div>
		
		<!--Yahoo!タグマネージャー導入 2014.04 -->
		<script type="text/javascript">
		  (function () {
			var tagjs = document.createElement("script");
			var s = document.getElementsByTagName("script")[0];
			tagjs.async = true;
			tagjs.src = "//s.yjtag.jp/tag.js#site=bTZi1c8";
			s.parentNode.insertBefore(tagjs, s);
		  }());
		</script>
		<noscript>
			<iframe src="//b.yjtag.jp/iframe?c=bTZi1c8" width="1" height="1" frameborder="0" scrolling="no" marginheight="0" marginwidth="0"></iframe>
		</noscript>
		<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	</body>
</html>