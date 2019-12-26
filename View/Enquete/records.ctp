<?php echo $this->element('menu');?>
<?php echo $this->Html->css('enquete');?>
<div class="admin-records-index full-view">
	<div class="ib-page-title"><?php echo __('アンケート履歴'); ?></div>
	<div class="ib-horizontal">

  <div class = white-width></div>
	<div class = "record-table">
	<table cellpadding="0" cellspacing="0">
		<thead>
			<tr>
				<th nowrap　class="ib-col-center"><?php echo $this->Paginator->sort('Enquete.created','受講日')?>
				<th nowrap><?php echo $this->Paginator->sort('Enquete.group_id', '担当講師'); ?></th>
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
				<td nowrap><?php echo h($groups[$record['Enquete']['group_id']]); ?>&nbsp;</td>
				<td nowrap><?php echo h($record['Enquete']['today_impressions']); ?>&nbsp;</td>
				<td nowrap><?php echo h($TF_list[$record['Enquete']['before_goal_cleared']]); ?>&nbsp;</td>
				<td><?php echo h($record['Enquete']['before_false_reason']); ?>&nbsp;</td>
				<td><?php echo h($record['Enquete']['today_goal']); ?>&nbsp;</td>
				<td nowrap><?php echo h($TF_list[$record['Enquete']['today_goal_cleared']]); ?>&nbsp;</td>
				<td><?php echo h($record['Enquete']['today_false_reason']); ?>&nbsp;</td>
				<td><?php echo h($record['Enquete']['next_goal']); ?>&nbsp;</td>
			</tr>
		<?php endforeach; ?>
		</tbody>
	</table>

	<?php echo $this->element('paging');?>
	</div>
</div>
