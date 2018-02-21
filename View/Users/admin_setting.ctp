<?php echo $this->element('admin_menu');?>
<div class="users form">
	<div class="panel panel-default">
		<div class="panel-heading">
			設定
		</div>
		<div class="panel-body">
			<?php
				echo $this->Form->create('User', Configure::read('form_defaults'));
				echo $this->Form->input('User.new_password', array(
					'label' => 'パスワード',
					'type' => 'password',
					'autocomplete' => 'off'
				));
				echo $this->Form->input('User.new_password2', array(
					'label' => 'パスワード (確認用)',
					'type' => 'password',
					'autocomplete' => 'off'
				));
			?>
			<div class="form-group">
				<div class="col col-md-9 col-md-offset-3">
					<?php echo $this->Form->submit('保存', Configure::read('form_submit_defaults')); ?>
				</div>
			</div>
			<?php echo $this->Form->end(); ?>
		</div>
	</div>
</div>