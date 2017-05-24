<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/../cgi-bin/config.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/../cgi-bin/jd/japaneseDate.php';

	/*
	*	今注文確定日からお届け日を計算
	*	３営業日＋発送日（営業日）＋配達日数（土日含む）
	*
	*	@today			注文確定日の秒数 default: null　(今日)
	*	@transport		配送日数　default: 1　（北海道、九州、本州離島、島根隠岐郡は配送に2日）
	*	@count_days	作業日　default: 4　（発送日を含む）
	*	@mode			null: default,  simple: 袋詰の有無を考慮しない
	*
	*	return			お届日付情報のハッシュ	{'year','month','day','weekname'}
	*/
	function getDelidate($baseSec=null, $transport=1, $count_days=4, $mode=null){	
		$orderAmount = 0;	// 注文枚数
		$isPack = false;	// 袋詰めの有無
		$jd = new japaneseDate();
		$one_day = 86400;										// 一日の秒数
		
		if(empty($baseSec)){
			$time_stamp = time()+39600;							// 13:00からは翌日扱いのため11時間の秒数分を足す
			$year  = date("Y", $time_stamp);
			$month = date("m", $time_stamp);
			$day   = date("d", $time_stamp);
			$baseSec = mktime(0, 0, 0, $month, $day, $year);	// 注文確定日の00:00のtimestampを取得
		}
		$workday=0;												// 作業に要する日数をカウント
		if(is_null($mode)){
			if($isPack && $orderAmount>=10){					// 袋詰めありで且つ10枚以上のときは作業日数がプラス1日
				$count_days++;
			}
		}
		$_from_holiday = strtotime(_FROM_HOLIDAY);				// お休み開始日
		$_to_holiday = strtotime(_TO_HOLIDAY);				// お休み最終日
		while($workday<$count_days){
			$fin = $jd->makeDateArray($baseSec);
			if( (($fin['Weekday']>0 && $fin['Weekday']<6) && $fin['Holiday']==0) && ($baseSec<$_from_holiday || $_to_holiday<$baseSec) ){
				$workday++;
			}
			$baseSec += $one_day;
		}
		
		// 配送日数は曜日に関係しないため、2日かかる地域の場合に1日分を足す
		if($transport==2){
			$baseSec += $one_day;
		}
		
		$fin = $jd->makeDateArray($baseSec);
		$weekday = $jd->viewWeekday($fin['Weekday']);
		$fin['weekname'] = $weekday;
		$fin['timestamp'] = $baseSec;
		return $fin;
	}
	
	
	/*
	*	お届日
	*	[当日仕上げ, 通常]
	*/
	$time_stamp = time()+39600;
	$fin = getDelidate(null, 1, 1);
	$deliDate[0] = date('n月j日', $fin['timestamp'])."（".$fin['weekname']."）";
	$fin = getDelidate(null, 1, 4);
	$deliDate[1] = date('n月j日', $fin['timestamp'])."（".$fin['weekname']."）";
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
	<meta name="description" content="スタッフTシャツを最短即日（当日）発送します！Tシャツの文字入れのプリントは業界一早いです。1枚でも量産でも短納期で作成します。スタッフTシャツ・パーカー・ブルゾンの作成は短納期で早いスタッフTシャツ屋へ！">
	<meta name="keywords" content="スタッフTシャツ,作成,短納期">
	<meta name="viewport" content="width=device-width,user-scalable=no,maximum-scale=1" />
	<title>スタッフTシャツを短納期で作成【即日】｜スタッフTシャツ屋</title>
	<link rel="shortcut icon" href="/icon/favicon.ico" />
    <link rel="stylesheet" type="text/css" href="css/reset.css" media="all">
    <link rel="stylesheet" type="text/css" href="css/common - test.css" media="all">
	<link rel="stylesheet" type="text/css" href="css/top - test.css" media="all">   
	<link rel="stylesheet" type="text/css" href="css/media-style.css" media="all">
	<link rel="stylesheet" type="text/css" href="css/flexnav1.css" media="all">
	<link rel="stylesheet" type="text/css" href="css/slider-pro.css" media="screen" />
	<link href="http://netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css" rel="stylesheet" />
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
	<script src="js/jquery.flexnav.js"></script>
	<script src="js/jquery.sliderPro.min.js"></script>
	<script src="js/top1.js"></script>


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

<div id="head1"><h1>スタッフTシャツを短納期で作成！</h1></div>

<?php include 'common/global_nav - test.html'; ?>

<div id="mainwrap">
	<section id="main">
