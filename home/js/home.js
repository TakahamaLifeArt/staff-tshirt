/**
 * staff-tshirt.com
 * for home page
 * depend on jQuery, sliderPro
 *-------------------
 * log
 * 2017-06-03 creted
 */
$(function(){
	
	// extended the jQuery object
	$.extend({
		holidayInfo: {},
		calc_firmorder: function(args, deliDay){
		/*
		 * お届け日から注文確定日を計算
		 * @param {string} args お届け希望日付（YYYY-MM-DD）
		 * @param {integer} deliDay 配送日数
		 */
			$('#delivery_date_wrapper .delivery_date_box').text('');
			if(args=="") return;
			var base = Date.parse( args )/1000;
			var postData = {'act':'firmorderdays', 'base':base, 'transport':deliDay};
			$.ajax({ url:'/php_libs/orders.php', type:'post', dataType:'json', async:true, data:postData, 
				success:function(r){
					// r[通常納期, 2日仕上げ, 翌日仕上げ, 当日仕上げ]
					// 製作日数が足りない場合は除外
					var len = r.length;
					if(len==0){
						$.msgbox('制作日数が足りません。お届け希望日をご確認ください。');
						return;
					}
					var ids = [
							   $('#delidate_1'),
							   $('#delidate_2'),
							   $('#delidate_3'),
							   $('#delidate_4')
							  ];
					var i = len - 1;
					var dt = new Date();
					for(var t=ids.length - 1; t>=0; t--){
						var targetSec = Date.parse(r[i--]);
						if(! isNaN(targetSec)){
							dt.setTime(targetSec);
							var d = dt.getFullYear() + "/" + ("00"+(dt.getMonth() + 1)).slice(-2) + "/" + ("00"+dt.getDate()).slice(-2);
							ids[t].text(d);
						} else {
							ids[t].text('');
						}
					}
				} 
			});
		}
	});
	
	// main visual
	$('#example3').sliderPro({
		width: 735,
		height: 300,
		fade: true,
		arrows: true,
		buttons: false,
		fullScreen: false,
		shuffle: true,
		smallSize: 500,
		mediumSize: 1000,
		largeSize: 3000,
		thumbnailArrows: true,
		autoplay: true
	});
	
	// side navigation
	var nav = $('.fixed');
	var offset = nav.offset();
	$(window).scroll(function() {
		if ($(window).scrollTop() > offset.top) {
			nav.addClass('fixed');
		} else {
			nav.removeClass('fixed');
		}
	});
	
	// destination
	$('#destination').on('change', function(){
		var dest = $('#destination option:selected').data('destination');
		var args = $("#datepicker").datepicker('getDate');
		args = $.datepicker.formatDate('yy/mm/dd', args);
		$.calc_firmorder(args, dest);
	});
	
	
	// calendar
	$("#datepicker").datepicker({
		onSelect: function(dateText, inst) {
			var dest = $('#destination option:selected').data('destination');
			var args = dateText.replace(/-/g, '/');
			$.calc_firmorder(args, dest);
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
});