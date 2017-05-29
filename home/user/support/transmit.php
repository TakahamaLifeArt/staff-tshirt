<?php
/**
 * 仮パスワード送信
 */
ini_set('memory_limit', '128M');
require_once dirname(__FILE__).'/../php_libs/mailer.php';
require_once dirname(__FILE__).'/../php_libs/conndb.php';

if( isset($_REQUEST['ticket'], $_REQUEST['u']) ) {
	$conndb = new Conndb(_API_U);
	
	$newpass = substr(sha1(time().mt_rand()),0,10);
	$args = array('userid'=>$_REQUEST['u'], 'pass'=>$newpass);
	$res = $conndb->updatePass($args);
	if($res){
		$dat = $conndb->getUserList($_REQUEST['u']);
		$args = array('email'=>$dat[0]['email'], 'newpass'=>$newpass, 'username'=>$dat[0]['customername']);
		$mailer = new Mailer($args);
		$isSend = $mailer->send_pass();
	}
}
/*
else{
	unset($_SESSION['ticket']);
	header("Location: "._DOMAIN);
}
*/
/* セッションの使用を廃止
if($isSend){
	unset($_SESSION['ticket']);
}
*/
	
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<meta name="description" content="スタッフTシャツ屋お客様マイページ仮パスワード発行はこちらから。パスワードをお忘れのお客様に仮パスワードをお送りいたします！スタッフTシャツ、イベントTシャツの作成は短納期で早いスタッフTシャツ屋タカハマライフアートへ。">
	<meta name="keywords" content="スタッフTシャツ,パスワード発行,即日,ログイン,お客様情報">
	<meta name="viewport" content="width=device-width,user-scalable=no,maximum-scale=1" />
	<title>メール送信 | スタッフTシャツ屋</title>
	<link rel="shortcut icon" href="/icon/favicon.ico" />
		<link rel="stylesheet" type="text/css" href="../../css/reset.css" media="all">
		<link rel="stylesheet" type="text/css" href="../../css/common - test.css" media="all">
		<link rel="stylesheet" type="text/css" href="../../css/flexnav1.css" media="all">
		<link rel="stylesheet" type="text/css" href="./css/finish_responsive.css" media="screen" />
		<link rel="stylesheet" type="text/css" href="../../css/media-style.css" media="all">
	<link rel="stylesheet" type="text/css" href="/common2/css/base.css" media="screen" />
	<script type="text/javascript" src="/common2/js/jquery.js"></script>
	<script type="text/javascript" src="/common2/js/tlalib.js"></script>
		<script src="../../js/jquery.flexnav.js"></script>
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
</head>

<body>

		<div id="head1">
			<h1>ログイン！スタッフTシャツならタカハマライフアート</h1>
		</div>
		<?php include '../../common/global_nav - test.html'; ?>
		<div id="mainwrap">
			<section id="main">
		<div class="contents">
			
			<?php
				$cst = 'cst';
				function cst($constant){
					return $constant;
				}
				if($isSend){
					$heading = '仮パスワードを送信しています。<br>ご確認ください！';
					$sub = 'Sending';
					$html = <<<DOC
				<div class="inner">
					<p>この度はタカハマライフアートをご利用いただき、誠にありがとうございます。</p>
					<p>仮パスワードは、ログイン後にマイページで変更できます。</p>
				</div>
				<div class="inner">
					<h3>【 <span class="highlights">メールが届かない場合</span> 】</h3>
					<p>
						お客様が入力されました {$args['email']} 宛てに確認メールを返信しておりますが。届かない場合には、<br>
						お手数ですが下記の連絡先までお問い合わせください。<br>
						お急ぎのお客様は、フリーダイヤル {$cst(_TOLL_FREE)} までお気軽にご連絡ください。
					</p>
					<p><a href="/contact/staff_contact/">メールでのお問い合わせはこちらから</a></p>
					<hr />
					<p class="gohome"><a href="/">ホームページに戻る</a></p>
				</div>
DOC;

				}else{
					$heading = '送信エラー！';
					$sub = 'Error';
					$html = <<<DOC
				<div class="inner">
					<div class="remarks">
						<p><strong>メールの送信が出来ませんでした。</strong></p>
						<p>メールの送信中にエラーが発生いたしました。</p>
					</div>
					<p>恐れ入りますが、再度 [ 送信 ] ボタンをクリックして下さい。</p>
				</div>
				<div class="inner">
					<h3>【 親切対応でしっかりサポート 】</h3>
					<p class="note">お急ぎのお客様は、フリーダイヤル {$cst(_TOLL_FREE)} までお気軽にご連絡ください。</p>
					<p><a href="/contact/staff_contact/">メールでのお問い合わせはこちらから</a></p>
					<hr />
					<p class="gohome"><a href="/">ホームページに戻る</a></p>
				</div>
DOC;
				}
			?>
			
			<div class="heading1_wrapper">
				<h2 class="heading2"><?php echo $heading;?></h2>
				<p class="comment"></p>
				<p class="sub"><?php echo $sub;?></p>
			</div>
			<?php echo $html;?>
		</div>

	

			</section>
			<?php include '../../common/side_nav - test.html'; ?>
		</div>
		<?php include '../../common/footer - test.html'; ?>

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
	
</body>
</html>
