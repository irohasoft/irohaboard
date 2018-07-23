<?php
/**
 * iroha Board Project
 *
 * @author        Kotaro Miura
 * @copyright     2015-2016 iroha Soft, Inc. (http://irohasoft.jp)
 * @link          http://irohaboard.irohasoft.jp
 * @license       http://www.gnu.org/licenses/gpl-3.0.en.html GPL License
 */

//$cakeDescription = __d('cake_dev', 'CakePHP: the rapid development php framework');
?>
<!DOCTYPE html>
<html>
<head>
	<?php echo $this->Html->charset(); ?>
	
	<title><?php echo h($this->Session->read('Setting.title')); ?></title>
	<meta name="application-name" content="iroha Board">
	<meta http-equiv="X-UA-Compatible" content="IE=edge"/>
	<?php
		// 受講者向け画面及び、管理システムのログイン画面のみ viewport を設定（スマートフォン対応）
		if(
			(!$this->params['admin'])||
			($this->params['action']=='admin_login')
		)
		{
			echo '<meta name="viewport" content="width=device-width,initial-scale=1">';
		}
		
		echo $this->Html->meta('icon');

		echo $this->Html->css('cake.generic');
		echo $this->Html->css('jquery-ui');
		echo $this->Html->css('bootstrap.min');
		echo $this->Html->css('common');

		echo $this->Html->script('jquery-1.9.1.min.js');
		echo $this->Html->script('jquery-ui-1.9.2.min.js');
		echo $this->Html->script('bootstrap.min.js');
		echo $this->Html->script('moment.js');
		echo $this->Html->script('common.js');
		
		// デモモード用JavaScript
		if(Configure::read('demo_mode'))
			echo $this->Html->script('demo.js');
		
		echo $this->fetch('meta');
		echo $this->fetch('css');
		echo $this->fetch('script');
		echo $this->fetch('css-embedded');
		echo $this->fetch('script-embedded');
	?>
	<script>
	// 一定時間経過後、メッセージを閉じる
	$(document).ready(function()
	{
		setTimeout(function() {
			$('#flashMessage').fadeOut("slow");
		}, 1500);
	});
	</script>
	<style>
		.ib-theme-color
		{
			background-color	: <?php echo h($this->Session->read('Setting.color')); ?>;
			color				: white;
		}
		
		.ib-logo a
		{
			color				: white;
			text-decoration		: none;
		}
	</style>
</head>
<body>
	<div class="header ib-theme-color">
		<div class="ib-logo ib-left">
			<a href="<?php echo $this->Html->url('/')?>"><?php echo h($this->Session->read('Setting.title')); ?></a>
		</div>
<?php
		if($loginedUser)
		{
			echo '<div class="ib-navi-item ib-right">'.$this->Html->link(__('ログアウト'), $logoutURL).'</div>';
			echo '<div class="ib-navi-sepa ib-right"></div>';
			echo '<div class="ib-navi-item ib-right">'.$this->Html->link(__('設定'), array('controller' => 'users', 'action' => 'setting')).'</div>';
			echo '<div class="ib-navi-sepa ib-right"></div>';
			echo '<div class="ib-navi-item ib-right">'.__('ようこそ ').h($loginedUser["name"]).' さん </div>';
		}
?>
	</div>
	
	<div id="container">
		<div id="header" class="row">
		</div>
		<div id="content" class="row">
			<?php echo $this->Session->flash(); ?>

			<?php echo $this->fetch('content'); ?>
		</div>
		<div id="footer" class="row">
		</div>
	</div>
	
	<div class="footer ib-theme-color text-center">
		<?php echo h($this->Session->read('Setting.copyright')); ?>
	</div>
	
	<div class="irohasoft">
		Powered by <a href="http://irohaboard.irohasoft.jp/">iroha Board</a>
	</div>
	
	<?php echo $this->element('sql_dump'); ?>
</body>
</html>
