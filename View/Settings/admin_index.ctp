<?php echo $this->element('admin_menu');?>
<div class="admin-settings-index full-view">
	<div class="card bg-light">
		<div class="card-header">
			<?php echo __('システム設定'); ?>
		</div>
		<div class="card-body">
			<?php echo $this->Form->create('Setting', Configure::read('form_defaults_bs4')); ?>
			<?php
				echo $this->Form->input('title',		array('label' => 'システム名',					'value'=>$settings['title']));
				echo $this->Form->input('copyright',	array('label' => 'コピーライト',				'value'=>$settings['copyright']));
				echo $this->Form->input('color',		array('label' => 'テーマカラー',				'options'=>$colors, 'selected'=>$settings['color']));
				echo $this->Form->input('information',	array('label' => '全体のお知らせ',				'value'=>$settings['information'], 'type' => 'textarea'));
			?>
			<div class="form-group">
				<div class="col col-sm-12 col-sm-offset-3">
					<?php
						echo $this->Form->submit(__('保存'), Configure::read('form_submit_defaults'));
						echo $this->Form->end();
					?>
				</div>
			</div>
			<?php echo $this->Form->end(); ?>
		</div>
	</div>
</div>
