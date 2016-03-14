/**
 * iroha Board Project
 *
 * @author        Kotaro Miura
 * @copyright     2015-2016 iroha Soft, Inc. (http://irohasoft.jp)
 * @link          http://irohasoft.jp/irohaboard
 * @license       http://www.gnu.org/licenses/gpl-3.0.en.html GPL License
 */
 
$(function (event)
{
	/*
	$('.btn').click(function(event)
	{
		event.preventDefault();
		return false;
	});
	*/
	
	$('.btn-primary').prop('disabled', true);
	$('.btn-danger').attr("onclick", 'alert("デモモードの為、削除できません");');
	
	$('.btn-primary[value="+ 追加"]').prop('disabled', false);
	$('.btn-primary[value="追加"]').prop('disabled', false);
	$('.btn-add').prop('disabled', false);
	$('.btn[value="ログイン"]').prop('disabled', false);
});

