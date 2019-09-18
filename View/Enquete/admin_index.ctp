<?php echo $this->element('admin_menu');?>
<?php echo $this->Html->css('enquete');?>
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
	<div class="ib-page-title"><?php echo __('アンケート一覧'); ?></div>
	<div class="ib-horizontal">
		<?php
			echo $this->Form->create('Enquete');
			echo '<div class="ib-search-buttons">';
			echo $this->Form->submit(__('検索'),	array('class' => 'btn btn-info', 'div' => false));
			echo $this->Form->hidden('cmd');
			//echo '<button type="button" class="btn btn-default" onclick="downloadCSV()">'.__('CSV出力').'</button>';
			echo '</div>';
			
			
			echo '<div class="ib-row">';
			echo $this->Form->input('group_id',		array(
				'label' => 'グループ :', 
				'options'=>$groups, 
				'selected'=>$group_id, 
				'empty' => '全て', 
				'required'=>false, 
				'class'=>'form-control'));
			
			echo $this->Form->input('name',			array(
				'label' => '氏名 :', 
				'value'=>$name, 
				'class'=>'form-control'));

			echo $this->Form->input('period',	array(
			'label' => '受講時間帯 :',
			'options'=>$period_list,
			'selected'=>$period,
			'empty' => '全て',
			'required'=>false,
			'class'=>'form-control'));


			echo '</div>';

			echo "<div class = white-width></div>";
			
			echo '<div class="ib-search-date-container form-inline">';
			echo $this->Form->input('from_date', array(
				'type' => 'date',
				'dateFormat' => 'YMD',
				'monthNames' => false,
				'timeFormat' => '24',
				'minYear' => date('Y') - 5,
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
				'minYear' => date('Y') - 5,
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
		
		<th nowrap　class="ib-col-center"><?php echo $this->Paginator->sort('Enquete.created','受講日')?>
		<th nowrap><?php echo $this->Paginator->sort('User.name', '氏名'); ?></th>
		<th nowrap><?php echo $this->Paginator->sort('Enquete.group_id', '担当講師'); ?></th>
		<th nowrap><?php echo $this->Paginator->sort('User.period', '所属'); ?></th>
		<th nowrap><?php echo $this->Paginator->sort('Enquete.today_impressions', '今日の感想'); ?></th>
		<th nowrap><?php echo $this->Paginator->sort('Enquete.before_goal_cleared', '前回ゴールT/F'); ?></th>
		<th nowrap><?php echo $this->Paginator->sort('Enquete.before_false_reason', '前回ゴールF理由'); ?></th>
		<th nowrap><?php echo $this->Paginator->sort('Enquete.today_goal', '今日のゴール'); ?></th>
		<th nowrap><?php echo $this->Paginator->sort('Enquete.today_goal_cleared', '今日のゴールT/F'); ?></th>
		<th nowrap><?php echo $this->Paginator->sort('Enquete.today_false_reason', '今日のゴールF理由'); ?></th>
		<th nowrap><?php echo $this->Paginator->sort('Enquete.next_goal', '次回までゴール'); ?></th>

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
		<td nowrap><?php echo h(Utils::getYMD($record['Enquete']['created'])); ?>&nbsp;</td>
		<td><?php echo h($record['User']['name']); ?>&nbsp;</td>
		<td><?php echo h($groups[$record['Enquete']['group_id']]); ?>&nbsp;</td>
		<td><?php echo h($period_list[$record['User']['period']]); ?>&nbsp;</td>
		<td><?php echo h($record['Enquete']['today_impressions']); ?>&nbsp;</td>
		<td><?php echo h($TF_list[$record['Enquete']['before_goal_cleared']]); ?>&nbsp;</td>
		<td><?php echo h($record['Enquete']['before_false_reason']); ?>&nbsp;</td>
		<td><?php echo h($record['Enquete']['today_goal']); ?>&nbsp;</td>
		<td><?php echo h($TF_list[$record['Enquete']['today_goal_cleared']]); ?>&nbsp;</td>
		<td><?php echo h($record['Enquete']['today_false_reason']); ?>&nbsp;</td>
		<td><?php echo h($record['Enquete']['next_goal']); ?>&nbsp;</td>
		

	</tr>
	<?php endforeach; ?>
	</tbody>
	</table>
	
	<?php echo $this->element('paging');?>
	</div>
</div>