<div id="mainvisual">
	<div id="thumb_wrap">
		<div id="thumb-h" class="slider-pro">
			<div class="sp-slides">
				<div class="sp-slide">
					<img class="sp-image" src="img/top/st_main_01.jpg">
				</div>
				<div class="sp-slide">
					<img class="sp-image" src="img/top/st_main_02.jpg">
				</div>
		<!--		<div class="sp-slide">
					<img class="sp-image" src="img/top/st_main_01.jpg"  width="1000px"/>
				</div>
				<div class="sp-slide">
					<img class="sp-image" src="img/top/st_main_01.jpg"  width="1000px"/>
				</div>  -->
			<!--/ sp-slides--></div>
		<!--	<div class="sp-thumbnails">
				<img class="sp-thumbnail" src="img/top/st_main_01.jpg"/>
				<img class="sp-thumbnail" src="img/top/point_02.jpg"/>
			</div>  -->
		<!--/ thumb-h--></div>
	</div>
</div>




<div class="blockclntent">
	
		<div id="plans">
			<div>
				<img src="/img/top/st_day.jpg">
				<div id="plandays">
					<div></div>
					<div><p><?php echo $deliDate[0];?></p></div>
					<div><p><?php echo $deliDate[1];?></p></div>
				</div>
			</div>
			<p class="btnred"><a href="/delivery/index.php">他の日付で調べる</a></p>
		</div>
		
		<div id="flowimg" class="content-lv3">
			<a href="/price/estimate.php"><img src="img/top/st_estimate.jpg" width="100%"  alt="10秒見積もり"></a>
		</div>

		<div class="content-lv3">
			<div class="orderbtn">
	　　　　　　　　<h3>アイテムから選ぶ</h3>
					<p>スタッフTシャツ屋はオリジナルスタッフユニフォームを作成する為に厳選されたアイテムを扱っています。</p>
					<p>どんなシーンにも対応できるラインナップなので手軽に楽しんでご利用いただけます！高品質ながらお求めやすい価格のアイテムを即日お届け!</p>
			　<ul>
				<li><a href="/lineup/lineup.php?c=t-shirts" onclick="ga('send','event’,'contact','click','head');" ><img src="/img/item/st_main_tshirt.jpg" width="100%" alt="Tシャツ" onmouseover="this.src='/img/item/st_main_tshirt_on.jpg'" onmouseout="this.src='/img/item/st_main_tshirt.jpg'" ></a></li>
				<li><a href="/lineup/lineup.php?c=sweat" onclick="ga('send','event’,'contact','click','head');" ><img src="/img/item/st_main_sweat.jpg" width="100%" alt="パーカー" onmouseover="this.src='/img/item/st_main_sweat_on.jpg'" onmouseout="this.src='/img/item/st_main_sweat.jpg'" ></a></li>
				<li><a href="/lineup/lineup.php?c=outer" onclick="ga('send','event’,'contact','click','head');" ><img src="/img/item/st_main_blouson.jpg" width="100%" alt="ブルゾン・ジャンパー" onmouseover="this.src='/img/item/st_main_blouson_on.jpg'" onmouseout="this.src='/img/item/st_main_blouson.jpg'" ></a></li>
				<li><a href="/lineup/lineup.php?c=polo-shirts" onclick="ga('send','event’,'contact','click','head');" ><img src="/img/item/st_main_polo.jpg" width="100%" alt="ポロシャツ" onmouseover="this.src='/img/item/st_main_polo_on.jpg'" onmouseout="this.src='/img/item/st_main_polo.jpg'" ></a></li>
				<li><a href="/lineup/lineup.php?c=sportswear" onclick="ga('send','event’,'contact','click','head');" ><img src="/img/item/st_main_sports.jpg" width="100%" alt="スポーツウェア" onmouseover="this.src='/img/item/st_main_sports_on.jpg'" onmouseout="this.src='/img/item/st_main_sports.jpg'" ></a></li>
				<li><a href="/lineup/lineup.php?c=long-shirts" onclick="ga('send','event’,'contact','click','head');" ><img src="/img/item/st_main_longtshirt.jpg" width="100%" alt="ロンT" onmouseover="this.src='/img/item/st_main_longtshirt_on.jpg'" onmouseout="this.src='/img/item/st_main_longtshirt.jpg'" ></a></li>
				<li><a href="/lineup/lineup.php?c=towel" onclick="ga('send','event’,'contact','click','head');" ><img src="/img/item/st_main_towel.jpg" width="100%" alt="タオル" onmouseover="this.src='/img/item/st_main_towel_on.jpg'" onmouseout="this.src='/img/item/st_main_towel.jpg'" ></a></li>
				<li><a href="/lineup/lineup.php?c=tote-bag" onclick="ga('send','event’,'contact','click','head');" ><img src="/img/item/st_main_bag.jpg" width="100%" alt="トートバッグ" onmouseover="this.src='/img/item/st_main_bag_on.jpg'" onmouseout="this.src='/img/item/st_main_bag.jpg'" ></a></li>
				<li><a href="/lineup/lineup.php?c=apron" onclick="ga('send','event’,'contact','click','head');" ><img src="/img/item/st_main_apron.jpg" width="100%" alt="エプロン" onmouseover="this.src='/img/item/st_main_apron_on.jpg'" onmouseout="this.src='/img/item/st_main_apron.jpg'" ></a></li>
				<li><a href="/lineup/lineup.php?c=overall" onclick="ga('send','event’,'contact','click','head');" ><img src="/img/item/st_main_tsunagi.jpg" width="100%" alt="つなぎ" onmouseover="this.src='/img/item/st_main_tsunagi_on.jpg'" onmouseout="this.src='/img/item/st_main_tsunagi.jpg'" ></a></li>
				<li><a href="/lineup/lineup.php?c=workwear" onclick="ga('send','event’,'contact','click','head');" ><img src="/img/item/st_main_work.jpg" width="100%" alt="ワークウェア" onmouseover="this.src='/img/item/st_main_work_on.jpg'" onmouseout="this.src='/img/item/st_main_work.jpg'" ></a></li>
				<li><a href="/lineup/lineup.php?c=cap" onclick="ga('send','event’,'contact','click','head');" ><img src="/img/item/st_main_cap.jpg" width="100%" alt="キャップ" onmouseover="this.src='/img/item/st_main_cap_on.jpg'" onmouseout="this.src='/img/item/st_main_cap.jpg'" ></a></li>
			</ul>
			</div>
		</div>
		
		<div class="content-lv3">
			<div class="orderbtn">
	　　　　　　　　<h3>用途から選ぶ</h3>
					<p>厳選した6つの用途からおすすめなTシャツを紹介しています！プリント方法ごとの価格例を参考に、迷わず商品をお選び頂けます。</p>
			　<ul>
				<li class="seence"><a href="/scene/event.html" onclick="ga('send','event’,'contact','click','head');" ><img src="/img/top/st_scene_e.jpg" width="100%" alt="イベント" onmouseover="this.src='/img/top/st_scene_e_on.jpg'" onmouseout="this.src='/img/top/st_scene_e.jpg'" ></a></li>
				<li class="seence"><a href="/scene/care.html" onclick="ga('send','event’,'contact','click','head');" ><img src="/img/top/st_scene_c.jpg" width="100%" alt="介護福祉" onmouseover="this.src='/img/top/st_scene_c_on.jpg'" onmouseout="this.src='/img/top/st_scene_c.jpg'" ></a></li>
				<li class="seence"><a href="/scene/volunteer.html" onclick="ga('send','event’,'contact','click','head');" ><img src="/img/top/st_scene_v.jpg" width="100%" alt="ボランティア" onmouseover="this.src='/img/top/st_scene_v_on.jpg'" onmouseout="this.src='/img/top/st_scene_v.jpg'" ></a></li>

			</ul>
			　<ul>
				<li class="seence"><a href="/scene/food.html" onclick="ga('send','event’,'contact','click','head');" ><img src="/img/top/st_scene_f.jpg" width="100%" alt="フード" onmouseover="this.src='/img/top/st_scene_f_on.jpg'" onmouseout="this.src='/img/top/st_scene_f.jpg'" ></a></li>
				<li class="seence"><a href="/scene/work.html" onclick="ga('send','event’,'contact','click','head');" ><img src="/img/top/st_scene_w.jpg" width="100%" alt="ワーク" onmouseover="this.src='/img/top/st_scene_w_on.jpg'" onmouseout="this.src='/img/top/st_scene_w.jpg'" ></a></li>
				<li class="seence"><a href="/scene/goods.html" onclick="ga('send','event’,'contact','click','head');" ><img src="/img/top/st_scene_g.jpg" width="100%" alt="グッズ" onmouseover="this.src='/img/top/st_scene_g_on.jpg'" onmouseout="this.src='/img/top/st_scene_g.jpg'" ></a></li>

			</ul>
			</div>
		</div>

		
		<div class="content-lv3">
			<div class="orderbtn">
	　　　　　　　　<h3>NEW ITEM</h3>
					<p>Tシャツ、シャツ、エプロンなど季節に合った新商品をご紹介!流行りのアイテムにもプリント、スタッフユニフォームを短納期でお届け!</p>
			　<ul>
				<li class="seence"><a href="/items/itemdetail.php?c=t-shirts&i=655" onclick="ga('send','event’,'contact','click','head');" ><img src="/img/top/st_new_01.jpg" width="100%" alt="Tシャツ" onmouseover="this.src='/img/top/st_new_on_01.jpg'" onmouseout="this.src='/img/top/st_new_01.jpg'" ></a></li>
				<li class="seence"><a href="/items/itemdetail.php?c=polo-shirts&i=642" onclick="ga('send','event’,'contact','click','head');" ><img src="/img/top/st_new_02.jpg" width="100%" alt="ポロシャツ" onmouseover="this.src='/img/top/st_new_on_02.jpg'" onmouseout="this.src='/img/top/st_new_02.jpg'" ></a></li>
				<li class="seence"><a href="/items/itemdetail.php?c=workwear&i=750" onclick="ga('send','event’,'contact','click','head');" ><img src="/img/top/st_new_03.jpg" width="100%" alt="シャツ" onmouseover="this.src='/img/top/st_new_on_03.jpg'" onmouseout="this.src='/img/top/st_new_03.jpg'" ></a></li>
			</ul>
			　<ul>
				<li class="seence"><a href="/items/itemdetail.php?c=apron&i=749" onclick="ga('send','event’,'contact','click','head');" ><img src="/img/top/st_new_04.jpg" width="100%" alt="エプロン" onmouseover="this.src='/img/top/st_new_on_04.jpg'" onmouseout="this.src='/img/top/st_new_04.jpg'" ></a></li>
				<li class="seence"><a href="/items/itemdetail.php?c=outer&i=567" onclick="ga('send','event’,'contact','click','head');" ><img src="/img/top/st_new_05.jpg" width="100%" alt="ブルゾン" onmouseover="this.src='/img/top/st_new_on_05.jpg'" onmouseout="this.src='/img/top/st_new_05.jpg'" ></a></li>
				<li class="seence"><a href="/items/itemdetail.php?c=apron&i=580" onclick="ga('send','event’,'contact','click','head');" ><img src="/img/top/st_new_06.jpg" width="100%" alt="エプロン" onmouseover="this.src='/img/top/st_new_on_06.jpg'" onmouseout="this.src='/img/top/st_new_06.jpg'" ></a></li>
			</ul>
			</div>
		</div>

		<div class="content-lv3">
			<div class="topdescrip topdescrip1">
				<div class="leftimg"><a href="/contact/request.html"><img src="img/top/st_request.jpg" width="100%"  alt="資料請求"></a></div>
				<div class="topdescrip_content">
					<h3>NEWS (お知らせ)</h3>
					<div class="newtxt"><p>2016.11.17 イラストレーターテンプレートを追加しました</p>
					　　 <p>2016.11.17 お届日を調べるシステムを導入しました。</p>
					　　 <p>2016.11.16 商品サンプルを無料でお届けするサービスができました。</p>
					　　 <p>2016.11.15 簡単に10秒で見積もりができるようになりました。</p>
					　　 <p>2016.11.14 新商品を追加しました。</p>
					　　 <p>2016.11.13 スタッフTシャツ屋をリニューアルしました。</p>
