<?php echo $this->element('menu')?>
<?php $this->start('script-embedded'); ?>
<script>

</script>
<?php $this->end(); ?>
<div class="progress-index full-view my-3">
  <div class="card">
    <div class="card-header"><?php echo __('成果発表一覧'); ?></div>
    <div class="card-body">
      <table>
	      <thead>
	      <tr>
	      	<th nowrap><?php echo __('タイトル'); ?></th>
	      	<th nowrap class="ib-col-datetime"><?php echo __('作成日時'); ?></th>
	      	<th nowrap class="ib-col-datetime"><?php echo __('更新日時'); ?></th>
	      </tr>
	      </thead>
	      <tbody>
        <?php foreach ($progress_list as $progress): ?>
	      <tr>
	      	<td nowrap>
	      		<?php
	      			echo $this->Html->link($progress['Progress']['title'], array('controller' => 'progressesDetails', 'action' => 'index', $progress['Progress']['id']));
	      			echo $this->Form->hidden('id', array('id'=>'', 'class'=>'progress_id', 'value'=>$progress['Progress']['id']));
	      		?>
	      	</td>
	      	<td nowrap class="ib-col-datetime"><?php echo h(Utils::getYMDHN($progress['Progress']['created'])); ?>&nbsp;</td>
	      	<td nowrap class="ib-col-datetime"><?php echo h(Utils::getYMDHN($progress['Progress']['modified'])); ?>&nbsp;</td>
        
	      </tr>
	      <?php endforeach; ?>
	      </tbody>
	    </table>
    </div>
  </div>
	
</div>
