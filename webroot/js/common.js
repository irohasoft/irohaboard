/**
 * iroha Board Project
 *
 * @author        Kotaro Miura
 * @copyright     2015-2021 iroha Soft, Inc. (https://irohasoft.jp)
 * @link          https://irohaboard.irohasoft.jp
 * @license       https://www.gnu.org/licenses/gpl-3.0.en.html GPL License
 */


$(document).ready(function()
{
	// 一定時間経過後、メッセージを閉じる
	setTimeout(function() {
		$('#flashMessage').fadeOut("slow");
	}, 1500);
});


function CommonUtility() {}

// リッチテキストエディタの設定
CommonUtility.prototype.setRichTextEditor = function (selector, upload_image_maxsize, base_url)
{
	// 旧パラメータ（use_upload_image）の対応
	if((upload_image_maxsize===true)||(upload_image_maxsize===false))
		upload_image_maxsize = (1024 * 1024 * 2);
	
	$(selector).summernote({
		lang: "ja-JP",
//		fontNames: ['Arial', 'Arial Black', 'Comic Sans MS', 'Courier New']
		maximumImageFileSize: upload_image_maxsize,
		callbacks: {
			onImageUpload: function(files)
			{
				var data = new FormData();
				var image_key= $('input[name="data[_Token][key]"]').val();
				
				data.append('file', files[0]);
				data.append('data[_Token][key]', image_key);
				
				$.ajax({
					data: data,
					type: 'POST',
					url: base_url + 'admin/contents/upload_image',
					cache: false,
					contentType: false,
					processData: false,
					success: function(url) {
						if(url)
						{
							$(selector).summernote('insertImage', JSON.parse(url)[0], 'image');
						}
						else
						{
							alert('画像のアップロードに失敗しました');
						}
					},
					error: function(url) {
						alert('通信中にエラーが発生しました');
					}
				});
			},
			onImageUploadError: function(e)
			{
				alert('指定されたファイルはアップロードできません');
			}
		}
	});
}

CommonUtility.prototype.getHHMMSSbySec = function (sec)
{
	var date = new Date('2000/1/1');
	
	date.setSeconds(sec);
	
	var h = date.getHours();
	var m = date.getMinutes();
	var s = date.getSeconds();
	
	if (h < 10)
		h = '0' + h;
	
	if (m < 10)
		m = '0' + m;
	
	if (s < 10)
		s = '0' + s;
	
	var hms = h + ':' + m + ':' + s;
	
	return hms;
}


var CommonUtil = new CommonUtility();

