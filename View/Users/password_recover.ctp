<div class="password-recover">
	<div class="panel panel-info form-signin">
		<div class="panel-heading">
			パスワード再設定
		</div>
		<div class="panel-body">
			<?php echo $this->Form->create('User', array('action' => 'password_recover')); ?>
			<div class="form-group">
				<?php echo $this->Form->input('email', array('label' => __('メールアドレス'), 'class'=>'form-control')); ?>
			</div>
			<?php echo $this->Form->end('Password_Recover', array('class'=>'btn btn-lg btn-primary btn-block')); ?>
		</div>
	</div>
</div>
