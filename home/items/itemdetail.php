<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/../cgi-bin/session_my_handler.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/php_libs/iteminfo.php';
$iteminfo = new ItemInfo();
if(isset($_GET['c'], $_GET['i'])){
	$category = $_GET['c'];
	$item_id = $_GET['i'];
	//$item_id = $iteminfo->itemID($item_code);
}else if(isset($_SESSION['smp']['orders'])){
	$category = $_SESSION['smp']['orders']['category'];
	//$item_code = $_SESSION['smp']['orders']['item_code'];
	$item_id = $_SESSION['smp']['orders']['item_id'];
}else{
	$category = 't-shirts';
	//$item_code = '085-cvt';
	$item_id = 4;
}

$r = $iteminfo->getData($category, $item_id);
list($key, $val) = each($r);
$item_code = $val['itemcode'];
$item_name = $val['itemname'];
$item_price = $val['price'];
$item_color = $val['color'];
$item_size = $val['sizeseries'];
$posid = $val['posid'];
$categorykey = $val['categorykey'];


/* 概算見積もり */
$amount = 30;
$args[] = array('itemid'=>$item_id, 'amount'=>$amount, 'ink'=>1, 'pos'=>'f');
$price = $iteminfo->printfee($args);
$base = $price['printfee'] + $item_price*$amount;
$tax = 1+($price['tax']/100);
$perone = ceil(floor($base*$tax)/$amount);

/*アイテム画像パスとカラー名*/
list($imgname, $color_name) = each($val['thumbs']);
$part = explode('_',$imgname);	// itemcode_colorcode
$image_src = _IMG_PSS.'items/'.$categorykey.'/'.$item_code.'/'.$imgname.'.jpg';
//$item_image = '<img id="item_imgfile"  src="'.$image_src.'" width="249" height="249" alt="'.$part[1].'"/>';

/*サムネイル*/
$thumb = '';
foreach($val['thumbs'] as $imgname=>$colorname){
	if($colorname==$color_name) $fno=$i;
	$i++;
	$colorHash = explode("_", $imgname);
	$thumb .= '<li';
	if($colorname==$color_name) $thumb .= ' class="nowimg"';
	$thumb .= '><img alt="'.$colorHash[1].'" title="'.$colorname.'" src="'._IMG_PSS.'items/list/'.$categorykey.'/'.$item_code.'/'.$imgname.'.jpg" width="38" /></li>';
}

/*レビュー*/
$review_data = $val['review'];
if($val['len']>0){
	if($val['len']>2){
		$end = 2;	// レビューを2件まで表示
	}else{
		$end = $val['len'];
	}
	$review_list = '';
	for($i=0; $i<$end; $i++){
		$amount = number_format($review_data[$i]['amount']);
		if(mb_strlen($review_data[$i]['review'], 'utf-8')>32){
			$review_text = mb_substr($review_data[$i]['review'], 0, 32, 'utf-8');
			$review_text .= ' ...';
		}else{
			$review_text = $review_data[$i]['review'];
		}
		$review_text = nl2br($review_text);
		
		$review_list .='<div class="unit_body">';
			$review_list .='<p>';
				$review_list .='<img src="/itemreviews/img/'.$val['img'].'.png" width="114" height="21" alt="">';
				$review_list .='<ins>'.$val['ratio'].'</ins>';
			$review_list .='</p>';
			$review_list .='<p>'.$review_text.'</p>';
		$review_list .='</div>';
	}
	
	$review_list .= '<p class="tor">';
	$review_list .= '<a href="/itemreviews/index.php?item='.$item_id.'">もっと見る（'.number_format($val['len']).'件）</a>';
	$review_list .= '</p>';
}else{
	$review_list = '<p class="tor"><span>レビュー（0件）</span></p>';
}

/*サイズリスト
foreach($val['size'] as $sizeid=>$sizename){
	if(empty($sizeList)){
		$sizeList .= '<option value="'.$sizename.'" data-cost="'.$val['size_price'][$sizeid].'" selected="selected">'.$sizename.'</option>';
	}else{
		$sizeList .= '<option value="'.$sizename.'" data-cost="'.$val['size_price'][$sizeid].'">'.$sizename.'</option>';
	}
}
*/

