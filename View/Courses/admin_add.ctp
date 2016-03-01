<?php echo $this->element('admin_menu');?>
<div class="courses form">
<?php echo $this->Html->link(__('<< 戻る'), array('action' => 'index'))?>
<?php echo $this->Form->create('Course'); ?>
	<fieldset>
		<legend><?php echo __('新規コース'); ?></legend>
	<?php
		echo $this->Form->input('title',   array('label' => 'コース名'));
		//echo $this->Form->input('opened',  array('label' => '公開日'));
		echo $this->Form->input('comment', array('label' => '備考'));
	?>
	</fieldset>
<?php echo $this->Form->end(__('保存')); ?>
</div>
