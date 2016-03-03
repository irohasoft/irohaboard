<?php echo $this->element('admin_menu');?>
<div class="infos index">
	<div class="ib-page-title"><?php echo __('お知らせ一覧'); ?></div>
	<div class="buttons_container">
		<button type="button" class="btn btn-primary" onclick="location.href='<?php echo Router::url(array('action' => 'add')) ?>'">+ 追加</button>
	</div>
	<table cellpadding="0" cellspacing="0">
	<thead>
	<tr>
			<th><?php echo $this->Paginator->sort('title',   __('タイトル')); ?></th>
			<th width="200"><?php echo $this->Paginator->sort('opened',		__('公開開始日')); ?></th>
			<th width="200"><?php echo $this->Paginator->sort('closed',		__('公開終了日')); ?></th>
			<th width="200"><?php echo $this->Paginator->sort('created',	__('作成日時')); ?></th>
			<th width="200" class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	</thead>
	<tbody>
	<?php foreach ($infos as $info): ?>
	<tr>
		<td><?php echo h($info['Info']['title']); ?>&nbsp;</td>
		<td><?php echo Utils::getYMD($info['Info']['opened']); ?>&nbsp;</td>
		<td><?php echo Utils::getYMD($info['Info']['closed']); ?>&nbsp;</td>
		<td><?php echo Utils::getYMDHN($info['Info']['created']); ?>&nbsp;</td>
		<td class="actions">
			<button type="button" class="btn btn-success" onclick="location.href='<?php echo Router::url(array('action' => 'edit', $info['Info']['id'])) ?>'">編集</button>
			<?php echo $this->Form->postLink(__('削除'), 
					array('action' => 'delete', $info['Info']['id']), 
					array('class'=>'btn btn-danger'), 
					__('[%s] を削除してもよろしいですか?', $info['Info']['title'])
			); ?>
		</td>
	</tr>
<?php endforeach; ?>
	</tbody>
	</table>
	<?php echo $this->Paginator->pagination(array('ul' => 'pagination')); ?>
</div>