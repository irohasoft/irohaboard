<?php echo $this->element('admin_menu');?>
<div class="groups index">
	<div class="ib-page-title"><?php echo __('グループ一覧'); ?></div>
	<div class="buttons_container">
		<button type="button" class="btn btn-primary" onclick="location.href='<?php echo Router::url(array('action' => 'add')) ?>'">+ 追加</button>
	</div>
	
	<table>
	<thead>
	<tr>
			<th><?php echo $this->Paginator->sort('title', 'グループ名'); ?></th>
			<th><?php echo $this->Paginator->sort('created', '作成日時'); ?></th>
			<th><?php echo $this->Paginator->sort('modified', '更新日時'); ?></th>
			<th><?php echo $this->Paginator->sort('status', 'ステータス'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	</thead>
	<tbody>
	<?php foreach ($groups as $group): ?>
	<tr>
		<td>
			<?php echo $this->Html->link($group['Group']['title'], array('action' => 'change', $group['Group']['id'])); ?>
		</td>
		<td><?php echo h($group['Group']['created']); ?>&nbsp;</td>
		<td><?php echo h($group['Group']['modified']); ?>&nbsp;</td>
		<td><?php echo h(Configure::read('group_status.'.$group['Group']['status'])); ?>&nbsp;</td>
		<td class="actions">
			<button type="button" class="btn btn-success" onclick="location.href='<?php echo Router::url(array('action' => 'edit', $group['Group']['id'])) ?>'">編集</button>
			<?php echo $this->Form->postLink(__('削除'), 
					array('action' => 'delete', $group['Group']['id']), 
					array('class'=>'btn btn-danger'), 
					__('[%s] を削除してもよろしいですか?', $group['Group']['title'])
			); ?>
		</td>
	</tr>
<?php endforeach; ?>
	</tbody>
	</table>
	<div class="text-center">
		<?php echo $this->Paginator->pagination(array('ul' => 'pagination')); ?>
	</div>
</div>
