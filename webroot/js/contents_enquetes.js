var _studySec		= 0;														// 学習時間
var _timerID		= null;														// 制限時間タイマーID

$(document).ready(function()
{
	// テスト結果表示モード以外の場合、制限時間を表示
	if(!IS_RECORD)
	{
		setStudySec();
		_timerID = setInterval(setStudySec, 1000);
	}
});

// 学習時間の更新
function setStudySec()
{
	$("#ContentsQuestionStudySec").val(_studySec);
	_studySec++;
}

// 送信確認画面を表示
function showConfirm()
{
	if(isComplete())
	{
		$('.modal-body').removeClass('bg-danger');
		$('.answer-incomplete').hide();
	}
	else
	{
		$('.modal-body').addClass('bg-danger');
		$('.answer-incomplete').show();
	}
	
	$('#confirmModal').modal();
}

// 未回答チェック
function isComplete()
{
	var cnt_radio = $('input[type="radio"]').length;
	
	for (var i=0; i < cnt_radio; i++)
	{
		var name = $('input[type="radio"]')[i].name;
		var cnt  = $('input[name="' + name + '"]:checked').length;
		
		if(cnt == 0)
			return false;
	}
	
	var cnt_text = $('textarea').length;
	
	for (var i=0; i < cnt_text; i++)
	{
		var val = $($('textarea')[i]).val();
		
		if(val == '')
			return false;
	}
	
	return true;
}

// 回答データの送信
function sendData()
{
	// 重複送信防止の為、ボタンを無効化
	$('.btn').prop('disabled', true);
	
	$('form').submit();
	return;
}