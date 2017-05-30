/*
	My Page
	charset utf-8
	2013-06-06	created
*/

$(function(){
	
	/********************************
	*	ユーザー情報を編集モードに切り替え
	*
	*/
	$('.edit_profile', '#profile_table').click( function(){
		$('.view', '#profile_table').hide();
		$('.edit', '#profile_table').show();
	});

	
	
	/********************************
	*	ユーザー情報を更新
	*/
	$('.ok_button', '#profile_table').click( function(){
		$('input[type="text"]', '#profile_table').each(function(){
			var data = $(this).val().trim();
			$(this).val(data);
		});
		document.forms.prof.submit();
	});
	
	
	/********************************
	*	ユーザー情報を表示モードに切り替え
	*/
	$('.cancel_button', '#profile_table').click( function(){
		document.forms.prof.reset();
		$('.err').text('');
		$('.edit', '#profile_table').hide();
		$('.view', '#profile_table').show();
	});
	
	
	/********************************
	*	パスワードを変更
	*/
	$('.ok_button', '#pass_table').click( function(){
		document.forms.pass.submit();
	});
	
	
	/********************************
	*	パスワードの変更をキャンセル
	*/
	$('.cancel_button', '#pass_table').click( function(){
		document.forms.pass.reset();
		$('.err').text('');
	});


	/********************************
	*	アドレスと電話を変更
	*/
	$('.ok_button', '#addr_table').click( function(){
		//var zipcode = $('#zipcode1').val();
		document.forms.addr.submit();
	});


	/********************************
	*	アドレスと電話の変更をキャンセル
	*/
	$('.cancel_button', '#addr_table').click( function(){
		document.forms.addr.reset();
		$('.err').text('');
	});

		/********************************
	*	お届け先1を変更
	*/
	$('.ok_button', '#deli_table').click( function(){
		document.forms.deli.submit();
	});


	/********************************
	*	お届け先1の変更をキャンセル
	*/
	$('.cancel_button', '#deli_table').click( function(){
		document.forms.deli.reset();
		$('.err').text('');
	});
		/********************************
	*	お届け先2を変更
	*/
	$('.ok_button', '#deli1_table').click( function(){
		document.forms.deli1.submit();
	});


	/********************************
	*	お届け先2の変更をキャンセル
	*/
	$('.cancel_button', '#deli1_table').click( function(){
		document.forms.deli1.reset();
		$('.err').text('');
	});

});
