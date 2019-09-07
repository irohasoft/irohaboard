<?php echo $this->element('admin_menu');?>
<?php $this->start('script-embedded'); ?>
<script>
	function downloadCSV()
	{
		var url = '<?php echo Router::url(array('action' => 'csv')) ?>/' + $('#MembersEventEventId').val() + '/' + $('#MembersEventStatus').val() + '/' + $('#MembersEventUsername').val();
		$("#RecordCmd").val("csv");
		$("#RecordAdminIndexForm").submit();
		$("#RecordCmd").val("");
	}
</script>
<?php $this->end(); ?>
<div class="admin-records-index">
	<div class="ib-page-title"><?php echo __('SOAP一覧'); ?></div>
	<div class="ib-horizontal">
		<?php
		echo $this->Form->create('Soap');
		echo '<div class="ib-search-buttons">';
		echo $this->Form->submit(__('検索'), array('class' => 'btn btn-info', 'div' => false));
		echo $this->Form->hidden('cmd');
		echo '</div>';

		echo '<div class="ib-row">';
		echo $this->Form->input('name',	array(
			'label' => '受講生名 :',
			'value'=>$name,
			'class'=>'form-control'));
		echo $this->Form->input('group_title',	array(
			'label' => '担当講師名 :',
			'value'=>$group_title,
			'class'=>'form-control'));
		echo $this->Form->input('period',	array(
			'label' => '受講時間帯 :',
			'options'=>$period_list,
			'selected'=>$period,
			'empty' => '全て',
			'required'=>false,
			'class'=>'form-control'));
		echo $this->Form->input('course_id', array(
			'label' => '教材 :',
			'options'=>$courses,
			'selected'=>$course_id,
			'empty' => '全て',
			'required'=>false,
			'class'=>'form-control'));
		echo '</div>';

		echo '<div class="ib-search-date-container form-inline">';
		echo $this->Form->input('from_date', array(
			'type' => 'date',
			'dateFormat' => 'YMD',
			'monthNames' => false,
			'timeFormat' => '24',
			'minYear' => $oldest_created_year,
			'maxYear' => date('Y'),
			'separator' => ' / ',
			'label'=> '対象日時 : ',
			'class'=>'form-control',
			'style' => 'display: inline;',
			'value' => $from_date
		));
		echo $this->Form->input('to_date', array(
			'type' => 'date',
			'dateFormat' => 'YMD',
			'monthNames' => false,
			'timeFormat' => '24',
			'minYear' => $oldest_created_year,
			'maxYear' => date('Y'),
			'separator' => ' / ',
			'label'=> '～',
			'class'=>'form-control',
			'style' => 'display: inline;',
			'value' => $to_date
		));
		echo '</div>';
		echo $this->Form->end();
		?>
	</div>

	<table cellpadding="0" cellspacing="0">
		<thead>
			<tr>
				<th class="ib-col-date">受講日&nbsp;</th>
				<th nowrap>受講生名&nbsp;</th>
				<th nowrap>担当講師名&nbsp;</th>
				<th nowrap class="text-center">1限<br/>or<br/>2限</th>
				<th nowrap class="text-center">STEP教材<br/>or<br/>応用教材</th>
				<th nowrap>SOAP</th>
				<th>自由コメント</th>
			</tr>
		</thead>
		<tbody>
		<?php foreach ($records as $record):
			if($record['User']['period'] == 0) {
				$class_hour = "1限";
			} elseif($record['User']['period'] == 1) {
				$class_hour = "2限";
			} else {
				$class_hour = "時限未設定";
			}
		?>
			<tr>
				<td class="ib-col-date"><?php echo h(Utils::getYMD($record['Soap']['created'])); ?>&nbsp;</td>
				<td nowrap class="text-center"><?php echo h($record['User']['name']); ?>&nbsp;</td>
				<td nowrap class="text-center"><?php echo h($record['Group']['title']); ?>&nbsp;</td>
				<td nowrap class="text-center"><?php echo h($class_hour); ?>&nbsp;</td>
				<td nowrap class="text-center"><?php echo h($record['Course']['title']); ?>&nbsp;</td>
				<td nowrap>
					S: <?php echo h($record['Soap']['S']); ?>&nbsp;<br/>
					O: <?php echo h($record['Soap']['O']); ?>&nbsp;<br/>
					A: <?php echo h($record['Soap']['A']); ?>&nbsp;<br/>
					P: <?php echo h($record['Soap']['P']); ?>&nbsp;
				</td>
				<td><?php echo h($record['Soap']['comment']); ?>&nbsp;</td>
			</tr>
		<?php endforeach; ?>
		</tbody>
	</table>
	<?php echo $this->element('paging');?>
</div>