/*説明文と素材*/
$detail_desc = nl2br($val['i_description']);
$detail_material = nl2br($val['i_material']);

/*寸法テーブル（UNISEX, LADIES', KIDS'）*/
$itemMeasure = $val['measure'];
$columns = array("KIDS&#39"=>1,"LADIES&#39"=>1,"UNISEX"=>1);
$tblHash = array("KIDS&#39"=>"","LADIES&#39"=>"","UNISEX"=>"");
$tblHead = array("KIDS&#39"=>"<tr><td>SIZE</td>","LADIES&#39"=>"<tr><td>SIZE</td>","UNISEX"=>"<tr><td>SIZE</td>");
$tblType = array();
$len = count($itemMeasure);
$curMeasure = $itemMeasure[0]["measure_id"];
for($i=0; $i<$len; $i++){
	if($itemMeasure[$i]["size_name"]=='F' || $itemMeasure[$i]["size_name"]=='S' || $itemMeasure[$i]["size_name"]=='M' || $itemMeasure[$i]["size_name"]=='L' || $itemMeasure[$i]["size_name"]=='XL' || $itemMeasure[$i]["size_name"]=='O' || $itemMeasure[$i]["size_name"]=='XO' || $itemMeasure[$i]["size_name"]=='YO' || $itemMeasure[$i]["size_name"]=='2YO' || (substr($itemMeasure[$i]["size_name"],0,1)>=3 && substr($itemMeasure[$i]["size_name"],1,1)>="L")){
		$tblType["UNISEX"][] = $itemMeasure[$i];
		if($itemMeasure[$i]["measure_id"]==$curMeasure){
			$tblHead["UNISEX"] .= "<td>".$itemMeasure[$i]["size_name"]."</td>";
			$columns["UNISEX"]++;
		}
	}else if(substr($itemMeasure[$i]["size_name"],0,1)=='W' || substr($itemMeasure[$i]["size_name"],0,1)=='G'){
		$tblType["LADIES&#39"][] = $itemMeasure[$i];
		if($itemMeasure[$i]["measure_id"]==$curMeasure){
			$tblHead["LADIES&#39"] .= "<td>".$itemMeasure[$i]["size_name"]."</td>";
			$columns["LADIES&#39"]++;
		}
	}else{
		$tblType["KIDS&#39"][] = $itemMeasure[$i];
		if($itemMeasure[$i]["measure_id"]==$curMeasure){
			$tblHead["KIDS&#39"] .= "<td>".$itemMeasure[$i]["size_name"]."</td>";
			$columns["KIDS&#39"]++;
		}
	}
	$col++;
}
$itemsize_table = "";
foreach ($tblType as $key => $value) {
	$curMeasure = 0;
	$preDimension = "";
	$col = 0;
	$len = count($value);
	for($i=0; $i<$len; $i++){
		if(empty($tblHash[$key])){
			if($categorykey=='tote-bag' || $categorykey=='towel'){
				$tblHash[$key] .= '<table>';
			}else{
				$tblHash[$key] .= '<table><caption>'.$key.'</caption>';
			}
			$tblHash[$key] .= '<tfoot><tr><td colspan="'.$columns[$key].'">(cm)</td></tr></tfoot><tbody>';
			$tblHash[$key] .= $tblHead[$key].="</tr>";
		}
		if($value[$i]["measure_id"]!=$curMeasure){
			if($curMeasure!=0){
				if($col==1){
					$tblHash[$key] .= '<td>';
				}else{
					$tblHash[$key] .= '<td colspan="'.$col.'">';
				}
				$tblHash[$key] .= $preDimension.'</td>';
				$col = 0;
				$preDimension = "";
				$tblHash[$key] .= "</tr>";
			}
			$tblHash[$key] .= "<tr><td>".$value[$i]["measure_name"]."</td>";
			$curMeasure = $value[$i]["measure_id"];
		}
		if($preDimension!="" && $preDimension!=$value[$i]["dimension"]){
			if($col==1){
				$tblHash[$key] .= '<td>';
			}else{
				$tblHash[$key] .= '<td colspan="'.$col.'">';
			}
			$tblHash[$key] .= $preDimension.'</td>';
			$col = 1;
			$preDimension = $value[$i]["dimension"];
		}else{
			$col++;
			$preDimension = $value[$i]["dimension"];
		}
	}
	if($col==1){
		$tblHash[$key] .= '<td>';
	}else{
		$tblHash[$key] .= '<td colspan="'.$col.'">';
	}
	$tblHash[$key] .= $preDimension.'</td>';
	$itemsize_table .= $tblHash[$key].'</tr></tbody></table>';
}

