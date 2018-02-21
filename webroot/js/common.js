/**
 * iroha Board Project
 *
 * @author        Kotaro Miura
 * @copyright     2015-2016 iroha Soft, Inc. (http://irohasoft.jp)
 * @link          http://irohaboard.irohasoft.jp
 * @license       http://www.gnu.org/licenses/gpl-3.0.en.html GPL License
 */
 

function CommonUtility() {}

// リッチテキストエディタの設定
CommonUtility.prototype.setRichTextEditor = function (selector, use_upload_image, base_url)
{
	$(selector).summernote({
		lang: "ja-JP",
		maximumImageFileSize: (1024 * 500),
		callbacks: {
			onImageUpload: function(files)
			{
				var data = new FormData();
				data.append("file", files[0]);
				
				$.ajax({
					data: data,
					type: 'POST',
					url: base_url + 'admin/contents/upload_image',
					cache: false,
					contentType: false,
					processData: false,
					success: function(url) {
						$(selector).summernote('insertImage', JSON.parse(url)[0], 'image');
					}
				});
			},
			onImageUploadError: function(e)
			{
				alert('画像のアップロードに失敗しました');
			}
		}
	});
}

var CommonUtil = new CommonUtility();

