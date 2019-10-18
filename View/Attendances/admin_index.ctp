<?php echo $this->element('admin_menu');?>
<?php echo $this->Html->css('enquete');?>
<?php $this->start('script-embedded'); ?>
<script>
function downloadCSV()
{
	var url = '<?php echo Router::url(array('action' => 'csv')) ?>/' + $('#MembersEventEventId').val() + '/' + $('#MembersEventStatus').val() + '/' + $('#MembersEventUsername').val();
	$("#EnqueteCmd").val("csv");
	$("#EnqueteAdminIndexForm").submit();
	$("#EnqueteCmd").val("");
}
</script>
<?php $this->end(); ?>
<div class="admin-records-index">
	<div class="ib-page-title"><?php echo __('出欠席一覧'); ?></div>
	<div class="ib-horizontal">
		<?php
		
			echo $this->Form->create('Attendance');
			echo '<div class="ib-search-buttons">';
			echo $this->Form->submit(__('適用'),	array('class' => 'btn btn-info', 'div' => false));
			echo $this->Form->hidden('cmd');
			//echo '<button type="button" class="btn btn-default" onclick="downloadCSV()">'.__('CSV出力').'</button>';
			echo '</div>';

			echo '<div class="ib-row" >';
			echo '<div class="ib-search-date-container form-inline">';
			echo $this->Form->input('target_date',	array(
				'label' => '日付：',
				'options'=>$date_list,
				'selected'=>'',
				'empty' => '',
				'required'=>false,
				'style' => '',
				'value' => $target_date,
				'class'=>'form-control'));

			//授業時間設定
			//一限
			//$this->log($period1_from);
			echo $this->Form->input('period1_from', array(
				/*
				'label' => '１限：',
				'options'=>array(
					'09:00:00','09:10:00'
				),
				'selected'=>'',
				'empty' => '09:00:00',
				'required'=>false,
				'style' => '',
				'class'=>'form-control'
				*/
				'label' => '１限：', 
				'type' => 'datetime', 
				'dateFormat' => '', 
				'timeFormat' => '24', 
				'monthNames' => false, 
				'empty' => '',
				'value' => $period1_from,
				'class'=>'form-control',
				'interval' => 1
			));
			echo $this->Form->input('period1_to', array(
				'label' => array(
					'text' => '〜',
					'style' => 'position:relative; left:-15px'
				),
				'type' => 'datetime', 
				'dateFormat' => '', 
				'timeFormat' => '24', 
				'monthNames' => false, 
				'empty' => '',
				'value' => $period1_to,
				'class'=>'form-control',
				'interval' => 1
			));
			//二限
			echo $this->Form->input('period2_from', array(
				'label' => '２限：',
				'type' => 'datetime', 
				'dateFormat' => '', 
				'timeFormat' => '24', 
				'monthNames' => false, 
				'empty' => '',
				'value' => $period2_from,
				'class'=>'form-control',
				'interval' => 1
			));
			echo $this->Form->input('period2_to', array(
				'label' => array(
					'text' => '〜',
					'style' => 'position:relative; left:-15px'
				),
				'type' => 'datetime', 
				'dateFormat' => '', 
				'timeFormat' => '24', 
				'monthNames' => false, 
				'empty' => '',
				'value' => $period2_to,
				'class'=>'form-control',
				'interval' => 1
			));
			/*
			echo $this->Form->input('group_id',	array(
				'label' => 'グループ :',
				'options'=>$groups,
				'selected'=>$group_id,
				'empty' => '全て',
				'required'=>false,
				'class'=>'form-control'));

			echo $this->Form->input('name',	array(
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
			));*/
			echo '</div>';
			echo $this->Form->end();
		?>
	</div>
	
	<div class = "record-table">
	<table cellpadding="0" cellspacing="0">
		<thead>
			<tr>
			<th nowrap　class="ib-col-center"><?php echo __('氏名');?>
			<?php foreach ($date_list as $date):?>
				<th nowrap><?php echo $date;?></th>
			<?php endforeach;?>
			</tr>
		</thead>
		<tbody>
		<?php //$this->log(count($date_list));?>
		<?php foreach ($attendance_list as $attendance_info):?>
		<?php $user_id = $attendance_info[0]['Attendance']['user_id']?>
			<tr>
				<td nowrap><?php echo h($name_list[$user_id]); ?>&nbsp;</td>
				<?php
					//$this->log(count($attendance_info));
					$no_info_number = count($date_list) - count($attendance_info);
					for($i = 0; $i < $no_info_number; $i++){
						echo "<td nowrap>&nbsp;</td>";
					}
					foreach ($attendance_info as $row):
						if($row['Attenadnce']['status'] == 0){
							$mark = '×';
						}
						if($row['Attendance']['status'] == 1){
							if($row['Attendance']['late_time'] != 0){
								$late_time = $row['Attendance']['late_time'];
								$mark = '△'."($late_time)";
							}else{
								$mark = '○';
							}
						}
					
				?>
				<td nowrap><span style = "font-size : 15pt"><?php echo h($mark); ?>&nbsp;</span></td>
				<?php endforeach; ?>
			</tr>
		<?php endforeach; ?>
		</tbody>
	</table>

	<?php echo $this->element('paging');?>
	</div>
</div>