/*寸法の脚注*/
if(!empty($val['i_note'])){
	$footNote .= empty($val["i_note_label"])? "<h3>".$val["i_note_label"]."</h3>": "";
	$footNote .= $val["i_note"]? "<p>".$val["i_note"]."</p>": "";
}

/*プリント可能範囲のサイズ*/
$printSizeTable = '';
$row = 0;
$isFirstRow = TRUE;
$preValue = "";
$sizeName = array();
$posArea = array();
$len = count($val['printarea']);
for($i=0; $i<$len; $i++){
	$posArea[0][$val['printarea'][$i]["name"]] = $val['printarea'][$i]["printarea_1"];
	$posArea[1][$val['printarea'][$i]["name"]] = $val['printarea'][$i]["printarea_2"];
	$posArea[2][$val['printarea'][$i]["name"]] = $val['printarea'][$i]["printarea_3"];
	$posArea[3][$val['printarea'][$i]["name"]] = $val['printarea'][$i]["printarea_4"];
	$posArea[4][$val['printarea'][$i]["name"]] = $val['printarea'][$i]["printarea_5"];
	$posArea[5][$val['printarea'][$i]["name"]] = $val['printarea'][$i]["printarea_6"];
	$row++;
}
for($i=0; $i<6; $i++){
	$tbl = "";
	$preValue = "";
	foreach ($posArea[$i] as $key => $value) {
		if(!$value) break;
		if(empty($tbl)){
			$tbl .= '<table>
						<colgroup>
							<col span="1" class="col01" />
							<col span="2" />
						</colgroup>
						<thead>
							<tr>
								<th>プリント箇所</th>
								<th>サイズ</th>
								<th>プリントサイズ(cm)</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<th rowspan="'.$row.'">'.($i+1).'</th>';
		}

		if($preValue==""){
			$preValue = $value;
			$sizeName = array($key);
			$isFirstRow = TRUE;
		}else if($preValue!=$value){
			if(!$isFirstRow) $tbl .= '<tr>';
			if(empty($sizeName[1])){
				$tbl .= '<td>'.$sizeName[0].'</td>';
			}else{
				$tbl .= '<td>'.$sizeName[0].' - '.$sizeName[1].'</td>';
			}
			$tbl .= '<td>'.$preValue.'</td></tr>';
			$sizeName = array($key);
			$preValue = $value;
			$isFirstRow = FALSE;
		}else{
			$sizeName[1] = $key;
		}

	}
	if(!empty($tbl)){
		if(!$isFirstRow) $tbl .= '<tr>';
		if(empty($sizeName[1])){
			$tbl .= '<td>'.$sizeName[0].'</td>';
		}else{
			$tbl .= '<td>'.$sizeName[0].' - '.$sizeName[1].'</td>';
		}
		$tbl .= '<td>'.$preValue.'</td></tr>';
		$tbl .= '</tbody></table>';
		$printSizeTable .= $tbl;
	}
}

