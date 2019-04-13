<div class="infos-index">
	<div class="breadcrumb">
	<?php
	$this->Html->addCrumb('HOME', array(
		'controller' => 'users_courses',
		'action' => 'index'
	));
	echo $this->Html->getCrumbs(' / ');
	?>
	</div>
	<div class="panel panel-success">
		<div class="panel-heading"><?php echo __('お知らせ一覧'); ?></div>
		<div class="panel-body">
			<table cellpadding="0" cellspacing="0">
			<thead>
			<tr>
				<th><?php echo $this->Paginator->sort('opend',   __('日付')); ?></th>
				<th><?php echo $this->Paginator->sort('title',   __('タイトル')); ?></th>
			</tr>
			</thead>
			<tbody>
			<?php foreach ($infos as $info): ?>
			<tr>
				<td width="100" valign="top"><?php echo h(Utils::getYMD($info['Info']['created'])); ?>&nbsp;</td>
				<td><?php echo $this->Html->link($info['Info']['title'], array('action' => 'view', $info['Info']['id'])); ?>&nbsp;</td>
			</tr>
			<?php endforeach; ?>
			</tbody>
			</table>
			<?php echo $this->element('paging');?>
		</div>
	</div>
</div>