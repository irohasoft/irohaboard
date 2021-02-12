<?= $this->element('admin_menu');?>
<?php $this->start('script-embedded'); ?>
<script>
	function openRecord(course_id, user_id)
	{
		window.open(
			'<?= Router::url(['controller' => 'contents', 'action' => 'record']) ?>/'+course_id+'/'+user_id,
			'irohaboard_record',
			'width=1100, height=700, menubar=no, toolbar=no, scrollbars=yes'
		);
	}
	
	function openTestRecord(content_id, record_id)
	{
		window.open(
			'<?= Router::url(['controller' => 'contents_questions', 'action' => 'record']) ?>/'+content_id+'/'+record_id,
			'irohaboard_record',
			'width=1100, height=700, menubar=no, toolbar=no, scrollbars=yes'
		);
	}
	
	function downloadCSV()
	{
		$("#RecordCmd").val("csv");
		$("#RecordAdminIndexForm").submit();
		$("#RecordCmd").val("");
	}
</script>
<?php $this->end(); ?>
<div class="admin-records-index">
	<div class="ib-page-title"><?= __('学習履歴一覧'); ?></div>
	<div class="ib-horizontal">
	<?php
		echo $this->Form->create('Record');
		echo '<div class="ib-search-buttons">';
		echo $this->Form->submit(__('検索'),	['class' => 'btn btn-info', 'div' => false]);
		echo $this->Form->hidden('cmd');
		echo '<button type="button" class="btn btn-default" onclick="downloadCSV()">'.__('CSV出力').'</button>';
		echo '</div>';
		
		echo '<div class="ib-row">';
		echo $this->Form->searchField('course_id',			['label' => __('コース'), 'options' => $courses, 'empty' => '全て']);
		echo $this->Form->searchField('content_category',	['label' => __('コンテンツ種別'), 'options' => Configure::read('content_category'), 'empty' => '全て', 'selected' => $content_category]);
		echo $this->Form->searchField('content_title',		['label' => __('コンテンツ名')]);
		echo '</div>';
		
		echo '<div class="ib-row">';
		echo $this->Form->searchField('group_id',	['label' => __('グループ'), 'options' => $groups, 'empty' => '全て', 'selected' => $group_id]);
		echo $this->Form->searchField('username',	['label' => __('ログインID')]);
		echo $this->Form->searchField('name',		['label' => __('氏名')]);
		echo '</div>';
		
		echo '<div class="ib-search-date-container">';
		echo $this->Form->searchDate('from_date', ['label'=> __('対象日時'), 'value' => $from_date]);
		echo $this->Form->searchDate('to_date',   ['label'=> __('～'), 'value' => $to_date]);
		echo '</div>';
		echo $this->Form->end();
	?>
	</div>
	<table cellpadding="0" cellspacing="0">
	<thead>
	<tr>
		<th nowrap><?= $this->Paginator->sort('User.username', __('ログインID')); ?></th>
		<th nowrap><?= $this->Paginator->sort('User.name', __('氏名')); ?></th>
		<th nowrap><?= $this->Paginator->sort('course_id', __('コース')); ?></th>
		<th nowrap><?= $this->Paginator->sort('content_id', __('コンテンツ')); ?></th>
		<th nowrap class="ib-col-center"><?= $this->Paginator->sort('score', __('得点')); ?></th>
		<th class="ib-col-center" nowrap><?= $this->Paginator->sort('pass_score', __('合格点')); ?></th>
		<th nowrap class="ib-col-center"><?= $this->Paginator->sort('is_passed', __('結果')); ?></th>
		<th class="ib-col-center" nowrap><?= $this->Paginator->sort('understanding', __('理解度')); ?></th>
		<th class="ib-col-center"><?= $this->Paginator->sort('study_sec', __('学習時間')); ?></th>
		<th class="ib-col-datetime"><?= $this->Paginator->sort('created', __('学習日時')); ?></th>
	</tr>
	</thead>
	<tbody>
	<?php foreach ($records as $record): ?>
	<tr>
		<td><?= h($record['User']['username']); ?>&nbsp;</td>
		<td><?= h($record['User']['name']); ?>&nbsp;</td>
		<td><a href="javascript:openRecord(<?= h($record['Course']['id']); ?>, <?= h($record['User']['id']); ?>);"><?= h($record['Course']['title']); ?></a></td>
		<td><?= h($record['Content']['title']); ?>&nbsp;</td>
		<td class="ib-col-center"><?= h($record['Record']['score']); ?>&nbsp;</td>
		<td class="ib-col-center"><?= h($record['Record']['pass_score']); ?>&nbsp;</td>
		<td nowrap class="ib-col-center"><a href="javascript:openTestRecord(<?= h($record['Content']['id']); ?>, <?= h($record['Record']['id']); ?>);"><?= Configure::read('record_result.'.$record['Record']['is_passed']); ?></a></td>
		<td nowrap class="ib-col-center"><?= h(Configure::read('record_understanding.'.$record['Record']['understanding'])); ?>&nbsp;</td>
		<td class="ib-col-center"><?= h(Utils::getHNSBySec($record['Record']['study_sec'])); ?>&nbsp;</td>
		<td class="ib-col-date"><?= h(Utils::getYMDHN($record['Record']['created'])); ?>&nbsp;</td>
	</tr>
	<?php endforeach; ?>
	</tbody>
	</table>
	<?= $this->element('paging');?>
</div>
