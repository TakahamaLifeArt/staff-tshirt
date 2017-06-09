<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/../cgi-bin/downloader.php';
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
	<meta name="description" content="スタッフTシャツ屋タカハマライフアートのデザインの作り方ページです。イラレ、手書き、画像（写真など）、デザイン方法ごとに入稿する際の注意点がございます。スタッフTシャツ、イベントTシャツの作成は短納期で早いスタッフTシャツ屋タカハマライフアートへ。">
	<meta name="keywords" content="デザイン,オリジナル,Tシャツ,東京,作成,プリント">
	<title>デザインの作り方｜スタッフTシャツ屋</title>
	<link rel="shortcut icon" href="/icon/favicon.ico" />
	<meta name="viewport" content="width=device-width,user-scalable=no,maximum-scale=1" />
	<link rel="stylesheet" type="text/css" href="../css/reset.css" media="all">
	<link rel="stylesheet" type="text/css" href="../css/common - test.css" media="all">
	<link rel="stylesheet" type="text/css" href="../css/flexnav1.css" media="all">
	<link rel="stylesheet" type="text/css" href="../css/media-style.css" media="all">
	<link rel="stylesheet" type="text/css" href="../css/design.css" media="all">
	<!--<link rel="alternate" media="only screen and (max-width: 640px)" href="http://www.staff-tshirt.com/m/nagare/" >-->
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
	<script type="text/javascript" src="/common2/js/tlalib.js"></script>
	<script type="text/javascript" src="./js/template_illust.js"></script>
	<script src="../js/jquery.flexnav.js"></script>
	<script>
		jQuery(function ($) {
			var nav = $('.fixed'),
				offset = nav.offset();

			$(window).scroll(function () {
				if ($(window).scrollTop() > offset.top) {
					nav.addClass('fixed');
				} else {
					nav.removeClass('fixed');
				}
			});
			$(".flexnav").flexNav();
		});
	</script>
	<!--[if lt IE 9]> 
	<script src="/js/html5shiv.js"></script>
	<![endif]-->

</head>

<body>
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-PLQMXT"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
	<div id="head1">
		<h1>デザインの作り方！スタッフTシャツならタカハマライフアート</h1></div>

	<?php include '../common/global_nav - test.html'; ?>

		<div id="mainwrap">
			<section id="main">
				<h2 class="heading2">デザインの作り方</h2>
				<div class="content-lv2">
					<p>デザインの作り方から入稿の仕方まで、ご案内いたします。
						<br> 入稿用テンプレートもございますので、ご活用くださいませ。
					</p>
				</div>


				<div class="step content-lv3">
					<div class="red_line">
						<h3 class="line_txt">デザインの作り方は <span>3</span>通り</h3></div>
					<p>マンガで入稿方法を簡単に説明しておりますので、ご参照ください。</p>
					<div class="left_btn">
						<ul>
							<li>
								<a href="/design/designguide_freehand.php" class="pcset"><img src="../img/design/tegaki.jpg" alt="手書き入稿についてはこちらから" width="100%" onmouseover="this.src='../img/design/tegaki_on.jpg'" onmouseout="this.src='../img/design/tegaki.jpg'"></a>
							</li>
							<li>
								<a href="/design/designguide_illust.php" class="pcset"><img src="../img/design/illustrator.jpg" alt="Illustrator、Photoshop入稿についてはこちらから" width="100%" onmouseover="this.src='../img/design/illustrator_on.jpg'" onmouseout="this.src='../img/design/illustrator.jpg'"></a>
							</li>
