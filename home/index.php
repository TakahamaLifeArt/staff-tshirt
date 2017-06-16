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
	*	[通常、2日仕上げ、翌日仕上げ、当日仕上げ]
	*/
	$time_stamp = time()+39600;
	$today = date('Y年n月j日', $time_stamp);
	$timeLimit = array('13:00', '13:00', '13:00', '11:00');
	$serviceName = array('通常３日仕上げ', '通常２日仕上げ', '翌日仕上げ', '当日特急');
	for($cnt=4,$idx=0; $cnt>0; $cnt--,$idx++){
		$fin = getDelidate(null, 1, $cnt);
		$m0 = $fin['Month']<10? 0: 1;
		$m1 = $fin['Month']<10? $fin['Month']: $fin['Month']-10;
		$d0 = $fin['Day']<10? 0: substr($fin['Day'], 0, 1);
		$d1 = $fin['Day']<10? $fin['Day']: substr($fin['Day'], 1);
		
		$dat .= '<li';
		if($cnt!=4){
			$dat .= ' class="hide"';
		}
		$dat .= '>'.$today.'　'.$timeLimit[$idx].'時までにお電話での確認まで完了した場合<br>'.$serviceName[$idx].'で';
		$dat .= '<div class="datewrap">';
		$dat .= '<span class="date">'.$m0.'</span><span class="date">'.$m1.'</span><span class="date2">月</span>';
		$dat .= '<span class="date">'.$d0.'</span><span class="date">'.$d1.'</span><span class="date2">日</span>';
		// 	$dat .= '<span class="date">'.mb_convert_encoding($fin['weekname'], 'utf-8', 'euc-jp').'</span>'; 
		$dat .= '<span class="date3">にお届けできます。</span>';
		$dat .= '</div>';
		$dat .= '</li>';
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



	require_once $_SERVER['DOCUMENT_ROOT'].'/php_libs/orders.php';
	$order = new Orders();
	
	for($cnt=1,$idx=0; $cnt<5; $cnt++,$idx++){
		$fin = $order->getDelidate(null, 1, $cnt, 'simple');
		$main_month[$idx] = $fin['Month'];
		$main_day[$idx] = $fin['Day'];
	}

?>
<!DOCTYPE html>
<html lang="ja">

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
		<meta charset="utf-8">
		<meta name="description" content="スタッフTシャツを最短即日（当日）発送します！Tシャツの文字入れのプリントは業界一早いです。1枚でも量産でも短納期で作成します。スタッフTシャツ・パーカー・ブルゾンの作成は短納期で早いスタッフTシャツ屋へ！">
		<meta name="keywords" content="スタッフTシャツ,作成,短納期">
		<meta name="viewport" content="width=device-width,user-scalable=no,maximum-scale=1">
		<title>スタッフTシャツを短納期で作成【即日】｜スタッフTシャツ屋</title>
		<link rel="shortcut icon" href="/icon/favicon.ico">
		<link rel="stylesheet" type="text/css" href="css/reset.css" media="all">
		<link rel="stylesheet" type="text/css" href="css/flexnav1.css" media="all">
		<link rel="stylesheet" type="text/css" href="css/slider-pro.min.css" media="screen">
		<link rel="stylesheet" type="text/css" href="css/examples.css" media="screen">
		<link href='//fonts.googleapis.com/css?family=Open+Sans:400,600' rel='stylesheet'>
		<link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
		<link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" rel="stylesheet">
		<link rel="stylesheet" type="text/css" href="css/common.css" media="all">
		<link rel="stylesheet" type="text/css" href="css/main.css" media="all">
		<link rel="stylesheet" type="text/css" href="css/media-style.css" media="all">
		<link href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css" rel="stylesheet">
		<link href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css" rel="stylesheet">
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
		<script src="//code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
		<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
		<script src="js/jquery.flexnav.js"></script>
		<script src="js/jquery.sliderPro.min.js"></script>
		<script src="/common2/js/tlalib.js"></script>
		<script src="js/top1.js"></script>
		<script src="js/home.js"></script>
		<script>
			(function(i, s, o, g, r, a, m) {
				i['GoogleAnalyticsObject'] = r;
				i[r] = i[r] || function() {
					(i[r].q = i[r].q || []).push(arguments)
				}, i[r].l = 1 * new Date();
				a = s.createElement(o),
					m = s.getElementsByTagName(o)[0];
				a.async = 1;
				a.src = g;
				m.parentNode.insertBefore(a, m)
			})(window, document, 'script', 'https://www.google-analytics.com/analytics.js', 'ga');
			ga('create', 'UA-11155922-7', 'auto');
			ga('send', 'pageview');
		</script>
	</head>

	<body>

		<!-- Google Tag Manager (noscript) -->
		<noscript>
			<iframe src="https://www.googletagmanager.com/ns.html?id=GTM-PLQMXT" height="0" width="0" style="display:none;visibility:hidden"></iframe>
		</noscript>
		<!-- End Google Tag Manager (noscript) -->

		<div id="head1">
			<h1>スタッフTシャツを短納期で作成！</h1>
		</div>

		<?php include 'common/global_nav.html'; ?>

		<div id="mainwrap">
			<section id="main">
				<div id="mainvisual">
					<div id="example3" class="slider-pro">
						<div class="sp-slides">
							<div class="sp-slide">
								<a href="http://www.staff-tshirt.com/lineup/lineup.php"onclick="ga('send','event’,'item','click','slider');">
									<img class="sp-image" src="../img/top/st_main_01_1.jpg" data-src="../img/top/st_main_01_1.jpg" />
								</a>

								<!-- <p class="sp-layer sp-white sp-padding"
					data-horizontal="50" data-vertical="50"
					data-show-transition="left" data-show-delay="400">
					Lorem ipsum
				</p> 

				<p class="sp-layer sp-black sp-padding"
					data-horizontal="180" data-vertical="50"
					data-show-transition="left" data-show-delay="600">
					dolor sit amet
				</p>

				<p class="sp-layer sp-white sp-padding"
					data-horizontal="315" data-vertical="50"
					data-show-transition="left" data-show-delay="800">
					consectetur adipisicing elit.
				</p> -->
							</div>

							<div class="sp-slide">
								<a href="http://www.staff-tshirt.com/price/estimate.php"onclick="ga('send','event’,'10-estimate','click','slider');">
									<img class="sp-image" src="../img/top/st_main_02_2.jpg" data-src="../img/top/st_main_02_2.jpg" data-small="../img/top/st_main_02_2.jpg" data-medium="../img/top/st_main_02_2.jpg" data-large="../img/top/st_main_02_2.jpg" data-retina="../img/top/st_main_02_2.jpg" />
								</a>

								<!-- <h3 class="sp-layer sp-black sp-padding" 
					data-horizontal="40" data-vertical="40" 
					data-show-transition="left">
					Lorem ipsum dolor sit amet
				</h3>

				<p class="sp-layer sp-white sp-padding" 
					data-horizontal="40" data-vertical="100" 
					data-show-transition="left" data-show-delay="200">
					consectetur adipisicing elit
				</p> 

				<p class="sp-layer sp-black sp-padding" 
					data-horizontal="40" data-vertical="160" data-width="350" 
					data-show-transition="left" data-show-delay="400">
					sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
				</p>  -->
							</div>

							<div class="sp-slide">
								<a href="http://www.staff-tshirt.com/contact/request.html"onclick="ga('send','event’,'request','click','slider');">
									<img class="sp-image" src="../img/top/st_main_03.jpg" data-src="../img/top/st_main_03.jpg" data-small="../img/top/st_main_03.jpg" data-medium="../img/top/st_main_03.jpg" data-large="../img/top/st_main_03.jpg" data-retina="../img/top/st_main_03.jpg" />
								</a>

								<!--  <p class="sp-layer sp-white sp-padding" 
					data-position="centerCenter" data-vertical="-50" 
					data-show-transition="right" data-show-delay="500" >
					Lorem ipsum dolor sit amet
				</p>

				<p class="sp-layer sp-black sp-padding" 
					data-position="centerCenter" data-vertical="50" 
					data-show-transition="left" data-show-delay="700">
					consectetur adipisicing elit
				</p>  -->
							</div>

							<div class="sp-slide">
								<a href="http://www.staff-tshirt.com/delivery/index.php"onclick="ga('send','event’,'nouki','click','slider');">
									<img class="sp-image" src="../img/top/st_main_04.jpg" data-src="../img/top/st_main_04.jpg" data-small="../img/top/st_main_04.jpg" data-medium="../img/top/st_main_04.jpg" data-large="../img/top/st_main_04.jpg" data-retina="../img/top/st_main_04.jpg" />
								</a>

								<!--  <p class="sp-layer sp-black sp-padding"
					data-position="bottomLeft" data-vertical="0" data-width="100%"
					data-show-transition="up">
					Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
				</p>  -->
							</div>

						</div>

						<div class="sp-thumbnails">
							<img class="sp-thumbnail" src="../img/top/st_mini_01.jpg" />
							<img class="sp-thumbnail" src="../img/top/st_mini_02.jpg" />
							<img class="sp-thumbnail" src="../img/top/st_mini_03.jpg" />
							<img class="sp-thumbnail" src="../img/top/st_mini_04.jpg" />
						</div>
					</div>
				</div>




				<!--<div class="blockclntent">
	
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
		</div> -->

				<!--<div id="flowimg" class="content-lv3">
			<a href="/price/estimate.php"><img src="img/top/st_estimate.jpg" width="100%"  alt="10秒見積もり"></a>
		</div> -->




				<div class="next_button">
					<ul>
						<li>
							<a href="http://www.staff-tshirt.com/price/estimate.php"onclick="ga('send','event’,'10-estimate','click','top');"><img src="../img/top/st_r_01.jpg" width="100%"></a>
						</li>
						<li>
							<a href="http://www.staff-tshirt.com/contact/request.html"onclick="ga('send','event’,'request','click','top');"><img src="../img/top/st_r_02.jpg" width="100%"></a>
						</li>
						<li>
							<a href="http://www.staff-tshirt.com/nagare/index.php"onclick="ga('send','event’,'order-flow','click','top');"><img src="../img/top/st_r_03.jpg" width="100%"></a>
						</li>
					</ul>
				</div>

				<div id="step">
					<img src="img/top/st_step.jpg" width="100%" alt="5ステップ">
				</div>
				
 				<div id="option">
				<h3 id="Line005">【アイテム価格改定のお知らせ】</h3>
						<p>6月22日(木)より下記のブランドのアイテムが価格改定となります。<br>
							「GILDAN」<br>
							改定前に御見積りいただいた場合でも、ご注文確定が6月22日(木)以降になりますと改定後の価格となりますのでご注意くださいませ。<br>
							※リピート注文の場合も改定後は改定価格でのご提供となりますのでご了承ください。
						</p>

				</div> 	

				<div id="step_2">
					<img src="img/top/st_step_2.jpg" width="100%" alt="5ステップsp">
				</div>

			<div id="delivery_date_main">
				<img src="../img/top/st_nouki_main.jpg" width="100%" id="deri_topttl">
				<p class="nouki_title">面倒な納期計算がワンクリックで自動計算！</p>
				<p class="nouki_button">
					<a href="http://www.staff-tshirt.com/delivery/index.php"><img src="../img/st_question.png">納期を詳しく見る</a>
				</p>
				
				<div id="delivery_date_wrapper_1">
					<p class="nouki_sel"><img src="../img/st_b_1.png">お届けの地域</p>

					<div class="select">
						<select name="都道府県" id="destination">
							<option value="">都道府県をお選びください。</option>  
							<optgroup label="北海道・東北">  
								<option data-destination="2" value="北海道">北海道</option>  
								<option data-destination="2" value="青森県">青森県</option>  
								<option data-destination="2" value="秋田県">秋田県</option>  
								<option data-destination="2" value="岩手県">岩手県</option>  
								<option data-destination="2" value="山形県">山形県</option>  
								<option data-destination="2" value="宮城県">宮城県</option>  
								<option data-destination="2" value="福島県">福島県</option>  
							</optgroup>  
							<optgroup label="甲信越・北陸">  
								<option data-destination="1" value="山梨県">山梨県</option>  
								<option data-destination="1" value="長野県">長野県</option>  
								<option data-destination="1" value="新潟県">新潟県</option>  
								<option data-destination="1" value="富山県">富山県</option>  
								<option data-destination="1" value="石川県">石川県</option>  
								<option data-destination="1" value="福井県">福井県</option>  
							</optgroup>  
							<optgroup label="関東">  
								<option data-destination="1" value="茨城県">茨城県</option>  
								<option data-destination="1" value="栃木県">栃木県</option>  
								<option data-destination="1" value="群馬県">群馬県</option>  
								<option data-destination="1" value="埼玉県">埼玉県</option>  
								<option data-destination="1" value="千葉県">千葉県</option>  
								<option data-destination="1" value="東京都">東京都</option>  
								<option data-destination="1" value="神奈川県">神奈川県</option>  
							</optgroup>  
							<optgroup label="東海">  
								<option data-destination="1" value="愛知県">愛知県</option>  
								<option data-destination="1" value="静岡県">静岡県</option>  
								<option data-destination="1" value="岐阜県">岐阜県</option>  
								<option data-destination="1" value="三重県">三重県</option>  
							</optgroup>  
							<optgroup label="関西">  
								<option data-destination="1" value="大阪府">大阪府</option>  
								<option data-destination="1" value="兵庫県">兵庫県</option>  
								<option data-destination="1" value="京都府">京都府</option>  
								<option data-destination="1" value="滋賀県">滋賀県</option>  
								<option data-destination="1" value="奈良県">奈良県</option>  
								<option data-destination="1" value="和歌山県">和歌山県</option>  
							</optgroup>  
							<optgroup label="中国">  
								<option data-destination="1" value="岡山県">岡山県</option>  
								<option data-destination="1" value="広島県">広島県</option>  
								<option data-destination="1" value="鳥取県">鳥取県</option>  
								<option data-destination="1" value="島根県">島根県</option>  
								<option data-destination="1" value="山口県">山口県</option>  
							</optgroup>  
							<optgroup label="四国">  
								<option data-destination="1" value="徳島県">徳島県</option>  
								<option data-destination="1" value="香川県">香川県</option>  
								<option data-destination="1" value="愛媛県">愛媛県</option>  
								<option data-destination="1" value="高知県">高知県</option>  
							</optgroup>  
							<optgroup label="九州・沖縄">  
								<option data-destination="2" value="福岡県">福岡県</option>  
								<option data-destination="2" value="佐賀県">佐賀県</option>  
								<option data-destination="2" value="長崎県">長崎県</option>  
								<option data-destination="2" value="熊本県">熊本県</option>  
								<option data-destination="2" value="大分県">大分県</option>  
								<option data-destination="2" value="宮崎県">宮崎県</option>  
								<option data-destination="2" value="鹿児島県">鹿児島県</option>  
								<option data-destination="2" value="沖縄県">沖縄県</option>  
							</optgroup>  
						</select>
					</div>

					<p class="nouki_sel"><img src="../img/st_b_2.png">届いて欲しい日</p>
					<div class="calendar" id="datepicker"></div>
					<p class="nouki_note"><span>※</span>発送は東京からしております。<br><span>※</span>お届けが2日以上かかる地域もございます。</p>

				</div>

				<div id="delivery_date_wrapper">
					<ul>
						<li class="delivery_box1">
							<div class="lft_box">
							<img src="../img/top/st_answer_a_01.png">
								<div id="delidate_1" class="delivery_date_box"></div>
							</div>
							<div class="rit_box"><img src="../img/top/st_answer_b_01.png"></div>
						</li>
						
						<li class="delivery_box2">
							<div class="lft_box">
							<img src="../img/top/st_answer_a_02.png">
								<div id="delidate_2" class="delivery_date_box"></div>
							</div>
							<div class="rit_box"><img src="../img/top/st_answer_b_02.png"></div>
						</li>
						
						<li class="delivery_box3">
							<div class="lft_box">
							<img src="../img/top/st_answer_a_03.png">
								<div id="delidate_3" class="delivery_date_box"></div>
							</div>
							<div class="rit_box"><img src="../img/top/st_answer_b_03.png"></div>
						</li>
						
						<li class="delivery_box4">
							<div class="lft_box">
							<img src="../img/top/st_answer_a_04.png">
								<div id="delidate_4" class="delivery_date_box"></div>
							</div>
							<div class="rit_box"><img src="../img/top/st_answer_b_04.png"></div>
						</li>
					</ul>
					<a href="http://www.staff-tshirt.com/delivery/index.php"><span class="toha">注文確定とは</span></a>
				</div>


				<div id="plandays">
					<ul class="tab">
						<li class="select">
							<p>通常<span>３日</span>仕上げ</p>
						</li>
						<li><span>２日</span>仕上げ</li>
						<li><span>翌日</span>仕上げ</li>
						<li><span>当日</span>特急</li>
					</ul>
					<ul class="content">
						<?php echo $dat; ?>
					</ul>
					<p class="nouki_button_2"><a href="http://www.staff-tshirt.com/delivery/index.php"onclick="ga('send','event’,'nouki','click','top-nouki');">納期を詳しく見る</a></p>
				</div>

				<div id="delivery_date_wrapper_2">
					<ul class="time_plan">
						<a href="http://www.staff-tshirt.com/order/"onclick="ga('send','event’,'order','click','top-nouki');">
							<li class="first">お申し込み</li>
						</a>
						<a href="http://www.staff-tshirt.com/contact/staff_contact/"onclick="ga('send','event’,'contact','click','top-nouki');">
							<li class="second">お問い合わせ</li>
						</a>
					</ul>
				</div>
				
			</div>



				<!--<div id="delivery_date1">
                    <p class="txt1">今すぐ
                        <span class="red">注文確定</span>した場合の<span class="red">お届け日</span>
                    </p>
                    
                       <div class="time">
	                    <ul class="box_time" style="height: 202px">
		                    <li class="block1">
			                    <section class="round1">
			                    <div class="block_sq">
			                     <p class="text"><span class=red>当日</span><br>特急</p>
			                     
			                    </div>
			                    <div class="block_sq1">
			                    <div class="block_sq2">
			                    <div class="block_sq2">
					                    <table class="bk_table" style="width:130px">
											<tr id="result_date4">
												<td class="dt"><p><span class="mm"><?php echo $main_month[0]; ?></span></p>
												<td style="width: 15px">/</td>
												<td class="dt"><p><?php echo $main_day[0]; ?></span></p></td>
											</tr>
										</table>
			                    	</div>
			                    </div>
			                    </div>
		                    	</section>
		                    </li>
		                    <li class="block1">
			                    <section class="round2">
			                    <div class="block_sq">
			                     <p class="text"><span class=red>翌日</span><br>仕上げ</p>
			                    </div>
			                    <div class="block_sq1">
			                    	<div class="block_sq2">
					                    <table class="bk_table" style="width:130px">
											<tr id="result_date3">
												<td class="dt"><p><span class="mm"><?php echo $main_month[1]; ?></span></p></td>
												<td style="width: 15px">/</td>
												<td class="dt"><p><span class="dd"><?php echo $main_day[1]; ?></span></p></td>
											</tr>
										</table>
			                    	</div>
			                    </div>
		                    	</section>
	                    		</li>
								<li class="block1">
			                    <section class="round3">
			                    <div class="block_sq">
			                    <p class="text"><span class=red>2日</span><br>仕上げ</p>
			                    </div>
			                    <div class="block_sq1">
			                    	<div class="block_sq2">
			                    <div class="block_sq2">
					                    <table class="bk_table" style="width:130px">
											<tr id="result_date2">
												<td class="dt"><p><span class="mm"><?php echo $main_month[2]; ?></span></p></td>
												<td style="width: 15px">/</td>
												<td class="dt"><p><span class="dd"><?php echo $main_day[2]; ?></span></p></td>
											</tr>
										</table>
			                    	</div>
			                    </div>
			                    </div>
		                    	</section>
	                    		</li>
								<li class="block1">
			                    <section class="round4">
			                    <div class="block_sq">
			                    <p class="text">通常<span class=red>3日</span><br>仕上げ</p>
			                    </div>
			                    <div class="block_sq1">
			                    	<div class="block_sq2">
			                    	<table class="bk_table" style="width:130px">
											<tr id="result_date">
												<td class="dt"><p class="date"><?php echo $fin['Month'];?></p></td>
												<td style="width: 15px">/</td>
												<td class="dt"><p class="date"><?php echo $fin['Day'];?></p></td>
											</tr>
										</table>
			                    </div>
			                    </div>
		                    	</section>
	                    		</li>
	                    </ul>
		            </div>
  							 <section>
			                    <table class="box_time">
									<tr>
										<td class="sp" style="width: 140px; height: 42px;"><p class="nouki">注文〆12時まで</p></td>
										<td class="sp1" style="height: 42px"></td>
										<td class="sp2" style="height: 42px"><p class="nouki">注文〆13時まで</p></td>
									</tr>
								</table>
								          <div class="block_sq">
			                    <p class="text"><span class=red>※</span>通常3日仕上げ以外のプランは特急料金がかかります。</p>	
			                    		                    </div>
							</section>	-->

				<div class="content-lv3">
					<div class="orderbtn">
					
						<h3>アイテム</h3>
						<p>スタッフTシャツ屋はオリジナルスタッフユニフォームを作成する為に厳選されたアイテムを扱っています。</p>
						<p>どんなシーンにも対応できるラインナップなので手軽に楽しんでご利用いただけます！高品質ながらお求めやすい価格のアイテムを即日お届け!</p>
					
						<ul>
							<li>
								<a href="/lineup/lineup.php?c=t-shirts" onclick="ga('send','event’,'contact','click','head');"><img src="/img/item/st_item_tshirt.jpg" width="100%" alt="Tシャツ"></a>
								<figcaption class="item_text">定番品から仕事着・イベント向けまで、素材や着心地にこだわったスタッフTシャツを、サイズや色数を豊富に取り揃えています。
								</figcaption>
							</li>
							<li>
								<a href="/lineup/lineup.php?c=polo-shirts" onclick="ga('send','event’,'contact','click','head');"><img src="/img/item/st_item_poro.jpg" width="100%" alt="パーカー"></a>
								<figcaption class="item_text">ポロシャツはTシャツに比べ、オフィスカジュアルとしても着用することができ、ビジネスの場面で活躍できるアイテムです。
								</figcaption>
							</li>
							<li>
								<a href="/lineup/lineup.php?c=tote-bag" onclick="ga('send','event’,'contact','click','head');"><img src="/img/item/st_item_bag.jpg" width="100%" alt="ブルゾン・ジャンパー"></a>
								<figcaption class="item_text">普段でもノベルティグッズとしても使えるトートバッグは、プリント映えする無地のものから肩掛けなど豊富にご用意しております。
								</figcaption>
							</li>
							<li>
								<a href="/lineup/lineup.php?c=sportswear" onclick="ga('send','event’,'contact','click','head');"><img src="/img/item/st_item_Sports.jpg" width="100%" alt="ポロシャツ"></a>
								<figcaption class="item_text">着心地、吸汗速乾にこだわったスポーツウェアは社内での行事や地域のスポーツ大会など様々な場面で活躍します。
								</figcaption>
							</li>
							<li>
								<a href="/lineup/lineup.php?c=sweat" onclick="ga('send','event’,'contact','click','head');"><img src="/img/item/st_item_sweat.jpg" width="100%" alt="スポーツウェア"></a>
								<figcaption class="item_text">吸汗に優れている素材を使用しているため、部屋着や寝間着としてだけではなく、作業着としても着用できるアイテムです。
								</figcaption>
							</li>
							<li>
								<a href="/lineup/lineup.php?c=outer" onclick="ga('send','event’,'contact','click','head');"><img src="/img/item/st_item_blouson.jpg" width="100%" alt="ロンT"></a>
								<figcaption class="item_text">ブルゾン・ジャンパーは展示会やイベントなどの多くの場面で活躍するアイテムです。種類・色数豊富に取り揃えております。
								</figcaption>
							</li>
							<li>
								<a href="/lineup/lineup.php?c=cap" onclick="ga('send','event’,'contact','click','head');"><img src="/img/item/st_item_cap.jpg" width="100%" alt="タオル"></a>
								<figcaption class="item_text">スタッフTシャツ屋では、サンバイザーやワークキャップなど幅広くご用意しております。ワンポイントとして人気の高いアイテム。
								</figcaption>
							</li>
							<li>
								<a href="/lineup/lineup.php?c=long-shirts" onclick="ga('send','event’,'contact','click','head');"><img src="/img/item/st_item_lont.jpg" width="100%" alt="トートバッグ"></a>
								<figcaption class="item_text">ロングTシャツはフルシーズンで活躍する人気の高いアイテムです。7分袖やラグランTシャツなど数多くご用意しました。
								</figcaption>
							</li>
							<li>
								<a href="/lineup/lineup.php?c=towel" onclick="ga('send','event’,'contact','click','head');"><img src="/img/item/st_item_towel.jpg" width="100%" alt="エプロン"></a>
								<figcaption class="item_text">ハンドタオルからバスタオルまで、スポーツイベントなどでは欠かせないタオルをお手軽に作成できます。
								</figcaption>
							</li>
							<li>
								<a href="/lineup/lineup.php?c=apron" onclick="ga('send','event’,'contact','click','head');"><img src="/img/item/st_item_apron.jpg" width="100%" alt="つなぎ"></a>
								<figcaption class="item_text">飲食店のユニフォームとしてお店の雰囲気にぴったり合ったオリジナルエプロンを作ることができます。
								</figcaption>
							</li>
							<li>
								<a href="/lineup/lineup.php?c=overall" onclick="ga('send','event’,'contact','click','head');"><img src="/img/item/st_item_tsunagi.jpg" width="100%" alt="ワークウェア"></a>
								<figcaption class="item_text">職場の仲間と作業服としても使えるオリジナルつなぎはいかがですか？夏場でも快適な半袖つなぎもご用意しております。
								</figcaption>
							</li>
							<li>
								<a href="/lineup/lineup.php?c=workwear" onclick="ga('send','event’,'contact','click','head');"><img src="/img/item/st_item_work.jpg" width="100%" alt="キャップ"></a>
								<figcaption class="item_text">抗菌防臭機能付きの上品な仕上がりのオックスフォードシャツや、チノパンなど、働きやすい着心地にこだわった商品を取り揃えております。
								</figcaption>
							</li>
						</ul>
					</div>
				</div>

				<div class="content-lv3">
					<div class="orderbtn">
					
						<h3>用途から選ぶ</h3>
						<p>厳選した6つの用途からおすすめなTシャツを紹介しています！プリント方法ごとの価格例を参考に、迷わず商品をお選び頂けます。</p>
					
						<ul>
							<li class="seence">
								<p class="youto_ttl">イベント</p>
								<a href="/scene/event.html" onclick="ga('send','event’,'contact','click','head');"><img src="/img/top/st_use_event.jpg" width="100%" alt="イベント"></a>
							</li>

							<li class="seence">
								<p class="youto_ttl">介護福祉</p>
								<a href="/scene/care.html" onclick="ga('send','event’,'contact','click','head');"><img src="/img/top/st_use_medical.jpg" width="100%" alt="介護福祉"></a>
							</li>

							<li class="seence">
								<p class="youto_ttl">ボランティア</p>
								<a href="/scene/volunteer.html" onclick="ga('send','event’,'contact','click','head');"><img src="/img/top/st_use_volunteer.jpg" width="100%" alt="ボランティア"></a>
							</li>

						</ul>
					
						<ul>
							<li class="seence">
								<p class="youto_ttl">フードユニフォーム</p>
								<a href="/scene/food.html" onclick="ga('send','event’,'contact','click','head');"><img src="/img/top/st_use_food.jpg" width="100%" alt="フード"></a>
							</li>
							<li class="seence">
								<p class="youto_ttl">ワークウェア</p>
								<a href="/scene/work.html" onclick="ga('send','event’,'contact','click','head');"><img src="/img/top/st_use_work.jpg" width="100%" alt="ワーク"></a>
							</li>
							<li class="seence">
								<p class="youto_ttl">グッズ</p>
								<a href="/scene/goods.html" onclick="ga('send','event’,'contact','click','head');"><img src="/img/top/st_use_goods.jpg" width="100%" alt="グッズ"></a>
							</li>

						</ul>
					</div>
				</div>


				<div class="content-lv3">
					<div class="orderbtn">
					
						<h3>新しいアイテム</h3>
						<p>Tシャツ、シャツ、エプロンなど季節に合った新商品をご紹介!流行りのアイテムにもプリント、スタッフユニフォームを短納期でお届け!</p>
					
						<ul>
							<li class="seence">
								<a href="/items/itemdetail.php?c=t-shirts&i=655" onclick="ga('send','event’,'contact','click','head');"><img src="/img/top/st_new_01.jpg" width="100%" alt="Tシャツ" onmouseover="this.src='/img/top/st_new_on_01.jpg'" onmouseout="this.src='/img/top/st_new_01.jpg'"></a>
							</li>
							<li class="seence">
								<a href="/items/itemdetail.php?c=polo-shirts&i=642" onclick="ga('send','event’,'contact','click','head');"><img src="/img/top/st_new_02.jpg" width="100%" alt="ポロシャツ" onmouseover="this.src='/img/top/st_new_on_02.jpg'" onmouseout="this.src='/img/top/st_new_02.jpg'"></a>
							</li>
							<li class="seence">
								<a href="/items/itemdetail.php?c=workwear&i=715" onclick="ga('send','event’,'contact','click','head');"><img src="/img/top/st_new_03.jpg" width="100%" alt="シャツ" onmouseover="this.src='/img/top/st_new_on_03.jpg'" onmouseout="this.src='/img/top/st_new_03.jpg'"></a>
							</li>
						</ul>
					
						<ul>
							<li class="seence">
								<a href="/items/itemdetail.php?c=apron&i=81" onclick="ga('send','event’,'contact','click','head');"><img src="/img/top/st_new_04.jpg" width="100%" alt="エプロン" onmouseover="this.src='/img/top/st_new_on_04.jpg'" onmouseout="this.src='/img/top/st_new_04.jpg'"></a>
							</li>
							<li class="seence">
								<a href="/items/itemdetail.php?c=outer&i=567" onclick="ga('send','event’,'contact','click','head');"><img src="/img/top/st_new_05.jpg" width="100%" alt="ブルゾン" onmouseover="this.src='/img/top/st_new_on_05.jpg'" onmouseout="this.src='/img/top/st_new_05.jpg'"></a>
							</li>
							<li class="seence">
								<a href="/items/itemdetail.php?c=apron&i=580" onclick="ga('send','event’,'contact','click','head');"><img src="/img/top/st_new_06.jpg" width="100%" alt="エプロン" onmouseover="this.src='/img/top/st_new_on_06.jpg'" onmouseout="this.src='/img/top/st_new_06.jpg'"></a>
							</li>
						</ul>
					</div>
				</div>

				<div class="content-lv3">
					<div class="topdescrip topdescrip1">

						<div class="topdescrip_content">
							<h3>NEWS (お知らせ)</h3>
							<div class="newtxt">
								<p>2016.11.17 イラストレーターテンプレートを追加しました</p>
							
								<p>2016.11.17 お届日を調べるシステムを導入しました。</p>
							
								<p>2016.11.16 商品サンプルを無料でお届けするサービスができました。</p>
							
								<p>2016.11.15 簡単に10秒で見積もりができるようになりました。</p>
							
								<p>2016.11.14 新商品を追加しました。</p>
							
								<p>2016.11.13 スタッフTシャツ屋をリニューアルしました。</p>
								</div>
						</div>
					</div>

					<div class="topdescrip topdescrip2">

					</div>

					<div class="content-lv3">
						<div class="topdescrip topdescrip1">
							<div class="leftimg logo"><img src="img/top/st_seo_logo.jpg" width="100%" alt="スタッフTシャツ屋"></div>
							<div class="topdescrip_content">
								<h4>スタッフ様ユニフォームはスタッフTシャツ屋にお任せ！</h4>
								<p>スタッフTシャツが最短当日の短納期で印刷できます！スタッフTシャツ・ポロシャツ・パーカー・ ブルゾン・トートバッグ等の作成は早い・激安・高品質のタカハマライフアートへ。 企業、お店、イベントのイメージアップに役立ちます。一流アパレル技術の東京の自社工場でプリント方法もいろいろ選べます。 1枚からスタッフTシャツ、イベントTシャツなど 大口注文まで、納期、価格等ご相談に応じます。</p>

							
								<h4>スタッフTシャツ屋の特徴</h4>
								<p>早い！業界最速短納期を提供しています。 通常3日仕上げで対応、業界最速の当日仕上げを含む「特急仕上げプラン」もあり、スタッフTシャツが何処よりも早く即日で作成できます。 スタッフTシャツ・ブルゾンの作成に関わる様々なご相談には電話、メールフォーム応じています。 丁寧で早い対応を心がけていて、お客様から評価をいただいております。 また、様々なプリント方法の中で、スタッフTシャツ・ブルゾンに適しているデジタルコピー転写プリントができます。 フルカラーでデザインの再現性が高く、ロゴや写真がほぼデータ通りにプリントできます。 オリジナルスタッフユニフォームを製作してイベントを盛り上げましょう！</p>

							</div>
						</div>
					</div>

					<div class="totop"><a href="#head1">スタッフTシャツ屋　ページトップへ</a></div>
				</div>
			</section>

			<?php include 'common/side_nav.html'; ?>
		</div>

		<?php include 'common/footer.html'; ?>
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
	</body>

</html>
