<?= $this->element('admin_menu');?>
<div class="admin-infos-index">
	<div class="ib-page-title"><?= __('お知らせ一覧'); ?></div>
	<div class="buttons_container">
		<button type="button" class="btn btn-primary btn-add" onclick="location.href='<?= Router::url(['action' => 'add']) ?>'">+ 追加</button>
	</div>
	<table cellpadding="0" cellspacing="0">
	<thead>
	<tr>
		<th><?= $this->Paginator->sort('title',   __('タイトル')); ?></th>
		<th nowrap><?= __('対象グループ'); ?></th>
		<th class="ib-col-date"><?= $this->Paginator->sort('created', __('作成日時')); ?></th>
		<th class="ib-col-date"><?= $this->Paginator->sort('modified', __('更新日時')); ?></th>
		<th class="ib-col-action"><?= __('Actions'); ?></th>
	</tr>
	</thead>
	<tbody>
	<?php foreach ($infos as $info): ?>
	<tr>
		<td><?= h($info['Info']['title']); ?>&nbsp;</td>
		<td><div class="reader col-group" title="<?= h($info['InfoGroup']['group_title']); ?>"><p><?= h($info['InfoGroup']['group_title']); ?>&nbsp;</p></td>
		<td class="ib-col-date"><?= Utils::getYMDHN($info['Info']['created']); ?>&nbsp;</td>
		<td class="ib-col-date"><?= Utils::getYMDHN($info['Info']['modified']); ?>&nbsp;</td>
		<td class="ib-col-action">
			<button type="button" class="btn btn-success" onclick="location.href='<?= Router::url(['action' => 'edit', $info['Info']['id']]) ?>'"><?= __('編集')?></button>
			<?= $this->Form->postLink(__('削除'), ['action' => 'delete', $info['Info']['id']], ['class'=>'btn btn-danger'], 
					__('[%s] を削除してもよろしいですか?', $info['Info']['title']));?>
		</td>
	</tr>
	<?php endforeach; ?>
	</tbody>
	</table>
	<?= $this->element('paging');?>
</div>