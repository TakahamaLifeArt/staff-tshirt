<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/../cgi-bin/session_my_handler.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/php_libs/iteminfo.php';
$iteminfo = new ItemInfo();
if(isset($_GET['c'])){
	$categoryCode = $_GET['c'];
}else{
	$categoryCode = 't-shirts';
}
$r = $iteminfo->getList($categoryCode);
// スポーツウェアは、カテゴリに関係なくドライタグを表示させるため
$categoryName = $categoryCode=='sportswear'? 'スポーツウェア': $r[0]['category_name'];
$len = count($r);
$cols = 0;
$html = '<div class="line_wrapper clearfix">';
for($i=0; $i<$len; $i++){
	$val = $r[$i];
	if($cols!=0 && $cols%5==0) $html .= '</div><div class="line_wrapper clearfix">';
	$html .= '<div class="cell">';
		$html .= '<div class="features">'.$val['i_caption'].'</div>';
		$html .= '<a class="c_img" href="/items/itemdetail.php?c='.$val['category_key'].'&amp;i='.$val['id'].'" id="anchor_'.$i.'">';
			$html .= '<img alt="'.$val['name'].'" src="'._IMG_PSS.'items/'.$val['category_key'].'/'.$val['code'].'/'.$val['code'].'_'.$val["i_color_code"].'.jpg" class="img_'.$i.'" width="170">';
		$html .= '</a>';
		$html .= '<div class="c_text">';
			$html .= '<div class="name_wrap"><a href="/items/itemdetail.php?c='.$val['category_key'].'&amp;i='.$val['id'].'" id="anchor_'.$i.'">'.strtoupper($val['name']).'</a></div>';
			$html .= '<p class="price2">メーカー価格 &yen;<span>'.number_format($val['cost_maker']).'</span></p>';
			$html .= '<div class="price1">&yen;'.number_format($val['cost']).'～';
				$html .= '<div class="icon_color">'.$val['colors'].'</div>';
			$html .= '</div>';
			$html .= '<p class="size">サイズ：<span>'.$val['sizeseries'].'</span></p>';
			$html .= '<p class="itembtn">';
				$html .= '<a href="/items/itemdetail.php?c='.$val['category_key'].'&amp;i='.$val['id'].'" id="anchor_'.$i.'" class="btn_lineup">詳細を見る</a>';
			$html .= '</p>';
		$html .= '</div>';
	$html .= '</div>';
	$cols++;
}
$html .= '</div>';
?>
<!DOCTYPE html>
<html>

	<head>
		<!-- Google Tag Manager -->
		<script>
			(function(w, d, s, l, i) {
				w[l] = w[l] || [];
				w[l].push({
					'gtm.start': new Date().getTime(),
					event: 'gtm.js'
				});
				var f = d.getElementsByTagName(s)[0],
					j = d.createElement(s),
					dl = l != 'dataLayer' ? '&l=' + l : '';
				j.async = true;
				j.src =
					'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
				f.parentNode.insertBefore(j, f);
			})(window, document, 'script', 'dataLayer', 'GTM-PLQMXT');

		</script>
		<!-- End Google Tag Manager -->
		<meta charset="utf-8" />
		<meta name="description" content="スタッフTシャツのアイテム一覧はこちらから。商品アイテムをご確認できます！スタッフTシャツ、イベントTシャツの作成は短納期で早いスタッフTシャツ屋タカハマライフアートへ。">
		<meta name="keywords" content="スタッフTシャツ,作成,アイテム一覧 ,お客様情報">
		<meta name="viewport" content="width=device-width,user-scalable=no,maximum-scale=1" />
		<title>スタッフ<?php echo $categoryName;?>の作成・アイテム一覧｜ スタッフTシャツ屋</title>
		<link rel="shortcut icon" href="/icon/favicon.ico" />
		<link rel="stylesheet" type="text/css" href="../css/reset.css" media="all">
		<link rel="stylesheet" type="text/css" href="../css/common - test.css" media="all">
		<link rel="stylesheet" type="text/css" href="../css/flexnav1.css" media="all">
		<link rel="stylesheet" type="text/css" media="screen" href="/common2/css/base.css">
		<link rel="stylesheet" type="text/css" media="screen" href="../css/lineup1.css" />
		<link rel="stylesheet" type="text/css" href="../css/media-style.css" media="all">
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
		<script src="../js/jquery.flexnav.js"></script>
		<script>
			$(document).ready(function($) {
				$(".flexnav").flexNav();
				/*scroll*/
				$("a[href^=#]").click(function() {
					var Hash = $(this.hash);
					var HashOffset = $(Hash).offset().top;
					$("html,body").animate({
						scrollTop: HashOffset
					}, 500);
					return false;
				});
			});

		</script>
		<script type="text/javascript">
			jQuery(function($) {

				var nav = $('.fixed'),
					offset = nav.offset();

				$(window).scroll(function() {
					if ($(window).scrollTop() > offset.top) {
						nav.addClass('fixed');
					} else {
						nav.removeClass('fixed');
					}
				});

			});

		</script>
	</head>

	<body>
		<!-- Google Tag Manager (noscript) -->
		<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-PLQMXT" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
		<!-- End Google Tag Manager (noscript) -->
		<div id="head1">
			<h1>アイテム一覧！スタッフTシャツ屋</h1>
		</div>
		<?php include '../common/global_nav - test.html'; ?>
		<div id="mainwrap">
			<section id="main">
				<div class="contents">
					<div class="orderbtn_item">
						<ul>
							<li>
								<a href="./lineup.php?c=t-shirts" onclick="ga('send','event’,'contact','click','head');">
									<img src="/img/item/st_main_tshirt.jpg" width="100%" alt="Tシャツ" onmouseover="this.src='/img/item/st_main_tshirt_on.jpg'" onmouseout="this.src='/img/item/st_main_tshirt.jpg'">
								</a>
							</li>
							<li>
								<a href="./lineup.php?c=sweat" onclick="ga('send','event’,'contact','click','head');">
									<img src="/img/item/st_main_sweat.jpg" width="100%" alt="パーカー" onmouseover="this.src='/img/item/st_main_sweat_on.jpg'" onmouseout="this.src='/img/item/st_main_sweat.jpg'">
								</a>
							</li>
							<li>
								<a href="./lineup.php?c=outer" onclick="ga('send','event’,'contact','click','head');">
									<img src="/img/item/st_main_blouson.jpg" width="100%" alt="ブルゾン・ジャンパー" onmouseover="this.src='/img/item/st_main_blouson_on.jpg'" onmouseout="this.src='/img/item/st_main_blouson.jpg'">
								</a>
							</li>
							<li>
								<a href="./lineup.php?c=polo-shirts" onclick="ga('send','event’,'contact','click','head');">
									<img src="/img/item/st_main_polo.jpg" width="100%" alt="ポロシャツ" onmouseover="this.src='/img/item/st_main_polo_on.jpg'" onmouseout="this.src='/img/item/st_main_polo.jpg'">
								</a>
							</li>
							<li>
								<a href="./lineup.php?c=sportswear" onclick="ga('send','event’,'contact','click','head');">
									<img src="/img/item/st_main_sports.jpg" width="100%" alt="スポーツウェア" onmouseover="this.src='/img/item/st_main_sports_on.jpg'" onmouseout="this.src='/img/item/st_main_sports.jpg'">
								</a>
							</li>
							<li>
								<a href="./lineup.php?c=long-shirts" onclick="ga('send','event’,'contact','click','head');">
									<img src="/img/item/st_main_longtshirt.jpg" width="100%" alt="ロンT" onmouseover="this.src='/img/item/st_main_longtshirt_on.jpg'" onmouseout="this.src='/img/item/st_main_longtshirt.jpg'">
								</a>
							</li>
							<li>
								<a href="./lineup.php?c=towel" onclick="ga('send','event’,'contact','click','head');">
									<img src="/img/item/st_main_towel.jpg" width="100%" alt="タオル" onmouseover="this.src='/img/item/st_main_towel_on.jpg'" onmouseout="this.src='/img/item/st_main_towel.jpg'">
								</a>
							</li>
							<li>
								<a href="./lineup.php?c=tote-bag" onclick="ga('send','event’,'contact','click','head');">
									<img src="/img/item/st_main_bag.jpg" width="100%" alt="トートバッグ" onmouseover="this.src='/img/item/st_main_bag_on.jpg'" onmouseout="this.src='/img/item/st_main_bag.jpg'">
								</a>
							</li>
							<li>
								<a href="./lineup.php?c=apron" onclick="ga('send','event’,'contact','click','head');">
									<img src="/img/item/st_main_apron.jpg" width="100%" alt="エプロン" onmouseover="this.src='/img/item/st_main_apron_on.jpg'" onmouseout="this.src='/img/item/st_main_apron.jpg'">
								</a>
							</li>
							<li>
								<a href="./lineup.php?c=overall" onclick="ga('send','event’,'contact','click','head');">
									<img src="/img/item/st_main_tsunagi.jpg" width="100%" alt="つなぎ" onmouseover="this.src='/img/item/st_main_tsunagi_on.jpg'" onmouseout="this.src='/img/item/st_main_tsunagi.jpg'">
								</a>
							</li>
							<li>
								<a href="./lineup.php?c=workwear" onclick="ga('send','event’,'contact','click','head');">
									<img src="/img/item/st_main_work.jpg" width="100%" alt="ワークウェア" onmouseover="this.src='/img/item/st_main_work_on.jpg'" onmouseout="this.src='/img/item/st_main_work.jpg'">
								</a>
							</li>
							<li>
								<a href="./lineup.php?c=cap" onclick="ga('send','event’,'contact','click','head');">
									<img src="/img/item/st_main_cap.jpg" width="100%" alt="キャップ" onmouseover="this.src='/img/item/st_main_cap_on.jpg'" onmouseout="this.src='/img/item/st_main_cap.jpg'">
								</a>
							</li>
						</ul>
					</div>
					<div class="section">
						<h2 class="heading2 up">
							<?php echo $categoryName;?>にオリジナルプリント
						</h2>
						<?php echo $html;?>
					</div>
					<div class="under_msg clearfix">
						<p>スタッフTシャツ屋はオリジナルスタッフTシャツの作成だけでなく、ポロシャツ、パーカー、ブルゾンなどにもプリントすることが可能です!!自社工場を完備していますので、アイテムにかかわらず業界最速といわれるスピードで対応いたします。また、Ｔシャツの見本やシルクスクリーンのプリント見本も無料で郵送!! 実際に見たい、どんなプリントか確認したいというお客様のご要望にお応えいたします。ご不明な点は、お気軽にお問い合わせください。 </p>
					</div>
				</div>
				<p class="totop">
					<a href="#head1">
						<?php echo $categoryName;?>一覧　ページトップへ
					</a>
				</p>
			</section>
			<?php include '../common/side_nav - test.html'; ?>
		</div>
		<?php include '../common/footer - test.html'; ?>
		<!--Yahoo!タグマネージャー導入 2014.04 -->
		<script type="text/javascript">
			(function() {
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
