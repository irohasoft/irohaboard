/**
 * iroha Board Project
 *
 * @author        Kotaro Miura
 * @copyright     2015-2016 iroha Soft, Inc. (http://irohasoft.jp)
 * @link          http://irohaboard.irohasoft.jp
 * @license       http://www.gnu.org/licenses/gpl-3.0.en.html GPL License
 */
 

function CommonUtility() {}

// 連想配列を特定のキーでソート
CommonUtility.prototype.setRichTextEditor = function (selector)
{
	$(selector).summernote({
		lang: "ja-JP",
		maximumImageFileSize: 102400,
		callbacks: {
			onImageUploadError: function(e)
			{
				alert(e);
			}
		}
	});
}

var CommonUtil = new CommonUtility();

