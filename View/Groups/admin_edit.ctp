<?php echo $this->element('admin_menu');?>
<div class="groups form">
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
				echo $this->Form->input('comment',	array('label' => __('備考')));
				/*
				echo $this->Form->input('status',	array(
					'type' => 'radio',
					'before' => '<label class="col col-md-3 control-label">公開設定</label>',
					'separator'=>"　", 
					'disabled'=>false, 
					'legend' => false,
					'class' => false,
					'options' => Configure::read('group_status')
					)
				);
				*/
			?>
			<div class="form-group">
				<div class="col col-md-9 col-md-offset-3">
					<?php echo $this->Form->submit('保存', Configure::read('form_submit_defaults')); ?>
				</div>
			</div>
		</div>
	</div>
</div>