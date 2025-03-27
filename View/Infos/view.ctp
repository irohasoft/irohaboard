<div class="infos-view">
	<div class="breadcrumb">
	<?php
	$this->Html->addCrumb(
		'<span class="glyphicon glyphicon-home" aria-hidden="true"></span> HOME',
		['controller' => 'users_courses','action' => 'index'],
		['escape' => false]
	);

	$this->Html->addCrumb(
		'<span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span> '.__('お知らせ一覧'),
		['controller' => 'infos','action' => 'index'],
		['escape' => false]
	);

	echo $this->Html->getCrumbs(' / ');
	
	$title = h($info['Info']['title']);
	$date  = h(Utils::getYMD($info['Info']['created']));
	$body  = $info['Info']['body'];
	$target = Configure::read('open_link_same_window') ? [] : ['target' => '_blank'];
	$body  = $this->Text->autoLinkUrls($body, $target);
	$body  = nl2br($body);
	?>
	</div>

	<div class="panel panel-success">
		<div class="panel-heading"><?= $title; ?></div>
		<div class="panel-body">
			<div class="text-right"><?= $date; ?></div>
			<?= $body; ?>
		</div>
	</div>
</div>
