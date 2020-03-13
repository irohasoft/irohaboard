<?php echo $this->element('admin_menu');?>
<?php echo $this->Html->css('soapview');?>
<?php $this->start('script-embedded'); ?>
<script>
	function downloadCSV()
	{
		var url = '<?php echo Router::url(array('action' => 'csv')) ?>/' + $('#MembersEventEventId').val() + '/' + $('#MembersEventStatus').val() + '/' + $('#MembersEventUsername').val();
		$("#SoapCmd").val("csv");
		$("#SoapAdminIndexForm").submit();
		$("#SoapCmd").val("");
	}
	function setTodayDate(){
		$("#SoapCmd").val("today");
		$("#SoapAdminIndexForm").submit();
		$("#SoapCmd").val("");
	}
</script>
<?php $this->end(); ?>
<div class="admin-records-index full-view">
	<div class="ib-page-title"><?php echo __('SOAP'); ?></div>
	<div class="ib-horizontal">
		<?php
		echo $this->Form->create('Soap');
		echo '<div class="ib-search-buttons">';
		echo $this->Form->submit(__('検索'), array('class' => 'btn btn-primary', 'div' => false));
		echo $this->Form->hidden('cmd');
		echo '<button type="button" class="btn btn-info" onclick="setTodayDate()">'.__('今日').'</button>';
		echo '<button type="button" class="btn btn-secondary" onclick="downloadCSV()">'.__('CSV出力').'</button>';
		echo '</div>';

		echo '<div class="ib-row">';
		echo $this->Form->input('name',	array(
			'label' => '受講生氏名・番号 :',
			'value'=>$name,
			'class'=>'form-control'));
		echo $this->Form->input('group_id',	array(
			'label' => 'グループ :',
			'options'=>$groups,
			'selected'=>$group_id,
			'empty' => '全て',
			'required'=>false,
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

		echo '<div class="ib-search-date-container">';
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

	<div class = "record-table">
	<table cellpadding="0" cellspacing="0">
		<thead>
			<tr>
				<th class="ib-col-date"><?php echo $this->Paginator->sort('created', '受講日'); ?></th>
				<th nowrap><?php echo $this->Paginator->sort('User.name', '受講生名'); ?></th>
				<th nowrap><?php echo $this->Paginator->sort('group_id', '担当講師名'); ?></th>
				<th nowrap class="text-center"><?php echo $this->Paginator->sort('User.period', '受講時限'); ?></th>
				<th nowrap class="text-center"><?php echo $this->Paginator->sort('Course.title', '教材種別'); ?></th>
				<th nowrap>SOAP</th>
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
				<td nowrap class="ib-col-date"><?php echo h(Utils::getYMD($record['Soap']['created'])); ?>&nbsp;</td>
				<td>
					<?php
						echo $this->Html->link(h($record['User']['name']),
							array(
								'controller' => 'recentstates',
								'action' => 'admin_student_view', $record['User']['id']
						));
					?>
				</td>
				<td nowrap><?php echo h($record['Group']['title']); ?>&nbsp;</td>
				<td nowrap><?php echo h($class_hour); ?>&nbsp;</td>
				<td nowrap><?php echo h($record['Course']['title']); ?>&nbsp;</td>
				<td>
					<div class="soap-item"><b>S</b>:&nbsp;<?php echo h($record['Soap']['S']); ?>&nbsp;</div>
					<div class="soap-item"><b>O</b>:&nbsp;<?php echo h($record['Soap']['O']); ?>&nbsp;</div>
					<div class="soap-item"><b>A</b>:&nbsp;<?php echo h($record['Soap']['A']); ?>&nbsp;</div>
					<div class="soap-item"><b>P</b>:&nbsp;<?php echo h($record['Soap']['P']); ?>&nbsp;</div>
					<div class="soap-item"><b>自由記述</b>:&nbsp;<?php echo h($record['Soap']['comment']); ?>&nbsp;</div>
				</td>
			</tr>
		<?php endforeach; ?>
		</tbody>
	</table>
	<?php echo $this->element('paging');?>
	</div>
</div>
