/*
*	Takahama Life Art
*	
*	deliveryday module
*	charset utf-8
*/

$(function(){
	    
	jQuery.extend({
		schedule_date: "",
		holidayInfo: {},
		calc_delivery: function(){
		/*
		*	注文確定日からお届け日を計算
		*/
			$('.cal_date').hide();
			$('#datepicker_deliday').val('');
			if($.datepicker_firmorder=="") return;
			base = Date.parse( $.datepicker_firmorder )/1000;	
			var transport = 1;
			if( $('#transport').is(':checked') ){
				transport = 2;
			}
			var postData = {'act':'deliverydays', 'base':base, 'transport':transport, 'mode':'simple'};
			$.ajax({ url:'/php_libs/orders.php', type:'post', dataType:'json', async:true, data:postData, 
				success:function(r){
					// r[通常納期, 2日仕上げ, 翌日仕上げ, 当日仕上げ, 注文確定日]
					var $target = $('#cal_date_r ul');
					var dt = new Date();
					for(var i=0,t=3; i<4; i++,t--){
						var targetSec = Date.parse(r[t]);
						if(! isNaN(targetSec)){
							dt.setTime(targetSec);
							$target.children('li:eq('+i+')').find('.mm').text(dt.getMonth() + 1);
							$target.children('li:eq('+i+')').find('.dd').text(dt.getDate());
						}
					}
					$('#cal_date_r .heading4 span').text(r[4]);
					dt = new Date(r[0]);
					var d = dt.getFullYear() + "-" + ("00"+(dt.getMonth() + 1)).slice(-2) + "-" + ("00"+dt.getDate()).slice(-2);
					$('#datepicker_deliday').val(d);
					$('#arrow img').attr('src', 'img/b3.png');
					$('#cal_date_r').show();
				}
			});
		},
		calc_firmorder: function(){
		/*
		*	お届け日から注文確定日を計算
		*/
			$('.cal_date').hide();
			$('#datepicker_firmorder').val('');
			if($.datepicker_firmorder=="") return;
			var base = Date.parse( $.datepicker_firmorder )/1000;	
			var transport = 1;
			if( $('#transport').is(':checked') ){
				transport = 2;
			}
			var postData = {'act':'firmorderdays', 'base':base, 'transport':transport};
			$.ajax({ url:'/php_libs/orders.php', type:'post', dataType:'json', async:true, data:postData, 
				success:function(r){
					// r[通常納期, 2日仕上げ, 翌日仕上げ, 当日仕上げ]
					// 製作日数が足りない場合は除外
					var len = r.length;
					if(len==0){
						$.msgbox('制作日数が足りません。お届け希望日をご確認ください。');
						return;
					}
					var ids = ['',
						$('#cal_date_b0'),
						$('#cal_date_b1'),
						$('#cal_date_b2'),
						$('#cal_date_b3'),
						];
					var len = r.length;
					var $target = ids[len];
					var dt = new Date();
					for(var i=0,t=len-1; i<len; i++,t--){
						var targetSec = Date.parse(r[t]);
						if(! isNaN(targetSec)){
							dt.setTime(targetSec);
							$target.children('ul').children('li:eq('+i+')').find('.mm').text(dt.getMonth() + 1);
							$target.children('ul').children('li:eq('+i+')').find('.dd').text(dt.getDate());	
						}
						
					}
					$target.find('.heading4 span').text($.datepicker_firmorder);
					dt = new Date($.datepicker_firmorder);
					$('ul li', $target).each( function(){
						$(this).find('.otodoke_b span').text((dt.getMonth()+1)+'/'+dt.getDate());
					});
					dt = new Date(r[0]);
					var d = dt.getFullYear() + "-" + ("00"+(dt.getMonth() + 1)).slice(-2) + "-" + ("00"+dt.getDate()).slice(-2);
					$('#datepicker_firmorder').val(d);
					$('#arrow img').attr('src', 'img/b'+(len-1)+'.png');
					$target.show();
				} 
			});
		}
	});
	
	/* calendar */
	$("#datepicker_firmorder, #datepicker_deliday").datepicker({
		onSelect: function(dateText, inst) {
			$.datepicker_firmorder = dateText.replace(/-/g, '/');
		},
		beforeShowDay: function(date){
			var weeks = date.getDay();
			var texts = "";
			if(weeks == 0) texts = "休日";
			var YY = date.getFullYear();
			var MM = date.getMonth() + 1;
			var DD = date.getDate();
			var currDate = YY + "/" + MM + "/" + DD;
			var datesec = Date.parse(currDate)/1000;
			var key = YY+"_"+MM;
			if(!$.holidayInfo[key]){
				$.holidayInfo[key] = new Array();
				$.ajax({url: '../php_libs/checkHoliday.php',
						type: 'GET',
						dataType: 'text',
						data: {'datesec':datesec},
						async: false,
						success: function(r){
							if(r!=""){
								var info = r.split(',');
								for(var i=0; i<info.length; i++){
									$.holidayInfo[key][info[i]] = info[i];
								}
							}
						}
					   });
			}
			if($.holidayInfo[key][DD] && weeks!=6) weeks = 0;
			if(weeks == 0) return [true, 'days_red', texts];
			else if(weeks == 6) return [true, 'days_blue'];
			return [true];
		}
    });
	
	/* 日付を指定して納期確認 */
	$('#btnFirmorder, #btnDeliday').click( function(){
		var myName = $(this).attr('id');
		$.datepicker_firmorder = $(this).closest('.cal_date_inner').find('.forDate').val().replace(/-/g, '/');
		switch(myName){
			case 'btnFirmorder':
				if($('#datepicker_firmorder').val()==""){
					$.msgbox('注文確定日を指定して下さい');
				}else{
					$.calc_delivery();
				}
				break;
			case 'btnDeliday':
				if($('#datepicker_deliday').val()==""){
					$.msgbox('お届け日を指定して下さい');
				}else{
					$.calc_firmorder();
				}
				break;
		}
	});
	
	
	/* クラスTシャツキャンペーンのポップアップ */
	$('#campaign_class-t').click( function(){
		$.msgbox('<img src="/img/banner/pc-pop.png">');
	});
	
	/* 2大キャンペーンのポップアップ */
	$('.nidai_campaign').click( function(){
		$.msgbox('<img src="/img/banner/pop_pc_nidai.png">');
	});
	
});
