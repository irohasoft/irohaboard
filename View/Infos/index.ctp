<?php echo $this->element('menu');?>
<div class="infos index">
	<div class="panel panel-success">
	<div class="panel-heading"><?php echo __('お知らせ一覧'); ?></div>
	<div class="panel-body">

	<table cellpadding="0" cellspacing="0">
	<thead>
	<tr>
		<th><?php echo $this->Paginator->sort('title',   __('タイトル')); ?></th>
		<th width="200"><?php echo $this->Paginator->sort('opend',   __('作成日時')); ?></th>
	</tr>
	</thead>
	<tbody>
	<?php foreach ($infos as $info): ?>
	<tr>
		<td><?php echo $this->Html->link($info['Info']['title'], array('action' => 'view', $info['Info']['id'])); ?>&nbsp;</td>
		<td><?php echo h($info['Info']['created']); ?>&nbsp;</td>
	</tr>
<?php endforeach; ?>
	</tbody>
	</table>
	<?php echo $this->Paginator->pagination(array('ul' => 'pagination')); ?>
	</div>
	</div>
</div>