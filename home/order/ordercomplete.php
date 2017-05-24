<?php
	ini_set('memory_limit', '256M');
	require_once dirname(__FILE__).'/../php_libs/orders.php';
	require_once dirname(__FILE__).'/ordermail.php';
	
	if( isset($_POST['ticket'], $_SESSION['ticket'], $_SESSION['orders']) && $_POST['ticket']==$_SESSION['ticket'] ) {
		$email = $_SESSION['orders']['customer']['email'];
		$customer = mb_convert_encoding($_SESSION['orders']['customer']['customername'], 'utf-8', auto);
		$ordermail = new Ordermail();
		$isSend = $ordermail->send();
	}else{
		$isSend = false;
	}

	/* 注文フローのセッションを破棄 */
	if($isSend){
		unset($_SESSION['ticket']);
		$_SESSION['orders'] = array();
		//setcookie(session_name(), "", time()-86400, "/");
		unset($_SESSION['orders']);
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
	<title>お申し込みメールの送信完了　|　スタッフTシャツ屋タカハマライフアート</title>
	<meta name="Description" content="WebでカンタンにスタッフTシャツのお申し込みができます。簡単入力で瞬時に料金の目安がわかります！トレーナー・ポロシャツ・オリジナルTシャツの作成・プリントは、東京都葛飾区のタカハマライフアートにお任せ下さい！" />
	<meta name="keywords" content="注文,お申し込み,オリジナル,Tシャツ,早い,東京" />
	<meta name="google-site-verification" content="PfzRZawLwE2znVhB5M7mPaNOKFoRepB2GO83P73fe5M" />
    <link rel="stylesheet" type="text/css" href="../css/reset.css" media="all">
    <link rel="stylesheet" type="text/css" href="../css/common - test.css" media="all">
	<link rel="stylesheet" type="text/css" href="../css/flexnav1.css" media="all">
	<link rel="stylesheet" type="text/css" href="../css/about - test.css" media="all">
	<link rel="stylesheet" type="text/css" href="../css/media-style.css" media="all">
	<link rel="stylesheet" type="text/css" href="./css/finish.css" media="screen" />
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
	<script type="text/javascript" src="/common2/js/tlalib.js"></script>
	<script src="../js/jquery.flexnav.js"></script>
	<script type="text/javascript">
		$(function($) {
			$(".flexnav").flexNav();
			var nav    = $('.fixed'),
				offset = nav.offset();

			$(window).scroll(function () {
				if($(window).scrollTop() > offset.top) {
					nav.addClass('fixed');
				} else {
					nav.removeClass('fixed');
				}
			});
		});
	</script>
	
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-11155922-7', 'auto');
  ga('send', 'pageview');

</script>
</head>

<body>
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-PLQMXT"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->

<?php include '../common/global_nav - test.html'; ?>

<div id="mainwrap">
	<section id="main">

		<div class="contents">
			<?php			
				$cst = 'cst';
				function cst($constant){
					return $constant;
				}
				
				if($isSend){
					$heading = 'お申し込み内容の確認メールを返信中です。<br>必ずご確認ください！';
					$sub = 'Sending';
					$html = <<<DOC
				<div class="inner">
					<p>{$customer}　様</p>
					<p>この度はタカハマライフアートをご利用いただき、誠にありがとうございます。</p>
				</div>
				
				<div class="remarks">
					<h3>制作の開始について</h3>
					<p>現時点では、<span class="highlights">ご注文は確定していません。</span></p>
					<p>
						制作を開始するにあたり、お電話によるデザインの確認をもって注文確定とさせていただいております。<br>
						弊社から御見積りメールをお送りいたしますので、
						大変お手数ですが、ご確認後フリーダイヤル<ins> {$cst(_TOLL_FREE)} </ins>までご連絡ください。
					</p>
				</div>
				
				<div class="inner">
					<h3>【 <span class="highlights">確認メールが届かない場合</span> 】</h3>
					<p>
						ご入力頂いたメールアドレス {$email} 宛に、お申し込み内容の確認メールを送信しています。<br>
						お客様に確認メールが届いていない場合、弊社にお申し込みメールが届いていない可能性がございますので、<br>
						恐れ入りますが、フリーダイヤル<ins> {$cst(_TOLL_FREE)} </ins>までお問い合わせ下さい。
					</p>
				</div>
				
				<div class="inner">
					<h3>【 ご注文に関するお問い合わせ 】</h3>
					<p>
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
					<p>{$customer}　様</p>
					<div class="remarks">
						<h3>お申し込みメールの送信が出来ませんでした。</h3>
						<p>お申し込みメールの送信中にエラーが発生いたしました。</p>
					</div>
					<p>恐れ入りますが、再度 <a href="/order/">お申し込みフォーム</a> に戻り [ 注文する ] ボタンをクリックして下さい。</p>
				</div>
				<div class="inner">
					<h3>【 ご注文に関するお問い合わせ 】</h3>
					<p class="note">お急ぎのお客様は、フリーダイヤル {$cst(_TOLL_FREE)} までお気軽にご連絡ください。</p>
					<p><a href="/contact/staff_contact/">メールでのお問い合わせはこちらから</a></p>
					<hr />
					<p class="gohome"><a href="/order/">お申し込みフォームに戻る</a></p>
				</div>
DOC;
				}
				
			?>
			
			<div class="heading1_wrapper">
				<h1><?php echo $heading;?></h1>
				<p class="comment"></p>
				<p class="sub"><?php echo $sub;?></p>
			</div>
			<p class="heading"></p>
			<?php echo $html;?>
		</div>
		
	</div>

	</section>
	<?php include '../common/side_nav - test.html'; ?>
</div>
<?php include '../common/footer - test.html'; ?>


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


<!-- Facebook Pixel Code -->
<script>
!function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?
n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;
n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;
t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,
document,'script','https://connect.facebook.net/en_US/fbevents.js');

