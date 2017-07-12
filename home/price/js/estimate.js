/**
*	Takahama Life Art
*	見積
*	charset utf-8
*/

$(function(){
	
	jQuery.extend({
		init: function(){
			$('#color_wrap input[name=color]').val(['0']);
			$.setBody(1);
			var sess = sessionStorage;
			$.getJSON($.TLA.api+'?callback=?', {'act':'itemdetail', 'args':'', 'mode':'list','show_site':$.TLA.site, 'output':'jsonp'}, function(r){
				sess.setItem("itemhash", JSON.stringify(r));
			});
			$.when(
				$.ajax({url:'../php_libs/iteminfo.php', async:true, type:'GET', dataType:'json', 
				data:{'act':'itemtype', 'category_id':'', 'output':'jsonp'}})
			).then(function(r){
				$.items.itemTag = r;
				var tag = [];	// タグIDの配列
				var posId = {};	// タグIDをキーにした対応するプリント位置IDのハッシュ
				for(var ppId in $.items.itemTag[1]){
					var tagId = $.items.itemTag[1][ppId]['tag'];
					tag[tag.length] = tagId;
					posId[tagId] = ppId;
				}
				$.getJSON($.TLA.api+'?callback=?', {'act':'item', 'categoryid':1, 'mode':tag, 'show_site':$.TLA.site, 'output':'jsonp'}, function(r){
					jQuery.each(r, function(key, val){
						$.items.hash[val.id] = [val.code, val.name, val.cost, val.cost_color, val.item_row, posId[val.tag_id]];
					});
				});
				
				// シルエットの指定変更
				$('#boxwrap').on('change', 'input[name="body_type"]', function(){
					$.showPrintPosition($(this).val());
				});
				
				// インク色数の変更
				$('#pos_wrap').on('change', 'select', function(){
					$.resetResult();
				});
				
				// 枚数及びアイテムカラーの変更
				$('#order_amount, #color_wrap input').on('change', function(){
					$.resetResult();
				});
				
				/* 計算開始 */
				$('#btnEstimate').on('click', function(){
					$.calcPrice();
				});
			}).fail(function(jqXHR, textStatus, errorThrown){
				alert('Error: '+textStatus);
			});
		},
		items:{
		/*
		*	指定カテゴリのアイテム情報
		*	hash:	 [アイテムコード, アイテム名, 白色単価, 白以外単価, 表示順, プリント位置ID]
		* 	itemTag: [カテゴリID : [プリントポジションID : ['tag':タグID, 'label':タグ名] ] ]
		*/
			'hash':{},
			'itemTag':[],
		},
		resetResult: function(){
		/*
		*	計算結果の初期化とリストの非表示
		*/
			$("#recommend h2:first ins").html(0);
			$('#recommend .tab-contents .rankingitem').html('');
			$('.more, .rankingmore', '#recommend').hide();
			$('#tab2').click();
		},
		getStorage: function(key){
		/*
		*	sessionStorageのデータを取得
		*	@key		取得するデータのキー(itemhash)を指定、未指定（falseとなる0,"",null,undefined,false）で全てのデータ
		* 	@第二引数		アイテムコード
		*	return		{i_coloe_code, i_caption}
		*/
	  		var sess = sessionStorage;
			var store = {};
			if(!key){
				for(var key in sess){
					store[key] = JSON.parse(sess.getItem(key));
				}
			}else{
				store[key] = JSON.parse(sess.getItem(key));
				if(arguments.length>1){
					store = store[key][arguments[1]];
				}
			}
			return store;
		},
		changeCategory: function(my){
		/*
		*	商品カテゴリーの変更
		*/
			$('#boxwrap').css('visibility','hidden');
			var categoryId = my.options[my.selectedIndex].value;
			var tag = [];	// タグIDの配列
			var posId = {};	// タグIDをキーにした対応するプリント位置IDのハッシュ
			for(var ppId in $.items.itemTag[categoryId]){
				var tagId = $.items.itemTag[categoryId][ppId]['tag'];
				tag[tag.length] = tagId;
				posId[tagId] = ppId;
			}
			$.items.hash = {};
			$.getJSON($.TLA.api+'?callback=?', {'act':'item', 'categoryid':categoryId, 'mode':tag, 'show_site':$.TLA.site, 'output':'jsonp'}, function(r){
				jQuery.each(r, function(key, val){
					$.items.hash[val.id] = [val.code, val.name, val.cost, val.cost_color, val.item_row, posId[val.tag_id]];
				});
				$.setBody(categoryId);
			});
		},
		setBody: function(id){
		/*
		*	アイテムタイプの書換
		*	@id		category ID
		*/
			$.ajax({url:'../php_libs/iteminfo.php', async:true, type:'POST', dataType:'text', 
				data:{'act':'body','category_id':id}, success: function(r){
					$('#boxwrap').html(r).css('visibility','visible');
					var posid = $('#boxwrap').find('.check_body:checked').val();
					$.showPrintPosition(posid);
				}
			});
		},
		showPrintPosition: function(id){
		/*
		*	プリント位置画像（絵型）とインク色数指定の生成
		*	@id		print position ID
		*/
			$.ajax({url:'../php_libs/iteminfo.php', async:true, type:'POST', dataType:'text', 
				data:{'act':'position','pos_id':id}, success: function(r){
					$('#pos_wrap ul').html(r);
					$.init_position();
					$.resetResult();
				}
			});
		},
		init_position: function(){
		/*
		*	絵型のマウスクリックイベント設定
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
		},
		calcPrice: function(){
		/*
		*	見積計算
		*	アイテムコード、枚数、インク色数、プリント位置の配列　[itemcode, amount, ink, pos][...]
		*/
			var vol = $('#order_amount').val();
			var pos_count = 0;
			var itemid = [];
			var amount = [];
			var pos = [];
			var ink = [];
			var size = [];
			var option = [];
			var optionValue = $('#color_wrap input[name=color]:checked').val();
			var ppID = $('#boxwrap .check_body:checked').val();	// 絵型ID
			var category_key = $("#category_selector option:selected").data('code');
			
			// デザインの数
			$('#pos_wrap ul li').each(function(){
				var self = $(this);
				var select = self.find('select');
				var idx = select.attr('class').split('_')[1];
				var ink_count = select.val();
				var pos_name = self.find('.posname_'+idx).text();
				if(ink_count==0) return true;		// continue
				for(var itemId in $.items.hash){
					if($.items.hash[itemId][5]!=ppID) continue;
					itemid.push(itemId);
					amount.push(vol);
					pos.push(pos_name);
					ink.push(ink_count);
					size.push(0);
					option.push(optionValue);
				}
				pos_count++;
			});
			
			$.resetResult();
			
			if(pos_count==0){
				$('#resultList').html('');
				alert('プリントする場所とデザインの色数を指定してください。');
				return;
			}
			
			var args = {'sheetsize':'1', 'act':'printfeelist', 'show_site':$.TLA.show_site, 'output':'jsonp', 'itemid':itemid, 'amount':amount, 'pos':pos, 'ink':ink, 'size':size, 'option':option};
			$.getJSON($.TLA.api+'?callback=?', args, function(r){
				// 見積り額と表示順を設定
				var costIndex = 2;	// 白色
				if(optionValue!=0) costIndex = 3;	// 白色以外
				jQuery.each(r, function(key, val){
					if (val.printfee==0) return true;
					r[key]['row'] = $.items.hash[val.itemid][4]-0;
					r[key]['base'] = ($.items.hash[val.itemid][costIndex]-0)*vol + (val.printfee-0);
				});
				var r2 = r.slice(0);
				
				// 安い順
				r.sort( function(a,b){
					if (a.base < b.base) return -1;
					if (a.base > b.base) return 1;
					if (a.row < b.row) return -1;
					if (a.row > b.row) return 1;
					return 0;
				});
				
				// 人気順（表示順）
				r2.sort( function(a,b){
					if (a.row < b.row) return -1;
					if (a.row > b.row) return 1;
					return 0;
				});
				
				var tab1 = ["", ""];
				var tab2 = ["", ""];
				var rank = ['', ' class="first"', ' class="second"', ' class="third"'];
				var ranking = "";
				var idx = 0;
				var num = 0;
				jQuery.each(r, function(key, val){
					var itemid = val.itemid;
					var itemcode = $.items.hash[itemid][0];
					var itemname = $.items.hash[itemid][1];
					var base = val.base-0;
					var tax = Math.floor( base * (val.tax/100) );
					var result = Math.floor( base * (1+val.tax/100) );
					var perone = $.addFigure(Math.ceil(result/vol));
					var itemHash = $.getStorage("itemhash", itemcode);
					ranking = "";
					num++;
					if(num<4){
						ranking = rank[num];
					}else if(num==4){
						idx++;
					}
					
					tab1[idx] += '<div class="item clearfix">';
						tab1[idx] += '<div class="left-tab">';
							tab1[idx] += '<p class="ttl"><span'+ranking+'>'+num+'位</span>'+itemHash['i_caption']+'</p>';
							tab1[idx] += '<div class="img"><img src="'+_IMG_PSS+'items/list/'+category_key+'/'+itemcode+'/'+itemcode+'_'+itemHash['i_color_code']+'.jpg"></div>';
							tab1[idx] += '<div class="name">'+itemname+'</div>';
						tab1[idx] += '</div>';
						tab1[idx] += '<div class="right-tab">';
							tab1[idx] += '<div class="arrow">一枚当たり</div>';
							tab1[idx] += '<span class="tri"></span>';
							tab1[idx] += '<div class="per">￥'+perone+'&#65374;</div>';
							tab1[idx] += '<p class="total">合計'+$.addFigure(result)+'&#65374;</p>';
							tab1[idx] += '<div class="taCenter">';
								tab1[idx] += '<div class="detail"><a href="/items/itemdetail.php?c='+category_key+'&i='+itemid+'">詳細を見る</a></div>';
								tab1[idx] += '<div class="apply"><a href="/order/index.php?item_id='+itemid+'&update=1">お申し込みへ</a></div>';
							tab1[idx] += '</div>';
						tab1[idx] += '</div>';
					tab1[idx] += '</div>';
					
					itemid = r2[key].itemid;
					itemcode = $.items.hash[itemid][0];
					itemname = $.items.hash[itemid][1];
					base = r2[key].base-0;
					tax = Math.floor( base * (r2[key].tax/100) );
					result = Math.floor( base * (1+r2[key].tax/100) );
					perone = $.addFigure(Math.ceil(result/vol));
					itemHash = $.getStorage("itemhash", itemcode);
					tab2[idx] += '<div class="item clearfix">';
						tab2[idx] += '<div class="left-tab">';
							tab2[idx] += '<p class="ttl"><span'+ranking+'>'+num+'位</span>'+itemHash['i_caption']+'</p>';
							tab2[idx] += '<div class="img"><img src="'+_IMG_PSS+'items/list/'+category_key+'/'+itemcode+'/'+itemcode+'_'+itemHash['i_color_code']+'.jpg"></div>';
							tab2[idx] += '<div class="name">'+itemname+'</div>';
						tab2[idx] += '</div>';
						tab2[idx] += '<div class="right-tab">';
							tab2[idx] += '<div class="arrow">一枚当たり</div>';
							tab2[idx] += '<span class="tri"></span>';
							tab2[idx] += '<div class="per">￥'+perone+'&#65374;</div>';
							tab2[idx] += '<p class="total">合計'+$.addFigure(result)+'&#65374;</p>';
							tab2[idx] += '<div class="taCenter">';
								tab2[idx] += '<div class="detail"><a href="/items/itemdetail.php?c='+category_key+'&i='+itemid+'">詳細を見る</a></div>';
								tab2[idx] += '<div class="apply"><a href="/order/index.php?item_id='+itemid+'&update=1">お申し込みへ</a></div>';
							tab2[idx] += '</div>';
						tab2[idx] += '</div>';
					tab2[idx] += '</div>';
				});
				$("#recommend h2:first ins").html(num);
				$('#recommend .tab1:eq(0) .rankingitem').html(tab1[0]);
				$('#recommend .tab1:eq(1) .rankingitem').html(tab1[1]);
				$('#recommend .tab2:eq(0) .rankingitem').html(tab2[0]);
				$('#recommend .tab2:eq(1) .rankingitem').html(tab2[1]);
				$('#recommend .more').show();
			});
		},
		addFigure:function(args){
		/*
		*	金額の桁区切り
		*	@arg		対象の値
		*
		*	@return		桁区切りした文字列
		*/
			var str = new String(args);
			str = str.replace(/[０-９]/g, function(m){
	    				var a = "０１２３４５６７８９";
		    			var r = a.indexOf(m);
		    			return r==-1? m: r;
		    		});
		    str -= 0;
	    	var num = new String(str);
	    	if( num.match(/^[-]?\d+(\.\d+)?/) ){
	    		while(num != (num = num.replace(/^(-?\d+)(\d{3})/, "$1,$2")));
	    	}else{
	    		num = "0";
	    	}
	    	return num;
		}
	});
	
	/*
	 * カテゴリ変更イベント
	 */
	$('#category_selector').on('change', function(){
		$.changeCategory(this);
	});
	
	/* タブ */
	$('.tab-index a').click(function(e){
		var $self = $(this);
		var id = $self.attr('id');
		$('.tab-index .active').removeClass('active');
		$self.parent().addClass('active');
		$('.tab-contents').each(function(){
			$(this).removeClass('active');
		});
		$('.'+id, '#recommend').addClass('active');
		e.preventDefault();
	});
	
	
	/* さらに表示 */
	$('#recommend .more').on('click', function() {
		$(this).hide();
		$(this).next('.rankingmore').slideDown('fast');
	});
	
	
    /* initialize */
    $.init();
	
});