　　　　　　　　　　　　　　　　　　　　</div>
				</div>
			</div>

			<div class="topdescrip topdescrip2">
				<div><a href="/nagare/index.php"><img src="img/top/st_flow.jpg" width="100%"  alt="ご利用の流れ"></a></div>
			</div>
		</div>

		<div class="content-lv3">
			<div class="topdescrip topdescrip1">
				<div class="leftimg logo"><img src="img/top/st_seo_logo.jpg" width="100%"  alt="スタッフTシャツ屋"></div>
				<div class="topdescrip_content">
					     <h4>スタッフ様ユニフォームはスタッフTシャツ屋にお任せ！</h4>
					<p>スタッフTシャツが最短当日の短納期で印刷できます！スタッフTシャツ・ポロシャツ・パーカー・
					ブルゾン・トートバッグ等の作成は早い・激安・高品質のタカハマライフアートへ。
					企業、お店、イベントのイメージアップに役立ちます。一流アパレル技術の東京の自社工場でプリント方法もいろいろ選べます。 
					1枚からスタッフTシャツ、イベントTシャツなど 大口注文まで、納期、価格等ご相談に応じます。</p>

					　　　<h4>スタッフTシャツ屋の特徴</h4>
					<p>早い！業界最速短納期を提供しています。
					通常3日仕上げで対応、業界最速の当日仕上げを含む「特急仕上げプラン」もあり、スタッフTシャツが何処よりも早く即日で作成できます。
					スタッフTシャツ・ブルゾンの作成に関わる様々なご相談には電話、メールフォーム応じています。
					丁寧で早い対応を心がけていて、お客様から評価をいただいております。
					また、様々なプリント方法の中で、スタッフTシャツ・ブルゾンに適しているデジタルコピー転写プリントができます。
					フルカラーでデザインの再現性が高く、ロゴや写真がほぼデータ通りにプリントできます。 オリジナルスタッフユニフォームを製作してイベントを盛り上げましょう！</p>

				</div>
			</div>
		</div>

		<div class="totop"><a href="#head1">スタッフTシャツ屋　ページトップへ</a></div>
</div>
	</section>

	<?php include 'common/side_nav - test.html'; ?>
</div>

<?php include 'common/footer - test.html'; ?>

</body>
</html>