<?php echo $this->element('admin_menu');?>
<div class="records index">
	<div class="ib-page-title"><?php echo __('成績一覧'); ?></div>
	<div class="ib-horizontal">
		<?php
			echo $this->Form->create();
			echo $this->Form->input('username', array('label' => 'ユーザ名:'));
			echo $this->Form->input('coursetitle', array('label' => 'コース名:'));
			echo $this->Form->input('contenttitle', array('label' => 'コンテンツ名:'));
			//echo $this->Form->input('group_id', array('label' => '所属グループ', 'options'=>$groups));
			echo $this->Form->submit(__('検索'));
			echo $this->Form->end();
		?>
	</div>
	<table cellpadding="0" cellspacing="0">
	<thead>
	<tr>
			<th><?php echo $this->Paginator->sort('course_id', 'コース'); ?></th>
			<th><?php echo $this->Paginator->sort('content_id', 'コンテンツ'); ?></th>
			<th><?php echo $this->Paginator->sort('user_id', '氏名'); ?></th>
			<th><?php echo $this->Paginator->sort('score', '得点'); ?></th>
			<th><?php echo $this->Paginator->sort('pass_score', '合格点'); ?></th>
			<th><?php echo $this->Paginator->sort('is_passed', '結果'); ?></th>
			<th><?php echo $this->Paginator->sort('is_complete', '完了'); ?></th>
			<th><?php echo $this->Paginator->sort('understanding', '理解度'); ?></th>
			<th><?php echo $this->Paginator->sort('study_sec', '学習時間'); ?></th>
			<th><?php echo $this->Paginator->sort('created', '学習日時'); ?></th>
	</tr>
	</thead>
	<tbody>
	<?php foreach ($records as $record): ?>
	<tr>
		<td><?php echo h($record['Course']['title']); ?>&nbsp;</td>
		<td><?php echo h($record['Content']['title']); ?>&nbsp;</td>
		<td><?php echo h($record['User']['name']); ?>&nbsp;</td>
		<td><?php echo h($record['Record']['score']); ?>&nbsp;</td>
		<td><?php echo h($record['Record']['pass_score']); ?>&nbsp;</td>
		<td><?php echo h(Configure::read('record_result.'.$record['Record']['is_passed'])); ?>&nbsp;</td>
		<td><?php echo h(Configure::read('record_complete.'.$record['Record']['is_complete'])); ?>&nbsp;</td>
		<td><?php echo h(Configure::read('record_understanding.'.$record['Record']['understanding'])); ?>&nbsp;</td>
		<td><?php echo h($record['Record']['study_sec']); ?>&nbsp;</td>
		<td><?php echo h($record['Record']['created']); ?>&nbsp;</td>
	</tr>
<?php endforeach; ?>
	</tbody>
	</table>
	<div class="text-center">
		<?php echo $this->Paginator->pagination(array('ul' => 'pagination')); ?>
	</div>
</div>
