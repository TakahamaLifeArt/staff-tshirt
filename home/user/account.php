<?php
//require_once dirname(__FILE__).'/php_libs/loginstatus.php';
require_once dirname(__FILE__).'/php_libs/funcs.php';

// ログイン状態のチェック
$me = checkLogin();
if(!$me){
	jump('login.php');
}

$conndb = new Conndb(_API_U);

if($_SERVER['REQUEST_METHOD']!='POST'){
	setToken();
}else{
	chkToken();
	
	if(isset($_POST['profile'])){
		$args = array(
			'profile'=>true,
			'uname'=>$_POST['uname'],
			//'email'=>$_POST['email'],
			'ukana'=>$_POST['ukana'],
			'userid'=>$_POST['userid']
		);
		$err = update_user($args);
		if(!empty($err)){
			$hide = 'style="display:none;"';
			$show = 'style="display:block;"';
		}else{
			$hide = '';
			$show = '';
		}
		// ユーザー情報の再取得
		$me = checkLogin();
		if(!$me){
			jump(_DOMAIN);
		}
	}else if(isset($_POST['mypass'])){
		$args = array(
			'userid'=>$_POST['userid'],
			'pass'=>$_POST['pass'],
			'passconf'=>$_POST['passconf']
		);
		$err = update_pass($args);
	}else if(isset($_POST['myaddr'])){
		$args = array(
			'userid'=>$_POST['userid'],
			'zipcode'=>$_POST['zipcode'],
			'addr0'=>$_POST['addr0'],
			'addr1'=>$_POST['addr1'],
			'addr2'=>$_POST['addr2'],
			'tel'=>$_POST['tel']
		);
		$err = update_addr($args);
	}else if(isset($_POST['mydeli'])){
		$args = array(
			'userid'=>$_POST['userid'],
			'deliid'=>$_POST['deliid'],
			'organization'=>$_POST['organization'],
			'delizipcode'=>$_POST['delizipcode'],
			'deliaddr0'=>$_POST['deliaddr0'],
			'deliaddr1'=>$_POST['deliaddr1'],
			'deliaddr2'=>$_POST['deliaddr2'],
			'deliaddr3'=>$_POST['deliaddr3'],
			'deliaddr4'=>$_POST['deliaddr4'],
			'delitel'=>$_POST['delitel']
		);
		$err = update_deli($args,'a');
	}else if(isset($_POST['mydeli1'])){
		$args = array(
			'userid'=>$_POST['userid'],
			'deliid'=>$_POST['deliid1'],
			'organization'=>$_POST['deli1organization'],
			'delizipcode'=>$_POST['deli1zipcode'],
			'deliaddr0'=>$_POST['deli1addr0'],
			'deliaddr1'=>$_POST['deli1addr1'],
			'deliaddr2'=>$_POST['deli1addr2'],
			'deliaddr3'=>$_POST['deli1addr3'],
			'deliaddr4'=>$_POST['deli1addr4'],
			'delitel'=>$_POST['deli1tel']
		);
		$err = update_deli($args,'b');
	}
}


