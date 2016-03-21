<?php echo $this->element('menu');?>
<div class="infos index">
	<div class="panel panel-success">
		<div class="panel-heading"><?php echo __('お知らせ一覧'); ?></div>
		<div class="panel-body">
			<table cellpadding="0" cellspacing="0">
			<thead>
			<tr>
				<th><?php echo $this->Paginator->sort('title',   __('タイトル')); ?></th>
				<th class="ib-col-date"><?php echo $this->Paginator->sort('opend',   __('作成日')); ?></th>
			</tr>
			</thead>
			<tbody>
			<?php foreach ($infos as $info): ?>
			<tr>
				<td><?php echo $this->Html->link($info['Info']['title'], array('action' => 'view', $info['Info']['id'])); ?>&nbsp;</td>
				<td class="ib-col-date"><?php echo h(Utils::getYMD($info['Info']['created'])); ?>&nbsp;</td>
			</tr>
			<?php endforeach; ?>
			</tbody>
			</table>
			<?php echo $this->element('paging');?>
		</div>
	</div>
</div>