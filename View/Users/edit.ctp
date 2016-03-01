<?php echo $this->element('menu');?>
<div class="users form">
<?php echo $this->Html->link(__('<< 戻る'), array('action' => 'index'))?>
<?php echo $this->Form->create('User'); ?>
	<fieldset>
		<legend><?php echo __('編集') ?></legend>
	<?php
	echo $this->Form->input('password', array(
			'label' => 'パスワード'
	));
	?>
	</fieldset>
<?php echo $this->Form->end(__('保存')); ?>
</div>