// ユーザー情報を設定
$u = $conndb->getUserList($me['id']);
$username = $me['customername'];
$userkana = $me['customerruby'];
$email = $u[0]['email'];
//お届け先情報を設定
$deli = $conndb->getDeli($me['id']);
//$deli = mb_convert_encoding($deli, 'euc-jp', 'utf-8');
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<meta name="description" content="スタッフTシャツお客様のアカウントはこちらから。お客様情報をご確認できます！スタッフTシャツ、イベントTシャツの作成は短納期で早いスタッフTシャツ屋タカハマライフアートへ。">
		<meta name="keywords" content="スタッフTシャツ,作成,アカウント,お客様情報">
		<meta name="viewport" content="width=device-width,user-scalable=no,maximum-scale=1" />
		<title>アカウント | スタッフTシャツ屋</title>
		<link rel="shortcut icon" href="/icon/favicon.ico" />
		<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
		<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css">
		<link rel="stylesheet" type="text/css" href="../css/reset.css" media="all">
		<link rel="stylesheet" type="text/css" href="../css/common - test.css" media="all">
		<link rel="stylesheet" type="text/css" href="../css/flexnav1.css" media="all">
		<link rel="stylesheet" type="text/css" href="../css/media-style.css" media="all">
		<link rel="stylesheet" type="text/css" media="screen" href="/common2/css/base.css">
		<link rel="stylesheet" type="text/css" media="screen" href="./css/style.css" />
		<link rel="stylesheet" type="text/css" media="screen" href="./css/account.css" />
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
		<script src="https://ajaxzip3.github.io/ajaxzip3.js" charset="utf-8"></script>
		<script type="text/javascript" src="/common2/js/tlalib.js"></script>
		<script type="text/javascript" src="./js/account.js"></script>
		<script src="../js/jquery.flexnav.js"></script>
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
				$(".flexnav").flexNav();
			});
		</script>
		<script type="text/javascript">
			var _gaq = _gaq || [];
			_gaq.push(['_setAccount', 'UA-11155922-2']);
			_gaq.push(['_trackPageview']);

			(function() {
				var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
				ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
				var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
			})();
		</script>
	</head>
	<body>
		<!-- Google Tag Manager -->
		<noscript>
			<iframe src="//www.googletagmanager.com/ns.html?id=GTM-T5NQFM" height="0" width="0" style="display:none;visibility:hidden"></iframe>
		</noscript>
		<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'//www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-T5NQFM');</script>
		<!-- End Google Tag Manager -->
		<div id="head1">
			<h1>お支払い状況！スタッフTシャツ屋</h1>
		</div>
		<?php include '../common/global_nav - test.html'; ?>
		<div id="mainwrap">
			<section id="main">
				<div class="contents">
					<h2 class="heading2">アカウント</h2>
					<div class="toolbar">
						<div class="toolbar_inner clearfix">
							<div class="menu_wrap">
								<?php echo $menu;?>
							</div>
						</div>
					</div>
					<?php echo $loginStatus;?>
			<div id="a_message">
				<p><span class="fontred">※</span>お客様情報をご変更した際はこちらまでご連絡をお願いいたします。</p><p>TEL：03-5670-0787　　MAIL：info@takahama428.com　</p>
			</div>

			<form class="section" name="prof" action="<?php echo $_SERVER['SCRIPT_NAME']; ?>" method="post" enctype="multipart/form-data" onSubmit="return false;">
				<table class="form_table me" id="profile_table">
					<h2>ユーザー情報</h2>
					<tfoot>
						<tr>
							<td colspan="2">
								<input type="hidden" name="token" value="<?php echo $_SESSION['token']; ?>">
								<input type="hidden" name="userid" value="<?php echo $me['id'];?>">
								<input type="hidden" name="profile" value="1">
								<p class="view" <?php echo $hide; ?>><input type="button" value="編集" class="edit_profile"></p>
								<p class="edit" <?php echo $show; ?>><span class="ok_button">更新</span><span class="cancel_button">Cancel</span></p>
							</td>
						</tr>
					</tfoot>
					<tbody>
						<tr>
							<th>メールアドレス</th>
							<td>
								<p class="view" <?php echo $hide; ?>><?php echo $email;?></p>
								<p class="edit" id="mail_addr" <?php echo $show; ?>><?php echo $email;?></p>
								<ins class="err"> <?php echo $err['email']; ?></ins>
							</td>
						</tr>
						<tr>
							<th>ユーザーネーム<span class="fontred">※</span></th>
							<td>
								<p class="view" <?php echo $hide; ?>><?php echo $username;?></p>
								<p class="edit" <?php echo $show; ?>><input type="text" name="uname" value="<?php echo $username;?>"></p>
								<ins class="err"> <?php echo $err['uname']; ?></ins>
							</td>
						</tr>
						<tr>
							<th>フリガナ</th>
							<td>
								<p class="view" <?php echo $hide; ?>><?php echo $userkana;?></p>
								<p class="edit" <?php echo $show; ?>><input type="text" name="ukana" value="<?php echo $userkana;?>"></p>
							</td>
						</tr>
					</tbody>
				</table>
			</form>
			<form class="section" name="pass" action="<?php echo $_SERVER['SCRIPT_NAME']; ?>" method="post" onSubmit="return false;">
				<table class="form_table me" id="pass_table">
					<h2>パスワードの変更</h2>
					<tfoot>
						<tr>
							<td colspan="2">
								<input type="hidden" name="token" value="<?php echo $_SESSION['token']; ?>">
								<input type="hidden" name="userid" value="<?php echo $me['id'];?>">
								<input type="hidden" name="mypass" value="1">
								<p><span class="ok_button">更新</span><span class="cancel_button">Cancel</span></p>
							</td>
						</tr>
					</tfoot>
					<tbody>
						<tr>
							<th>パスワード</th>
							<td><input type="password" name="pass" value=""><br><ins class="err"> <?php echo $err['pass']; ?></ins></td>
						</tr>
						<tr>
							<th>パスワード確認用</th>
							<td><input type="password" name="passconf" value=""><br><ins class="err"> <?php echo $err['passconf']; ?></ins></td>
						</tr>
					</tbody>
				</table>
			</form>


			<form class="section" name="addr" action="<?php echo $_SERVER['SCRIPT_NAME']; ?>" method="post" onSubmit="return false;">
				<table class="form_table addr" id="addr_table">
					<h2>住所の変更</h2><ins class="err"> <?php echo $err['addr']; ?></ins>
					<tfoot>
						<tr>
							<td colspan="2">
								<input type="hidden" name="token" value="<?php echo $_SESSION['token']; ?>">
								<input type="hidden" name="userid" value="<?php echo $me['id'];?>">
								<input type="hidden" name="myaddr" value="1">
								<p><span class="ok_button">更新</span><span class="cancel_button">Cancel</span></p>
							</td>
						</tr>
					</tfoot>
					<tbody>
						<tr>
							<th>〒郵便番号<span class="fontred">※</span></th>
					  	<td><input type="text" name="zipcode" class="forZip" id="zipcode1" value="<?php echo $u[0]['zipcode']; ?>" onChange="AjaxZip3.zip2addr(this,'','addr0','addr1');" />
							<ins class="err"> <?php echo $err['zipcode']; ?></ins></td>
						</tr>
						<tr>
						<th>都道府県<span class="fontred">※</span></th>
					  	<td><input type="text" name="addr0" id="addr0" value="<?php echo $u[0]['addr0']; ?>" maxlength="4" />
							<br><ins class="err"> <?php echo $err['addr0']; ?></ins></td>
						</tr>
						<tr>
						  <th>市 / 区<span class="fontred">※</span></th>
						  <td><input type="text" name="addr1" id="addr1" value="<?php echo $u[0]['addr1']; ?>" placeholder="文字数は全角28文字、半角56文字です" maxlength="56" class="restrict" />
							<br><ins class="err"> <?php echo $err['addr1']; ?></ins></td>
						</tr>
						<tr>
						<th>アドレス<span class="fontred">※</span></th>
							<td><input type="text" name="addr2" id="addr1" value="<?php echo $u[0]['addr2']; ?>" placeholder="文字数は全角16文字、半角32文字です" maxlength="32" class="restrict" />
							<br><ins class="err"> <?php echo $err['addr2']; ?></ins></td>
						</tr>
						<tr>
						<th>電話番号<span class="fontred">※</span></th>
							<td><input type="text" name="tel" id="tel" class="forPhone" value="<?php echo $u[0]['tel']; ?>"/>
							<br><ins class="err"> <?php echo $err['tel']; ?></ins></td>
						</tr>
					</tbody>
				</table>
			</form>

			<form class="section" name="deli" action="<?php echo $_SERVER['SCRIPT_NAME']; ?>" method="post" onSubmit="return false;">
				<table class="form_table deli" id="deli_table">
					<h2>お届先1の変更</h2><ins class="err"> <?php echo $err['a_deliaddr']; ?></ins>
					<tfoot>
						<tr>
							<td colspan="2">
								<input type="hidden" name="token" value="<?php echo $_SESSION['token']; ?>">
								<input type="hidden" name="userid" value="<?php echo $me['id'];?>">
								<input type="hidden" name="mydeli" value="1">
								<input type="hidden" name="deliid" value="<?php echo $deli[0]['id'];?>">
								<p><span class="ok_button">更新</span><span class="cancel_button">Cancel</span></p>
							</td>
						</tr>
					</tfoot>
					<tbody>
						<tr>
							<th>お届先<span class="fontred">※</span></th>
					  		<td><input type="text" name="organization" id="organization" value="<?php echo $deli[0]["organization"]; ?>" maxlength="30" class="restrict" />
								<br><ins class="err"> <?php echo $err['a_organization']; ?></ins></td>
						</tr>
						<tr>
							<th>〒郵便番号<span class="fontred">※</span></th>
					  	<td><input type="text" name="delizipcode" class="forZip" id="zipcode1" value="<?php echo $deli[0]["delizipcode"]; ?>" onChange="AjaxZip3.zip2addr(this,'','deliaddr0','deliaddr1');" />
							<ins class="err"> <?php echo $err['a_delizipcode']; ?></ins></td>
						</tr>
						<tr>
						<th>都道府県<span class="fontred">※</span></th>
					  	<td><input type="text" name="deliaddr0" id="addr0" value="<?php echo $deli[0]["deliaddr0"]; ?>" maxlength="4" />
							<br><ins class="err"> <?php echo $err['a_deliaddr0']; ?></ins></td>
						</tr>
						<tr>
						  <th>住所１<span class="fontred">※</span></th>
						  <td><input type="text" name="deliaddr1" id="addr1" value="<?php echo $deli[0]["deliaddr1"]; ?>" placeholder="文字数は全角28文字、半角56文字です" maxlength="56" class="restrict" />
							<br><ins class="err"> <?php echo $err['a_deliaddr1']; ?></ins></td>
						</tr>
						<tr>
						<th>住所２<span class="fontred">※</span></th>
							<td><input type="text" name="deliaddr2" id="addr1" value="<?php echo $deli[0]["deliaddr2"];?>" placeholder="文字数は全角16文字、半角32文字です" maxlength="32" class="restrict" />
							<br><ins class="err"> <?php echo $err['a_deliaddr2']; ?></ins></td>
						</tr>
						<tr>
						<th>会社・部門１</th>
							<td><input type="text" name="deliaddr3" id="addr1" value="<?php echo $deli[0]["deliaddr3"];?>" placeholder="文字数は全角16文字、半角32文字です" maxlength="32" class="restrict" /></td>
						</tr>
						<tr>
						<th>会社・部門２</th>
							<td><input type="text" name="deliaddr4" id="addr1" value="<?php echo $deli[0]["deliaddr4"];?>" placeholder="文字数は全角16文字、半角32文字です" maxlength="32" class="restrict" /></td>
						</tr>
						<tr>
						<th>電話番号<span class="fontred">※</span></th>
							<td><input type="text" name="delitel" id="tel" class="forPhone" value="<?php echo $deli[0]["delitel"] ?>"/>
							<br><ins class="err"> <?php echo $err['a_delitel']; ?></ins></td>
						</tr>
						<tr></tr>
					</tbody>
				</table>
			</form>

			<form class="section" name="deli1" action="<?php echo $_SERVER['SCRIPT_NAME']; ?>" method="post" onSubmit="return false;">
				<table class="form_table deli1" id="deli1_table">
					<h2>お届先2の変更</h2><ins class="err"> <?php echo $err['b_deliaddr']; ?></ins>
					<tfoot>
						<tr>
							<td colspan="2">
								<input type="hidden" name="token" value="<?php echo $_SESSION['token']; ?>">
								<input type="hidden" name="userid" value="<?php echo $me['id'];?>">
								<input type="hidden" name="deliid1" value="<?php echo $deli[1]['id'];?>">
								<input type="hidden" name="mydeli1" value="1">
								<p><span class="ok_button">更新</span><span class="cancel_button">Cancel</span></p>
							</td>
						</tr>
					</tfoot>
					<tbody>
						<tr>
							<th>お届先<span class="fontred">※</span></th>
					  	<td><input type="text" name="deli1organization" id="deli1organization" value="<?php echo $deli[1]["organization"]; ?>" maxlength="30" class="restrict" />
							<br><ins class="err"> <?php echo $err['b_deli1organization']; ?></ins></td>
						</tr>
						<tr>
							<th>〒郵便番号<span class="fontred">※</span></th>
					  	<td><input type="text" name="deli1zipcode" class="forZip" id="zipcode1" value="<?php echo $deli[1]["delizipcode"]; ?>" onChange="AjaxZip3.zip2addr(this,'','deli1addr0','deli1addr1');" />
							<ins class="err"> <?php echo $err['b_delizipcode']; ?></ins></td>
						</tr>
						<tr>
						<th>都道府県<span class="fontred">※</span></th>
					  	<td><input type="text" name="deli1addr0" id="addr0" value="<?php echo $deli[1]["deliaddr0"]; ?>" maxlength="4" />
							<br><ins class="err"> <?php echo $err['b_deliaddr0']; ?></ins></td>
						</tr>
						<tr>
						  <th>住所１<span class="fontred">※</span></th>
						  <td><input type="text" name="deli1addr1" id="addr1" value="<?php echo $deli[1]["deliaddr1"]; ?>" placeholder="文字数は全角28文字、半角56文字です" maxlength="56" class="restrict" />
							<br><ins class="err"> <?php echo $err['b_deliaddr1']; ?></ins></td>
						</tr>
						<tr>
							<th>住所２<span class="fontred">※</span></th>
								<td><input type="text" name="deli1addr2" id="addr1" value="<?php echo $deli[1]["deliaddr2"];?>" placeholder="文字数は全角16文字、半角32文字です" maxlength="32" class="restrict" />
								<br><ins class="err"> <?php echo $err['b_deliaddr2']; ?></ins></td>
						</tr>
						<tr>
						<th>会社・部門１</th>
							<td><input type="text" name="deli1addr3" id="addr1" value="<?php echo $deli[1]["deliaddr3"];?>" placeholder="文字数は全角16文字、半角32文字です" maxlength="32" class="restrict" /></td>
						</tr>
						<tr>
						<tr>
						<th>会社・部門２</th>
							<td><input type="text" name="deli1addr4" id="addr1" value="<?php echo $deli[1]["deliaddr4"];?>" placeholder="文字数は全角16文字、半角32文字です" maxlength="32" class="restrict" /></td>
						</tr>
						<tr>
						<th>電話番号<span class="fontred">※</span></th>
							<td><input type="text" name="deli1tel" id="tel" class="forPhone" value="<?php echo $deli[1]["delitel"] ?>"/>
							<br><ins class="err"> <?php echo $err['b_delitel']; ?></ins></td>
						</tr>
					</tbody>
				</table>
			</form>
			
				</div>
				<p class="totop">
					<a href="#head1">アカウント　ページトップへ</a>
				</p>
			</section>
			<?php include '../common/side_nav - test.html'; ?>
		</div>
		<?php include '../common/footer - test.html'; ?>
		<div id="msgbox" class="modal fade" tabindex="-1" role="dialog">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
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