<div class="infos-index">
	<div class="breadcrumb">
	<?php
	$this->Html->addCrumb('HOME', [
		'controller' => 'users_courses',
		'action' => 'index'
	]);
	echo $this->Html->getCrumbs(' / ');
	?>
	</div>
	<div class="panel panel-success">
		<div class="panel-heading"><?= __('お知らせ一覧'); ?></div>
		<div class="panel-body">
			<table cellpadding="0" cellspacing="0">
			<thead>
			<tr>
				<th><?= $this->Paginator->sort('opend',   __('日付')); ?></th>
				<th><?= $this->Paginator->sort('title',   __('タイトル')); ?></th>
			</tr>
			</thead>
			<tbody>
			<?php foreach ($infos as $info): ?>
			<tr>
				<td width="100" valign="top"><?= h(Utils::getYMD($info['Info']['created'])); ?>&nbsp;</td>
				<td><?= $this->Html->link($info['Info']['title'], ['action' => 'view', $info['Info']['id']]); ?>&nbsp;</td>
			</tr>
			<?php endforeach; ?>
			</tbody>
			</table>
			<?= $this->element('paging');?>
		</div>
	</div>
</div>