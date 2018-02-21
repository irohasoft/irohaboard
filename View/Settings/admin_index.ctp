<?php echo $this->element('admin_menu');?>
<div class="settings index">
	<div class="panel panel-default">
		<div class="panel-heading">
			<?php echo __('システム設定'); ?>
		</div>
		<div class="panel-body">
			<?php echo $this->Form->create('Setting', Configure::read('form_defaults')); ?>
			<?php
				echo $this->Form->input('title',		array('label' => 'システム名', 'value'=>SessionHelper::read('Setting.title')));
				echo $this->Form->input('copyright',	array('label' => 'コピーライト', 'value'=>SessionHelper::read('Setting.copyright')));
				echo $this->Form->input('color',		array('label' => 'テーマカラー', 'options'=>$colors, 'selected'=>$color));
				echo $this->Form->input('information',	array('label' => '全体のお知らせ', 'value'=>SessionHelper::read('Setting.information'), 'type' => 'textarea'));
			?>
			<div class="form-group">
				<div class="col-sm-offset-3 col col-md-9 col-sm-8">
					<?php echo $this->Form->end(array('label' => __('保存'), 'class' => 'btn btn-primary')); ?>
				</div>
			</div>
			<?php echo $this->Form->end(); ?>
		</div>
	</div>
	
</div>
