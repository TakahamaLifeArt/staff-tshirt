<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/php_libs/conndb.php';
$conn = new Conndb();

// category selector
$data = $conn->categoryList();
$category_selector = '<select id="category_selector">';
for($i=0; $i< count($data); $i++){
	$category_selector .= '<option value="'.$data[$i]['id'].'" data-code="'.$data[$i]['code'].'">'.$data[$i]['name'].'</option>';
}
$category_selector = preg_replace('/value=\"'.$data[0]['id'].'\"/', 'value="'.$data[0]['id'].'" selected="selected"', $category_selector);
$category_selector .= '</select>';
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
	<title>10秒見積もり｜スタッフTシャツ屋</title>
	<meta name="Description" content="スタッフTシャツ屋でオリジナルＴシャツを作成した場合のお見積もりをオンラインで簡単に見ることができます。最短でオリジナルプリントを作成したい方はタカハマライフアートへ！" /> 
	<link rel="shortcut icon" href="/icon/favicon.ico" />
	<meta name="keywords" content="見積もり,簡単,Tシャツ,オリジナル,作成,スタッフTシャツ" /> 
	<meta name="viewport" content="width=device-width,user-scalable=no,maximum-scale=1" />
    <link rel="stylesheet" type="text/css" href="../css/reset.css" media="all">
    <link rel="stylesheet" type="text/css" href="../css/common - test.css" media="all">
	<link rel="stylesheet" type="text/css" href="../css/flexnav1.css" media="all">
	<link rel="stylesheet" type="text/css" href="../css/media-style.css" media="all">
	<link rel="stylesheet" type="text/css" href="/common2/css/printposition.css" media="all">
	<link rel="stylesheet" type="text/css" href="css/style02.css" media="all">
	<link rel="stylesheet" type="text/css" href="../css/estimate.css" media="all">
	<!-- OGP -->
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
	<script src="../js/jquery.flexnav.js"></script>
	<script src="/common2/js/tlalib.js"></script>
	<script type="text/javascript">
		var _IMG_PSS = "<?php echo _IMG_PSS;?>";
	</script>
	<script>
	$( document ).ready(function($) {
		$(".flexnav").flexNav();
		/*scroll*/
		$("a[href^=#]").click(function(){
			var Hash = $(this.hash);
			var HashOffset = $(Hash).offset().top;
			$("html,body").animate({
			scrollTop: HashOffset
			}, 500);
			return false;
		});
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
	  
	});
</script>


</head>
<body>
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-PLQMXT"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
<div id="head1"><h1>10秒見積もり！スタッフTシャツならタカハマライフアート</h1></div>

<?php include '../common/global_nav - test.html'; ?>

	
<div id="mainwrap">
	<section id="main">

	<h2 class="heading2">10秒見積もり</h2>
		<div class="content-lv2">
			<p>全アイテムの見積もりを簡単に比較できます。</p>
		</div>
			<div id="item_wrap">
				<div class="content-lv3">
				<h3>1.カテゴリーを選択</h3>
				<table>
					<caption></caption>
					<tbody>
					<tr>
						<th>商品カテゴリー</th>
						<td>
							<?php echo $category_selector;?>
						</td>
					</tr>
					</tbody>
				</table>
				</div>
			</div>
			
			<div id="body_wrap">
				<div class="content-lv3">
				<h3>2.タイプを選択</h3>
				<div id="boxwrap" class="clearfix">
				
				</div>
				
				<div id="color_wrap">
					<h3>アイテムカラーをご指定ください</h3>
					<input type="radio" name="color" value="0"  checked="checked" id="body-white"><label for="body-white" class="radio">白色</label>
					<input type="radio" name="color" value="1" id="body-radio"><label for="body-radio" class="radio">白色以外</label>
				</div>
				</div>
			</div>
			
			<div id="pos_wrap">
				<div class="content-lv3">
					<h3 class="longtxt">3.プリントする位置とデザインの色数を指定</h3>
					<div>
					<figure>
						<div><p>プリント位置</p></div>
						<ul>
							<li class="pntposition"></li>
						</ul>
					</figure>
					</div>
				</div>
			</div>
			
			<div id="price_wrap">
				<div class="content-lv3">
				<h3>4.アイテムの枚数を指定</h3>
				<table>
					<caption></caption>
					<tbody>
						<tr><td><input type="number" value="30" min="1" max="100" step="1" id="order_amount"> 枚</td></tr>
					</tbody>
				</table>
				</div>
			</div>
			<div class="comLink"><a href="#recommend" id="btnEstimate">見積もり開始</a></div>
						
			<div id="result_wrap02">

				<p class="caution ar"><span class="fontred">※</span>最も安いサイズ・カラー・プリント方法でのお見積もりです。</p>
					<section id="recommend">
					<p class="txt-ong">＼価格と人気が<span class="red">比較</span>できる／</p>
					<h2 class="recH2">お見積り一覧(<ins>31</ins>件)</h2>
				<div class="linered">
				<p class="bdr_red">こちらの料金は、概算となります。</p>
				<p class="gaisan">正確な金額は、デザイン確認後にお見積もりいたします。</p>
	       			</div>
					<div class="tab-index">
						<ul class="clearfix">
							<li class=""><a id="tab1">安い順</a></li>
							<li class="active"><a id="tab2">人気順</a></li>
						</ul>
					</div>
					<div class="tab-contents tab1">
						<div class="rankingitem">
							
						</div>
					</div>
					<div class="tab-contents tab2 active">
						<div class="rankingitem">
							
						</div>
					</div>
					<div class="more" style="display: block;">さらに見る<span></span></div>
					<div class="rankingmore" style="display: none;">
						<div class="tab-contents tab1">
							<div class="rankingitem">
								
							</div>
						</div>
						<div class="tab-contents tab2 active">
							<div class="rankingitem">
								
							</div>
						</div>
					</div>
				</section>
			</div>
 
		<div class="totop"><a href="#head1">ページトップへ</a></div>
	</section>
	<?php include '../common/side_nav - test.html'; ?>
</div>
<?php include '../common/footer - test.html'; ?>

	<script src="./js/estimate.js"></script>	
</body>
</html>
