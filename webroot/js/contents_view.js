var _studySec = 0;

$(document).ready(function()
{
	setInterval("_studySec++;", 1000);
	
	// PC向けボタンの追加
	for(key in BUTTON_PC_LIST)
	{
		$('.understanding-pc').prepend('<button type="button" class="btn btn-success" onclick="finish(' + key + ');">' + BUTTON_PC_LIST[key] + '</button>');
	}
	
	// スマートフォン向けボタンの追加
	for(key in BUTTON_SPN_LIST)
	{
		$('.understanding-spn').prepend('<button type="button" class="btn btn-success" onclick="finish(' + key + ');">' + BUTTON_SPN_LIST[key] + '</button>');
	}
	
	// 理解度が複数存在しない場合、メッセージを非表示とする
	if(Object.keys(BUTTON_PC_LIST).length < 2)
		$(".select-message").hide();
});

// 学習終了
function finish(val)
{
	// 学習履歴の重複記録防止の為、ボタンを無効化
	$('.btn').prop('disabled', true);
	
	// プレビューの場合、学習履歴を保存しない
	if(location.href.split('/').pop() == 'preview')
	{
		window.close();
		return;
	}
	
	// 学習履歴を残さずに終了の場合
	if(val == -1)
	{
		location.href = URL_CONTNES_INDEX;
		return;
	}

	// 学習履歴を保存する場合
	let is_complete = (val == 0) ? 0 : 1;
	let studySec = _studySec || 0;
	let understanding = (val > 0) ? val : 0;

	// POST送信用フォーム作成
	let form = document.createElement('form');
	form.method = 'POST';
	form.action = URL_RECORDS_ADD;

	// トークンを取得
	let token = document.querySelector('input[name="data[_Token][key]"]');
	
	// トークンをhiddenで付加
	if(token)
	{
		let input = document.createElement('input');
		input.type = 'hidden';
		input.name = 'data[_Token][key]';
		input.value = token.value;
		form.appendChild(input);
	}

	// 送信データを作成
	let inputs = {
		'is_complete': is_complete,
		'study_sec': studySec,
		'understanding': understanding
	};

	// データをhiddenで付加
	for(let key in inputs)
	{
		let input = document.createElement('input');
		input.type = 'hidden';
		input.name = key;
		input.value = inputs[key];
		form.appendChild(input);
	}

	// フォームを送信
	document.body.appendChild(form);
	form.submit();
}