<?php
/**************************************
　商品情報の取得
　charset utf-8
***************************************/


require_once $_SERVER['DOCUMENT_ROOT'].'/php_libs/conndb.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/../cgi-bin/JSON.php';
class ItemInfo extends Conndb {

	public function __construct(){
		parent::__construct();
	}
	
	// 商品一覧ページ
	public function getList($category){
		$cat = parent::categoryList();
		for($i=0; $i<count($cat); $i++){
			if($cat[$i]['code']!=$category) continue;
			$data = parent::itemList($cat[$i]['id']);
			break;
		}
		if($category=='sportswear'){
			$len = count($data);
			for($i=0; $i<$len; $i++){
				$sub[$data[$i]["id"]] = true;
			}
			$tmp = parent::itemList(2, 'tag');	// スポーツウェアにドライタグを追加
			$len = count($tmp);
			for($i=0; $i<$len; $i++){
				if(array_key_exists($tmp[$i]["id"], $sub)) continue;
				$data[] = $tmp[$i];
			}
		}
		
		$len = count($data);
		for($i=0; $i<$len; $i++){
			// サイズ展開
			$arySize = parent::itemSize($data[$i]['id']);
			$sizeCount = count($arySize);
			$s = array();
			for($t=0; $t<$sizeCount; $t++){
				$sizeid = $arySize[$t]['id'];
				$sizename = $arySize[$t]['name'];
				if($sizeid<11){								// 70-160
					$s[0][] = array($sizeid,$sizename);
				}else if($sizeid<17 || $sizeid>28){			// JS-JL, GS-GL, WS-WL
					$s[1][] = array($sizeid,$sizename);
				}else{										// XS-8L
					$s[2][] = array($sizeid,$sizename);
				}
			}
			for($n=0; $n<3; $n++){
				if(!empty($s[$n])){
					if($s[$n][0]!=$s[$n][count($s[$n])-1]){
						if($s[$n][0][0]+1==$s[$n][1][0]){
							$s[3][] = $s[$n][0][1].'-'.$s[$n][count($s[$n])-1][1];
						}else{
							for($p=0; $p<count($s[$n]); $p++){
								$s[3][] = $s[$n][$p][1];
							}
						}
					}else{
						$s[3][] = $s[$n][0][1];
					}
				}
			}
			$data[$i]['sizeseries'] = implode(', ', $s[3]);
		}
		
		return $data;
	}
	
	
	// 商品詳細ページ
	public function getData($category, $itemid=null, $priceorder='index'){
		$data = array();
		if(!is_null($itemid)){
			$data = parent::itemList($itemid, "item");
		}else{
			$cat = parent::categoryList();
			for($i=0; $i<count($cat); $i++){
				if($cat[$i]['code']==$category){
					$data = parent::itemList($cat[$i]['id']);
					break;
				}
			}
			if($category=='sportswear'){
				foreach($data as $key=>$val){
					$sub[$val["id"]] = true;
				}
				$tmp = parent::itemList(2, 'tag');	// スポーツウェアにドライタグを追加
				foreach($tmp as $key1=>$val1){
					if(!array_key_exists($val1["id"], $sub)){
						$data[] = $tmp[$key1];
					}
				}
			}
		}
		
		foreach($data as $key=>$val){
			$r[$key] = $val;
			$r[$key]['itemid'] = $val['id'];
			$r[$key]['itemcode'] = $val['code'];
			$r[$key]['itemname'] = $val['name'];
			$r[$key]['row'] = $val['item_row'];
			$r[$key]['posid'] = $val['posid'];
			$r[$key]['price'] = $val['cost'];	// 最安単価
			$r[$key]['price_maker'] = $val['cost_maker'];	// 最安単価
			
			// アイテムレビュー
			$review = parent::getItemReview(array('sort'=>'post', 'itemid'=>$val['id']));
			$len = count($review);
			$r[$key]['len'] = $len;
			
			// レビュー総合評価
			$v = 0;
			for($i=0; $i<$len; $i++){
				$v += $review[$i]['vote'];
			}
			if($v==0){
				$r[$key]['ratio'] = 0;
			}else{
				$r[$key]['ratio'] = round($v/$len, 1);
			}
			$r[$key]['img'] = $this->getStar($r[$key]['ratio']);

			// 属性データ
			$attr = parent::itemAttr($val['id']);
			$r[$key]['color'] = count($attr['code']);	// color count
			list($itemCode_colorCode, $sizehash) = each($attr['size']);
			$r[$key]['sizecount'] = count($sizehash);	// size count
			$part = explode('_', $itemCode_colorCode);
			$currentColorCode = $part[1];
			if( preg_match('/^p-/',$val['code']) && $val['i_color_code']==""){
				$suffix = '_style_0'; 
			}else{ 
				$suffix = '_'.$val['i_color_code']; 
			}
			$r[$key]['imgname'] = $val['code'].$suffix;
			list($categorykey, $categoryname) = each($attr['category']);
			$r[$key]['categorykey'] = $categorykey;
			$r[$key]['categoryname'] = $categoryname;
			
			// サイズ展開
			$s = array();
			foreach($sizehash as $sizeid=>$sizename){
				if($sizeid<11){								// 70-160
					$s[0][] = array($sizeid,$sizename);
				}else if($sizeid<17 || $sizeid>28){			// JS-JL, GS-GL, WS-WL
					$s[1][] = array($sizeid,$sizename);
				}else{										// XS-8L
					$s[2][] = array($sizeid,$sizename);
				}
			}
			for($i=0; $i<3; $i++){
				if(!empty($s[$i])){
					if($s[$i][0]!=$s[$i][count($s[$i])-1]){
						if($s[$i][0][0]+1==$s[$i][1][0]){
							$s[3][] = $s[$i][0][1].'-'.$s[$i][count($s[$i])-1][1];
						}else{
							for($t=0; $t<count($s[$i]); $t++){
								$s[3][] = $s[$i][$t][1];
							}
						}
					}else{
						$s[3][] = $s[$i][0][1];
					}
				}
			}
			$r[$key]['sizeseries'] = implode(', ', $s[3]);

			// アイテム詳細ページ
			if(!is_null($itemid)){
				// レビューのテキストを2件まで返す
				$reviewcount = 2;
				if($len<2){
					$reviewcount = $len;
				}
				for ($i=0; $i < $reviewcount; $i++) { 
					$r[$key]['review'][$i] = $review[$i];
				}
				
				// サイズ毎の単価
				$priceHash = parent::sizePrice($val['id'], $currentColorCode);
				for($i=0; $i<count($priceHash); $i++){
					$r[$key]['size_price'][$priceHash[$i]['id']] = $priceHash[$i]['cost'];
					$r[$key]['printarea'][$i] = $priceHash[$i];
				}
				
				// サイズ一覧
				$r[$key]['size'] = $sizehash;
				
				// アイテムカラー[{code:カラー名},{},...]
				$r[$key]['thumbs'] = $attr['code'];
				
				// 寸法
				$r[$key]['measure'] = parent::getItemMeasure($val['code']);
			}
		}
		
		/* ソート
		if($priceorder!='index'){
			$sort = 'price';
		}else{
			$sort = 'row';
		}
		foreach($r as $key=>$row){
			$sortkey[$key] = $row[$sort];
		}
		if($priceorder!='high'){
			array_multisort($sortkey,SORT_ASC,SORT_NUMERIC,$r);
			//usort($data, 'sort_asc');
		}else{
			array_multisort($sortkey,SORT_DESC,SORT_NUMERIC,$r);
			//usort($data, 'sort_desc');
		}
		*/
		
		return $r;
	}
	
	
	// カテゴリ毎のシルエットで使用する絵型IDをキーにしたタグIDと絵型名のハッシュ
	private $silhouetteId = array(array(),
						array(
							1=>array('tag'=>93,'label'=>'綿素材'),
							3=>array('tag'=>2,'label'=>'ドライ')
						),
						array(
							6=>array('tag'=>15,'label'=>'トレーナー'),
							7=>array('tag'=>13,'label'=>'プルパーカー'),
							8=>array('tag'=>16,'label'=>'パンツ'),
							10=>array('tag'=>14,'label'=>'ジップパーカー')
						),
						array(
							12=>array('tag'=>102,'label'=>'ポケット無し'),
							13=>array('tag'=>8,'label'=>'ポケット有り')
						),
						array(
							3=>array('tag'=>103,'label'=>'GAME'),
							16=>array('tag'=>104,'label'=>'TRAINING')
						),
						array(
							3=>array('tag'=>2,'label'=>'ドライ'),
							7=>array('tag'=>105,'label'=>'スウェット'),
							41=>array('tag'=>93,'label'=>'綿素材')
						),
						array(
							18=>array('tag'=>6,'label'=>'薄い生地'),
							23=>array('tag'=>5,'label'=>'厚い生地')
						),
						array(
							25=>array('tag'=>107,'label'=>'キャップ'),
							32=>array('tag'=>33,'label'=>'バンダナ')
						),
						array(
							29=>array('tag'=>78,'label'=>'タオル')
						),
						array(
							30=>array('tag'=>79,'label'=>'トートバッグ')
						),
						array(
							27=>array('tag'=>41,'label'=>'肩がけ'),
							34=>array('tag'=>42,'label'=>'腰巻き')
						),
						array(
							18=>array('tag'=>106,'label'=>'ドリズラー'),
							40=>array('tag'=>19,'label'=>'パンツ'),
							43=>array('tag'=>108,'label'=>'シャツ')
						),
						array(
							32=>array('tag'=>83,'label'=>'全アイテム')
						),
						array(
							2=>array('tag'=>12,'label'=>'長袖'),
							4=>array('tag'=>7,'label'=>'七部袖')
						),
						array(
							31=>array('tag'=>81,'label'=>'ベビー')
						),
						array(),
						array(
							49=>array('tag'=>12,'label'=>'長袖'),
							50=>array('tag'=>11,'label'=>'半袖')
						),
					);
	
	
	// 評価を0.5単位に変換し画像パスを返す
	private function getStar($args){
		if($args<0.5){
			$r = 'star00';
		}else if($args>=0.5 && $args<1){
			$r = 'star05';
		}else if($args>=1 && $args<1.5){
			$r = 'star10';
		}else if($args>=1.5 && $args<2){
			$r = 'star15';
		}else if($args>=2 && $args<2.5){
			$r = 'star20';
		}else if($args>=2.5 && $args<3){
			$r = 'star25';
		}else if($args>=3 && $args<3.5){
			$r = 'star30';
		}else if($args>=3.5 && $args<4){
			$r = 'star35';
		}else if($args>=4 && $args<4.5){
			$r = 'star40';
		}else if($args>=4.5 && $args<5){
			$r = 'star45';
		}else{
			$r = 'star50';
		}
		return $r;
	}
	
	
	/**
	*	文字列からBOMデータを削除する
	*	@param $str	対象文字列
	*	@return		BOM削除した文字列
	*/
	private function deleteBom($str){
		if (($str == NULL) || (mb_strlen($str) == 0)) {
			return $str;
		}
		if (ord($str{0}) == 0xef && ord($str{1}) == 0xbb && ord($str{2}) == 0xbf) {
			$str = substr($str, 3);
		}
		return $str;
	}
	
	
	/*
	* プリントポジションIDをキーにした、たぐIDと絵型名のハッシュ返す
	* @id	category ID
	*/
	public function getSilhouetteId($id=null){
		if(empty($id)){
			return $this->silhouetteId;
		}else{
			return $this->silhouetteId[$id];
		}
	}
	
	
	/*
	* 見積ページのシルエットのタグを返す
	* @id		category ID
	*/
	public function getSilhouette($id){
		$idx = 1;
		foreach($this->silhouetteId[$id] as $ppid=>$val){
			$files = parent::positionFor($ppid, 'pos');
			$imgfile = $this->deleteBom(file_get_contents($files[0]['filename']));
			$f = preg_replace('/.\/img\//', _IMG_PSS, $imgfile);
			preg_match('/<img (.*?)>/', $f, $match);
			$pos .= '<div class="box">';
				$pos .= '<div class="body_type">';
					$pos .= '<img '.$match[1].'">';
				$pos .= '</div>';
				$pos .= '<div class="desc">';
					$pos .= '<p>';
						$pos .= '<input type="radio" value="'.$ppid.'" name="body_type" class="check_body"';
						if($idx==1) $pos .= ' checked="checked"';
						$pos .= ' id="radio-'.$idx.'">';
						$pos .= '<label for="radio-'.$idx.'" class="radio"> '.$val['label'].'</label>';
					$pos .= '</p>';
				$pos .= '</div>';
			$pos .= '</div>';
			$idx++;
		}
		
		return $pos;
	}
	
	
	/*
	* 見積ページのプリント位置指定のタグを返す
	* @id		printposition ID
	*/
	public function getPrintPosition($id){
		if(preg_match('/\A[1-9][0-9]*\z/', $id)){
			$files = parent::positionFor($id, 'pos');
		}else{
			return;
		}
		
		for($i=0; $i<count($files); $i++){
			$imgfile = $this->deleteBom(file_get_contents($files[$i]['filename']));
			$f = preg_replace('/.\/img\//', _IMG_PSS, $imgfile);
			$pos .= '<li class="pntposition">';
				$pos .= '<div class="psnv">';
				$pos .= '<div class="pnttxt"><p class="posname_'.$i.'"></p></div>';
					$pos .= '<div class="pos_'.$i.'">'.$f.'</div>';
					$pos .= '<div><p>デザインの色数</p><p><select class="ink_'.$i.'"><option value="0" selected="selected">選択してください</option><option value="1">1色</option><option value="2">2色</option><option value="3">3色</option><option value="9">4色以上</option></select></p></div>';
				$pos .= '</div>';
			$pos .= '</li>';
		}
		
		return $pos;
	}
	
	
	/* 
	*	絵型の表示をソート(public)
	*	order by printposition_id, selective_key
	*/
	public function sortSelectivekey($args){
		$tmp = array(
			"mae"=>1,
			"mae_mini"=>1,
			"jacket_mae_mini"=>1,
			"mae_mini_2"=>1,
			"parker_mae_mini_2"=>1,
			"parker_mae_mini_zip "=>1,
			"apron_mae"=>1,
			"tote_mae"=>1,
			"short_apron_mae"=>1,
			"cap_mae"=>1,
			"visor_mae "=>1,
			"active_mae"=>1,
			"army_mae"=>1,
			
			"mae_hood"=>2,
			"short_apron_ue"=>2,
			
			"mune_right"=>3,
			"parker_mune_right"=>3,
			"active_mune_right"=>3,
			"cap_mae_right"=>3,
			"boxerpants_right"=>3,
			"shirt_mune_right"=>3,
			"game_pants_suso_right"=>3,
			
			"pocket"=>4,
			"parker_mae_pocket"=>4,
			"apron_pocket"=>4,
			"short_apron_pocket"=>4,
			
			"mune_left"=>5,
			"parker_mune_left"=>5,
			"active_mune_left"=>5,
			"polo_mune_left"=>5,
			"cap_mae_left"=>5,
			"boxerpants_left"=>5,
			"game_pants_suso_left"=>5,
			
			"suso_left"=>6,
			"apron_suso_left"=>6,
			"shirt_suso_left"=>6,
			
			"suso_mae"=>7,
			
			"suso_right"=>8,
			"shirt_suso_right"=>8,
			
			
			"mae_right"=>9,
			"workwear_mae_right"=>9,
			
			"mae_suso_right"=>10,
			"boxerpants_suso_right"=>10,
			
			"mae_momo_right"=>11,
			"workwear_mae_momo_right"=>11,
			
			"mae_hiza_right"=>12,
			"workwear_mae_hiza_right"=>12,
			
			"mae_asi_right"=>13,
			"workwear_mae_asi_right"=>13,
			
			
			"mae_left"=>14,
			"workwear_mae_left"=>14,
			
			"mae_suso_left"=>15,
			"boxerpants_suso_left"=>15,
			
			"mae_momo_left"=>16,
			"workwear_mae_momo_left"=>16,
			
			"mae_hiza_left"=>17,
			"workwear_mae_hiza_left"=>17,
			
			"mae_asi_left"=>18,
			"workwear_mae_asi_left"=>18,
			
			"happi_sode_left"=>19,
			"happi_mune_left"=>19,
			"happi_maetate_left"=>19,
			"happi_sode_right"=>19,
			"happi_mune_right"=>19,
			"happi_maetate_right"=>19,
			
			"towel_center"=>20,
			"towel_left"=>20,
			"towel_right"=>20,
			
			
			
			"usiro"=>21,
			"usiro_mini"=>21,
			"parker_usiro"=>21,
			"bench_usiro"=>21,
			"best_usiro"=>21,
			"tote_usiro"=>21,
			"cap_usiro"=>21,
			"active_cap_usiro"=>21,
			
			"eri"=>22,
			"kubi_usiro"=>22,
			"shirt_long_kubi_usiro"=>22,
			"shirt_short_kubi_usiro"=>22,
			
			"usiro_suso_left"=>23,
			"shirt_usiro_suso_left"=>23,
			
			"usiro_suso"=>24,
			
			"usiro_suso_right"=>25,
			"shirt_usiro_suso_right"=>25,
			
			"osiri"=>26,
			"pants_osiri"=>26,
			"boxerpants_osiri"=>26,
			
			
			"usiro_left"=>27,
			"pants_usiro_left"=>27,
			"workwear_usiro_left"=>27,
			
			"pants_usiro_suso_left"=>28,
			"boxerpants_usiro_suso_left"=>28,
			"game_pants_usiro_suso_left"=>28,
			
			"usiro_momo_left"=>29,
			"workwear_usiro_momo_left"=>29,
			
			"usiro_hiza_left"=>30,
			"workwear_usiro_hiza_left"=>30,
			
			"usiro_asi_left"=>31,
			"workwear_usiro_asi_left"=>31,
			
			"usiro_right"=>32,
			"pants_usiro_right"=>32,
			"workwear_usiro_right"=>32,
			
			"pants_usiro_suso_right"=>33,
			"boxerpants_usiro_suso_right"=>33,
			"game_pants_usiro_suso_right"=>33,
			
			"usiro_momo_right"=>34,
			"workwear_usiro_momo_right"=>34,
			
			"usiro_hiza_right"=>35,
			"workwear_usiro_hiza_right"=>35,
			
			"usiro_asi_right"=>36,
			"workwear_usiro_asi_right"=>36,
			
			
			
			"sode_right"=>37,
			"sode_right2"=>37,
			
			"hood_right"=>38,
			
			"long_sode_right"=>39,
			"trainer_sode_right"=>39,
			"parker_sode_right"=>39,
			"blouson_sode_right"=>39,
			"coat_sode_right"=>39,
			"boxerpants_side_right"=>39,
			"shirt_sode_right"=>39,
			"shirt_long_sode_right"=>39,
			
			"long_ude_right"=>40,
			"trainer_ude_right"=>40,
			"parker_ude_right"=>40,
			"blouson_ude_right"=>40,
			"coat_ude_right"=>40,
			"shirt_long_ude_right"=>40,
			
			"long_sodeguti_right"=>41,
			"trainer_sodeguti_right"=>41,
			
			"long_waki_right"=>42,
			"waki_right"=>42,
			"waki_right2"=>42,
			
			"sode_left"=>43,
			"sode_left2"=>43,
			
			"hood_left"=>44,
			
			"long_sode_left"=>45,
			"trainer_sode_left"=>45,
			"parker_sode_left"=>45,
			"blouson_sode_left"=>45,
			"coat_sode_left"=>45,
			"boxerpants_side_left"=>45,
			"shirt_sode_left"=>45,
			"shirt_long_sode_left"=>45,
			
			"long_ude_left"=>46,
			"trainer_ude_left"=>46,
			"parker_ude_left"=>46,
			"blouson_ude_left"=>46,
			"coat_ude_left"=>46,
			"shirt_long_ude_left"=>46,
			
			"long_sodeguti_left"=>47,
			"trainer_sodeguti_left"=>47,
			
			"long_waki_left"=>48,
			"waki_left"=>48,
			"waki_left2"=>48,
			
			"cap_side_right"=>49,
			"active_cap_side_right"=>49,
			
			"cap_side_left"=>50,
			"active_cap_side_left"=>50
		);
		
		foreach($args as $key=>$val){
			$a[$key] = $tmp[$val['key']];
		}
		array_multisort($a, $args);
		
		return $args;
	}
}


if(isset($_REQUEST['act'])){
	$iteminfo = new ItemInfo();
	switch($_REQUEST['act']){
	case 'body':
		// item silhouette
		$res = $iteminfo->getSilhouette($_REQUEST['category_id']);
		break;
		
	case 'position':
		// print position
		$res = $iteminfo->getPrintPosition($_REQUEST['pos_id']);
		break;
		
	case 'itemtype':
		// [categoryID : {printPositionID : {'tag' : tagID, 'label' : tagName}}]
		$res = $iteminfo->getSilhouetteId($_REQUEST['category_id']);
		if(isset($_REQUEST['output']) && $_REQUEST['output']=='jsonp'){
			$json = new Services_JSON(SERVICES_JSON_LOOSE_TYPE);
			$res = $json->encode($res);
			header("Content-Type: text/javascript; charset=utf-8");
		}
	}
	
	echo $res;
}
?>