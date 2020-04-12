<?php echo $this->element('admin_menu');?>
<?php echo $this->Html->css('attendance');?>
<?php $this->start('script-embedded'); ?>
<script>
$(function () {
	$('[data-toggle="tooltip"]').tooltip({
		boundary: 'window',
		trigger: 'focus hover',
		html: true
	});
});

function downloadCSV()
{
	var url = '<?php echo Router::url(array('action' => 'csv')) ?>/' + $('#MembersEventEventId').val() + '/' + $('#MembersEventStatus').val() + '/' + $('#MembersEventUsername').val();
	$("#EnqueteCmd").val("csv");
	$("#EnqueteAdminIndexForm").submit();
	$("#EnqueteCmd").val("");
}
</script>
<?php $this->end(); ?>
<div class="admin-records-index full-view">
	<div class="ib-page-title"><?php echo __('出欠席'); ?></div><br/><br/>

	<div class = "ib-row">
	  <span style = "margin-right : 20px" ><?php echo "受講日：".$last_day;?></span>
		<span style = "margin-right : 20px" ><?php echo "１限：".$cnt_1."名";?></span>
		<span style = "margin-right : 20px" ><?php echo "出席：".$att_1."名";?></span>
		<span style = "margin-right : 20px" ><?php echo "欠席：".$abs_1."名";?></span>
		<span style = "margin-right : 20px" ><?php echo "出席率：".round(($att_1 / $cnt_1) * 100)."%";?></span>
	</div>

	<?php //１限のリスト?>
	<div class = "record-table" style = "margin-bottom : 20px">
	<table cellpadding="0" cellspacing="0">
		<thead>
			<tr>
				<th nowrap class="non-last-column ib-col-center"><?php echo __('受講生番号');?></th>
				<th nowrap class="non-last-column"><?php echo __('氏名');?></th>
			<?php
				$no = 0;
				$length = count($date_list);
				foreach($date_list as $date){
					// 最後の要素
					if(++$no == $length){
						echo '<th nowrap class="last-column">'.h($date).'</th>';
					}else{
						echo '<th nowrap class="non-last-column">'.h($date).'</th>';
					}
				}
			?>
			</tr>
		</thead>
		<tbody>
		<?php
		foreach ($period1_members as $member):
			$user_id = $member['User']['id'];
			$attendance_info = $attendance_list[$user_id];
			$img_src = $this->Html->url(array(
				"controller" => "users",
				"action" => "show_picture",
				$user_id
			), false);
		?>
			<tr>
				<td nowrap class="ib-col-center"><?php echo h($username_list[$user_id]); ?>&nbsp;</td>
				<td nowrap>
					<span data-toggle="tooltip" title='<img src="<?php echo $img_src; ?>" height="150" alt="<?php echo $name_list[$user_id]; ?>"/>'>
						<?php echo h($name_list[$user_id]); ?>&nbsp;
					</span>
				</td>
				<?php
					foreach ($attendance_info as $row):
						switch($row['Attendance']['status']):
							case 0:  // 欠席
								$color = 'red';
								$mark  = '×';
								break;
							case 1:  // 出席済
								if($row['Attendance']['late_time'] != 0){
									$late_time = $row['Attendance']['late_time'];
									$color = 'green';
									$mark  = '△'."($late_time)";
								}else{
									$color = 'blue';
									$mark  = '○';
								}
								break;
							case 2:  // 未定
								$color = 'orange';
								$mark  = '?';
								break;
							case 3:  // 遅刻予定
								$color = 'green';
								$mark  = '△';
								break;
							case 4:  // 早退予定
								$color = 'blue';
								$mark  = '○(!)';
								break;
							case 5:  // 時限変更予定
								$color = 'blue';
								$mark  = '○(←→)';
								break;
						endswitch;
				?>
				<td nowrap><span style = "font-size : 15pt">
					<?php
						$attendance_id = $row['Attendance']['id'];
						echo $this->Html->link(__($mark),
							array(
								'controller' => 'attendances',
								'action' => 'admin_edit', $user_id, $attendance_id
							),
							array(
								'style' => 'color:'.$color.';'
						));
					?>
				</span></td>
				<?php endforeach; ?>
				<?php
					$no_info_number = count($date_list) - count($attendance_info);
					for($i = 0; $i < $no_info_number; $i++){
						echo "<td nowrap>&nbsp;</td>";
					}
				?>
			</tr>
		<?php endforeach; ?>
		</tbody>
	</table>
	</div>


	<div class = "ib-row" style = "margin-bottom : 10px">
	  <span style = "margin-right : 20px" ><?php echo "受講日：".$last_day;?></span>
		<span style = "margin-right : 20px" ><?php echo "２限：".$cnt_2."名";?></span>
		<span style = "margin-right : 20px" ><?php echo "出席：".$att_2."名";?></span>
		<span style = "margin-right : 20px" ><?php echo "欠席：".$abs_2."名";?></span>
		<span style = "margin-right : 20px" ><?php echo "出席率：".round(($att_2 / $cnt_2) * 100)."%";?></span>
	</div>

	<?php //２限のリスト?>
	<div class = "record-table" style = "margin-top : 30px">
	<table cellpadding="0" cellspacing="0">
		<thead>
			<tr>
				<th nowrap class="non-last-column ib-col-center"><?php echo __('受講生番号');?></th>
				<th nowrap class="non-last-column"><?php echo __('氏名');?></th>
			<?php
				$no = 0;
				$length = count($date_list);
				foreach($date_list as $date){
					// 最後の要素
					if(++$no == $length){
						echo '<th nowrap class="last-column">'.h($date).'</th>';
					}else{
						echo '<th nowrap class="non-last-column">'.h($date).'</th>';
					}
				}
			?>
			</tr>
		</thead>
		<tbody>
		<?php
		foreach ($period2_members as $member):
			$user_id = $member['User']['id'];
			$attendance_info = $attendance_list[$user_id];
			$img_src = $this->Html->url(array(
				"controller" => "users",
				"action" => "show_picture",
				$user_id
			), false);
		?>
			<tr>
				<td nowrap class="ib-col-center"><?php echo h($username_list[$user_id]); ?>&nbsp;</td>
				<td nowrap>
					<span data-toggle="tooltip" title='<img src="<?php echo $img_src; ?>" height="150" alt="<?php echo $name_list[$user_id]; ?>"/>'>
						<?php echo h($name_list[$user_id]); ?>&nbsp;
					</span>
				</td>
				<?php
					foreach ($attendance_info as $row):
						switch($row['Attendance']['status']):
							case 0:  // 欠席
								$color = 'red';
								$mark  = '×';
								break;
							case 1:  // 出席済
								if($row['Attendance']['late_time'] != 0){
									$late_time = $row['Attendance']['late_time'];
									$color = 'green';
									$mark  = '△'."($late_time)";
								}else{
									$color = 'blue';
									$mark  = '○';
								}
								break;
							case 2:  // 未定
								$color = 'orange';
								$mark  = '?';
								break;
							case 3:  // 遅刻予定
								$color = 'green';
								$mark  = '△';
								break;
							case 4:  // 早退予定
								$color = 'orange';
								$mark  = '?(!)';
								break;
							case 5:  // 時限変更予定
								$color = 'orange';
								$mark  = '?(←→)';
								break;
						endswitch;
				?>
				<td nowrap><span style = "font-size : 15pt">
					<?php
						$attendance_id = $row['Attendance']['id'];
						echo $this->Html->link(__($mark),
							array(
								'controller' => 'attendances',
								'action' => 'admin_edit', $user_id, $attendance_id
							),
							array(
								'style' => 'color:'.$color.';'
						));
					?>
				</span></td>
				<?php endforeach; ?>
				<?php
					$no_info_number = count($date_list) - count($attendance_info);
					for($i = 0; $i < $no_info_number; $i++){
						echo "<td nowrap>&nbsp;</td>";
					}
				?>
			</tr>
		<?php endforeach; ?>
		</tbody>
	</table>
	</div>
</div>
