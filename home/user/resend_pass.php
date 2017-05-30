<?php
require_once dirname(__FILE__).'/php_libs/funcs.php';

if($_SERVER['REQUEST_METHOD']!='POST'){
	setToken();
}else{
	chkToken();
	
	if(isset($_POST['resend'])){
		$conndb = new Conndb(_API_U);
		
		$param['email'] = trim(mb_convert_kana($_POST['email'],"s", "utf-8"));
		$args = array($param['email']);
		
		if(empty($param['email'])){
			$err['email'] = 'メールアドレスを入力して下さい。';
		}else if(!isValidEmailFormat($param['email'])){
			$err['email'] = 'メールアドレスが正しくありません。';
		}else{
			$user = $conndb->checkExistEmail($args);
			$userid = $user['id'];
			if(!$userid) $err['email'] = 'メールアドレスのご登録がありません。';
		}
		
		if(empty($err)) jump(_DOMAIN.'/user/support/transmit.php?ticket='.$_POST['token'].'&u='.$userid);
	}
	
}

?>
<!DOCTYPE html>
<html>
	<head>
	<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-PLQMXT');</script>
<!-- End Google Tag Manager -->
		<meta charset="utf-8" />
		<meta name="description" content="スタッフTシャツお客様のパスワードを忘れた方はこちらから。パスワードを忘れた方！スタッフTシャツ、イベントTシャツの作成は短納期で早いスタッフTシャツ屋タカハマライフアートへ。">
		<meta name="keywords" content="スタッフTシャツ,作成,デザイン,お客様情報">
		<meta name="viewport" content="width=device-width,user-scalable=no,maximum-scale=1" />
		<title>パスワードを忘れた方 - | スタッフTシャツ屋</title>
		<link rel="shortcut icon" href="/icon/favicon.ico" />
		<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
		<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css">
		<link rel="stylesheet" type="text/css" href="../css/reset.css" media="all">
		<link rel="stylesheet" type="text/css" href="../css/common - test.css" media="all">
		<link rel="stylesheet" type="text/css" href="../css/flexnav1.css" media="all">
		<link rel="stylesheet" type="text/css" href="../css/media-style.css" media="all">
		<link rel="stylesheet" type="text/css" media="screen" href="/common2/css/base.css">
		<link rel="stylesheet" type="text/css" media="screen" href="./css/style.css" />
		<link rel="stylesheet" type="text/css" media="screen" href="./css/account.css" />
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
		<script type="text/javascript" src="/common2/js/tlalib.js"></script>
		<script type="text/javascript" src="./js/resendpass.js"></script>
		<script src="../js/jquery.flexnav.js"></script>
		<script type="text/javascript">
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
			<script type="text/javascript">
			var _gaq = _gaq || [];
			_gaq.push(['_setAccount', 'UA-11155922-2']);
			_gaq.push(['_trackPageview']);

			(function() {
				var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
				ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
				var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
			})();
		</script>
	</head>
	<body>
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-PLQMXT"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
		<div id="head1">
			<h1>パスワードを忘れた方！スタッフTシャツ屋</h1>
		</div>
		<?php include '../common/global_nav - test.html'; ?>
		<div id="mainwrap">
			<section id="main">
				<div class="contents">
					<h2 class="heading2 up">パスワードを忘れた方</h2>
					<div class="section">
						<form name="pass" action="<?php echo $_SERVER['SCRIPT_NAME']; ?>" method="post" onsubmit="return false;">
							<table class="form_table me" id="pass_table">
								<caption>登録されたメールアドレスに仮パスワードを送信いたします。</caption>
								<tfoot>
									<tr>
										<td colspan="2">
											<input type="hidden" name="token" value="<?php echo $_SESSION['token']; ?>">
											<input type="hidden" name="resend" value="1">
											<p>
												<span class="ok_button">送信</span>
											</p>
										</td>
									</tr>
								</tfoot>
								<tbody>
									<tr>
										<th>メールアドレス</th>
										<td>
											<input type="text" name="email" value="<?php echo $_POST['email'];?>">
											<br>
											<ins class="err">
												<?php echo $err['email']; ?>
											</ins>
										</td>
									</tr>
								</tbody>
							</table>
						</form>
					</div>
				</div>
				<p class="totop">
					<a href="#head1">ページトップへ</a>
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