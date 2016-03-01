<?php echo $this->element('admin_menu');?>
<div class="users form">
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