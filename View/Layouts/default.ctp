<?php
/**
 * iroha Board Project
 *
 * @author        Kotaro Miura
 * @copyright     2015-2016 iroha Soft, Inc. (http://irohasoft.jp)
 * @link          http://irohasoft.jp/irohaboard
 * @license       http://www.gnu.org/licenses/gpl-3.0.en.html GPL License
 */

//$cakeDescription = __d('cake_dev', 'CakePHP: the rapid development php framework');
?>
<!DOCTYPE html>
<html>
<head>
	<?php echo $this->Html->charset(); ?>
	<title>
		<?php echo $this->fetch('title'); ?>
	</title>
	<meta name="viewport" content="width=device-width,initial-scale=1">
	<?php
		echo $this->Html->meta('icon');

		echo $this->Html->css('cake.generic');
		echo $this->Html->css('jquery-ui');
		echo $this->Html->css('bootstrap.min');
		echo $this->Html->css('common');

		echo $this->Html->script('jquery-1.9.1.min.js');
		echo $this->Html->script('jquery-ui-1.9.2.min.js');
		echo $this->Html->script('bootstrap.min.js');
		echo $this->Html->script('common.js');
		echo $this->Html->script('demo.js');

		echo $this->fetch('meta');
		echo $this->fetch('css');
		echo $this->fetch('script');
	?>
	<?php echo $this -> fetch( 'css-embedded' ); ?>
	<?php echo $this -> fetch( 'script-embedded' ); ?>
	<script>
	setTimeout(function() {
		$('#flashMessage').fadeOut("slow");
	}, 1200);
	</script>
	<style>
		.ib-theme-color
		{
			background-color	: <?php echo SessionHelper::read('Setting.color')?>;
			color				: white;
		}
	</style>
</head>
<body>
	<div id="container">
		<div id="header" class="row">
			<!--
			<div class="col-xs-4 col-sm-3 bg-success">
				<?php echo $this->Html->image('irohaboard.jpg'); ?>
			</div>
			-->
			<div class="col-xs-12 col-sm-12 ib-theme-color">
				<div class="ib-logo ib-left">
					<?php echo SessionHelper::read('Setting.title')?>
				</div>
<?php
	if($loginedUser)
	{
		echo '<div class="ib-navi-item ib-right">'.$this->Html->link('ログアウト', $logoutURL).'</div>';
		echo '<div class="ib-navi-sepa ib-right"></div>';
		echo '<div class="ib-navi-item ib-right">'.$this->Html->link(__('設定'), array('controller' => 'users', 'action' => 'setting')).'</div>';
		echo '<div class="ib-navi-sepa ib-right"></div>';
		echo '<div class="ib-navi-item ib-right">ようこそ '.$loginedUser["name"].' さん </div>';
	}else{
		echo $this->Html->link('ログイン',	 $loginURL);
	}
?>
			</div>
		</div>
		<div id="content" class="row">
			<?php echo $this->Session->flash(); ?>

			<?php echo $this->fetch('content'); ?>
		</div>
		<div id="footer" class="row">
			<div class="col-xs-12 col-sm-12 ib-theme-color text-center">
				<?php echo SessionHelper::read('Setting.copyright')?>
			</div>
		</div>
	</div>
	<?php echo $this->element('sql_dump'); ?>
</body>
</html>
