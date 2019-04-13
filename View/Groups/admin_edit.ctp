<?php echo $this->element('admin_menu');?>
<?php echo $this->Html->css( 'select2.min.css');?>
<?php echo $this->Html->script( 'select2.min.js');?>
<?php $this->Html->scriptStart(array('inline' => false)); ?>
	$(function (e) {
		$('#CourseCourse').select2({placeholder: "受講するコースを選択して下さい。(複数選択可)", closeOnSelect: <?php echo (Configure::read('close_on_select') ? 'true' : 'false'); ?>,});
	});
<?php $this->Html->scriptEnd(); ?>
<div class="admin-groups-edit">
<?php echo $this->Html->link(__('<< 戻る'), array('action' => 'index'))?>
	<div class="panel panel-default">
		<div class="panel-heading">
			<?php echo ($this->action == 'admin_edit') ? __('編集') :  __('新規グループ'); ?>
		</div>
		<div class="panel-body">
			<?php echo $this->Form->create('Group', Configure::read('form_defaults')); ?>
			<?php
				echo $this->Form->input('id');
				echo $this->Form->input('title',	array('label' => __('グループ名')));
				echo $this->Form->input('Course',	array('label' => __('受講コース'),		'size' => 20));
				echo $this->Form->input('comment',	array('label' => __('備考')));
			?>
			<div class="form-group">
				<div class="col col-sm-9 col-sm-offset-3">
					<?php echo $this->Form->submit('保存', Configure::read('form_submit_defaults')); ?>
				</div>
			</div>
			<?php echo $this->Form->end(); ?>
		</div>
	</div>
</div>