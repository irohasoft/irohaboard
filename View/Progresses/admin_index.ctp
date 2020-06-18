<?php echo $this->element('menu')?>
<?php $this->start('script-embedded'); ?>
<script>

</script>
<?php $this->end(); ?>
<div class="admin-courses-index full-view">
	<div class="ib-page-title"><?php echo __('成果発表一覧'); ?></div>
	<div class="buttons_container">
		<button type="button" class="btn btn-primary btn-add" onclick="location.href='<?php echo Router::url(array('action' => 'add')) ?>'">+ 追加</button>
	</div>

	<table>
	<thead>
	<tr>
		<th nowrap><?php echo __('タイトル'); ?></th>
		<th nowrap class="ib-col-datetime"><?php echo __('作成日時'); ?></th>
		<th nowrap class="ib-col-datetime"><?php echo __('更新日時'); ?></th>
		<th class="ib-col-action"><?php echo __('Actions'); ?></th>
	</tr>
	</thead>
	<tbody>
  <?php foreach ($progress_list as $progress): 
  $this->log($progress);
  ?>
	<tr>
		<td nowrap>
			<?php
				echo $this->Html->link($progress['Progress']['title'], array('controller' => 'progressDetail', 'action' => 'index', $progress['Progress']['id']));
				echo $this->Form->hidden('id', array('id'=>'', 'class'=>'progress_id', 'value'=>$progress['Progress']['id']));
			?>
		</td>
		<td nowrap class="ib-col-datetime"><?php echo h(Utils::getYMDHN($progress['Progress']['created'])); ?>&nbsp;</td>
		<td nowrap class="ib-col-datetime"><?php echo h(Utils::getYMDHN($progress['Progress']['modified'])); ?>&nbsp;</td>
		<td class="ib-col-action">
			<?php
			if($loginedUser['role']=='admin')
			{
				echo $this->Form->postLink(__('削除'),
					array('action' => 'delete', $progress['Progress']['id']),
					array('class'=>'btn btn-danger'),
					__('[%s] を削除してもよろしいですか?', $progress['Progress']['title'])
				);
			}?>
			<button type="button" class="btn btn-success" onclick="location.href='<?php echo Router::url(array('action' => 'edit', $progress['Progress']['id'])) ?>'">編集</button>

		</td>
	</tr>
	<?php endforeach; ?>
	</tbody>
	</table>
</div>