/*プリント可能範囲の絵型*/
$files = $iteminfo->positionFor($item_id);
$position_type = trim($files[0]["ppdata"]["pos"]);
$baseName = array(
	"前"=>"front",
	"後"=>"back",
	"横"=>"side",
	"プリントなし"=>"front"
);
$printAreaImage = "";
for($i=0; $i<count($files); $i++){
	$printAreaImage .= '<img src="'._IMG_PSS.'printarea/'.$position_type.'/base_'.$baseName[$files[$i]['base_name']].'.png" alt="">';
}

/*対応するプリント方法*/
$printMethod = $val['i_silk']? '<li><span class="sp1">シルクスクリーン</span></li>': '<span class="none"></span>';
$printMethod .= $val['i_digit']? '<li><span class="sp2">デジタル転写</span></li>': '<span class="none"></span>';
$printMethod .= $val['i_inkjet']? '<li><span class="sp3">インクジェット</span></li>': '<span class="none"></span>';
$printMethod .= $val['i_cutting']? '<li><span class="sp4">カッティング</span></li>': '<span class="none"></span>';

/*プリント位置とインク色数指定*/
$pos = $iteminfo->getPrintPosition($posid);

/*モデル着用写真のポップアップ*/
$filename = $iteminfo->getModelPhoto($categorykey, $item_code);
for ($i=0; $i < count($filename); $i++) { 
	$base = explode('.', $filename[$i]);
	$tmp = explode('_', $base[0]);
	$alt = 'モデル着用写真 '.$tmp[2].'cm・'.$tmp[1].'サイズ着用';
	$model_gallery .= '<li><img src="'._IMG_PSS."items/".$categorykey."/model/".$item_code.'/'.$filename[$i].'" height="70" alt="'.$alt.'"></li>';
}
if(!empty($model_gallery)){
	$model_gallery = '<p class="thumb_h">Model</p><ul class="clearfix">'.$model_gallery.'</ul>';
}

