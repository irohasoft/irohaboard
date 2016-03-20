<?php echo $this->element('menu');?>
<div class="infos view">
	<div class="ib-breadcrumb">
	<?php
	$this->Html->addCrumb('<< お知らせ一覧', array(
			'controller' => 'infos',
			'action' => 'index'
	));

	echo $this->Html->getCrumbs(' / ');
	
	$info = h($info['Info']['body']);
	$info = $this->Text->autoLinkUrls($info);
	$info = nl2br($info);
	?>
	</div>

	<div class="panel panel-success">
		<div class="panel-heading"><?php echo __('タイトル'); ?></div>
		<div class="panel-body"><?php echo $info; ?></div>
	</div>
</div>
