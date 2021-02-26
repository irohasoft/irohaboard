<?php
/**
 * iroha Board Project
 *
 * @author        Kotaro Miura
 * @copyright     2015-2021 iroha Soft, Inc. (https://irohasoft.jp)
 * @link          https://irohaboard.irohasoft.jp
 * @license       https://www.gnu.org/licenses/gpl-3.0.en.html GPL License
 */
?>
<!DOCTYPE html>
<html>
<head>
	<?= $this->Html->charset(); ?>
	
	<title><?= h($this->Session->read('Setting.title')); ?></title>
	<meta name="application-name" content="<?= APP_NAME; ?>">
	<meta http-equiv="X-UA-Compatible" content="IE=edge"/>
	<?php
		// 管理画面か確認、ただしログイン画面は例外とする
		$is_admin_page = $this->isAdminPage() && !$this->isLoginPage();
		
		// 受講者向け画面及び、管理システムのログイン画面のみ viewport を設定（スマートフォン対応）
		if(!$is_admin_page)
			echo '<meta name="viewport" content="width=device-width,initial-scale=1">';
		
		echo $this->Html->meta('icon');

		echo $this->Html->css('cake.generic');
		echo $this->Html->css('jquery-ui');
		echo $this->Html->css('bootstrap.min');
		echo $this->Html->css('common.css?20200701');
		
		// 管理画面用CSS
		if($is_admin_page)
			echo $this->Html->css('admin.css?20200701');

		// カスタマイズ用CSS
		echo $this->Html->css('custom.css?20200701');
		
		echo $this->Html->script('jquery-1.9.1.min.js');
		echo $this->Html->script('jquery-ui-1.9.2.min.js');
		echo $this->Html->script('bootstrap.min.js');
		echo $this->Html->script('moment.js');
		echo $this->Html->script('common.js?20200701');
		
		// 管理画面用スクリプト
		if($is_admin_page)
			echo $this->Html->script('admin.js?20200701');
		
		// デモモード用スクリプト
		if(Configure::read('demo_mode'))
			echo $this->Html->script('demo.js');
		
		// カスタマイズ用スクリプト
		echo $this->Html->script('custom.js?20200701');
		
		echo $this->fetch('meta');
		echo $this->fetch('css');
		echo $this->fetch('script');
		echo $this->fetch('css-embedded');
		echo $this->fetch('script-embedded');
	?>
	<style>
		.ib-theme-color
		{
			background-color	: <?= h($this->Session->read('Setting.color')); ?>;
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
			<a href="<?= $this->Html->url('/')?>"><?= h($this->Session->read('Setting.title')); ?></a>
		</div>
		<?php if(isset($loginedUser)) {?>
		<div class="ib-navi-item ib-right ib-navi-logout"><?= $this->Html->link(__('ログアウト'), ['controller' => 'users', 'action' => 'logout']); ?></div>
		<div class="ib-navi-sepa ib-right ib-navi-sepa-1 "></div>
		<div class="ib-navi-item ib-right ib-navi-setting"><?= $this->Html->link(__('設定'), ['controller' => 'users', 'action' => 'setting']); ?></div>
		<div class="ib-navi-sepa ib-right ib-navi-sepa-2"></div>
		<div class="ib-navi-item ib-right ib-navi-welcome"><?= __('ようこそ').' '.h($loginedUser['name']).' '.__('さん'); ?></div>
		<?php }?>
	</div>
	
	<div id="container">
		<div id="content" class="row">
			<?= $this->Session->flash(); ?>
			<?= $this->fetch('content'); ?>
		</div>
	</div>
	
	<div class="ib-theme-color text-center">
		<?= h($this->Session->read('Setting.copyright')); ?>
	</div>
	
	<div class="irohasoft">
		Powered by <a href="https://irohaboard.irohasoft.jp/">iroha Board</a>
	</div>
	
	<?= $this->element('sql_dump'); ?>
</body>
</html>