/*スタイル写真*/
$filename = $iteminfo->getStylePhoto($categorykey, $item_code);
for ($i=0; $i < count($filename); $i++) {
	$style_gallery .= '<li><img src="'._IMG_PSS.'items/'.$categorykey.'/'.$item_code.'/'.$filename[$i].'" height="70" alt="'.$itemcode.'"></li>';
}
if(!empty($style_gallery)){
	$style_gallery = '<p class="thumb_h">Style</p><ul id="style_thumb">'.$style_gallery.'</ul>';
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
		<title>アイテム詳細｜スタッフTシャツ屋</title>
		<meta name="Description" content="スタッフTシャツ屋でオリジナルＴシャツを作成した場合のお見積もりをオンラインで簡単に見ることができます。最短でオリジナルプリントを作成したい方はタカハマライフアートへ！" />
		<meta name="keywords" content="見積もり,簡単,Tシャツ,オリジナル,作成,スタッフTシャツ" />
		<meta name="viewport" content="width=device-width,user-scalable=no,maximum-scale=1" />
		<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
		<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css">
		<link rel="stylesheet" type="text/css" href="/css/reset.css" media="all">
		<link rel="stylesheet" type="text/css" href="/css/common - test.css" media="all">
		<link rel="stylesheet" type="text/css" href="/css/flexnav1.css" media="all">
		<link rel="stylesheet" type="text/css" href="/css/media-style.css" media="all">
		<link rel="stylesheet" type="text/css" href="/common2/css/printposition.css" media="all">
		<link rel="stylesheet" type="text/css" href="./css/culstyleadd.css" media="screen" />
		<link rel="stylesheet" type="text/css" href="./css/items_style.css" media="all">
		<!-- OGP -->
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
		<script src="/js/jquery.flexnav.js"></script>
		<script type="text/javascript" src="/common2/js/tlalib.js"></script>
		<script type="text/javascript" src="/items/js/jquery.changephoto.js"></script>
		<script type="text/javascript" src="/items/js/jquery.tableselect.js"></script>
		<script type="text/javascript" src="/items/js/estimate_sole.js"></script>
		<script type="text/javascript">
			var _CAT_ID = <?php echo $val['category_id']; ?>;
			var _CAT_KEY = '<?php echo $categorykey; ?>';
			var _CAT_NAME = '<?php echo $val['categoryname']; ?>';
			var _ITEM_ID = <?php echo $item_id; ?>;
			var _ITEM_CODE = '<?php echo $item_code; ?>';
			var _ITEM_NAME = '<?php echo $item_name; ?>';
			var _POS_ID = <?php echo $posid; ?>;
			$(function($) {
				var nav    = $('.fixed'),
					offset = nav.offset();
				$(window).scroll(function () {
					if($(window).scrollTop() > offset.top) {
						nav.addClass('fixed');
					} else {
						nav.removeClass('fixed');
					}
				});
				/*globalnav*/
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
			<h1>アイテム一覧！スタッフTシャツならタカハマライフアート</h1>
		</div>
		<?php include '../common/global_nav - test.html'; ?>
		<div id="mainwrap">
			<section id="main">
				<h2 class="heading2">アイテム詳細</h2>
				<div class="content-lv2">
					<p>アイテムの詳細が詳しくわかる！さらにその場で概算金額がわかります。</p>
				</div>
				<div id="item_left">
					<div id="item_image">
						<img id="item_image_l" src="<?php echo $image_src;?>" width="100%">
						<div class="dotted"></div>
						<ul id="item_image_notes">
							<li id="notes_color">
								<?php echo $color_name;?>
							</li>
							<br>
							<li id="notes_size">展開サイズ 
								<span id="size_span">
									<?php echo $item_size;?>
								</span>
							</li>
							<li id="size_info">
								<a class="info_icon" href="#size">サイズ目安</a>
							</li>
						</ul>
					</div>
					<div id="item_thumb">
						<div id="item_colors">
							<p class="thumb_h">Color
								<span id="num_of_color">
									全<?php echo $item_color;?>色
								</span>
							</p>
							<ul id="color_thumb">
								<?php echo $thumb;?>
							</ul>
						</div>
					</div>
					<div id="model">
						<?php echo $model_gallery; ?>
					</div>

					<div id="item_style">
						<?php echo $style_gallery; ?>
					</div>
				</div>
				<div id="item_right">
					<div id="item_title">
						<h2>品番：
							<span id="item_code"><?php echo strtoupper($item_code);?></span>
						</h2>
						<h1><?php echo $item_name;?></h1>
						<div id="price">Takahama価格：
							<span id="price_detail"><?php echo number_format($item_price);?>円&#65374;/１枚</span>
						</div>
						<div id="priceex">例えば、
							<br>注文枚数
							<span>30</span>枚&nbsp;プリント位置
							<span>1</span>ヶ所&nbsp;インク
							<span>1</span>色で、
							<br>
							<div id="priceexper">
								<span><?php echo number_format($perone);?></span>円/1枚
							</div>
						</div>
						<div id="orderbtn_wrap_up">
							<form name="f1" action="/order/" method="post">
								<input type="hidden" name="item_id" value="<?php echo $item_id;?>">
								<input type="hidden" name="update" id="update" value="1">
								<div id="btnOrder_up" onclick="ga(['send','event','order','click','<?php echo $item_code;?>']);">お申込みフォームへ</div>
							</form>
						</div>
						<ul id="blue_btns">
							<li id="calbtn">
								<a href="#howmuch" onclick="ga(['send','event','howmuch','click','<?php echo $item_code;?>']);">その場で見積もり</a>
							</li>
						</ul>
					</div>
					<div class="contents-lv3">
						<h2 id="review_side">アイテムレビュー</h2>
						<?php echo $review_list;?>
					</div>
					<div class="contents-lv3">
						<h2 id="info_side">アイテム説明</h2>
						<div id="info_txt">
							<p><?php echo $detail_desc; ?></p>
							<p>■素材<br><?php echo $detail_material; ?></p>
						</div>
					</div>
				</div>
				<div class="contents-lv2">
					<h2 id="size">サイズ目安</h2>
					<div class="dotted"></div>
					<div id="size_detail">
						<?php echo $itemsize_table;?>
					</div>
				</div>
				<div class="contents-lv2 printarea_wrap">
					<h2 id="printarea">プリント可能範囲</h2>
					<div class="dotted"></div>
					<p>サイズ対応表とプリント可能範囲・プリント最大サイズについてご説明いたします。</p>
					<div class="flex-container flex-around">
						<?php echo $printAreaImage; ?>
					</div>
					<div class="flex-container">
						<?php echo $printSizeTable; ?>
					</div>
					<div class="footnote">
						<?php echo $footNote; ?>
					</div>
					<div class="printtype">
						<h2 id="printarea">対応プリント</h2>
						<div class="dotted"></div>
						<ul>
							<?php echo $printMethod; ?>
						</ul>
					</div>
				</div>
				<div class="up">
					<a class="up_icon" href="#topicpath">ページ上部へ</a>
				</div>
				<div class="contents-lv2">
					<h2 id="howmuch">簡単無料！お見積りのご依頼はこちらから</h2>
					<div id="cul">
						<div id="price_wrap">
							<h3 class="stepone">カラーとサイズごとの枚数をご指定ください。
								<a class="info_icon" href="#size">サイズ目安</a>
							</h3>
							<div class="item_colors">
								<p class="thumb_h">(1) Color
									<span>全<?php echo $item_color;?>色</span>
									<span class="notes_color"><?php echo $color_name;?></span>
								</p>
								<ul class="color_thumb clearfix">
									<?php echo $thumb;?>
								</ul>
							</div>
							<table class="curitemid_215">
								<caption>(2) サイズと枚数</caption>
								<tbody>
									
								</tbody>
							</table>
						</div>
						<div id="pos_wrap">
							<div class="content-lv3">
								<h3 class="longtxt">3.プリントする位置とデザインの色数を指定</h3>
								<div>
									<figure>
										<div>
											<p class="pos_step ps1">(1)プリントする位置を選択してください。</p>
										</div>
										<ul>
											<?php echo $pos;?>
										</ul>
									</figure>
								</div>
							</div>
						</div>
						<div id="printfee_wrap">
							<h3 class="stepthree">計算結果を確認してください。</h3>
							<table>
								<tbody>
								<tr>
									<td class="lbl02">
										<p>計</p>
									</td>
									<td>
										<p id="baseprice">
											<span>0</span>円
										</p>
									</td>
								</tr>
								<tr>
									<td class="lbl02">
										<p>消費税</p>
									</td>
									<td>
										<p id="salestax">
											<span>0</span>円
										</p>
									</td>
								</tr>
								<tr>
									<td class="lbl01">
										<p>合　　計</p>
									</td>
									<td>
										<p id="result">
											<span>0</span>円
										</p>
									</td>
								</tr>
								<tr>
									<td class="lbl02">
										<p>1枚あたり</p>
									</td>
									<td>
										<p id="perone">
											<span>0</span>円
										</p>
									</td>
								</tr>
								<tr>
									<td class="lbl02">
										<p>合計枚数</p>
									</td>
									<td>
										<p id="totamount">
											<span>0</span>枚
										</p>
									</td>
								</tr>
								</tbody>
							</table>
						</div>
						<div id="order_wrap">
							<p id="orderguide">
								<span>お見積もり金額について</span>
								<br>デザイン、ボディのカラー・サイズ・素材により、表示されているお見積もり金額と別のプリント方法でご提案させていただくこともございますので、お見積もり金額がお打ち合わせ後変わることがございます。
							</p>
							<div id="orderbtn_wrap">
								<form name="f1" action="/order/" method="post" >
									<input type="hidden" name="item_id" value="<?php echo $item_id;?>" />
									<input type="hidden" name="update" value="1" />
									<div id="btnOrder" >お申込みフォームへ</div>
								</form>
							</div>
						</div>
					</div>
					<img src="/img/top/st_flow.jpg" width="100%">
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
				<div class="totop">
					<a href="#head1">ページトップへ</a>
				</div>
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
		<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	</body>
</html>