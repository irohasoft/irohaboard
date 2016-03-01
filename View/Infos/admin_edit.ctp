<?php echo $this->element('admin_menu');?>
<?php echo $this->Html->css( 'select2.min.css');?>
<?php echo $this->Html->script( 'select2.min.js');?>

<div class="infos form">
<?php echo $this->Html->link(__('<< 戻る'), array('action' => 'index'))?>
<?php echo $this->Form->create('Info'); ?>
	<fieldset>
		<legend><?php echo ($this->action == 'admin_edit') ? __('編集') :  __('新規お知らせ'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('title',	array('label' => __('タイトル')));
		echo $this->Form->input('body',		array('label' => __('本文')));
	?>
	</fieldset>
	
	<?php echo $this->Form->end(__('保存')); ?>
</div>
