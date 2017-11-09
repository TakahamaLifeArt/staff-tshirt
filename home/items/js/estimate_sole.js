/**
*	Takahama Life Art
*	見積　（単一商品）
*	charset utf-8
*	log
*	2016-10-26 Staff-tshirtの商品詳細ページ用
*	2017-11-09 プリント位置指定のレイアウト変更に伴い絵型面の指定箇所を変更
*/

$(function(){

	jQuery.extend({
		curitemid: '',	// アイテムが固定の場合に対応
		printparam:{
			/*
		*	プリント代計算で使用するパラメーター
		*/
			'itemid':[],
			'sizeid':[],
			'size':[],
			'cost':[],
			'amount':[],
			'pos':[],
			'ink':[],
			'color':[]
		},
		clearparam: function(){
			$.printparam.itemid = [];
			$.printparam.sizeid = [];
			$.printparam.size = [];
			$.printparam.cost = [];
			$.printparam.amount = [];
			$.printparam.pos = [];
			$.printparam.ink = [];
			$.printparam.color = [];
		},
		init: function(){
			$('#color_thumb li img').imagesLoaded(function(){$('#item_colors').fadeIn();});
			$.curitemid = _ITEM_ID;
			$.setPrintposEvent();
			var colorcode = $('#item_image_l').attr('src');
			colorcode = colorcode.slice(colorcode.lastIndexOf('_')+1, colorcode.lastIndexOf('.'));
			$.showSizeform($.curitemid, colorcode, []);
		},
		changeThumb: function(img){
			/*
		*	見積もり商品のサムネイルの変更
		*	@img	imgタグ
		*/
			var colorcode = img.attr("alt");
			var colorname = img.attr("title");
			$(".notes_color").html(colorname);
			$(".color_thumb li.nowimg").removeClass("nowimg");
			img.parent().addClass("nowimg");
			var tmp = {};
			$('#price_wrap table tbody tr:odd td').each( function(index){
				var sizeid = $(this).attr('class').split('_')[1];
				tmp[sizeid] = $(this).find('input.forNum').val();
			});
			$.showSizeform($.curitemid, colorcode, tmp);
		},
		focusNumber: function(my){
			if($(my).val()=="0") {
				$(my).val("");
			}
		},
		showSizeform: function(itemid, colorcode, volume){
			/*
		*	サイズごとの枚数入力フォーム
		*	@itemid			アイテムID
		*	@colorcode		アイテムカラーコード
		*	@volume			サイズIDをキーにした枚数のハッシュ
		*/
			$.getJSON($.TLA.api+'?callback=?', {'act':'sizeprice', 'itemid':itemid, 'colorcode':colorcode, 'output':'jsonp'}, function(r){
				var pre_sizeid = 0;
				var cost = 0;
				var amount = 0;
				var size_head = '';
				var size_body = '';
				var sum = 0;
				var size_table = '';
				$.each(r, function(key, val){
					if(typeof volume[val.id]=='undefined'){
						amount = 0;
					}else{
						amount = volume[val.id]-0;
					}
					sum += amount;
					if(key==0){
						pre_sizeid = val['id'];
						cost = val['cost'];
						size_head = '<th></th><th>'+val['name']+'</th>';
						size_body = '<th data-label="1枚単価">'+$.addFigure(val['cost'])+' 円</th><td class="size_'+val['id']+'_'+val['name']+'_'+val['cost']+'" data-label="'+val['name']+'">';
						size_body += '<input id="size_'+val['id']+'" type="number" value="'+amount+'" min="0" max="999" class="forNum" onfocus="$.focusNumber(this);" onchange="$.addOrder();" /></td>';
					}else if(cost != val['cost'] || (val['id']>(++pre_sizeid) && val['id']>10)){	// 単価が違うかまたは、サイズ160以下を除きサイズが連続していない
						size_table += '<tr class="heading">'+size_head+'</tr>';
						size_table += '<tr>'+size_body+'</tr>';

						pre_sizeid = val['id'];
						cost = val['cost'];
						size_head = '<th></th><th>'+val['name']+'</th>';
						size_body = '<th data-label="1枚単価">'+$.addFigure(val['cost'])+' 円</th><td class="size_'+val['id']+'_'+val['name']+'_'+val['cost']+'" data-label="'+val['name']+'">';
						size_body += '<input id="size_'+val['id']+'" type="number" value="'+amount+'" min="0" max="999" class="forNum" onfocus="$.focusNumber(this);" onchange="$.addOrder();" /></td>';
					}else{
						pre_sizeid = val['id'];
						size_head += '<th>'+val['name']+'</th>';
						size_body += '<td class="size_'+val['id']+'_'+val['name']+'_'+val['cost']+'" data-label="'+val['name']+'">';
						size_body += '<input id="size_'+val['id']+'" type="number" value="'+amount+'" min="0" max="999" class="forNum" onfocus="$.focusNumber(this);" onchange="$.addOrder();" /></td>';
					}
				});
				size_table += '<tr class="heading">'+size_head+'</tr>';
				size_table += '<tr>'+size_body+'</tr>';
				$('table:first tbody', '#price_wrap').html(size_table);

				$.addOrder(false);
			});

		},
		setPrintposEvent: function(){
			/*
		*	プリント位置と色数の変更イベント設定
		*/
			$('#pos_wrap ul li').each( function(){
				$(this).find('img:not(:nth-child(1))').each(function(index) {
					var postfix = '_on';
					var img = $(this);
					var id = img.parent().attr('class').split('_')[1];
					var src = img.attr('src');
					var src_on = src.substr(0, src.lastIndexOf('.'))
					+ postfix
					+ src.substring(src.lastIndexOf('.'));
					$('<img>').attr('src', src_on);
					img.hover(
						function() {
							img.not('.cur').attr('src', src_on);
						},
						function() {
							img.not('.cur').attr('src', src);
						}
					).click( function(){
						if(img.is('.cur')) return;
						var cur = $(this).siblings('img.cur');
						cur.attr('src', cur.attr('src').replace(/_on.png$/, '.png')).removeClass('cur');
						img.attr('src', src_on);
						img.addClass('cur');
						$('.posname_'+id).text(img.attr('alt'));
					});

					if(index==0){
						img.not('.cur').attr('src', src_on).addClass('cur');
						$('.posname_'+id).text(img.attr('alt'));
					}
				});
			});

			// 色数指定で計算開始
			$('#pos_wrap select').change( function(){
				$.addOrder();
			});
		},
		resetResult: function(){
			/*
		*	見積もり金額の表示クリア
		*	@arguments	メッセージ
		*/
			$('#totamount span, #result span, #perone span, #baseprice span, #salestax span').text('0');
			if(arguments.length>0) $.msgbox(arguments[0]);
		},
		addOrder: function(){
		/*
		*	見積計算のハッシュを生成する
		*	@arguments　false: 枚数のチェックを行なわない
		*/
			var item_id = $.curitemid;
			var size_id = [];
			var size = [];
			var cost = [];
			var posi = [];
			var inks = [];
			var volm = [];
			var a = 0;
			var colorName = $('#price_wrap .item_colors .thumb_h .notes_color').text();

			$.clearparam();

			$('#price_wrap table tbody tr:odd td').each( function(index){
				var v = $(this).find('input.forNum').val();
				if(v==0) return true;
				var param = $(this).attr('class').split('_');
				size_id[a] = param[1];
				size[a] = param[2];
				cost[a] = param[3];
				volm[a] = v;
				a++;
			});

			if(a==0 && arguments[0]!==false){
				$.resetResult('枚数をご指定ください。');
				return;
			}

			a = 0;
			$('#pos_wrap select').each( function(){
				var self = $(this);
				var idx = self.attr('class').split('_');
				var ink = self.val()-0;
//				var posname = $(this).parent().prev().text();
				var posname = self.closest('.psnv').find('.posname_'+idx[1]).text();
				if(ink>0){
					posi[a] = posname;
					inks[a] = ink;
					a++;
				}
			});

			if(a==0){
				$.resetResult();
				return;
			}

			for(var x=0; x<size.length; x++){	
				for(var y=0; y<posi.length; y++){
					$.printparam.itemid.push(item_id);
					$.printparam.sizeid.push(size_id[x]);
					$.printparam.size.push(size[x]);
					$.printparam.cost.push(cost[x]);
					$.printparam.amount.push(volm[x]);
					$.printparam.pos.push(posi[y]);
					$.printparam.ink.push(inks[y]);
					$.printparam.color.push(colorName);
				}
			}

			$.calcPrice();
		},
		calcPrice: function(){
			/*
		*	プリント代を取得
		*/
			if($.printparam.itemid.length==0){
				$.resetResult();
				return;
			}

			var output = false;
			if(arguments.length>0) output=arguments[0];

			/*
			*	アイテムのサイズ毎で保持している枚数をアイテム毎に集計する
			*	（アイテムとサイズの昇順になっていること）
			*/
			var tmpVol = 0;
			var presize = 0;
			var itemsum = 0;
			for(var i=0; i<$.printparam.itemid.length; i++){
				vol = $.printparam.amount[i]-0;
				if(i==0){
					tmpVol = $.printparam.amount[i]-0;
					itemsum = ($.printparam.cost[i]-0) * ($.printparam.amount[i]-0);
				}else if(presize!=$.printparam.size[i]){
					tmpVol += $.printparam.amount[i]-0;
					itemsum += ($.printparam.cost[i]-0) * ($.printparam.amount[i]-0);
				}
				presize = $.printparam.size[i];
			}

			var amount = $.printparam.amount.slice(0);
			for(var j=0; j<amount.length; j++){
				amount[j] = tmpVol;
			}

			var optionId = $.printparam.color[0]!='ホワイト'? 1: 0;
			var inkjetOption = {};
			inkjetOption[optionId] = amount[0];
			var param = {'act':'printfee', 'output':'jsonp', 'args':[]};
			var args = [];
			var existPos = {};
			for (var i=0; i<$.printparam.itemid.length; i++) {
				if (existPos.hasOwnProperty($.printparam.pos[i])) continue;
				args[i] = { 
					'itemid':$.printparam.itemid[i], 
					'amount':amount[i], 
					'pos':$.printparam.pos[i], 
					'ink':$.printparam.ink[i],
					'size':0,
					'option':inkjetOption
				};
				existPos[$.printparam.pos[i]] = true;
			}
			param['args'] = args;
			$.getJSON($.TLA.api+'?callback=?', param, function(r){
				//				var str = new String(r.volume);
				//				if(!str.match(/^\d+$/)) return;

				var base = itemsum + (r.printfee-0);
				var tax = Math.floor( base * (r.tax/100) );
				var result = Math.floor( base * (1+r.tax/100) );
				var perone = Math.ceil(result/tmpVol);

				if(!output){
					$('#baseprice span').text($.addFigure(base));
					$('#salestax span').text($.addFigure(tax));
					$('#result span').text($.addFigure(result));
					$('#totamount span').text(tmpVol);
					$('#perone span').text($.addFigure(perone));
				}else{
					if(typeof output.attr('value')=='undefined') output.text($.addFigure(perone));
					else output.val($.addFigure(perone));
				}
			});
		},
		updateItem: function(){
			/*
		*	カートに商品を追加更新
		*/
			var postData = {};
			var mode = 'update';
			var isResult = false;

			postData = {'act':'update', 'mode':'items'};
			postData.categoryid = _CAT_ID;
			postData.categorykey = _CAT_KEY;
			postData.categoryname = _CAT_NAME;
			postData.itemid = $.curitemid;
			postData.itemcode = _ITEM_CODE;
			postData.itemname = _ITEM_NAME;
			postData.posid = _POS_ID;

			// 全サイズを上書する
			var color_name = $('.thumb_h .notes_color', '#price_wrap').text();
			var color_code = $('.color_thumb li.nowimg img', '#price_wrap').attr("alt");
			var totAmount = 0;
			postData['sizeid'] = [];
			postData['sizename'] = [];
			postData['cost'] = [];
			postData['amount'] = [];
			postData['colorcode'] = [];
			postData['colorname'] = [];

			$('#price_wrap table tbody tr:odd td').each( function(index){
				var v = $(this).find('input.forNum').val();
				var param = $(this).attr('class').split('_');
				postData['sizeid'][index] = param[1];
				postData['sizename'][index] = param[2];
				postData['cost'][index] = param[3];
				postData['amount'][index] = v;
				postData['colorcode'][index] = color_code;
				postData['colorname'][index] = color_name;

				totAmount += v;
			});

			if(totAmount==0){
				return false;
			}

			var curRow = 0;
			$.ajax({url:'/php_libs/orders.php', async:false, type:'POST', dataType:'json', data:postData, 
					success:function(r){
						if(r.length!=0){
							isResult = true;
						}
					}
				   });

			return isResult;
		},
		updatePosition: function(){
			/*
		*	プリント位置とインク色数を更新
		*/
			var posid = _POS_ID;
			var base = [];
			var posname = [];
			var ink = [];
			var attach = [];
			var isResult = false;

			$('#pos_wrap table tbody tr:eq(3) td').each( function(){
				var base_name = $(this).attr('class');
				$('div.inks', this).each( function(){
					var v = $(this).find('select').val();
					if(v>0){
						base.push(base_name);
						posname.push( $(this).children('p:first').text() );
						ink.push( v );

						isResult = true;
					}
				});
			});

			if(isResult){
				$.ajax({url:'/php_libs/orders.php', async:false, type:'POST', dataType:'json', 
						data:{'act':'update','mode':'design', 'posid':posid, 'base':base, 'posname':posname, 'ink':ink, 'attachname':attach}, success: function(r){
							if(r.length!=0){
								isResult = r;
							}
						}
					   });
			}

			return isResult;
		}
	});


	/* 注文フォームへ遷移 */
	$('#btnOrder, #btnOrder_up').click( function(){
		var f = $(this).closest("form");
		var func = function(){
			var step = 1;
			if($('#result span').text()!='0'){
				if($.updateItem()){
					if($.updatePosition()){
						step = 3;
					}
				}
			}
			document.getElementById("update").value = step;
			f.submit();
		};

		// メーカー「ザナックス」の場合にポップアップ
		if($(this).hasClass('popup')){
			$.confbox.show(
				'<h3 class="fontred">★要確認</h3>'+
				'<div style="padding:0.5em;"><p>'+
				'このアイテムはメーカーの在庫状況が不安定な為<br>'+
				'お申し込みフォームからご指定頂きました枚数の在庫確認を行った後<br>'+
				'弊社から「在庫有無・納期」のご連絡をさせて頂きます。<br>'+
				'メーカに在庫が無い場合は受注生産となり、納期を2~3週間頂く場合がございます。'+
				'</p>'+
				'<p class="note" style="margin-bottom:1em;"><span>※</span>在庫状況によっては、ご希望に添えない場合がございます。</p>'+
				'<p style="margin-bottom:1em;">大変ご不便おかけしますが、何卒宜しくお願い致します。</p>'+
				'<p>お急ぎの方はお電話でのお問い合わせをお願いします。</p>'+
				'<address>0120-130-428</address></div>', 
				function(){
					if($.confbox.result.data){
						func();
					}else{
						// Do nothing.
					}
				}
			);
		}else{
			func();
		}
	});


	/* 見積もり商品のカラー変更 */
	$(".color_thumb").on('click', 'li img', function(){
		$.changeThumb($(this));
	});


	/* initialize */
	$.init();
});
