<?php echo $this->element('admin_menu');?>
<div class="courses index">
	<div class="ib-page-title"><?php echo __('コース一覧'); ?></div>
	<div class="buttons_container">
		<button type="button" class="btn btn-primary" onclick="location.href='<?php echo Router::url(array('action' => 'add')) ?>'">+ 追加</button>
	</div>

	<table cellpadding="0" cellspacing="0">
	<thead>
	<tr>
			<th><?php echo $this->Paginator->sort('title', 'コース名'); ?></th>
			<th><?php echo $this->Paginator->sort('opened', '公開日時'); ?></th>
			<th><?php echo $this->Paginator->sort('created', '作成日時'); ?></th>
			<th><?php echo $this->Paginator->sort('modified', '更新日時'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	</thead>
	<tbody>
	<?php foreach ($courses as $course): ?>
	<tr>
		<td>
			<?php echo $this->Html->link($course['Course']['title'], array('controller' => 'contents', 'action' => 'index', $course['Course']['id'])); ?>
		</td>
		<td><?php echo h($course['Course']['opened']); ?>&nbsp;</td>
		<td><?php echo h($course['Course']['created']); ?>&nbsp;</td>
		<td><?php echo h($course['Course']['modified']); ?>&nbsp;</td>
		<td class="actions">
			<button type="button" class="btn btn-success" onclick="location.href='<?php echo Router::url(array('action' => 'edit', $course['Course']['id'])) ?>'">編集</button>
			<?php echo $this->Form->postLink(__('削除'),
					array('action' => 'delete', $course['Course']['id']),
					array('class'=>'btn btn-danger'),
					__('[%s] を削除してもよろしいですか?', $course['Course']['title'])
			); ?>
		</td>
	</tr>
<?php endforeach; ?>
	</tbody>
	</table>
	<?php echo $this->Paginator->pagination(array('ul' => 'pagination')); ?>
</div>
