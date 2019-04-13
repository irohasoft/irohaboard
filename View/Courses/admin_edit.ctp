<?php echo $this->element('admin_menu');?>
<div class="admin-courses-edit">
<?php echo $this->Html->link(__('<< 戻る'), array('action' => 'index'))?>
	<div class="panel panel-default">
		<div class="panel-heading">
			<?php echo ($this->action == 'admin_edit') ? __('編集') :  __('新規コース'); ?>
		</div>
		<div class="panel-body">
			<?php echo $this->Form->create('Course', Configure::read('form_defaults')); ?>
			<?php
				echo $this->Form->input('id');
				echo $this->Form->input('title',	array('label' => __('コース名')));
				/*
				echo $this->Form->input('opened',	array(
					'type' => 'datetime',
					'dateFormat' => 'YMD',
					'monthNames' => false,
					'timeFormat' => '24',
					'separator' => ' - ',
					'label'=> '公開日時',
					'style' => 'width:initial; display: inline;'
				));
				*/
				echo $this->Form->input('introduction',	array('label' => __('コース紹介')));
				echo $this->Form->input('comment',		array('label' => __('備考')));
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