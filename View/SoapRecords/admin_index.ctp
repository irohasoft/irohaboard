<?php echo $this->element('admin_menu');?>

<div class="admin-soaprecords-index">
	<div class="ib-page-title"><?php echo __('SOAP一覧'); ?></div>
	<div class="ib-horizontal">
		<?php echo $this->Form->create('Soap'); ?>
		<div class="ib-search-buttons">
			<?php
				echo $this->Form->submit(__('検索'), array('class' => 'btn btn-info', 'div' => false));
				echo $this->Form->hidden('cmd');
			?>
		</div>

		<?php echo $this->Form->end(); ?>
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
				$period = "1限";
			} elseif($record['User']['period'] == 1) {
				$period = "2限";
			} else {
				$period = "時限未設定";
			}
		?>
			<tr>
				<td class="ib-col-date"><?php echo h(Utils::getYMD($record['Soap']['created'])); ?>&nbsp;</td>
				<td nowrap class="text-center"><?php echo h($record['User']['name']); ?>&nbsp;</td>
				<td nowrap class="text-center"><?php echo h($record['Group']['title']); ?>&nbsp;</td>
				<td nowrap class="text-center"><?php echo h($period); ?>&nbsp;</td>
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
