<?php
	require_once $_SERVER['DOCUMENT_ROOT'].'/php_libs/orders.php';
	$order = new Orders();
	
	for($cnt=1,$idx=0; $cnt<5; $cnt++,$idx++){
		$fin = $order->getDelidate(null, 1, $cnt, 'simple');
		$main_month[$idx] = $fin['Month'];
		$main_day[$idx] = $fin['Day'];
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
	<title>最速スタッフTシャツ作成　納期・お届け日　|　スタッフTシャツ屋</title>
	<meta name="Description" content="スタッフTシャツ作成を通常の3日仕上げから、最短で翌日仕上げまで、納期がWeb上で簡単にわかります。お急ぎのお客様はご確認ください。" />
	<meta name="keywords" content="スタッフTシャツ,東京,作成,プリント,お届け日,納期" />
	<meta name="viewport" content="width=device-width,user-scalable=no,maximum-scale=1" />
	<link rel="shortcut icon" href="/icon/favicon.ico" />
	<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css">
	<link rel="stylesheet" type="text/css" href="../css/reset.css" media="all">
	<link rel="stylesheet" type="text/css" href="/common2/css/base.css" media="screen" />
	<link rel="stylesheet" type="text/css" href="/common2/js/ui/flick/jquery.ui.core.css" media="all" />
	<link rel="stylesheet" type="text/css" href="/common2/js/ui/flick/jquery.ui.datepicker.css" media="all" />
	<link rel="stylesheet" type="text/css" href="/common2/js/ui/flick/jquery.ui.theme.css" media="all" />
	<link rel="stylesheet" type="text/css" href="../css/common.css" media="all">
	<link rel="stylesheet" type="text/css" href="../css/flexnav1.css" media="all">
	<link rel="stylesheet" type="text/css" href="../css/media-style.css" media="all">
	<link rel="stylesheet" type="text/css" href="./css/deliveryday.css" media="screen" />

	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
	<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.11.0/jquery-ui.min.js"></script>
	<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1/i18n/jquery.ui.datepicker-ja.min.js"></script>
	<script type="text/javascript" src="/common2/js/tlalib.js"></script>
	<script type="text/javascript" src="./js/deliveryday.js"></script>
	<script src="../js/jquery.flexnav.js"></script>
	<script>
	$( document ).ready(function($) {
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
	
<div id="head1"><h1>お支払い方法と送料について！スタッフTシャツ屋</h1></div>

<?php include '../common/global_nav.html'; ?>

<div id="mainwrap">
	<section id="main">
			<div class="heading1_wrapper">
				<h2  class="heading2_otodoke">お届け日</h2>
				<!--<p class="sub">Delivery day</p>-->
			</div>
			

			<!-- <div id="delivery_date_now" class="delivery_date_wrapper">
				<div class="heading2_s">
					<h2>今、<span>お電話での確認まで</span>完了した場合のお届け日を調べる</h2>
				</div>
				<ul class="cal_red">
					<li>
						<div>
						<p class="deribdr"><img class="bg_r" alt="当日仕上げ" src="img/day0_r.png" width="45%"></p>
						<p><span class="mm"><?php echo $main_month[0]; ?>/</span><span class="dd"><?php echo $main_day[0]; ?></span></p>
						<img alt="にお届け" src="img/niotodoke.png" width="130" height="22">
						</div>
						<p class="kome">※1</p>
					</li>
					<li>
						<div>
						<p class="deribdr"><img class="bg_r" alt="翌日仕上げ" src="img/day1_r.png" width="45%"></p>
						<p><span class="mm"><?php echo $main_month[1]; ?>/</span><span class="dd"><?php echo $main_day[1]; ?></span></p>
						<img alt="にお届け" src="img/niotodoke.png" width="130" height="22">
						</div>
						<p class="kome">※2</p>
					</li>
					<li>
						<div>
						<p class="deribdr"><img class="bg_r" alt="２日仕上げ" src="img/day2_r.png" width="45%"></p>
						<p><span class="mm"><?php echo $main_month[2]; ?>/</span><span class="dd"><?php echo $main_day[2]; ?></span></p>
						<img alt="にお届け" src="img/niotodoke.png" width="130" height="22">
						</div>
						<p class="kome">※3</p>
					</li>
					<li>
						<div>
						<p class="deribdr"><img class="bg_r" alt="通常３日仕上げ" src="img/day3_r.png" width="45%"></p>
						<p><span class="mm"><?php echo $main_month[3]; ?>/</span><span class="dd"><?php echo $main_day[3]; ?></span></p>
						<img alt="にお届け" src="img/niotodoke.png" width="130" height="22">
						<p class="kome"></p>
						</div>
					</li>
				</ul>
				<ul class="cal_red">
					<li>
				<p class="btnOrder_wrap"><a href="#schedule_wrapper" id="btnnvy">当日特急プラン</a></p></li>
					<li></li>
					<li></li>
					<li>
				<p class="btnOrder_wrap"><a href="/order/" id="btngrn">お申込み</a></p></li>
				</ul>				
				<div class="note">
					(※) お届け先が、北海道、九州、沖縄、東京離島、島根隠岐郡　のいずれかとなる場合は表示日数より１日多くかかります。<br>
					(※１) <span>まずはお電話でご相談ください。</span>アイテムは Tシャツ<a href="/items/itemdetail.php?c=t-shirts&i=4">085-CVT</a> の白か黒に限ります（サイズはS～XL）。<br>
					(※１) 当日仕上げの場合は<span>12:00</span>を過ぎると翌日扱いになります。
					(※２) 翌日仕上げの場合は、通常料金の<span>1.5倍</span>になります。<br>
					(※３) 2日仕上げの場合は、通常料金の<span>1.3倍</span>になります。
				</div>
			</div>  -->
			
			
			
			<div id="delivery_date_search" class="delivery_date_wrapper">
				<div class="heading2_s">
					<h2>お届け日(納期)を調べる</h2>
				</div>
				
				<div class="s_step">
					<h3 class="heading3"><span>1.</span>お届け先</h3>
					<p>お届け先が、北海道、九州、沖縄、東京離島、島根隠岐郡　のいずれかとなる場合はチェックして下さい。</p>
					<div class="s_step_inner">
						<input type="checkbox" id="transport" name="transport" value="2" /> <label class="pointer" for="transport">配達日数が２日かかります。</label>
					</div>
				</div>
				
				<div class="s_step">
					<h3 class="heading3"><span>2.</span>注文確定日もしくは、お届け日を指定</h3>
					<!--<p>「注文確定日からお届け日を計算」または、<br>
					「お届け日(手元に欲しい日)から注文確定しなければいけない日を計算」します。</p>-->
					<p class="toha"><a href="#condition"><img sc="/st_question_icon.png">注文確定とは</a></p>
				</div>

				<div id="cal_date">
					<div id="fixed_date">
						<p class="date_title">注文確定日</p>
						<div class="cal_date_inner">
							<p>13時を過ぎると翌日扱いです<span class="fontred">※1</span></p>
							<p>日付：<input id="datepicker_firmorder" type="text" size="10" class="forDate" value=""></p>
							<p><input type="button" value="注文確定日から計算" id="btnFirmorder"></p>
						</div>
					</div>
					<div id="arrow"><img alt="通常3日仕上げ" src="img/b0.png" width="90%"></div>
					<div id="deli_date">
						<p class="date_title">お届け日</p>
						<div class="cal_date_inner">
							<p>お手元に届く日です</p>
							<p>日付：<input id="datepicker_deliday" type="text" size="10" class="forDate" value=""></p>
							<p><input type="button" value="お届け日から計算" id="btnDeliday"></p>
						</div>
					</div>
				</div>

				<!--注文確定日から計算-->
				<div id="cal_date_r" class="cal_date">
					<h4 class="heading4"><span></span>の13時までに注文確定すると、</h4>
					<ul class="cal_red">
						<li>
							<div>
							<p class="deribdr"><img class="bg_r" alt="当日仕上げ" src="img/day0_r.png" width="45%"></p>
							<p><span class="mm"><?php echo $main_month[3]; ?>/</span><span class="dd"><?php echo $main_day[3]; ?></span></p>
							<img alt="にお届け" src="img/niotodoke.png" width="130" height="22">
							</div>
							<p class="kome">※1</p>
						</li>
						<li>
							<div>
							<p class="deribdr"><img class="bg_r" alt="翌日仕上げ" src="img/day1_r.png" width="45%"></p>
							<p><span class="mm"><?php echo $main_month[2]; ?>/</span><span class="dd"><?php echo $main_day[2]; ?></span></p>
							<img alt="にお届け" src="img/niotodoke.png" width="130" height="22">
							</div>
							<p class="kome">※2</p>
						</li>
						<li>
							<div>
							<p class="deribdr"><img class="bg_r" alt="２日仕上げ" src="img/day2_r.png" width="45%"></p>
							<p><span class="mm"><?php echo $main_month[1]; ?>/</span><span class="dd"><?php echo $main_day[1]; ?></span></p>
							<img alt="にお届け" src="img/niotodoke.png" width="130" height="22">
							</div>
							<p class="kome">※3</p>
						</li>
						<li>
							<div>
							<p class="deribdr"><img class="bg_r" alt="通常３日仕上げ" src="img/day3_r.png" width="45%"></p>
							<p><span class="mm"><?php echo $main_month[0]; ?>/</span><span class="dd"><?php echo $main_day[0]; ?></span></p>
							<img alt="にお届け" src="img/niotodoke.png" width="130" height="22">
							</div>
						</li>
					</ul>
					<p class="clearfix"></p>
				</div>

				<!--お届日から計算-->
				<div id="cal_date_b3" class="cal_date">
					<h4 class="heading4"><span>2014/4/10</span>にお届するには、</h4>
					<ul class="cal_blue">
						<li class="li_b0">
							<div>
							<p class="deribdr-1"><img class="bg_b" alt="当日仕上げでも間に合います" src="img/day0_b0.png" width="45%"></p>
							<p class="otodoke_b"><span></span>にお届可能</p>
							<p><span class="mm"><?php echo $main_month[3]; ?>/</span><span class="dd"><?php echo $main_day[3]; ?></span></p>
							<p class="time_b">11:00<span>までに</span></p>
							<p class="tel_b">お電話の確認まで<br>完了してください</p>
							</div>
							<p class="kome">※1</p>
						</li>
						<li class="li_b0">
							<div>
							<p class="deribdr-1"><img class="bg_b" alt="翌日仕上げでも間に合います" src="img/day1_b0.png" width="45%"></p>
							<p class="otodoke_b"><span></span>にお届可能</p>
							<p><span class="mm"><?php echo $main_month[2]; ?>/</span><span class="dd"><?php echo $main_day[2]; ?></span></p>
							<p class="time_b">13:00<span>までに</span></p>
							<p class="tel_b">お電話の確認まで<br>完了してください</p>
							</div>
							<p class="kome">※2</p>
						</li>
						<li class="li_b0">
							<div>
							<p class="deribdr-1"><img class="bg_b" alt="２日仕上げでも間に合います" src="img/day2_b0.png" width="45%"></p>
							<p class="otodoke_b"><span></span>にお届可能</p>
							<p><span class="mm"><?php echo $main_month[1]; ?>/</span><span class="dd"><?php echo $main_day[1]; ?></span></p>
							<p class="time_b">13:00<span>までに</span></p>
							<p class="tel_b">お電話の確認まで<br>完了してください</p>
							</div>
							<p class="kome">※3</p>
						</li>
						<li class="li_b">
							<div>
							<p class="deribdr-1"><img class="bg_b" alt="通常３日仕上げで間に合います" src="img/day3_b.png" width="45%"></p>
							<p class="otodoke_b"><span></span>にお届可能</p>
							<p><span class="mm"><?php echo $main_month[0]; ?>/</span><span class="dd"><?php echo $main_day[0]; ?></span></p>
							<p class="time_b">13:00<span>までに</span></p>
							<p class="tel_b">お電話の確認まで<br>完了してください</p>
							</div>
						</li>
					</ul>
					<p class="clearfix"></p>
				</div>
				<div id="cal_date_b2" class="cal_date">
					<h4 class="heading4"><span>2014/4/10</span>にお届するには、</h4>
					<ul class="cal_blue">
						<li class="li_b0">
							<div>
							<p class="deribdr-1"><img class="bg_b" alt="当日仕上げでも間に合います" src="img/day0_b0.png" width="45%"></p>
							<p class="otodoke_b"><span></span>にお届可能</p>
							<p><span class="mm"><?php echo $main_month[3]; ?>/</span><span class="dd"><?php echo $main_day[3]; ?></span></p>
							<p class="time_b">12:00<span>までに</span></p>
							<p class="tel_b">お電話の確認まで<br>完了してください</p>
							</div>
							<p class="kome">※1</p>
						</li>
						<li class="li_b0">
							<div>
							<p class="deribdr-1"><img class="bg_b" alt="翌日仕上げでも間に合います" src="img/day1_b0.png" width="45%"></p>
							<p class="otodoke_b"><span></span>にお届可能</p>
							<p><span class="mm"><?php echo $main_month[2]; ?>/</span><span class="dd"><?php echo $main_day[2]; ?></span></p>
							<p class="time_b">13:00<span>までに</span></p>
							<p class="tel_b">お電話の確認まで<br>完了してください</p>
							</div>
							<p class="kome">※2</p>
						</li>
						<li class="li_b">
							<div>
							<p class="deribdr-1"><img class="bg_b" alt="３日仕上げで間に合います" src="img/day2_b.png" width="45%"></p>
							<p class="otodoke_b"><span></span>にお届可能</p>
							<p><span class="mm"><?php echo $main_month[0]; ?>/</span><span class="dd"><?php echo $main_day[0]; ?></span></p>
							<p class="time_b">13:00<span>までに</span></p>
							<p class="tel_b">お電話の確認まで<br>完了してください</p>
							</div>
							<p class="kome">※3</p>
						</li>
					</ul>
					<p class="clearfix"></p>
				</div>
				<div id="cal_date_b1" class="cal_date">
					<h4 class="heading4"><span>2014/4/10</span>にお届するには、</h4>
					<ul class="cal_blue">
						<li class="li_b0">
							<div>
							<p class="deribdr-1"><img class="bg_b" alt="当日仕上げでも間に合います" src="img/day0_b0.png" width="45%"></p>
							<p class="otodoke_b"><span></span>にお届可能</p>
							<p><span class="mm"><?php echo $main_month[3]; ?>/</span><span class="dd"><?php echo $main_day[3]; ?></span></p>
							<p class="time_b">12:00<span>までに</span></p>
							<p class="tel_b">お電話の確認まで<br>完了してください</p>
							</div>
							<p class="kome">※1</p>
						</li>
						<li class="li_b">
							<div>
							<p class="deribdr-1"><img class="bg_b" alt="翌日仕上げで間に合います" src="img/day1_b.png" width="45%"></p>
							<p class="otodoke_b"><span></span>にお届可能</p>
							<p><span class="mm"><?php echo $main_month[0]; ?>/</span><span class="dd"><?php echo $main_day[0]; ?></span></p>
							<p class="time_b">13:00<span>までに</span></p>
							<p class="tel_b">お電話の確認まで<br>完了してください</p>
							</div>
							<p class="kome">※2</p>
						</li>
					</ul>
					<p class="clearfix"></p>
				</div>
				<div id="cal_date_b0" class="cal_date">
					<h4 class="heading4"><span>2014/4/10</span>にお届するには、</h4>
					<ul class="cal_blue">
						<li class="li_b">
							<div>
							<p class="deribdr-1"><img class="bg_b" alt="当日仕上げで間に合います" src="img/day0_b.png" width="45%"></p>
							<p class="otodoke_b"><span></span>にお届可能</p>
							<p><span class="mm"><?php echo $main_month[0]; ?>/</span><span class="dd"><?php echo $main_day[0]; ?></span></p>
							<p class="time_b">12:00<span>までに</span></p>
							<p class="tel_b">お電話の確認まで<br>完了してください</p>
							</div>
							<p class="kome">※1</p>
						</li>
						<li class="li_b_msg">
							まずはお電話ください！
						</li>
					</ul>
					<p class="clearfix"></p>
				</div>


				<ul class="cal_red">
					<li>
				<p class="btnOrder_wrap"><a href="http://www.staff-tshirt.com/contact/staff_contact/" id="btnnvy">お問い合わせ</a></p></li>
					<li>
				<p class="btnOrder_wrap"><a href="/order/" id="btngrn">お申込み</a></p></li>
				</ul>
				
				
				
				<div class="note">
					(※１) <span>まずはお電話でご相談ください。</span>アイテムは Tシャツ<a href="/items/itemdetail.php?c=t-shirts&i=4">085-CVT</a> の白か黒に限ります（サイズはS～XL）。<br>
					(※１) 当日仕上げの場合は<span>12:00</span>を過ぎると翌日扱いになります。<br>
					(※２) 翌日仕上げの場合は、通常料金の<span>1.5倍</span>になります。<br>
					(※３) 2日仕上げの場合は、通常料金の<span>1.3倍</span>になります。
				</div>
			</div>






<p class="oisogi">お急ぎの方はこちら！</p>
<div id="bigbanner"><a href="#schedule_wrapper"><img src="../img/banner/sokuzitsu.png" width="100%"></a></div>


			<div id="other_info">
				<div id="condition" class="plan_wrapper">
					<div>
						
						
						
						
						<h2 class="heading2_otodoke">ご注文確定条件</h2>
					</div>
					<p class="big">13:00までに<font color="red"><b>以下の3つ</b></font>を<font color="red"><b>電話</b></font>で確認してください！<br><font color="#001D87">13:00を過ぎると翌日扱いになります。</font></p>
					
					<ol class="plan_list">
					<img src="../delivery/img/st_point.jpg" width="100%">
					</ol>
					<p class="note"><span>※</span> 期間によってはキャンセルや枚数変更はできないことがあります。</p>
					<p class="note"><span>※</span> <font color="red"><b>当日仕上げの場合は12:00まで</b></font>にご注文が確定していることが必要です。</p>
				</div>

				<div id="extracharge" class="plan_wrapper">
					<div>
						<h2 class="heading2_otodoke">納期</h2>
					</div>
					<img src="../delivery/img/bigbanner_nouki.jpg" width="100%">
					<p class="nouki_big">なぜ最速短納期で届けられるの？</p>
						<ul class="nouki_list">
							<li><font color="#981B20"><b>1.</b></font>自社工場だから作成が早い！</li>
							<li><font color="#981B20"><b>2.</b></font>通常でも3日仕上げだから早い！</li>
							<li><font color="#981B20"><b>3.</b></font>直接引取りに来ることが可能！</li>
						</ul>
						
					<h3 class="ryoukin">料金について</h3>
					<p class="big">通常3日仕上げより早いお届けをご希望の場合は、<font color="red"><b>割増料金</b></font>がかかります。<br></p>
					<img src="../delivery/img/hayasa.jpg" width="100%">
					
					<p class="tyuusyaku"><span>※</span>使える割引は、イラレ割とブログ割となり、その他の割引は適用されません。</p>
				</div>
				
				
				
				<div id="schedule_wrapper" class="plan_wrapper">
					
					<div id="tokyuutitle">
						<h2 class="heading2_hayai"><img src="../img/banner/sokuzitsu.png" width="100%"></h2>
					</div>
					
					<div class="heading"><img src="./img/meritto.jpg" width="100%" class="leftdrop"><p class="textnon">3つのメリット</p></div>
						<ol class="inflist">
						<li><p><span class="stgbl">1.&emsp;<img src="./img/saisoku.png" width="29%" class="imgA"></span><br>&emsp;&emsp;全国当日出荷 … 最短6時間仕上げ</p></li>
						<li><p><span class="stgbl">2.&emsp;<img src="./img/anshin.png" width="21%" class="imgB"></span><br>&emsp;&emsp;来社引き取り可能！（17時～18時）</p></li>
						<li><p><span class="stgbl">3.&emsp;<img src="./img/jinsoku.png" width="20%" class="imgC"></span><br>&emsp;&emsp;東京近郊の方はバイク便のご利用で当日到着も可能！</p><p class="rgttxt"><span class="red">※</span>お届けが、2日以上かかる地域もございます。</p></li>
						</ol>

						<div class="heading"><img src="./img/jouken.jpg" width="100%"  class="leftdrop"><p class="textnon">3つの条件</p></div>
						<ol class="inflist">
						<li><p><span class="stg">1.&emsp;12時までに注文確定!</span><br>&emsp;&emsp;データ入稿が完了している・お電話で注文確定している。</p></li>
						<li><p><span class="stg">2.&emsp;1番人気のTシャツのみ対応可</span><br>&emsp;&emsp;CVT-085白と黒(Sサイズ～XL)&emsp;522-FTタオルの白色(フリーサイズ) </p></li>
						<li><p><span class="stg">3.&emsp;お支払い方法</span><br>&emsp;&emsp;「代金引換」「銀行振込」「カード決済」になります。</p>
						<p class="rgttxt"><span class="red">※</span>銀行振込・カード決済に限りご入金確認後、商品を発送致します。</p></li>
						</ol>
						
						
						
						<div id="oisogi2">
						<p class="oomoji">お急ぎの方はこちら！</p>
						<div class="sankaku"></div>
						<ul class="oisogi2_list">
							<li>不明点等ありましたら、まずはお電話ください！</li>
							<li>アイテム・デザインデータがお決まりの方は<br>こちらからすぐにお申し込みできます！</li>
							<li><a href="tel:0120130428"><img src="img/tel.png" width="120%" onmouseover="this.src='/delivery/img/tel_2.png'"onmouseout="this.src='/delivery/img/tel.png'"></a></li>
							<li><a href="http://www.staff-tshirt.com/contact/staff_contact/"><img src="img/toiawase.png" width="120%" onmouseover="this.src='/delivery/img/toiawase_2.png'"onmouseout="this.src='/delivery/img/toiawase.png'"></a></li>
						</ul>
						
						<p style="text-align: right;font-size: 13px;">お問い合わせフォームの欄で当日特急にしてください！</p>
						
						</div>
						
						
						<div class="content-lv3">
							<div class="heading"><img src="../delivery/img/schedule.png" width="100%"  class="lines-on-sides"><p class="textnon">プラン別発送スケジュール</p></div>		
						<p class="ptxt">お客様のご要望に合わせた4つの仕上げプランの中で当日特急プランが一番早い！</p>
						<p class="samday"><img src="./img/plan.png" width="80%"  alt="発送プラン"></p>
						<p class="daytxt"><span class="red">※</span>発送は東京からしております。<br><span class="red">※</span>お届けが、2日以上かかる地域もございます。</p>
						</div>
		
						<div class="content-lv3">
							<div class="heading"><img src="../delivery/img/price.png" width="100%"  class="lines-on-sides"><p class="textnon">料金目安</p></div>
							<p class="ptxt2">アイテムの枚数とサイズ、プリントの色数と方法でおおよその1枚の料金目安をご確認できます。</p>
							<p class="samday"><img src="./img/fee.png" width="80%" alt="料金目安"></p>
							<p class="daytxt"><span class="red">※</span>在庫不足の場合、カラー・サイズの変更をお願いする場合がございます。</p>
							<div class="ong">			
								<p>デザイン<span class="bktxt">と</span>Tシャツ枚数<span class="bktxt">をお知らせください。</span></p>
								<p>すぐにお見積もり致します！</p>
							</div>
						</div>
				</div>
				
				<div class="box_c">
					<div class="bg">
						<p>
							<span>お気軽にお問い合わせください</span>
							<img alt="フリーダイヤル" src="/common/img/head_contact_txt01.png" />
							<img alt="FAX" src="/common/img/head_contact_txt02.png" />
						</p>
					</div>
				</div>
			</div>


	
				<p class="scroll_top"><a href="#head1">納期・お届け日　ページトップへ</a></p>

	</section>
	<?php include '../common/side_nav.html'; ?>
</div>
<?php include '../common/footer.html'; ?>
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