/**
 * iroha Board Project
 *
 * @author        Kotaro Miura
 * @copyright     2015-2021 iroha Soft, Inc. (https://irohasoft.jp)
 * @link          https://irohaboard.irohasoft.jp
 * @license       https://www.gnu.org/licenses/gpl-3.0.en.html GPL License
 */

var _studySec		= 0;														// 学習時間
var _timerID		= null;														// 制限時間タイマーID
var _lblStudySec	= null;														// 制限時間表示ラベル

$(document).ready(function()
{
	// テスト結果表示モード以外の場合、制限時間を表示
	if(!IS_RECORD)
	{
		_lblStudySec = $("#lblStudySec");
		_lblStudySec.show();
		setStudySec();
		_timerID = setInterval(setStudySec, 1000);
	}
});

// 学習時間の更新
function setStudySec()
{
	if(TIMELIMIT_SEC > 0)
	{
		if( _studySec > TIMELIMIT_SEC )
		{
			clearInterval(_timerID);
			
			if(typeof MSG_TIMELIMIT == 'undefined')
			{
				alert("制限時間を過ぎましたので自動採点を行います。");
			}
			else
			{
				alert(MSG_TIMELIMIT);
			}
			
			$("form").submit();
			return;
		}
		
		var restSec = TIMELIMIT_SEC - _studySec;
		var rest = CommonUtil.getHHMMSSbySec(restSec);
		
		if(typeof MSG_REST_TIME == 'undefined')
		{
			_lblStudySec.text('残り時間 : ' + rest);
		}
		else
		{
			_lblStudySec.text(MSG_REST_TIME + ' : ' + rest);
		}
		
		if(restSec < 60)
		{
			if(_lblStudySec.hasClass('btn-info'))
				_lblStudySec.removeClass('btn-info').addClass('btn-danger');
		}
	}
	else
	{
		var passed = CommonUtil.getHHMMSSbySec(_studySec);
		
		if(typeof MSG_TIME == 'undefined')
		{
			_lblStudySec.text('経過: ' + passed);
		}
		else
		{
			_lblStudySec.text(MSG_TIME + ' : ' + passed);
		}
	}
	
	$("#ContentsQuestionStudySec").val(_studySec);
	_studySec++;
}

// 採点
function sendData()
{
	// 重複送信防止の為、ボタンを無効化
	$('.btn').prop('disabled', true);
	
	$('form').submit();
	return;
}