fbq('init', '1634291193549030');
fbq('track', "PageView");
fbq('track', 'AddToCart');</script>
<noscript><img height="1" width="1" style="display:none"
src="https://www.facebook.com/tr?id=1634291193549030&ev=PageView&noscript=1"
/></noscript>
<!-- End Facebook Pixel Code -->

<!-- Yahoo Code for your Conversion Page -->
<script type="text/javascript">
    /* <![CDATA[ */
    var yahoo_conversion_id = 1000313056;
    var yahoo_conversion_label = "bAwhCODf1WgQzretogM";
    var yahoo_conversion_value = 0;
    /* ]]> */
</script>
<script type="text/javascript" src="//s.yimg.jp/images/listing/tool/cv/conversion.js">
</script>
<noscript>
    <div style="display:inline;">
        <img height="1" width="1" style="border-style:none;" alt="" src="//b91.yahoo.co.jp/pagead/conversion/1000313056/?value=0&label=bAwhCODf1WgQzretogM&guid=ON&script=0&disvt=true"/>
    </div>
</noscript>

<!-- Google Code for CV Conversion Page -->
<script type="text/javascript">
/* <![CDATA[ */
var google_conversion_id = 984715716;
var google_conversion_language = "en";
var google_conversion_format = "3";
var google_conversion_color = "ffffff";
var google_conversion_label = "HhuaCPzZ6WgQxKPG1QM";
var google_remarketing_only = false;
/* ]]> */
</script>
<script type="text/javascript" src="//www.googleadservices.com/pagead/conversion.js">
</script>
<noscript>
<div style="display:inline;">
<img height="1" width="1" style="border-style:none;" alt="" src="//www.googleadservices.com/pagead/conversion/984715716/?label=HhuaCPzZ6WgQxKPG1QM&amp;guid=ON&amp;script=0"/>
</div>
</noscript>

	
</body>
</html>
