<div class="usersCourses form">
<?php echo $this->Form->create('UsersCourse'); ?>
	<fieldset>
		<legend><?php echo __('Add Users Course'); ?></legend>
	<?php
		echo $this->Form->input('user_id');
		echo $this->Form->input('course_id');
		echo $this->Form->input('started');
		echo $this->Form->input('ended');
		echo $this->Form->input('comment');
	?>
	</fieldset>
<?php echo $this->Form->end(__('•Û‘¶')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<?php echo $this->element('admin_menu');?>
	</ul>
</div>
