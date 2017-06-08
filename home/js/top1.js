$( document ).ready(function($) {
	$('#thumb-h').sliderPro({
		responsive: true,
		width: 1000, //横幅
		buttons: false,//ナビゲーションボタン
		shuffle: false,//スライドのシャッフル
		thumbnailWidth: 180,//サムネイルの横幅
		thumbnailHeight: 50,//サムネイルの縦幅
		slideDistance:0,//スライド同士の距離   
		breakpoints: {
			480: {//表示方法を変えるサイズ
				thumbnailWidth: 110,
				thumbnailHeight: 40
			}
		}
	});
	/*globalnav*/
	$(".flexnav").flexNav();

	/*tab*/
	$('.tab li').click(function() {
		var index = $('.tab li').index(this);
//		$('.content li').css('display','none');
//		$('.content li').eq(index).css('display','block');
		$('.content li:not(.hide)').addClass('hide');
		$('.content li:eq('+index+')').removeClass('hide');
		$('.tab li').removeClass('select');
		$(this).addClass('select');
	});

	/*scroll*/
	$("a[href^=#]").click(function(){
		var Hash = $(this.hash);
		var HashOffset = $(Hash).offset().top;
		$("html,body").animate({
		scrollTop: HashOffset
		}, 500);
		return false;
	});

(function() {
var sb = document.getElementById('srchBox');
if (sb && sb.className == 'watermark') {
  var si = document.getElementById('srchInput');
  var f = function() { si.className = 'nomark'; };
  var b = function() {
    if (si.value == '') {
      si.className = '';
    }
  };
  si.onfocus = f;
  si.onblur = b;
  if (!/[&?]p=[^&]/.test(location.search)) {
    b();
  } else {
    f();
  }
}
})();





});
