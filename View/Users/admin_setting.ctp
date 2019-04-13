<?php echo $this->element('admin_menu');?>
<div class="admin-users-setting">
	<div class="panel panel-default">
		<div class="panel-heading">
			設定
		</div>
		<div class="panel-body">
			<?php
				echo $this->Form->create('User', Configure::read('form_defaults'));
				echo $this->Form->input('User.new_password', array(
					'label' => '新しいパスワード',
					'type' => 'password',
					'autocomplete' => 'new-password'
				));
				echo $this->Form->input('User.new_password2', array(
					'label' => '新しいパスワード (確認用)',
					'type' => 'password',
					'autocomplete' => 'new-password'
				));
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