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

function setTodayDate(){
		$("#EnqueteCmd").val("today");
		$("#EnqueteAdminIndexForm").submit();
		$("#EnqueteCmd").val("");
}

function dis_item(obj, className){
	var i;
	var elements = document.getElementsByClassName(className);
	if( obj.checked ){
		for( i = 0; i < elements.length; i++){
			elements[i].style.display = "";
		}
	}else{
		for( i = 0; i < elements.length; i++){
			elements[i].style.display = "none";
		}
	}
}
</script>
<?php $this->end(); ?>
<div class="admin-records-index full-view">
	<div class="ib-page-title"><?php echo __('アンケート'); ?></div>
	<div class="ib-horizontal">
		<?php
			echo $this->Form->create('Enquete');
			echo '<div class="ib-search-buttons">';
			echo $this->Form->submit(__('検索'),	array('class' => 'btn btn-primary', 'div' => false));
			echo $this->Form->hidden('cmd');
			echo '<button type="button" class="btn btn-info" onclick="setTodayDate()">'.__('今日').'</button>';
			echo '<button type="button" class="btn btn-secondary" onclick="downloadCSV()">'.__('CSV出力').'</button>';
			echo '</div>';

			echo '<div class="ib-row">';

			echo $this->Form->input('group_id',	array(
				'label' => 'グループ :',
				'options'=>$groups,
				'selected'=>$group_id,
				'empty' => '全て',
				'required'=>false,
				'div' => array(
					'class' => false
				),
				'between' => '<div class = "input select required">',
				'after' => '</div>',
				'class'=>'form-control'
			));

			echo $this->Form->input('name',	array(
				'label' => '受講生氏名・番号 :',
				'value'=>$name,
				'div' => array(
					'class' => false
				),
				'between' => '<div class = "input text required">',
				'after' => '</div>',
				'class'=>'form-control'
			));
			echo $this->Form->input('period',	array(
				'label' => '受講時間帯 :',
				'options'=>$period_list,
				'selected'=>$period,
				'empty' => '全て',
				'required'=>false,
				'div' => array(
					'class' => false
				),
				'between' => '<div class = "input select required">',
				'after' => '</div>',
				'class'=>'form-control'));
			echo '</div>';

			//echo "<div class = white-width></div>";

			echo '<div class="ib-search-date-container form-inline">';
			echo $this->Form->input('from_date', array(
				'type' => 'date',
				'dateFormat' => 'YMD',
				'monthNames' => false,
				'timeFormat' => '24',
				'minYear' => date('Y') - 5,
				'maxYear' => date('Y'),
				'separator' => ' / ',
				'label'=> array(
					'text' => '対象日時 : ',
					'style' => 'position : relative; left: -11px;'
				),
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
				'label'=> array(
					'text' => '～',
					'style' => 'position : relative; left: -12px;'
				),
				'class'=>'form-control',
				'style' => 'display: inline;',
				'value' => $to_date
			));
			echo '</div>';
		?>
	</div>

	<div class="ib-horizontal">
		<label>表示項目：</label>
		<?php
			echo $this->Form->input('dis_before_goal_cleared', array(
				'type' => 'checkbox',
				'label' => '前回T/F',
				'class' => 'form-control',
				'style' => 'display: inline;',
				'onclick' => 'dis_item(this,"beforeGoalCleared")',
			));

			echo $this->Form->input('dis_before_false_reason', array(
				'type' => 'checkbox',
				'label' => '前回F理由',
				'class' => 'form-control',
				'style' => 'display: inline;',
				'onclick' => 'dis_item(this,"beforeFalseReason")',
			));

			echo $this->Form->input('dis_next_goal', array(
				'type' => 'checkbox',
				'label' => '次回までゴール',
				'class' => 'form-control',
				'style' => 'display: inline;',
				'onclick' => 'dis_item(this,"nextGoal")',
			));

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
				<th nowrap class="beforeGoalCleared" style="display : none;"><?php echo $this->Paginator->sort('Enquete.before_goal_cleared', '前回T/F'); ?></th>
				<th nowrap class="beforeFalseReason" style="display : none;"><?php echo $this->Paginator->sort('Enquete.before_false_reason', '前回F理由'); ?></th>
				<th nowrap><?php echo $this->Paginator->sort('Enquete.today_goal', '今日のゴール'); ?></th>
				<th nowrap><?php echo $this->Paginator->sort('Enquete.today_goal_cleared', '今日T/F'); ?></th>
				<th nowrap><?php echo $this->Paginator->sort('Enquete.today_false_reason', '今日F理由'); ?></th>
				<th nowrap class="nextGoal" style="display : none;"><?php echo $this->Paginator->sort('Enquete.next_goal', '次回までゴール'); ?></th>
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
				<td nowrap>
					<?php
						echo $this->Html->link(h($record['User']['name']),
							array(
								'controller' => 'recentstates',
								'action' => 'admin_student_view', $record['User']['id']
						));
					?>
				</td>
				<td nowrap><?php echo h($groups[$record['Enquete']['group_id']]); ?>&nbsp;</td>
				<td nowrap><?php echo h($period_list[$record['User']['period']]); ?>&nbsp;</td>
				<td><div class="text-item"><?php echo h($record['Enquete']['today_impressions']); ?></div></td>
				<td nowrap class="beforeGoalCleared" style="display : none;"><?php echo h($TF_list[$record['Enquete']['before_goal_cleared']]); ?>&nbsp;</td>
				<td class="beforeFalseReason" style="display : none;"><?php echo h($record['Enquete']['before_false_reason']); ?>&nbsp;</td>
				<td><?php echo h($record['Enquete']['today_goal']); ?>&nbsp;</td>
				<td nowrap><?php echo h($TF_list[$record['Enquete']['today_goal_cleared']]); ?>&nbsp;</td>
				<td><?php echo h($record['Enquete']['today_false_reason']); ?>&nbsp;</td>
				<td class="nextGoal" style="display : none;"><?php echo h($record['Enquete']['next_goal']); ?>&nbsp;</td>
			</tr>
		<?php endforeach; ?>
		</tbody>
	</table>

	<?php echo $this->element('paging');?>
	</div>
</div>