<li><a href="/design/designguide_image.php" class="pcset"><img src="../img/design/gazou.jpg" alt="写真・画像入稿についてはこちらから" width="100%" onmouseover="this.src='../img/design/gazou_on.jpg'" onmouseout="this.src='../img/design/gazou.jpg'"></a></li>

						</ul>
					</div>
				</div>

				<div class="step content-lv3">

				        <div class="serectbtn">
						<p>各プリント方法の説明をしています。お客様のイメージにあったプリント方法をお選びくださいませ。</p>
						<p>
						<a href="/print/index.php" class="pcset"><img src="../img/design/howtoprint.jpg" alt="プリント方法" width="100%" onmouseover="this.src='../img/design/howtoprint_on.jpg'" onmouseout="this.src='../img/design/howtoprint.jpg'"></a>				
						</p>
					<h3>フォント・インク</h3>
						<p>こちらから様々な書体とインクのカラーをご覧いただけます。</p>
						<div class="left_btn">
							<ul>
							<li>
									<a href="/font/" class="pcbtn"><img src="../img/design/font.jpg" alt="フォント" width="100%" onmouseover="this.src='../img/design/font_on.jpg'" onmouseout="this.src='../img/design/font.jpg'"></a>
								</li>
								<li>
									<a href="/ink/" class="pcbtn"><img src="../img/design/ink.jpg" alt="インク" width="100%" onmouseover="this.src='../img/design/ink_on.jpg'" onmouseout="this.src='../img/design/ink.jpg'"></a>
								</li>	
							</ul>
						</div>
					<h3>プリントサイズイメージ</h3>
					<p>デザイン作成の際にご参照ください。</p>
					<div><img src="../img/design/printsizeimage.jpg" width="100%" alt="デザイン作成位置"></div>	
					</div>


				</div>
				<div class="step content-lv3">
					<div class="double">
						<h3 class="ttl_txt">ご注文金額が30,000円以上の方、<span class="bld_txt">&yen;0</span>で下記2点をサポートいたします!</h3></div>
					<div class="left_pnt">
						<p><img src="../img/design/support_01.jpg" width="100%" alt="フォント"></p>
					</div>
					<div class="right_pnt">
						<p><img src="../img/design/support_02.jpg" width="100%" alt="文字うち替え"></p>
					</div>

				</div>

				<div class="step content-lv3">
					<div class="red_line">
						<h3 class="line_txt"><span><img src="../img/design/caution.png" width="100%" alt="◇" class="mark"></span>注意</h3></div>
					<h3>使用不可なデータ</h3>
					<p>下記内容は解像度が低いため、使用不可になります。ただし2・3・4は解像度が充分である場合は可能です。</p>
					<div class="left_pnt">
						<ul>
							<li>
								<p class="txt_ttl"><span><img src="../img/design/icon_01.png" width="100%" alt="◇" class="mark"></span>ビジネスソフト、オフィスデータで作成したもの</p>
							</li>
							<li>
								<p class="txt_ttl"><span><img src="../img/design/icon_02.png" width="100%" alt="◇" class="mark"></span>Windowsペイントソフトのデータ</p>
							</li>

						</ul>
					</div>
					<div class="right_pnt">
						<ul>
							<li>
								<p class="txt_ttl"><span><img src="../img/design/icon_03.png" width="100%" alt="◇" class="mark"></span>携帯電話のカメラの写真</p>
							</li>
							<li>
								<p class="txt_ttl"><span><img src="../img/design/icon_04.png" width="100%" alt="◇" class="mark"></span>ホームページ等からダウンロードしたjpg・gif画像</p>
							</li>

						</ul>
					</div>

				</div>

				<div class="step content-lv3">
					<h3>著作権・商標登録・肖像権のあるデザイン</h3>

					<div class="syouhyou">
						<ul>
							<li class="point">
								<p>著作権法上認められた場合を除き、無断で複製 ・転用することはできません。</p>
							</li>
							<li class="point">
								<p>c マークが入ったものは不可</p>
							</li>
							<li class="point">
								<p>企業ロゴ、ブランドロゴ、漫画アニメキャラクター、著名人のイラスト、ディズニー等はそのままの使用は不可 </p>
							</li>
							<li>
								<p><img src="../img/design/icon_05.png" width="100%" alt="マーク" class="mark"><img src="../img/design/icon_06.png" width="100%" alt="マーク" class="mark"></p>
								<div class="inner">
									<h4>お断りをする際の判断基準について</h4>
								</div>
							</li>
							<li>
								<p>1. 個人を特定できる、著名人等の似顔絵・シルエットを含んだデザイン。</p>
								<p>2. パロディー元のデザインに対して、一部分でも全く手を加えていない箇所があるデザイン。</p>
								<p>3 デザイン内容が違っていても第3者が見て本物と誤解を招く恐れのあるデザイン。 </p>
							</li>

						</ul>
					</div>
				</div>

				<div class="step content-lv3 offphone">
					<h3>オーダーシートダウンロード</h3>
					<p>手書き入稿用のオーダーシートです。</p>
					<div class="seet">
						<p>
							<a href="/contact/faxorderform.pdf" class="pcset"><img src="../img/design/ordetsheet.jpg" alt="オーダーシートダウンロード" width="100%" onmouseover="this.src='../img/design/ordetsheet_on.jpg'" onmouseout="this.src='../img/design/ordetsheet.jpg'"></a>
						</p>
					</div>

					<h3>Illustratorテンプレートダウンロード</h3>
					<p>Illustrator入稿用のテンプレートです。ご注文するアイテムの絵型を選択し、お客様のデザインを載せて入稿することができます。</p>
					<div class="section">
						<ul>
							<li>
								<div class="box tmp_1" alt="tshirts" onclick="ga('send','event','template','click','tshirts');">
									<p>Tシャツ</p>
								</div>
							</li>
							<li>
								<div class="box tmp_2" alt="longshirts" onclick="ga('send','event','template','click','longshirts');">
									<p>ロングTシャツ</p>
								</div>
							</li>
							<li>
								<div class="box tmp_3" alt="poloshirts-pocket" onclick="ga('send','event','template','click','poloshirts-pocket');">
									<p>ポロシャツ（ﾎﾟｹ有）</p>
								</div>
							</li>
							<li>
								<div class="box tmp_4" alt="poloshirts" onclick="ga('send','event','template','click','poloshirts');">
									<p>ポロシャツ（ﾎﾟｹ無）</p>
								</div>
							</li>
							<li>
								<div class="box tmp_5" alt="trainer" onclick="ga('send','event','template','click','trainer');">
									<p>トレーナー</p>
								</div>
							</li>
							<li>
								<div class="box tmp_6" alt="parker" onclick="ga('send','event','template','click','parker');">
									<p>プルオーバーパーカー</p>
								</div>
							</li>
							<li>
								<div class="box tmp_7" alt="zipparker" onclick="ga('send','event','template','click','zipparker');">
									<p>ジップパーカー</p>
								</div>
							</li>
							<li>
								<div class="box tmp_8" alt="sweatpants" onclick="ga('send','event','template','click','sweatpants');">
									<p>スウェットパンツ</p>
								</div>
							</li>
							<li>
								<div class="box tmp_9" alt="halfpants" onclick="ga('send','event','template','click','halfpants');">
									<p>ハーフパンツ</p>
								</div>
							</li>
							<li>
								<div class="box tmp_10" alt="blouson" onclick="ga('send','event','template','click','blouson');">
									<p>ブルゾン・ジャンパー</p>
								</div>
							</li>
							<li>
								<div class="box tmp_11" alt="cap" onclick="ga('send','event','template','click','cap');">
									<p>キャップ・帽子</p>
								</div>
							</li>
							<li>
								<div class="box tmp_12" alt="towel" onclick="ga('send','event','template','click','towel');">
									<p>タオル</p>
								</div>
							</li>
						</ul>
					</div>
				</div>

				<div class="onphone">
					<p class="red_txt">※手書き入稿の方はオーダーシートを、Illustrator入稿の方はテンプレートをPCからダウンロードできます。是非ご利用くださいませ。</p>
					<p>デザインにお悩みの方は、スタッフがサポートいたしますので、こちらからお問い合わせください。</p>
					<div class="seet">
						<p class="scen_btn"><a href="/contact/staff_contact/" class="pcset">お問い合わせ</a></p>
					</div>

				</div>
				<div class="totop"><a href="#head1">ページトップへ</a></div>
			</section>
			<?php include '../common/side_nav - test.html'; ?>
		</div>
		<?php include '../common/footer - test.html'; ?>
		<form name="downloader" action="<?php echo $_SERVER['SCRIPT_NAME']; ?>" method="post" onSubmit="return false;" >
			<input type="hidden" name="downloadfile" value="" />
			<input type="hidden" name="act" value="download" />
		</form>
</body>

</html>