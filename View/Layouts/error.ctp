<?php
/**
 * iroha Board Project
 *
 * @author        Kotaro Miura
 * @copyright     2015-2016 iroha Soft, Inc. (http://irohasoft.jp)
 * @link          http://irohaboard.irohasoft.jp
 * @license       http://www.gnu.org/licenses/gpl-3.0.en.html GPL License
 */

$cakeDescription = __d('cake_dev', 'CakePHP: the rapid development php framework');
?>
<!DOCTYPE html>
<html>
<head>
	<?= $this->Html->charset(); ?>
	<title>
		<?= $cakeDescription ?>:
		<?= $this->fetch('title'); ?>
	</title>
	<?php
		echo $this->Html->meta('icon');

		echo $this->Html->css('cake.generic');
		echo $this->Html->css('common');

		echo $this->fetch('meta');
		echo $this->fetch('css');
		echo $this->fetch('script');
	?>
</head>
<body>
	<div id="container">
		<div id="header">
			<h1><?= $this->Html->link($cakeDescription, 'http://cakephp.org'); ?></h1>
		</div>
		<div id="content">

			<?= $this->Session->flash(); ?>

			<?= $this->fetch('content'); ?>
		</div>
		<div id="footer">
			<?= $this->Html->link(
					$this->Html->image('cake.power.gif', ['alt' => $cakeDescription, 'border' => '0']),
					'http://www.cakephp.org/',
					['target' => '_blank', 'escape' => false]
				);
			?>
		</div>
	</div>
	<?= $this->element('sql_dump'); ?>
</body>
</html>
